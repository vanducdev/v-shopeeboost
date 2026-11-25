(function () {
 'use strict';
 if (window.__vShopeeBoostLoaded) return;
 window.__vShopeeBoostLoaded = true;
 const APP_NAME = '[V-ShopeeBoost]';
 const CONFIG = {
 debug: false,
 showNotifications: true,
 toastDuration: 4000
 };
 let isLoggedIn = false;
 let shadowRoot = null;
 let toastTimer = null;
 function init() {
 if (CONFIG.debug) console.log(`${APP_NAME} Content script loaded on:`, window.location.hostname);
 createOverlayUI();
 checkLoginStatus();
 setupMessageListener();
 observeLoginState();
 }
 async function checkLoginStatus() {
 try {
 const cookies = await chrome.runtime.sendMessage({
 action: 'get_cookies',
 data: { domain: getCurrentDomain(), names: ['SPC_F'] }
 });
 isLoggedIn = !!(cookies && cookies.SPC_F);
 updateLoginUI();
 } catch (error) {
 if (CONFIG.debug) console.error(`${APP_NAME} Error checking login:`, error);
 }
 }
 function setupMessageListener() {
 chrome.runtime.onMessage.addListener((message, sender, sendResponse) => {
 const { action, data } = message;
 switch (action) {
 case 'login_success':
 handleLoginSuccess(data);
 sendResponse({ success: true });
 break;
 case 'check_status':
 sendResponse({
 isLoggedIn,
 url: window.location.href,
 domain: getCurrentDomain()
 });
 break;
 case 'get_page_info':
 sendResponse(getPageInfo());
 break;
 case 'apply_cookies':
 applyCookies(data).then(() => sendResponse({ success: true }));
 return true;
 case 'logout':
 performLogout().then(() => sendResponse({ success: true }));
 return true;
 default:
 sendResponse({ error: 'Unknown action' });
 }
 });
 }
 async function applyCookies(cookieData) {
 try {
 const result = await chrome.runtime.sendMessage({
 action: 'set_cookies',
 data: {
 spcf: cookieData.spcf,
 cookies: cookieData.cookies,
 domain: cookieData.domain || getCurrentDomain()
 }
 });
 if (!result || (result.failed?.length > 0 && result.success?.length === 0)) {
 throw new Error('Failed to set cookies');
 }
 showNotification('Đã áp dụng cookies thành công!', 'success');
 setTimeout(() => window.location.reload(), 1200);
 } catch (error) {
 if (CONFIG.debug) console.error(`${APP_NAME} Error applying cookies:`, error);
 showNotification('Lỗi khi áp dụng cookies', 'error');
 }
 }
 async function performLogout() {
 try {
 await chrome.runtime.sendMessage({
 action: 'clear_cookies',
 data: { domain: getCurrentDomain() }
 });
 showNotification('Đã đăng xuất thành công!', 'success');
 setTimeout(() => window.location.reload(), 1200);
 } catch (error) {
 if (CONFIG.debug) console.error(`${APP_NAME} Error logging out:`, error);
 showNotification('Lỗi khi đăng xuất', 'error');
 }
 }
 function handleLoginSuccess(data) {
 isLoggedIn = true;
 updateLoginUI();
 if (CONFIG.showNotifications && data?.shopName) {
 showNotification(`Chào mừng ${data.shopName}!`, 'success');
 }
 }
 function getPageInfo() {
 return {
 url: window.location.href,
 domain: getCurrentDomain(),
 pathname: window.location.pathname,
 title: document.title,
 isShopee: isShopeeDomain(),
 isLoggedIn
 };
 }
 function isShopeeDomain() {
 return window.location.hostname.includes('shopee.');
 }
 function getCurrentDomain() {
 const hostname = window.location.hostname;
 const match = hostname.match(/shopee\..+$/);
 return match ? match[0] : 'shopee.vn';
 }
 function observeLoginState() {
 const observer = new MutationObserver(debounce(() => {
 const hasLoginButton = !!document.querySelector('[data-sqe="login-btn"], .login-button');
 const newLoginState = !hasLoginButton;
 if (newLoginState !== isLoggedIn) {
 isLoggedIn = newLoginState;
 updateLoginUI();
 }
 }, 800));
 if (document.body) {
 observer.observe(document.body, { childList: true, subtree: false });
 }
 }
 function createOverlayUI() {
 const host = document.createElement('div');
 host.id = 'vsb-host';
 document.body.appendChild(host);
 shadowRoot = host.attachShadow({ mode: 'closed' });
 const style = document.createElement('style');
 style.textContent = `
 #vsb-toast {
 position: fixed;
 bottom: 24px;
 right: 24px;
 z-index: 2147483647;
 display: flex;
 align-items: center;
 gap: 10px;
 padding: 12px 16px;
 background: rgba(15, 15, 25, 0.96);
 border-radius: 10px;
 box-shadow: 0 8px 32px rgba(0,0,0,0.4), 0 0 0 1px rgba(255,255,255,0.08);
 color: #e8eaf0;
 font-family: -apple-system, 'Segoe UI', sans-serif;
 font-size: 13px;
 font-weight: 500;
 transform: translateX(calc(100% + 32px));
 opacity: 0;
 transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1), opacity 0.3s ease;
 backdrop-filter: blur(12px);
 border-left: 3px solid #666;
 pointer-events: none;
 max-width: 300px;
 }
 #vsb-toast.show { transform: translateX(0); opacity: 1; pointer-events: auto; }
 #vsb-toast.success { border-left-color: #22c55e; }
 #vsb-toast.error { border-left-color: #ef4444; }
 #vsb-toast.warning { border-left-color: #f59e0b; }
 .vsb-toast-icon { font-size: 16px; flex-shrink: 0; }
 `;
 const toast = document.createElement('div');
 toast.id = 'vsb-toast';
 toast.innerHTML = `
 <span class="vsb-toast-icon" id="vsb-icon">✓</span>
 <span id="vsb-msg"></span>
 `;
 shadowRoot.appendChild(style);
 shadowRoot.appendChild(toast);
 }
 function showNotification(message, type = 'info') {
 if (!CONFIG.showNotifications || !shadowRoot) return;
 const icons = { success: '✓', error: '✗', warning: '⚠', info: 'ℹ' };
 const toast = shadowRoot.getElementById('vsb-toast');
 const icon = shadowRoot.getElementById('vsb-icon');
 const msg = shadowRoot.getElementById('vsb-msg');
 if (!toast || !icon || !msg) return;
 icon.textContent = icons[type] || 'ℹ';
 msg.textContent = message;
 toast.className = `show ${type}`;
 clearTimeout(toastTimer);
 toastTimer = setTimeout(() => { toast.className = ''; }, CONFIG.toastDuration);
 }
 function updateLoginUI() {
 }
 function debounce(func, wait) {
 let timeout;
 return function (...args) {
 clearTimeout(timeout);
 timeout = setTimeout(() => func.apply(this, args), wait);
 };
 }
 if (document.readyState === 'loading') {
 document.addEventListener('DOMContentLoaded', init, { once: true });
 } else {
 init();
 }
})();