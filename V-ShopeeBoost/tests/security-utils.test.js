'use strict';

const assert = require('node:assert/strict');
const os = require('node:os');
const path = require('node:path');
const test = require('node:test');

const {
  parseAllowedExternalUrl,
  parseOAuthCallbackParams,
  redactSecrets,
  resolveInside,
  validateBackupName,
  validateChromeStoreId,
  validateExtensionId,
  validatePageName,
  validateProfileName
} = require('../security-utils');

test('profile names accept normal names and reject traversal/reserved names', () => {
  assert.equal(validateProfileName('Shop Việt Nam 01'), 'Shop Việt Nam 01');
  for (const value of ['..', '.', '../outside', 'a/b', 'a\\b', 'CON', 'name.', '']) {
    assert.throws(() => validateProfileName(value));
  }
});

test('backup and page names reject traversal', () => {
  assert.equal(validateBackupName('backup-2026'), 'backup-2026');
  assert.equal(validatePageName('create-profile'), 'create-profile');
  assert.throws(() => validateBackupName('../../config'));
  assert.throws(() => validatePageName('../admin/login'));
});

test('resolveInside never escapes the selected base', () => {
  const base = path.join(os.tmpdir(), 'v-shopeeboost-test');
  assert.equal(resolveInside(base, 'profile', 'config.json'), path.join(base, 'profile', 'config.json'));
  assert.throws(() => resolveInside(base, '..', 'outside.txt'));
  assert.throws(() => resolveInside(base, path.parse(base).root, 'outside.txt'));
});

test('external URLs only allow explicitly selected protocols', () => {
  assert.equal(parseAllowedExternalUrl('https://vmaxtech.vn/'), 'https://vmaxtech.vn/');
  assert.throws(() => parseAllowedExternalUrl('file:///C:/Windows/System32/calc.exe'));
  assert.throws(() => parseAllowedExternalUrl('javascript:alert(1)'));
  assert.throws(() => parseAllowedExternalUrl('https://user:pass@example.com/'));
});

test('OAuth callbacks accept one token/state from query or fragment only', () => {
  assert.deepEqual(
    parseOAuthCallbackParams('vshopeeboost://auth/#access_token=token-123&state=state-123'),
    { accessToken: 'token-123', state: 'state-123' }
  );
  assert.deepEqual(
    parseOAuthCallbackParams('vshopeeboost://auth/?access_token=token-456&state=state-456'),
    { accessToken: 'token-456', state: 'state-456' }
  );
  assert.throws(() => parseOAuthCallbackParams('https://auth/?access_token=a&state=b'));
  assert.throws(() => parseOAuthCallbackParams('vshopeeboost://evil/#access_token=a&state=b'));
  assert.throws(() => parseOAuthCallbackParams('vshopeeboost://auth/#access_token=a&state=b&state=c'));
  assert.throws(() => parseOAuthCallbackParams('vshopeeboost://auth/#access_token=a'));
});

test('extension ids use Chrome extension id alphabet', () => {
  assert.equal(validateExtensionId('abcdefghijklmnopabcdefghijklmnop'), 'abcdefghijklmnopabcdefghijklmnop');
  assert.equal(validateExtensionId('0123456789abcdef0123456789abcdef'), '0123456789abcdef0123456789abcdef');
  assert.throws(() => validateExtensionId('../../Extension'));
  assert.equal(validateChromeStoreId('abcdefghijklmnopabcdefghijklmnop'), 'abcdefghijklmnopabcdefghijklmnop');
  assert.throws(() => validateChromeStoreId('zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz'));
});

test('logs redact common secrets', () => {
  const value = redactSecrets('vshopeeboost://auth/#state=oauth-nonce&access_token=secret&password=hunter2 Authorization: Bearer abc.def {"access_token":"json-secret"}');
  assert.equal(value.includes('secret'), false);
  assert.equal(value.includes('hunter2'), false);
  assert.equal(value.includes('abc.def'), false);
  assert.equal(value.includes('json-secret'), false);
  assert.equal(value.includes('oauth-nonce'), false);
  assert.match(value, /\[REDACTED\]/);
});
