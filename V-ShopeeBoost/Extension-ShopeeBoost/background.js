const APP_NAME = '[V-ShopeeBoost]';
const APP_VERSION = '6.0.0 Pro Max';
const SHOPEE_DOMAINS = new Set([
 'shopee.vn', 'shopee.com', 'shopee.sg', 'shopee.my',
 'shopee.co.id', 'shopee.ph', 'shopee.tw', 'shopee.co.th'
]);

function normalizeShopeeDomain(value) {
 const domain = String(value || '').trim().toLowerCase().replace(/^\.+/, '');
 if (!SHOPEE_DOMAINS.has(domain)) throw new Error('Tên miền Shopee không hợp lệ');
 return domain;
}

function normalizeCookieDomain(value, selectedDomain) {
 const domain = String(value || `.${selectedDomain}`).trim().toLowerCase().replace(/^\.+/, '');
 if (domain !== selectedDomain && !domain.endsWith(`.${selectedDomain}`)) {
  throw new Error('Cookie domain nằm ngoài tên miền Shopee đã chọn');
 }
 return `.${domain}`;
}
chrome.runtime.onInstalled.addListener(async (details) => {
 console.log(`${APP_NAME} Extension installed:`, details.reason);
 if (details.reason === 'install') {
 await initializeDefaultData();
 } else if (details.reason === 'update') {
 console.log(`${APP_NAME} Updated to v${APP_VERSION}`);
 await migrateData();
 }
 await setStorage({
 installedAt: new Date().toISOString(),
 version: APP_VERSION
 });
});
chrome.runtime.onStartup.addListener(async () => {
 console.log(`${APP_NAME} Browser started`);
 await checkSessionStatus();
});
chrome.runtime.onMessage.addListener((message, sender, sendResponse) => {
 handleMessage(message, sender)
 .then(sendResponse)
 .catch(error => {
 console.error(`${APP_NAME} Message handler error:`, error);
 sendResponse({ error: error.message });
 });
 return true;
});
async function handleMessage(message, sender) {
 const { action, data } = message;
 switch (action) {
 case 'set_cookies': return await setCookies(data);
 case 'get_cookies': return await getCookies(data);
 case 'clear_cookies': return await clearCookies(data);
 case 'get_all_cookies': return await getAllShopeeCookies();
 case 'get_data': return await getStorage(data?.keys);
 case 'set_data': return await setStorage(data);
 case 'remove_data': return await removeStorage(data?.keys);
 case 'create_tab': return await createNewTab(data?.url);
 case 'get_current_tab': return await getCurrentTab();
 case 'update_tab': return await updateTab(data?.tabId, data?.updateProperties);
 case 'save_account': return await saveAccount(data);
 case 'get_accounts': return await getAccounts();
 case 'delete_account': return await deleteAccount(data?.id);
 case 'use_account': return await useAccount(data?.id);
 case 'track_login': return await trackLogin(data);
 case 'get_stats': return await getStatistics();
 case 'check_update': return await checkForUpdate();
 case 'ping': return { status: 'ok', version: APP_VERSION, timestamp: Date.now() };
 default:
 throw new Error(`Unknown action: ${action}`);
 }
}
async function setCookies(data) {
 if (!data || typeof data !== 'object') throw new Error('Dữ liệu cookie không hợp lệ');
 const { spcf, cookies } = data;
 const domain = normalizeShopeeDomain(data.domain);
 const baseUrl = `https://${domain}`;
 const results = { success: [], failed: [] };
 if (spcf) {
 try {
 await chrome.cookies.set({
 url: baseUrl,
 name: 'SPC_F',
 value: String(spcf).slice(0, 8192),
 domain: `.${domain}`,
 path: '/',
 secure: true,
 sameSite: 'no_restriction'
 });
 results.success.push('SPC_F');
 } catch (error) {
 results.failed.push({ name: 'SPC_F', error: error.message });
 }
 }
 if (cookies) {
 const cookieArray = parseCookies(cookies);
 for (const cookie of cookieArray) {
 if (cookie.name && cookie.value !== undefined) {
 try {
 const sameSite = ['no_restriction', 'lax', 'strict', 'unspecified'].includes(cookie.sameSite)
  ? cookie.sameSite
  : 'no_restriction';
 const cookiePath = typeof cookie.path === 'string' && cookie.path.startsWith('/')
  ? cookie.path.slice(0, 2048)
  : '/';
 await chrome.cookies.set({
 url: baseUrl,
 name: String(cookie.name).slice(0, 256),
 value: String(cookie.value).slice(0, 8192),
 domain: normalizeCookieDomain(cookie.domain, domain),
 path: cookiePath,
 secure: cookie.secure !== false,
 sameSite,
 ...(Number.isFinite(Number(cookie.expirationDate)) ? { expirationDate: Number(cookie.expirationDate) } : {})
 });
 results.success.push(cookie.name);
 } catch (error) {
 results.failed.push({ name: cookie.name, error: error.message });
 }
 }
 }
 }
 await saveCookieHistory(domain, results);
 return results;
}
async function getCookies(data) {
 const domain = normalizeShopeeDomain(data?.domain || 'shopee.vn');
 const names = data?.names;
 if (names && Array.isArray(names)) {
 const cookies = {};
 for (const name of names) {
 try {
 const cookie = await chrome.cookies.get({
 url: `https://${domain}`,
 name
 });
 if (cookie) cookies[name] = cookie.value;
 } catch (error) {
 console.warn(`${APP_NAME} Error getting cookie ${name}:`, error);
 }
 }
 return cookies;
 } else {
 return await chrome.cookies.getAll({ domain: domain || 'shopee.vn' });
 }
}
async function clearCookies(data) {
 const domain = normalizeShopeeDomain(data?.domain || 'shopee.vn');
 const cookies = await chrome.cookies.getAll({ domain });
 let cleared = 0;
 for (const cookie of cookies) {
 try {
 const protocol = cookie.secure ? 'https' : 'http';
 await chrome.cookies.remove({
 url: `${protocol}://${domain}${cookie.path}`,
 name: cookie.name
 });
 cleared++;
 } catch (e) {
 }
 }
 return { success: true, count: cleared };
}
async function getAllShopeeCookies() {
 const domains = [
 'shopee.vn', 'shopee.com', 'shopee.sg', 'shopee.my',
 'shopee.co.id', 'shopee.ph', 'shopee.tw', 'shopee.co.th'
 ];
 const allCookies = {};
 for (const domain of domains) {
 try {
 const cookies = await chrome.cookies.getAll({ domain });
 if (cookies.length > 0) allCookies[domain] = cookies;
 } catch (e) {
 }
 }
 return allCookies;
}
function parseCookies(cookiesString) {
 if (!cookiesString) return [];
 const trimmed = cookiesString.trim();
 if (trimmed.startsWith('[') || trimmed.startsWith('{')) {
 try {
 const parsed = JSON.parse(trimmed);
 return Array.isArray(parsed) ? parsed : [parsed];
 } catch (e) {
 }
 }
 return trimmed.split(';').map(pair => {
 const [name, ...valueParts] = pair.split('=');
 if (name && valueParts.length > 0) {
 return { name: name.trim(), value: valueParts.join('=').trim() };
 }
 return null;
 }).filter(Boolean);
}
async function saveCookieHistory(domain, results) {
 const history = (await getStorage('cookieHistory')) || [];
 history.unshift({
 timestamp: new Date().toISOString(),
 domain,
 added: results.success.length,
 failed: results.failed.length
 });
 if (history.length > 20) history.splice(20);
 await setStorage({ cookieHistory: history });
}
async function saveAccount(accountData) {
 const accounts = (await getStorage('accounts')) || [];
 const newAccount = {
 id: Date.now(),
 ...accountData,
 createdAt: new Date().toISOString(),
 lastUsed: null,
 loginCount: 0
 };
 const existingIndex = accounts.findIndex(a => a.shopName === accountData.shopName);
 if (existingIndex !== -1) {
 accounts[existingIndex] = { ...accounts[existingIndex], ...newAccount };
 } else {
 accounts.push(newAccount);
 }
 await setStorage({ accounts });
 return newAccount;
}
async function getAccounts() {
 return (await getStorage('accounts')) || [];
}
async function deleteAccount(id) {
 const accounts = (await getStorage('accounts')) || [];
 await setStorage({ accounts: accounts.filter(a => a.id !== id) });
 return { success: true };
}
async function useAccount(id) {
 const accounts = (await getStorage('accounts')) || [];
 const account = accounts.find(a => a.id === id);
 if (!account) throw new Error('Account not found');
 await setCookies({
 spcf: account.spcfToken,
 cookies: account.cookies,
 domain: account.domain || 'shopee.vn'
 });
 account.lastUsed = new Date().toISOString();
 account.loginCount = (account.loginCount || 0) + 1;
 await setStorage({ accounts });
 await trackLogin({ accountId: id, accountName: account.shopName, domain: account.domain });
 return account;
}
async function trackLogin(data) {
 const stats = (await getStorage('loginStats')) || {
 totalLogins: 0,
 loginHistory: [],
 accountsUsed: {}
 };
 stats.totalLogins = (stats.totalLogins || 0) + 1;
 stats.loginHistory = stats.loginHistory || [];
 stats.loginHistory.unshift({
 timestamp: new Date().toISOString(),
 shopName: data.shopName || data.accountName,
 domain: data.domain,
 accountId: data.accountId
 });
 if (stats.loginHistory.length > 50) stats.loginHistory.splice(50);
 if (data.accountId) {
 stats.accountsUsed = stats.accountsUsed || {};
 stats.accountsUsed[data.accountId] = (stats.accountsUsed[data.accountId] || 0) + 1;
 }
 await setStorage({ loginStats: stats });
 return stats;
}
async function getStatistics() {
 const stats = (await getStorage('loginStats')) || {};
 const accounts = (await getStorage('accounts')) || [];
 return {
 totalAccounts: accounts.length,
 totalLogins: stats.totalLogins || 0,
 lastLogin: stats.loginHistory?.[0]?.timestamp || null,
 lastShopName: stats.loginHistory?.[0]?.shopName || null,
 accountsUsed: stats.accountsUsed || {}
 };
}
async function createNewTab(url) {
 return await chrome.tabs.create({ url, active: true });
}
async function getCurrentTab() {
 const [tab] = await chrome.tabs.query({ active: true, currentWindow: true });
 return tab;
}
async function updateTab(tabId, updateProperties) {
 return await chrome.tabs.update(tabId, updateProperties);
}
function getStorage(keys) {
 return new Promise((resolve) => {
 if (typeof keys === 'undefined') {
 chrome.storage.local.get(null, resolve);
 } else if (typeof keys === 'string') {
 chrome.storage.local.get(keys, (result) => resolve(result[keys]));
 } else if (Array.isArray(keys)) {
 chrome.storage.local.get(keys, resolve);
 } else {
 resolve({});
 }
 });
}
function setStorage(items) {
 return new Promise((resolve, reject) => {
 chrome.storage.local.set(items, () => {
 if (chrome.runtime.lastError) {
 reject(chrome.runtime.lastError);
 } else {
 resolve(true);
 }
 });
 });
}
function removeStorage(keys) {
 return new Promise((resolve, reject) => {
 const keyArr = typeof keys === 'string' ? [keys] : keys;
 chrome.storage.local.remove(keyArr, () => {
 if (chrome.runtime.lastError) {
 reject(chrome.runtime.lastError);
 } else {
 resolve(true);
 }
 });
 });
}
async function checkForUpdate() {
 try {
 const manifest = chrome.runtime.getManifest();
 return {
 currentVersion: manifest.version,
 hasUpdate: false,
 lastChecked: new Date().toISOString()
 };
 } catch (e) {
 return { currentVersion: APP_VERSION, hasUpdate: false };
 }
}
async function checkSessionStatus() {
 try {
 const settings = (await getStorage('settings')) || {};
 if (settings.autoClearOnClose) {
 }
 return { status: 'ok' };
 } catch (error) {
 console.error(`${APP_NAME} Session check error:`, error);
 }
}
async function migrateData() {
 try {
 const oldStats = await getStorage('loginStats');
 if (oldStats && typeof oldStats.count !== 'undefined' && typeof oldStats.totalLogins === 'undefined') {
 oldStats.totalLogins = oldStats.count;
 delete oldStats.count;
 await setStorage({ loginStats: oldStats });
 console.log(`${APP_NAME} Data migrated successfully`);
 }
 } catch (e) {
 console.warn(`${APP_NAME} Migration warning:`, e);
 }
}
async function initializeDefaultData() {
 const defaultSettings = {
 defaultDomain: 'shopee.vn',
 autoOpenTab: true,
 autoSaveCookie: true,
 colorTheme: 'dark',
 enableAnimation: true,
 autoClearOnClose: false
 };
 await setStorage({
 settings: defaultSettings,
 accounts: [],
 loginStats: {
 totalLogins: 0,
 loginHistory: [],
 accountsUsed: {}
 },
 cookieHistory: [],
 loginHistory: []
 });
 console.log(`${APP_NAME} Default data initialized`);
}
chrome.runtime.setUninstallURL('https://app.vmaxtech.vn/feedback?from=vshopeeboost');

// ── SYNC FINGERPRINT CONFIG & PROXY AUTH (v6.0.0 Pro Max) ────────────────
try {
  if (typeof importScripts === 'function') {
    importScripts('fingerprint.js');
  }
} catch (e) {
  // Ignore in environments where fingerprint.js is not yet copied
}

let proxyCredentials = null;

function parseCredentials(rawProxy, explicitAuth) {
  if (explicitAuth && explicitAuth.username && explicitAuth.password) {
    return {
      username: String(explicitAuth.username).trim(),
      password: String(explicitAuth.password).trim()
    };
  }
  if (!rawProxy || typeof rawProxy !== 'string') return null;
  let str = rawProxy.trim();
  if (str.includes('://')) {
    const parts = str.split('://');
    str = parts[1] || '';
  }
  let username = '', password = '';
  if (str.includes('@')) {
    const lastAtIndex = str.lastIndexOf('@');
    const authPart = str.substring(0, lastAtIndex);
    const firstColon = authPart.indexOf(':');
    if (firstColon !== -1) {
      username = authPart.substring(0, firstColon);
      password = authPart.substring(firstColon + 1);
    } else {
      username = authPart;
    }
  } else {
    const parts = str.split(':');
    if (parts.length >= 4) {
      username = parts[2] || '';
      password = parts.slice(3).join(':');
    }
  }
  if (username && password) {
    return { username: username.trim(), password: password.trim() };
  }
  return null;
}

// Synchronously extract credentials if fingerprint.js is already imported
if (typeof FINGERPRINT_CONFIG !== 'undefined' && FINGERPRINT_CONFIG) {
  proxyCredentials = parseCredentials(FINGERPRINT_CONFIG.proxy, FINGERPRINT_CONFIG.proxyAuth);
  if (proxyCredentials) {
    console.log(`${APP_NAME} Initialized proxy credentials synchronously for: ${proxyCredentials.username}`);
  }
}

async function loadProxyCredentials() {
  try {
    const response = await fetch(chrome.runtime.getURL('fingerprint.json'));
    const config = await response.json();
    if (config) {
      const creds = parseCredentials(config.proxy, config.proxyAuth);
      if (creds) {
        proxyCredentials = creds;
        console.log(`${APP_NAME} Loaded proxy credentials from fingerprint.json for: ${creds.username}`);
      }
    }
  } catch (e) {
    console.warn(`${APP_NAME} Notice loading fingerprint.json:`, e.message);
  }
}
loadProxyCredentials();

// Proxy Authentication Handler: Never allow Chrome to show authentication dialog
chrome.webRequest.onAuthRequired.addListener(
  (details) => {
    if (!details.isProxy) return;
    if (!proxyCredentials && typeof FINGERPRINT_CONFIG !== 'undefined' && FINGERPRINT_CONFIG) {
      proxyCredentials = parseCredentials(FINGERPRINT_CONFIG.proxy, FINGERPRINT_CONFIG.proxyAuth);
    }
    if (proxyCredentials && proxyCredentials.username) {
      console.log(`${APP_NAME} Auto-authenticating proxy request for ${details.challenger?.host || 'proxy'}`);
      return {
        authCredentials: {
          username: proxyCredentials.username,
          password: proxyCredentials.password
        }
      };
    }
  },
  { urls: ["<all_urls>"] },
  ["blocking"]
);

// WebRTC Leak Shield Configuration
function applyWebRTCShield() {
  try {
    if (chrome.privacy && chrome.privacy.network && chrome.privacy.network.webRTCIPHandlingPolicy) {
      chrome.privacy.network.webRTCIPHandlingPolicy.set({
        value: 'disable_non_proxied_udp'
      }, () => {
        console.log(`${APP_NAME} WebRTC Leak Shield enabled: disable_non_proxied_udp`);
      });
    }
  } catch (e) {
    console.warn(`${APP_NAME} Error setting WebRTC shield:`, e.message);
  }
}
applyWebRTCShield();
chrome.runtime.onInstalled.addListener(() => applyWebRTCShield());
chrome.runtime.onStartup.addListener(() => applyWebRTCShield());
