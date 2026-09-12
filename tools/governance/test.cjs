'use strict';
const assert = require('node:assert/strict');
const {readDocuments, validateSchemas, verifyRelations} = require('./verify.cjs');
const original = readDocuments();
const api = 'resources/public-api/v1.json';
const caps = 'resources/capabilities/v1.json';
const services = 'resources/service-map/v1.json';
const record = 'docs/release-record.md';
validateSchemas(original);
verifyRelations(original);
const fixtures = [
  ['capability release is required', values => {delete values[caps].release;}, validateSchemas, /capabilities.*required property 'release'/s],
  ['legacy string capabilities are rejected', values => {values[caps].capabilities = ['conversion-extension'];}, validateSchemas, /capabilities.*must be object/s],
  ['empty native requirements are not null', values => {values[caps].native_requirements = [];}, validateSchemas, /capabilities.*native_requirements/s],
  ['legacy service map keys are rejected', values => {values[services].providers = [];}, validateSchemas, /service-map.*additional properties/s],
  ['provider absence requires a reason', values => {values[services].provider_absence_reason = null;}, verifyRelations, /provider absence reason/],
  ['namespace roots require their trailing separator', values => {values[record].source.app.old_namespace_roots[0] = 'Kumwe\\App';}, validateSchemas, /release-record.*old_namespace_roots/s],
  ['replacement maps must be explicit strings', values => {values[record].consumer_contract.namespace_or_api_replacements[0] = {from: 'Old', to: 'New'};}, validateSchemas, /release-record.*namespace_or_api_replacements/s],
  ['obsolete workflow state is rejected', values => {values[record].concurrency = {};}, validateSchemas, /release-record.*additional properties/s],
  ['package records cannot claim Core completion', values => {values[record].governance.completion_claim = true;}, validateSchemas, /release-record.*completion_claim/s],
  ['framework record requires its matching discriminated section', values => {values[record].framework_php = null;}, validateSchemas, /release-record.*framework_php/s],
  ['public method parameters reject undocumented schema fields', values => {values[api].symbols['Kumwe\\Conversion\\Contribution\\MoneyRateProviderDefinition'].methods.fromArray.parameters[0].unknown = true;}, validateSchemas, /public-api.*additional properties/s],
  ['canonical manifest version must be semantic', values => {values[api].release = 'latest';}, validateSchemas, /public-api.*release/s],
  ['unowned exported symbols are rejected', values => {values[caps].capabilities[0].symbols.pop();}, verifyRelations, /Every public symbol must have one capability owner/],
  ['duplicate capability ownership is rejected', values => {values[caps].capabilities[0].symbols.push(values[caps].capabilities[0].symbols[0]);}, verifyRelations, /Every public symbol must have one capability owner/],
  ['incomplete record callable inventory is rejected', values => {values[record].framework_php.extracted_symbols[0].public_methods.pop();}, verifyRelations, /record member drift/],
  ['stale record manifest bytes are rejected', values => {values[record].ownership.public_manifests[0].sha256 = '0'.repeat(64);}, verifyRelations, /Release record digest differs/],
  ['missing record manifest identity is rejected', values => {values[record].ownership.public_manifests.pop();}, verifyRelations, /Missing or duplicate record digest/],
];
for (const [description, mutate, validate, expected] of fixtures) {
  const values = structuredClone(original);
  mutate(values);
  assert.throws(() => validate(values), expected, description);
}
console.log('Governance refusal regressions passed: ' + fixtures.length + ' independent malformed or inconsistent documents.');
