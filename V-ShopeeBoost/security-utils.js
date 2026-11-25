'use strict';

const fs = require('fs');
const path = require('path');

const PROFILE_NAME_RE = /^[\p{L}\p{N}_ .-]{1,80}$/u;
const BACKUP_NAME_RE = /^[\p{L}\p{N}_ .-]{1,80}$/u;
const PAGE_NAME_RE = /^[a-z0-9-]{1,60}$/;
const EXTENSION_ID_RE = /^[a-z0-9_-]{1,64}$/;
const CHROME_STORE_ID_RE = /^[a-p]{32}$/;
const WINDOWS_RESERVED_RE = /^(con|prn|aux|nul|com[1-9]|lpt[1-9])(?:\.|$)/i;
const CONTROL_CHARS_RE = /[\u0000-\u001f\u007f]/;

function assertPlainObject(value, label = 'Dữ liệu') {
  if (!value || typeof value !== 'object' || Array.isArray(value)) {
    throw new TypeError(`${label} không hợp lệ`);
  }
  return value;
}

function validateLeafName(value, label, pattern) {
  if (typeof value !== 'string') throw new TypeError(`${label} không hợp lệ`);
  const normalized = value.normalize('NFC').trim();
  if (
    !pattern.test(normalized) ||
    normalized === '.' ||
    normalized === '..' ||
    normalized.endsWith('.') ||
    normalized.endsWith(' ') ||
    WINDOWS_RESERVED_RE.test(normalized) ||
    CONTROL_CHARS_RE.test(normalized) ||
    normalized.includes('/') ||
    normalized.includes('\\')
  ) {
    throw new TypeError(`${label} không hợp lệ`);
  }
  return normalized;
}

function validateProfileName(value) {
  return validateLeafName(value, 'Tên profile', PROFILE_NAME_RE);
}

function validateBackupName(value) {
  return validateLeafName(value, 'Tên backup', BACKUP_NAME_RE);
}

function validatePageName(value) {
  if (typeof value !== 'string' || !PAGE_NAME_RE.test(value)) {
    throw new TypeError('Tên trang không hợp lệ');
  }
  return value;
}

function validateExtensionId(value) {
  if (typeof value !== 'string' || !EXTENSION_ID_RE.test(value)) {
    throw new TypeError('Extension ID không hợp lệ');
  }
  return value;
}

function validateChromeStoreId(value) {
  if (typeof value !== 'string' || !CHROME_STORE_ID_RE.test(value)) {
    throw new TypeError('Chrome Web Store ID không hợp lệ');
  }
  return value;
}

function resolveInside(baseDir, ...segments) {
  const base = path.resolve(baseDir);
  const resolved = path.resolve(base, ...segments);
  const relative = path.relative(base, resolved);
  if (relative === '' || (!relative.startsWith('..') && !path.isAbsolute(relative))) {
    return resolved;
  }
  throw new Error('Đường dẫn nằm ngoài vùng dữ liệu cho phép');
}

function assertExistingFileInside(baseDir, filePath, allowedExtensions = []) {
  if (typeof filePath !== 'string' || !filePath.trim()) {
    throw new TypeError('Đường dẫn tệp không hợp lệ');
  }
  const base = path.resolve(baseDir);
  const resolved = path.resolve(filePath);
  const relative = path.relative(base, resolved);
  if (relative.startsWith('..') || path.isAbsolute(relative)) {
    throw new Error('Tệp nằm ngoài vùng được phép');
  }
  if (!fs.existsSync(resolved) || !fs.statSync(resolved).isFile()) {
    throw new Error('Không tìm thấy tệp');
  }
  if (allowedExtensions.length && !allowedExtensions.includes(path.extname(resolved).toLowerCase())) {
    throw new Error('Định dạng tệp không được hỗ trợ');
  }
  return resolved;
}

function parseAllowedExternalUrl(value, protocols = ['https:']) {
  if (typeof value !== 'string' || value.length > 2048 || CONTROL_CHARS_RE.test(value)) {
    throw new TypeError('Liên kết không hợp lệ');
  }
  const parsed = new URL(value);
  if (!protocols.includes(parsed.protocol) || parsed.username || parsed.password) {
    throw new TypeError('Giao thức liên kết không được phép');
  }
  return parsed.toString();
}

function parseAllowedWebUrl(value, { allowHttp = false, hosts = null } = {}) {
  const protocols = allowHttp ? ['https:', 'http:'] : ['https:'];
  const normalized = parseAllowedExternalUrl(value, protocols);
  const parsed = new URL(normalized);
  if (
    hosts &&
    !hosts.some(host => parsed.hostname === host || parsed.hostname.endsWith(`.${host}`))
  ) {
    throw new TypeError('Tên miền không được phép');
  }
  return normalized;
}

function parseOAuthCallbackParams(value) {
  if (typeof value !== 'string' || value.length > 16384 || CONTROL_CHARS_RE.test(value)) {
    throw new TypeError('OAuth callback không hợp lệ');
  }

  const parsed = new URL(value);
  if (
    parsed.protocol !== 'vshopeeboost:' ||
    parsed.hostname.toLowerCase() !== 'auth' ||
    (parsed.pathname !== '' && parsed.pathname !== '/') ||
    parsed.username ||
    parsed.password
  ) {
    throw new TypeError('OAuth callback không hợp lệ');
  }

  const queryParams = parsed.searchParams;
  const fragmentParams = new URLSearchParams(parsed.hash.startsWith('#') ? parsed.hash.slice(1) : '');
  const readSingle = (name, maxLength) => {
    const values = [...queryParams.getAll(name), ...fragmentParams.getAll(name)];
    if (
      values.length !== 1 ||
      !values[0] ||
      values[0].length > maxLength ||
      CONTROL_CHARS_RE.test(values[0])
    ) {
      throw new TypeError(`OAuth ${name} không hợp lệ`);
    }
    return values[0];
  };

  return {
    accessToken: readSingle('access_token', 8192),
    state: readSingle('state', 256)
  };
}

function redactSecrets(value) {
  if (typeof value !== 'string') return value;
  return value
    .replace(/([?#&](?:key|api[_-]?key|password|pass|token|access_token|license_key|oauth[_-]?state|state)=)[^&#\s]*/gi, '$1[REDACTED]')
    .replace(/("(?:key|api[_-]?key|password|pass|token|access_token|license_key|oauth[_-]?state|state)"\s*:\s*")[^"]*"/gi, '$1[REDACTED]"')
    .replace(/\b(Bearer|Basic)\s+[A-Za-z0-9+/_=.-]+/gi, '$1 [REDACTED]')
    .replace(/([a-z][a-z0-9+.-]*:\/\/)([^:@/\s]+):([^@/\s]+)@/gi, '$1[REDACTED]:[REDACTED]@');
}

function atomicWriteFileSync(filePath, contents, encoding = 'utf8') {
  const dir = path.dirname(filePath);
  fs.mkdirSync(dir, { recursive: true });
  const tempPath = resolveInside(dir, `.${path.basename(filePath)}.${process.pid}.${Date.now()}.tmp`);
  try {
    fs.writeFileSync(tempPath, contents, { encoding, mode: 0o600 });
    fs.renameSync(tempPath, filePath);
  } finally {
    try {
      if (fs.existsSync(tempPath)) fs.unlinkSync(tempPath);
    } catch {}
  }
}

function atomicWriteJsonSync(filePath, value) {
  atomicWriteFileSync(filePath, JSON.stringify(value, null, 2));
}

function safeErrorMessage(error, fallback = 'Đã xảy ra lỗi') {
  const raw = error && typeof error.message === 'string' ? error.message : fallback;
  return redactSecrets(raw).slice(0, 500);
}

// ── PARSE PROXY STRING (v6.0.0 Pro Max) ──────────────────────────────────
function parseProxyString(proxyStr, defaultType = 'http') {
  if (!proxyStr || typeof proxyStr !== 'string' || proxyStr.length > 2000 || CONTROL_CHARS_RE.test(proxyStr)) return null;
  let str = proxyStr.trim();
  if (!str) return null;

  let type = (defaultType || 'http').toLowerCase();
  if (type === 'socks') type = 'socks5';

  // Tách scheme nếu có (http://, https://, socks5://, socks4://, socks://)
  if (str.includes('://')) {
    const parts = str.split('://');
    type = parts[0].toLowerCase();
    if (type === 'socks') type = 'socks5';
    str = parts.slice(1).join('://').trim();
  }

  let host = '', port = 80, user = '', pass = '';

  if (str.includes('@')) {
    // Định dạng user:pass@host:port
    const lastAtIndex = str.lastIndexOf('@');
    const authPart = str.substring(0, lastAtIndex);
    const serverPart = str.substring(lastAtIndex + 1);

    if (authPart.includes(':')) {
      const firstColon = authPart.indexOf(':');
      user = authPart.substring(0, firstColon);
      pass = authPart.substring(firstColon + 1);
    } else {
      user = authPart;
    }

    if (serverPart.includes(':')) {
      const lastColon = serverPart.lastIndexOf(':');
      host = serverPart.substring(0, lastColon);
      port = parseInt(serverPart.substring(lastColon + 1), 10);
    } else {
      return null;
    }
  } else {
    // Định dạng host:port:user:pass hoặc host:port
    const parts = str.split(':');
    if (parts.length < 2) return null;
    host = parts[0] ? parts[0].trim() : '';
    port = parseInt(parts[1], 10);
    if (parts.length >= 4) {
      user = parts[2] ? parts[2].trim() : '';
      pass = parts.slice(3).join(':');
    }
  }

  host = host.trim().replace(/^\[|\]$/g, '');
  if (user) user = user.trim();
  if (pass) pass = pass.trim();

  if (!host || isNaN(port) || port < 1 || port > 65535) {
    return null;
  }

  const hasAuth = Boolean(user && pass);

  let serverArg = `${host}:${port}`;
  if (type === 'socks5') {
    serverArg = `socks5://${host}:${port}`;
  } else if (type === 'socks4') {
    serverArg = `socks4://${host}:${port}`;
  } else if (type === 'https') {
    serverArg = `https://${host}:${port}`;
  } else {
    serverArg = `http://${host}:${port}`;
  }

  const formattedProxy = hasAuth ? `${host}:${port}:${user}:${pass}` : `${host}:${port}`;

  return {
    raw: proxyStr,
    type,
    host,
    port,
    user,
    pass,
    hasAuth,
    serverArg,
    formattedProxy,
    auth: hasAuth ? { username: user, password: pass } : null
  };
}

// ── PROXY AUTH PAC FILE ─────────────────────────────────────────────────
function createProxyAuthPacFile(profilePath, proxy, proxyType = 'http') {
  const parsed = parseProxyString(proxy, proxyType);
  if (!parsed) return null;
  const proxyHost = parsed.host;
  const proxyPort = parsed.port;

  let scheme = 'PROXY';
  if (parsed.type === 'socks5') scheme = 'SOCKS5';
  else if (parsed.type === 'socks4') scheme = 'SOCKS';
  else if (parsed.type === 'https') scheme = 'HTTPS';

  const proxyStr = `${scheme} ${proxyHost}:${proxyPort}`;
  const pacPath = path.join(profilePath, 'proxy-auth.pac');
  const pacFallback = path.join(profilePath, 'proxy.pac');
  const pacContent = `function FindProxyForURL(url, host) {
  if (shExpMatch(host, "127.0.0.1") || shExpMatch(host, "localhost") || isPlainHostName(host)) {
    return "DIRECT";
  }
  return "${proxyStr}; DIRECT";
}
`;
  try {
    fs.writeFileSync(pacPath, pacContent, 'utf8');
    fs.writeFileSync(pacFallback, pacContent, 'utf8');
    return pacPath;
  } catch {
    return null;
  }
}

// ── CLEAN PREFERENCES & ANTI-TELEMETRY ──────────────────────────────────
function ensureCleanPreferences(userDataDir) {
  try {
    const defaultDir = path.join(userDataDir, 'Default');
    fs.mkdirSync(defaultDir, { recursive: true });
    const prefsPath = path.join(defaultDir, 'Preferences');

    let prefs = {};
    if (fs.existsSync(prefsPath)) {
      try {
        prefs = JSON.parse(fs.readFileSync(prefsPath, 'utf8'));
      } catch {
        prefs = {};
      }
    }

    if (!prefs.profile || typeof prefs.profile !== 'object') prefs.profile = {};
    prefs.profile.exit_type = 'Normal';
    prefs.profile.exited_cleanly = true;

    if (!prefs.browser || typeof prefs.browser !== 'object') prefs.browser = {};
    prefs.browser.has_seen_welcome_page = true;
    prefs.browser.check_default_browser = false;

    if (!prefs.translate || typeof prefs.translate !== 'object') prefs.translate = {};
    prefs.translate.enabled = false;

    if (!prefs.safebrowsing || typeof prefs.safebrowsing !== 'object') prefs.safebrowsing = {};
    prefs.safebrowsing.enabled = false;

    if (!prefs.metrics || typeof prefs.metrics !== 'object') prefs.metrics = {};
    prefs.metrics.reporting_enabled = false;

    if (!prefs.webrtc || typeof prefs.webrtc !== 'object') prefs.webrtc = {};
    prefs.webrtc.ip_handling_policy = 'disable_non_proxied_udp';

    atomicWriteJsonSync(prefsPath, prefs);
    return true;
  } catch {
    return false;
  }
}

const COUNTRY_GEO_MAP = {
  VN: { timezone: 'Asia/Ho_Chi_Minh', lang: 'vi-VN,vi;q=0.9,en-US;q=0.8,en;q=0.7' },
  TH: { timezone: 'Asia/Bangkok', lang: 'th-TH,th;q=0.9,en-US;q=0.8,en;q=0.7' },
  SG: { timezone: 'Asia/Singapore', lang: 'en-SG,en;q=0.9,zh-CN;q=0.8,ms;q=0.7' },
  MY: { timezone: 'Asia/Kuala_Lumpur', lang: 'ms-MY,ms;q=0.9,en-US;q=0.8,en;q=0.7' },
  ID: { timezone: 'Asia/Jakarta', lang: 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7' },
  PH: { timezone: 'Asia/Manila', lang: 'en-PH,fil-PH;q=0.9,en;q=0.8' },
  TW: { timezone: 'Asia/Taipei', lang: 'zh-TW,zh;q=0.9,en-US;q=0.8,en;q=0.7' },
  US: { timezone: 'America/New_York', lang: 'en-US,en;q=0.9' }
};

module.exports = {
  assertExistingFileInside,
  assertPlainObject,
  atomicWriteFileSync,
  atomicWriteJsonSync,
  createProxyAuthPacFile,
  COUNTRY_GEO_MAP,
  ensureCleanPreferences,
  parseAllowedExternalUrl,
  parseOAuthCallbackParams,
  parseAllowedWebUrl,
  parseProxyString,
  redactSecrets,
  resolveInside,
  safeErrorMessage,
  validateBackupName,
  validateChromeStoreId,
  validateExtensionId,
  validatePageName,
  validateProfileName
};
