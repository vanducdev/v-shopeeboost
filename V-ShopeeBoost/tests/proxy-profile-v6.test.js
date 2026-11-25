'use strict';

const assert = require('node:assert/strict');
const fs = require('node:fs');
const os = require('node:os');
const path = require('node:path');
const test = require('node:test');

const {
  parseProxyString,
  createProxyAuthPacFile,
  ensureCleanPreferences,
  COUNTRY_GEO_MAP
} = require('../security-utils');

const ROOT = path.resolve(__dirname, '..');

// ── 1. PROXY PARSING TESTS (ALL FORMATS) ──────────────────────────────────
test('parseProxyString: parses IP:Port standard format', () => {
  const parsed = parseProxyString('103.152.118.23:8080');
  assert.notEqual(parsed, null);
  assert.equal(parsed.host, '103.152.118.23');
  assert.equal(parsed.port, 8080);
  assert.equal(parsed.type, 'http');
  assert.equal(parsed.hasAuth, false);
  assert.equal(parsed.serverArg, 'http://103.152.118.23:8080');
  assert.equal(parsed.formattedProxy, '103.152.118.23:8080');
});

test('parseProxyString: parses IP:Port:User:Pass format', () => {
  const parsed = parseProxyString('103.152.118.23:8080:shopee_user:secret_pass');
  assert.notEqual(parsed, null);
  assert.equal(parsed.host, '103.152.118.23');
  assert.equal(parsed.port, 8080);
  assert.equal(parsed.user, 'shopee_user');
  assert.equal(parsed.pass, 'secret_pass');
  assert.equal(parsed.hasAuth, true);
  assert.equal(parsed.serverArg, 'http://103.152.118.23:8080');
  assert.equal(parsed.formattedProxy, '103.152.118.23:8080:shopee_user:secret_pass');
});

test('parseProxyString: parses User:Pass@IP:Port format', () => {
  const parsed = parseProxyString('vip_user:vip_pass@192.168.1.100:3128');
  assert.notEqual(parsed, null);
  assert.equal(parsed.host, '192.168.1.100');
  assert.equal(parsed.port, 3128);
  assert.equal(parsed.user, 'vip_user');
  assert.equal(parsed.pass, 'vip_pass');
  assert.equal(parsed.hasAuth, true);
  assert.equal(parsed.serverArg, 'http://192.168.1.100:3128');
  assert.equal(parsed.formattedProxy, '192.168.1.100:3128:vip_user:vip_pass');
});

test('parseProxyString: parses SOCKS5 with scheme and auth', () => {
  const parsed = parseProxyString('socks5://proxyuser:proxypass@45.76.100.20:1080');
  assert.notEqual(parsed, null);
  assert.equal(parsed.type, 'socks5');
  assert.equal(parsed.host, '45.76.100.20');
  assert.equal(parsed.port, 1080);
  assert.equal(parsed.user, 'proxyuser');
  assert.equal(parsed.pass, 'proxypass');
  assert.equal(parsed.hasAuth, true);
  assert.equal(parsed.serverArg, 'socks5://45.76.100.20:1080');
});

test('parseProxyString: parses SOCKS5 with IP:Port:User:Pass', () => {
  const parsed = parseProxyString('socks5://45.76.100.20:1080:proxyuser:proxypass');
  assert.notEqual(parsed, null);
  assert.equal(parsed.type, 'socks5');
  assert.equal(parsed.host, '45.76.100.20');
  assert.equal(parsed.port, 1080);
  assert.equal(parsed.user, 'proxyuser');
  assert.equal(parsed.pass, 'proxypass');
  assert.equal(parsed.serverArg, 'socks5://45.76.100.20:1080');
});

test('parseProxyString: handles defaultType parameter', () => {
  const parsed = parseProxyString('45.76.100.20:1080', 'socks5');
  assert.notEqual(parsed, null);
  assert.equal(parsed.type, 'socks5');
  assert.equal(parsed.serverArg, 'socks5://45.76.100.20:1080');
});

test('parseProxyString: handles passwords containing colons', () => {
  const parsedAuthAt = parseProxyString('myuser:pass:with:colons@10.0.0.1:8080');
  assert.notEqual(parsedAuthAt, null);
  assert.equal(parsedAuthAt.user, 'myuser');
  assert.equal(parsedAuthAt.pass, 'pass:with:colons');

  const parsedFourParts = parseProxyString('10.0.0.1:8080:myuser:pass:with:colons');
  assert.notEqual(parsedFourParts, null);
  assert.equal(parsedFourParts.user, 'myuser');
  assert.equal(parsedFourParts.pass, 'pass:with:colons');
});

test('parseProxyString: rejects invalid inputs', () => {
  assert.equal(parseProxyString(''), null);
  assert.equal(parseProxyString(null), null);
  assert.equal(parseProxyString('invalid_no_port'), null);
  assert.equal(parseProxyString('127.0.0.1:99999'), null); // Port > 65535
  assert.equal(parseProxyString('127.0.0.1:-5'), null);
  assert.equal(parseProxyString('127.0.0.1:abc'), null);
});

// ── 2. PAC FILE GENERATION TESTS ──────────────────────────────────────────
test('createProxyAuthPacFile: creates valid PAC for HTTP and SOCKS5', () => {
  const tmpDir = fs.mkdtempSync(path.join(os.tmpdir(), 'pac-test-'));
  try {
    const httpPac = createProxyAuthPacFile(tmpDir, '1.2.3.4:8080', 'http');
    assert.ok(fs.existsSync(httpPac));
    const httpContent = fs.readFileSync(httpPac, 'utf8');
    assert.match(httpContent, /PROXY 1\.2\.3\.4:8080/);
    assert.match(httpContent, /DIRECT/);

    const socksPac = createProxyAuthPacFile(tmpDir, 'socks5://1.2.3.4:1080');
    assert.ok(fs.existsSync(socksPac));
    const socksContent = fs.readFileSync(socksPac, 'utf8');
    assert.match(socksContent, /SOCKS5 1\.2\.3\.4:1080/);
  } finally {
    fs.rmSync(tmpDir, { recursive: true, force: true });
  }
});

// ── 3. CLEAN PREFERENCES & ANTI-TELEMETRY TESTS ───────────────────────────
test('ensureCleanPreferences: initializes clean profile with anti-telemetry', () => {
  const tmpDir = fs.mkdtempSync(path.join(os.tmpdir(), 'pref-test-'));
  try {
    const res = ensureCleanPreferences(tmpDir);
    assert.equal(res, true);
    const prefsPath = path.join(tmpDir, 'Default', 'Preferences');
    assert.ok(fs.existsSync(prefsPath));

    const prefs = JSON.parse(fs.readFileSync(prefsPath, 'utf8'));
    assert.equal(prefs.profile.exit_type, 'Normal');
    assert.equal(prefs.profile.exited_cleanly, true);
    assert.equal(prefs.browser.has_seen_welcome_page, true);
    assert.equal(prefs.browser.check_default_browser, false);
    assert.equal(prefs.translate.enabled, false);
    assert.equal(prefs.safebrowsing.enabled, false);
    assert.equal(prefs.metrics.reporting_enabled, false);
    assert.equal(prefs.webrtc.ip_handling_policy, 'disable_non_proxied_udp');
  } finally {
    fs.rmSync(tmpDir, { recursive: true, force: true });
  }
});

// ── 4. EXTENSION MANIFEST INTEGRITY ───────────────────────────────────────
test('manifest.json: has version 6.0.0, required permissions, and web_accessible_resources', () => {
  const manifestPath = path.join(ROOT, 'Extension-ShopeeBoost', 'manifest.json');
  assert.ok(fs.existsSync(manifestPath));
  const manifest = JSON.parse(fs.readFileSync(manifestPath, 'utf8'));

  assert.equal(manifest.version, '6.0.0');
  assert.ok(manifest.permissions.includes('webRequestAuthProvider'));
  assert.ok(manifest.permissions.includes('privacy'));

  // Verify web_accessible_resources includes fingerprint.json
  const war = manifest.web_accessible_resources;
  assert.ok(Array.isArray(war));
  const hasFingerprintJson = war.some(entry => entry.resources && entry.resources.includes('fingerprint.json'));
  assert.ok(hasFingerprintJson, 'manifest.json must declare fingerprint.json in web_accessible_resources');
});

// ── 5. VERSION CONSISTENCY CHECK (v6.0.0 Pro Max) ─────────────────────────
test('version consistency: package.json, manifest.json, background.js, and UI files', () => {
  const pkg = JSON.parse(fs.readFileSync(path.join(ROOT, 'package.json'), 'utf8'));
  assert.equal(pkg.version, '6.0.0');

  const bg = fs.readFileSync(path.join(ROOT, 'Extension-ShopeeBoost', 'background.js'), 'utf8');
  assert.match(bg, /const APP_VERSION = '6\.0\.0 Pro Max';/);

  const popup = fs.readFileSync(path.join(ROOT, 'Extension-ShopeeBoost', 'popup.js'), 'utf8');
  assert.match(popup, /const APP_VERSION = '6\.0\.0 Pro Max';/);

  const splash = fs.readFileSync(path.join(ROOT, 'splash.html'), 'utf8');
  assert.match(splash, /v6\.0\.0 Pro Max/);

  const indexHtml = fs.readFileSync(path.join(ROOT, 'index.html'), 'utf8');
  assert.match(indexHtml, /v6\.0\.0 Pro Max/);
});

// ── 6. GEOIP ALIGNMENT MAP ────────────────────────────────────────────────
test('COUNTRY_GEO_MAP provides correct timezones for major Shopee regions', () => {
  assert.equal(COUNTRY_GEO_MAP.VN.timezone, 'Asia/Ho_Chi_Minh');
  assert.equal(COUNTRY_GEO_MAP.TH.timezone, 'Asia/Bangkok');
  assert.equal(COUNTRY_GEO_MAP.SG.timezone, 'Asia/Singapore');
  assert.equal(COUNTRY_GEO_MAP.MY.timezone, 'Asia/Kuala_Lumpur');
  assert.equal(COUNTRY_GEO_MAP.ID.timezone, 'Asia/Jakarta');
  assert.equal(COUNTRY_GEO_MAP.PH.timezone, 'Asia/Manila');
  assert.equal(COUNTRY_GEO_MAP.TW.timezone, 'Asia/Taipei');
});
