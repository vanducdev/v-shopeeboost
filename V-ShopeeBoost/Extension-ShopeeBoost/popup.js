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

function findShopeeRoot(hostname) {
 const host = String(hostname || '').trim().toLowerCase().replace(/\.$/, '');
 for (const domain of SHOPEE_DOMAINS) {
  if (host === domain || host.endsWith(`.${domain}`)) return domain;
 }
 return '';
}

function sanitizeImportedAccount(value, forcedId = Date.now() + Math.random()) {
 if (!value || typeof value !== 'object' || Array.isArray(value)) return null;
 let domain;
 try { domain = normalizeShopeeDomain(value.domain || 'shopee.vn'); } catch { return null; }
 const shopName = String(value.shopName || '').trim().slice(0, 200);
 if (!shopName) return null;
 let cookies = value.cookies;
 if (cookies && typeof cookies !== 'string') {
  try { cookies = JSON.stringify(cookies); } catch { cookies = ''; }
 }
 return {
  id: Number.isFinite(Number(forcedId)) ? Number(forcedId) : Date.now() + Math.random(),
  shopName,
  spcfToken: String(value.spcfToken || '').slice(0, 8192),
  cookies: String(cookies || '').slice(0, 1_000_000),
  domain,
  createdAt: String(value.createdAt || new Date().toISOString()).slice(0, 50),
  lastUsed: value.lastUsed ? String(value.lastUsed).slice(0, 50) : null,
  loginCount: Math.max(0, Math.min(1_000_000, Number(value.loginCount) || 0))
 };
}

function sanitizeLoginHistory(value) {
 if (!Array.isArray(value)) return [];
 return value.slice(0, 100).map(item => ({
  shopName: String(item?.shopName || '').slice(0, 200),
  domain: findShopeeRoot(item?.domain) || '',
  time: String(item?.time || item?.timestamp || '').slice(0, 50)
 }));
}

function sanitizeBackupData(data) {
 if (!data || typeof data !== 'object' || Array.isArray(data)) throw new Error('Dữ liệu backup không hợp lệ');
 const accounts = (Array.isArray(data.accounts) ? data.accounts : [])
  .slice(0, 500)
  .map((account, index) => sanitizeImportedAccount(account, Date.now() + index))
  .filter(Boolean);
 const totalLogins = Math.max(0, Math.min(100_000_000, Number(data.loginStats?.totalLogins) || 0));
 return {
  accounts,
  loginStats: { totalLogins },
  loginHistory: sanitizeLoginHistory(data.loginHistory),
  settings: {
   defaultDomain: (() => {
    try { return normalizeShopeeDomain(data.settings?.defaultDomain || 'shopee.vn'); }
    catch { return 'shopee.vn'; }
   })(),
   autoOpenTab: data.settings?.autoOpenTab !== false,
   showNotifications: data.settings?.showNotifications !== false
  }
 };
}
document.addEventListener('DOMContentLoaded', async () => {
 setupTabs();
 setupEventListeners();
 await Promise.all([
 refreshAll(),
 loadSettings(),
 initVersionDisplay()
 ]);
});
function setupTabs() {
 document.querySelectorAll('.tab-btn').forEach(btn => {
 btn.addEventListener('click', () => {
 const tab = btn.dataset.tab;
 document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
 document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
 btn.classList.add('active');
 document.getElementById('tab-' + tab)?.classList.add('active');
 if (tab === 'accounts') loadAccountList(document.getElementById('searchShop')?.value || '');
 if (tab === 'history') loadHistory();
 if (tab === 'settings') loadSettings();
 });
 });
}
function setupEventListeners() {
 document.getElementById('btnGetCookie')?.addEventListener('click', autoGetCookies);
 document.getElementById('btnClearForm')?.addEventListener('click', clearForm);
 document.getElementById('btnSaveAccount')?.addEventListener('click', saveAccount);
 document.getElementById('btnLoginNow')?.addEventListener('click', performLogin);
 document.getElementById('btnCopySpcfInput')?.addEventListener('click', handleCopySpcfInput);
 document.getElementById('searchShop')?.addEventListener('input', e => loadAccountList(e.target.value.trim()));
 document.getElementById('btnQuickAdd')?.addEventListener('click', () => switchToTab('main'));
 document.getElementById('btnClearHistory')?.addEventListener('click', clearHistory);
 document.getElementById('btnBackup')?.addEventListener('click', backupData);
 document.getElementById('btnRestore')?.addEventListener('click', () => document.getElementById('fileImport').click());
 document.getElementById('btnImportJSON')?.addEventListener('click', showImportModal);
 document.getElementById('btnExport')?.addEventListener('click', exportAllData);
 document.getElementById('btnOpenShopee')?.addEventListener('click', () => chrome.tabs.create({ url: 'https://shopee.vn' }));
 document.getElementById('btnCopySpcfTool')?.addEventListener('click', handleCopySpcfTool);
 document.getElementById('btnClearAll')?.addEventListener('click', clearAllAccounts);
 document.getElementById('btnCheckUpdate')?.addEventListener('click', checkUpdate);
 document.getElementById('btnOpenSupport')?.addEventListener('click', () => chrome.tabs.create({ url: chrome.runtime.getURL('pages/support.html') }));
 document.getElementById('btnOpenOptions')?.addEventListener('click', () => chrome.runtime.openOptionsPage());
 document.getElementById('setDefaultDomain')?.addEventListener('change', saveSettings);
 document.getElementById('setAutoOpenTab')?.addEventListener('change', saveSettings);
 document.getElementById('setShowNotifs')?.addEventListener('change', saveSettings);
 document.getElementById('btnCloseModal')?.addEventListener('click', hideImportModal);
 document.getElementById('btnCancelImport')?.addEventListener('click', hideImportModal);
 document.getElementById('btnConfirmImport')?.addEventListener('click', confirmImport);
 document.getElementById('importModal')?.addEventListener('click', e => { if (e.target.id === 'importModal') hideImportModal(); });
 document.getElementById('fileImport')?.addEventListener('change', handleFileImport);
 document.getElementById('headerVer')?.addEventListener('click', () => chrome.tabs.create({ url: chrome.runtime.getURL('pages/changelog.html') }));
}
async function refreshAll() {
 await Promise.all([
 updateStats(),
 loadAccountList(),
 loadHistory(),
 updateStatusLabel()
 ]);
}
async function updateStats() {
 try {
 const [accounts, loginStats, loginHistory] = await Promise.all([
 getStorage('accounts'),
 getStorage('loginStats'),
 getStorage('loginHistory')
 ]);
 const accs = accounts || [];
 const stats = loginStats || { totalLogins: 0 };
 const history = loginHistory || [];
 setEl('stat-shops', accs.length);
 setEl('stat-logins', stats.totalLogins || stats.count || 0);
 setEl('shopCountBadge', `${accs.length} shop`);
 if (history.length > 0) {
 const last = history[0];
 setEl('stat-last', formatTimeAgo(last.time || last.timestamp));
 setEl('stat-last-shop', last.shopName || '');
 }
 } catch (e) {
 console.warn('[V-ShopeeBoost] Stats update error:', e);
 }
}
async function updateStatusLabel() {
 try {
 const history = (await getStorage('loginHistory')) || [];
 const dot = document.getElementById('statusDot');
 const label = document.getElementById('statusLabel');
 if (history.length > 0) {
 dot?.classList.add('active');
 if (label) label.textContent = history[0].shopName || 'Đã đăng nhập';
 } else {
 dot?.classList.remove('active');
 if (label) label.textContent = 'Chưa đăng nhập';
 }
 } catch (e) {}
}
async function initVersionDisplay() {
 const manifest = chrome.runtime.getManifest();
 const ver = manifest.version || APP_VERSION;
 setEl('headerVer', `v${ver}`);
 setEl('settingsVersion', `V-ShopeeBoost v${ver}`);
}
async function checkUpdate() {
 const btn = document.getElementById('btnCheckUpdate');
 if (btn) btn.textContent = 'Đang kiểm tra...';
 try {
 const result = await chrome.runtime.sendMessage({ action: 'check_update' });
 showToast(`Phiên bản hiện tại: v${result.currentVersion} — Đây là phiên bản mới nhất!`, 'info');
 } catch (e) {
 showToast('Không thể kiểm tra cập nhật', 'warning');
 } finally {
 if (btn) btn.textContent = 'Kiểm tra cập nhật';
 }
}
function clearForm() {
 ['spcfToken', 'cookiesInput', 'shopName'].forEach(id => {
 const el = document.getElementById(id);
 if (el) el.value = '';
 });
 showStatus('Đã xóa form', 'info');
}
async function saveAccount() {
 const spcf = val('spcfToken');
 const cookies = val('cookiesInput');
 const shopName = val('shopName');
 const domain = val('domainSelect');
 if (!shopName) { showStatus('Vui lòng nhập tên shop!', 'error'); document.getElementById('shopName')?.focus(); return; }
 if (!spcf && !cookies) { showStatus('Vui lòng nhập SPC_F hoặc Cookies!', 'error'); return; }
 try {
 const accounts = (await getStorage('accounts')) || [];
 const newAcc = {
 id: Date.now(),
 shopName, spcfToken: spcf, cookies, domain,
 createdAt: new Date().toISOString(), lastUsed: null, loginCount: 0
 };
 const idx = accounts.findIndex(a => a.shopName === shopName);
 if (idx !== -1) {
 accounts[idx] = { ...accounts[idx], ...newAcc };
 showToast('Đã cập nhật: ' + shopName, 'success');
 } else {
 accounts.push(newAcc);
 showToast('Đã lưu shop: ' + shopName, 'success');
 }
 await setStorage({ accounts });
 clearForm();
 await refreshAll();
 } catch (e) {
 showStatus('Lỗi khi lưu: ' + e.message, 'error');
 }
}
async function performLogin() {
 const spcf = val('spcfToken');
 const cookiesInput = val('cookiesInput');
 const domain = val('domainSelect');
 const shopName = val('shopName');
 const applySpf = document.getElementById('chkSpcfChange')?.checked;
 const applyCk = document.getElementById('chkCookiesChange')?.checked;
 if (!spcf && !cookiesInput) { showStatus('Vui lòng nhập SPC_F hoặc Cookies!', 'error'); return; }
 showStatus('Đang đăng nhập...', 'info');
 const btn = document.getElementById('btnLoginNow');
 if (btn) { btn.textContent = 'Đang xử lý...'; btn.disabled = true; }
 try {
 await setCookiesLocal(applySpf ? spcf : '', applyCk ? cookiesInput : '', domain);
 const history = (await getStorage('loginHistory')) || [];
 history.unshift({ shopName: shopName || domain, domain, time: new Date().toISOString() });
 if (history.length > 50) history.splice(50);
 const stats = (await getStorage('loginStats')) || { totalLogins: 0 };
 stats.totalLogins = (stats.totalLogins || stats.count || 0) + 1;
 stats.lastLogin = new Date().toISOString();
 delete stats.count;
 if (shopName) {
 const accounts = (await getStorage('accounts')) || [];
 const acc = accounts.find(a => a.shopName === shopName);
 if (acc) {
 acc.lastUsed = new Date().toISOString();
 acc.loginCount = (acc.loginCount || 0) + 1;
 await setStorage({ accounts });
 }
 }
 await setStorage({ loginHistory: history, loginStats: stats });
 showStatus('Đăng nhập thành công! Đang mở Shopee...', 'success');
 await refreshAll();
 const settings = (await getStorage('settings')) || {};
 if (settings.autoOpenTab !== false) {
 setTimeout(() => chrome.tabs.create({ url: `https://${domain}` }), 700);
 }
 } catch (e) {
 console.error('[V-ShopeeBoost] Login error:', e);
 showStatus('Lỗi: ' + e.message, 'error');
 } finally {
 if (btn) { btn.textContent = '▶ Đăng nhập ngay'; btn.disabled = false; }
 }
}
async function setCookiesLocal(spcf, cookiesInput, domain) {
 domain = normalizeShopeeDomain(domain);
 const baseUrl = `https://${domain}`;
 if (spcf) {
 await chrome.cookies.set({
 url: baseUrl, name: 'SPC_F', value: spcf,
 domain: `.${domain}`, path: '/', secure: true, sameSite: 'no_restriction'
 });
 }
 if (cookiesInput) {
 const arr = parseCookieInput(cookiesInput);
 for (const c of arr) {
 if (!c.name || c.value === undefined) continue;
 try {
 const requestedCookieDomain = String(c.domain || `.${domain}`).trim().toLowerCase().replace(/^\.+/, '');
 if (requestedCookieDomain !== domain && !requestedCookieDomain.endsWith(`.${domain}`)) {
  throw new Error('Cookie domain nằm ngoài tên miền Shopee đã chọn');
 }
 const sameSite = ['no_restriction', 'lax', 'strict', 'unspecified'].includes(c.sameSite)
  ? c.sameSite
  : 'no_restriction';
 const cookiePath = typeof c.path === 'string' && c.path.startsWith('/') ? c.path.slice(0, 2048) : '/';
 await chrome.cookies.set({
 url: baseUrl, name: String(c.name).slice(0, 256), value: String(c.value).slice(0, 8192),
 domain: `.${requestedCookieDomain}`, path: cookiePath,
 secure: c.secure !== false, sameSite,
 ...(Number.isFinite(Number(c.expirationDate)) ? { expirationDate: Number(c.expirationDate) } : {})
 });
 } catch (e) {
 console.warn(`[V-ShopeeBoost] Cookie "${c.name}" error:`, e.message);
 }
 }
 }
}
function parseCookieInput(input) {
 const trimmed = input.trim();
 if (trimmed.startsWith('[') || trimmed.startsWith('{')) {
 try {
 const p = JSON.parse(trimmed);
 return Array.isArray(p) ? p : [p];
 } catch { }
 }
 return trimmed.split(';').map(p => {
 const [name, ...rest] = p.split('=');
 return name?.trim() ? { name: name.trim(), value: rest.join('=').trim() } : null;
 }).filter(Boolean);
}
async function autoGetCookies() {
 const btn = document.getElementById('btnGetCookie');
 if (btn) btn.textContent = 'Đang lấy...';
 try {
 showStatus('Đang quét tab Shopee...', 'info');
 const tabs = await chrome.tabs.query({ active: true, currentWindow: true });
 if (!tabs?.length) { showStatus('Không tìm thấy tab active', 'error'); return; }
 const url = new URL(tabs[0].url);
 const shopeeRoot = findShopeeRoot(url.hostname);
 if (!shopeeRoot) { showStatus('Vui lòng mở tab Shopee và thử lại!', 'error'); return; }
 const cookies = await chrome.cookies.getAll({ domain: url.hostname });
 if (!cookies?.length) { showStatus('Không tìm thấy cookie nào!', 'error'); return; }
 const spcf = cookies.find(c => c.name === 'SPC_F')?.value || '';
 const domain = shopeeRoot;
 const cookieArr = cookies.map(c => ({
 domain: c.domain, expirationDate: c.expirationDate, hostOnly: c.hostOnly,
 httpOnly: c.httpOnly, name: c.name, path: c.path,
 sameSite: c.sameSite, secure: c.secure, session: c.session, value: c.value
 }));
 setInputVal('spcfToken', spcf);
 setInputVal('cookiesInput', JSON.stringify(cookieArr));
 const domainSelect = document.getElementById('domainSelect');
 if (domainSelect) domainSelect.value = domain;
 let title = tabs[0].title?.split(' - ')[0]?.split(' | ')[0]?.trim() || '';
 if (title.length > 30) title = title.substring(0, 30) + '...';
 setInputVal('shopName', title);
 showStatus(`Đã lấy ${cookies.length} cookie thành công!`, 'success');
 showToast('Lấy cookie Shopee thành công!', 'success');
 } catch (e) {
 showStatus('Lỗi: ' + e.message, 'error');
 } finally {
 if (btn) btn.textContent = 'Lấy Cookie';
 }
}
async function loadAccountList(query = '') {
 const accounts = (await getStorage('accounts')) || [];
 const container = document.getElementById('accountList');
 if (!container) return;
 setEl('shopCountBadge', `${accounts.length} shop`);
 if (accounts.length === 0) {
 container.innerHTML = `<div class="empty-state">
 <div class="empty-icon"></div>
 <div class="empty-title">Chưa có shop nào</div>
 <div>Đăng nhập và lưu shop đầu tiên của bạn</div>
 </div>`;
 return;
 }
 let filtered = accounts;
 if (query) {
 const q = query.toLowerCase();
 filtered = accounts.filter(a =>
 a.shopName?.toLowerCase().includes(q) || a.domain?.toLowerCase().includes(q)
 );
 }
 if (filtered.length === 0) {
 container.innerHTML = `<div class="empty-state">
 <div class="empty-icon"></div>
 <div class="empty-title">Không tìm thấy</div>
 <div>Thử từ khóa khác</div>
 </div>`;
 return;
 }
 const sorted = [...filtered].sort((a, b) => {
 if (!a.lastUsed && !b.lastUsed) return 0;
 if (!a.lastUsed) return 1;
 if (!b.lastUsed) return -1;
 return new Date(b.lastUsed) - new Date(a.lastUsed);
 });
 container.innerHTML = sorted.map(acc => {
 const safeId = Number.isFinite(Number(acc.id)) ? Number(acc.id) : -1;
 const initial = (acc.shopName || '?')[0].toUpperCase();
 const timeAgo = acc.lastUsed ? formatTimeAgo(acc.lastUsed) : null;
 let cookieCount = 0;
 try {
 const p = JSON.parse(acc.cookies || '[]');
 cookieCount = Array.isArray(p) ? p.length : '?';
 } catch { cookieCount = acc.cookies ? '?' : 0; }
 return `
 <div class="account-item" onclick="quickLogin(${safeId})">
 <div class="account-left">
 <div class="account-avatar">${escapeHtml(initial)}</div>
 <div>
 <div class="account-name">${escapeHtml(acc.shopName)}</div>
 <div class="account-meta">${escapeHtml(acc.domain)} · ${cookieCount} cookie${timeAgo ? ` · ${timeAgo}` : ''}</div>
 </div>
 </div>
 <div class="account-actions" onclick="event.stopPropagation()">
 <button class="icon-btn login-btn" onclick="quickLogin(${safeId})" title="Đăng nhập nhanh">▶</button>
 <button class="icon-btn" onclick="loadToForm(${safeId})" title="Xem / chỉnh sửa">✎</button>
 <button class="icon-btn del-btn" onclick="deleteAccount(${safeId})" title="Xóa">✕</button>
 </div>
 </div>`;
 }).join('');
}
window.quickLogin = async function (id) {
 await loadToForm(id);
 setTimeout(performLogin, 100);
};
window.loadToForm = async function (id) {
 const accounts = (await getStorage('accounts')) || [];
 const acc = accounts.find(a => a.id === id);
 if (!acc) return;
 setInputVal('spcfToken', acc.spcfToken || '');
 setInputVal('cookiesInput', acc.cookies || '');
 setInputVal('shopName', acc.shopName || '');
 const sel = document.getElementById('domainSelect');
 if (sel) sel.value = acc.domain || 'shopee.vn';
 switchToTab('main');
 showToast('Đã tải: ' + acc.shopName, 'info');
};
window.deleteAccount = async function (id) {
 if (!confirm('Xóa shop này khỏi danh sách đã lưu?')) return;
 const accounts = (await getStorage('accounts')) || [];
 await setStorage({ accounts: accounts.filter(a => a.id !== id) });
 await refreshAll();
 showToast('Đã xóa shop', 'success');
};
async function loadHistory() {
 const history = (await getStorage('loginHistory')) || [];
 const container = document.getElementById('historyList');
 if (!container) return;
 if (history.length === 0) {
 container.innerHTML = `<div class="empty-state">
 <div class="empty-icon"></div>
 <div class="empty-title">Chưa có lịch sử</div>
 <div>Lịch sử sẽ xuất hiện sau khi đăng nhập</div>
 </div>`;
 return;
 }
 container.innerHTML = history.slice(0, 30).map(h => `
 <div class="history-item">
 <div class="history-dot"></div>
 <div class="history-info">
 <div class="history-shop">${escapeHtml(h.shopName || h.domain || '?')}</div>
 <div class="history-time">${escapeHtml(h.domain || '')} · ${formatDateTime(h.time || h.timestamp)}</div>
 </div>
 <span class="history-badge">${formatTimeAgo(h.time || h.timestamp)}</span>
 </div>`).join('');
}
async function clearHistory() {
 if (!confirm('Xóa toàn bộ lịch sử đăng nhập?')) return;
 await setStorage({ loginHistory: [] });
 await loadHistory();
 showToast('Đã xóa lịch sử', 'success');
}
async function backupData() {
 try {
 const all = await chrome.storage.local.get(['accounts', 'loginStats', 'loginHistory']);
 const blob = new Blob([
 JSON.stringify({ version: APP_VERSION, createdAt: new Date().toISOString(), data: all }, null, 2)
 ], { type: 'application/json' });
 downloadBlob(blob, `vshopeeboost-backup-${Date.now()}.json`);
 showToast('Sao lưu thành công!', 'success');
 } catch (e) {
 showToast('Lỗi sao lưu: ' + e.message, 'error');
 }
}
async function handleFileImport(e) {
 const file = e.target.files[0];
 if (!file) return;
 try {
 if (file.size > 2_000_000) throw new Error('File backup quá lớn');
 const text = await file.text();
 if (text.length > 2_000_000) throw new Error('File backup quá lớn');
 const parsed = JSON.parse(text);
 if (!parsed.data) throw new Error('File không đúng định dạng backup');
 if (!confirm('Khôi phục sẽ ghi đè dữ liệu hiện tại. Tiếp tục?')) return;
 await chrome.storage.local.set(sanitizeBackupData(parsed.data));
 await refreshAll();
 showToast('Khôi phục thành công!', 'success');
 } catch (err) {
 showToast('Lỗi: ' + err.message, 'error');
 }
 e.target.value = '';
}
async function exportAllData() {
 const accounts = (await getStorage('accounts')) || [];
 if (!accounts.length) { showToast('Không có shop nào!', 'warning'); return; }
 const blob = new Blob([JSON.stringify(accounts, null, 2)], { type: 'application/json' });
 downloadBlob(blob, `vshopeeboost-shops-${Date.now()}.json`);
 showToast(`Đã xuất ${accounts.length} shop!`, 'success');
}
async function clearAllAccounts() {
 if (!confirm('Xóa TẤT CẢ shop đã lưu? Không thể hoàn tác!')) return;
 await setStorage({ accounts: [] });
 await refreshAll();
 showToast('Đã xóa tất cả shop', 'success');
}
function showImportModal() {
 document.getElementById('importModal')?.classList.add('show');
 setTimeout(() => document.getElementById('importData')?.focus(), 100);
}
function hideImportModal() {
 document.getElementById('importModal')?.classList.remove('show');
 setInputVal('importData', '');
}
async function confirmImport() {
 const raw = val('importData');
 if (!raw) { showToast('Vui lòng nhập dữ liệu JSON!', 'error'); return; }
 try {
 const parsed = JSON.parse(raw);
 let incoming = [];
 if (Array.isArray(parsed)) incoming = parsed;
 else if (parsed.accounts) incoming = parsed.accounts;
 else if (parsed.data?.accounts) incoming = parsed.data.accounts;
 else throw new Error('Không tìm thấy dữ liệu tài khoản');
 const accounts = (await getStorage('accounts')) || [];
 let added = 0;
 for (const acc of incoming.slice(0, 500)) {
 const safeAccount = sanitizeImportedAccount(acc, Date.now() + Math.random());
 if (safeAccount && !accounts.find(a => a.shopName === safeAccount.shopName)) {
 accounts.push(safeAccount);
 added++;
 }
 }
 await setStorage({ accounts });
 hideImportModal();
 await refreshAll();
 showToast(`Đã nhập ${added} shop mới!`, 'success');
 } catch (e) {
 showToast('JSON không hợp lệ: ' + e.message, 'error');
 }
}
async function loadSettings() {
 const settings = (await getStorage('settings')) || {};
 const domainEl = document.getElementById('setDefaultDomain');
 const autoOpenEl = document.getElementById('setAutoOpenTab');
 const notifsEl = document.getElementById('setShowNotifs');
 if (domainEl) domainEl.value = settings.defaultDomain || 'shopee.vn';
 if (autoOpenEl) autoOpenEl.checked = settings.autoOpenTab !== false;
 if (notifsEl) notifsEl.checked = settings.showNotifications !== false;
 const domainSelectEl = document.getElementById('domainSelect');
 if (domainSelectEl && !domainSelectEl.value) {
 domainSelectEl.value = settings.defaultDomain || 'shopee.vn';
 }
}
async function saveSettings() {
 const settings = {
 defaultDomain: document.getElementById('setDefaultDomain')?.value || 'shopee.vn',
 autoOpenTab: document.getElementById('setAutoOpenTab')?.checked ?? true,
 showNotifications: document.getElementById('setShowNotifs')?.checked ?? true
 };
 await setStorage({ settings });
 showToast('Đã lưu cài đặt', 'success');
}
function getStorage(key) {
 return new Promise(resolve => {
 chrome.storage.local.get(key, result => {
 if (typeof key === 'string') resolve(result[key]);
 else resolve(result);
 });
 });
}
function setStorage(items) {
 return new Promise(resolve => chrome.storage.local.set(items, resolve));
}
function val(id) { return document.getElementById(id)?.value?.trim() || ''; }
function setEl(id, text) { const el = document.getElementById(id); if (el) el.textContent = text; }
function setInputVal(id, v) { const el = document.getElementById(id); if (el) el.value = v; }
function escapeHtml(t) {
 if (!t) return '';
 const d = document.createElement('div');
 d.textContent = t;
 return d.innerHTML;
}
function switchToTab(tab) {
 document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
 document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
 document.querySelector(`[data-tab="${tab}"]`)?.classList.add('active');
 document.getElementById('tab-' + tab)?.classList.add('active');
}
function downloadBlob(blob, name) {
 const url = URL.createObjectURL(blob);
 const a = document.createElement('a');
 a.href = url; a.download = name; a.click();
 setTimeout(() => URL.revokeObjectURL(url), 1000);
}
function showStatus(msg, type) {
 const el = document.getElementById('loginStatus');
 if (!el) return;
 const safeType = ['success', 'error', 'info'].includes(type) ? type : 'info';
 el.replaceChildren();
 const text = document.createElement('span');
 text.textContent = String(msg || '');
 el.appendChild(text);
 el.className = `status-bar show ${safeType}`;
 clearTimeout(el._t);
 el._t = setTimeout(() => el.classList.remove('show'), 5000);
}
function showToast(msg, type = 'success') {
 const container = document.getElementById('toastContainer');
 if (!container) return;
 const icons = { success: '', error: '', warning: '', info: '' };
 const toast = document.createElement('div');
 const safeType = ['success', 'error', 'warning', 'info'].includes(type) ? type : 'info';
 toast.className = `toast ${safeType}`;
 const icon = document.createElement('span');
 icon.textContent = icons[safeType] || '';
 const text = document.createElement('span');
 text.textContent = String(msg || '');
 toast.append(icon, text);
 container.appendChild(toast);
 setTimeout(() => {
 toast.style.opacity = '0';
 toast.style.transform = 'translateX(12px)';
 toast.style.transition = 'all 0.3s ease';
 setTimeout(() => toast.remove(), 350);
 }, 3200);
}
function formatTimeAgo(dateStr) {
 if (!dateStr) return '';
 const diff = Date.now() - new Date(dateStr).getTime();
 const m = Math.floor(diff / 60000);
 const h = Math.floor(diff / 3600000);
 const d = Math.floor(diff / 86400000);
 if (m < 1) return 'vừa xong';
 if (m < 60) return `${m} phút trước`;
 if (h < 24) return `${h} giờ trước`;
 if (d < 7) return `${d} ngày trước`;
 return new Date(dateStr).toLocaleDateString('vi-VN');
}
function formatDateTime(dateStr) {
 if (!dateStr) return '';
 return new Date(dateStr).toLocaleString('vi-VN', {
 hour: '2-digit', minute: '2-digit', day: '2-digit', month: '2-digit', year: 'numeric'
 });
}
async function copyToClipboard(text) {
 try {
 await navigator.clipboard.writeText(text);
 return true;
 } catch (err) {
 const textarea = document.createElement('textarea');
 textarea.value = text;
 textarea.style.position = 'fixed';
 document.body.appendChild(textarea);
 textarea.select();
 try {
 document.execCommand('copy');
 document.body.removeChild(textarea);
 return true;
 } catch (e) {
 document.body.removeChild(textarea);
 return false;
 }
 }
}
async function getActiveTabSpcf() {
 const tabs = await chrome.tabs.query({ active: true, currentWindow: true });
 if (!tabs?.length) throw new Error('Không tìm thấy tab active');
 const url = new URL(tabs[0].url);
 if (!url.hostname.includes('shopee.')) {
 throw new Error('Vui lòng mở tab Shopee và thử lại!');
 }
 let cookies = [];
 try {
 cookies = await chrome.cookies.getAll({ url: tabs[0].url });
 } catch (e) {
 console.warn('[V-ShopeeBoost] Failed to get cookies by url:', e);
 }
 let spcf = cookies.find(c => c.name === 'SPC_F')?.value;
 if (!spcf) {
 try {
 cookies = await chrome.cookies.getAll({ domain: url.hostname });
 spcf = cookies.find(c => c.name === 'SPC_F')?.value;
 } catch (e) {
 console.warn('[V-ShopeeBoost] Failed to get cookies by domain:', e);
 }
 }
 if (!spcf) {
 const parts = url.hostname.split('.');
 if (parts.length >= 2) {
 const parentDomain = parts.slice(-2).join('.');
 try {
 cookies = await chrome.cookies.getAll({ domain: parentDomain });
 spcf = cookies.find(c => c.name === 'SPC_F')?.value;
 } catch (e) {
 console.warn('[V-ShopeeBoost] Failed to get cookies by parent domain:', e);
 }
 }
 }
 if (!spcf) {
 throw new Error('Không tìm thấy cookie SPC_F. Vui lòng đăng nhập Shopee trước!');
 }
 return { spcf, hostname: url.hostname };
}
async function handleCopySpcfInput() {
 const inputVal = val('spcfToken');
 if (inputVal) {
 const ok = await copyToClipboard(inputVal);
 if (ok) {
 showToast('Đã sao chép SPC_F vào bộ nhớ tạm!', 'success');
 } else {
 showToast('Không thể sao chép tự động', 'error');
 }
 return;
 }
 const btn = document.getElementById('btnCopySpcfInput');
 const oldText = btn ? btn.textContent : 'Sao chép';
 if (btn) { btn.textContent = 'Đang lấy...'; btn.disabled = true; }
 try {
 const { spcf } = await getActiveTabSpcf();
 setInputVal('spcfToken', spcf);
 const ok = await copyToClipboard(spcf);
 if (ok) {
 showToast('Đã lấy và sao chép SPC_F thành công!', 'success');
 showStatus('Đã lấy SPC_F thành công', 'success');
 } else {
 showToast('Đã lấy SPC_F nhưng sao chép thất bại', 'warning');
 }
 } catch (e) {
 showToast(e.message, 'error');
 showStatus('Lỗi: ' + e.message, 'error');
 } finally {
 if (btn) { btn.textContent = oldText; btn.disabled = false; }
 }
}
async function handleCopySpcfTool() {
 const btn = document.getElementById('btnCopySpcfTool');
 const oldTitle = btn ? btn.querySelector('.tool-title')?.textContent : 'Lấy mã SPC_F';
 const titleEl = btn ? btn.querySelector('.tool-title') : null;
 if (titleEl) titleEl.textContent = 'Đang lấy...';
 if (btn) btn.disabled = true;
 try {
 const { spcf, hostname } = await getActiveTabSpcf();
 const ok = await copyToClipboard(spcf);
 if (ok) {
 showToast(`Đã sao chép SPC_F của ${hostname} vào bộ nhớ tạm!`, 'success');
 } else {
 showToast('Đã lấy SPC_F nhưng không thể sao chép', 'warning');
 }
 } catch (e) {
 showToast(e.message, 'error');
 } finally {
 if (titleEl) titleEl.textContent = oldTitle;
 if (btn) btn.disabled = false;
 }
}
