'use strict';
const fs = require('node:fs');
const path = require('node:path');
const crypto = require('node:crypto');
const assert = require('node:assert/strict');
const {execFileSync} = require('node:child_process');
const Ajv2020 = require('ajv/dist/2020').default;
const yaml = require('yaml');
const root = path.resolve(__dirname, '../..');
const files = {
  'resources/public-api/v1.json': 'package-public-api.v1.schema.json',
  'resources/capabilities/v1.json': 'package-capabilities.v1.schema.json',
  'resources/service-map/v1.json': 'package-service-map.v1.schema.json',
  'docs/release-record.md': 'package-release-record.v1.schema.json',
};
const ajv = new Ajv2020({allErrors: true, strict: false});
const validators = Object.fromEntries(Object.entries(files).map(([file, schema]) => [file,
  ajv.compile(JSON.parse(fs.readFileSync(path.join(__dirname, 'schemas', schema), 'utf8')))]));
function readDocuments(directory = root) {
  const result = {};
  for (const file of Object.keys(files)) {
    const text = fs.readFileSync(path.join(directory, file), 'utf8');
    if (file.endsWith('.md')) {
      assert(text.startsWith('---\n'), 'Release record must start with YAML front matter');
      const closing = text.indexOf('\n---\n', 4);
      assert(closing > 0, 'Release record closing front-matter fence is required');
      result[file] = yaml.parse(text.slice(4, closing), {uniqueKeys: true});
      const headings = [...text.slice(closing + 5).matchAll(/^## (.+)$/gm)].map(match => match[1]);
      assert.deepEqual(headings, ["Package contract","Public API and responsibility","Dependencies and semantic inputs","Consumer contract","Test ownership","Consumer verification","Compatibility and drift","Validation"]);
    } else result[file] = JSON.parse(text);
  }
  return result;
}
function validateSchemas(values) {
  for (const [file, validate] of Object.entries(validators)) {
    if (!validate(values[file])) throw new Error(file + ': ' + ajv.errorsText(validate.errors, {separator: '\n'}));
  }
}
function verifyRelations(values, directory = root) {
  const composer = JSON.parse(fs.readFileSync(path.join(directory, 'composer.json'), 'utf8'));
  const api = values['resources/public-api/v1.json'];
  const record = values['docs/release-record.md'];
  const names = Object.keys(api.symbols).sort();
  assert.equal(api.package, composer.name);
  assert.deepEqual(Object.keys(composer.autoload['psr-4']), [api.namespace]);
  for (const file of ['resources/capabilities/v1.json', 'resources/service-map/v1.json']) {
    assert.equal(values[file].package, api.package, 'Package identity drift');
    assert.equal(values[file].release, api.release, 'Release identity drift');
  }
  const caps = values['resources/capabilities/v1.json'];
  assert.equal(caps.namespace, api.namespace, 'Capability namespace drift');
  const services = values['resources/service-map/v1.json'];
  assert.equal(services.config_provider, null);
  assert.equal(typeof services.provider_absence_reason, 'string', 'Direct construction needs a provider absence reason');
  assert(services.provider_absence_reason.trim().length > 0);
  assert.deepEqual(services.factories, []);
  assert.deepEqual(services.aliases, {});
  assert.deepEqual(services.delegators, []);
  assert.deepEqual(services.configuration_keys, []);
  const covered = caps.capabilities.flatMap(capability => capability.symbols);
  assert.deepEqual(covered.slice().sort(), names, 'Every public symbol must have one capability owner');
  assert.equal(new Set(caps.capabilities.map(capability => capability.id)).size, caps.capabilities.length);
  for (const capability of caps.capabilities) for (const file of capability.documentation) {
    assert(fs.statSync(path.join(directory, file)).isFile(), 'Missing documentation: ' + file);
  }
  assert.equal(record.target.artifact_identity, composer.name);
  assert.equal(record.framework_php.canonical_namespace, api.namespace);
  assert.equal(record.framework_php.composer_package, composer.name);
  assert.equal(record.documentation.changelog_record, 'CHANGELOG.md / ' + api.release);
  const entries = record.framework_php.extracted_symbols;
  assert.deepEqual(entries.map(entry => entry.new_fqcn).sort(), names, 'Release record source mapping is incomplete');
  for (const entry of entries) {
    const symbol = api.symbols[entry.new_fqcn];
    assert.equal(entry.target_path, symbol.file);
    assert.equal(entry.kind, symbol.kind);
    for (const [key, member] of [['public_methods', 'methods'], ['public_properties', 'properties'], ['public_constants', 'constants']]) {
      assert.deepEqual(entry[key].slice().sort(), Object.keys(symbol[member]).sort(), entry.new_fqcn + ' record member drift');
    }
  }
  const digests = record.ownership.public_manifests;
  for (const file of Object.keys(files).filter(file => file.endsWith('.json'))) {
    assert.equal(digests.filter(entry => entry.path === file).length, 1, 'Missing or duplicate record digest: ' + file);
  }
  for (const entry of digests) {
    const actual = crypto.createHash('sha256').update(fs.readFileSync(path.join(directory, entry.path))).digest('hex');
    assert.equal(entry.sha256, actual, 'Release record digest differs: ' + entry.path);
  }
}
function verify() {
  const values = readDocuments();
  validateSchemas(values);
  verifyRelations(values);
  for (const tool of ['public-api.php', 'verify-governed-manifests.php', 'render-public-api.php']) {
    process.stdout.write(execFileSync('php', [path.join(root, 'tools', tool)], {cwd: root}));
  }
  console.log('All three authoritative package schemas, full discriminated record, source/docs and ownership relationships passed.');
}
module.exports = {readDocuments, validateSchemas, verifyRelations};
if (require.main === module) verify();
