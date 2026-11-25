/**
 * V-ShopeeBoost PRO MAX v6.0.0
 * main.js — Electron Main Process
 * © 2026 Vmax Software | Hotline: 0886.335.455
 */

const { app, BrowserWindow, ipcMain, dialog, shell, safeStorage, session, screen } = require('electron');
const path  = require('path');
const fs    = require('fs');
const net   = require('net');
const https = require('https');
const http  = require('http');
const crypto = require('crypto');
const { fileURLToPath } = require('url');
const { spawn, execFile, execFileSync } = require('child_process');
const { ExtensionRepository, ZIPInstaller, CRXInstaller, ChromeStoreInstaller, ExtensionManagerService } = require('./extension-services');
const {
  assertPlainObject,
  atomicWriteJsonSync,
  parseAllowedExternalUrl,
  parseOAuthCallbackParams,
  parseAllowedWebUrl,
  redactSecrets,
  resolveInside,
  safeErrorMessage,
  validateBackupName,
  validateChromeStoreId,
  validateExtensionId,
  validatePageName,
  validateProfileName
} = require('./security-utils');

const isDev = process.argv.includes('--dev');

// Chỉ cho phép bỏ qua chứng chỉ khi nhà phát triển chủ động bật cờ riêng.
// Production luôn xác minh TLS để ngăn chặn MITM đánh cắp license/token.
if (isDev && process.env.VSHOPEE_ALLOW_INSECURE_TLS === '1') {
  app.commandLine.appendSwitch('ignore-certificate-errors');
}

let mainWindow, splashWindow;
let queuedDeepLink = '';

function findDeepLinkArg(commandLine = process.argv) {
  return commandLine.find(arg => typeof arg === 'string' && arg.startsWith('vshopeeboost://')) || '';
}

function dispatchDeepLink(url) {
  if (typeof url !== 'string' || !url.startsWith('vshopeeboost://')) return;
  if (!app.isReady()) {
    queuedDeepLink = url;
    return;
  }
  void handleDeepLink(url);
}

// Đăng ký Custom Protocol: vshopeeboost://
if (process.defaultApp) {
  if (process.argv.length >= 2) {
    app.setAsDefaultProtocolClient('vshopeeboost', process.execPath, [path.resolve(process.argv[1])])
  }
} else {
  app.setAsDefaultProtocolClient('vshopeeboost')
}

// Khóa ứng dụng chạy đơn lẻ (Single Instance) để bắt Deep Link
const gotTheLock = app.requestSingleInstanceLock();
if (!gotTheLock) {
  app.quit();
} else {
  app.on('second-instance', (event, commandLine) => {
    if (mainWindow) {
      if (mainWindow.isMinimized()) mainWindow.restore();
      mainWindow.focus();
    }
    dispatchDeepLink(findDeepLinkArg(commandLine));
  });

  app.on('open-url', (event, url) => {
    event.preventDefault();
    dispatchDeepLink(url);
  });
}

// ── PATHS ──────────────────────────────────────────────────────────────────
const USER_DATA   = app.getPath('userData');
const PROFILES_DIR = path.join(USER_DATA, 'profiles');
const LOGS_DIR    = path.join(USER_DATA, 'logs');
const BACKUPS_DIR = path.join(USER_DATA, 'backups');
const CFG_FILE    = path.join(USER_DATA, 'config.json');
const GROUPS_FILE = path.join(USER_DATA, 'groups.json');
const SECRETS_FILE = path.join(USER_DATA, 'secrets.json');

[PROFILES_DIR, LOGS_DIR, BACKUPS_DIR].forEach(d => {
  if (!fs.existsSync(d)) fs.mkdirSync(d, { recursive: true });
});

// Helper for Groups
function sanitizeGroups(groups) {
  if (!Array.isArray(groups)) return [];
  const seen = new Set();
  const safeGroups = [];
  for (const group of groups.slice(0, 100)) {
    const rawName = typeof group === 'string' ? group : group?.name;
    if (typeof rawName !== 'string') continue;
    const name = rawName.normalize('NFC').trim().slice(0, 80);
    if (!name || /[<>"'`]/.test(name) || seen.has(name)) continue;
    seen.add(name);
    if (typeof group === 'string') safeGroups.push(name);
    else {
      const description = typeof group.description === 'string'
        ? group.description.normalize('NFC').trim().slice(0, 500).replace(/[<>]/g, '')
        : '';
      safeGroups.push({ name, description });
    }
  }
  return safeGroups;
}

function getGroups() {
  try {
    if (fs.existsSync(GROUPS_FILE)) {
      const groups = sanitizeGroups(JSON.parse(fs.readFileSync(GROUPS_FILE, 'utf8')));
      if (groups.length) return groups;
    }
  } catch {}
  return ['Mặc định'];
}
function saveGroups(groups) {
  try {
    atomicWriteJsonSync(GROUPS_FILE, groups);
    return true;
  } catch {
    return false;
  }
}


// ── LOG ────────────────────────────────────────────────────────────────────
const logBuffer = [];
function log(msg, type = 'info') {
  const ts  = new Date().toISOString();
  const safeMessage = redactSecrets(String(msg));
  const row = { message: safeMessage, type, timestamp: ts };
  logBuffer.push(row);
  if (logBuffer.length > 2000) logBuffer.shift();
  fs.appendFile(
    path.join(LOGS_DIR, `app-${ts.slice(0,10)}.log`),
    `[${ts}] [${type.toUpperCase()}] ${safeMessage}\n`,
    () => {}
  );
  if (mainWindow && !mainWindow.isDestroyed())
    mainWindow.webContents.send('log', row);
}

// ── CHROME FINDER ──────────────────────────────────────────────────────────
function findChrome() {
  const candidates = [
    // 1. Ưu tiên tìm ChromeCore141 / Chromium v120 đi kèm trong thư mục app
    path.join(__dirname, 'ChromeCore141', 'chrome.exe'),
    path.join(__dirname, '..', 'ChromeCore141', 'chrome.exe'),
    path.join(app.getAppPath(), 'ChromeCore141', 'chrome.exe'),
    path.join(app.getAppPath(), '..', 'ChromeCore141', 'chrome.exe'),
    path.join(process.resourcesPath || '', 'ChromeCore141', 'chrome.exe'),
    path.join(__dirname, 'chromium-v120', 'chromium.exe'),
    path.join(process.resourcesPath || '', 'chromium-v120', 'chromium.exe'),
    path.join(app.getAppPath(), 'chromium-v120', 'chromium.exe'),
    path.join(app.getAppPath(), '..', 'chromium-v120', 'chromium.exe'),

    // 2. Các đường dẫn Chrome hệ thống mặc định
    process.env['PROGRAMFILES']    ? path.join(process.env['PROGRAMFILES'],    'Google','Chrome','Application','chrome.exe') : '',
    process.env['PROGRAMFILES(X86)'] ? path.join(process.env['PROGRAMFILES(X86)'],'Google','Chrome','Application','chrome.exe') : '',
    process.env['LOCALAPPDATA']    ? path.join(process.env['LOCALAPPDATA'],    'Google','Chrome','Application','chrome.exe') : '',
    '/usr/bin/google-chrome',
    '/usr/bin/chromium',
    '/usr/bin/chromium-browser',
    '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
  ].filter(Boolean);
  return candidates.find(p => { try { return fs.existsSync(p); } catch { return false; } }) || '';
}

// ── APP CONFIG ─────────────────────────────────────────────────────────────
function getCfg() {
  try {
    if (fs.existsSync(CFG_FILE)) {
      const stored = JSON.parse(fs.readFileSync(CFG_FILE, 'utf8'));
      // Không bao giờ trả secret cũ về renderer. Migration được thực hiện sau app.ready.
      delete stored.gasPassword;
      delete stored.apiKey;
      return sanitizeConfig(stored, defaultCfg());
    }
  } catch {}
  return defaultCfg();
}
function defaultCfg() {
  return { 
    chromePath: '', 
    chromeArgs: '', 
    theme: 'light-clean', 
    language: 'vi',
    minimizeToTray: false, 
    autoRefresh: false, 
    startupPage: 'dashboard',
    nhamang: 'random', 
    tinhthanh: '0', 
    fontSize: 'medium', 
    firstRun: true,
    startUrl: 'https://whoer.net',
    shopeeDelay: 2,
    autoCheckProxy: true,
    maxRetries: 3,
    autoBackupOnClose: false,
    maxBackups: 5
  };
}
const CONFIG_KEYS = new Set(Object.keys(defaultCfg()).concat([
  'activeHwid', 'verifiedPlatformId', 'machineId', 'userEmail', 'userName', 'userAvatar',
  'licenseKey', 'licenseExpiry', 'licenseLastCheck', 'licenseSig'
]));

function sanitizeConfig(input, existing = defaultCfg()) {
  assertPlainObject(input, 'Cấu hình');
  const result = { ...existing };
  for (const [key, value] of Object.entries(input)) {
    if (!CONFIG_KEYS.has(key)) continue;
    if (typeof value === 'string') result[key] = value.slice(0, key === 'chromeArgs' ? 2000 : 500);
    else if (typeof value === 'boolean' || (typeof value === 'number' && Number.isFinite(value))) result[key] = value;
  }
  if (result.startUrl) {
    try { result.startUrl = parseAllowedWebUrl(result.startUrl, { allowHttp: false }); }
    catch { result.startUrl = defaultCfg().startUrl; }
  }
  const allowedThemes = new Set(['light-clean', 'light-warm', 'light-ocean', 'dark-blue', 'dark-purple', 'dark-green']);
  const allowedLanguages = new Set(['vi', 'en', 'th', 'id', 'ms', 'fil', 'zh-CN', 'ja', 'ko', 'es']);
  const allowedStartupPages = new Set(['dashboard', 'profiles', 'proxy', 'settings']);
  if (!allowedThemes.has(result.theme)) result.theme = defaultCfg().theme;
  if (!allowedLanguages.has(result.language)) result.language = defaultCfg().language;
  if (!allowedStartupPages.has(result.startupPage)) result.startupPage = defaultCfg().startupPage;
  return result;
}

function saveCfg(c) {
  const clean = sanitizeConfig(c, defaultCfg());
  atomicWriteJsonSync(CFG_FILE, clean);
}

function loadEncryptedSecrets() {
  try {
    if (!fs.existsSync(SECRETS_FILE) || !safeStorage.isEncryptionAvailable()) return {};
    const stored = JSON.parse(fs.readFileSync(SECRETS_FILE, 'utf8'));
    const result = {};
    for (const [key, encoded] of Object.entries(stored)) {
      if (typeof encoded === 'string') result[key] = safeStorage.decryptString(Buffer.from(encoded, 'base64'));
    }
    return result;
  } catch (error) {
    log(`Không thể đọc kho secret: ${safeErrorMessage(error)}`, 'warning');
    return {};
  }
}

function saveEncryptedSecret(key, value) {
  if (!safeStorage.isEncryptionAvailable()) throw new Error('Hệ điều hành không hỗ trợ mã hóa secret');
  const current = fs.existsSync(SECRETS_FILE)
    ? JSON.parse(fs.readFileSync(SECRETS_FILE, 'utf8'))
    : {};
  if (value) current[key] = safeStorage.encryptString(String(value)).toString('base64');
  else delete current[key];
  atomicWriteJsonSync(SECRETS_FILE, current);
}

function getEncryptedSecret(key) {
  return loadEncryptedSecrets()[key] || '';
}

const ENCRYPTED_VALUE_PREFIX = 'enc:v1:';

function encryptSensitiveValue(value) {
  if (!value || String(value).startsWith(ENCRYPTED_VALUE_PREFIX)) return value || '';
  if (!safeStorage.isEncryptionAvailable()) throw new Error('Hệ điều hành không hỗ trợ mã hóa dữ liệu nhạy cảm');
  return ENCRYPTED_VALUE_PREFIX + safeStorage.encryptString(String(value)).toString('base64');
}

function decryptSensitiveValue(value) {
  if (!value || typeof value !== 'string' || !value.startsWith(ENCRYPTED_VALUE_PREFIX)) return value || '';
  try {
    return safeStorage.decryptString(Buffer.from(value.slice(ENCRYPTED_VALUE_PREFIX.length), 'base64'));
  } catch {
    return '';
  }
}

function protectProfileRecord(record) {
  const protectedRecord = { ...record };
  if (typeof protectedRecord.proxy === 'string') protectedRecord.proxy = encryptSensitiveValue(protectedRecord.proxy);
  return protectedRecord;
}

function hydrateProfileRecord(record) {
  return { ...record, proxy:decryptSensitiveValue(record?.proxy) };
}

function migrateSensitiveProfileData() {
  try {
    if (!safeStorage.isEncryptionAvailable()) return;
    for (const name of fs.readdirSync(PROFILES_DIR)) {
      let safeName;
      try { safeName = validateProfileName(name); } catch { continue; }
      const configPath = resolveInside(PROFILES_DIR, safeName, 'config.json');
      if (!fs.existsSync(configPath)) continue;
      const record = JSON.parse(fs.readFileSync(configPath, 'utf8'));
      if (record.proxy && !String(record.proxy).startsWith(ENCRYPTED_VALUE_PREFIX)) {
        atomicWriteJsonSync(configPath, protectProfileRecord(record));
      }
    }
  } catch (error) {
    log(`Không thể migrate proxy sang kho mã hóa: ${safeErrorMessage(error)}`, 'warning');
  }
}

function migrateLegacySecrets() {
  try {
    if (!fs.existsSync(CFG_FILE)) return;
    const stored = JSON.parse(fs.readFileSync(CFG_FILE, 'utf8'));
    if (stored.gasPassword && !getEncryptedSecret('adminApiPassword')) {
      saveEncryptedSecret('adminApiPassword', stored.gasPassword);
      delete stored.gasPassword;
      atomicWriteJsonSync(CFG_FILE, sanitizeConfig(stored, defaultCfg()));
      log('Đã chuyển mật khẩu Admin sang kho mã hóa của hệ điều hành', 'info');
    }
  } catch (error) {
    log(`Không thể migrate secret cũ: ${safeErrorMessage(error)}`, 'warning');
  }
}

// ── RUNNING PROCESSES ───────────────────────────────────────────────────────
const running = {};

// ══════════════════════════════════════════════════════════════════════════
// LICENSE SYSTEM
// ══════════════════════════════════════════════════════════════════════════
// Vui lòng thay thế URL dưới đây bằng đường dẫn Web App Google Apps Script của bạn
const LICENSE_API_URL = 'https://app.vmaxtech.vn/api/license/check'; 

let isLicenseValid = false;
let licenseMsg = 'Chưa xác thực';

function requireLicense() {
  if (!isLicenseValid) {
    throw new Error('Bản quyền ứng dụng V-ShopeeBoost không hợp lệ hoặc đã hết hạn. Vui lòng kích hoạt.');
  }
}

let cachedHWID = null;

function getHWID() {
  if (cachedHWID) return cachedHWID;

  const cfg = getCfg();
  let currentPlatformId = '';

  try {
    if (process.platform === 'win32') {
      const out = execFileSync('reg.exe', [
        'query',
        'HKLM\\SOFTWARE\\Microsoft\\Cryptography',
        '/v',
        'MachineGuid'
      ], { timeout: 2000, windowsHide: true, stdio: ['ignore', 'pipe', 'ignore'] }).toString();
      const match = out.match(/MachineGuid\s+REG_SZ\s+(\S+)/);
      if (match && match[1]) currentPlatformId = match[1].trim();
    } else if (process.platform === 'darwin') {
      const out = execFileSync('ioreg', ['-rd1', '-c', 'IOPlatformExpertDevice'], {
        timeout: 2000,
        stdio: ['ignore', 'pipe', 'ignore']
      }).toString();
      const match = out.match(/"IOPlatformUUID"\s*=\s*"([^"]+)"/);
      if (match && match[1]) currentPlatformId = match[1].trim();
    } else {
      currentPlatformId = fs.readFileSync('/var/lib/dbus/machine-id', 'utf8').trim();
    }
  } catch (err) {
    log(`Lỗi lấy Platform ID: ${err.message}`, 'warning');
  }

  // Nếu đã có cache và khớp với Platform ID của máy hiện tại -> dùng luôn cache
  if (cfg.activeHwid && cfg.verifiedPlatformId && currentPlatformId && cfg.verifiedPlatformId === currentPlatformId) {
    cachedHWID = cfg.activeHwid;
    return cachedHWID;
  }

  // Chưa có cache hoặc đổi sang máy mới -> Tính toán HWID đầy đủ
  let resolvedHwid = '';
  try {
    if (process.platform === 'win32') {
      // Ưu tiên lấy BIOS UUID bằng PowerShell
      try {
        const out = execFileSync('powershell.exe', [
          '-NoLogo',
          '-NoProfile',
          '-NonInteractive',
          '-Command',
          '(Get-CimInstance Win32_ComputerSystemProduct -ErrorAction Stop).UUID'
        ], { timeout: 5000, windowsHide: true, stdio: ['ignore', 'pipe', 'ignore'] }).toString();
        const uuid = out.trim();
        if (uuid && uuid.length > 10) resolvedHwid = uuid;
      } catch {}

      // Dự phòng bằng WMIC
      if (!resolvedHwid) {
        try {
          const out = execFileSync('wmic.exe', ['csproduct', 'get', 'uuid'], {
            timeout: 5000,
            windowsHide: true,
            stdio: ['ignore', 'pipe', 'ignore']
          }).toString();
          const uuid = out.replace('UUID', '').trim();
          if (uuid && uuid.length > 10) resolvedHwid = uuid;
        } catch {}
      }

      // Cuối cùng dùng tạm Platform ID
      if (!resolvedHwid) {
        resolvedHwid = currentPlatformId;
      }
    } else {
      resolvedHwid = currentPlatformId;
    }
  } catch (err) {
    log(`Lỗi tính HWID: ${err.message}`, 'warning');
  }

  if (!resolvedHwid) {
    if (cfg.machineId) resolvedHwid = cfg.machineId;
    else {
      resolvedHwid = crypto.randomUUID();
      cfg.machineId = resolvedHwid;
    }
  }

  // Lưu lại vào file config để các lần sau tái sử dụng tức thì
  cfg.activeHwid = resolvedHwid;
  cfg.verifiedPlatformId = currentPlatformId;
  saveCfg(cfg);

  cachedHWID = resolvedHwid;
  return cachedHWID;
}

// Request helper hỗ trợ 302/307 Redirect (cần cho Google Apps Script)
function requestWithRedirect(url, options = {}, depth = 0) {
  return new Promise((resolve, reject) => {
    if (depth > 5) return reject(new Error('Too many redirects'));
    const parsedRequestUrl = new URL(url);
    if (parsedRequestUrl.protocol !== 'https:') return reject(new Error('Only HTTPS requests are allowed'));
    const req = https.get(url, { ...options, timeout: 10000 }, (res) => {
      if (res.statusCode >= 300 && res.statusCode < 400 && res.headers.location) {
        const redirectUrl = new URL(res.headers.location, parsedRequestUrl);
        if (parsedRequestUrl.protocol === 'https:' && redirectUrl.protocol !== 'https:') {
          return reject(new Error('Blocked insecure redirect'));
        }
        const nextOptions = { ...options, headers:{ ...(options.headers || {}) } };
        if (redirectUrl.origin !== parsedRequestUrl.origin) delete nextOptions.headers.Authorization;
        return requestWithRedirect(redirectUrl.toString(), nextOptions, depth + 1).then(resolve, reject);
      }
      
      let data = '';
      let size = 0;
      res.on('data', (chunk) => {
        size += chunk.length;
        if (size > 2 * 1024 * 1024) {
          req.destroy(new Error('Response too large'));
          return;
        }
        data += chunk;
      });
      res.on('end', () => {
        if (res.statusCode < 200 || res.statusCode >= 300) {
          reject(new Error(`HTTP ${res.statusCode}`));
          return;
        }
        try {
          resolve(JSON.parse(data));
        } catch (e) {
          resolve(data);
        }
      });
    });
    req.on('error', reject);
    req.on('timeout', () => {
      req.destroy();
      reject(new Error('Timeout'));
    });
  });
}

function requestPostJson(url, data) {
  return new Promise((resolve, reject) => {
    const parsedUrl = new URL(url);
    if (parsedUrl.protocol !== 'https:') return reject(new Error('Only HTTPS requests are allowed'));
    const postData = JSON.stringify(data);
    
    const options = {
      hostname: parsedUrl.hostname,
      port: parsedUrl.port || (parsedUrl.protocol === 'https:' ? 443 : 80),
      path: parsedUrl.pathname + parsedUrl.search,
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Content-Length': Buffer.byteLength(postData)
      },
      timeout: 10000
    };
    
    const req = https.request(options, (res) => {
      let body = '';
      let size = 0;
      res.on('data', (chunk) => {
        size += chunk.length;
        if (size > 2 * 1024 * 1024) {
          req.destroy(new Error('Response too large'));
          return;
        }
        body += chunk;
      });
      res.on('end', () => {
        if (res.statusCode < 200 || res.statusCode >= 300) {
          reject(new Error(`HTTP ${res.statusCode}`));
          return;
        }
        try {
          resolve(JSON.parse(body));
        } catch (e) {
          resolve(body);
        }
      });
    });
    
    req.on('error', reject);
    req.on('timeout', () => {
      req.destroy();
      reject(new Error('Timeout'));
    });
    
    req.write(postData);
    req.end();
  });
}

async function checkLicenseWeb(key) {
  const hwid = getHWID();
  const data = {
    license_key: key,
    device_id: hwid,
    device_name: 'V-ShopeeBoost Pro User',
    software_name: 'V-ShopeeBoost Pro',
    software_version: app.getVersion()
  };
  try {
    const res = await requestPostJson(LICENSE_API_URL, data);
    return res;
  } catch (err) {
    log(`Lỗi kết nối API Web: ${err.message}`, 'error');
    return { status: false, networkError: true, message: `Không thể kết nối máy chủ: ${safeErrorMessage(err)}` };
  }
}

// Check key online
async function checkLicenseOnline(key, isActivation = false) {
  if (!LICENSE_API_URL || LICENSE_API_URL.includes('YOUR_WEB_APP_ID')) {
    return { ok: false, msg: 'Chưa cấu hình API bản quyền (LICENSE_API_URL)' };
  }
  const hwid = getHWID();
  const action = isActivation ? 'activate' : 'check';
  const url = `${LICENSE_API_URL}?action=${action}&key=${encodeURIComponent(key)}&hwid=${encodeURIComponent(hwid)}`;
  try {
    const res = await requestWithRedirect(url);
    if (typeof res === 'object' && res !== null) return res;
    return { ok: false, msg: 'Phản hồi từ máy chủ không hợp lệ' };
  } catch (err) {
    return { ok: false, msg: `Không thể kết nối máy chủ bản quyền: ${err.message}` };
  }
}

// Xác thực cache offline bằng chữ ký SHA256 để chống sửa config.json
async function getPublicIP() {
  try {
    const res = await requestWithRedirect('https://api.ipify.org?format=json');
    return res.ip || '127.0.0.1';
  } catch (err) {
    return '127.0.0.1';
  }
}

function getLicenseCacheSigningKey() {
  let key = getEncryptedSecret('licenseCacheSigningKey');
  if (!key) {
    key = crypto.randomBytes(32).toString('base64');
    saveEncryptedSecret('licenseCacheSigningKey', key);
  }
  return key;
}

function createLicenseCacheSignature(email, key, expiry, hwid) {
  return crypto
    .createHmac('sha256', getLicenseCacheSigningKey())
    .update(JSON.stringify([email, key, expiry, hwid]))
    .digest('hex');
}

// Xác thực cache offline bằng HMAC với khóa được mã hóa bởi hệ điều hành.
function verifyLicenseCache() {
  const cfg = getCfg();
  if (!cfg.userEmail || !cfg.licenseKey || !cfg.licenseExpiry || !cfg.licenseSig) return false;
  
  const hwid = getHWID();
  const expectedSig = createLicenseCacheSignature(cfg.userEmail, cfg.licenseKey, cfg.licenseExpiry, hwid);
  const actualBytes = Buffer.from(String(cfg.licenseSig), 'hex');
  const expectedBytes = Buffer.from(expectedSig, 'hex');
  if (actualBytes.length !== expectedBytes.length || !crypto.timingSafeEqual(actualBytes, expectedBytes)) return false;
  
  const expiryDate = new Date(cfg.licenseExpiry);
  expiryDate.setHours(23, 59, 59, 999);
  if (isNaN(expiryDate.getTime()) || Date.now() > expiryDate.getTime()) return false;
  const lastCheck = Number(cfg.licenseLastCheck);
  if (!Number.isFinite(lastCheck) || lastCheck > Date.now() + 5 * 60 * 1000) return false;
  if (Date.now() - lastCheck > 72 * 60 * 60 * 1000) return false;
  
  return true;
}

function saveLicenseCache(key, expiry) {
  const cfg = getCfg();
  const hwid = getHWID();
  const sig = createLicenseCacheSignature(cfg.userEmail || '', key, expiry, hwid);
  cfg.licenseKey = key;
  cfg.licenseExpiry = expiry;
  cfg.licenseLastCheck = Date.now();
  cfg.licenseSig = sig;
  saveCfg(cfg);
}

// Kiểm tra bản quyền lúc khởi động
async function checkLicenseStartup() {
  const cfg = getCfg();
  const hwid = getHWID();
  log(`Khởi động app. Thiết bị: ${hwid}`, 'info');

  if (!cfg.userEmail) {
    isLicenseValid = false;
    licenseMsg = 'Chưa đăng nhập tài khoản';
    setTimeout(launchApp, 500);
    return;
  }

  if (!cfg.licenseKey) {
    isLicenseValid = false;
    licenseMsg = 'Tài khoản chưa được kích hoạt bản quyền';
    setTimeout(launchApp, 500);
    return;
  }

  // 1. Thử xác thực online trước
  try {
    const res = await checkLicenseWeb(cfg.licenseKey);
    if (res && res.status) {
      saveLicenseCache(cfg.licenseKey, res.expire);
      isLicenseValid = true;
      licenseMsg = `Bản quyền hợp lệ. Hết hạn: ${res.expire}`;
      log(licenseMsg, 'info');
      setTimeout(launchApp, 500);
      return;
    } else if (res && res.networkError) {
      throw new Error(res.message || 'Network unavailable');
    } else {
      isLicenseValid = false;
      licenseMsg = res.message || 'Mã kích hoạt không đúng hoặc hết hạn';
      log(`Online check fail: ${licenseMsg}`, 'warning');
      setTimeout(launchApp, 500);
      return;
    }
  } catch (err) {
    log(`Không thể kết nối máy chủ để check online, kiểm tra cache offline...`, 'info');
  }

  // 2. Nếu check online lỗi (mất mạng), kiểm tra cache offline
  if (verifyLicenseCache()) {
    isLicenseValid = true;
    licenseMsg = `Chạy offline. Hạn dùng đến: ${cfg.licenseExpiry}`;
    log(licenseMsg, 'info');
  } else {
    isLicenseValid = false;
    licenseMsg = 'Bản quyền đã hết hạn hoặc chữ ký lỗi';
    log(licenseMsg, 'warning');
  }
  setTimeout(launchApp, 500);
}

function launchApp() {
  if (splashWindow && !splashWindow.isDestroyed()) splashWindow.close();
  createMain();
  
  // Thiết lập các timer chạy ngầm sau khi app khởi chạy
  setupAppTimers();
}

// ══════════════════════════════════════════════════════════════════════════
// WINDOWS
// ══════════════════════════════════════════════════════════════════════════
function createSplash() {
  splashWindow = new BrowserWindow({
    width:620, height:400, frame:false, transparent:true,
    resizable:false, skipTaskbar:true, alwaysOnTop:true,
    backgroundColor:'#00000000',
    webPreferences:{ nodeIntegration:false, contextIsolation:true, sandbox:true, webSecurity:true }
  });
  hardenWindow(splashWindow);
  splashWindow.loadFile(path.join(__dirname, 'splash.html'));
}

function hardenWindow(win) {
  win.webContents.setWindowOpenHandler(({ url }) => {
    try {
      const safeUrl = parseAllowedExternalUrl(url, ['https:']);
      void shell.openExternal(safeUrl);
    } catch (error) {
      log(`Đã chặn liên kết ngoài: ${safeErrorMessage(error)}`, 'warning');
    }
    return { action: 'deny' };
  });

  win.webContents.on('will-navigate', (event, targetUrl) => {
    try {
      const target = new URL(targetUrl);
      if (target.protocol !== 'file:') throw new Error('Chỉ cho phép điều hướng nội bộ');
      resolveInside(__dirname, path.relative(__dirname, fileURLToPath(target)));
    } catch (error) {
      event.preventDefault();
      log(`Đã chặn điều hướng renderer: ${safeErrorMessage(error)}`, 'warning');
    }
  });

  win.webContents.session.setPermissionRequestHandler((_webContents, _permission, callback) => callback(false));
  win.webContents.session.setPermissionCheckHandler(() => false);
}

function createMain() {
  let winWidth = 1380;
  let winHeight = 880;
  try {
    const primaryDisplay = screen.getPrimaryDisplay();
    if (primaryDisplay && primaryDisplay.workAreaSize) {
      const { width: sW, height: sH } = primaryDisplay.workAreaSize;
      winWidth = Math.min(1380, Math.max(960, Math.round(sW * 0.95)));
      winHeight = Math.min(880, Math.max(600, Math.round(sH * 0.95)));
    }
  } catch {}

  mainWindow = new BrowserWindow({
    width: winWidth, height: winHeight, minWidth: 960, minHeight: 580,
    frame:false, backgroundColor:'#080810', show:false,
    icon: path.join(__dirname, 'build', 'icon.png'),
    webPreferences:{
      preload: path.join(__dirname, 'preload.js'),
      contextIsolation:true, nodeIntegration:false,
      sandbox:true, webSecurity:true,
      spellcheck:false
    }
  });
  hardenWindow(mainWindow);

  if (isLicenseValid) {
    mainWindow.loadFile(path.join(__dirname, 'index.html'));
  } else {
    mainWindow.loadFile(path.join(__dirname, 'pages', 'activation.html'));
  }

  if (!isDev) {
    mainWindow.webContents.on('before-input-event', (event, input) => {
      if (input.key === 'F12' || (input.control && input.shift && input.key === 'I')) {
        event.preventDefault();
      }
    });
  }
  mainWindow.once('ready-to-show', () => {
    setTimeout(() => {
      if (splashWindow && !splashWindow.isDestroyed()) splashWindow.close();
      mainWindow.maximize();
      mainWindow.show();
    }, 2000);
  });
  mainWindow.on('closed', () => mainWindow = null);
  mainWindow.on('close', e => {
    // Luôn đóng hoàn toàn ứng dụng và dọn dẹp khi bấm nút X
    ipcMain.emit('win-close');
  });
}

app.whenReady().then(() => {
  if (!gotTheLock) return;
  migrateLegacySecrets();
  migrateSensitiveProfileData();
  session.defaultSession.webRequest.onHeadersReceived((details, callback) => {
    const headers = { ...details.responseHeaders };
    headers['Content-Security-Policy'] = [
      "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' data: https://fonts.gstatic.com; img-src 'self' data: https:; connect-src 'self' https: http://127.0.0.1:34297; object-src 'none'; base-uri 'none'; frame-src 'none'; form-action 'none'"
    ];
    callback({ responseHeaders: headers });
  });
  createSplash();
  startLocalAuthServer();
  const startupDeepLink = queuedDeepLink || findDeepLinkArg();
  queuedDeepLink = '';
  if (startupDeepLink) setImmediate(() => dispatchDeepLink(startupDeepLink));
  setTimeout(checkLicenseStartup, 400);
  app.on('activate', () => { if (!mainWindow) createMain(); });
});
app.on('window-all-closed', () => { if (process.platform !== 'darwin') app.quit(); });

// ══════════════════════════════════════════════════════════════════════════
// IPC — WINDOW
// ══════════════════════════════════════════════════════════════════════════
ipcMain.on('win-min',   () => mainWindow?.minimize());
ipcMain.on('win-max',   () => mainWindow?.isMaximized() ? mainWindow.unmaximize() : mainWindow?.maximize());
ipcMain.on('win-close', async () => {
  // Thực hiện dọn dẹp tắt toàn bộ trình duyệt đang chạy trước khi đóng app hoàn toàn
  const names = Object.keys(running);
  for (const name of names) {
    const pid = running[name];
    if (pid) {
      try {
        if (process.platform === 'win32') {
          execFile('taskkill.exe', ['/pid', String(pid), '/f', '/t'], () => {});
        } else {
          process.kill(pid, 'SIGKILL');
        }
      } catch {}
      delete running[name];
    }
  }

  mainWindow?.destroy();
  app.quit();
});

// ══════════════════════════════════════════════════════════════════════════
// IPC — PROFILES
// ══════════════════════════════════════════════════════════════════════════
const PROFILE_STRING_LIMITS = {
  proxy:2000, notes:5000, color:20, userAgent:2000, proxyType:20, timezone:100,
  language:200, screen:50, canvas:30, webgl:30, audio:30, webrtc:30, fonts:100,
  cpu:30, browserType:30, group:80, platform:100, dpr:20, touch:30, ram:30,
  gpu:300, clientrects:30, mediaDevices:30, battery:30, permissions:30,
  speech:30, sensors:30, geolocation:100, createdAt:50, lastOpened:50
};
const PROFILE_BOOLEAN_KEYS = new Set([
  'maskBuildID','maskHardware','maskVendor','maskConnection','isDeleted'
]);

function sanitizeProfileRecord(input, existing = {}, includeMetadata = false) {
  assertPlainObject(input, 'Dữ liệu profile');
  const result = { ...existing };
  for (const [key, limit] of Object.entries(PROFILE_STRING_LIMITS)) {
    if (!includeMetadata && ['createdAt','lastOpened'].includes(key)) continue;
    if (typeof input[key] === 'string') result[key] = input[key].normalize('NFC').slice(0, limit);
  }
  for (const key of PROFILE_BOOLEAN_KEYS) {
    if (typeof input[key] === 'boolean') result[key] = input[key];
  }
  if (Array.isArray(input.tags)) {
    result.tags = input.tags.filter(v => typeof v === 'string').map(v => v.normalize('NFC').slice(0, 100)).slice(0, 30);
  }
  if (includeMetadata && Number.isFinite(Number(input.openCount))) {
    result.openCount = Math.max(0, Math.min(1_000_000_000, Number(input.openCount)));
  }
  return result;
}

ipcMain.handle('profiles-get', async () => {
  requireLicense();
  try {
    return fs.readdirSync(PROFILES_DIR)
      .filter(item => {
        try {
          validateProfileName(item);
          return fs.statSync(resolveInside(PROFILES_DIR, item)).isDirectory();
        } catch {
          return false;
        }
      })
      .map(item => {
        let cfg = {};
      try { cfg = sanitizeProfileRecord(hydrateProfileRecord(JSON.parse(fs.readFileSync(resolveInside(PROFILES_DIR, item, 'config.json'),'utf8'))), {}, true); } catch {}
        return { name:item, proxy:cfg.proxy||'', notes:cfg.notes||'', tags:cfg.tags||[],
          color:cfg.color||'', userAgent:cfg.userAgent||'', openCount:cfg.openCount||0,
          createdAt:cfg.createdAt||null, lastOpened:cfg.lastOpened||null,
          // Fingerprint fields
          proxyType:cfg.proxyType||'http', timezone:cfg.timezone||'auto',
          language:cfg.language||'vi-VN, en-US', screen:cfg.screen||'random',
          canvas:cfg.canvas||'noise', webgl:cfg.webgl||'noise',
          audio:cfg.audio||'noise', webrtc:cfg.webrtc||'mask',
          fonts:cfg.fonts||'random', cpu:cfg.cpu||'random',
          browserType:cfg.browserType||'system',
          group:cfg.group||'Mặc định',
          // Advanced Fingerprints
          platform:cfg.platform||'Win32',
          dpr:cfg.dpr||'1',
          touch:cfg.touch||'disable',
          ram:cfg.ram||'random',
          gpu:cfg.gpu||'random',
          clientrects:cfg.clientrects||'noise',
          mediaDevices:cfg.mediaDevices||'noise',
          battery:cfg.battery||'noise',
          permissions:cfg.permissions||'noise',
          speech:cfg.speech||'noise',
          sensors:cfg.sensors||'disable',
          geolocation:cfg.geolocation||'auto',
          maskBuildID:cfg.maskBuildID !== false,
          maskHardware:cfg.maskHardware !== false,
          maskVendor:cfg.maskVendor !== false,
          isDeleted: cfg.isDeleted || false,
          status: running[item] ? 'active' : 'inactive' };
      });
  } catch (e) { log('profiles-get: '+e.message,'error'); return []; }
});

ipcMain.handle('profiles-create', async (_, data) => {
  requireLicense();
  assertPlainObject(data, 'Dữ liệu profile');
  const cleanData = sanitizeProfileRecord(data);
  const { name, proxy, notes, tags, color, userAgent,
    proxyType, timezone, language, screen,
    canvas, webgl, audio, webrtc, fonts, cpu, browserType, group,
    platform, dpr, touch, ram, gpu, clientrects, mediaDevices,
    battery, permissions, speech, sensors, geolocation,
    maskBuildID, maskHardware, maskVendor, maskConnection } = { ...cleanData, name:data.name };

  let safeName;
  try { safeName = validateProfileName(name); }
  catch { return { ok:false, msg:'Tên profile không hợp lệ' }; }
  const existing = fs.readdirSync(PROFILES_DIR).filter(i => {
    try { return fs.statSync(path.join(PROFILES_DIR,i)).isDirectory(); } catch { return false; }
  });

  const pp = resolveInside(PROFILES_DIR, safeName);
  if (fs.existsSync(pp)) return { ok:false, msg:'Profile đã tồn tại' };
  fs.mkdirSync(pp);
  atomicWriteJsonSync(resolveInside(pp, 'config.json'), protectProfileRecord({
    proxy: proxy||'', notes: notes||'', tags: tags||[], color: color||'',
    userAgent: userAgent||'',
    proxyType: proxyType||'http', timezone: timezone||'auto',
    language: language||'vi-VN, en-US', screen: screen||'random',
    canvas: canvas||'noise', webgl: webgl||'noise',
    audio: audio||'noise', webrtc: webrtc||'mask',
    fonts: fonts||'random', cpu: cpu||'random',
    browserType: browserType||'system',
    group: group||'Mặc định',
    // Advanced fingerprints
    platform: platform||'Win32',
    dpr: dpr||'1',
    touch: touch||'disable',
    ram: ram||'random',
    gpu: gpu||'random',
    clientrects: clientrects||'noise',
    mediaDevices: mediaDevices||'noise',
    battery: battery||'noise',
    permissions: permissions||'noise',
    speech: speech||'noise',
    sensors: sensors||'disable',
    geolocation: geolocation||'auto',
    maskBuildID: maskBuildID !== false,
    maskHardware: maskHardware !== false,
    maskVendor: maskVendor !== false,
    maskConnection: maskConnection !== false,
    createdAt: new Date().toISOString(), openCount:0
  }));
  log(`Tạo profile: ${safeName}`, 'info');
  return { ok:true };
});

ipcMain.handle('profiles-update', async (_, name, data) => {
  requireLicense();
  const safeName = validateProfileName(name);
  assertPlainObject(data, 'Dữ liệu profile');
  const pp = resolveInside(PROFILES_DIR, safeName);
  if (!fs.existsSync(pp)) return { ok:false, msg:'Profile không tồn tại' };
  let cfg = {};
  try { cfg = sanitizeProfileRecord(hydrateProfileRecord(JSON.parse(fs.readFileSync(resolveInside(pp, 'config.json'),'utf8'))), {}, true); } catch {}
  cfg = sanitizeProfileRecord(data, cfg);
  atomicWriteJsonSync(resolveInside(pp, 'config.json'), protectProfileRecord(cfg));
  log(`Cập nhật profile: ${safeName}`, 'info');
  return { ok:true };
});

ipcMain.handle('profiles-rename', async (_, oldName, newName) => {
  requireLicense();
  const safeOldName = validateProfileName(oldName);
  let safeNewName;
  try { safeNewName = validateProfileName(newName); }
  catch { return { ok:false, msg:'Tên không hợp lệ' }; }
  const oldP = resolveInside(PROFILES_DIR, safeOldName);
  const newP = resolveInside(PROFILES_DIR, safeNewName);
  if (!fs.existsSync(oldP)) return { ok:false, msg:'Profile không tồn tại' };
  if (fs.existsSync(newP))  return { ok:false, msg:'Tên đã tồn tại' };
  fs.renameSync(oldP, newP);
  if (running[safeOldName]) { running[safeNewName]=running[safeOldName]; delete running[safeOldName]; }
  log(`Đổi tên: ${safeOldName} → ${safeNewName}`, 'info');
  return { ok:true };
});

ipcMain.handle('profiles-delete', async (_, name) => {
  requireLicense();
  const safeName = validateProfileName(name);
  const pp = resolveInside(PROFILES_DIR, safeName);
  if (!fs.existsSync(pp)) return { ok:false, msg:'Không tìm thấy' };
  fs.rmSync(pp, { recursive:true, force:true });
  delete running[safeName];
  log(`Xóa profile: ${safeName}`, 'warning');
  return { ok:true };
});

ipcMain.handle('profiles-delete-bulk', async (_, names) => {
  requireLicense();
  if (!Array.isArray(names) || names.length > 500) throw new TypeError('Danh sách profile không hợp lệ');
  let n = 0;
  for (const name of names) {
    try {
      const safeName = validateProfileName(name);
      fs.rmSync(resolveInside(PROFILES_DIR, safeName), { recursive:true, force:true });
      delete running[safeName];
      n++;
    } catch {}
  }
  log(`Xóa ${n} profiles`, 'warning');
  return { ok:true, deleted:n };
});

ipcMain.handle('profiles-duplicate', async (_, name) => {
  requireLicense();
  const safeName = validateProfileName(name);
  const srcP = resolveInside(PROFILES_DIR, safeName);
  if (!fs.existsSync(srcP)) return { ok:false, msg:'Profile không tồn tại' };
  const copyBase = safeName.slice(0, 68).trimEnd();
  let newName = validateProfileName(`${copyBase}_copy`), i=1;
  while (fs.existsSync(resolveInside(PROFILES_DIR, newName))) {
    newName = validateProfileName(`${copyBase}_copy${i++}`);
  }
  fs.mkdirSync(resolveInside(PROFILES_DIR, newName));
  const cfgP = resolveInside(srcP, 'config.json');
  if (fs.existsSync(cfgP)) {
    const cfg = JSON.parse(fs.readFileSync(cfgP,'utf8'));
    cfg.createdAt=new Date().toISOString(); cfg.lastOpened=null; cfg.openCount=0;
    atomicWriteJsonSync(resolveInside(PROFILES_DIR, newName, 'config.json'), protectProfileRecord(cfg));
  }
  log(`Nhân bản: ${safeName} → ${newName}`, 'info');
  return { ok:true, newName };
});

// ── DEFAULT BOOKMARKS ───────────────────────────────────────────────────────
function ensureDefaultBookmarks(userDataDir) {
  try {
    const defaultDir = path.join(userDataDir, 'Default');
    const bookmarksPath = path.join(defaultDir, 'Bookmarks');

    // Chỉ ghi nếu chưa có file Bookmarks (profile mới)
    if (fs.existsSync(bookmarksPath)) return;

    // Tạo thư mục Default nếu chưa có
    fs.mkdirSync(defaultDir, { recursive: true });

    // Chrome FILETIME = microseconds kể từ 01/01/1601
    const now = (BigInt(Date.now()) + BigInt(11644473600000)) * BigInt(1000);
    const nowStr = now.toString();

    const bookmarks = {
      checksum: '0000000000000000000000000000000',
      roots: {
        bookmark_bar: {
          children: [
            {
              date_added: nowStr,
              guid: 'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
              id: '4',
              name: 'Shopee Việt Nam',
              type: 'url',
              url: 'https://shopee.vn/'
            },
            {
              date_added: nowStr,
              guid: 'b2c3d4e5-f6a7-8901-bcde-f12345678901',
              id: '5',
              name: 'VMaxTech App',
              type: 'url',
              url: 'https://app.vmaxtech.vn/'
            }
          ],
          date_added: nowStr,
          date_modified: nowStr,
          guid: '0bc5d8f0-1234-5678-abcd-ef0123456789',
          id: '1',
          name: 'Bookmarks bar',
          type: 'folder'
        },
        other: {
          children: [],
          date_added: nowStr,
          date_modified: '0',
          guid: '1bc5d8f0-1234-5678-abcd-ef0123456789',
          id: '2',
          name: 'Other bookmarks',
          type: 'folder'
        },
        synced: {
          children: [],
          date_added: nowStr,
          date_modified: '0',
          guid: '2bc5d8f0-1234-5678-abcd-ef0123456789',
          id: '3',
          name: 'Mobile bookmarks',
          type: 'folder'
        }
      },
      version: 1
    };

    fs.writeFileSync(bookmarksPath, JSON.stringify(bookmarks, null, 3), 'utf8');
    log(`Đã tạo bookmark mặc định cho profile: ${userDataDir}`, 'info');
  } catch (err) {
    log(`Lỗi tạo bookmark: ${err.message}`, 'warning');
  }
}

// ── CLEAN PREFERENCES & ANTI-TELEMETRY (v6.0.0 Pro Max) ───────────────────
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
  } catch (err) {
    // Non-blocking preference setup
  }
}

const proxyGeoCache = new Map();
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

// ── PARSE PROXY STRING (v6.0.0 Pro Max) ──────────────────────────────────
function parseProxyString(proxyStr, defaultType = 'http') {
  if (!proxyStr || typeof proxyStr !== 'string' || proxyStr.length > 2000 || /[\u0000-\u001f\u007f]/.test(proxyStr)) return null;
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

  // Tham số --proxy-server chuẩn cho Chrome
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

function pacPathToFileUrl(pacPath) {
  return 'file:///' + pacPath.split(path.sep).join('/').replace(/^([A-Za-z]):/, '$1:');
}

const BLOCKED_CHROME_ARG_PREFIXES = [
  '--user-data-dir', '--load-extension', '--disable-extensions-except', '--proxy-server',
  '--remote-debugging', '--remote-allow-origins', '--disable-web-security',
  '--allow-running-insecure-content', '--ignore-certificate-errors'
];

function getSafeCustomChromeArgs(value) {
  if (typeof value !== 'string') return [];
  return value
    .match(/(?:[^\s"]+|"[^"]*")+/g)?.slice(0, 30)
    .map(arg => arg.replace(/^"|"$/g, ''))
    .filter(arg => arg.startsWith('--') && !BLOCKED_CHROME_ARG_PREFIXES.some(prefix => arg.startsWith(prefix)))
    .map(arg => arg.slice(0, 500)) || [];
}

// ── OPEN CHROME ────────────────────────────────────────────────────────────
ipcMain.handle('profiles-open', async (_, name, customProxy, startUrl) => {
  requireLicense();
  const safeName = validateProfileName(name);
  const pp = resolveInside(PROFILES_DIR, safeName);
  if (!fs.existsSync(pp)) return { ok:false, msg:'Profile không tồn tại' };

  let pCfg = {};
  try { pCfg = sanitizeProfileRecord(hydrateProfileRecord(JSON.parse(fs.readFileSync(resolveInside(pp, 'config.json'),'utf8'))), {}, true); } catch {}

  const cfg = getCfg();
  let chromePath = '';

  // Luôn ưu tiên ChromeCore141 / chromium-v120 đi kèm app trước
  const chromiumCandidates = [
    path.join(__dirname, 'ChromeCore141', 'chrome.exe'),
    path.join(__dirname, '..', 'ChromeCore141', 'chrome.exe'),
    path.join(app.getAppPath(), 'ChromeCore141', 'chrome.exe'),
    path.join(app.getAppPath(), '..', 'ChromeCore141', 'chrome.exe'),
    path.join(process.resourcesPath || '', 'ChromeCore141', 'chrome.exe'),
    path.join(__dirname, 'chromium-v120', 'chromium.exe'),
    path.join(__dirname, '..', 'chromium-v120', 'chromium.exe'),
    path.join(app.getAppPath(), 'chromium-v120', 'chromium.exe'),
    path.join(app.getAppPath(), '..', 'chromium-v120', 'chromium.exe'),
    path.join(process.resourcesPath || '', 'chromium-v120', 'chromium.exe'),
    // Dự phòng thư mục cũ
    path.join(__dirname, 'chrom64bit', 'chrome.exe'),
    path.join(app.getAppPath(), '..', 'chrom64bit', 'chrome.exe'),
    path.join(process.resourcesPath || '', 'chrom64bit', 'chrome.exe'),
  ];
  chromePath = chromiumCandidates.find(p => { try { return fs.existsSync(p); } catch { return false; } }) || '';

  // Nếu không có chromium đi kèm, dùng Chrome hệ thống (cấu hình trong Settings)
  if (!chromePath) {
    chromePath = cfg.chromePath || '';
    if (!chromePath || !fs.existsSync(chromePath)) chromePath = findChrome();
    if (!chromePath || !fs.existsSync(chromePath)) {
      return { ok:false, msg:'Không tìm thấy trình duyệt. Vui lòng kiểm tra thư mục ChromeCore141 hoặc vào Cài đặt → Chrome để cấu hình.', needChrome:true };
    }
  }

  const rawProxyInput = typeof customProxy === 'string' ? customProxy.slice(0, 2000) : (pCfg.proxy || '');
  const proxyType = pCfg.proxyType || 'http';
  const parsedProxy = parseProxyString(rawProxyInput, proxyType);

  // Auto GeoIP Alignment (v6.0.0 Pro Max)
  let geoInfo = null;
  if (parsedProxy) {
    createProxyAuthPacFile(pp, rawProxyInput, proxyType);
    geoInfo = proxyGeoCache.get(parsedProxy.host);
  }

  const resolvedTimezone = (pCfg.timezone && pCfg.timezone !== 'auto')
    ? pCfg.timezone
    : (geoInfo?.timezone || 'Asia/Ho_Chi_Minh');

  const resolvedLanguage = (pCfg.language && pCfg.language !== 'auto')
    ? pCfg.language
    : (geoInfo?.countryCode && COUNTRY_GEO_MAP[geoInfo.countryCode]?.lang ? COUNTRY_GEO_MAP[geoInfo.countryCode].lang : (pCfg.language || 'vi-VN, en-US'));

  const args = [
    `--user-data-dir=${pp}`,
    '--no-first-run',
    '--no-default-browser-check',
    '--no-service-autorun',
    '--test-type',
    '--disable-blink-features=AutomationControlled',
    // Comprehensive Anti-Telemetry & Background Network Blocking
    '--disable-sync',
    '--disable-background-networking',
    '--disable-component-update',
    '--disable-default-apps',
    '--disable-domain-reliability',
    '--disable-client-side-phishing-detection',
    '--disable-breakpad',
    '--no-crash-upload',
    '--disable-metrics',
    '--disable-metrics-repo',
    '--disable-speech-api',
    '--disable-speech-synthesis-api',
    '--disable-hang-monitor',
    '--disable-prompt-on-repost',
    '--disable-ipc-flooding-protection',
    '--disable-background-timer-throttling',
    '--disable-backgrounding-occluded-windows',
    '--disable-renderer-backgrounding',
    '--signin-promo-style=disabled',
    '--password-store=basic',
    '--use-mock-keychain',
    // WebRTC Leak Shield
    '--force-webrtc-ip-handling-policy=disable_non_proxied_udp',
    '--enforce-webrtc-ip-permission-check',
    // Performance & Stability
    '--disable-features=Translate,OptimizationHints,MediaRouter,DialMediaRouteProvider,CalculateNativeWinOcclusion,InterestFeedContentSuggestions'
  ];

  // Apply proxy với --proxy-server trực tiếp cho Chrome
  if (parsedProxy) {
    log(`Áp dụng Proxy cho Chrome: ${parsedProxy.serverArg} (Auth: ${parsedProxy.hasAuth})`, 'info');
    args.push(`--proxy-server=${parsedProxy.serverArg}`);
    args.push('--proxy-bypass-list=127.0.0.1,localhost,<local>');
  }

  // User Agent
  if (pCfg.userAgent) args.push(`--user-agent=${pCfg.userAgent}`);

  // Apply fingerprint settings
  // Canvas block/noise
  if (pCfg.canvas === 'block') {
    args.push('--disable-canvas-audio', '--disable-image-canvas-conversion');
  } else if (pCfg.canvas === 'noise') {
    // Add noise via extensions is complex, we just set flags
  }

  // WebGL
  if (pCfg.webgl === 'block') {
    args.push('--disable-webgl', '--disable-webgl2');
  } else if (pCfg.webgl === 'noise') {
    args.push('--enable-webgl', '--use-gl=swiftshader');
  }

  // Audio
  if (pCfg.audio === 'block') {
    args.push('--mute-audio', '--disable-audio-support');
  }

  // WebRTC
  if (pCfg.webrtc === 'disable') {
    args.push('--disable-webrtc');
  } else if (pCfg.webrtc === 'mask') {
    args.push('--enable-features=WebRtcHideLocalIpWithMdns');
  }

  // Fonts
  if (pCfg.fonts === 'block') {
    args.push('--disable-fonts');
  }

  // Language
  if (resolvedLanguage) {
    const lang = resolvedLanguage.split(',')[0].trim();
    args.push(`--lang=${lang}`);
  }

  // Screen resolution
  if (pCfg.screen && pCfg.screen !== 'random') {
    const [w, h] = pCfg.screen.split(/[x,]/).map(s => s.trim());
    if (w && h) args.push(`--window-size=${w},${h}`);
  }

  // Custom Chrome args
  if (cfg.chromeArgs) args.push(...getSafeCustomChromeArgs(cfg.chromeArgs));

  // Tự động sao chép và nạp extension ShopeeBoost riêng biệt cho mỗi profile để hỗ trợ vân tay
  const extCandidates = [
    path.join(__dirname, '..', 'Extension-ShopeeBoost'),
    path.join(__dirname, 'Extension-ShopeeBoost'),
    path.join(app.getAppPath(), '..', 'Extension-ShopeeBoost'),
    path.join(process.resourcesPath || '', 'Extension-ShopeeBoost'),
  ];
  const extPath = extCandidates.find(p => { try { return fs.existsSync(path.join(p, 'manifest.json')); } catch { return false; } });
  
  if (extPath) {
    const extDest = path.join(pp, 'extension');
    try {
      if (fs.existsSync(extDest)) {
        fs.rmSync(extDest, { recursive: true, force: true });
      }
      
      const copyDirSync = (src, dest) => {
        fs.mkdirSync(dest, { recursive: true });
        const entries = fs.readdirSync(src, { withFileTypes: true });
        for (const entry of entries) {
          const srcPath = path.join(src, entry.name);
          const destPath = path.join(dest, entry.name);
          if (entry.isDirectory()) {
            copyDirSync(srcPath, destPath);
          } else {
            fs.copyFileSync(srcPath, destPath);
          }
        }
      };
      
      copyDirSync(extPath, extDest);
      
      // Ghi cấu hình vân tay riêng biệt
      const activeProxyStr = parsedProxy ? parsedProxy.formattedProxy : rawProxyInput;
      const fpData = {
        proxy: activeProxyStr,
        proxyAuth: parsedProxy && parsedProxy.hasAuth ? { username: parsedProxy.user, password: parsedProxy.pass } : null,
        userAgent: pCfg.userAgent || '',
        screen: pCfg.screen || 'random',
        platform: pCfg.platform || 'Win32',
        dpr: pCfg.dpr || '1',
        touch: pCfg.touch || 'disable',
        cpu: pCfg.cpu || 'random',
        ram: pCfg.ram || 'random',
        gpu: pCfg.gpu || 'random',
        canvas: pCfg.canvas || 'noise',
        webgl: pCfg.webgl || 'noise',
        audio: pCfg.audio || 'noise',
        clientrects: pCfg.clientrects || 'noise',
        mediaDevices: pCfg.mediaDevices || 'noise',
        fonts: pCfg.fonts || 'random',
        battery: pCfg.battery || 'noise',
        permissions: pCfg.permissions || 'noise',
        speech: pCfg.speech || 'noise',
        sensors: pCfg.sensors || 'disable',
        timezone: resolvedTimezone,
        language: resolvedLanguage,
        geolocation: pCfg.geolocation || 'auto',
        webrtc: pCfg.webrtc || 'mask',
        maskBuildID: pCfg.maskBuildID !== false,
        maskHardware: pCfg.maskHardware !== false,
        maskVendor: pCfg.maskVendor !== false,
        maskConnection: pCfg.maskConnection !== false
      };
      atomicWriteJsonSync(resolveInside(extDest, 'fingerprint.json'), fpData);
      fs.writeFileSync(resolveInside(extDest, 'fingerprint.js'), `const FINGERPRINT_CONFIG = ${JSON.stringify(fpData, null, 2)};`, { encoding:'utf8', mode:0o600 });
      
      const loadExts = [extDest];
      try {
        const db = ExtensionRepository.load(pp);
        for (const [id, ext] of Object.entries(db)) {
          if (ext.enabled) {
            const fullExtPath = path.join(pp, 'extensions', id);
            if (fs.existsSync(fullExtPath)) {
              loadExts.push(fullExtPath);
            }
          }
        }
      } catch (e) {
        log(`Lỗi nạp Extension bổ sung: ${e.message}`, 'error');
      }
      
      args.push(`--load-extension=${loadExts.join(',')}`);
      log(`Load custom extensions for profile: ${loadExts.join(',')}`, 'info');
    } catch (errCopy) {
      log(`Lỗi copy extension: ${errCopy.message}, chuyển sang load mặc định`, 'error');
      args.push(`--load-extension=${extPath}`);
    }
  } else {
    log('Không tìm thấy Extension-ShopeeBoost để load', 'warning');
  }

  // Gim bookmark mặc định và dọn dẹp profile sạch sẽ (Anti-telemetry)
  ensureDefaultBookmarks(pp);
  ensureCleanPreferences(pp);

  // Thêm mặc định mở trang check IP và proxy của app
  if (startUrl) {
    try {
      args.push(parseAllowedWebUrl(startUrl, { allowHttp:false }));
    } catch {
      return { ok:false, msg:'URL khởi động phải là liên kết HTTPS hợp lệ' };
    }
  } else {
    args.push('http://127.0.0.1:34297/ip');
  }

  try {
    const spawnEnv = { ...process.env };
    if (resolvedTimezone) {
      spawnEnv.TZ = resolvedTimezone;
    }
    const proc = spawn(chromePath, args, { detached:true, stdio:'ignore', env:spawnEnv });
    proc.on('error', (err) => {
      log(`Lỗi khởi chạy trình duyệt: ${err.message}`, 'error');
    });
    proc.unref();
    running[safeName] = proc.pid;
    proc.on('close', () => delete running[safeName]);
  } catch (err) {
    return { ok: false, msg: `Không thể chạy trình duyệt. File bị lỗi hoặc thiếu tài nguyên (Side-by-side). Chi tiết: ${err.message}` };
  }

  pCfg.lastOpened = new Date().toISOString();
  pCfg.openCount  = (pCfg.openCount||0)+1;
  atomicWriteJsonSync(resolveInside(pp, 'config.json'), protectProfileRecord(pCfg));
  log(`Mở Chrome: ${safeName}${parsedProxy ? ` [${parsedProxy.serverArg}]` : ''}`, 'info');
  return { ok:true };
});

ipcMain.handle('profiles-close', async (_, name) => {
  const safeName = validateProfileName(name);
  const pid = running[safeName];
  if (!pid) return { ok: false, msg: 'Profile không chạy' };
  try {
    if (process.platform === 'win32') {
      execFile('taskkill.exe', ['/pid', String(pid), '/f', '/t'], () => {});
    } else {
      process.kill(pid, 'SIGKILL');
    }
    delete running[safeName];
    log(`Tắt Chrome: ${safeName}`, 'info');
    return { ok: true };
  } catch (err) {
    delete running[safeName];
    return { ok: true };
  }
});

ipcMain.handle('profiles-close-all', async () => {
  const names = Object.keys(running);
  let count = 0;
  for (const name of names) {
    const pid = running[name];
    if (pid) {
      try {
        if (process.platform === 'win32') {
          execFile('taskkill.exe', ['/pid', String(pid), '/f', '/t'], () => {});
        } else {
          process.kill(pid, 'SIGKILL');
        }
        count++;
      } catch {}
      delete running[name];
    }
  }
  log(`Tắt toàn bộ ${count} profiles đang hoạt động`, 'warning');
  return { ok: true, count };
});

ipcMain.handle('profiles-open-bulk', async (_, names) => {
  requireLicense();
  if (!Array.isArray(names) || names.length > 100) throw new TypeError('Danh sách profile không hợp lệ');
  const cfg = getCfg();
  let chromePath = cfg.chromePath || '';
  if (!chromePath || !fs.existsSync(chromePath)) chromePath = findChrome();
  if (!chromePath || !fs.existsSync(chromePath)) return { ok:false, msg:'Không tìm thấy Chrome', needChrome:true };

  let opened = 0;
  for (const name of names) {
    try {
      const safeName = validateProfileName(name);
      const pp = resolveInside(PROFILES_DIR, safeName);
      if (!fs.existsSync(pp)) continue;
      let pCfg = {};
      try { pCfg = sanitizeProfileRecord(hydrateProfileRecord(JSON.parse(fs.readFileSync(resolveInside(pp, 'config.json'),'utf8'))), {}, true); } catch {}
      
      const args = [`--user-data-dir=${pp}`, '--no-first-run', '--no-default-browser-check', 'http://127.0.0.1:34297/ip'];
      const rawProxyInput = pCfg.proxy || '';
      const proxyType = pCfg.proxyType || 'http';
      const parsedProxy = parseProxyString(rawProxyInput, proxyType);
      
      if (parsedProxy) {
        args.push(`--proxy-server=${parsedProxy.serverArg}`, '--proxy-bypass-list=127.0.0.1,localhost,<local>');
      }
      
      const proc = spawn(chromePath, args, {detached:true,stdio:'ignore'});
      proc.unref();
      running[safeName] = proc.pid;
      proc.on('close', () => delete running[safeName]);
      pCfg.lastOpened=new Date().toISOString(); pCfg.openCount=(pCfg.openCount||0)+1;
      atomicWriteJsonSync(resolveInside(pp, 'config.json'), protectProfileRecord(pCfg));
      opened++;
      await new Promise(r=>setTimeout(r,500));
    } catch {}
  }
  log(`Mở bulk ${opened} profiles`, 'info');
  return { ok:true, opened };
});

// ── IMPORT/EXPORT ──────────────────────────────────────────────────────────
ipcMain.handle('profiles-import', async (_, text) => {
  requireLicense();
  if (typeof text !== 'string' || text.length > 5_000_000) throw new TypeError('Dữ liệu import không hợp lệ');
  const lines  = text.trim().split('\n').map(l=>l.trim()).filter(Boolean);
  let created=0, skipped=0, errors=0;
  for (const line of lines) {
    const parts = line.split(/[|,\t]/);
    let name;
    try { name = validateProfileName((parts[0]||'').trim()); } catch { errors++; continue; }
    const proxy = (parts[1]||'').trim().slice(0, 2000);
    const pp = resolveInside(PROFILES_DIR, name);
    if (fs.existsSync(pp)) { skipped++; continue; }
    try {
      fs.mkdirSync(pp);
      atomicWriteJsonSync(resolveInside(pp, 'config.json'), protectProfileRecord({proxy,createdAt:new Date().toISOString(),openCount:0}));
      created++;
    } catch { errors++; }
  }
  log(`Import: +${created}, skip ${skipped}, err ${errors}`, 'info');
  return { ok:true, created, skipped, errors };
});

ipcMain.handle('profiles-export', async (_, fmt) => {
  requireLicense();
  try {
    const items = fs.readdirSync(PROFILES_DIR).filter(i=>{try{return fs.statSync(path.join(PROFILES_DIR,i)).isDirectory();}catch{return false;}});
    const profiles = items.map(name=>{
      let cfg={};
      try{cfg=sanitizeProfileRecord(hydrateProfileRecord(JSON.parse(fs.readFileSync(resolveInside(PROFILES_DIR,name,'config.json'),'utf8'))),{},true);}catch{}
      return{name,...cfg};
    });
    let data;
    switch(fmt){
      case 'json': data=JSON.stringify(profiles,null,2); break;
      case 'name': data=profiles.map(p=>p.name).join('\n'); break;
      case 'proxy': data=profiles.filter(p=>p.proxy).map(p=>p.proxy).join('\n'); break;
      case 'full': data=profiles.map(p=>`${p.name}|${p.proxy||''}|${p.userAgent||''}`).join('\n'); break;
      default: data=profiles.map(p=>`${p.name}|${p.proxy||''}`).join('\n');
    }
    return{ok:true,data,count:profiles.length};
  } catch(e){return{ok:false,msg:e.message};}
});

// ── BACKUP/RESTORE ─────────────────────────────────────────────────────────
ipcMain.handle('backup-create', async (_, name) => {
  requireLicense();
  try {
    const bkName = validateBackupName(name || `backup-${Date.now()}`);
    const items  = fs.readdirSync(PROFILES_DIR).filter(i=>{try{return fs.statSync(path.join(PROFILES_DIR,i)).isDirectory();}catch{return false;}});
    const data   = {};
    for (const item of items) {
      const cfgP = resolveInside(PROFILES_DIR,item,'config.json');
      if (fs.existsSync(cfgP)) data[item]=JSON.parse(fs.readFileSync(cfgP,'utf8'));
      else data[item]={};
    }
    atomicWriteJsonSync(resolveInside(BACKUPS_DIR, `${bkName}.json`), {timestamp:new Date().toISOString(),profiles:data});
    log(`Backup: ${bkName}`, 'info');
    return{ok:true,name:bkName,count:items.length};
  }catch(e){return{ok:false,msg:e.message};}
});

ipcMain.handle('backup-list', async () => {
  requireLicense();
  try{
    return fs.readdirSync(BACKUPS_DIR).filter(f=>f.endsWith('.json')).map(f=>{
      const stat=fs.statSync(path.join(BACKUPS_DIR,f));
      let count=0;
      try{count=Object.keys(JSON.parse(fs.readFileSync(path.join(BACKUPS_DIR,f),'utf8')).profiles||{}).length;}catch{}
      return{name:f.replace('.json',''),size:stat.size,mtime:stat.mtime,count};
    }).sort((a,b)=>new Date(b.mtime)-new Date(a.mtime));
  }catch{return[];}
});

ipcMain.handle('backup-restore', async (_, bkName) => {
  requireLicense();
  try{
    const safeBackupName = validateBackupName(bkName);
    const bk=JSON.parse(fs.readFileSync(resolveInside(BACKUPS_DIR, `${safeBackupName}.json`),'utf8'));
    assertPlainObject(bk, 'Backup');
    let restored=0;
    for(const[name,cfg] of Object.entries(bk.profiles||{})){
      const safeName = validateProfileName(name);
      assertPlainObject(cfg, 'Cấu hình profile');
      const pp=resolveInside(PROFILES_DIR,safeName);
      if(!fs.existsSync(pp))fs.mkdirSync(pp);
      atomicWriteJsonSync(resolveInside(pp, 'config.json'), protectProfileRecord(sanitizeProfileRecord(hydrateProfileRecord(cfg), {}, true)));
      restored++;
    }
    log(`Restore: ${bkName} (${restored})`, 'info');
    return{ok:true,restored};
  }catch(e){return{ok:false,msg:e.message};}
});

ipcMain.handle('backup-delete', async (_, bkName) => {
  requireLicense();
  try{
    const safeBackupName = validateBackupName(bkName);
    fs.unlinkSync(resolveInside(BACKUPS_DIR, `${safeBackupName}.json`));
    return{ok:true};
  }
  catch(e){return{ok:false,msg:e.message};}
});

// ── PROXY ──────────────────────────────────────────────────────────────────
async function httpGet(url) {
  try {
    return await requestWithRedirect(parseAllowedExternalUrl(url, ['https:']));
  } catch (e) {
    log(`httpGet error: ${safeErrorMessage(e)}`, 'error');
  }
  return {};
}

async function fetchJsonSafe(url, options = {}) {
  const safeUrl = parseAllowedExternalUrl(url, ['https:']);
  const controller = new AbortController();
  const timer = setTimeout(() => controller.abort(), 12_000);
  try {
    const response = await fetch(safeUrl, {
      ...options,
      redirect: 'error',
      signal: controller.signal
    });
    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    if (!response.body) return {};
    const reader = response.body.getReader();
    const chunks = [];
    let total = 0;
    while (true) {
      const { done, value } = await reader.read();
      if (done) break;
      total += value.byteLength;
      if (total > 2 * 1024 * 1024) {
        await reader.cancel();
        throw new Error('Response too large');
      }
      chunks.push(Buffer.from(value));
    }
    const text = Buffer.concat(chunks).toString('utf8');
    return text ? JSON.parse(text) : {};
  } finally {
    clearTimeout(timer);
  }
}

function validateProxyApiValue(value, label, maxLength = 4096) {
  if (typeof value !== 'string') throw new TypeError(`${label} không hợp lệ`);
  const normalized = value.trim();
  if (!normalized || normalized.length > maxLength || /[\u0000-\u001f\u007f]/.test(normalized)) {
    throw new TypeError(`${label} không hợp lệ`);
  }
  return normalized;
}

ipcMain.handle('proxy-fetch', async (_, payload) => {
  try{
    requireLicense();
    assertPlainObject(payload, 'Dữ liệu proxy');
    const apiKey = validateProxyApiValue(payload.apiKey, 'API key');
    const nhamang = String(payload.nhamang || 'random').slice(0, 100);
    const tinhthanh = String(payload.tinhthanh || '0').slice(0, 100);
    const url = new URL('https://proxyxoay.shop/api/get.php');
    url.searchParams.set('key', apiKey);
    url.searchParams.set('nhamang', nhamang);
    url.searchParams.set('tinhthanh', tinhthanh);
    const data=await httpGet(url.toString());
    if(data.status===100){
      const parts=data.proxyhttp.split(':');
      log(`Proxy: ${parts[0]}:${parts[1]}`,'info');
      return{ok:true,proxy:`${parts[0]}:${parts[1]}`,full:data.proxyhttp};
    }
    return{ok:false,msg:data.message||'API lỗi'};
  }catch(e){return{ok:false,msg:e.message};}
});
ipcMain.handle('proxy-rotate', async (_, payload) => {
  try {
    requireLicense();
    assertPlainObject(payload, 'Dữ liệu xoay proxy');
    const provider = validateProxyApiValue(payload.provider, 'Nhà cung cấp', 50);
    const apiKey = validateProxyApiValue(payload.apiKey, 'API key');
    const proxyKey = typeof payload.proxyKey === 'string' && payload.proxyKey.trim()
      ? validateProxyApiValue(payload.proxyKey, 'Proxy key')
      : '';
    if (!new Set(['nestproxy','mktproxy','kiotproxy','tmproxy','wwproxy','tinsoft','proxyvn','922proxy']).has(provider)) {
      throw new TypeError('Nhà cung cấp proxy không hợp lệ');
    }
    log(`Bắt đầu xoay proxy (${provider})`, 'info');
    
    if (provider === 'nestproxy') {
      if (!proxyKey) throw new TypeError('Proxy key không hợp lệ');
      const removeUrl = `https://nestproxy.com/api/client/proxy/remove?proxy_key=${encodeURIComponent(proxyKey)}`;
      const removeData = await fetchJsonSafe(removeUrl, {
        method: 'POST',
        headers: { 'user-api-key': apiKey }
      });
      log(`NestProxy remove response: ${JSON.stringify(removeData)}`, 'info');
      
      await new Promise(r => setTimeout(r, 1500));
      
      const availUrl = `https://nestproxy.com/api/client/proxy/available?proxy_key=${encodeURIComponent(proxyKey)}`;
      const data = await fetchJsonSafe(availUrl, {
        method: 'GET',
        headers: { 'user-api-key': apiKey }
      });
      log(`NestProxy available response: ${JSON.stringify(data)}`, 'info');
      
      let proxyStr = '';
      if (data) {
        if (data.data) {
          if (typeof data.data === 'object') {
            proxyStr = data.data.proxy || (data.data.ip ? `${data.data.ip}:${data.data.port || ''}` : '');
          } else if (typeof data.data === 'string') {
            proxyStr = data.data;
          }
        } else if (data.proxy) {
          proxyStr = data.proxy;
        } else if (data.ip) {
          proxyStr = `${data.ip}:${data.port || ''}`;
        }
      }
      
      if (proxyStr && proxyStr.includes(':') && proxyStr.length > 5) {
        return { ok: true, proxy: proxyStr.trim(), msg: 'Xoay NestProxy thành công!' };
      }
      
      const errMsg = data.message || data.msg || data.error || (data.status && data.status !== 'success' ? 'Lỗi máy chủ' : '') || 'Không thể lấy IP mới từ NestProxy';
      return { ok: false, msg: errMsg };
    } 
    
    if (provider === 'mktproxy') {
      const url = new URL('https://api.mktproxy.com/index.php/api/v1/proxy/change-ip');
      url.searchParams.set('key', apiKey);
      log(`Calling MKTProxy URL: ${url}`, 'info');
      const data = await fetchJsonSafe(url.toString());
      log(`MKTProxy response: ${JSON.stringify(data)}`, 'info');
      
      let proxyStr = '';
      if (data) {
        proxyStr = data.proxy || (data.data && (data.data.proxy || data.data.ip_port)) || '';
      }
      
      if (proxyStr && proxyStr.includes(':') && proxyStr.length > 5) {
        return { ok: true, proxy: proxyStr.trim(), msg: data.msg || data.message || 'Xoay MKTProxy thành công!' };
      }
      if (data && (data.code === 1 || data.status === true || data.ok === true)) {
        return { ok: true, proxy: proxyStr, msg: data.msg || data.message || 'Xoay MKTProxy thành công!' };
      }
      const errMsg = data.msg || data.message || data.error || 'Lỗi từ máy chủ MKTProxy';
      return { ok: false, msg: errMsg };
    }

    if (provider === 'kiotproxy') {
      const url = `https://api.kiotproxy.com/api/v1/proxies/new?key=${encodeURIComponent(apiKey)}`;
      log(`Calling KiotProxy: ${url}`, 'info');
      const data = await fetchJsonSafe(url);
      log(`KiotProxy response: ${JSON.stringify(data)}`, 'info');
      if (data && (data.code === 200 || data.success)) {
        const ip = data.data.ip || data.data.ipAddress || data.data.host || '127.0.0.1';
        const port = data.data.httpPort || data.data.port || 80;
        return { ok: true, proxy: `${ip}:${port}`, msg: data.message || 'Xoay KiotProxy thành công!' };
      }
      const errMsg = data.message || data.msg || 'Không xoay được KiotProxy (Đang trong thời gian chờ)';
      return { ok: false, msg: errMsg };
    }

    if (provider === 'tmproxy') {
      const url = 'https://api.tmproxy.com/api/proxy/get-new-ip';
      log(`Calling TMProxy: ${url}`, 'info');
      const data = await fetchJsonSafe(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ api_key: apiKey })
      });
      log(`TMProxy response: ${JSON.stringify(data)}`, 'info');
      if (data && data.code === 0 && data.data) {
        const proxyStr = data.data.ip_address + (data.data.port ? `:${data.data.port}` : '');
        return { ok: true, proxy: proxyStr, msg: data.message || 'Xoay TMProxy thành công!' };
      }
      const errMsg = data.message || 'Không xoay được TMProxy';
      return { ok: false, msg: errMsg };
    }

    if (provider === 'wwproxy') {
      const url = `https://wwproxy.com/api/client/proxy/available?key=${encodeURIComponent(apiKey)}`;
      log(`Calling WWProxy: ${url}`, 'info');
      const data = await fetchJsonSafe(url);
      log(`WWProxy response: ${JSON.stringify(data)}`, 'info');
      if (data && data.success && data.data) {
        return { ok: true, proxy: data.data.proxy, msg: data.message || 'Xoay WWProxy thành công!' };
      }
      const errMsg = data.message || 'Không xoay được WWProxy';
      return { ok: false, msg: errMsg };
    }

    if (provider === 'tinsoft') {
      const url = `https://proxy.tinsoftsv.com/api/changeKeyIp.php?key=${encodeURIComponent(apiKey)}&location=0`;
      log(`Calling Tinsoft: ${url}`, 'info');
      const data = await fetchJsonSafe(url);
      log(`Tinsoft response: ${JSON.stringify(data)}`, 'info');
      if (data && data.success) {
        return { ok: true, proxy: data.proxy, msg: 'Xoay Tinsoft thành công!' };
      }
      const errMsg = data.description || 'Không xoay được Tinsoft';
      return { ok: false, msg: errMsg };
    }

    if (provider === 'proxyvn') {
      const url = `https://proxy.vn/api/change-ip?key=${encodeURIComponent(apiKey)}`;
      log(`Calling ProxyVN: ${url}`, 'info');
      const data = await fetchJsonSafe(url);
      log(`ProxyVN response: ${JSON.stringify(data)}`, 'info');
      if (data && data.success) {
        return { ok: true, proxy: data.proxy || data.ip, msg: 'Xoay ProxyVN thành công!' };
      }
      const errMsg = data.msg || 'Không xoay được ProxyVN';
      return { ok: false, msg: errMsg };
    }

    if (provider === '922proxy') {
      const url = `https://api.922proxy.com/api/change-ip?key=${encodeURIComponent(apiKey)}`;
      log(`Calling 922Proxy: ${url}`, 'info');
      const data = await fetchJsonSafe(url);
      log(`922Proxy response: ${JSON.stringify(data)}`, 'info');
      if (data && (data.code === 0 || data.success)) {
        return { ok: true, proxy: data.proxy || data.ip, msg: 'Xoay 922Proxy thành công!' };
      }
      const errMsg = data.msg || 'Không xoay được 922Proxy';
      return { ok: false, msg: errMsg };
    }
    
    return { ok: false, msg: 'Provider không hợp lệ' };
  } catch (err) {
    log(`Lỗi kết nối API xoay: ${err.message}`, 'error');
    return { ok: false, msg: `Lỗi kết nối API: ${err.message}` };
  }
});

function checkHttpProxy(host, port, user, pass, timeout = 5000) {
  return new Promise((resolve) => {
    const socket = new net.Socket();
    let resolved = false;

    socket.setTimeout(timeout);
    socket.connect(port, host, () => {
      let req = `CONNECT google.com:443 HTTP/1.1\r\nHost: google.com:443\r\n`;
      if (user && pass) {
        const auth = Buffer.from(`${user}:${pass}`).toString('base64');
        req += `Proxy-Authorization: Basic ${auth}\r\n`;
      }
      req += `\r\n`;
      socket.write(req);
    });

    socket.on('data', (data) => {
      if (resolved) return;
      resolved = true;
      socket.destroy();
      const resStr = data.toString();
      if (resStr.includes(' 200 ') || resStr.includes('200 Connection established') || resStr.includes('200 OK')) {
        resolve({ valid: true });
      } else {
        resolve({ valid: false, msg: 'Proxy từ chối kết nối (Auth/Error)' });
      }
    });

    socket.on('error', (err) => {
      if (resolved) return;
      resolved = true;
      socket.destroy();
      resolve({ valid: false, msg: err.message });
    });

    socket.on('timeout', () => {
      if (resolved) return;
      resolved = true;
      socket.destroy();
      resolve({ valid: false, msg: 'Kết nối quá hạn (Timeout)' });
    });
  });
}

function checkSocks5Proxy(host, port, user, pass, timeout = 5000) {
  return new Promise((resolve) => {
    const socket = new net.Socket();
    let resolved = false;
    let step = 0;

    socket.setTimeout(timeout);
    socket.connect(port, host, () => {
      // Step 1: Send SOCKS5 greeting with support for no auth (0x00) and user/pass auth (0x02)
      const buf = Buffer.from([0x05, 0x02, 0x00, 0x02]);
      socket.write(buf);
      step = 1;
    });

    socket.on('data', (data) => {
      if (resolved) return;
      try {
        if (step === 1) {
          // Response to greeting: [0x05, method]
          if (data[0] !== 0x05) {
            resolved = true;
            socket.destroy();
            return resolve({ valid: false, msg: 'Không phải SOCKS5 server' });
          }
          const method = data[1];
          if (method === 0x00) {
            sendSocks5Connect(socket);
          } else if (method === 0x02) {
            if (!user || !pass) {
              resolved = true;
              socket.destroy();
              return resolve({ valid: false, msg: 'Proxy yêu cầu tài khoản/mật khẩu' });
            }
            sendSocks5Auth(socket, user, pass);
          } else {
            resolved = true;
            socket.destroy();
            return resolve({ valid: false, msg: `Phương thức xác thực không hỗ trợ (${method})` });
          }
        } else if (step === 2) {
          // Response to auth: [0x01, status]
          if (data[0] !== 0x01 || data[1] !== 0x00) {
            resolved = true;
            socket.destroy();
            return resolve({ valid: false, msg: 'Sai tài khoản hoặc mật khẩu proxy' });
          }
          sendSocks5Connect(socket);
        } else if (step === 3) {
          // Response to connect: [0x05, status, 0x00, ...]
          resolved = true;
          socket.destroy();
          if (data[0] === 0x05 && data[1] === 0x00) {
            resolve({ valid: true });
          } else {
            resolve({ valid: false, msg: `Kết nối thất bại (SOCKS5 Error: ${data[1]})` });
          }
        }
      } catch (e) {
        resolved = true;
        socket.destroy();
        resolve({ valid: false, msg: e.message });
      }
    });

    function sendSocks5Auth(sock, username, password) {
      const uBuf = Buffer.from(username);
      const pBuf = Buffer.from(password);
      const req = Buffer.alloc(3 + uBuf.length + pBuf.length);
      req[0] = 0x01;
      req[1] = uBuf.length;
      uBuf.copy(req, 2);
      req[2 + uBuf.length] = pBuf.length;
      pBuf.copy(req, 3 + uBuf.length);
      sock.write(req);
      step = 2;
    }

    function sendSocks5Connect(sock) {
      const target = 'google.com';
      const tBuf = Buffer.from(target);
      const req = Buffer.alloc(7 + tBuf.length);
      req[0] = 0x05;
      req[1] = 0x01;
      req[2] = 0x00;
      req[3] = 0x03;
      req[4] = tBuf.length;
      tBuf.copy(req, 5);
      req[5 + tBuf.length] = 0x01; // Port high byte of 443
      req[6 + tBuf.length] = 0xbb; // Port low byte of 443
      sock.write(req);
      step = 3;
    }

    socket.on('error', (err) => {
      if (resolved) return;
      resolved = true;
      socket.destroy();
      resolve({ valid: false, msg: err.message });
    });

    socket.on('timeout', () => {
      if (resolved) return;
      resolved = true;
      socket.destroy();
      resolve({ valid: false, msg: 'Kết nối quá hạn (Timeout)' });
    });
  });
}

function checkSocks5ProxyFull(host, port, user, pass, timeout = 8000) {
  return new Promise((resolve) => {
    const socket = new net.Socket();
    let resolved = false;
    let step = 0;
    let httpData = '';
    const startTime = Date.now();

    socket.setTimeout(timeout);
    
    socket.connect(port, host, () => {
      const buf = Buffer.from([0x05, 0x02, 0x00, 0x02]);
      socket.write(buf);
      step = 1;
    });

    socket.on('data', (data) => {
      if (resolved) return;
      try {
        if (step === 1) {
          if (data[0] !== 0x05) {
            resolved = true;
            socket.destroy();
            return resolve({ valid: false, msg: 'Không phải SOCKS5 server' });
          }
          const method = data[1];
          if (method === 0x00) {
            sendSocks5Connect();
          } else if (method === 0x02) {
            if (!user || !pass) {
              resolved = true;
              socket.destroy();
              return resolve({ valid: false, msg: 'Proxy yêu cầu tài khoản/mật khẩu' });
            }
            sendSocks5Auth();
          } else {
            resolved = true;
            socket.destroy();
            return resolve({ valid: false, msg: `Phương thức xác thực không hỗ trợ (${method})` });
          }
        } else if (step === 2) {
          if (data[0] !== 0x01 || data[1] !== 0x00) {
            resolved = true;
            socket.destroy();
            return resolve({ valid: false, msg: 'Sai tài khoản hoặc mật khẩu proxy' });
          }
          sendSocks5Connect();
        } else if (step === 3) {
          if (data[0] === 0x05 && data[1] === 0x00) {
            const req = `GET /json HTTP/1.1\r\nHost: ip-api.com\r\nConnection: close\r\nUser-Agent: Mozilla/5.0\r\n\r\n`;
            socket.write(req);
            step = 4;
          } else {
            resolved = true;
            socket.destroy();
            resolve({ valid: false, msg: `Kết nối qua SOCKS5 thất bại (Code: ${data[1]})` });
          }
        } else if (step === 4) {
          httpData += data.toString('utf8');
        }
      } catch (e) {
        resolved = true;
        socket.destroy();
        resolve({ valid: false, msg: e.message });
      }
    });

    function sendSocks5Auth() {
      const uBuf = Buffer.from(user);
      const pBuf = Buffer.from(pass);
      const req = Buffer.alloc(3 + uBuf.length + pBuf.length);
      req[0] = 0x01;
      req[1] = uBuf.length;
      uBuf.copy(req, 2);
      req[2 + uBuf.length] = pBuf.length;
      pBuf.copy(req, 3 + uBuf.length);
      socket.write(req);
      step = 2;
    }

    function sendSocks5Connect() {
      const target = 'ip-api.com';
      const tBuf = Buffer.from(target);
      const req = Buffer.alloc(7 + tBuf.length);
      req[0] = 0x05;
      req[1] = 0x01;
      req[2] = 0x00;
      req[3] = 0x03;
      req[4] = tBuf.length;
      tBuf.copy(req, 5);
      req[5 + tBuf.length] = 0x00;
      req[6 + tBuf.length] = 0x50;
      socket.write(req);
      step = 3;
    }

    socket.on('end', () => {
      if (resolved) return;
      resolved = true;
      socket.destroy();
      
      if (step === 4 && httpData) {
        parseHttpResponse(httpData);
      } else {
        resolve({ valid: false, msg: 'Kết nối bị ngắt đột ngột' });
      }
    });

    socket.on('error', (err) => {
      if (resolved) return;
      resolved = true;
      socket.destroy();
      resolve({ valid: false, msg: err.message });
    });

    socket.on('timeout', () => {
      if (resolved) return;
      resolved = true;
      socket.destroy();
      resolve({ valid: false, msg: 'Kết nối quá hạn (Timeout)' });
    });

    function parseHttpResponse(responseStr) {
      try {
        const bodyIndex = responseStr.indexOf('\r\n\r\n');
        if (bodyIndex === -1) {
          return resolve({ valid: false, msg: 'Phản hồi HTTP không hợp lệ' });
        }
        
        const body = responseStr.substring(bodyIndex + 4).trim();
        const parsed = JSON.parse(body);
        if (parsed && (parsed.status === 'success' || parsed.query)) {
          resolve({
            valid: true,
            info: {
              ip: parsed.query,
              country: parsed.country || 'Vietnam',
              countryCode: parsed.countryCode || 'VN',
              region: parsed.regionName || '',
              city: parsed.city || '',
              isp: parsed.isp || 'Viettel',
              timezone: parsed.timezone || 'Asia/Ho_Chi_Minh',
              asn: parsed.as || 'AS7552',
              speed: `${Date.now() - startTime}ms`
            }
          });
        } else {
          resolve({ valid: false, msg: `Lỗi API IP: ${parsed.message || 'Không có dữ liệu'}` });
        }
      } catch (err) {
        resolve({ valid: false, msg: `Lỗi đọc JSON phản hồi: ${err.message}` });
      }
    }
  });
}

ipcMain.handle('proxy-check', async (_, proxy) => {
  try {
    requireLicense();
    const rawProxy = typeof proxy === 'object' && proxy !== null ? (proxy.proxy || '') : proxy;
    const typeHint = typeof proxy === 'object' && proxy !== null ? (proxy.type || proxy.proxyType || 'http') : 'http';
    const parsed = parseProxyString(rawProxy, typeHint);
    if (!parsed) return { valid: false, msg: 'Địa chỉ Proxy không hợp lệ hoặc thiếu Port' };
    
    let res;
    if (parsed.type.includes('socks')) {
      res = await checkSocks5Proxy(parsed.host, parsed.port, parsed.user, parsed.pass);
    } else {
      res = await checkHttpProxy(parsed.host, parsed.port, parsed.user, parsed.pass);
    }
    
    if (res.valid) {
      return {
        valid: true,
        ip: parsed.host,
        port: parsed.port,
        type: parsed.type.toUpperCase() + (parsed.hasAuth ? ' (Auth)' : ' (Anon)')
      };
    } else {
      return { valid: false, msg: res.msg || 'Không kết nối được' };
    }
  } catch (err) {
    return { valid: false, msg: err.message };
  }
});

ipcMain.handle('proxy-check-full', async (_, proxy) => {
  try {
    requireLicense();
    const rawProxy = typeof proxy === 'object' && proxy !== null ? (proxy.proxy || '') : proxy;
    const typeHint = typeof proxy === 'object' && proxy !== null ? (proxy.type || proxy.proxyType || 'http') : 'http';
    const parsed = parseProxyString(rawProxy, typeHint);
    if (!parsed) return { ok: false, msg: 'Địa chỉ Proxy không hợp lệ hoặc thiếu Port' };
    
    const startTime = Date.now();
    
    if (parsed.type.includes('socks')) {
      const res = await checkSocks5ProxyFull(parsed.host, parsed.port, parsed.user, parsed.pass, 8000);
      if (res.valid) {
        if (res.info) proxyGeoCache.set(parsed.host, res.info);
        return {
          ok: true,
          info: res.info
        };
      } else {
        return { ok: false, msg: res.msg || 'Không kết nối được SOCKS5' };
      }
    } else {
      const data = await new Promise((resolve, reject) => {
        const options = {
          host: parsed.host,
          port: parsed.port,
          path: 'http://ip-api.com/json',
          method: 'GET',
          headers: {
            'Host': 'ip-api.com',
            'User-Agent': 'Mozilla/5.0'
          }
        };

        if (parsed.hasAuth) {
          const auth = Buffer.from(`${parsed.user}:${parsed.pass}`).toString('base64');
          options.headers['Proxy-Authorization'] = `Basic ${auth}`;
        }

        const req = http.request(options, (res) => {
          if (res.statusCode !== 200) {
            reject(new Error(`Proxy phản hồi HTTP ${res.statusCode}`));
            return;
          }
          let body = '';
          res.on('data', chunk => body += chunk);
          res.on('end', () => {
            try {
              const jsonRes = JSON.parse(body);
              if (jsonRes && (jsonRes.status === 'success' || jsonRes.query)) {
                resolve(jsonRes);
              } else {
                reject(new Error(jsonRes.message || 'Không có dữ liệu IP'));
              }
            } catch (e) {
              reject(new Error('Phản hồi không phải JSON từ proxy'));
            }
          });
        });

        req.on('error', (err) => {
          reject(err);
        });

        req.setTimeout(8000, () => {
          req.destroy();
          reject(new Error('Kết nối quá hạn (Timeout)'));
        });

        req.end();
      });

      const speed = Date.now() - startTime;
      const geoResult = {
        ip: data.query || parsed.host,
        port: parsed.port,
        country: data.country || 'Vietnam',
        countryCode: data.countryCode || 'VN',
        region: data.regionName || '',
        city: data.city || '',
        isp: data.isp || 'Viettel',
        timezone: data.timezone || 'Asia/Ho_Chi_Minh',
        asn: data.as || 'AS7552',
        speed: `${speed}ms`
      };
      proxyGeoCache.set(parsed.host, geoResult);
      return {
        ok: true,
        info: geoResult
      };
    }
  } catch (e) {
    return { ok: false, msg: `Proxy không hoạt động: ${e.message}` };
  }
});

ipcMain.handle('profiles-clear-data', async (_, name, type) => {
  requireLicense();
  const safeName = validateProfileName(name);
  if (!['cache', 'cookies', 'history', 'all'].includes(type)) throw new TypeError('Loại dữ liệu không hợp lệ');
  const pp = resolveInside(PROFILES_DIR, safeName);
  if (!fs.existsSync(pp)) return { ok:false, msg:'Profile không tồn tại' };

  try {
    const paths = [];
    if (type === 'cache' || type === 'all') {
      paths.push(path.join(pp, 'Default', 'Cache'));
      paths.push(path.join(pp, 'Default', 'Code Cache'));
    }
    if (type === 'cookies' || type === 'all') {
      paths.push(path.join(pp, 'Default', 'Cookies'));
      paths.push(path.join(pp, 'Default', 'Network', 'Cookies'));
      paths.push(path.join(pp, 'Default', 'Local Storage'));
    }
    if (type === 'history' || type === 'all') {
      paths.push(path.join(pp, 'Default', 'History'));
      paths.push(path.join(pp, 'Default', 'History-journal'));
    }

    const rmRec = (targetPath) => {
      if (fs.existsSync(targetPath)) {
        try {
          fs.rmSync(targetPath, { recursive: true, force: true });
        } catch (e) {
          log(`Lỗi xoá ${targetPath}: ${e.message}`, 'warning');
        }
      }
    };

    paths.forEach(p => rmRec(p));
    log(`Đã dọn dẹp dữ liệu (${type}) cho profile: ${safeName}`, 'info');
    return { ok: true };
  } catch (err) {
    return { ok: false, msg: err.message };
  }
});

// ── CONFIG ─────────────────────────────────────────────────────────────────
ipcMain.handle('cfg-get', async () => {
  const c = getCfg();
  if (!c.chromePath || !fs.existsSync(c.chromePath)) {
    const auto = findChrome();
    if (auto && fs.existsSync(auto)) {
      c.chromePath = auto;
      try { saveCfg(c); } catch {}
    }
  }
  return Object.fromEntries(Object.keys(defaultCfg()).map(key => [key, c[key]]));
});
ipcMain.handle('cfg-save', async (_, c) => {
  assertPlainObject(c, 'Cài đặt');
  const requested = sanitizeConfig(c, defaultCfg());
  const existing = getCfg();
  for (const key of Object.keys(defaultCfg())) existing[key] = requested[key];
  saveCfg(existing);
  log('Cài đặt đã lưu','info');
  return{ok:true};
});
ipcMain.handle('cfg-browse-chrome', async () => {
  const r=await dialog.showOpenDialog(mainWindow,{title:'Chọn chrome.exe',filters:[{name:'Executable',extensions:['exe','']}],properties:['openFile']});
  return r.canceled?{ok:false}:{ok:true,path:r.filePaths[0]};
});
ipcMain.handle('cfg-detect-chrome', async () => {
  const p=findChrome();
  if(p){log(`Chrome: ${p}`,'info');return{ok:true,path:p};}
  return{ok:false};
});
ipcMain.handle('cfg-open-chrome-download', async () => shell.openExternal('https://www.google.com/chrome/'));
const SECURE_STATE_KEYS = new Set(['proxyCheckHistory','proxyRotateHistory','rotateProxyCurrentInfo','managedProxies']);
ipcMain.handle('secure-state-get', async (_, key) => {
  requireLicense();
  if (!SECURE_STATE_KEYS.has(key)) throw new TypeError('Secure state key không hợp lệ');
  const raw = getEncryptedSecret(`state:${key}`);
  if (!raw) return null;
  try { return JSON.parse(raw); } catch { return null; }
});
ipcMain.handle('secure-state-set', async (_, key, value) => {
  requireLicense();
  if (!SECURE_STATE_KEYS.has(key)) throw new TypeError('Secure state key không hợp lệ');
  const serialized = JSON.stringify(value);
  if (serialized.length > 2_000_000) throw new Error('Secure state vượt quá giới hạn');
  saveEncryptedSecret(`state:${key}`, serialized);
  return { ok:true };
});

// ── LOGS ───────────────────────────────────────────────────────────────────
ipcMain.handle('logs-get',      async () => logBuffer);
ipcMain.handle('logs-clear',    async () => { logBuffer.length=0; return{ok:true}; });
ipcMain.handle('logs-open-dir', async () => shell.openPath(LOGS_DIR));

// ── UTILS ──────────────────────────────────────────────────────────────────
ipcMain.handle('utils-open-profiles-dir', async () => shell.openPath(PROFILES_DIR));
ipcMain.handle('utils-open-backups-dir',  async () => shell.openPath(BACKUPS_DIR));
ipcMain.handle('utils-version',  async () => app.getVersion());
ipcMain.handle('utils-platform', async () => process.platform);
ipcMain.handle('utils-running',  async () => Object.keys(running));
ipcMain.handle('utils-open-url', async (_, url) => {
  const safeUrl = parseAllowedExternalUrl(url, ['https:']);
  await shell.openExternal(safeUrl);
  return { ok:true };
});
ipcMain.handle('utils-read-page', async (_, pageName) => {
  try {
    const safePageName = validatePageName(pageName);
    const pagePath = resolveInside(path.join(__dirname, 'pages'), `${safePageName}.html`);
    if (fs.existsSync(pagePath)) {
      return fs.readFileSync(pagePath, 'utf8');
    }
    return '<div style="padding: 20px; color: var(--red);">Không tìm thấy trang.</div>';
  } catch (err) {
    return '<div style="padding: 20px; color: var(--red);">Không thể đọc trang.</div>';
  }
});

// ── EXTENSION MANAGER IPC HANDLERS ─────────────────────────────────────────
ipcMain.handle('extensions-get', async (_, profileName) => {
  requireLicense();
  const pp = resolveInside(PROFILES_DIR, validateProfileName(profileName));
  return ExtensionManagerService.getExtensions(pp);
});

ipcMain.handle('extensions-toggle', async (_, profileName, extId, enableState) => {
  requireLicense();
  const pp = resolveInside(PROFILES_DIR, validateProfileName(profileName));
  return ExtensionManagerService.toggleExtension(pp, validateExtensionId(extId), Boolean(enableState));
});

ipcMain.handle('extensions-toggle-all', async (_, profileName, enableState) => {
  requireLicense();
  const pp = resolveInside(PROFILES_DIR, validateProfileName(profileName));
  return ExtensionManagerService.toggleAllExtensions(pp, enableState);
});

ipcMain.handle('extensions-uninstall', async (_, profileName, extId) => {
  requireLicense();
  const pp = resolveInside(PROFILES_DIR, validateProfileName(profileName));
  return ExtensionManagerService.uninstallExtension(pp, validateExtensionId(extId));
});

ipcMain.handle('extensions-clone', async (_, srcProfile, destProfile) => {
  requireLicense();
  const src = resolveInside(PROFILES_DIR, validateProfileName(srcProfile));
  const dest = resolveInside(PROFILES_DIR, validateProfileName(destProfile));
  return ExtensionManagerService.cloneExtensions(src, dest);
});

ipcMain.handle('extensions-install-store', async (_, profileName, extId) => {
  requireLicense();
  const pp = resolveInside(PROFILES_DIR, validateProfileName(profileName));
  return ChromeStoreInstaller.downloadAndInstall(validateChromeStoreId(extId), pp);
});

ipcMain.handle('extensions-install-file', async (_, profileName, filePath, fileType) => {
  requireLicense();
  const pp = resolveInside(PROFILES_DIR, validateProfileName(profileName));
  if (!['crx', 'zip'].includes(fileType)) throw new TypeError('Loại extension không hợp lệ');
  const resolvedFile = path.resolve(String(filePath));
  const expectedExtension = fileType === 'crx' ? '.crx' : '.zip';
  if (!fs.existsSync(resolvedFile) || !fs.statSync(resolvedFile).isFile() || path.extname(resolvedFile).toLowerCase() !== expectedExtension) {
    return { ok:false, msg:'Tệp extension không hợp lệ' };
  }
  if (fs.statSync(resolvedFile).size > 100 * 1024 * 1024) return { ok:false, msg:'Tệp extension vượt quá 100 MB' };
  if (fileType === 'crx') {
    return CRXInstaller.install(resolvedFile, pp);
  } else {
    return ZIPInstaller.install(resolvedFile, pp);
  }
});

ipcMain.handle('extensions-open-folder', async (_, profileName, extId) => {
  requireLicense();
  const pp = resolveInside(PROFILES_DIR, validateProfileName(profileName));
  const extPath = resolveInside(pp, 'extensions', validateExtensionId(extId));
  if (fs.existsSync(extPath)) {
    shell.openPath(extPath);
    return { ok: true };
  }
  return { ok: false, msg: 'Thư mục không tồn tại' };
});

// ── GROUPS ─────────────────────────────────────────────────────────────────
ipcMain.handle('groups-get', async () => getGroups());
ipcMain.handle('groups-save', async (_, groups) => {
  requireLicense();
  if (!Array.isArray(groups) || groups.length > 100) throw new TypeError('Danh sách nhóm không hợp lệ');
  const safeGroups = sanitizeGroups(groups);
  const ok = saveGroups(safeGroups);
  if (ok) log('Đã lưu danh sách nhóm', 'info');
  return { ok };
});

// ── LICENSE & AUTHENTICATION ───────────────────────────────────────────────
ipcMain.handle('license-get-info', async () => {
  const cfg = getCfg();
  return {
    hwid: getHWID(),
    email: cfg.userEmail || '',
    name: cfg.userName || '',
    avatar: cfg.userAvatar || '',
    key: cfg.licenseKey ? `${cfg.licenseKey.slice(0, 4)}••••${cfg.licenseKey.slice(-4)}` : '',
    expiry: cfg.licenseExpiry || '',
    isValid: isLicenseValid,
    msg: licenseMsg
  };
});

let localServer = null;
const OAUTH_STATE_SECRET_KEY = 'pendingOAuthStates';
const OAUTH_STATE_TTL_MS = 10 * 60 * 1000;
const OAUTH_STATE_MAX_PENDING = 5;
const pendingOAuthStates = new Map();
let pendingOAuthStatesLoaded = false;

function assertSenderPage(event, allowedPages) {
  const rawUrl = event?.senderFrame?.url || event?.sender?.getURL?.() || '';
  try {
    const parsed = new URL(rawUrl);
    if (parsed.protocol !== 'file:') throw new Error();
    const senderPath = resolveInside(__dirname, path.relative(__dirname, fileURLToPath(parsed)));
    const relativePage = path.relative(__dirname, senderPath).split(path.sep).join('/');
    if (!allowedPages.includes(relativePage)) throw new Error();
  } catch {
    throw new Error('Trang hiện tại không được phép thực hiện thao tác này');
  }
}

function prunePendingOAuthStates(now = Date.now()) {
  for (const [state, expiresAt] of pendingOAuthStates) {
    if (
      typeof state !== 'string' ||
      state.length < 32 ||
      state.length > 256 ||
      !Number.isFinite(expiresAt) ||
      expiresAt <= now
    ) {
      pendingOAuthStates.delete(state);
    }
  }
  while (pendingOAuthStates.size > OAUTH_STATE_MAX_PENDING) {
    pendingOAuthStates.delete(pendingOAuthStates.keys().next().value);
  }
}

function loadPendingOAuthStates() {
  if (pendingOAuthStatesLoaded) return;
  pendingOAuthStatesLoaded = true;
  try {
    const stored = getEncryptedSecret(OAUTH_STATE_SECRET_KEY);
    if (stored) {
      const parsed = JSON.parse(stored);
      if (parsed && typeof parsed === 'object' && !Array.isArray(parsed)) {
        for (const [state, expiresAt] of Object.entries(parsed)) {
          pendingOAuthStates.set(state, Number(expiresAt));
        }
      }
    }
  } catch (err) {
    log(`Không thể khôi phục phiên OAuth đang chờ: ${safeErrorMessage(err)}`, 'warn');
  }
  prunePendingOAuthStates();
}

function persistPendingOAuthStates() {
  try {
    prunePendingOAuthStates();
    const serialized = pendingOAuthStates.size
      ? JSON.stringify(Object.fromEntries(pendingOAuthStates))
      : '';
    saveEncryptedSecret(OAUTH_STATE_SECRET_KEY, serialized);
  } catch (err) {
    log(`Không thể lưu phiên OAuth đang chờ: ${safeErrorMessage(err)}`, 'warn');
  }
}

function issueOAuthState() {
  loadPendingOAuthStates();
  prunePendingOAuthStates();
  let state;
  do {
    state = crypto.randomBytes(32).toString('base64url');
  } while (pendingOAuthStates.has(state));
  pendingOAuthStates.set(state, Date.now() + OAUTH_STATE_TTL_MS);
  prunePendingOAuthStates();
  persistPendingOAuthStates();
  return state;
}

function consumeOAuthState(state) {
  loadPendingOAuthStates();
  prunePendingOAuthStates();
  const supplied = typeof state === 'string' ? state : '';
  const suppliedBytes = Buffer.from(supplied, 'utf8');
  let matchedState = '';

  for (const candidate of pendingOAuthStates.keys()) {
    const expectedBytes = Buffer.from(candidate, 'utf8');
    if (
      suppliedBytes.length === expectedBytes.length &&
      crypto.timingSafeEqual(suppliedBytes, expectedBytes)
    ) {
      matchedState = candidate;
    }
  }

  if (!matchedState) {
    persistPendingOAuthStates();
    throw new Error('Phiên đăng nhập không hợp lệ hoặc đã hết hạn');
  }
  pendingOAuthStates.delete(matchedState);
  persistPendingOAuthStates();
}

function startLocalAuthServer() {
  if (localServer) return;
  localServer = http.createServer(async (req, res) => {
    try {
      const parsedUrl = new URL(req.url, `http://${req.headers.host}`);
      if (parsedUrl.pathname === '/auth-callback') {
        const accessToken = parsedUrl.searchParams.get('access_token');
        const state = parsedUrl.searchParams.get('state');
        if (accessToken) {
          consumeOAuthState(state);
          res.writeHead(200, { 'Content-Type': 'application/json' });
          res.end(JSON.stringify({ ok: true }));
          void handleOAuthAccessToken(accessToken);
        } else {
          res.writeHead(400, { 'Content-Type': 'application/json' });
          res.end(JSON.stringify({ ok: false, msg: 'Missing access token' }));
        }
      } else if (parsedUrl.pathname === '/ip') {
        res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
        res.end(getIpCheckHtml());
      } else if (parsedUrl.pathname === '/logo') {
        const logoPath = path.join(__dirname, 'image', 'logo-app-vmax45.png');
        if (fs.existsSync(logoPath)) {
          res.writeHead(200, { 'Content-Type': 'image/png' });
          fs.createReadStream(logoPath).pipe(res);
        } else {
          res.writeHead(404);
          res.end();
        }
      } else {
        res.writeHead(404);
        res.end();
      }
    } catch (e) {
      res.writeHead(500);
      res.end();
    }
  });
  
  localServer.once('error', (err) => {
    const failedServer = localServer;
    localServer = null;
    try { failedServer?.close(); } catch {}
    if (err.code === 'EADDRINUSE') {
      log('Cổng auth 34297 đang được tiến trình khác sử dụng. Hãy đóng bản V-ShopeeBoost cũ rồi mở lại ứng dụng.', 'error');
    } else {
      log(`Local auth server error: ${safeErrorMessage(err)}`, 'error');
    }
  });
  
  localServer.listen(34297, '127.0.0.1', () => {
    log('Local auth server listening on http://127.0.0.1:34297', 'info');
  });
}

async function handleOAuthAccessToken(accessToken) {
  try {
    if (
      typeof accessToken !== 'string' ||
      !accessToken ||
      accessToken.length > 8192 ||
      /[\u0000-\u001f\u007f]/.test(accessToken)
    ) {
      throw new Error('Access token không hợp lệ');
    }
    log('Received OAuth access token. Synchronizing user...', 'info');
    const userInfo = await getGoogleUserInfo(accessToken);
    if (userInfo && userInfo.email) {
      const cfg = getCfg();
      cfg.userEmail = userInfo.email;
      cfg.userName = userInfo.name || userInfo.email.split('@')[0];
      cfg.userAvatar = userInfo.picture || '';
      saveCfg(cfg);
      
      if (cfg.licenseKey) {
        const res = await checkLicenseWeb(cfg.licenseKey);
        if (res && res.status) {
          saveLicenseCache(cfg.licenseKey, res.expire);
          isLicenseValid = true;
          licenseMsg = `Bản quyền hợp lệ. Hết hạn: ${res.expire}`;
          if (mainWindow && !mainWindow.isDestroyed()) {
            mainWindow.loadFile(path.join(__dirname, 'index.html'));
          }
        } else {
          isLicenseValid = false;
          licenseMsg = res.message || 'Tài khoản chưa được kích hoạt bản quyền';
          if (mainWindow && !mainWindow.isDestroyed()) {
            mainWindow.loadFile(path.join(__dirname, 'pages', 'activation.html'));
          }
        }
      } else {
        isLicenseValid = false;
        licenseMsg = 'Chưa kích hoạt bản quyền';
        if (mainWindow && !mainWindow.isDestroyed()) {
          mainWindow.loadFile(path.join(__dirname, 'pages', 'activation.html'));
        }
      }
      if (mainWindow && !mainWindow.isDestroyed()) {
        mainWindow.webContents.send('login-success', { email: cfg.userEmail, name: cfg.userName });
      }
    }
  } catch (err) {
    log(`Lỗi xử lý local token: ${err.message}`, 'error');
  }
}

async function handleDeepLink(urlStr) {
  try {
    log(`Deep link received: ${redactSecrets(urlStr)}`, 'info');
    const { accessToken, state } = parseOAuthCallbackParams(urlStr);
    consumeOAuthState(state);
    log('Access token parsed from deep link. Synchronizing user...', 'info');
    await handleOAuthAccessToken(accessToken);
  } catch (err) {
    log(`Lỗi xử lý deep link: ${err.message}`, 'error');
  }
}

ipcMain.handle('license-login-google', async (event) => {
  assertSenderPage(event, ['pages/activation.html']);
  const clientId = '326337579332-7cqnbohkjjjgrd2n6nnq0bsk4cn4q7k9.apps.googleusercontent.com';
  const redirectUri = 'https://app.vmaxtech.vn/auth/google';
  const oauthState = issueOAuthState();
  const authUrl = `https://accounts.google.com/o/oauth2/v2/auth?client_id=${clientId}&redirect_uri=${encodeURIComponent(redirectUri)}&response_type=token&scope=profile%20email&state=${encodeURIComponent(oauthState)}`;
  
  try {
    shell.openExternal(authUrl);
    return { ok: true, msg: 'Đang mở trình duyệt hệ thống...' };
  } catch (err) {
    return { ok: false, msg: `Không thể mở trình duyệt: ${err.message}` };
  }
});

ipcMain.handle('license-login-manual', async (event, tokenOrUrl) => {
  assertSenderPage(event, ['pages/activation.html']);
  try {
    if (typeof tokenOrUrl !== 'string' || tokenOrUrl.length > 8192) throw new Error('Token không hợp lệ');
    let accessToken = tokenOrUrl.trim();
    if (tokenOrUrl.includes('access_token=')) {
      const hash = tokenOrUrl.split('access_token=')[1];
      accessToken = hash.split('&')[0];
    }
    
    if (!accessToken) return { ok: false, msg: 'Không tìm thấy Access Token trong nội dung dán' };
    
    const userInfo = await getGoogleUserInfo(accessToken);
    if (userInfo && userInfo.email) {
      const cfg = getCfg();
      cfg.userEmail = userInfo.email;
      cfg.userName = userInfo.name || userInfo.email.split('@')[0];
      cfg.userAvatar = userInfo.picture || '';
      saveCfg(cfg);
      
      if (cfg.licenseKey) {
        const res = await checkLicenseWeb(cfg.licenseKey);
        if (res && res.status) {
          saveLicenseCache(cfg.licenseKey, res.expire);
          isLicenseValid = true;
          licenseMsg = `Bản quyền hợp lệ. Hết hạn: ${res.expire}`;
          if (mainWindow && !mainWindow.isDestroyed()) {
            mainWindow.loadFile(path.join(__dirname, 'index.html'));
          }
        } else {
          isLicenseValid = false;
          licenseMsg = res.message || 'Tài khoản chưa được kích hoạt bản quyền';
          if (mainWindow && !mainWindow.isDestroyed()) {
            mainWindow.loadFile(path.join(__dirname, 'pages', 'activation.html'));
          }
        }
      } else {
        isLicenseValid = false;
        licenseMsg = 'Chưa kích hoạt bản quyền';
        if (mainWindow && !mainWindow.isDestroyed()) {
          mainWindow.loadFile(path.join(__dirname, 'pages', 'activation.html'));
        }
      }
      return { ok: true, user: { email: cfg.userEmail, name: cfg.userName } };
    } else {
      return { ok: false, msg: 'Không thể lấy thông tin profile từ Google' };
    }
  } catch (err) {
    return { ok: false, msg: `Lỗi đăng nhập thủ công: ${err.message}` };
  }
});

async function getGoogleUserInfo(accessToken) {
  try {
    const res = await requestWithRedirect('https://www.googleapis.com/oauth2/v3/userinfo', {
      headers: { Authorization: `Bearer ${accessToken}` }
    });
    return res;
  } catch (err) {
    log(`Lỗi lấy thông tin Google User Info: ${err.message}`, 'error');
    return null;
  }
}

ipcMain.handle('license-activate', async (_, key) => {
  if (!key) return { ok: false, msg: 'Vui lòng nhập mã kích hoạt' };
  const cfg = getCfg();
  if (!cfg.userEmail) return { ok: false, msg: 'Vui lòng đăng nhập Google trước' };

  try {
    const res = await checkLicenseWeb(key);
    if (res && res.status) {
      saveLicenseCache(key, res.expire);
      isLicenseValid = true;
      licenseMsg = `Kích hoạt thành công. Hết hạn: ${res.expire}`;
      if (mainWindow && !mainWindow.isDestroyed()) {
        mainWindow.loadFile(path.join(__dirname, 'index.html'));
      }
      return { ok: true, msg: 'Kích hoạt thành công!' };
    } else {
      return { ok: false, msg: res.message || 'Mã kích hoạt không đúng hoặc hết hạn' };
    }
  } catch (err) {
    return { ok: false, msg: `Lỗi kích hoạt: ${err.message}` };
  }
});

ipcMain.handle('license-logout', async () => {
  const cfg = getCfg();
  delete cfg.userEmail;
  delete cfg.userName;
  delete cfg.userAvatar;
  delete cfg.licenseKey;
  delete cfg.licenseExpiry;
  delete cfg.licenseSig;
  saveCfg(cfg);
  isLicenseValid = false;
  licenseMsg = 'Chưa đăng nhập tài khoản';
  
  if (mainWindow && !mainWindow.isDestroyed()) {
    mainWindow.loadFile(path.join(__dirname, 'pages', 'activation.html'));
  }
  return { ok: true };
});

// ── ADMIN PANEL APIS ────────────────────────────────────────────────────────
const adminSessions = new Map();
const ADMIN_ACTIONS = new Set([
  'admin_list', 'admin_create', 'admin_update', 'admin_delete', 'admin_list_users',
  'admin_renew_user_key', 'admin_delete_user', 'admin_send_user_notification',
  'admin_send_broadcast'
]);

function grantAdminSession(event) {
  adminSessions.set(event.sender.id, Date.now() + 30 * 60 * 1000);
}

function requireAdminSession(event) {
  const expiresAt = adminSessions.get(event.sender.id) || 0;
  if (expiresAt <= Date.now()) {
    adminSessions.delete(event.sender.id);
    throw new Error('Phiên Admin đã hết hạn. Vui lòng đăng nhập lại.');
  }
  adminSessions.set(event.sender.id, Date.now() + 30 * 60 * 1000);
}

ipcMain.handle('admin-login', async (event, username, password) => {
  assertSenderPage(event, ['admin/login.html']);
  if (username !== 'admin' || typeof password !== 'string' || password.length < 8 || password.length > 256) {
    return { ok: false, msg: 'Tài khoản hoặc mật khẩu không chính xác' };
  }
  try {
    const res = await requestPostJson(LICENSE_API_URL, { action:'admin_list', password });
    if (!res || res.ok !== true) return { ok:false, msg:'Tài khoản hoặc mật khẩu không chính xác' };
    saveEncryptedSecret('adminApiPassword', password);
    grantAdminSession(event);
    return { ok: true, msg: 'Đăng nhập Admin thành công!' };
  } catch (error) {
    log(`Admin login thất bại: ${safeErrorMessage(error)}`, 'warning');
    return { ok:false, msg:'Không thể xác thực Admin với máy chủ' };
  }
});

ipcMain.handle('admin-logout', async (event) => {
  assertSenderPage(event, ['admin/index.html']);
  adminSessions.delete(event.sender.id);
  return { ok:true };
});

ipcMain.handle('admin-api-call', async (event, action, params = {}) => {
  assertSenderPage(event, ['admin/index.html']);
  requireAdminSession(event);
  if (!ADMIN_ACTIONS.has(action)) return { ok:false, msg:'Hành động Admin không hợp lệ' };
  assertPlainObject(params, 'Tham số Admin');
  if (!LICENSE_API_URL || LICENSE_API_URL.includes('YOUR_WEB_APP_ID')) {
    return { ok: false, msg: 'Chưa cấu hình API bản quyền (LICENSE_API_URL)' };
  }
  
  const gasPassword = getEncryptedSecret('adminApiPassword');
  if (!gasPassword) return { ok:false, msg:'Chưa cấu hình thông tin xác thực Admin' };
  
  try {
    const safeParams = {};
    for (const [key, value] of Object.entries(params).slice(0, 30)) {
      if (!/^[a-zA-Z0-9_]{1,50}$/.test(key)) continue;
      if (['string','number','boolean'].includes(typeof value)) safeParams[key] = String(value).slice(0, 5000);
    }
    return await requestPostJson(LICENSE_API_URL, { action, password:gasPassword, ...safeParams });
  } catch (err) {
    return { ok: false, msg: `Lỗi gọi API Admin: ${safeErrorMessage(err)}` };
  }
});

ipcMain.handle('admin-save-gas-password', async (event, password) => {
  assertSenderPage(event, ['admin/index.html']);
  requireAdminSession(event);
  if (typeof password !== 'string' || password.length < 8 || password.length > 256) {
    return { ok:false, msg:'Mật khẩu phải có từ 8 đến 256 ký tự' };
  }
  saveEncryptedSecret('adminApiPassword', password);
  return { ok: true };
});

ipcMain.handle('admin-get-gas-password', async (event) => {
  assertSenderPage(event, ['admin/index.html']);
  requireAdminSession(event);
  return '';
});

ipcMain.handle('shopee-get-announcements', async () => {
  const url = `https://app.vmaxtech.vn/api/shopee/announcements`;
  try {
    const res = await requestWithRedirect(url);
    return res;
  } catch (err) {
    return { status: false, message: err.message };
  }
});

ipcMain.handle('shopee-get-latest-version', async () => {
  const url = `https://app.vmaxtech.vn/api/shopee/latest-version`;
  try {
    const res = await requestWithRedirect(url);
    return res;
  } catch (err) {
    return { status: false, message: err.message };
  }
});

// ── DỰNG TIMER THEO DÕI BẢN QUYỀN VÀ THÔNG BÁO CHẠY NGẦM ────────────────────
let checkLicenseInterval = null;
let checkAnnInterval = null;
let lastAnnIdSeen = '';

function setupAppTimers() {
  if (checkLicenseInterval) clearInterval(checkLicenseInterval);
  if (checkAnnInterval) clearInterval(checkAnnInterval);
  
  // 1. Check Bản Quyền Online mỗi 5 phút
  checkLicenseInterval = setInterval(async () => {
    const cfg = getCfg();
    if (!cfg.userEmail || !cfg.licenseKey || !isLicenseValid) return;
    
    try {
      const res = await checkLicenseWeb(cfg.licenseKey);
      if (res && res.status) {
        saveLicenseCache(cfg.licenseKey, res.expire);
        isLicenseValid = true;
        licenseMsg = `Bản quyền hợp lệ. Hết hạn: ${res.expire}`;
      } else {
        isLicenseValid = false;
        licenseMsg = res.message || 'Bản quyền đã bị khóa hoặc hết hạn';
        log(`Chạy ngầm phát hiện bản quyền không hợp lệ: ${licenseMsg}`, 'warning');
        
        if (mainWindow && !mainWindow.isDestroyed()) {
          mainWindow.webContents.send('license-status-changed', { ok: false, msg: licenseMsg });
          mainWindow.loadFile(path.join(__dirname, 'pages', 'activation.html'));
        }
      }
    } catch (err) {
      log(`Lỗi kết nối kiểm tra bản quyền định kỳ: ${err.message}`, 'warning');
    }
  }, 5 * 60 * 1000);
}

function getIpCheckHtml() {
  return `<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>V-ShopeeBoost - Kiểm tra IP & Proxy</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"><\/script>
  <style>
    :root {
      --bg: #090a0f;
      --bg2: #0d0e1a;
      --card-bg: #12131e;
      --card-bg2: #1a1b2e;
      --border: rgba(255,255,255,0.07);
      --text: #ffffff;
      --text-muted: #8b8e9f;
      --text-dim: #5a5d72;
      --accent: #6366f1;
      --accent2: #818cf8;
      --accent-grad: linear-gradient(135deg, #6366f1 0%, #818cf8 100%);
      --green: #22c55e;
      --red: #ef4444;
      --yellow: #f59e0b;
      --blue: #3b82f6;
      --cyan: #06b6d4;
      --purple: #a855f7;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'Inter', sans-serif;
      min-height: 100vh;
      padding: 0;
    }
    /* HEADER */
    .top-bar {
      background: linear-gradient(135deg, #0d0e1a 0%, #12131e 100%);
      border-bottom: 1px solid var(--border);
      padding: 14px 24px;
      display: flex;
      align-items: center;
      gap: 12px;
      position: sticky;
      top: 0;
      z-index: 1000;
      backdrop-filter: blur(20px);
    }
    .top-bar .logo-img { height: 32px; }
    .top-bar .brand { font-size: 16px; font-weight: 800; letter-spacing: 0.3px; }
    .top-bar .brand span { color: var(--accent2); }
    .top-bar .tagline { font-size: 11px; color: var(--text-muted); margin-left: auto; }
    .refresh-btn {
      background: var(--accent-grad);
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 7px 16px;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 6px;
      transition: opacity 0.2s, transform 0.15s;
      font-family: inherit;
    }
    .refresh-btn:hover { opacity: 0.85; transform: translateY(-1px); }
    .refresh-btn:active { transform: translateY(0); }
    /* LAYOUT */
    .container {
      max-width: 1100px;
      margin: 0 auto;
      padding: 24px 20px;
    }
    /* IP HERO */
    .ip-hero {
      background: linear-gradient(135deg, #12131e 0%, #1a1b2e 100%);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 32px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 32px;
      position: relative;
      overflow: hidden;
    }
    .ip-hero::before {
      content: '';
      position: absolute;
      top: -60px; right: -60px;
      width: 200px; height: 200px;
      background: radial-gradient(circle, rgba(108,99,255,0.15) 0%, transparent 70%);
      pointer-events: none;
    }
    .ip-main { flex: 1; }
    .ip-label {
      font-size: 11px;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 1.5px;
      margin-bottom: 8px;
    }
    .ip-address {
      font-family: 'JetBrains Mono', monospace;
      font-size: 42px;
      font-weight: 700;
      color: #fff;
      line-height: 1;
      margin-bottom: 12px;
    }
    .badges { display: flex; gap: 8px; flex-wrap: wrap; }
    .badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 11.5px;
      font-weight: 600;
      padding: 4px 12px;
      border-radius: 99px;
      border: 1px solid;
    }
    .badge-green { color: var(--green); background: rgba(34,197,94,0.1); border-color: rgba(34,197,94,0.25); }
    .badge-red { color: var(--red); background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.25); }
    .badge-yellow { color: var(--yellow); background: rgba(245,158,11,0.1); border-color: rgba(245,158,11,0.25); }
    .badge-blue { color: var(--blue); background: rgba(59,130,246,0.1); border-color: rgba(59,130,246,0.25); }
    .badge-purple { color: var(--purple); background: rgba(168,85,247,0.1); border-color: rgba(168,85,247,0.25); }
    .badge-cyan { color: var(--cyan); background: rgba(6,182,212,0.1); border-color: rgba(6,182,212,0.25); }
    .badge-loading { color: var(--text-muted); background: rgba(255,255,255,0.04); border-color: var(--border); animation: pulse 1.5s infinite; }
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.4} }
    .ip-flag { text-align: center; }
    .flag-emoji { font-size: 64px; line-height: 1; display: block; filter: drop-shadow(0 4px 12px rgba(0,0,0,0.5)); }
    .flag-country { font-size: 13px; color: var(--text-muted); margin-top: 6px; text-align: center; }
    /* GRID */
    .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 20px; }
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; }
    @media (max-width: 768px) {
      .grid-3 { grid-template-columns: 1fr; }
      .grid-2 { grid-template-columns: 1fr; }
      .ip-hero { flex-direction: column; }
      .ip-address { font-size: 28px; }
    }
    /* CARD */
    .card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 20px;
    }
    .card-title {
      font-size: 11px;
      font-weight: 700;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 7px;
    }
    .card-title .icon {
      width: 22px; height: 22px;
      border-radius: 6px;
      display: flex; align-items: center; justify-content: center;
      font-size: 12px;
    }
    .info-row {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      padding: 9px 0;
      border-bottom: 1px solid rgba(255,255,255,0.04);
      gap: 12px;
    }
    .info-row:last-child { border-bottom: none; padding-bottom: 0; }
    .info-key { font-size: 12px; color: var(--text-muted); white-space: nowrap; flex-shrink: 0; }
    .info-val { font-size: 12.5px; font-weight: 600; text-align: right; word-break: break-all; }
    .mono { font-family: 'JetBrains Mono', monospace; font-size: 11px; }
    /* MAP */
    .map-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 16px;
      overflow: hidden;
      margin-bottom: 20px;
    }
    .map-header {
      padding: 16px 20px;
      display: flex;
      align-items: center;
      gap: 8px;
      border-bottom: 1px solid var(--border);
    }
    .map-header .card-title { margin-bottom: 0; }
    .map-coords {
      margin-left: auto;
      font-family: 'JetBrains Mono', monospace;
      font-size: 11.5px;
      color: var(--accent2);
    }
    #map {
      width: 100%;
      height: 320px;
      background: #0d0e1a;
    }
    .leaflet-tile-pane { filter: brightness(0.85) saturate(0.8); }
    /* UA SECTION */
    .ua-box {
      background: rgba(0,0,0,0.25);
      border: 1px solid rgba(255,255,255,0.04);
      border-radius: 10px;
      padding: 12px;
      font-family: 'JetBrains Mono', monospace;
      font-size: 11px;
      color: var(--text-muted);
      word-break: break-all;
      line-height: 1.6;
    }
    /* SPEED TEST */
    .speed-bar-wrap {
      height: 6px;
      background: rgba(255,255,255,0.06);
      border-radius: 99px;
      overflow: hidden;
      margin-top: 8px;
    }
    .speed-bar {
      height: 100%;
      border-radius: 99px;
      background: var(--accent-grad);
      width: 0%;
      transition: width 1.5s ease;
    }
    .speed-val {
      font-size: 24px;
      font-weight: 800;
      font-family: 'JetBrains Mono', monospace;
      color: #fff;
      margin-bottom: 4px;
    }
    .speed-unit { font-size: 12px; color: var(--text-muted); }
    /* SECURITY CHECKS */
    .check-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 0;
      border-bottom: 1px solid rgba(255,255,255,0.04);
    }
    .check-row:last-child { border-bottom: none; }
    .check-label { display: flex; align-items: center; gap: 8px; font-size: 12.5px; font-weight: 500; }
    .check-icon { font-size: 16px; }
    /* LOADING SKELETON */
    .skel {
      background: linear-gradient(90deg, rgba(255,255,255,0.04) 25%, rgba(255,255,255,0.08) 50%, rgba(255,255,255,0.04) 75%);
      background-size: 200% 100%;
      animation: shimmer 1.5s infinite;
      border-radius: 4px;
      height: 14px;
      display: inline-block;
    }
    @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
    /* COPY BUTTON */
    .copy-btn {
      background: none;
      border: 1px solid var(--border);
      border-radius: 6px;
      color: var(--text-muted);
      font-size: 11px;
      padding: 3px 8px;
      cursor: pointer;
      transition: all 0.2s;
      font-family: inherit;
      margin-left: 6px;
    }
    .copy-btn:hover { border-color: var(--accent); color: var(--accent); }
    /* SECTION LABEL */
    .section-label {
      font-size: 11px;
      font-weight: 700;
      color: var(--text-dim);
      text-transform: uppercase;
      letter-spacing: 1.2px;
      margin-bottom: 12px;
      margin-top: 4px;
    }
    /* FOOTER */
    .footer {
      text-align: center;
      padding: 20px;
      color: var(--text-dim);
      font-size: 11px;
      border-top: 1px solid var(--border);
      margin-top: 20px;
    }
    /* PRIVACY SCORE */
    .score-ring {
      position: relative;
      width: 100px;
      height: 100px;
      margin: 0 auto 12px;
    }
    .score-ring svg { transform: rotate(-90deg); }
    .score-ring .val {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 24px;
      font-weight: 800;
      font-family: 'JetBrains Mono', monospace;
    }
    .score-label { text-align: center; font-size: 12px; color: var(--text-muted); }
  </style>
</head>
<body>
  <!-- TOP BAR -->
  <div class="top-bar">
    <img src="http://127.0.0.1:34297/logo" alt="Logo" class="logo-img" onerror="this.style.display='none'">
    <div class="brand">V-ShopeeBoost <span>PRO</span></div>
    <span class="tagline">Hệ thống kiểm tra IP & Proxy</span>
    <button class="refresh-btn" onclick="fetchAll()">⟳ Tải lại</button>
  </div>

  <div class="container">
    <!-- IP HERO -->
    <div class="ip-hero">
      <div class="ip-main">
        <div class="ip-label">Địa chỉ IP của bạn</div>
        <div class="ip-address" id="ip">Đang tải...</div>
        <div class="badges" id="badges">
          <span class="badge badge-loading">Đang kiểm tra...</span>
        </div>
      </div>
      <div class="ip-flag">
        <div class="flag-country" id="flag-country">Đang xác định vị trí</div>
      </div>
    </div>

    <!-- MAP -->
    <div class="map-card">
      <div class="map-header">
        <div class="card-title" style="margin-bottom:0">
          Vị trí trên bản đồ
        </div>
        <span class="map-coords" id="map-coords">Đang xác định...</span>
      </div>
      <div id="map"></div>
    </div>

    <!-- THÔNG TIN CHÍNH -->
    <div class="grid-3">
      <!-- VỊ TRÍ -->
      <div class="card">
        <div class="card-title">
          Vị trí địa lý
        </div>
        <div class="info-row">
          <span class="info-key">Quốc gia</span>
          <span class="info-val" id="country">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">Mã QG</span>
          <span class="info-val" id="country-code">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">Tỉnh/Vùng</span>
          <span class="info-val" id="region">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">Thành phố</span>
          <span class="info-val" id="city">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">Mã bưu điện</span>
          <span class="info-val" id="zip">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">Múi giờ</span>
          <span class="info-val" id="timezone">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">Tọa độ</span>
          <span class="info-val mono" id="coords">...</span>
        </div>
      </div>
      <!-- MẠNG -->
      <div class="card">
        <div class="card-title">
          Thông tin mạng
        </div>
        <div class="info-row">
          <span class="info-key">Nhà mạng</span>
          <span class="info-val" id="isp">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">Tổ chức</span>
          <span class="info-val" id="org">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">AS Number</span>
          <span class="info-val mono" id="asn">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">Loại IP</span>
          <span class="info-val" id="ip-type">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">IPv4</span>
          <span class="info-val mono" id="ipv4">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">Giao thức</span>
          <span class="info-val" id="ip-version">IPv4</span>
        </div>
        <div class="info-row">
          <span class="info-key">Thời gian</span>
          <span class="info-val" id="cur-time">...</span>
        </div>
      </div>
      <!-- BẢO MẬT -->
      <div class="card">
        <div class="card-title">
          Bảo mật & Proxy
        </div>
        <div class="check-row">
          <span class="check-label">VPN / Proxy</span>
          <span class="badge" id="sec-proxy">...</span>
        </div>
        <div class="check-row">
          <span class="check-label">Kết nối an toàn</span>
          <span class="badge" id="sec-https">...</span>
        </div>
        <div class="check-row">
          <span class="check-label">WebRTC Leak</span>
          <span class="badge" id="sec-webrtc">...</span>
        </div>
        <div class="check-row">
          <span class="check-label">Cookies</span>
          <span class="badge" id="sec-cookies">...</span>
        </div>
        <div class="check-row">
          <span class="check-label">Do Not Track</span>
          <span class="badge" id="sec-dnt">...</span>
        </div>
        <div class="check-row">
          <span class="check-label">JS Enabled</span>
          <span class="badge badge-green">Có</span>
        </div>
      </div>
    </div>

    <!-- BROWSER + SYSTEM -->
    <div class="grid-2">
      <div class="card">
        <div class="card-title">
          Trình duyệt & Hệ thống
        </div>
        <div class="info-row">
          <span class="info-key">Nền tảng</span>
          <span class="info-val" id="platform">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">Ngôn ngữ</span>
          <span class="info-val" id="browser-lang">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">Số ngôn ngữ</span>
          <span class="info-val" id="browser-langs">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">Độ phân giải</span>
          <span class="info-val" id="screen-res">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">Màu sắc</span>
          <span class="info-val" id="color-depth">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">Giờ hệ thống</span>
          <span class="info-val" id="sys-timezone">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">CPU cores</span>
          <span class="info-val" id="cpu-cores">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">RAM (GB)</span>
          <span class="info-val" id="ram">...</span>
        </div>
        <div style="margin-top:12px">
          <div class="info-key" style="margin-bottom:6px">User-Agent</div>
          <div class="ua-box" id="ua">...</div>
        </div>
      </div>
      <div class="card">
        <div class="card-title">
          Dữ liệu bổ sung
        </div>
        <div class="info-row">
          <span class="info-key">Số lõi CPU</span>
          <span class="info-val" id="hw-concurrency">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">Touch Points</span>
          <span class="info-val" id="touch-points">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">Pixel Ratio</span>
          <span class="info-val" id="pixel-ratio">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">Trình duyệt</span>
          <span class="info-val" id="browser-name">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">Phiên bản</span>
          <span class="info-val" id="browser-ver">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">Loại thiết bị</span>
          <span class="info-val" id="device-type">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">Online</span>
          <span class="info-val" id="is-online">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">Loại kết nối</span>
          <span class="info-val" id="conn-type">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">Tốc độ DL</span>
          <span class="info-val" id="dl-speed">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">Độ trễ RTT</span>
          <span class="info-val" id="rtt">...</span>
        </div>
        <div class="info-row">
          <span class="info-key">Đồng hồ địa phương</span>
          <span class="info-val" id="local-time">...</span>
        </div>
      </div>
    </div>

    <div class="footer">
      V-ShopeeBoost PRO &nbsp;•&nbsp; Trang kiểm tra IP & Proxy &nbsp;•&nbsp; <span id="footer-time"></span>
    </div>
  </div>

  <script>
    // ─── BROWSER FINGERPRINT ───────────────────────────────────────────
    const ua = navigator.userAgent;
    document.getElementById('ua').textContent = ua;
    document.getElementById('platform').textContent = navigator.platform || 'Không rõ';
    document.getElementById('browser-lang').textContent = navigator.language || 'Không rõ';
    document.getElementById('browser-langs').textContent = (navigator.languages || []).join(', ') || 'Không rõ';
    document.getElementById('screen-res').textContent = screen.width + '×' + screen.height + ' @ ' + screen.availWidth + '×' + screen.availHeight;
    document.getElementById('color-depth').textContent = screen.colorDepth + ' bit';
    document.getElementById('sys-timezone').textContent = Intl.DateTimeFormat().resolvedOptions().timeZone;
    document.getElementById('cpu-cores').textContent = navigator.hardwareConcurrency || 'Không rõ';
    document.getElementById('ram').textContent = navigator.deviceMemory ? navigator.deviceMemory + ' GB' : 'Không rõ';
    document.getElementById('hw-concurrency').textContent = navigator.hardwareConcurrency || 'Không rõ';
    document.getElementById('touch-points').textContent = navigator.maxTouchPoints || '0';
    document.getElementById('pixel-ratio').textContent = window.devicePixelRatio || '1';
    document.getElementById('is-online').textContent = navigator.onLine ? '✓ Online' : '✕ Offline';
    const conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
    if (conn) {
      document.getElementById('conn-type').textContent = conn.effectiveType || 'Không rõ';
      document.getElementById('dl-speed').textContent = conn.downlink ? conn.downlink + ' Mbps' : 'Không rõ';
      document.getElementById('rtt').textContent = conn.rtt ? conn.rtt + ' ms' : 'Không rõ';
    } else {
      document.getElementById('conn-type').textContent = 'Không rõ';
      document.getElementById('dl-speed').textContent = 'Không rõ';
      document.getElementById('rtt').textContent = 'Không rõ';
    }

    // Detect browser
    function detectBrowser() {
      if (ua.includes('Edg/')) return ['Microsoft Edge', ua.match(/Edg\\/(\\S+)/)?.[1]];
      if (ua.includes('OPR/') || ua.includes('Opera')) return ['Opera', ua.match(/OPR\\/(\\S+)/)?.[1]];
      if (ua.includes('Firefox/')) return ['Firefox', ua.match(/Firefox\\/(\\S+)/)?.[1]];
      if (ua.includes('Chrome/')) return ['Chrome', ua.match(/Chrome\\/(\\S+)/)?.[1]];
      if (ua.includes('Safari/') && !ua.includes('Chrome')) return ['Safari', ua.match(/Version\\/(\\S+)/)?.[1]];
      return ['Không rõ', ''];
    }
    const [bname, bver] = detectBrowser();
    document.getElementById('browser-name').textContent = bname;
    document.getElementById('browser-ver').textContent = bver || 'Không rõ';
    document.getElementById('device-type').textContent = /Mobi|Android|iPhone|iPad/i.test(ua) ? 'Di động' : 'Máy tính';

    // Security checks
    document.getElementById('sec-https').innerHTML = location.protocol === 'https:' ? '<span class="badge badge-green">HTTPS</span>' : '<span class="badge badge-yellow">HTTP</span>';
    document.getElementById('sec-cookies').innerHTML = navigator.cookieEnabled ? '<span class="badge badge-green">Bật</span>' : '<span class="badge badge-red">Tắt</span>';
    document.getElementById('sec-dnt').innerHTML = navigator.doNotTrack === '1' ? '<span class="badge badge-green">Bật</span>' : '<span class="badge badge-yellow">Tắt</span>';

    // Time update
    function updateTime() {
      const now = new Date();
      const fmt = now.toLocaleString('vi-VN', { hour:'2-digit', minute:'2-digit', second:'2-digit', day:'2-digit', month:'2-digit', year:'numeric' });
      document.getElementById('local-time').textContent = fmt;
      document.getElementById('footer-time').textContent = fmt;
    }
    updateTime();
    setInterval(updateTime, 1000);

    // Map initialization
    let map = null;
    let marker = null;
    function initMap(lat, lon, city, country) {
      if (!map) {
        map = L.map('map', { zoomControl: true, scrollWheelZoom: true }).setView([lat, lon], 11);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '© OpenStreetMap contributors',
          maxZoom: 19
        }).addTo(map);
      } else {
        map.setView([lat, lon], 11);
        if (marker) map.removeLayer(marker);
      }
      const icon = L.divIcon({
        className: '',
        html: '<div style="width:16px;height:16px;background:linear-gradient(135deg,#6366f1,#818cf8);border-radius:50%;border:3px solid #fff;box-shadow:0 4px 16px rgba(99,102,241,0.5)"></div>',
        iconSize: [16, 16],
        iconAnchor: [8, 8]
      });
      marker = L.marker([lat, lon], { icon }).addTo(map);
      marker.bindPopup('<b>' + city + '</b><br>' + country + '<br><code>' + lat + ', ' + lon + '</code>').openPopup();
      document.getElementById('map-coords').textContent = lat.toFixed(4) + ', ' + lon.toFixed(4);
    }

    // WebRTC Leak detection
    function checkWebRTCLeak() {
      try {
        const pc = new RTCPeerConnection({ iceServers: [{ urls: 'stun:stun.l.google.com:19302' }] });
        const ips = new Set();
        pc.createDataChannel('');
        pc.createOffer().then(o => pc.setLocalDescription(o));
        pc.onicecandidate = (e) => {
          if (!e || !e.candidate) return;
          const m = e.candidate.candidate.match(/(\\d{1,3}(\\.\\d{1,3}){3})/g);
          if (m) m.forEach(ip => ips.add(ip));
          if (ips.size > 0) {
            const localIPs = [...ips].filter(ip => ip.startsWith('192.168') || ip.startsWith('10.') || ip.startsWith('172.'));
            document.getElementById('sec-webrtc').innerHTML = localIPs.length > 0
              ? '<span class="badge badge-red">Bị rò IP nội bộ</span>'
              : '<span class="badge badge-green">Không rò</span>';
          }
        };
        setTimeout(() => {
          pc.close();
          if (document.getElementById('sec-webrtc').textContent === '...') {
            document.getElementById('sec-webrtc').innerHTML = '<span class="badge badge-green">Không rò</span>';
          }
        }, 3000);
      } catch (e) {
        document.getElementById('sec-webrtc').innerHTML = '<span class="badge badge-yellow">Không hỗ trợ</span>';
      }
    }

    // Main fetch function
    async function fetchAll() {
      // Reset
      document.getElementById('ip').textContent = 'Đang tải...';
      document.getElementById('badges').innerHTML = '<span class="badge badge-loading">Đang kiểm tra...</span>';
      document.getElementById('flag-country').textContent = 'Đang xác định vị trí';

      const apis = [
        { url: 'https://ipapi.co/json/', map: d => ({
          ip: d.ip, country: d.country_name, countryCode: d.country_code, region: d.region,
          city: d.city, zip: d.postal, timezone: d.timezone, isp: d.org, org: d.org, asn: d.asn,
          lat: d.latitude, lon: d.longitude
        })},
        { url: 'http://ip-api.com/json?fields=status,message,country,countryCode,region,regionName,city,zip,lat,lon,timezone,isp,org,as,query,proxy,hosting', map: d => ({
          ip: d.query, country: d.country, countryCode: d.countryCode, region: d.regionName,
          city: d.city, zip: d.zip, timezone: d.timezone, isp: d.isp, org: d.org, asn: d.as,
          lat: d.lat, lon: d.lon, isProxy: d.proxy, isHosting: d.hosting
        })},
        { url: 'https://ipinfo.io/json', map: d => {
          const [lat, lon] = (d.loc || '0,0').split(',').map(Number);
          return { ip: d.ip, country: d.country, countryCode: d.country, region: d.region, city: d.city,
            zip: d.postal, timezone: d.timezone, isp: d.org, org: d.org, lat, lon };
        }}
      ];

      let data = null;
      for (const api of apis) {
        try {
          const res = await fetch(api.url, { signal: AbortSignal.timeout(7000) });
          if (res.ok) {
            const raw = await res.json();
            if (raw.status === 'fail') continue;
            data = api.map(raw);
            break;
          }
        } catch (e) { console.warn('IP API failed:', api.url, e); }
      }

      if (!data) {
        document.getElementById('ip').textContent = 'Không thể tải';
        document.getElementById('badges').innerHTML = '<span class="badge badge-red">Mất kết nối internet</span>';
        return;
      }

      // Fill IP Hero
      document.getElementById('ip').textContent = data.ip || 'Không rõ';
      document.getElementById('ipv4').textContent = data.ip || 'Không rõ';

      // Country code
      const cc = (data.countryCode || '').toUpperCase();
      document.getElementById('flag-country').textContent = (data.country || '') + (cc ? ' (' + cc + ')' : '');

      // Badges
      const isProxyLikely = data.isProxy || data.isHosting || false;
      let badgeHtml = '<span class="badge badge-green">Kết nối thành công</span>';
      if (isProxyLikely) badgeHtml += ' <span class="badge badge-yellow">Proxy / Hosting</span>';
      badgeHtml += ' <span class="badge badge-blue">' + (data.country || 'Unknown') + '</span>';
      document.getElementById('badges').innerHTML = badgeHtml;

      // Location card
      document.getElementById('country').textContent = data.country || 'Không rõ';
      document.getElementById('country-code').textContent = cc || 'Không rõ';
      document.getElementById('region').textContent = data.region || 'Không rõ';
      document.getElementById('city').textContent = data.city || 'Không rõ';
      document.getElementById('zip').textContent = data.zip || 'Không rõ';
      document.getElementById('timezone').textContent = data.timezone || 'Không rõ';
      const lat = data.lat, lon = data.lon;
      document.getElementById('coords').textContent = (lat && lon) ? lat + ', ' + lon : 'Không rõ';

      // Network card
      document.getElementById('isp').textContent = data.isp || 'Không rõ';
      document.getElementById('org').textContent = data.org || 'Không rõ';
      document.getElementById('asn').textContent = data.asn || 'Không rõ';
      document.getElementById('ip-type').textContent = data.isHosting ? 'Hosting/Datacenter' : data.isProxy ? 'Proxy' : 'Dân dụng';
      document.getElementById('ip-version').textContent = (data.ip || '').includes(':') ? 'IPv6' : 'IPv4';
      document.getElementById('cur-time').textContent = new Date().toLocaleTimeString('vi-VN');

      // Security
      document.getElementById('sec-proxy').innerHTML = isProxyLikely
        ? '<span class="badge badge-yellow">Có dấu hiệu</span>'
        : '<span class="badge badge-green">Không phát hiện</span>';

      // Map
      if (lat && lon && !isNaN(lat) && !isNaN(lon)) {
        initMap(parseFloat(lat), parseFloat(lon), data.city || 'Vị trí', data.country || '');
      }
    }

    // Run on load
    fetchAll();
    checkWebRTCLeak();
  <\/script>
</body>
</html>`;
}
