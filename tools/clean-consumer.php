<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$manifest = json_decode(file_get_contents($root . '/composer.json'), true, 512, JSON_THROW_ON_ERROR);
$temporary = sys_get_temp_dir() . '/kumwe-' . basename($root) . '-' . bin2hex(random_bytes(6));
mkdir($temporary, 0700, true);
$run = static function (array $command, string $directory): void {
    $process = proc_open($command, [STDIN, STDOUT, STDERR], $pipes, $directory);
    if (!is_resource($process) || proc_close($process) !== 0) {
        throw new RuntimeException('Consumer command failed: ' . implode(' ', $command));
    }
};
$run(['composer', 'archive', '--format=zip', '--dir=' . $temporary, '--file=candidate', '--no-interaction'], $root);
$archive = $temporary . '/candidate.zip';
$package = $manifest;
unset($package['require-dev'], $package['autoload-dev'], $package['scripts'], $package['archive']);
$package['version'] = 'dev-candidate';
$package['dist'] = ['type' => 'zip', 'url' => 'file://' . $archive, 'shasum' => sha1_file($archive)];
$repositories = [['type' => 'package', 'package' => $package]];
$require = [$manifest['name'] => 'dev-candidate'];
$sourceDependencies = json_decode(getenv('KUMWE_SOURCE_DEPENDENCIES') ?: '{}', true, 512, JSON_THROW_ON_ERROR);
$sourceRecords = [];
foreach ($sourceDependencies as $name => $dependency) {
    $path = realpath($dependency['path']);
    if ($path === false || !is_file($path . '/composer.json')) { throw new RuntimeException('Invalid dependency path.'); }
    $metadata = json_decode(file_get_contents($path . '/composer.json'), true, 512, JSON_THROW_ON_ERROR);
    if ($metadata['name'] !== $name) { throw new RuntimeException('Dependency identity mismatch.'); }
    $version = $dependency['version'] ?? 'dev-source';
    $repositories[] = ['type' => 'path', 'url' => $path, 'options' => ['symlink' => false, 'versions' => [$name => $version]]];
    $require[$name] = $dependency['version'] ?? ('dev-source as ' . $dependency['satisfies']);
    $sourceRecords[$name] = ['mode' => 'local-source-alias', 'path' => $path, 'candidate_coordinate' => $require[$name], 'composer_sha256' => hash_file('sha256', $path . '/composer.json')];
}
if ($sourceDependencies !== [] && !array_any($sourceDependencies, static fn(array $dependency): bool => isset($dependency['version']))) { $repositories[] = ['packagist.org' => false]; }
$consumer = ['name' => 'kumwe/isolated-consumer', 'require' => $require, 'repositories' => $repositories, 'minimum-stability' => 'dev', 'prefer-stable' => true];
file_put_contents($temporary . '/composer.json', json_encode($consumer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . "\n");
$run(['composer', 'install', '--no-dev', '--classmap-authoritative', '--no-scripts', '--no-plugins', '--no-interaction'], $temporary);
$installed = $temporary . '/vendor/' . $manifest['name'];
foreach (['src', 'resources/public-api/v1.json', 'resources/capabilities/v1.json', 'resources/service-map/v1.json', 'resources/migration/source-map.json', 'docs/release-record.md', 'examples/consumer.php'] as $path) {
    if (!file_exists($installed . '/' . $path)) { throw new RuntimeException('Archive missing ' . $path); }
}
foreach (['tests', 'tools', 'vendor', '.git'] as $path) {
    if (file_exists($installed . '/' . $path)) { throw new RuntimeException('Development content in package archive: ' . $path); }
}
$smoke = <<<'SMOKE'
<?php
declare(strict_types=1);
$loader = require 'vendor/autoload.php';
if (!$loader->isClassMapAuthoritative()) { throw new RuntimeException('Autoload not authoritative'); }
foreach (array_keys($loader->getClassMap()) as $name) {
    if (str_starts_with($name, 'Kumwe\\App\\') || str_starts_with($name, 'Kumwe\\Extension\\')) {
        throw new RuntimeException('Forbidden host dependency: ' . $name);
    }
}
require 'vendor/__PACKAGE__/examples/consumer.php';
SMOKE;
file_put_contents($temporary . '/smoke.php', str_replace('__PACKAGE__', $manifest['name'], $smoke));
$run([PHP_BINARY, 'smoke.php'], $temporary);
// Verify standalone CLI bootstrap as well as the supported preloaded consumer invocation.
$run([PHP_BINARY, $installed . '/examples/consumer.php', $temporary . '/vendor/autoload.php'], $temporary);
$invalid = proc_open(
    [PHP_BINARY, $installed . '/examples/consumer.php', $temporary . '/missing-autoload.php'],
    [STDIN, ['pipe', 'w'], ['pipe', 'w']],
    $pipes,
    $temporary,
);
if (!is_resource($invalid)) { throw new RuntimeException('Could not run invalid-autoload regression.'); }
$invalidOutput = stream_get_contents($pipes[1]);
$invalidError = stream_get_contents($pipes[2]);
fclose($pipes[1]);
fclose($pipes[2]);
if (proc_close($invalid) === 0
    || !str_contains($invalidOutput . $invalidError, 'Composer autoload file is missing or unreadable:')
    || str_contains($invalidOutput, 'Money and unit provider declarations matched real conversion requests.')) {
    throw new RuntimeException('Installed example did not refuse the missing explicit autoload path.');
}
echo "Installed example CLI bootstrap and missing-autoload refusal passed.\n";
$evidence = ['kind' => $sourceDependencies === [] ? 'archive-with-registry-dependencies' : 'archive-with-local-source-dependencies', 'release_attestation' => false, 'archive_sha256' => hash_file('sha256', $archive), 'consumer_directory' => $temporary, 'dependencies' => $sourceRecords];
file_put_contents($temporary . '/consumer-evidence.json', json_encode($evidence, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . "\n");
echo json_encode($evidence, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . "\n";
