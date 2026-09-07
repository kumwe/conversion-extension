<?php
declare(strict_types=1);
$root = dirname(__DIR__);
$composer = json_decode(file_get_contents($root . '/composer.json'), true, 512, JSON_THROW_ON_ERROR);
$namespace = array_key_first($composer['autoload']['psr-4']);
$allowed = ["Kumwe\\Contribution\\", "Kumwe\\Conversion\\Contract\\", "Kumwe\\Conversion\\Value\\"];
$mapping = json_decode(file_get_contents($root . '/resources/migration/source-map.json'), true, 512, JSON_THROW_ON_ERROR);
foreach ($mapping as $entry) {
    if (hash_file('sha256', $root . '/' . $entry['target_path']) !== $entry['target_sha256']) {
        throw new RuntimeException('Extracted source digest differs from the migration map: ' . $entry['target_path']);
    }
}
$expected = array_column($mapping, 'new_fqcn'); sort($expected);
$actual = [];
foreach (glob($root . '/src/*.php') as $path) {
    $bytes = file_get_contents($path);
    $name = $namespace . basename($path, '.php'); $actual[] = $name;
    foreach (token_get_all($bytes, TOKEN_PARSE) as $token) {
        if (!is_array($token)) { continue; }
        if (in_array($token[0], [T_EVAL, T_INCLUDE, T_INCLUDE_ONCE, T_REQUIRE, T_REQUIRE_ONCE], true)) { throw new RuntimeException('Executable loading in ' . $path); }
        if (!in_array($token[0], [T_NAME_QUALIFIED, T_NAME_FULLY_QUALIFIED], true)) { continue; }
        $reference = ltrim($token[1], '\\');
        if (!str_starts_with($reference, 'Kumwe\\') || $reference === rtrim($namespace, '\\') || str_starts_with($reference, $namespace)) { continue; }
        $permitted = false;
        foreach ($allowed as $prefix) { if (str_starts_with($reference, $prefix)) { $permitted = true; } }
        if (!$permitted) { throw new RuntimeException('Forbidden dependency ' . $reference); }
    }
    if (preg_match('/class_alias|PresentationPreference|Registrar|ContainerInterface|ConfigProvider/', $bytes) === 1) { throw new RuntimeException('Host authority leaked into ' . $path); }
}
sort($actual);
if ($actual !== $expected) { throw new RuntimeException('Source closure differs from migration map.'); }
$services = json_decode(file_get_contents($root . '/resources/service-map/v1.json'), true, 512, JSON_THROW_ON_ERROR);
if ($services['services'] !== [] || $services['providers'] !== []) { throw new RuntimeException('Pure declarations must not export container services.'); }
echo count($actual) . " types; source closure and dependency boundary verified.\n";
