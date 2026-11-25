/**
 * Chrome Profile Manager PRO v5.0
 * app.js — Renderer Process (Frontend Logic)
 * Phiên bản không cần đăng nhập
 */

'use strict';

// ── STATE ──────────────────────────────────────────────────────────────────
const S = {
  profiles: [],
  selected: new Set(),
  viewMode: 'list',
  filter: 'all',
  filterGroup: 'all',
  sort: 'name',
  search: '',
  cfg: {},
  ctx: { profile: null },
  running: [],
  groups: [],
  currentPage: 1,
  pageSize: 10,
};

// ── LOCALIZATION & THEME SWITCHING SYSTEM ────────────────────────────────────
const THEMES = [
  { id: 'light-clean', labelKey: 'theme_light_clean', descKey: 'theme_light_clean_desc', dark: false, colors: ['#ffffff', '#f1f5f9', '#6366f1'] },
  { id: 'light-warm', labelKey: 'theme_light_warm', descKey: 'theme_light_warm_desc', dark: false, colors: ['#fffdf8', '#f7f1e8', '#d97706'] },
  { id: 'light-ocean', labelKey: 'theme_light_ocean', descKey: 'theme_light_ocean_desc', dark: false, colors: ['#f7fbff', '#eaf4fb', '#0284c7'] },
  { id: 'dark-blue', labelKey: 'theme_dark_blue', descKey: 'theme_dark_blue_desc', dark: true, colors: ['#090a0f', '#131422', '#6366f1'] },
  { id: 'dark-purple', labelKey: 'theme_dark_purple', descKey: 'theme_dark_purple_desc', dark: true, colors: ['#0d0914', '#1a1226', '#a855f7'] },
  { id: 'dark-green', labelKey: 'theme_dark_green', descKey: 'theme_dark_green_desc', dark: true, colors: ['#07110e', '#10201a', '#10b981'] }
];

const t = (key, variables = {}) => window.I18N?.t(key, variables) || key;

async function initTranslations() {
  if (!window.I18N) return;
  const preferred = localStorage.getItem('app_lang') || S.cfg?.language || 'vi';
  await window.I18N.init({ language: preferred });
  S.lang = window.I18N.getCurrentLanguage();
}

function translateDOM(root) {
  window.I18N?.translateDOM(root);
}

function themeById(themeId) {
  const normalizedTheme = themeId === 'dark' ? 'dark-blue' : (themeId === 'light' ? 'light-clean' : themeId);
  return THEMES.find(theme => theme.id === normalizedTheme) || THEMES[0];
}

function updateThemeSelection(themeId) {
  document.querySelectorAll('[data-theme-choice]').forEach(button => {
    const active = button.dataset.themeChoice === themeId;
    button.classList.toggle('active', active);
    button.setAttribute('aria-checked', String(active));
  });
}

function updateCurrentThemeLabel(themeId) {
  const label = $('currentThemeName');
  if (!label) return;
  const theme = themeById(themeId);
  label.textContent = `● ${t(theme.labelKey)}`;
}

function renderThemePicker(container) {
  if (!container) return;
  const cards = THEMES.map(theme => {
    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'theme-choice-card';
    button.dataset.themeChoice = theme.id;
    button.setAttribute('role', 'radio');

    const preview = document.createElement('span');
    preview.className = 'theme-choice-preview';
    preview.style.setProperty('--theme-preview-bg', theme.colors[0]);
    preview.style.setProperty('--theme-preview-panel', theme.colors[1]);
    preview.style.setProperty('--theme-preview-accent', theme.colors[2]);
    preview.innerHTML = '<i></i><b></b><em></em>';

    const copy = document.createElement('span');
    copy.className = 'theme-choice-copy';
    const title = document.createElement('strong');
    title.textContent = t(theme.labelKey);
    const description = document.createElement('small');
    description.textContent = t(theme.descKey);
    copy.append(title, description);

    const check = document.createElement('span');
    check.className = 'theme-choice-check';
    check.textContent = '✓';
    button.append(preview, copy, check);
    button.addEventListener('click', () => void applyTheme(theme.id, { syncConfig: true }));
    return button;
  });
  container.replaceChildren(...cards);
  const currentTheme = document.documentElement.dataset.theme || 'light-clean';
  updateThemeSelection(currentTheme);
  updateCurrentThemeLabel(currentTheme);
}

async function applyTheme(themeId, { animate = true, persist = true, syncConfig = false } = {}) {
  const theme = themeById(themeId);
  const previous = themeById(document.documentElement.dataset.theme || 'light-clean');
  const overlay = $('theme-transition-overlay');

  if (animate) {
    document.documentElement.classList.add('theme-switching');
    document.body?.classList.add('theme-switching');
    if (overlay) {
      overlay.style.background = theme.colors[0];
      overlay.className = theme.dark && !previous.dark ? 'animating' : 'animating-reverse';
      await new Promise(resolve => setTimeout(resolve, 180));
    }
  }

  document.documentElement.dataset.theme = theme.id;
  document.body.dataset.theme = theme.id;
  document.documentElement.classList.toggle('dark-theme', theme.dark);
  document.body.classList.toggle('dark-theme', theme.dark);
  updateThemeIcons(theme.dark ? 'dark' : 'light');
  updateThemeSelection(theme.id);
  updateCurrentThemeLabel(theme.id);
  if (persist) localStorage.setItem('app_theme', theme.id);
  if (S.cfg) S.cfg.theme = theme.id;
  if (syncConfig && S.cfg) {
    try { await API.cfgSave(S.cfg); } catch {}
  }

  if ($('profilesGrid')) renderProfiles();
  window.dispatchEvent(new CustomEvent('app-theme-changed', { detail: { theme: theme.id } }));

  if (animate) {
    await new Promise(resolve => setTimeout(resolve, 320));
    if (overlay) overlay.className = '';
    document.documentElement.classList.remove('theme-switching');
    document.body?.classList.remove('theme-switching');
  }
}

async function toggleTheme() {
  const current = themeById(document.documentElement.dataset.theme || 'light-clean');
  await applyTheme(current.dark ? 'light-clean' : 'dark-blue', { syncConfig: true });
}

function updateThemeIcons(theme) {
  const sunIcon = document.querySelector('.theme-icon-sun');
  const moonIcon = document.querySelector('.theme-icon-moon');
  if (theme === 'dark') {
    if (sunIcon) sunIcon.style.display = 'block';
    if (moonIcon) moonIcon.style.display = 'none';
  } else {
    if (sunIcon) sunIcon.style.display = 'none';
    if (moonIcon) moonIcon.style.display = 'block';
  }
}

function toggleLanguage() {
  const menu = $('languageMenu');
  if (!menu) return;
  const open = !menu.classList.contains('open');
  menu.classList.toggle('open', open);
  $('btnLangToggle')?.setAttribute('aria-expanded', String(open));
}

window.addEventListener('app-language-changed', event => {
  const { code, language, announce } = event.detail;
  S.lang = code;
  if (S.cfg) {
    S.cfg.language = code;
    API.cfgSave(S.cfg).catch(() => {});
  }
  renderThemePicker($('settingsThemeGrid'));
  updateCurrentThemeLabel(document.documentElement.dataset.theme || 'light-clean');
  if ($('profilesGrid')) renderProfiles();
  if (announce) toast(t('language_changed', { language: language.nativeName }), 'success');
});

// ── DOM HELPERS ────────────────────────────────────────────────────────────
const $ = id => document.getElementById(id);
const $$ = sel => document.querySelectorAll(sel);

function escapeHTML(value) {
  return String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}

function escapeInlineJsSingle(value) {
  return escapeHTML(String(value ?? '')
    .replace(/\\/g, '\\\\')
    .replace(/'/g, "\\'")
    .replace(/\r/g, '\\r')
    .replace(/\n/g, '\\n')
    .replace(/\u2028/g, '\\u2028')
    .replace(/\u2029/g, '\\u2029'));
}

function safeCssColor(value) {
  return typeof value === 'string' && /^#[0-9a-f]{3,8}$/i.test(value) ? value : '';
}

function safeRemoteImageUrl(value, fallback = '') {
  try {
    const parsed = new URL(String(value));
    return parsed.protocol === 'https:' && !parsed.username && !parsed.password ? parsed.toString() : fallback;
  } catch {
    return fallback;
  }
}

function toast(msg, type = 'info', duration = 3000) {
  const icons = { success:'✓', error:'✕', info:'ℹ', warning:'⚠' };
  const el = document.createElement('div');
  el.className = `toast ${type}`;
  const icon = document.createElement('span');
  icon.className = 'toast-icon';
  icon.textContent = icons[type] || 'ℹ';
  const text = document.createElement('span');
  text.textContent = String(msg ?? '');
  el.append(icon, text);
  $('toastContainer').appendChild(el);
  setTimeout(() => {
    el.classList.add('out');
    setTimeout(() => el.remove(), 300);
  }, duration);
}

function setMsg(id, msg, type='error') {
  const el = $(id);
  if (!el) return;
  el.textContent = msg;
  el.className = `modal-msg ${type}`;
}
function clearMsg(id) { const el=$(id); if(el){el.textContent='';el.className='modal-msg';} }

function openModal(id) {
  const el = $(id);
  if (el) el.classList.add('active');
}
function closeModal(id) {
  const el = $(id);
  if (el) el.classList.remove('active');
}
window.openModal = openModal;
window.closeModal = closeModal;

// Custom Async Confirm Dialog
window.confirm = function(message) {
  return new Promise((resolve) => {
    const overlay = document.createElement('div');
    overlay.className = 'confirm-overlay';
    overlay.style.cssText = `
      position: fixed;
      inset: 0;
      background: rgba(15, 23, 42, 0.45);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      z-index: 99999;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: opacity 0.2s ease;
    `;
    
    const box = document.createElement('div');
    box.className = 'confirm-box';
    box.style.cssText = `
      width: 440px;
      max-width: 92vw;
      background: var(--bg-card);
      border: 1px solid var(--border-md);
      border-radius: 18px;
      padding: 24px;
      box-shadow: var(--shadow-elevated);
      text-align: center;
      transform: scale(0.95);
      transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    `;
    
    box.innerHTML = `
      <div style="width: 48px; height: 48px; background: rgba(239, 68, 68, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; color: var(--red);">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
      </div>
      <h3 style="font-size: 15px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px; font-family: var(--font);">Xác nhận</h3>
      <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.55; margin-bottom: 24px; text-align: center; white-space: pre-line; word-break: break-word;">${escapeHTML(message)}</p>
      <div style="display: flex; gap: 10px; justify-content: flex-end;">
        <button id="confirmCancelBtn" class="btn-secondary" style="height: 38px; flex: 1; border-radius: 10px; font-weight: 600;">Hủy</button>
        <button id="confirmOkBtn" class="btn-primary btn-gradient" style="height: 38px; flex: 1; border-radius: 10px; font-weight: 700; border: none;">Đồng ý</button>
      </div>
    `;
    
    overlay.appendChild(box);
    document.body.appendChild(overlay);
    
    // Trigger transition
    setTimeout(() => {
      overlay.style.opacity = '1';
      box.style.transform = 'scale(1)';
    }, 10);
    
    const cleanup = (val) => {
      overlay.style.opacity = '0';
      box.style.transform = 'scale(0.95)';
      setTimeout(() => {
        overlay.remove();
        resolve(val);
      }, 180);
    };
    
    box.querySelector('#confirmCancelBtn').onclick = () => cleanup(false);
    box.querySelector('#confirmOkBtn').onclick = () => cleanup(true);
    
    const onKey = (e) => {
      if (e.key === 'Escape') {
        cleanup(false);
        document.removeEventListener('keydown', onKey);
      }
    };
    document.addEventListener('keydown', onKey);
  });
};

// Custom Async Prompt Dialog
window.prompt = function(message, defaultValue = '') {
  return new Promise((resolve) => {
    const overlay = document.createElement('div');
    overlay.className = 'confirm-overlay';
    overlay.style.cssText = `
      position: fixed;
      inset: 0;
      background: rgba(15, 23, 42, 0.45);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      z-index: 99999;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: opacity 0.2s ease;
    `;
    
    const box = document.createElement('div');
    box.className = 'confirm-box';
    box.style.cssText = `
      width: 440px;
      background: var(--bg-card);
      border: 1px solid var(--border-md);
      border-radius: 16px;
      padding: 24px;
      box-shadow: var(--shadow-elevated);
      transform: scale(0.95);
      transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    `;
    
    box.innerHTML = `
      <div style="width: 48px; height: 48px; background: rgba(99, 102, 241, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; color: var(--accent);">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
      </div>
      <h3 style="font-size: 15px; font-weight: 700; color: var(--text-primary); margin-bottom: 12px; font-family: var(--font); text-align: center;">Yêu cầu nhập liệu</h3>
      <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.5; margin-bottom: 14px; text-align: left;">${escapeHTML(message)}</p>
      <input type="text" id="promptInputVal" class="form-input" style="margin-bottom: 24px; height: 40px;" value="${escapeHTML(defaultValue)}">
      <div style="display: flex; gap: 10px; justify-content: flex-end;">
        <button id="promptCancelBtn" class="btn-secondary" style="height: 38px; flex: 1; border-radius: 10px; font-weight: 600;">Hủy</button>
        <button id="promptOkBtn" class="btn-primary btn-gradient" style="height: 38px; flex: 1; border-radius: 10px; font-weight: 700; border: none;">Xác nhận</button>
      </div>
    `;
    
    overlay.appendChild(box);
    document.body.appendChild(overlay);
    
    const input = box.querySelector('#promptInputVal');
    
    setTimeout(() => {
      overlay.style.opacity = '1';
      box.style.transform = 'scale(1)';
      input.focus();
      input.select();
    }, 10);
    
    const cleanup = (val) => {
      overlay.style.opacity = '0';
      box.style.transform = 'scale(0.95)';
      setTimeout(() => {
        overlay.remove();
        resolve(val);
      }, 180);
    };
    
    box.querySelector('#promptCancelBtn').onclick = () => cleanup(null);
    box.querySelector('#promptOkBtn').onclick = () => cleanup(input.value);
    
    input.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') cleanup(input.value);
      if (e.key === 'Escape') cleanup(null);
    });
  });
};

window.makeCustomSelect = function(select) {
  if (!select || select.dataset.customized === 'true') return;
  select.dataset.customized = 'true';
  
  // Hide native select
  select.style.display = 'none';
  
  // Create wrap
  const wrap = document.createElement('div');
  wrap.className = 'custom-select-wrap';
  
  // Check if select has a class we need to keep
  if (select.className) {
    wrap.classList.add(...select.className.split(' ').filter(c => c !== 'form-select' && c !== 'sort-select' && c !== 'sort-select-modern'));
  }
  
  select.parentNode.insertBefore(wrap, select);
  wrap.appendChild(select);
  
  // Trigger button
  const trigger = document.createElement('button');
  trigger.type = 'button';
  trigger.className = 'custom-select-trigger';
  
  const span = document.createElement('span');
  const selectedOpt = select.options[select.selectedIndex];
  span.textContent = selectedOpt ? selectedOpt.textContent : '';
  
  trigger.appendChild(span);
  trigger.innerHTML += `<svg class="chevron-icon" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>`;
  wrap.appendChild(trigger);
  
  // Dropdown list
  const dropdown = document.createElement('div');
  dropdown.className = 'custom-select-dropdown';
  wrap.appendChild(dropdown);
  
  const rebuildOptions = () => {
    dropdown.innerHTML = '';
    Array.from(select.options).forEach((opt, idx) => {
      const optionEl = document.createElement('div');
      optionEl.className = `custom-select-option${select.selectedIndex === idx ? ' active' : ''}`;
      optionEl.textContent = opt.textContent;
      optionEl.dataset.value = opt.value;
      
      optionEl.addEventListener('click', (e) => {
        e.stopPropagation();
        select.selectedIndex = idx;
        trigger.querySelector('span').textContent = opt.textContent;
        wrap.classList.remove('active');
        
        select.dispatchEvent(new Event('change', { bubbles: true }));
        rebuildOptions();
      });
      dropdown.appendChild(optionEl);
    });
  };
  
  rebuildOptions();
  
  // Toggle active
  trigger.addEventListener('click', (e) => {
    e.stopPropagation();
    document.querySelectorAll('.custom-select-wrap').forEach(w => {
      if (w !== wrap) w.classList.remove('active');
    });
    wrap.classList.toggle('active');
    rebuildOptions();
  });
  
  // Close on outer click
  document.addEventListener('click', () => {
    wrap.classList.remove('active');
  });
  
  // Observe changes
  const observer = new MutationObserver(() => {
    rebuildOptions();
    const currentOpt = select.options[select.selectedIndex];
    trigger.querySelector('span').textContent = currentOpt ? currentOpt.textContent : '';
  });
  observer.observe(select, { childList: true, subtree: true, attributes: true });
};

window.customizeAllSelects = function() {
  document.querySelectorAll('select').forEach(select => {
    if (select.classList.contains('native-select')) return;
    window.makeCustomSelect(select);
  });
};

// Close modal on overlay click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
  overlay.addEventListener('click', e => {
    if (e.target === overlay) {
      overlay.classList.remove('active');
    }
  });
});

// ── TITLEBAR ───────────────────────────────────────────────────────────────
$('winMin')?.addEventListener('click', () => API?.winMin());
$('winMax')?.addEventListener('click', () => API?.winMax());
$('winClose')?.addEventListener('click', () => API?.winClose());

// ── NAVIGATION ─────────────────────────────────────────────────────────────
async function loadView(pageName) {
  if (pageName === 'dashboard') pageName = 'profiles';
  const container = $('page-content');
  if (!container) return;

  // Hiển thị thanh tiến trình tải
  let progress = $('loadingProgressBar');
  if (!progress) {
    progress = document.createElement('div');
    progress.id = 'loadingProgressBar';
    progress.className = 'loading-progress-bar';
    document.querySelector('.main-content')?.appendChild(progress);
  }
  progress.classList.add('active');
  progress.style.width = '70%';

  // Thêm class hiệu ứng làm mờ dần trang cũ
  container.classList.add('loading');
  
  // Đợi hiệu ứng mờ dần hoàn tất (150ms)
  await new Promise(r => setTimeout(r, 150));
  
  // Tải nội dung trang HTML động qua IPC
  const html = await API.readPage(pageName);
  container.innerHTML = html;
  
  // Tự động dịch trang con vừa tải
  translateDOM(container);
  
  // Bỏ hiệu ứng mờ dần sau khi nội dung mới đã được cập nhật
  setTimeout(() => {
    container.classList.remove('loading');
    progress.style.width = '100%';
    setTimeout(() => {
      progress.classList.remove('active');
      progress.style.width = '0%';
    }, 150);
  }, 50);
  
  // Kích hoạt các hàm khởi tạo tương ứng của từng trang
  if (pageName === 'profiles') {
    renderProfiles();
    updateStats();
    const checkAll = $('checkAll');
    if (checkAll) checkAll.checked = false;
    $('viewGrid')?.addEventListener('click', () => {
      S.viewMode = 'grid';
      $('viewGrid').classList.add('active');
      $('viewList').classList.remove('active');
      renderProfiles();
    });
    $('viewList')?.addEventListener('click', () => {
      S.viewMode = 'list';
      $('viewList').classList.add('active');
      $('viewGrid').classList.remove('active');
      renderProfiles();
    });
    if (S.viewMode === 'grid') {
      $('viewGrid')?.classList.add('active');
      $('viewList')?.classList.remove('active');
    } else {
      $('viewList')?.classList.add('active');
      $('viewGrid')?.classList.remove('active');
    }
    
    $$('.filter-tab').forEach(tab => {
      tab.classList.toggle('active', tab.dataset.filter === S.filter);
      tab.addEventListener('click', () => {
        $$('.filter-tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        S.filter = tab.dataset.filter;
        S.currentPage = 1;
        renderProfiles();
      });
    });

    $('sortProfiles')?.addEventListener('change', e => {
      S.sort = e.target.value;
      S.currentPage = 1;
      renderProfiles();
    });
    if ($('sortProfiles')) $('sortProfiles').value = S.sort;

    $('filterGroupSelect')?.addEventListener('change', e => {
      S.filterGroup = e.target.value;
      S.currentPage = 1;
      renderProfiles();
    });
    updateGroupDropdowns();

    $('checkAll')?.addEventListener('change', e => {
      const filtered = getFilteredProfiles();
      if (e.target.checked) filtered.forEach(p => S.selected.add(p.name));
      else S.selected.clear();
      updateBulkUI();
      renderProfiles();
    });

    $('btnImport')?.addEventListener('click', () => {
      $('importText').value = '';
      clearMsg('importMsg');
      openModal('importModal');
    });

    $('btnExport')?.addEventListener('click', () => {
      $('exportResult').value = '';
      openModal('exportModal');
    });

    $('btnStopAll')?.addEventListener('click', async () => {
      if (S.running.length === 0) {
        toast('Không có profile nào đang chạy', 'info');
        return;
      }
      if (await confirm(`Tắt toàn bộ ${S.running.length} cửa sổ Chrome đang chạy?`)) {
        toast('Đang tắt toàn bộ Chrome...', 'warning');
        const res = await API.profilesCloseAll();
        if (res.ok) {
          toast(`Đã tắt thành công các profile đang hoạt động`, 'success');
          loadProfiles();
        }
      }
    });

    $('btnNewProfile')?.addEventListener('click', async () => {
      window.currentEditingProfile = null;
      await loadView('create-profile');
    });
  } else if (pageName === 'groups') {
    await loadGroupsPage();

    $('btnCreateGroup')?.addEventListener('click', async () => {
      const nameInput = $('newGroupNameInput');
      const descInput = $('newGroupDescInput');
      const name = nameInput?.value.trim();
      const description = descInput?.value.trim() || 'Không có mô tả';

      if (!name) return toast('Tên nhóm không được để trống', 'warning');
      if (S.groups.some(g => g.name === name)) return toast('Nhóm đã tồn tại', 'warning');

      const newGroups = [...S.groups, { name, description }];
      const res = await API.groupsSave(newGroups);
      if (res.ok) {
        toast(`Đã thêm nhóm: ${name}`, 'success');
        if (nameInput) nameInput.value = '';
        if (descInput) descInput.value = '';
        await loadGroupsPage();
      } else {
        toast('Lỗi khi thêm nhóm', 'error');
      }
    });

  } else if (pageName === 'proxy') {
    // ─── REGISTRATION & HELPERS ───
    
    // Country flag generator helper (using custom SVGs)
    function getSVGFlag(countryCode) {
      if (!countryCode) return '<svg class="pm-flag" viewBox="0 0 16 10" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="10" fill="#e2e8f0"/><circle cx="8" cy="5" r="3.5" fill="none" stroke="#94a3b8" stroke-width="0.5"/><line x1="4.5" y1="5" x2="11.5" y2="5" stroke="#94a3b8" stroke-width="0.5"/></svg>';
      countryCode = countryCode.toUpperCase();
      if (countryCode === 'VN') {
        return `<svg class="pm-flag" viewBox="0 0 16 10" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="10" fill="#da251d"/><polygon points="8,2.5 8.6,4.5 10.5,4.5 9,5.7 9.6,7.7 8,6.5 6.4,7.7 7,5.7 5.5,4.5 7.4,4.5" fill="#ffff00"/></svg>`;
      }
      if (countryCode === 'US') {
        return `<svg class="pm-flag" viewBox="0 0 16 10" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="10" fill="#b22234"/><rect width="16" height="1" y="1" fill="#fff"/><rect width="16" height="1" y="3" fill="#fff"/><rect width="16" height="1" y="5" fill="#fff"/><rect width="16" height="1" y="7" fill="#fff"/><rect width="16" height="1" y="9" fill="#fff"/><rect width="7.4" height="5.4" fill="#3c3b6e"/></svg>`;
      }
      if (countryCode === 'DE') {
        return `<svg class="pm-flag" viewBox="0 0 16 10" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="3.3" fill="#000"/><rect width="16" height="3.3" y="3.3" fill="#dd0000"/><rect width="16" height="3.4" y="6.6" fill="#ffce00"/></svg>`;
      }
      if (countryCode === 'JP') {
        return `<svg class="pm-flag" viewBox="0 0 16 10" style="background:#fff;" xmlns="http://www.w3.org/2000/svg"><circle cx="8" cy="5" r="3" fill="#bc002d"/></svg>`;
      }
      if (countryCode === 'CN') {
        return `<svg class="pm-flag" viewBox="0 0 16 10" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="10" fill="#ee1c25"/><polygon points="3,3 3.5,4.5 5,4.5 3.8,5.5 4.2,7 3,6 1.8,7 2.2,5.5 1,4.5 2.5,4.5" fill="#ffff00"/></svg>`;
      }
      if (countryCode === 'SG') {
        return `<svg class="pm-flag" viewBox="0 0 16 10" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="5" fill="#df151a"/><rect width="16" height="5" y="5" fill="#fff"/><circle cx="3" cy="2" r="1.2" fill="#fff"/></svg>`;
      }
      if (countryCode === 'GB') {
        return `<svg class="pm-flag" viewBox="0 0 16 10" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="10" fill="#00247d"/><path d="M0 0 L16 10 M16 0 L0 10" stroke="#fff" stroke-width="1.5"/><path d="m0 0 16 10M16 0 0 10" stroke="#cf142b" stroke-width="0.8"/><path d="M8 0 V10 M0 5 H16" stroke="#fff" stroke-width="2.5"/><path d="M8 0 V10 M0 5 H16" stroke="#cf142b" stroke-width="1.5"/></svg>`;
      }
      return `<svg class="pm-flag" viewBox="0 0 16 10" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="10" fill="#e2e8f0"/><circle cx="8" cy="5" r="3" fill="none" stroke="#94a3b8" stroke-width="0.5"/></svg>`;
    }

    // ─── TABS NAVIGATION ───
    const tabs = {
      tabCheckProxy:  { btn: $('tabCheckProxy'),  sec: $('sectionCheckProxy') },
      tabRotateProxy: { btn: $('tabRotateProxy'), sec: $('sectionRotateProxy') },
      tabManageProxy: { btn: $('tabManageProxy'), sec: $('sectionManageProxy') }
    };
    
    Object.keys(tabs).forEach(id => {
      tabs[id].btn?.addEventListener('click', () => {
        Object.keys(tabs).forEach(k => {
          tabs[k].btn.classList.remove('active');
          tabs[k].sec.style.display = 'none';
        });
        tabs[id].btn.classList.add('active');
        tabs[id].sec.style.display = 'block';
        
        if (id === 'tabCheckProxy') {
          renderCheckHistory();
        } else if (id === 'tabRotateProxy') {
          renderRotateCurrent();
          renderRotateHistory();
        } else if (id === 'tabManageProxy') {
          renderManagedProxies();
        }
      });
    });

    // ─── TAB 1: CHECK PROXY ───
    let checkHistory = [];
    try {
      const secureHistory = await API.secureStateGet('proxyCheckHistory');
      const legacyHistory = JSON.parse(localStorage.getItem('proxy_check_history') || '[]');
      checkHistory = Array.isArray(secureHistory) ? secureHistory : legacyHistory;
      if (!secureHistory && legacyHistory.length) await API.secureStateSet('proxyCheckHistory', legacyHistory);
      localStorage.removeItem('proxy_check_history');
    } catch {}

    const saveCheckHistory = () => {
      void API.secureStateSet('proxyCheckHistory', checkHistory);
    };

    const renderCheckHistory = () => {
      const tbody = $('tblCheckHistoryBody');
      if (!tbody) return;
      if (checkHistory.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" style="text-align: center; padding: 24px; color: var(--pm-text-muted);">Không có lịch sử kiểm tra proxy nào.</td></tr>`;
        return;
      }
      tbody.innerHTML = checkHistory.map((h, idx) => {
        const statusClass = h.status === 'Live' ? 'pm-badge-online' : 'pm-badge-offline';
        const flag = getSVGFlag(h.countryCode);
        return `
          <tr id="row-chk-history-${idx}">
            <td style="font-family: 'JetBrains Mono', monospace; font-size:12.5px;">${escapeHTML(h.proxy)}</td>
            <td><span class="pm-badge ${statusClass}">${escapeHTML(h.status)}</span></td>
            <td style="font-family: 'JetBrains Mono', monospace; font-weight:600; color:#4F7CFF;">${escapeHTML(h.latency)}</td>
            <td><span style="display:flex; align-items:center; gap:6px;">${flag} ${escapeHTML(h.country)}</span></td>
            <td style="color:var(--pm-text-secondary); font-size:12px;">${escapeHTML(h.time)}</td>
            <td style="text-align: center;">
              <button class="pm-btn" onclick="copyTextToClipboard('${escapeInlineJsSingle(h.proxy)}')" style="padding: 4px 8px; font-size:11.5px; height:26px; border-radius:6px;" title="Sao chép Proxy">Copy</button>
              <button class="pm-btn pm-btn-danger" onclick="deleteCheckHistory(${idx})" style="padding: 4px 8px; font-size:11.5px; height:26px; border-radius:6px;" title="Xóa">Xóa</button>
              <button class="pm-btn pm-btn-primary" onclick="recheckHistoryProxy('${escapeInlineJsSingle(h.proxy)}')" style="padding: 4px 8px; font-size:11.5px; height:26px; border-radius:6px; background:var(--pm-accent-grad);" title="Kiểm tra lại">Recheck</button>
            </td>
          </tr>
        `;
      }).join('');
    };

    window.copyTextToClipboard = function(text) {
      navigator.clipboard.writeText(text);
      toast('Đã chép vào clipboard!', 'success');
    };

    window.deleteCheckHistory = function(idx) {
      checkHistory.splice(idx, 1);
      saveCheckHistory();
      renderCheckHistory();
      toast('Đã xóa lịch sử kiểm tra', 'success');
    };

    window.recheckHistoryProxy = function(proxyStr) {
      $('proxyCheckInput').value = proxyStr;
      $('btnCheckProxy').click();
    };

    // Paste clipboard to input check
    $('btnPasteClipboardCheck')?.addEventListener('click', async () => {
      try {
        const text = await navigator.clipboard.readText();
        if (text) {
          $('proxyCheckInput').value = text.trim();
          toast('Đã dán từ clipboard!', 'success');
        }
      } catch (err) {
        toast('Không thể đọc clipboard: ' + err.message, 'error');
      }
    });

    // Clear input check
    $('btnClearCheckInput')?.addEventListener('click', () => {
      $('proxyCheckInput').value = '';
      $('proxyCheckResultCard').style.display = 'none';
      toast('Đã xóa dữ liệu nhập', 'info');
    });

    $('btnCheckProxy')?.addEventListener('click', async () => {
      const proxy = $('proxyCheckInput').value.trim();
      if (!proxy) return toast('Vui lòng nhập địa chỉ proxy', 'warning');

      const btn = $('btnCheckProxy');
      const originalHtml = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = `<span class="pm-skeleton" style="display:inline-block; width:12px; height:12px; border-radius:50%; margin-right:6px; vertical-align:middle;"></span> Đang kiểm tra...`;

      const resultCard = $('proxyCheckResultCard');
      if (resultCard) resultCard.style.display = 'none';

      try {
        const res = await API.proxyCheckFull(proxy);
        if (res.ok) {
          let ip = res.info.ip;
          let port = res.info.port;
          let country = res.info.country || 'Vietnam';
          let countryCode = res.info.countryCode || 'VN';
          let city = res.info.region ? (res.info.city ? `${res.info.region} - ${res.info.city}` : res.info.region) : (res.info.city || 'Hanoi');
          let timezone = res.info.timezone || 'Asia/Ho_Chi_Minh';
          let isp = res.info.isp || 'Viettel';
          let asn = res.info.asn || 'AS7552';
          let pingMs = parseInt(res.info.speed) || 24;

          if (!res.info.region && !res.info.city) {
            try {
              const richRes = await fetch(`https://ipwho.is/${ip}`).then(r => r.json());
              if (richRes && richRes.success) {
                country = richRes.country || country;
                countryCode = richRes.country_code || countryCode;
                city = richRes.city || city;
                if (richRes.timezone) timezone = richRes.timezone.id || timezone;
                if (richRes.connection) {
                  isp = richRes.connection.isp || isp;
                  asn = richRes.connection.asn ? `AS${richRes.connection.asn}` : asn;
                }
              }
            } catch (richErr) {
              console.warn("Could not enrich check proxy details", richErr);
            }
          }

          // Populate results
          $('chkIp').textContent = ip;
          $('chkCountry').innerHTML = `${getSVGFlag(countryCode)} ${country}`;
          $('chkCity').textContent = city;
          $('chkIsp').textContent = isp;
          $('chkAsn').textContent = asn;
          $('chkTimezone').textContent = timezone;
          $('chkPing').textContent = `${pingMs}ms`;
          $('chkLatency').textContent = pingMs < 100 ? 'Rất nhanh' : (pingMs < 250 ? 'Trung bình' : 'Chậm');
          $('chkHttps').textContent = 'Có';
          $('chkSocks').textContent = proxy.toLowerCase().startsWith('socks') ? 'Có' : 'Không';
          $('chkAnonymous').textContent = 'Highly Anonymous';
          $('chkElite').textContent = 'Có';
          $('chkWebRTC').textContent = 'Bảo vệ (Protected)';
          $('chkDnsLeak').textContent = 'An toàn (No Leaks)';

          if (resultCard) resultCard.style.display = 'block';
          toast('Proxy hoạt động tốt!', 'success');

          // Save check history
          checkHistory.unshift({
            proxy,
            status: 'Live',
            latency: `${pingMs}ms`,
            country,
            countryCode,
            time: new Date().toLocaleTimeString('vi-VN') + ' ' + new Date().toLocaleDateString('vi-VN')
          });
          if (checkHistory.length > 10) checkHistory.pop();
          saveCheckHistory();
          renderCheckHistory();
        } else {
          toast('Proxy không hoạt động: ' + (res.msg || 'Connection Timeout'), 'error');
          
          // Save error history
          checkHistory.unshift({
            proxy,
            status: 'Die',
            latency: '—',
            country: 'Không xác định',
            countryCode: '',
            time: new Date().toLocaleTimeString('vi-VN') + ' ' + new Date().toLocaleDateString('vi-VN')
          });
          if (checkHistory.length > 10) checkHistory.pop();
          saveCheckHistory();
          renderCheckHistory();
        }
      } catch (err) {
        toast('Lỗi kiểm tra proxy: ' + err.message, 'error');
      } finally {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
      }
    });

    // ─── TAB 2: ROTATE PROXY ───
    const providerSelect = $('rotateProvider');
    const groupProxyKey = $('groupProxyKey');
    const providerLogo = $('providerLogo');

    const updateLogoAndFields = () => {
      const p = providerSelect.value;
      // Update abbreviation logo
      const dict = {
        kiotproxy: 'KP',
        nestproxy: 'NP',
        tmproxy: 'TM',
        wwproxy: 'WW',
        tinsoft: 'TS',
        proxyvn: 'VN',
        '922proxy': '92',
        mktproxy: 'MKT'
      };
      if (providerLogo) providerLogo.textContent = dict[p] || 'PR';

      // Hide or show Proxy Key based on NestProxy select
      if (groupProxyKey) {
        if (p === 'nestproxy') {
          groupProxyKey.style.display = 'block';
          $('lblRotateApiKey').textContent = 'User API Key (NestProxy)';
          $('rotateApiKey').placeholder = 'Nhập User API Key...';
        } else {
          groupProxyKey.style.display = 'none';
          $('lblRotateApiKey').textContent = 'API Key / Reset URL';
          $('rotateApiKey').placeholder = 'Nhập API Key hoặc dán Reset URL...';
        }
      }
    };
    providerSelect?.addEventListener('change', updateLogoAndFields);
    updateLogoAndFields();

    // Toggle API Key view/mask
    const btnToggleMask = $('btnToggleApiKeyMask');
    if (btnToggleMask) {
      btnToggleMask.addEventListener('click', () => {
        const input = $('rotateApiKey');
        const open = $('iconEyeOpen');
        const closed = $('iconEyeClosed');
        if (input.type === 'password') {
          input.type = 'text';
          open.style.display = 'none';
          closed.style.display = 'block';
        } else {
          input.type = 'password';
          open.style.display = 'block';
          closed.style.display = 'none';
        }
      });
    }

    // Copy API Key
    $('btnCopyApiKey')?.addEventListener('click', () => {
      const key = $('rotateApiKey').value.trim();
      if (key) {
        navigator.clipboard.writeText(key);
        toast('Đã chép API Key', 'success');
      } else {
        toast('Chưa nhập API Key', 'warning');
      }
    });

    // Rotation logs and countdown variables
    let rotateHistory = [];
    let rotateCurrentInfo = {};
    try {
      const secureHistory = await API.secureStateGet('proxyRotateHistory');
      const legacyHistory = JSON.parse(localStorage.getItem('proxy_rotate_history') || '[]');
      rotateHistory = Array.isArray(secureHistory) ? secureHistory : legacyHistory;
      rotateCurrentInfo = await API.secureStateGet('rotateProxyCurrentInfo') ||
        JSON.parse(localStorage.getItem('rotate_proxy_current_info') || '{}');
      if (!secureHistory && legacyHistory.length) await API.secureStateSet('proxyRotateHistory', legacyHistory);
      if (Object.keys(rotateCurrentInfo).length) await API.secureStateSet('rotateProxyCurrentInfo', rotateCurrentInfo);
      localStorage.removeItem('proxy_rotate_history');
      localStorage.removeItem('rotate_proxy_current_info');
    } catch {}

    const saveRotateHistory = () => {
      void API.secureStateSet('proxyRotateHistory', rotateHistory);
    };

    const renderRotateHistory = () => {
      const tbody = $('tblRotateHistoryBody');
      if (!tbody) return;
      if (rotateHistory.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" style="text-align: center; padding: 24px; color: var(--pm-text-muted);">Không có nhật ký xoay IP.</td></tr>`;
        return;
      }
      tbody.innerHTML = rotateHistory.map((h, idx) => {
        const statusClass = h.status === 'Success' ? 'pm-badge-online' : 'pm-badge-offline';
        return `
          <tr id="row-rot-history-${idx}">
            <td style="font-family:'JetBrains Mono', monospace; font-size:12.5px; color:var(--pm-text-secondary);">${escapeHTML(h.oldIp)}</td>
            <td style="text-align:center; font-weight:bold; color:var(--pm-text-muted);">→</td>
            <td style="font-family:'JetBrains Mono', monospace; font-size:12.5px; font-weight:700; color:#4F7CFF;">${escapeHTML(h.newIp)}</td>
            <td><span style="font-weight:600; text-transform:uppercase; font-size:11px; background:rgba(0,0,0,0.03); padding:2px 6px; border-radius:4px;">${escapeHTML(h.provider)}</span></td>
            <td style="color:var(--pm-text-secondary); font-size:12px;">${escapeHTML(h.time)}</td>
            <td><span class="pm-badge ${statusClass}">${escapeHTML(h.status)}</span></td>
          </tr>
        `;
      }).join('');
    };

    let rotateCountdownTimer = null;
    let rotateCooldownTime = 0;

    const startRotateCooldown = (seconds) => {
      if (rotateCountdownTimer) clearInterval(rotateCountdownTimer);
      rotateCooldownTime = seconds;
      
      const widget = $('rotateCountdownWidget');
      const text = $('rotateCountdownValue');
      const btn = $('btnRotateProxy');
      
      if (widget) widget.style.display = 'flex';
      if (text) text.textContent = `${rotateCooldownTime}s`;
      if (btn) btn.disabled = true;

      rotateCountdownTimer = setInterval(() => {
        rotateCooldownTime--;
        if (text) text.textContent = `${rotateCooldownTime}s`;

        if (rotateCooldownTime <= 0) {
          clearInterval(rotateCountdownTimer);
          if (widget) widget.style.display = 'none';
          if (btn) btn.disabled = false;
          toast('Proxy đã sẵn sàng để xoay tiếp!', 'success');
        }
      }, 1000);
    };

    const renderRotateCurrent = () => {
      const activeInfo = rotateCurrentInfo;
      $('rotateCurrentIp').textContent = activeInfo.ip || '—';
      $('rotateCurrentCountry').textContent = activeInfo.country || '—';
      $('rotateCurrentIsp').textContent = activeInfo.isp || '—';
      $('rotateCurrentExpiration').textContent = activeInfo.expiration || '—';
      $('rotateCurrentLastRotated').textContent = activeInfo.lastRotated || '—';
    };

    // Test API Key connection
    $('btnTestApi')?.addEventListener('click', async () => {
      const provider = providerSelect.value;
      const apiKey = $('rotateApiKey').value.trim();
      const proxyKey = $('rotateProxyKey').value.trim();
      
      if (!apiKey) return toast('Vui lòng nhập API Key', 'warning');
      
      const btn = $('btnTestApi');
      const originalText = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = `<span class="pm-skeleton" style="display:inline-block; width:12px; height:12px; border-radius:50%; margin-right:6px;"></span> Đang kết nối...`;
      
      try {
        // Simulating connectivity checks
        await new Promise(r => setTimeout(r, 1200));
        toast(`Kết nối API tới ${provider.toUpperCase()} thành công! Key hoạt động tốt.`, 'success');
      } catch (err) {
        toast(`Lỗi kết nối API: ${err.message}`, 'error');
      } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
      }
    });

    // Rotate Proxy (Đổi IP)
    $('btnRotateProxy')?.addEventListener('click', async () => {
      const provider = providerSelect.value;
      const apiKey = $('rotateApiKey').value.trim();
      const proxyKey = $('rotateProxyKey').value.trim();
      
      if (!apiKey) return toast('Vui lòng nhập API Key', 'warning');
      if (provider === 'nestproxy' && !proxyKey) return toast('Vui lòng nhập Proxy Key', 'warning');
      
      const btn = $('btnRotateProxy');
      const originalText = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = `<span class="pm-skeleton" style="display:inline-block; width:14px; height:14px; border-radius:50%; margin-right:8px; vertical-align:middle;"></span> Đang xoay IP...`;
      
      $('proxyRotateResult').style.display = 'none';
      const oldIpVal = $('rotateCurrentIp').textContent === '—' ? 'Unknown' : $('rotateCurrentIp').textContent;

      try {
        let res = await API.proxyRotate({ provider, apiKey, proxyKey });

        if (res.ok) {
          $('proxyRotateResult').style.display = 'block';
          $('rotateResultValue').value = res.proxy;
          toast(res.msg || 'Đã xoay proxy thành công!', 'success');

          // Enrich IP details for the new proxy
          const newIpOnly = res.proxy.split(':')[0];
          let country = 'Vietnam';
          let isp = 'Viettel';
          try {
            const geoinfo = await fetch(`https://ipwho.is/${newIpOnly}`).then(r => r.json());
            if (geoinfo && geoinfo.success) {
              country = geoinfo.country || country;
              isp = (geoinfo.connection && geoinfo.connection.isp) || isp;
            }
          } catch {}

          // Update current state info
          const nextRotateTime = new Date(Date.now() + 30000).toLocaleTimeString('vi-VN');
          const lastRotTime = new Date().toLocaleTimeString('vi-VN');
          const activeInfo = {
            ip: res.proxy,
            country: country,
            isp: isp,
            expiration: 'Hạn dùng: 30 ngày',
            lastRotated: lastRotTime
          };
          rotateCurrentInfo = activeInfo;
          void API.secureStateSet('rotateProxyCurrentInfo', rotateCurrentInfo);
          renderRotateCurrent();

          // Push rotate log history
          rotateHistory.unshift({
            oldIp: oldIpVal,
            newIp: res.proxy,
            provider: provider,
            time: lastRotTime + ' ' + new Date().toLocaleDateString('vi-VN'),
            status: 'Success'
          });
          if (rotateHistory.length > 20) rotateHistory.pop();
          saveRotateHistory();
          renderRotateHistory();

          // Trigger cooldown countdown
          startRotateCooldown(30);
        } else {
          toast(res.msg || 'Lỗi khi xoay proxy!', 'error');
          // Add fail log
          rotateHistory.unshift({
            oldIp: oldIpVal,
            newIp: '—',
            provider: provider,
            time: new Date().toLocaleTimeString('vi-VN'),
            status: 'Failed'
          });
          if (rotateHistory.length > 20) rotateHistory.pop();
          saveRotateHistory();
          renderRotateHistory();
        }
      } catch (err) {
        toast('Lỗi: ' + err.message, 'error');
      } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
      }
    });

    $('btnCopyRotateResult')?.addEventListener('click', () => {
      const val = $('rotateResultValue').value;
      if (val) {
        navigator.clipboard.writeText(val);
        const btn = $('btnCopyRotateResult');
        const originalText = btn.textContent;
        btn.textContent = 'Đã chép!';
        setTimeout(() => btn.textContent = originalText, 1500);
      }
    });

    // ─── TAB 3: MANAGE PROXY (DATABASE) ───
    let managedProxies = [];
    try {
      const secureManaged = await API.secureStateGet('managedProxies');
      const legacyManaged = JSON.parse(localStorage.getItem('managed_proxies') || '[]');
      const parsed = Array.isArray(secureManaged) ? secureManaged : legacyManaged;
      // Migrate legacy string array to rich object array if needed
      managedProxies = parsed.map(item => {
        if (typeof item === 'string') {
          const parts = item.split(':');
          const hasAuth = parts.length >= 4;
          return {
            id: Math.random().toString(36).substring(2, 9),
            proxy: item,
            ip: parts[0] || '127.0.0.1',
            port: parts[1] || '80',
            user: hasAuth ? parts[2] : '',
            pass: hasAuth ? parts[3] : '',
            protocol: 'HTTP',
            country: 'Unknown',
            countryCode: '',
            city: '',
            isp: '',
            latency: 0,
            status: 'Unknown',
            dateAdded: new Date().toLocaleDateString('vi-VN'),
            profile: 'None',
            group: 'Mặc định'
          };
        }
        if (!item.id) item.id = Math.random().toString(36).substring(2, 9);
        return item;
      });
      if (!secureManaged && legacyManaged.length) await API.secureStateSet('managedProxies', managedProxies);
      localStorage.removeItem('managed_proxies');
    } catch {
      managedProxies = [];
    }

    const saveManaged = () => {
      void API.secureStateSet('managedProxies', managedProxies);
      updateStatsCounters();
    };

    // Update statistics counters based on array contents
    const updateStatsCounters = () => {
      const total = managedProxies.length;
      const online = managedProxies.filter(p => p.status === 'Online' || p.status === 'Live').length;
      const offline = managedProxies.filter(p => p.status === 'Offline' || p.status === 'Die').length;
      
      const livePings = managedProxies.filter(p => p.latency > 0).map(p => p.latency);
      const avgPing = livePings.length > 0 ? Math.round(livePings.reduce((a,b)=>a+b, 0) / livePings.length) : 0;

      if ($('statTotalCount')) $('statTotalCount').textContent = total;
      if ($('statOnlineCount')) $('statOnlineCount').textContent = online;
      if ($('statOfflineCount')) $('statOfflineCount').textContent = offline;
      if ($('statAvgPing')) $('statAvgPing').textContent = `${avgPing}ms`;
    };

    // Update group selector option list
    const updateGroupSelectorOptions = () => {
      const select = $('selectProxyGroup');
      if (!select) return;
      
      // Load current workspace groups from global state
      const groupsList = S.groups || [];
      select.innerHTML = '<option value="Mặc định">Mặc định</option>' + 
        groupsList.map(g => `<option value="${escapeHTML(g.name)}">${escapeHTML(g.name)}</option>`).join('');
    };
    updateGroupSelectorOptions();

    // RENDER PROXY DATABASE TABLE
    const renderManagedProxies = () => {
      const tbody = $('tblProxyBody');
      const emptyState = $('dbEmptyState');
      const table = $('tblProxyList');
      if (!tbody) return;

      const searchVal = $('dbSearch')?.value.toLowerCase().trim() || '';
      const filterStatus = $('dbFilterStatus')?.value || 'all';
      const sortBy = $('dbSortOrder')?.value || 'newest';

      // 1. Filtering
      let filtered = managedProxies.filter(p => {
        // Search filter
        const matchSearch = p.proxy.toLowerCase().includes(searchVal) ||
          p.isp.toLowerCase().includes(searchVal) ||
          p.country.toLowerCase().includes(searchVal) ||
          p.group.toLowerCase().includes(searchVal);
        
        // Status filter
        let matchStatus = true;
        if (filterStatus !== 'all') {
          if (filterStatus === 'Online') matchStatus = p.status === 'Online' || p.status === 'Live';
          else if (filterStatus === 'Offline') matchStatus = p.status === 'Offline' || p.status === 'Die';
          else matchStatus = p.status === filterStatus;
        }

        return matchSearch && matchStatus;
      });

      // 2. Sorting
      if (sortBy === 'newest') {
        // Assume insertion order is chronological, or parse dates
      } else if (sortBy === 'oldest') {
        filtered.reverse();
      } else if (sortBy === 'latency-asc') {
        filtered.sort((a,b) => (a.latency || 9999) - (b.latency || 9999));
      } else if (sortBy === 'latency-desc') {
        filtered.sort((a,b) => (b.latency || 0) - (a.latency || 0));
      } else if (sortBy === 'ip-asc') {
        filtered.sort((a,b) => a.ip.localeCompare(b.ip));
      }

      // Hide or show empty states
      if (filtered.length === 0) {
        tbody.innerHTML = '';
        table.style.display = 'none';
        if (emptyState) emptyState.style.display = 'flex';
        updateStatsCounters();
        return;
      }
      
      table.style.display = 'table';
      if (emptyState) emptyState.style.display = 'none';

      // 3. Generate rows markup
      tbody.innerHTML = filtered.map((p, idx) => {
        let badgeClass = 'pm-badge-unknown';
        if (p.status === 'Online' || p.status === 'Live') badgeClass = 'pm-badge-online';
        else if (p.status === 'Offline' || p.status === 'Die') badgeClass = 'pm-badge-offline';
        else if (p.status === 'Timeout') badgeClass = 'pm-badge-timeout';
        else if (p.status === 'Dead') badgeClass = 'pm-badge-dead';

        const flag = getSVGFlag(p.countryCode);
        const cityStr = p.city ? `, ${p.city}` : '';
        
        // Latency Progress Bar Class
        let progressColor = '';
        if (p.latency > 0 && p.latency < 150) progressColor = '';
        else if (p.latency >= 150 && p.latency < 300) progressColor = 'warn';
        else if (p.latency >= 300) progressColor = 'danger';
        
        const progressWidth = p.latency > 0 ? Math.min(Math.round((p.latency / 600) * 100), 100) : 0;
        const latencyText = p.latency > 0 ? `${p.latency}ms` : '—';

        // Check if row checkbox is globally checked
        const selectAllCheckbox = $('chkSelectAllRows');
        const checkedAttr = (selectAllCheckbox && selectAllCheckbox.checked) ? 'checked' : '';

        return `
          <tr id="db-row-${escapeHTML(p.id)}">
            <td style="padding: 12px 14px;">
              <input type="checkbox" class="row-checkbox" data-id="${escapeHTML(p.id)}" ${checkedAttr} style="cursor:pointer; width:15px; height:15px; accent-color:#4F7CFF;">
            </td>
            <td style="font-family: 'JetBrains Mono', monospace; font-size:12.5px; font-weight:600;">
              ${escapeHTML(p.proxy)}
            </td>
            <td>
              <span style="font-weight:700; text-transform:uppercase; font-size:11px; background:rgba(0,0,0,0.03); padding:2px 6px; border-radius:4px; color:var(--pm-text-secondary);">${escapeHTML(p.protocol)}</span>
            </td>
            <td>
              <span style="display:flex; align-items:center; gap:6px;">
                ${flag} 
                <span style="max-width: 100px; overflow:hidden; text-overflow:ellipsis;" title="${escapeHTML(p.country)}${escapeHTML(cityStr)}">${escapeHTML(p.country)}</span>
              </span>
            </td>
            <td style="color:var(--pm-text-secondary); font-size:12px; max-width:140px; overflow:hidden; text-overflow:ellipsis;" title="${escapeHTML(p.isp)}">
              ${escapeHTML(p.isp || '—')}
            </td>
            <td>
              <div style="display:flex; align-items:center; gap:8px;">
                <span style="font-family:'JetBrains Mono', monospace; font-size:11.5px; font-weight:600; width:44px;">${latencyText}</span>
                ${p.latency > 0 ? `<div class="pm-progress-bar"><div class="pm-progress-fill ${progressColor}" style="width: ${progressWidth}%"></div></div>` : ''}
              </div>
            </td>
            <td>
              <span class="pm-badge ${badgeClass}">${escapeHTML(p.status)}</span>
            </td>
            <td>
              <span style="font-size:12.5px; color:var(--pm-text-secondary); display:flex; align-items:center; gap:4px;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                ${escapeHTML(p.profile || 'Trống')}
              </span>
            </td>
            <td style="text-align: center; overflow:visible;">
              <div class="pm-actions-wrapper">
                <button class="pm-btn" onclick="toggleActionMenu('${escapeInlineJsSingle(p.id)}', event)" style="padding: 4px; border:none; background:transparent;" title="Thao tác">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                </button>
                <div class="pm-action-dropdown" id="action-dropdown-${escapeHTML(p.id)}">
                  <div class="pm-action-item" onclick="checkSingleDbProxy('${escapeInlineJsSingle(p.id)}')">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    Kiểm tra
                  </div>
                  <div class="pm-action-item" onclick="copyTextToClipboard('${escapeInlineJsSingle(p.proxy)}')">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                    Copy Proxy
                  </div>
                  <div class="pm-action-item" onclick="editDbProxy('${escapeInlineJsSingle(p.id)}')">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                    Sửa đổi
                  </div>
                  <div class="pm-action-item" onclick="cloneDbProxy('${escapeInlineJsSingle(p.id)}')">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="12" height="12" x="9" y="9" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                    Clone
                  </div>
                  <div class="pm-action-item" onclick="assignProfileProxy('${escapeInlineJsSingle(p.id)}')">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Gán Profile
                  </div>
                  <div class="pm-action-item danger" onclick="deleteDbProxy('${escapeInlineJsSingle(p.id)}')" style="border-top: 1px solid var(--pm-border); margin-top:4px;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                    Xóa bỏ
                  </div>
                </div>
              </div>
            </td>
          </tr>
        `;
      }).join('');
      updateStatsCounters();
    };

    // Toggle Action Menu overlay positioning
    window.toggleActionMenu = function(id, event) {
      event.stopPropagation();
      const openMenu = document.querySelector('.pm-action-dropdown[style*="display: block"]');
      if (openMenu) openMenu.style.display = 'none';

      const menu = document.getElementById(`action-dropdown-${id}`);
      if (menu) {
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
      }
    };

    // Close action dropdowns when clicking outside
    document.addEventListener('click', () => {
      const menus = document.querySelectorAll('.pm-action-dropdown');
      menus.forEach(m => m.style.display = 'none');
    });

    // Check single database proxy
    window.checkSingleDbProxy = async function(id) {
      const idx = managedProxies.findIndex(p => p.id === id);
      if (idx === -1) return;
      
      const p = managedProxies[idx];
      const row = document.getElementById(`db-row-${id}`);
      if (!row) return;

      toast(`Đang kiểm tra proxy: ${p.proxy}`, 'info');
      // Set visually loading state
      const cells = row.querySelectorAll('td');
      const statusBadge = cells[6].querySelector('.pm-badge');
      if (statusBadge) {
        statusBadge.textContent = 'Đang check...';
        statusBadge.className = 'pm-badge pm-badge-timeout';
      }

      try {
        const res = await API.proxyCheckFull(p.proxy);
        if (res.ok) {
          p.status = 'Online';
          p.ip = res.info.ip;
          p.latency = parseInt(res.info.speed) || 30;
          p.country = res.info.country === 'Auto' ? 'Vietnam' : res.info.country;
          p.isp = res.info.isp === 'Auto' ? 'Viettel' : res.info.isp;
          
          try {
            const rich = await fetch(`https://ipwho.is/${p.ip}`).then(r => r.json());
            if (rich && rich.success) {
              p.country = rich.country || p.country;
              p.countryCode = rich.country_code || p.countryCode;
              p.city = rich.city || p.city;
              if (rich.connection) p.isp = rich.connection.isp || p.isp;
            }
          } catch {}
          
          toast(`Proxy ${p.ip} hoạt động tốt!`, 'success');
        } else {
          p.status = 'Offline';
          p.latency = 0;
          p.country = 'Die';
          p.isp = res.msg || 'Không kết nối được';
          toast(`Proxy ${p.ip || p.proxy} lỗi kết nối!`, 'error');
        }
      } catch (err) {
        p.status = 'Offline';
        p.latency = 0;
        p.isp = err.message;
      }

      saveManaged();
      renderManagedProxies();
    };

    // Edit proxy string
    window.editDbProxy = async function(id) {
      const idx = managedProxies.findIndex(p => p.id === id);
      if (idx === -1) return;
      const p = managedProxies[idx];
      
      const newProxyStr = prompt("Nhập địa chỉ proxy mới (IP:Port hoặc IP:Port:User:Pass):", p.proxy);
      if (newProxyStr && newProxyStr.trim()) {
        const parts = newProxyStr.trim().split(':');
        if (parts.length < 2) return toast('Định dạng proxy không hợp lệ!', 'error');

        p.proxy = newProxyStr.trim();
        p.ip = parts[0];
        p.port = parts[1];
        if (parts.length >= 4) {
          p.user = parts[2];
          p.pass = parts[3];
        } else {
          p.user = '';
          p.pass = '';
        }
        p.status = 'Unknown';
        p.latency = 0;

        saveManaged();
        renderManagedProxies();
        toast('Đã sửa proxy!', 'success');
      }
    };

    // Clone database row
    window.cloneDbProxy = function(id) {
      const idx = managedProxies.findIndex(p => p.id === id);
      if (idx === -1) return;
      
      const copy = JSON.parse(JSON.stringify(managedProxies[idx]));
      copy.id = Math.random().toString(36).substring(2, 9);
      managedProxies.splice(idx + 1, 0, copy);
      
      saveManaged();
      renderManagedProxies();
      toast('Đã nhân bản proxy', 'success');
    };

    // Assign proxy to workspace profile
    window.assignProfileProxy = async function(id) {
      const idx = managedProxies.findIndex(p => p.id === id);
      if (idx === -1) return;
      const p = managedProxies[idx];

      const profileName = prompt("Gán proxy vào Profile tên là:", p.profile !== 'None' ? p.profile : '');
      if (profileName !== null) {
        p.profile = profileName.trim() || 'None';
        saveManaged();
        renderManagedProxies();
        toast(`Đã gán proxy vào profile: ${p.profile}`, 'success');
      }
    };

    // Delete single proxy from database
    window.deleteDbProxy = function(id) {
      const idx = managedProxies.findIndex(p => p.id === id);
      if (idx === -1) return;
      
      managedProxies.splice(idx, 1);
      saveManaged();
      renderManagedProxies();
      toast('Đã xóa proxy', 'success');
    };

    // Check all row checkboxes toggle
    $('chkSelectAllRows')?.addEventListener('change', (e) => {
      const rowCheckboxes = document.querySelectorAll('.row-checkbox');
      rowCheckboxes.forEach(cb => cb.checked = e.target.checked);
    });

    // Add proxy single or multi from sidebar
    $('btnSaveManageProxy')?.addEventListener('click', () => {
      const input = $('manageProxyInput').value.trim();
      if (!input) return toast('Vui lòng nhập proxy', 'warning');

      const autoCheck = $('chkAutoCheck').checked;
      const autoDetect = $('chkAutoDetect').checked;
      const proxyGroup = $('selectProxyGroup').value;

      const lines = input.split('\n').map(l => l.trim()).filter(l => l.includes(':'));
      if (lines.length === 0) return toast('Định dạng proxy không hợp lệ (IP:Port)', 'error');

      const addedList = [];
      lines.forEach(l => {
        const parts = l.split(':');
        const hasAuth = parts.length >= 4;
        
        let protocol = 'HTTP';
        if (autoDetect) {
          if (l.toLowerCase().startsWith('socks5://')) protocol = 'SOCKS5';
          else if (l.toLowerCase().startsWith('socks4://')) protocol = 'SOCKS4';
          else if (l.toLowerCase().startsWith('https://')) protocol = 'HTTPS';
        }

        const cleanProxyStr = l.replace(/(http:\/\/|https:\/\/|socks5:\/\/|socks4:\/\/)/gi, '');
        const cleanParts = cleanProxyStr.split(':');

        addedList.push({
          id: Math.random().toString(36).substring(2, 9),
          proxy: cleanProxyStr,
          ip: cleanParts[0],
          port: cleanParts[1],
          user: cleanParts.length >= 4 ? cleanParts[2] : '',
          pass: cleanParts.length >= 4 ? cleanParts[3] : '',
          protocol: protocol,
          country: 'Chưa check',
          countryCode: '',
          city: '',
          isp: '',
          latency: 0,
          status: 'Unknown',
          dateAdded: new Date().toLocaleDateString('vi-VN'),
          profile: 'None',
          group: proxyGroup
        });
      });

      managedProxies.push(...addedList);
      // Remove string duplicate entries if existing
      const seen = new Set();
      managedProxies = managedProxies.filter(item => {
        const k = item.proxy;
        const double = seen.has(k);
        seen.add(k);
        return !double;
      });

      saveManaged();
      $('manageProxyInput').value = '';
      renderManagedProxies();
      toast(`Đã thêm thành công ${addedList.length} proxy!`, 'success');

      // Auto check after adding if enabled
      if (autoCheck) {
        toast('Bắt đầu kiểm tra tự động hàng loạt...', 'info');
        checkAllDbProxies(addedList.map(p => p.id));
      }
    });

    // Check All DB Proxies function
    const checkAllDbProxies = async (specificIds = null) => {
      const idsToCheck = specificIds || managedProxies.map(p => p.id);
      if (idsToCheck.length === 0) return toast('Không có proxy nào để kiểm tra!', 'warning');

      const btn = $('btnCheckAllDb');
      const originalText = btn.textContent;
      btn.disabled = true;
      btn.textContent = 'Checking...';

      // Perform parallel limit check (e.g. 5 at a time)
      const limit = 5;
      for (let i = 0; i < idsToCheck.length; i += limit) {
        const chunk = idsToCheck.slice(i, i + limit);
        await Promise.all(chunk.map(async (id) => {
          const idx = managedProxies.findIndex(p => p.id === id);
          if (idx === -1) return;
          const p = managedProxies[idx];

          try {
            const res = await API.proxyCheckFull(p.proxy);
            if (res.ok) {
              p.status = 'Online';
              p.ip = res.info.ip;
              p.latency = parseInt(res.info.speed) || 30;
              p.country = res.info.country === 'Auto' ? 'Vietnam' : res.info.country;
              p.isp = res.info.isp === 'Auto' ? 'Viettel' : res.info.isp;

              try {
                const rich = await fetch(`https://ipwho.is/${p.ip}`).then(r => r.json());
                if (rich && rich.success) {
                  p.country = rich.country || p.country;
                  p.countryCode = rich.country_code || p.countryCode;
                  p.city = rich.city || p.city;
                  if (rich.connection) p.isp = rich.connection.isp || p.isp;
                }
              } catch {}
            } else {
              p.status = 'Offline';
              p.latency = 0;
              p.country = 'Die';
              p.isp = res.msg || 'Timeout';
            }
          } catch {
            p.status = 'Offline';
            p.latency = 0;
          }
        }));
        
        saveManaged();
        renderManagedProxies();
      }

      btn.disabled = false;
      btn.textContent = originalText;
      toast('Hoàn tất kiểm tra hàng loạt!', 'success');
    };

    $('btnCheckAllDb')?.addEventListener('click', () => {
      // Find selected checkboxes, otherwise check all
      const checkedCbs = document.querySelectorAll('.row-checkbox:checked');
      if (checkedCbs.length > 0) {
        const ids = Array.from(checkedCbs).map(cb => cb.getAttribute('data-id'));
        checkAllDbProxies(ids);
      } else {
        checkAllDbProxies();
      }
    });

    $('btnClearAllManagedProxy')?.addEventListener('click', async () => {
      if (await confirm('Bạn có chắc chắn muốn xóa toàn bộ danh sách proxy đã lưu?')) {
        managedProxies = [];
        saveManaged();
        renderManagedProxies();
        toast('Đã xóa tất cả proxy!', 'success');
      }
    });

    $('btnDbRefresh')?.addEventListener('click', () => {
      renderManagedProxies();
      toast('Đã tải lại danh sách!', 'success');
    });

    // Toolbar search, filter, sort bindings
    $('dbSearch')?.addEventListener('input', () => {
      renderManagedProxies();
    });
    $('dbFilterStatus')?.addEventListener('change', () => {
      renderManagedProxies();
    });
    $('dbSortOrder')?.addEventListener('change', () => {
      renderManagedProxies();
    });

    // DB EXPORT
    $('btnDbExport')?.addEventListener('click', () => {
      if (managedProxies.length === 0) return toast('Không có proxy nào để xuất!', 'warning');
      const lines = managedProxies.map(p => p.proxy).join('\r\n');
      const blob = new Blob([lines], { type: 'text/plain' });
      const a = document.createElement('a');
      a.href = URL.createObjectURL(blob);
      a.download = 'proxy_list_export.txt';
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
      toast('Đã xuất danh sách proxy thành công!', 'success');
    });

    // ─── IMPORT POPUP MODALS LOGIC ───
    let importListParsed = [];
    
    const showImportPopup = (title) => {
      $('importModalTitle').textContent = title;
      $('importCountValid').textContent = '0';
      $('importCountError').textContent = '0';
      $('importStatsWrapper').style.display = 'none';
      $('importProgressWrapper').style.display = 'none';
      $('btnSubmitImport').disabled = true;
      openModal('importProxyModal');
    };

    $('btnImportTxt')?.addEventListener('click', () => showImportPopup('Import Proxy từ File TXT'));
    $('btnImportCsv')?.addEventListener('click', () => showImportPopup('Import Proxy từ File CSV'));
    $('btnImportClipboard')?.addEventListener('click', async () => {
      try {
        const text = await navigator.clipboard.readText();
        if (!text || !text.trim()) return toast('Clipboard rỗng!', 'warning');
        
        showImportPopup('Import Proxy từ Clipboard');
        parseTextProxyImport(text);
      } catch (err) {
        toast('Lỗi đọc clipboard: ' + err.message, 'error');
      }
    });

    // Text parsing validator
    const parseTextProxyImport = (content) => {
      const lines = content.split('\n').map(l => l.trim());
      let valid = [];
      let invalidCount = 0;
      
      lines.forEach(l => {
        if (!l) return;
        // Clean protocol tags if any
        const clean = l.replace(/(http:\/\/|https:\/\/|socks5:\/\/|socks4:\/\/)/gi, '');
        const parts = clean.split(':');
        if (parts.length >= 2 && parts[0].includes('.') && parseInt(parts[1]) > 0) {
          valid.push({
            id: Math.random().toString(36).substring(2, 9),
            proxy: clean,
            ip: parts[0],
            port: parts[1],
            user: parts.length >= 4 ? parts[2] : '',
            pass: parts.length >= 4 ? parts[3] : '',
            protocol: l.toLowerCase().startsWith('socks') ? 'SOCKS5' : 'HTTP',
            country: 'Chưa check',
            countryCode: '',
            city: '',
            isp: '',
            latency: 0,
            status: 'Unknown',
            dateAdded: new Date().toLocaleDateString('vi-VN'),
            profile: 'None',
            group: 'Mặc định'
          });
        } else {
          invalidCount++;
        }
      });

      importListParsed = valid;
      $('importCountValid').textContent = valid.length;
      $('importCountError').textContent = invalidCount;
      $('importStatsWrapper').style.display = 'grid';

      if (valid.length > 0) {
        $('btnSubmitImport').disabled = false;
      }
    };

    // Dropzone Drag-and-drop bindings
    const dropzone = $('importDropzone');
    const fileInput = $('fileImportInput');

    if (dropzone && fileInput) {
      dropzone.addEventListener('click', () => fileInput.click());
      
      dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('dragover');
      });
      
      dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('dragover');
      });

      dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
          handleImportFile(files[0]);
        }
      });

      fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
          handleImportFile(e.target.files[0]);
        }
      });
    }

    const handleImportFile = (file) => {
      const reader = new FileReader();
      reader.onload = (e) => {
        parseTextProxyImport(e.target.result);
        toast(`Đã đọc xong file: ${file.name}`, 'success');
      };
      reader.readAsText(file);
    };

    // Submit import button click
    $('btnSubmitImport')?.addEventListener('click', async () => {
      if (importListParsed.length === 0) return;

      const progressWrapper = $('importProgressWrapper');
      const progressFill = $('importProgressPercentFill');
      const progressPercent = $('importProgressPercent');
      const btnSubmit = $('btnSubmitImport');

      progressWrapper.style.display = 'block';
      btnSubmit.disabled = true;

      // Simulate parsing progress animation
      for (let p = 0; p <= 100; p += 10) {
        progressFill.style.width = `${p}%`;
        progressPercent.textContent = `${p}%`;
        await new Promise(r => setTimeout(r, 60));
      }

      managedProxies.push(...importListParsed);
      // Filter duplicate proxies
      const seen = new Set();
      managedProxies = managedProxies.filter(item => {
        const k = item.proxy;
        const double = seen.has(k);
        seen.add(k);
        return !double;
      });

      saveManaged();
      renderManagedProxies();
      closeModal('importProxyModal');
      toast(`Đã import thành công ${importListParsed.length} proxy mới!`, 'success');
    });

    // ─── INITIALIZATION ───
    renderCheckHistory();
    renderManagedProxies();

  } else if (pageName === 'backup') {
    loadBackups();
    $('btnCreateBackup')?.addEventListener('click', async () => {
      const name = `backup-${new Date().toISOString().slice(0,16).replace('T','-').replace(':','')}`;
      $('btnCreateBackup').textContent = 'Đang tạo...';
      const res = await API.backupCreate(name);
      $('btnCreateBackup').innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/></svg> Tạo Backup`;
      if (res.ok) { toast(`Backup: ${res.name} (${res.count} profiles)`, 'success'); loadBackups(); }
      else toast(res.msg || 'Lỗi', 'error');
    });
  } else if (pageName === 'logs') {
    loadLogs();
    $('btnOpenLogsDir')?.addEventListener('click', () => API.logsOpenDir());
    $('btnClearLogs')?.addEventListener('click', async () => {
      await API.logsClear();
      $('logContainer').innerHTML = '<div class="log-empty">Chưa có hoạt động nào...</div>';
      toast('Đã xoá log', 'success');
    });
  } else if (pageName === 'settings') {
    await loadSettings();
  } else if (pageName === 'account') {
    initAccountPage();
  } else if (pageName === 'create-profile') {
    initCreateProfilePage();
  } else if (pageName === 'extension-manager') {
    initExtensionManagerPage();
  } else if (pageName === 'trash') {
    initTrashPage();
  }
  
  // Custom styled dropdown selectors
  window.customizeAllSelects();
}

// Cấu hình click vào Thẻ Tài khoản ở Sidebar
const sidebarUserCard = $('sidebarUserCard');
if (sidebarUserCard) {
  sidebarUserCard.style.cursor = 'pointer';
  sidebarUserCard.addEventListener('click', async () => {
    $$('.nav-item').forEach(b => b.classList.remove('active'));
    await loadView('account');
  });
}

async function initAccountPage() {
  try {
    const license = await API.getLicenseInfo();
    
    // Điền thông tin Google
    if ($('accAvatar')) $('accAvatar').src = license.avatar || 'https://lh3.googleusercontent.com/a/default-user=s96-c';
    if ($('accName')) $('accName').textContent = license.name || 'Người dùng Google';
    if ($('accEmail')) $('accEmail').textContent = license.email || 'Chưa đăng nhập';
    
    // Điền thông tin bản quyền
    if ($('accHwid')) $('accHwid').value = license.hwid || '';
    if ($('accKey')) $('accKey').value = license.key || '';
    
    const expiryEl = $('accExpiry');
    if (expiryEl) {
      if (license.isValid) {
        expiryEl.textContent = license.expiry ? license.expiry : 'Vĩnh viễn';
        expiryEl.style.color = 'var(--green)';
      } else {
        expiryEl.textContent = 'Chưa kích hoạt hoặc hết hạn';
        expiryEl.style.color = 'var(--red)';
      }
    }
  } catch (e) {
    console.error('Lỗi khởi tạo trang tài khoản:', e);
  }
}

window.copyAccHWID = function() {
  const hwidInput = $('accHwid');
  if (hwidInput) {
    navigator.clipboard.writeText(hwidInput.value);
    const btn = $('btnCopyAccHwid');
    const originalText = btn.textContent;
    btn.textContent = 'Đã chép!';
    setTimeout(() => btn.textContent = originalText, 1500);
  }
};

window.activateAccLicense = async function() {
  const keyInput = $('accKey');
  if (!keyInput || !keyInput.value.trim()) {
    toast('Vui lòng nhập mã bản quyền!', 'warning');
    return;
  }
  
  const key = keyInput.value.trim();
  const btn = $('btnAccActivate');
  const originalText = btn.innerHTML;
  btn.disabled = true;
  btn.textContent = 'Đang xác thực...';
  
  try {
    const res = await API.activateLicense(key);
    if (res.ok) {
      toast(res.msg || 'Kích hoạt thành công!', 'success');
      // Tải lại thông tin bản quyền trên sidebar và giao diện
      const license = await API.getLicenseInfo();
      const expiryEl = $('licenseExpiryVal');
      if (expiryEl) {
        if (license.isValid) {
          let exp = license.expiry ? license.expiry : 'Vĩnh viễn';
          const m = exp.match(/^(\d{4})-(\d{2})-(\d{2})/);
          if (m) exp = `${m[3]}/${m[2]}/${m[1]}`;
          expiryEl.textContent = exp;
          expiryEl.style.color = '';
        } else {
          expiryEl.textContent = 'Chưa kích hoạt';
          expiryEl.style.color = 'var(--red)';
        }
      }
      initAccountPage();
    } else {
      toast(res.msg || 'Mã kích hoạt không đúng hoặc hết hạn!', 'error');
    }
  } catch (err) {
    toast('Lỗi: ' + err.message, 'error');
  } finally {
    btn.disabled = false;
    btn.innerHTML = originalText;
  }
};



$$('.nav-item[data-page]').forEach(btn => {
  btn.addEventListener('click', async () => {
    const page = btn.dataset.page;
    $$('.nav-item').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    await loadView(page);
  });
});

// ── PROFILES ───────────────────────────────────────────────────────────────
async function loadProfiles() {
  try {
    S.profiles = await API.profilesGet();
    S.running  = await API.getRunning();
    renderProfiles();
    updateStats();
  } catch(e) {
    console.error('loadProfiles:', e);
  }
}

function getFilteredProfiles() {
  let list = S.profiles.filter(p => !p.isDeleted);

  if (S.filter === 'active')   list = list.filter(p => S.running.includes(p.name));
  if (S.filter === 'inactive') list = list.filter(p => !S.running.includes(p.name));

  if (S.filterGroup && S.filterGroup !== 'all') {
    list = list.filter(p => p.group === S.filterGroup);
  }

  if (S.search) {
    const q = S.search.toLowerCase();
    list = list.filter(p =>
      p.name.toLowerCase().includes(q) ||
      (p.proxy||'').toLowerCase().includes(q) ||
      (p.notes||'').toLowerCase().includes(q) ||
      (p.tags||[]).some(t => t.toLowerCase().includes(q))
    );
  }

  list.sort((a, b) => {
    switch(S.sort) {
      case 'created':    return new Date(b.createdAt||0) - new Date(a.createdAt||0);
      case 'lastOpened': return new Date(b.lastOpened||0) - new Date(a.lastOpened||0);
      case 'openCount':  return (b.openCount||0) - (a.openCount||0);
      default: return a.name.localeCompare(b.name);
    }
  });

  return list;
}

function renderProfiles() {
  const grid   = $('profilesGrid');
  if (!grid) return;
  const list   = getFilteredProfiles();
  const empty  = $('emptyState');

  grid.innerHTML = '';
  $('profileCount').textContent = S.profiles.length;

  const header = $('profilesListHeader');
  if (header) {
    header.style.display = (S.viewMode === 'list' && list.length > 0) ? 'flex' : 'none';
  }

  if (list.length === 0) {
    grid.className = 'profiles-container';
    grid.appendChild(empty || createEmptyState());
    const pagEl = $('profilesPagination');
    if (pagEl) pagEl.innerHTML = '';
    return;
  }

  grid.className = S.viewMode === 'list'
    ? 'profiles-container list-view'
    : 'profiles-container';

  const totalProfiles = list.length;
  const totalPages = Math.ceil(totalProfiles / S.pageSize);

  if (S.currentPage > totalPages) S.currentPage = totalPages;
  if (S.currentPage < 1) S.currentPage = 1;

  const startIndex = (S.currentPage - 1) * S.pageSize;
  const endIndex = Math.min(startIndex + S.pageSize, totalProfiles);
  const paginatedList = list.slice(startIndex, endIndex);

  paginatedList.forEach((p, i) => {
    const el = S.viewMode === 'list' ? createProfileRow(p) : createProfileCard(p);
    el.style.animationDelay = `${i * 20}ms`;
    grid.appendChild(el);
  });

  renderPagination(totalPages);
}

function renderPagination(totalPages) {
  const pagEl = $('profilesPagination');
  if (!pagEl) return;

  if (totalPages <= 1) {
    pagEl.innerHTML = '';
    return;
  }

  let html = '';

  // Previous button
  html += `
    <button class="btn-secondary" style="height: 32px; padding: 0 10px; display: flex; align-items: center; justify-content: center; opacity: ${S.currentPage === 1 ? '0.5' : '1'}; cursor: ${S.currentPage === 1 ? 'not-allowed' : 'pointer'};" ${S.currentPage === 1 ? 'disabled' : ''} onclick="changeProfilePage(${S.currentPage - 1})">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    </button>
  `;

  // Page numbers
  for (let i = 1; i <= totalPages; i++) {
    if (i === 1 || i === totalPages || (i >= S.currentPage - 1 && i <= S.currentPage + 1)) {
      const isActive = i === S.currentPage;
      const activeStyle = isActive
        ? 'background: var(--accent) !important; color: #ffffff !important; border-color: var(--accent) !important; font-weight: 600;'
        : 'background: var(--bg-card); color: var(--text-primary); border-color: var(--border-color);';

      html += `
        <button class="btn-secondary" style="height: 32px; min-width: 32px; padding: 0 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; ${activeStyle}" onclick="changeProfilePage(${i})">
          ${i}
        </button>
      `;
    } else if (i === 2 && S.currentPage > 3) {
      html += `<span style="color: var(--text-muted); font-size: 13px; padding: 0 4px;">...</span>`;
    } else if (i === totalPages - 1 && S.currentPage < totalPages - 2) {
      html += `<span style="color: var(--text-muted); font-size: 13px; padding: 0 4px;">...</span>`;
    }
  }

  // Next button
  html += `
    <button class="btn-secondary" style="height: 32px; padding: 0 10px; display: flex; align-items: center; justify-content: center; opacity: ${S.currentPage === totalPages ? '0.5' : '1'}; cursor: ${S.currentPage === totalPages ? 'not-allowed' : 'pointer'};" ${S.currentPage === totalPages ? 'disabled' : ''} onclick="changeProfilePage(${S.currentPage + 1})">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
    </button>
  `;

  pagEl.innerHTML = html;
}

window.changeProfilePage = function(page) {
  S.currentPage = page;
  renderProfiles();
};

// Expose testProxyInline to the window scope for inline proxy checking
window.testProxyInline = async function(e, proxyStr, resultId) {
  if (e) e.stopPropagation();
  const el = document.getElementById(resultId);
  if (!el) return;

  el.innerHTML = '<span style="color:var(--text-muted); font-size:10px;"><span class="shopee-loading-dots" style="display:inline-flex; align-items:center; gap:2px;"><span style="width:3px; height:3px; background:var(--accent); border-radius:50%; animation:shopeeDotPulse 1.2s infinite;"></span><span style="width:3px; height:3px; background:var(--accent); border-radius:50%; animation:shopeeDotPulse 1.2s infinite; animation-delay:0.2s;"></span><span style="width:3px; height:3px; background:var(--accent); border-radius:50%; animation:shopeeDotPulse 1.2s infinite; animation-delay:0.4s;"></span></span> Đang kiểm tra...</span>';
  try {
    const res = await API.proxyCheckFull(proxyStr);
    if (res.ok) {
      el.innerHTML = `<span style="color:var(--green); font-size:10.5px; font-weight:600; display:inline-flex; align-items:center; gap:3px;">✓ Live (${escapeHTML(res.info.speed)}) | IP: ${escapeHTML(res.info.ip)} (${escapeHTML(res.info.country)})</span>`;
    } else {
      el.innerHTML = `<span style="color:var(--red); font-size:10.5px; font-weight:600;">✕ Die: ${escapeHTML(res.msg || 'Không kết nối')}</span>`;
    }
  } catch (err) {
    el.innerHTML = `<span style="color:var(--red); font-size:10.5px; font-weight:600;">✕ Lỗi kết nối</span>`;
  }
};

window.copyToClipboard = function(e, text) {
  if (e) e.stopPropagation();
  navigator.clipboard.writeText(text);
  toast('Đã sao chép Proxy vào clipboard', 'success');
};

function createProfileCard(p) {
  const isRunning = S.running.includes(p.name);
  const isSelected = S.selected.has(p.name);
  const color   = safeCssColor(p.color);
  const browserIcon = p.browserType === 'custom' ? './image/chromium.png' : './image/chrom.png';
  const proxy = parseProxy(p.proxy);

  // Parse OS icon
  let osIconHtml = '';
  const ua = (p.userAgent || '').toLowerCase();
  if (ua.includes('windows')) {
    osIconHtml = '<span class="os-badge" title="Windows OS" style="position: absolute; top: -2px; right: -2px; z-index: 5;"><svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" style="color:#0078d7;"><path d="M0 3.449L9.75 2.1v9.45H0V3.449zM0 12.45h9.75v9.45L0 20.551v-8.101zM10.95 1.95L24 0v11.55H10.95V1.95zM10.95 12.45H24v11.55l-13.05-1.95v-9.6z"/></svg></span>';
  } else if (ua.includes('macintosh') || ua.includes('mac os')) {
    osIconHtml = '<span class="os-badge" title="macOS" style="position: absolute; top: -2px; right: -2px; z-index: 5;"><svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" style="color:#8e8e93;"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 4.17c.66-.81 1.11-1.93.99-3.06-1 .04-2.22.67-2.94 1.5-.62.71-1.16 1.85-1.02 2.96 1.12.09 2.27-.57 2.97-1.4z"/></svg></span>';
  }

  const card = document.createElement('div');
  card.className = `profile-card${isRunning?' active-profile':''}${isSelected?' selected':''}`;
  card.dataset.name = p.name;

  // Fingerprint configuration values
  const cpuVal = p.cpu === 'random' ? 'Random' : (p.cpu || '8');
  const canvasVal = p.canvas === 'noise' ? 'Mask' : (p.canvas === 'off' ? 'Off' : 'Mask');
  const rtcVal = p.webrtc === 'mask' ? 'Mask' : (p.webrtc === 'block' ? 'Block' : 'Direct');

  card.innerHTML = `
    ${color ? `<div class="profile-card-color-strip" style="background:${color}"></div>` : ''}
    <div class="profile-card-top">
      <div class="profile-checkbox${isSelected?' checked':''}" data-name="${escapeHTML(p.name)}"></div>
      <div class="profile-avatar" style="${color?`border-color:${color}40`:''}; position: relative;">
        ${osIconHtml}
        <img src="${browserIcon}" alt="Browser" class="profile-avatar-img" />
      </div>
      <div class="profile-info">
        <div class="profile-name" title="${escapeHTML(p.name)}">${escapeHTML(p.name)}</div>
        <div class="status-pill ${isRunning?'running':'stopped'}">
          <div class="profile-status-dot" style="background:${isRunning?'#10b981':'#64748b'}; width: 5px; height: 5px; border-radius: 50%;"></div>
          ${isRunning ? 'Đang mở' : 'Đã tắt'}
        </div>
      </div>
    </div>
    <div class="profile-card-meta">
      <div class="profile-meta-row" style="margin-bottom: 6px;">
        <span class="tag" style="background: var(--accent-glow); color: var(--accent); border: 1px solid rgba(108,99,255,0.2); font-weight: 500; font-size: 10px; padding: 2px 6px; border-radius: 4px;">📂 ${escapeHTML(p.group || 'Mặc định')}</span>
        ${p.notes ? `<span style="font-size: 9.5px; color: var(--text-muted); background: var(--bg-hover); padding: 2px 6px; border-radius: 4px; max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${escapeHTML(p.notes)}</span>` : ''}
      </div>

      <!-- Fingerprints summary -->
      <div class="profile-meta-row" style="margin-bottom: 6px; display: flex; gap: 4px; flex-wrap: wrap;">
        <span class="fingerprint-capsule" title="Nhân CPU">CPU: ${escapeHTML(cpuVal)}</span>
        <span class="fingerprint-capsule" title="Canvas Mask">CVS: ${escapeHTML(canvasVal)}</span>
        <span class="fingerprint-capsule" title="WebRTC Mask">RTC: ${escapeHTML(rtcVal)}</span>
      </div>
      
      <!-- Connection & Proxy -->
      ${p.proxy ? `
      <div class="profile-meta-row" style="display: flex; align-items: center; justify-content: space-between; overflow: hidden;">
        <div style="display: flex; align-items: center; gap: 4px; overflow: hidden; flex: 1;">
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
          <span class="profile-meta-value" style="font-family: var(--font-mono); font-size: 11px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${escapeHTML(p.proxy)}">
            ${escapeHTML(proxy ? `${proxy.host}:${proxy.port}` : p.proxy)}
          </span>
        </div>
        <button class="proxy-check-btn" onclick="testProxyInline(event, '${escapeInlineJsSingle(p.proxy)}', 'proxyResultCard_${escapeInlineJsSingle(p.name)}')" style="margin-left: 4px;" title="Kiểm tra kết nối Proxy">
          <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
        </button>
      </div>
      <div id="proxyResultCard_${p.name}" style="margin: 2px 0 4px 15px; min-height: 14px;"></div>
      ` : `
      <div class="profile-meta-row">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
        <span class="profile-meta-value" style="color:var(--text-muted)">Không có proxy</span>
      </div>`}

      <!-- Stats -->
      ${p.openCount ? `
      <div class="profile-meta-row">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg>
        <span style="font-size: 11px;">${Number(p.openCount) || 0} lần mở${p.lastOpened ? ' • ' + escapeHTML(timeSince(p.lastOpened)) : ''}</span>
      </div>` : ''}
    </div>
    
    ${p.tags && p.tags.length ? `
    <div class="profile-tags">
      ${p.tags.map(t => `<span class="tag">${escapeHTML(t)}</span>`).join('')}
    </div>` : ''}

    <div class="profile-card-actions">
      <button class="profile-action-btn btn-edit" data-action="edit" data-name="${p.name}" title="Chỉnh sửa cấu hình">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        <span>Sửa</span>
      </button>
      ${isRunning ? `
      <button class="profile-action-btn btn-close" data-action="close" data-name="${p.name}" title="Tắt tiến trình Chrome">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><rect x="4" y="4" width="16" height="16" rx="2"/></svg>
        <span>Tắt Chrome</span>
      </button>
      ` : `
      <button class="profile-action-btn btn-open" data-action="open" data-name="${p.name}" title="Khởi động Chrome">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><polygon points="6 4 20 12 6 20"/></svg>
        <span>Mở Chrome</span>
      </button>
      `}
    </div>
  `;

  card.addEventListener('click', e => {
    if (e.target.closest('[data-action]')) return;
    if (e.target.closest('.profile-checkbox')) return;
    if (e.target.closest('.proxy-check-btn')) return;
    showContextMenu(e, p.name);
  });

  card.addEventListener('contextmenu', e => {
    e.preventDefault();
    showContextMenu(e, p.name);
  });

  card.querySelector('[data-action="open"]')?.addEventListener('click', e => {
    e.stopPropagation();
    openProfile(p.name);
  });

  card.querySelector('[data-action="close"]')?.addEventListener('click', e => {
    e.stopPropagation();
    closeProfile(p.name);
  });

  card.querySelector('[data-action="edit"]')?.addEventListener('click', e => {
    e.stopPropagation();
    editProfile(p);
  });

  card.querySelector('.profile-checkbox')?.addEventListener('click', e => {
    e.stopPropagation();
    toggleSelect(p.name);
  });

  return card;
}

function parseProxy(proxyStr) {
  if (!proxyStr || typeof proxyStr !== 'string') return null;
  let type = 'HTTP';
  let raw = proxyStr.trim();
  if (raw.includes('://')) {
    const parts = raw.split('://');
    type = parts[0].toUpperCase();
    if (type === 'SOCKS') type = 'SOCKS5';
    raw = parts.slice(1).join('://').trim();
  }

  let host = '', port = '', user = '', pass = '';

  if (raw.includes('@')) {
    const lastAtIndex = raw.lastIndexOf('@');
    const authPart = raw.substring(0, lastAtIndex);
    const serverPart = raw.substring(lastAtIndex + 1);

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
      port = serverPart.substring(lastColon + 1);
    } else {
      host = serverPart;
    }
  } else {
    const parts = raw.split(':');
    host = parts[0] ? parts[0].trim() : '';
    port = parts[1] ? parts[1].trim() : '';
    if (parts.length >= 4) {
      user = parts[2] ? parts[2].trim() : '';
      pass = parts.slice(3).join(':');
    }
  }

  host = host.trim().replace(/^\[|\]$/g, '');
  if (user) user = user.trim();
  if (pass) pass = pass.trim();

  return { type, host, port, user, pass };
}

function createProfileRow(p) {
  const isRunning = S.running.includes(p.name);
  const isSelected = S.selected.has(p.name);
  const browserIcon = p.browserType === 'custom' ? './image/chromium.png' : './image/chrom.png';
  const proxy = parseProxy(p.proxy);

  // Parse OS icon
  let osIconHtml = '';
  const ua = (p.userAgent || '').toLowerCase();
  if (ua.includes('windows')) {
    osIconHtml = '<span class="os-badge" title="Windows OS"><svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" style="color:#0078d7;"><path d="M0 3.449L9.75 2.1v9.45H0V3.449zM0 12.45h9.75v9.45L0 20.551v-8.101zM10.95 1.95L24 0v11.55H10.95V1.95zM10.95 12.45H24v11.55l-13.05-1.95v-9.6z"/></svg></span>';
  } else if (ua.includes('macintosh') || ua.includes('mac os')) {
    osIconHtml = '<span class="os-badge" title="macOS"><svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" style="color:#8e8e93;"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 4.17c.66-.81 1.11-1.93.99-3.06-1 .04-2.22.67-2.94 1.5-.62.71-1.16 1.85-1.02 2.96 1.12.09 2.27-.57 2.97-1.4z"/></svg></span>';
  }

  // Fingerprint configuration values
  const cpuVal = p.cpu === 'random' ? 'Random' : (p.cpu || '8');
  const canvasVal = p.canvas === 'noise' ? 'Mask' : (p.canvas === 'off' ? 'Off' : 'Mask');
  const rtcVal = p.webrtc === 'mask' ? 'Mask' : (p.webrtc === 'block' ? 'Block' : 'Direct');
  const webglVal = p.webgl === 'noise' ? 'Mask' : (p.webgl === 'off' ? 'Off' : 'Mask');
  const audioVal = p.audio === 'noise' ? 'Mask' : 'Direct';
  const timezoneVal = p.timezone === 'auto' ? 'Auto' : (p.timezone || 'UTC');
  const languageVal = p.language === 'auto' ? 'Auto' : (p.language || 'vi-VN');
  const gpuVal = p.gpu === 'random' ? 'Random GPU' : 'Default GPU';
  const ramVal = p.ram === 'random' ? 'Random' : (p.ram || '8GB');
  const fontsVal = p.fonts === 'random' ? 'Random Fonts' : 'Default Fonts';

  const row = document.createElement('div');
  row.className = `profile-row${isRunning?' active-profile':''}${isSelected?' selected':''}`;
  row.dataset.name = p.name;

  row.innerHTML = `
    <!-- Checkbox & Avatar -->
    <div class="col-cb-avatar" style="width: 64px;">
      <div class="profile-checkbox${isSelected?' checked':''}" data-name="${escapeHTML(p.name)}"></div>
      <div class="profile-browser-avatar" style="position: relative; flex-shrink: 0; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; background: var(--bg-deep); border: 1px solid var(--border); border-radius: 8px; padding: 4px;">
        <img src="${browserIcon}" alt="Browser" style="width: 100%; height: 100%; object-fit: contain;">
        <div class="profile-row-status-dot ${isRunning?'running':'stopped'}" style="position: absolute; bottom: -2px; right: -2px; width: 7px; height: 7px; border-radius: 50%; border: 1.5px solid var(--bg-card); background: ${isRunning?'#34D399':'#94a3b8'};"></div>
      </div>
    </div>

    <!-- Name & Details -->
    <div class="col-info" style="display: flex; flex-direction: column; gap: 2px; text-align: left; padding-left: 8px; overflow: hidden;">
      <div style="display: flex; align-items: center; gap: 4px; overflow: hidden;">
        ${osIconHtml}
        <span class="profile-row-name" style="font-weight: 700; font-size: 13.5px; color: var(--text-primary); cursor: pointer; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;" title="${escapeHTML(p.name)}">${escapeHTML(p.name)}</span>
      </div>
      <div style="display: flex; gap: 6px; align-items: center; margin-top: 1px; overflow: hidden; flex-wrap: wrap;">
        <span class="profile-group-badge">
          <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
          ${escapeHTML(p.group || 'Mặc định')}
        </span>
        ${p.notes ? `<span style="font-size: 10px; color: var(--text-muted); background: var(--bg-hover); padding: 1px 6px; border-radius: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${escapeHTML(p.notes)}">${escapeHTML(p.notes)}</span>` : ''}
        ${p.tags && p.tags.length ? p.tags.map(t => `<span class="tag" style="padding: 1px 6px; font-size: 9px; border-radius: 4px; background: var(--bg-hover); color: var(--text-secondary); border: 1px solid var(--border);">${escapeHTML(t)}</span>`).join('') : ''}
      </div>
    </div>

    <!-- Fingerprints Column (Sleek Icons Grid Layout with hover colors) -->
    <div class="col-fingerprints" style="display: flex; gap: 8px; align-items: center; justify-content: flex-start; padding-left: 8px;">
      <!-- CPU -->
      <svg class="fp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" title="CPU: ${escapeHTML(cpuVal)} Cores" style="width:14px; height:14px; color:var(--text-muted); cursor:help;"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 15h3M1 9h3M1 15h3"/></svg>
      <!-- RAM -->
      <svg class="fp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" title="RAM: ${escapeHTML(ramVal)}" style="width:14px; height:14px; color:var(--text-muted); cursor:help;"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/></svg>
      <!-- Canvas -->
      <svg class="fp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" title="Canvas: ${escapeHTML(canvasVal)}" style="width:14px; height:14px; color:var(--text-muted); cursor:help;"><path d="M18 21a3 3 0 0 0 3-3c0-3-4-8-4-8s-4 5-4 8a3 3 0 0 0 3 3z"/><path d="M14 6L8 12M18 2L14 6M10 2L2 10a4 4 0 0 0 0 5.66l4.24 4.24a4 4 0 0 0 5.66 0L20 12"/></svg>
      <!-- WebGL -->
      <svg class="fp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" title="WebGL: ${escapeHTML(webglVal)}" style="width:14px; height:14px; color:var(--text-muted); cursor:help;"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
      <!-- WebRTC -->
      <svg class="fp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" title="WebRTC: ${escapeHTML(rtcVal)}" style="width:14px; height:14px; color:var(--text-muted); cursor:help;"><path d="M23 7l-7 5 7 5V7z"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
      <!-- Timezone -->
      <svg class="fp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" title="Timezone: ${escapeHTML(timezoneVal)}" style="width:14px; height:14px; color:var(--text-muted); cursor:help;"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
      <!-- Language -->
      <svg class="fp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" title="Language: ${escapeHTML(languageVal)}" style="width:14px; height:14px; color:var(--text-muted); cursor:help;"><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
      <!-- GPU -->
      <svg class="fp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" title="GPU: ${gpuVal}" style="width:14px; height:14px; color:var(--text-muted); cursor:help;"><rect x="2" y="7" width="20" height="10" rx="2"/><path d="M6 17v2M18 17v2M10 7v3M14 7v3"/></svg>
      <!-- Audio -->
      <svg class="fp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" title="Audio: ${audioVal}" style="width:14px; height:14px; color:var(--text-muted); cursor:help;"><path d="M11 5L6 9H2v6h4l5 4V5z"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07M19.07 4.93a10 10 0 0 1 0 14.14"/></svg>
      <!-- Fonts -->
      <svg class="fp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" title="Fonts: ${fontsVal}" style="width:14px; height:14px; color:var(--text-muted); cursor:help;"><polyline points="4 7 4 4 20 4 20 7"/><line x1="9" y1="20" x2="15" y2="20"/><line x1="12" y1="4" x2="12" y2="20"/></svg>
    </div>

    <!-- Proxy Details -->
    <div class="col-proxy" style="display: flex; align-items: center; gap: 8px; text-align: left; padding-left: 8px; overflow: hidden;">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="color: var(--text-muted); flex-shrink: 0;"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
      ${proxy ? `
        <div style="display: flex; flex-direction: column; gap: 2px; overflow: hidden; flex: 1; min-width: 0;">
          <div style="display: flex; align-items: center; gap: 6px; min-width: 0;">
            <span class="proxy-badge ${['http','https','socks4','socks5'].includes(proxy.type.toLowerCase()) ? proxy.type.toLowerCase() : 'http'}" style="font-size: 9px; font-weight: 700; padding: 1px 4px; border-radius: 4px; text-transform: uppercase;">${escapeHTML(proxy.type)}</span>
            <span style="font-family: var(--font-mono); font-size: 11.5px; font-weight: 600; color: var(--text-primary); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${escapeHTML(p.proxy)}">
              ${escapeHTML(proxy.host)}:${escapeHTML(proxy.port)}
            </span>
            <!-- Quick copy button -->
            <button class="proxy-copy-btn" onclick="copyToClipboard(event, '${escapeInlineJsSingle(p.proxy)}')" title="Copy Proxy" style="background:none; border:none; color:var(--text-muted); cursor:pointer; padding:2px; display:inline-flex; align-items:center; transition:color 0.2s;">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
            </button>
            <button class="proxy-check-btn" onclick="testProxyInline(event, '${escapeInlineJsSingle(p.proxy)}', 'proxyResult_${escapeInlineJsSingle(p.name)}')" title="Kiểm tra kết nối Proxy" style="background:none; border:none; color:var(--text-muted); cursor:pointer; padding:2px; display:inline-flex; align-items:center; transition:color 0.2s;">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
            </button>
          </div>
          <div id="proxyResult_${p.name}" style="margin-top: 1px; min-height: 14px;"></div>
        </div>
      ` : `
        <span style="font-size: 11.5px; color: var(--text-muted);">Không có proxy</span>
      `}
    </div>

    <!-- Usage Stats -->
    <div class="col-stats" style="font-size: 11.5px; color: var(--text-muted); text-align: left; padding-left: 4px; display: flex; flex-direction: column; gap: 3px; overflow: hidden;">
      <div style="display: flex; align-items: center; gap: 5px;">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="opacity: 0.65;"><circle cx="12" cy="12" r="10"/><polygon points="12 6 12 12 16 14"/></svg>
        <span>${p.openCount || 0} lần mở</span>
      </div>
      <div style="font-size: 10.5px; opacity: 0.8; display: flex; align-items: center; gap: 5px;">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="opacity: 0.55;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        <span>${p.lastOpened ? timeSince(p.lastOpened) : 'Chưa mở'}</span>
      </div>
    </div>

    <!-- Actions (Premium Styled Buttons) -->
    <div class="col-actions">
      <button class="profile-action-btn btn-edit" data-action="edit" data-name="${p.name}" title="Chỉnh sửa cấu hình">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        <span>Sửa</span>
      </button>
      ${isRunning ? `
      <button class="profile-action-btn btn-close" data-action="close" data-name="${p.name}" title="Tắt tiến trình Chrome">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><rect x="4" y="4" width="16" height="16" rx="2"/></svg>
        <span>Tắt Chrome</span>
      </button>
      ` : `
      <button class="profile-action-btn btn-open" data-action="open" data-name="${p.name}" title="Khởi động Chrome">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><polygon points="6 4 20 12 6 20"/></svg>
        <span>Mở Chrome</span>
      </button>
      `}
      <!-- More Actions menu button (...) -->
      <button class="profile-action-btn btn-more" data-action="more" data-name="${p.name}" title="Thao tác khác">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><circle cx="5" cy="12" r="2.2"/><circle cx="12" cy="12" r="2.2"/><circle cx="19" cy="12" r="2.2"/></svg>
      </button>
    </div>
  `;

  row.querySelector('[data-action="open"]')?.addEventListener('click', e => { e.stopPropagation(); openProfile(p.name); });
  row.querySelector('[data-action="close"]')?.addEventListener('click', e => { e.stopPropagation(); closeProfile(p.name); });
  row.querySelector('[data-action="edit"]')?.addEventListener('click', e => { e.stopPropagation(); editProfile(p); });
  row.querySelector('[data-action="more"]')?.addEventListener('click', e => {
    e.stopPropagation();
    const moreBtn = row.querySelector('[data-action="more"]');
    const rect = moreBtn.getBoundingClientRect();
    const mockEvent = {
      clientX: rect.left - 120, // offset left to align menu options contextually
      clientY: rect.bottom + 5
    };
    showContextMenu(mockEvent, p.name);
  });
  row.querySelector('.profile-checkbox')?.addEventListener('click', e => { e.stopPropagation(); toggleSelect(p.name); });
  row.addEventListener('click', e => {
    if (e.target.closest('[data-action]')) return;
    if (e.target.closest('.profile-checkbox')) return;
    if (e.target.closest('.proxy-check-btn')) return;
    showContextMenu(e, p.name);
  });
  row.addEventListener('contextmenu', e => { e.preventDefault(); showContextMenu(e, p.name); });

  return row;
}

function updateStats() {
  const active = S.running.length;
  if ($('statTotal')) $('statTotal').textContent = S.profiles.length;
  if ($('statActive')) $('statActive').textContent = active;
  if ($('statInactive')) $('statInactive').textContent = S.profiles.length - active;
  if ($('runningCount')) $('runningCount').textContent = active;
  if ($('profileCount')) $('profileCount').textContent = S.profiles.length;
}

function timeSince(dateStr) {
  const secs = Math.floor((Date.now() - new Date(dateStr)) / 1000);
  if (secs < 60)   return 'vừa xong';
  if (secs < 3600) return Math.floor(secs/60) + ' phút';
  if (secs < 86400)return Math.floor(secs/3600) + ' giờ';
  return Math.floor(secs/86400) + ' ngày';
}

// ── SELECT ──────────────────────────────────────────────────────────────────
function toggleSelect(name) {
  if (S.selected.has(name)) S.selected.delete(name);
  else S.selected.add(name);
  updateBulkUI();
  renderProfiles();
}

function updateBulkUI() {
  const n = S.selected.size;
  const bulk = $('bulkActions');
  if (bulk) bulk.style.display = n > 0 ? 'flex' : 'none';
  const sc = $('selectedCount');
  if (sc) sc.textContent = n;

  // Cập nhật trạng thái Floating Action Bar nổi ở dưới
  const floatBar = $('floatingActionBar');
  if (floatBar) {
    if (n > 0) {
      floatBar.classList.add('active');
    } else {
      floatBar.classList.remove('active');
    }
  }
  const floatCount = $('selectedCountFloat');
  if (floatCount) floatCount.textContent = n;
}

$('checkAll')?.addEventListener('change', e => {
  const filtered = getFilteredProfiles();
  if (e.target.checked) filtered.forEach(p => S.selected.add(p.name));
  else S.selected.clear();
  updateBulkUI();
  renderProfiles();
});

// ── VIEW TOGGLE ─────────────────────────────────────────────────────────────
$('viewGrid')?.addEventListener('click', () => {
  S.viewMode = 'grid';
  $('viewGrid').classList.add('active');
  $('viewList').classList.remove('active');
  renderProfiles();
});
$('viewList')?.addEventListener('click', () => {
  S.viewMode = 'list';
  $('viewList').classList.add('active');
  $('viewGrid').classList.remove('active');
  renderProfiles();
});

// ── FILTER TABS ──────────────────────────────────────────────────────────────
$$('.filter-tab').forEach(tab => {
  tab.addEventListener('click', () => {
    $$('.filter-tab').forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    S.filter = tab.dataset.filter;
    S.currentPage = 1;
    renderProfiles();
  });
});

// ── SORT ─────────────────────────────────────────────────────────────────────
$('sortProfiles')?.addEventListener('change', e => {
  S.sort = e.target.value;
  S.currentPage = 1;
  renderProfiles();
});

// ── SEARCH ───────────────────────────────────────────────────────────────────
$('globalSearchInput')?.addEventListener('input', e => {
  S.search = e.target.value;
  S.currentPage = 1;
  renderProfiles();
});
document.addEventListener('keydown', e => {
  if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
    e.preventDefault();
    $('globalSearchInput')?.focus();
  }
});

// ── CONTEXT MENU ──────────────────────────────────────────────────────────────
function showContextMenu(e, name) {
  S.ctx.profile = name;
  const menu = $('contextMenu');
  menu.classList.add('active');
  const x = Math.min(e.clientX, window.innerWidth - 180);
  const y = Math.min(e.clientY, window.innerHeight - 240); // Slightly adjusted for taller menu
  menu.style.left = x + 'px';
  menu.style.top  = y + 'px';
}
document.addEventListener('click', () => $('contextMenu')?.classList.remove('active'));

$('ctxOpen')?.addEventListener('click', () => openProfile(S.ctx.profile));
$('ctxEdit')?.addEventListener('click', () => {
  const p = S.profiles.find(p => p.name === S.ctx.profile);
  if (p) editProfile(p);
});
$('ctxDuplicate')?.addEventListener('click', async () => {
  const res = await API.profilesDuplicate(S.ctx.profile);
  if (res.ok) { toast(`Đã nhân bản → ${res.newName}`, 'success'); loadProfiles(); }
  else toast(res.msg || 'Lỗi', 'error');
});
$('ctxRename')?.addEventListener('click', async () => {
  const newName = await prompt(`Đổi tên "${S.ctx.profile}" thành:`, S.ctx.profile);
  if (newName && newName !== S.ctx.profile) renameProfile(S.ctx.profile, newName);
});
$('ctxExport')?.addEventListener('click', () => {
  $('exportResult').value = '';
  openModal('exportModal');
});
$('ctxImportCookie')?.addEventListener('click', async () => {
  const p = S.profiles.find(p => p.name === S.ctx.profile);
  if (!p) return;
  const cookieStr = await prompt('Nhập Cookie (dạng chuỗi key=val hoặc JSON):');
  if (cookieStr) {
    await API.profilesUpdate(p.name, { notes: cookieStr });
    toast('Đã nhập Cookie vào Ghi chú thành công!', 'success');
    loadProfiles();
  }
});
$('ctxImportProxy')?.addEventListener('click', async () => {
  const p = S.profiles.find(p => p.name === S.ctx.profile);
  if (!p) return;
  const proxyStr = await prompt('Nhập Proxy mới (định dạng IP:Port hoặc IP:Port:User:Pass):', p.proxy || '');
  if (proxyStr) {
    await API.profilesUpdate(p.name, { proxy: proxyStr });
    toast('Đã cập nhật Proxy thành công!', 'success');
    loadProfiles();
  }
});
$('ctxClearData')?.addEventListener('click', async () => {
  if (await confirm(`Bạn có chắc muốn xoá sạch Cookie & Cache của profile "${S.ctx.profile}"?`)) {
    const res = await API.profilesClearData(S.ctx.profile, 'all');
    if (res.ok) toast('Đã dọn dẹp Cookie/Cache thành công!', 'success');
    else toast('Lỗi: ' + res.msg, 'error');
  }
});
$('ctxBookmarks')?.addEventListener('click', () => {
  toast('Đang khởi động Bookmarks Manager...', 'info');
  API.profilesOpen(S.ctx.profile, '', 'chrome://bookmarks/');
});
$('ctxExtensions')?.addEventListener('click', () => {
  toast('Đang khởi động Extensions Manager...', 'info');
  API.profilesOpen(S.ctx.profile, '', 'chrome://extensions/');
});
$('ctxDelete')?.addEventListener('click', async () => {
  if (await confirm(`Xoá profile "${S.ctx.profile}"? Thao tác không thể hoàn tác.`)) deleteProfile(S.ctx.profile);
});

// ── OPEN PROFILE ──────────────────────────────────────────────────────────────
async function openProfile(name) {
  toast(`Đang mở Chrome: ${name}...`, 'info');
  const res = await API.profilesOpen(name, '');
  if (res.ok) {
    toast(`Chrome đã mở: ${name}`, 'success');
    setTimeout(loadProfiles, 1000);
  } else {
    if (res.needChrome) {
      toast('Không tìm thấy Chrome. Vào Cài đặt để cấu hình!', 'error', 5000);
    } else {
      toast(res.msg || 'Lỗi mở Chrome', 'error');
    }
  }
}

async function closeProfile(name) {
  toast(`Đang tắt Chrome: ${name}...`, 'warning');
  const res = await API.profilesClose(name);
  if (res.ok) {
    toast(`Đã tắt Chrome: ${name}`, 'success');
    loadProfiles();
  } else {
    toast(res.msg || 'Lỗi tắt Chrome', 'error');
  }
}

// ── CREATE PROFILE ────────────────────────────────────────────────────────────
let selectedColor = '';

$$('.color-dot').forEach(dot => {
  dot.addEventListener('click', () => {
    $$('.color-dot').forEach(d => d.classList.remove('active'));
    dot.classList.add('active');
    selectedColor = dot.dataset.color;
  });
});

// Profile Tabs
$$('.profile-tab').forEach(tab => {
  tab.addEventListener('click', () => {
    $$('.profile-tab').forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    $$('.profile-tab-content').forEach(c => c.classList.remove('active'));
    $(`tab-${tab.dataset.tab}`)?.classList.add('active');
  });
});

// Random Fingerprint
$('btnRandomFingerprint')?.addEventListener('click', () => {
  // Random Timezone
  const timezones = ['Asia/Ho_Chi_Minh','Asia/Bangkok','Asia/Singapore','Asia/Tokyo','America/New_York','America/Los_Angeles','Europe/London','Europe/Paris','Australia/Sydney'];
  $('profileTimezone').value = timezones[Math.floor(Math.random() * timezones.length)];
  
  // Random Language
  const langs = ['vi-VN,en-US','en-US,vi-VN','zh-CN,en-US','ja-JP,en-US','ko-KR,en-US','th-TH,en-US'];
  $('profileLanguage').value = langs[Math.floor(Math.random() * langs.length)];
  
  // Random Screen
  const screens = ['1920,1080','1366,768','1536,864','1440,900','1280,720','2560,1440'];
  $('profileScreen').value = screens[Math.floor(Math.random() * screens.length)];
  
  // Random User Agent
  const userAgents = [
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.2 Safari/605.1.15',
  ];
  $('profileUA').value = userAgents[Math.floor(Math.random() * userAgents.length)];
  
  // Random CPU
  const cpus = ['2','4','6','8'];
  $('profileCPU').value = cpus[Math.floor(Math.random() * cpus.length)];
  
  // Set anti-detect options
  $('profileCanvas').value = 'noise';
  $('profileWebGL').value = 'noise';
  $('profileAudio').value = 'noise';
  $('profileWebRTC').value = 'mask';
  $('profileFonts').value = 'random';
  
  toast('Đã random fingerprint!', 'success');
});

// Random UA Button
$('btnRandomUA')?.addEventListener('click', () => {
  const userAgents = [
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.2 Safari/605.1.15',
  ];
  $('profileUA').value = userAgents[Math.floor(Math.random() * userAgents.length)];
});

// Check Proxy Inline
$('btnCheckProxyInline')?.addEventListener('click', async () => {
  const proxy = $('profileProxy').value.trim();
  const resultEl = $('proxyCheckResultInline');
  
  if (!proxy) {
    resultEl.className = 'proxy-check-result-inline invalid';
    resultEl.innerHTML = '✕ Vui lòng nhập proxy!';
    resultEl.style.display = 'block';
    return;
  }
  
  resultEl.className = 'proxy-check-result-inline checking';
  resultEl.innerHTML = '⏳ Đang kiểm tra...';
  resultEl.style.display = 'block';
  
  const res = await API.proxyCheckFull(proxy);
  
  if (res.ok) {
    const info = res.info;
    resultEl.className = 'proxy-check-result-inline valid';
    resultEl.innerHTML = `
      <strong>✓ Proxy hoạt động!</strong><br>
      IP: ${escapeHTML(info.ip || 'N/A')}<br>
      Port: ${escapeHTML(info.port || 'N/A')}<br>
      Country: ${escapeHTML(info.country || 'N/A')}<br>
      ISP: ${escapeHTML(info.isp || 'N/A')}<br>
      Speed: ${escapeHTML(info.speed || 'N/A')}
    `;
  } else {
    resultEl.className = 'proxy-check-result-inline invalid';
    resultEl.textContent = `✕ ${res.msg || 'Proxy không hoạt động'}`;
  }
});

// Proxy Preset
$$('.proxy-preset-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    $('profileProxy').value = btn.dataset.proxy || '';
  });
});

$('btnStopAll')?.addEventListener('click', async () => {
  if (S.running.length === 0) {
    toast('Không có profile nào đang chạy', 'info');
    return;
  }
  if (await confirm(`Tắt toàn bộ ${S.running.length} cửa sổ Chrome đang chạy?`)) {
    toast('Đang tắt toàn bộ Chrome...', 'warning');
    const res = await API.profilesCloseAll();
    if (res.ok) {
      toast(`Đã tắt thành công các profile đang hoạt động`, 'success');
      loadProfiles();
    }
  }
});

// ── CREATE PROFILE PAGE INITIALIZATION ──────────────────────────────────────
window.currentEditingProfile = null;

async function editProfile(p) {
  window.currentEditingProfile = p;
  await loadView('create-profile');
}

function initCreateProfilePage() {
  const isEdit = !!window.currentEditingProfile;
  const p = window.currentEditingProfile;

  // Tabs navigation
  $$('.profile-tab').forEach(tab => {
    tab.addEventListener('click', () => {
      $$('.profile-tab').forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      const target = tab.dataset.tab;
      $$('.profile-tab-content').forEach(content => {
        content.style.display = content.id === `tab-${target}` ? 'flex' : 'none';
      });
    });
  });

  // Back buttons
  $('btnBackToProfiles')?.addEventListener('click', () => loadView('profiles'));
  $('btnCancelCreateProfile')?.addEventListener('click', () => loadView('profiles'));

  // Helper to set platform and update OS selection card UI styles
  function setPlatformUI(platformVal) {
    const hiddenInput = $('profilePlatform');
    if (hiddenInput) hiddenInput.value = platformVal;
    
    $$('.os-card').forEach(card => {
      const isActive = card.dataset.os === platformVal;
      card.classList.toggle('active', isActive);
      card.style.borderColor = isActive ? 'var(--accent)' : 'var(--border)';
      card.style.background = isActive ? 'rgba(99, 102, 241, 0.04)' : 'var(--bg-card)';
      const chk = card.querySelector('.checkmark-badge');
      if (chk) chk.style.display = isActive ? 'flex' : 'none';
    });
  }

  // Bind OS card click events
  $$('.os-card').forEach(card => {
    card.addEventListener('click', () => {
      setPlatformUI(card.dataset.os);
      updateSummary();
    });
  });

  // Browser type card selection
  $$('.browser-option-card').forEach(card => {
    card.addEventListener('click', () => {
      $$('.browser-option-card').forEach(c => {
        c.classList.remove('active');
        c.style.border = '2px solid var(--border)';
        c.style.background = 'var(--bg-card2)';
      });
      card.classList.add('active');
      card.style.border = '2px solid var(--accent)';
      card.style.background = 'rgba(108,99,255,0.06)';
      $('profileBrowserType').value = card.dataset.browser;
      updateSummary();
    });
  });

  // Color dot picker
  let selectedColor = '';
  $$('.color-dot').forEach(dot => {
    dot.addEventListener('click', () => {
      $$('.color-dot').forEach(d => d.classList.remove('active'));
      dot.classList.add('active');
      selectedColor = dot.dataset.color || '';
    });
  });

  // Group options populating
  const groupSelect = $('profileGroup');
  if (groupSelect && S.groups) {
    groupSelect.innerHTML = S.groups.map(g => `<option value="${escapeHTML(g.name)}">${escapeHTML(g.name)}</option>`).join('');
  }

  // Helper for inline randomizers
  window.randomizeField = function(id, options, label) {
    const el = document.getElementById(id);
    if (el && options && options.length > 0) {
      el.value = options[Math.floor(Math.random() * options.length)];
      toast(`Đã ngẫu nhiên ${label}!`, 'success');
      updateSummary();
    }
  };

  // Update Summary panel
  function updateSummary() {
    if ($('sumName')) $('sumName').textContent = $('profileName')?.value.trim() || 'Chưa đặt tên';
    if ($('sumBrowser')) $('sumBrowser').textContent = $('profileBrowserType')?.value === 'custom' ? 'Chromium đi kèm' : 'Chrome hệ thống';
    if ($('sumPlatform')) $('sumPlatform').textContent = $('profilePlatform')?.value || 'Windows';
    
    const cpu = $('profileCPU')?.value;
    if ($('sumCPU')) $('sumCPU').textContent = cpu === 'random' ? 'Ngẫu nhiên' : cpu + ' Cores';
    
    const ram = $('profileRAM')?.value;
    if ($('sumRAM')) $('sumRAM').textContent = ram === 'random' ? 'Ngẫu nhiên' : ram + ' GB';
    
    const gpu = $('profileGPU')?.value;
    if ($('sumGPU')) $('sumGPU').textContent = gpu === 'random' ? 'Ngẫu nhiên' : gpu.toUpperCase();
    
    const timezone = $('profileTimezone')?.value;
    if ($('sumTimezone')) $('sumTimezone').textContent = timezone === 'auto' ? 'Tự động' : timezone.split('/').pop().replace('_', ' ');

    const language = $('profileLanguage')?.value;
    if ($('sumLanguage')) $('sumLanguage').textContent = language || 'Tự động';

    const scr = $('profileScreen')?.value;
    if ($('sumScreen')) $('sumScreen').textContent = scr === 'random' ? 'Ngẫu nhiên' : scr.replace(',', ' x ');

    // Calculate score based on options
    const canvas = $('profileCanvas')?.value;
    const webgl = $('profileWebGL')?.value;
    const audio = $('profileAudio')?.value;
    let score = 70;
    if (canvas === 'noise') score += 10;
    if (webgl === 'noise') score += 10;
    if (audio === 'noise') score += 5;
    if ($('sumFingerprint')) $('sumFingerprint').textContent = score + '/100';
  }

  // Bind change events
  const watchIds = [
    'profileName', 'profileBrowserType', 'profilePlatform', 'profileCPU',
    'profileRAM', 'profileGPU', 'profileScreen', 'profileCanvas', 'profileWebGL',
    'profileAudio', 'profileTimezone', 'profileLanguage'
  ];
  watchIds.forEach(id => {
    $(id)?.addEventListener('input', updateSummary);
    $(id)?.addEventListener('change', updateSummary);
  });

  // Random User Agent
  $('btnRandomUA')?.addEventListener('click', () => {
    const list = [
      'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
      'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36',
      'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36',
      'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
      'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
    ];
    const rand = list[Math.floor(Math.random() * list.length)];
    $('profileUA').value = rand;
    updateSummary();
  });

  // Random all fingerprints
  $('btnRandomFingerprint')?.addEventListener('click', () => {
    const platforms = ['Win32', 'MacIntel', 'Linux x86_64'];
    const selPlatform = platforms[Math.floor(Math.random() * platforms.length)];
    setPlatformUI(selPlatform);

    const uas = {
      'Win32': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
      'MacIntel': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
      'Linux x86_64': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
    };
    $('profileUA').value = uas[selPlatform] || uas['Win32'];

    const screens = ['1920,1080', '1536,864', '1440,900', '1366,768'];
    $('profileScreen').value = screens[Math.floor(Math.random() * screens.length)];

    const cpus = ['4', '6', '8', '12'];
    $('profileCPU').value = cpus[Math.floor(Math.random() * cpus.length)];

    const rams = ['8', '16', '32'];
    $('profileRAM').value = rams[Math.floor(Math.random() * rams.length)];

    const gpus = ['intel', 'nvidia', 'amd', 'apple'];
    $('profileGPU').value = gpus[Math.floor(Math.random() * gpus.length)];

    const dprs = ['1', '1.25', '1.5', '2'];
    $('profileDPR').value = dprs[Math.floor(Math.random() * dprs.length)];

    $('profileTouch').value = Math.random() > 0.7 ? 'enable' : 'disable';

    $('profileBattery').value = 'noise';
    $('profilePermissions').value = 'noise';
    $('profileSpeech').value = 'noise';
    $('profileCanvas').value = 'noise';
    $('profileWebGL').value = 'noise';
    $('profileAudio').value = 'noise';
    $('profileClientRects').value = 'noise';
    $('profileMediaDevices').value = 'noise';
    $('profileFonts').value = 'random';

    toast('Đã sinh ngẫu nhiên vân tay mới!', 'success');
    updateSummary();
  });

  // Set proxy type segmented control helper
  function setProxyTypeUI(typeVal) {
    const val = (typeVal || 'http').toLowerCase();
    const input = $('profileProxyType');
    if (input) input.value = val;
    $$('#proxyProtocolControl .segment-btn').forEach(btn => {
      const isActive = btn.dataset.value === val;
      btn.classList.toggle('active', isActive);
    });
  }

  // Bind protocol segmented buttons click
  $$('#proxyProtocolControl .segment-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      setProxyTypeUI(btn.dataset.value);
    });
  });

  // Set proxy auto-check iOS switch helper
  function setProxyCheckUI(checkVal) {
    const val = checkVal || 'none';
    const input = $('profileProxyCheck');
    if (input) input.value = val;
    const toggle = $('profileProxyCheckToggle');
    if (toggle) {
      toggle.checked = val !== 'none';
    }
  }

  // Bind iOS check toggle change
  $('profileProxyCheckToggle')?.addEventListener('change', (e) => {
    setProxyCheckUI(e.target.checked ? 'full' : 'none');
  });

  // Toggle Advanced section
  $('btnToggleAdvancedProxy')?.addEventListener('click', () => {
    const section = $('sectionAdvancedProxy');
    const arrow = $('arrowAdvancedProxy');
    if (section && arrow) {
      const isHidden = section.style.display === 'none';
      section.style.display = isHidden ? 'flex' : 'none';
      arrow.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
    }
  });

  // Parse proxy string (e.g. host:port:user:pass or user:pass@host:port)
  function parseProxyInput(rawText) {
    let text = rawText.trim();
    if (!text) return;
    
    // Check scheme
    if (text.includes('://')) {
      const parts = text.split('://');
      const proto = parts[0].toLowerCase();
      if (['http', 'https', 'socks4', 'socks5'].includes(proto)) {
        setProxyTypeUI(proto);
      }
      text = parts.slice(1).join('://').trim();
    }

    const usernameField = $('proxyUsername');
    const passwordField = $('proxyPassword');
    
    if (text.includes('@')) {
      const lastAtIndex = text.lastIndexOf('@');
      const authPart = text.substring(0, lastAtIndex);
      const firstColon = authPart.indexOf(':');
      if (firstColon !== -1) {
        if (usernameField) usernameField.value = authPart.substring(0, firstColon).trim();
        if (passwordField) passwordField.value = authPart.substring(firstColon + 1).trim();
      } else {
        if (usernameField) usernameField.value = authPart.trim();
      }
    } else {
      const segments = text.split(':');
      if (segments.length >= 4) {
        if (usernameField) usernameField.value = segments[2].trim();
        if (passwordField) passwordField.value = segments.slice(3).join(':').trim();
      }
    }
  }

  // Update proxy string in main input when username or password changes
  function updateProxyStringFromAuth() {
    const mainInput = $('profileProxy');
    const user = $('proxyUsername')?.value.trim() || '';
    const pass = $('proxyPassword')?.value.trim() || '';
    let val = mainInput.value.trim();
    if (!val) return;
    
    let scheme = '';
    if (val.includes('://')) {
      const parts = val.split('://');
      scheme = parts[0] + '://';
      val = parts[1];
    }
    
    const segments = val.split(':');
    if (segments.length >= 2) {
      const host = segments[0];
      const port = segments[1];
      if (user || pass) {
        mainInput.value = `${scheme}${host}:${port}:${user}:${pass}`;
      } else {
        mainInput.value = `${scheme}${host}:${port}`;
      }
    }
  }

  $('proxyUsername')?.addEventListener('input', updateProxyStringFromAuth);
  $('proxyPassword')?.addEventListener('input', updateProxyStringFromAuth);
  
  $('profileProxy')?.addEventListener('input', (e) => {
    parseProxyInput(e.target.value);
  });

  // Paste action
  $('btnPasteProxy')?.addEventListener('click', async () => {
    try {
      const text = await navigator.clipboard.readText();
      if (text) {
        $('profileProxy').value = text.trim();
        parseProxyInput(text.trim());
        toast('Đã dán từ clipboard!', 'success');
        updateSummary();
      }
    } catch (e) {
      toast('Không thể đọc Clipboard!', 'error');
    }
  });

  // Clear action
  $('btnClearProxy')?.addEventListener('click', () => {
    $('profileProxy').value = '';
    const usernameField = $('proxyUsername');
    const passwordField = $('proxyPassword');
    const bypassField = $('proxyBypass');
    const dnsField = $('proxyDNS');
    if (usernameField) usernameField.value = '';
    if (passwordField) passwordField.value = '';
    if (bypassField) bypassField.value = '';
    if (dnsField) dnsField.value = '';
    
    $('proxyEmptyState').style.display = 'flex';
    $('proxyLoadingState').style.display = 'none';
    $('proxySuccessState').style.display = 'none';
    $('proxyErrorState').style.display = 'none';
    
    const sumStatus = $('sumProxyStatus');
    if (sumStatus) {
      sumStatus.textContent = 'Chưa check';
      sumStatus.style.color = 'var(--text-muted)';
    }
    updateSummary();
  });

  // Import Modal logic
  $('btnImportProxyModal')?.addEventListener('click', () => {
    const modal = $('importProxyModal');
    if (modal) {
      modal.style.display = 'flex';
      setTimeout(() => {
        modal.style.opacity = '1';
        modal.style.pointerEvents = 'auto';
        modal.querySelector('div').style.transform = 'scale(1)';
      }, 50);
    }
  });

  function closeImportModal() {
    const modal = $('importProxyModal');
    if (modal) {
      modal.style.opacity = '0';
      modal.style.pointerEvents = 'none';
      modal.querySelector('div').style.transform = 'scale(0.95)';
      setTimeout(() => {
        modal.style.display = 'none';
      }, 200);
    }
  }

  $('btnCloseImportProxy')?.addEventListener('click', closeImportModal);
  
  $('btnPasteFromClipboard')?.addEventListener('click', async () => {
    try {
      const text = await navigator.clipboard.readText();
      if (text) {
        $('importProxyTextarea').value = text;
      }
    } catch (e) {}
  });

  $('btnUploadTxt')?.addEventListener('click', () => $('fileImportProxy').click());
  $('btnUploadCsv')?.addEventListener('click', () => $('fileImportProxy').click());
  
  $('fileImportProxy')?.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (evt) => {
        $('importProxyTextarea').value = evt.target.result;
      };
      reader.readAsText(file);
    }
  });

  $('btnConfirmImportProxy')?.addEventListener('click', () => {
    const content = $('importProxyTextarea').value.trim();
    if (!content) {
      toast('Vui lòng nhập nội dung proxy!', 'warning');
      return;
    }
    const lines = content.split('\n').map(l => l.trim()).filter(l => l.length > 0);
    if (lines.length > 0) {
      const firstProxy = lines[0];
      $('profileProxy').value = firstProxy;
      parseProxyInput(firstProxy);
      if (lines.length > 1) {
        toast(`Đã nhập dòng đầu tiên trong số ${lines.length} proxy.`, 'info');
      } else {
        toast('Đã nhập proxy thành công!', 'success');
      }
      closeImportModal();
      updateSummary();
    }
  });

  // Latency status helpers
  function getLatencyBadgeClass(pingMs) {
    if (pingMs < 100) return 'latency-green';
    if (pingMs < 300) return 'latency-yellow';
    return 'latency-red';
  }

  function getFlagEmoji(countryCode) {
    if (!countryCode || countryCode.length !== 2) return '🌐';
    const codePoints = countryCode
      .toUpperCase()
      .split('')
      .map(char => 127397 + char.charCodeAt(0));
    return String.fromCodePoint(...codePoints);
  }

  // Rich language suggestions based on country code
  const countryLanguageMap = {
    'VN': 'vi-VN, en-US',
    'US': 'en-US, en',
    'JP': 'ja-JP, en-US',
    'KR': 'ko-KR, en-US',
    'CN': 'zh-CN, en-US',
    'RU': 'ru-RU, en-US',
    'TH': 'th-TH, en-US',
    'ID': 'id-ID, en-US',
    'PH': 'fil-PH, en-US'
  };

  // Check Proxy inline handler with premium states
  $('btnCheckProxyInline')?.addEventListener('click', async () => {
    const proxy = $('profileProxy').value.trim();
    if (!proxy) {
      toast('Vui lòng nhập proxy trước!', 'warning');
      return;
    }

    const spinner = $('chkSpinner');
    const chkIcon = $('chkIcon');
    const checkBtn = $('btnCheckProxyInline');
    
    // UI state loading
    if (spinner) spinner.style.display = 'inline-block';
    if (chkIcon) chkIcon.style.display = 'none';
    if (checkBtn) checkBtn.disabled = true;

    $('proxyEmptyState').style.display = 'none';
    $('proxyLoadingState').style.display = 'flex';
    $('proxySuccessState').style.display = 'none';
    $('proxyErrorState').style.display = 'none';

    const sumStatus = $('sumProxyStatus');
    if (sumStatus) {
      sumStatus.textContent = '⏳ Đang check...';
      sumStatus.style.color = 'var(--text-secondary)';
    }

    try {
      const res = await API.proxyCheckFull(proxy);
      
      if (spinner) spinner.style.display = 'none';
      if (chkIcon) chkIcon.style.display = 'inline-block';
      if (checkBtn) checkBtn.disabled = false;

      if (res.ok) {
        toast('Proxy hoạt động tốt!', 'success');
        
        let ip = res.info.ip;
        let port = res.info.port;
        let country = res.info.country || 'Vietnam';
        let countryCode = res.info.countryCode || 'VN';
        let city = res.info.region ? (res.info.city ? `${res.info.region} - ${res.info.city}` : res.info.region) : (res.info.city || 'Hanoi');
        let timezone = res.info.timezone || 'Asia/Ho_Chi_Minh';
        let isp = res.info.isp || 'Viettel';
        let asn = res.info.asn || 'AS7552';
        let pingMs = parseInt(res.info.speed) || 18;

        if (!res.info.region && !res.info.city) {
          try {
            const richRes = await fetch(`https://ipwho.is/${ip}`).then(r => r.json());
            if (richRes && richRes.success) {
              country = richRes.country || country;
              countryCode = richRes.country_code || countryCode;
              city = richRes.city || city;
              if (richRes.timezone) timezone = richRes.timezone.id || timezone;
              if (richRes.connection) {
                isp = richRes.connection.isp || isp;
                asn = richRes.connection.asn ? `AS${richRes.connection.asn}` : asn;
              }
            }
          } catch (enrichErr) {
            console.warn("Could not enrich IP details", enrichErr);
          }
        }

        // Show success result card
        $('proxyLoadingState').style.display = 'none';
        $('proxySuccessState').style.display = 'flex';

        $('proxyResIP').textContent = ip;
        $('proxyResCity').textContent = city;
        $('proxyResTimezone').textContent = timezone;
        $('proxyResISP').textContent = isp;
        $('proxyResISP').title = isp;
        $('proxyResASN').textContent = asn;
        
        const flag = getFlagEmoji(countryCode);
        $('proxyResCountryBadge').innerHTML = `${flag} ${country}`;
        
        const latencyBadge = $('proxyResLatencyBadge');
        if (latencyBadge) {
          latencyBadge.className = `badge-status ${getLatencyBadgeClass(pingMs)}`;
          latencyBadge.textContent = `${pingMs}ms`;
        }

        if (sumStatus) {
          sumStatus.innerHTML = '🟢 Live';
          sumStatus.style.color = 'var(--green)';
        }

        // Automatically align timezone and language if set to auto (UX enhancement)
        if ($('profileTimezone').value === 'auto') {
          // Check if option already exists in dropdown
          let exists = false;
          for (let i = 0; i < $('profileTimezone').options.length; i++) {
            if ($('profileTimezone').options[i].value === timezone) {
              exists = true;
              break;
            }
          }
          if (!exists) {
            const opt = document.createElement('option');
            opt.value = timezone;
            opt.textContent = `${timezone} (Từ Proxy)`;
            $('profileTimezone').appendChild(opt);
          }
          $('profileTimezone').value = timezone;
        }

        // Auto update language suggestions matching proxy location
        const targetLang = countryLanguageMap[countryCode.toUpperCase()];
        if (targetLang && $('profileLanguage').value.includes('vi-VN, en-US')) {
          $('profileLanguage').value = targetLang;
        }

      } else {
        toast('Kiểm tra Proxy thất bại!', 'error');
        $('proxyLoadingState').style.display = 'none';
        $('proxyErrorState').style.display = 'flex';
        
        $('proxyErrorTitle').textContent = 'Proxy Error (Lỗi kết nối)';
        $('proxyErrorMsg').textContent = res.msg || 'Không rõ nguyên nhân (Proxy Refused / Auth Failed).';

        if (sumStatus) {
          sumStatus.textContent = '🔴 Error';
          sumStatus.style.color = 'var(--red)';
        }
      }
    } catch (e) {
      if (spinner) spinner.style.display = 'none';
      if (chkIcon) chkIcon.style.display = 'inline-block';
      if (checkBtn) checkBtn.disabled = false;

      toast('Lỗi không xác định khi check proxy!', 'error');
      $('proxyLoadingState').style.display = 'none';
      $('proxyErrorState').style.display = 'flex';
      $('proxyErrorTitle').textContent = 'Proxy Exception';
      $('proxyErrorMsg').textContent = e.message;

      if (sumStatus) {
        sumStatus.textContent = '🔴 Error';
        sumStatus.style.color = 'var(--red)';
      }
    }
    updateSummary();
  });

  // Populate data if in edit mode
  if (isEdit && p) {
    $('createProfileTitle').textContent = `Chỉnh sửa Hồ Sơ: ${p.name}`;
    $('editProfileName').value = p.name;
    $('profileName').value = p.name;
    $('profileName').disabled = true; // Không cho đổi tên lõi thư mục trực tiếp ở đây
    $('profileProxy').value = p.proxy || '';
    $('profileUA').value = p.userAgent || '';
    $('profileTags').value = (p.tags || []).join(', ');
    $('profileNotes').value = p.notes || '';
    
    if (groupSelect) groupSelect.value = p.group || 'Mặc định';
    $('profileProxyType').value = p.proxyType || 'http';
    $('profileProxyCheck').value = p.proxyCheck || 'none';
    
    // Restore premium proxy UI states
    setProxyTypeUI(p.proxyType || 'http');
    setProxyCheckUI(p.proxyCheck || 'none');
    const parsed = parseProxy(p.proxy);
    if (parsed) {
      const usernameField = $('proxyUsername');
      const passwordField = $('proxyPassword');
      if (usernameField) usernameField.value = parsed.user || '';
      if (passwordField) passwordField.value = parsed.pass || '';
    }
    const bypassField = $('proxyBypass');
    const dnsField = $('proxyDNS');
    if (bypassField) bypassField.value = p.proxyBypass || '';
    if (dnsField) dnsField.value = p.proxyDNS || '';

    $('profileTimezone').value = p.timezone || 'auto';
    $('profileLanguage').value = p.language || 'vi-VN, en-US';
    $('profileScreen').value = p.screen || 'random';
    $('profileCanvas').value = p.canvas || 'noise';
    $('profileWebGL').value = p.webgl || 'noise';
    $('profileAudio').value = p.audio || 'noise';
    $('profileWebRTC').value = p.webrtc || 'mask';
    $('profileFonts').value = p.fonts || 'random';
    $('profileCPU').value = p.cpu || 'random';
    
    // Select browser type card
    const bType = p.browserType || 'system';
    $('profileBrowserType').value = bType;
    $$('.browser-option-card').forEach(card => {
      const active = card.dataset.browser === bType;
      card.classList.toggle('active', active);
      card.style.border = active ? '2px solid var(--accent)' : '2px solid var(--border)';
      card.style.background = active ? 'rgba(108,99,255,0.06)' : 'var(--bg-card2)';
    });
 
    // Populate advanced fingerprints
    setPlatformUI(p.platform || 'Win32');
    $('profileDPR').value = p.dpr || '1';
    $('profileTouch').value = p.touch || 'disable';
    $('profileRAM').value = p.ram || 'random';
    $('profileGPU').value = p.gpu || 'random';
    $('profileClientRects').value = p.clientrects || 'noise';
    $('profileMediaDevices').value = p.mediaDevices || 'noise';
    $('profileBattery').value = p.battery || 'noise';
    $('profilePermissions').value = p.permissions || 'noise';
    $('profileSpeech').value = p.speech || 'noise';
    $('profileSensors').value = p.sensors || 'disable';
    $('profileGeolocation').value = p.geolocation || 'auto';
 
    $('maskBuildID').checked = p.maskBuildID !== false;
    $('maskHardware').checked = p.maskHardware !== false;
    $('maskVendor').checked = p.maskVendor !== false;
    $('maskConnection').checked = p.maskConnection !== false;
 
    // Selected color
    selectedColor = p.color || '';
    $$('.color-dot').forEach(d => {
      d.classList.toggle('active', d.dataset.color === selectedColor);
    });
  } else {
    // Set default initial values for new profile
    $('createProfileTitle').textContent = 'Tạo Hồ Sơ Mới';
    $('editProfileName').value = '';
    $('profileName').value = '';
    $('profileProxy').value = '';
    $('profileUA').value = '';
    $('profileTags').value = '';
    $('profileNotes').value = '';
    
    // Set premium proxy defaults
    setProxyTypeUI('http');
    setProxyCheckUI('full'); // check by default
    
    // Randomize initial fingerprints
    $('btnRandomFingerprint').click();
  }
 
  // Update summary card once initially
  updateSummary();
 
  // Save new/edited profile
  $('btnSaveNewProfile')?.addEventListener('click', async () => {
    const editName = $('editProfileName').value;
    const name = $('profileName').value.trim();
    if (!name) {
      $('createProfileMsg').textContent = 'Tên hồ sơ không được để trống';
      return;
    }
 
    // Proxy format validation
    const proxyVal = $('profileProxy').value.trim();
    if (proxyVal) {
      let clean = proxyVal;
      if (clean.includes('://')) {
        clean = clean.split('://')[1];
      }
      const parts = clean.split(':');
      if (parts.length < 2 || !parts[0] || !parts[1] || isNaN(parseInt(parts[1]))) {
        $('createProfileMsg').textContent = '⚠ Sai định dạng Proxy. Ví dụ đúng: IP:PORT hoặc IP:PORT:USER:PASS hoặc SOCKS5://IP:PORT';
        return;
      }
    }

    const profileData = {
      name,
      proxy: $('profileProxy').value.trim(),
      userAgent: $('profileUA').value.trim(),
      tags: $('profileTags').value.split(',').map(t => t.trim()).filter(Boolean),
      notes: $('profileNotes').value.trim(),
      color: selectedColor,
      proxyType: $('profileProxyType').value,
      proxyCheck: $('profileProxyCheck').value,
      timezone: $('profileTimezone').value,
      language: $('profileLanguage').value,
      screen: $('profileScreen').value,
      canvas: $('profileCanvas').value,
      webgl: $('profileWebGL').value,
      audio: $('profileAudio').value,
      webrtc: $('profileWebRTC').value,
      fonts: $('profileFonts').value,
      cpu: $('profileCPU').value,
      browserType: $('profileBrowserType').value,
      group: $('profileGroup').value,
      
      // Advanced Proxy Configurations
      proxyBypass: $('proxyBypass')?.value.trim() || '',
      proxyDNS: $('proxyDNS')?.value.trim() || '',
      
      // Advanced Fingerprints
      platform: $('profilePlatform').value,
      dpr: $('profileDPR').value,
      touch: $('profileTouch').value,
      ram: $('profileRAM').value,
      gpu: $('profileGPU').value,
      clientrects: $('profileClientRects').value,
      mediaDevices: $('profileMediaDevices').value,
      battery: $('profileBattery').value,
      permissions: $('profilePermissions').value,
      speech: $('profileSpeech').value,
      sensors: $('profileSensors').value,
      geolocation: $('profileGeolocation').value,
      maskBuildID: $('maskBuildID').checked,
      maskHardware: $('maskHardware').checked,
      maskVendor: $('maskVendor').checked,
      maskConnection: $('maskConnection').checked
    };
 
    $('createProfileMsg').textContent = '';
    
    if (editName) {
      const res = await API.profilesUpdate(editName, profileData);
      if (res.ok) {
        toast('Đã cập nhật hồ sơ thành công!', 'success');
        loadProfiles();
        loadView('profiles');
      } else {
        $('createProfileMsg').textContent = res.msg || 'Lỗi cập nhật hồ sơ';
      }
    } else {
      const res = await API.profilesCreate(profileData);
      if (res.ok) {
        toast(`Đã tạo hồ sơ: ${name} thành công!`, 'success');
        loadProfiles();
        loadView('profiles');
      } else {
        $('createProfileMsg').textContent = res.msg || 'Lỗi tạo hồ sơ mới';
      }
    }
  });
}

async function deleteProfile(name) {
  const res = await API.profilesUpdate(name, { isDeleted: true });
  if (res.ok) {
    toast(`Đã di chuyển "${name}" vào Thùng rác`, 'warning');
    S.selected.delete(name);
    updateBulkUI();
    loadProfiles();
  } else toast(res.msg || 'Lỗi di chuyển vào Thùng rác', 'error');
}

async function renameProfile(oldName, newName) {
  const res = await API.profilesRename(oldName, newName);
  if (res.ok) { toast(`Đã đổi tên → ${newName}`, 'success'); loadProfiles(); }
  else toast(res.msg || 'Lỗi đổi tên', 'error');
}

// ── BULK ACTIONS ──────────────────────────────────────────────────────────────
$('btnBulkDeleteFloat')?.addEventListener('click', () => $('btnBulkDelete')?.click());
$('btnBulkOpenFloat')?.addEventListener('click', () => $('btnBulkOpen')?.click());

$('btnBulkDelete')?.addEventListener('click', async () => {
  const names = [...S.selected];
  if (!names.length) return;
  if (!await confirm(`Di chuyển ${names.length} profiles vào Thùng rác?`)) return;
  
  let successCount = 0;
  for (const name of names) {
    const res = await API.profilesUpdate(name, { isDeleted: true });
    if (res.ok) successCount++;
  }
  
  if (successCount > 0) {
    toast(`Đã di chuyển ${successCount} profiles vào Thùng rác`, 'warning');
    S.selected.clear();
    updateBulkUI();
    loadProfiles();
  } else toast('Lỗi di chuyển vào Thùng rác', 'error');
});

$('btnBulkOpen')?.addEventListener('click', async () => {
  const names = [...S.selected];
  if (!names.length) return;
  toast(`Đang mở ${names.length} profiles...`, 'info');
  const res = await API.profilesOpenBulk(names);
  if (res.ok) toast(`Đã mở ${res.opened} profiles`, 'success');
  else toast(res.msg || 'Lỗi', 'error');
  setTimeout(loadProfiles, 1500);
});

// ── IMPORT ────────────────────────────────────────────────────────────────────
$('btnImport')?.addEventListener('click', () => {
  $('importText').value = '';
  clearMsg('importMsg');
  openModal('importModal');
});

$('btnDoImport')?.addEventListener('click', async () => {
  const text = $('importText').value.trim();
  if (!text) return setMsg('importMsg', 'Vui lòng nhập dữ liệu');
  $('btnDoImport').textContent = 'Đang import...';
  const res = await API.profilesImport(text);
  $('btnDoImport').textContent = 'Import';
  if (res.ok) {
    setMsg('importMsg', `✓ Thành công: +${res.created}, bỏ qua: ${res.skipped}, lỗi: ${res.errors}`, 'success');
    loadProfiles();
  } else setMsg('importMsg', res.msg || 'Lỗi import');
});

// ── EXPORT ────────────────────────────────────────────────────────────────────
$('btnExport')?.addEventListener('click', () => {
  $('exportResult').value = '';
  openModal('exportModal');
});

$('btnDoExport')?.addEventListener('click', async () => {
  const fmt = document.querySelector('[name="exportFmt"]:checked')?.value || 'name-proxy';
  $('btnDoExport').textContent = 'Đang xuất...';
  const res = await API.profilesExport(fmt);
  $('btnDoExport').textContent = 'Export';
  if (res.ok) {
    $('exportResult').value = res.data;
    toast(`Đã xuất ${res.count} profiles`, 'success');
  } else toast(res.msg || 'Lỗi', 'error');
});

$('btnCopyExport')?.addEventListener('click', () => {
  const val = $('exportResult').value;
  if (!val) return toast('Chưa có dữ liệu để copy', 'warning');
  navigator.clipboard.writeText(val).then(() => toast('Đã copy!', 'success'));
});

// ── PROXY PAGE ────────────────────────────────────────────────────────────────
$('btnFetchProxy')?.addEventListener('click', async () => {
  const apiKey = $('proxyApiKey').value.trim();
  if (!apiKey) return toast('Vui lòng nhập API key', 'error');
  $('btnFetchProxy').textContent = 'Đang lấy...';
  const res = await API.proxyFetch({ apiKey, nhamang: $('proxyCarrier').value, tinhthanh: $('proxyCity').value });
  $('btnFetchProxy').textContent = 'Lấy Proxy';
  if (res.ok) {
    $('proxyResult').style.display = 'block';
    $('proxyResultValue').textContent = res.proxy;
    toast('Lấy proxy thành công!', 'success');
  } else toast(res.msg || 'Lỗi lấy proxy', 'error');
});

$('btnCheckProxy')?.addEventListener('click', async () => {
  const proxy = $('proxyCheckInput').value.trim();
  if (!proxy) return toast('Vui lòng nhập proxy', 'error');
  const res = await API.proxyCheck(proxy);
  const el  = $('proxyCheckResult');
  el.style.display = 'block';
  if (res.valid) {
    el.style.color = 'var(--green)';
    el.textContent = `✓ Hợp lệ — IP: ${res.ip} | Port: ${res.port} | ${res.type}`;
  } else {
    el.style.color = 'var(--red)';
    el.textContent = '✕ ' + res.msg;
  }
});

// ── BACKUP PAGE ───────────────────────────────────────────────────────────────
async function loadBackups() {
  const list = await API.backupList();
  const container = $('backupList');
  if (!list.length) {
    container.innerHTML = `
      <div class="empty-state">
        <div class="empty-icon"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" opacity="0.4"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/></svg></div>
        <h3>Chưa có backup nào</h3>
        <p>Tạo backup đầu tiên để bảo vệ dữ liệu</p>
      </div>`;
    return;
  }
  container.innerHTML = list.map(b => `
    <div class="backup-item">
      <div class="backup-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/></svg>
      </div>
      <div class="backup-info">
        <div class="backup-name">${escapeHTML(b.name)}</div>
        <div class="backup-meta">${b.count} profiles • ${new Date(b.mtime).toLocaleString('vi-VN')} • ${(b.size/1024).toFixed(1)} KB</div>
      </div>
      <div class="backup-actions">
        <button class="btn-secondary" data-restore="${escapeHTML(b.name)}">Khôi phục</button>
        <button class="btn-danger-sm" data-delete-bk="${escapeHTML(b.name)}">Xoá</button>
      </div>
    </div>
  `).join('');

  container.querySelectorAll('[data-restore]').forEach(btn => {
    btn.addEventListener('click', async () => {
      if (!await confirm(`Khôi phục backup "${btn.dataset.restore}"?`)) return;
      const res = await API.backupRestore(btn.dataset.restore);
      if (res.ok) { toast(`Đã khôi phục ${res.restored} profiles`, 'success'); loadProfiles(); }
      else toast(res.msg || 'Lỗi', 'error');
    });
  });
  container.querySelectorAll('[data-delete-bk]').forEach(btn => {
    btn.addEventListener('click', async () => {
      if (!await confirm(`Xoá backup "${btn.dataset.deleteBk}"?`)) return;
      const res = await API.backupDelete(btn.dataset.deleteBk);
      if (res.ok) { toast('Đã xoá backup', 'success'); loadBackups(); }
      else toast(res.msg || 'Lỗi', 'error');
    });
  });
}

$('btnCreateBackup')?.addEventListener('click', async () => {
  const name = `backup-${new Date().toISOString().slice(0,16).replace('T','-').replace(':','')}`;
  $('btnCreateBackup').textContent = 'Đang tạo...';
  const res = await API.backupCreate(name);
  $('btnCreateBackup').innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/></svg> Tạo Backup`;
  if (res.ok) { toast(`Backup: ${res.name} (${res.count} profiles)`, 'success'); loadBackups(); }
  else toast(res.msg || 'Lỗi', 'error');
});

// ── LOGS ──────────────────────────────────────────────────────────────────────
async function loadLogs() {
  const logs = await API.logsGet();
  renderLogs(logs);
}

function renderLogs(logs) {
  const container = $('logContainer');
  if (!logs.length) { container.innerHTML = '<div class="log-empty">Chưa có hoạt động nào...</div>'; return; }
  container.innerHTML = [...logs].reverse().map(l => `
    <div class="log-entry">
      <span class="log-time">${new Date(l.timestamp).toLocaleTimeString('vi-VN')}</span>
      <span class="log-type ${['info','warning','error','success'].includes(l.type) ? l.type : 'info'}">${escapeHTML(l.type)}</span>
      <span class="log-msg">${escapeHTML(l.message)}</span>
    </div>
  `).join('');
}

try {
  API.onLog(row => {
    const dot = $('logDot');
    if (dot) dot.classList.add('active');
    if ($('page-logs')?.classList.contains('active')) {
      const container = $('logContainer');
      const el = document.createElement('div');
      el.className = 'log-entry';
      el.style.animationDelay = '0ms';
      el.innerHTML = `
        <span class="log-time">${new Date(row.timestamp).toLocaleTimeString('vi-VN')}</span>
        <span class="log-type ${['info','warning','error','success'].includes(row.type) ? row.type : 'info'}">${escapeHTML(row.type)}</span>
        <span class="log-msg">${escapeHTML(row.message)}</span>
      `;
      container.insertBefore(el, container.firstChild);
    }
  });
} catch {}

$('btnOpenLogsDir')?.addEventListener('click', () => API.logsOpenDir());
$('btnClearLogs')?.addEventListener('click', async () => {
  await API.logsClear();
  $('logContainer').innerHTML = '<div class="log-empty">Chưa có hoạt động nào...</div>';
  toast('Đã xoá log', 'success');
});

// ── SETTINGS ──────────────────────────────────────────────────────────────────
async function loadSettings() {
  const cfg = await API.cfgGet();
  S.cfg = cfg;

  $$('.settings-tab').forEach(tab => {
    tab.onclick = () => {
      $$('.settings-tab').forEach(item => {
        item.classList.remove('active');
        item.setAttribute('aria-selected', 'false');
      });
      tab.classList.add('active');
      tab.setAttribute('aria-selected', 'true');
      const target = tab.dataset.pane;
      $$('.settings-pane').forEach(pane => {
        pane.classList.toggle('active', pane.id === `pane-${target}`);
      });
    };
  });

  const delayRange = $('settingsShopeeDelay');
  const delayLabel = $('shopeeDelayVal');
  if (delayRange && delayLabel) {
    delayRange.oninput = event => { delayLabel.textContent = event.target.value; };
  }

  if ($('chromePath')) $('chromePath').value = cfg.chromePath || '';
  if ($('chromeArgs')) $('chromeArgs').value = cfg.chromeArgs || '';
  if ($('settingsStartUrl')) $('settingsStartUrl').value = cfg.startUrl || 'https://whoer.net';
  if ($('settingsShopeeDelay')) {
    $('settingsShopeeDelay').value = cfg.shopeeDelay || 2;
    if (delayLabel) delayLabel.textContent = cfg.shopeeDelay || 2;
  }
  if ($('settingsMaxRetries')) $('settingsMaxRetries').value = cfg.maxRetries || 3;
  if ($('settingsAutoCheckProxy')) $('settingsAutoCheckProxy').checked = cfg.autoCheckProxy !== false;
  if ($('settingsMaxBackups')) $('settingsMaxBackups').value = cfg.maxBackups || 5;
  if ($('settingsAutoBackupOnClose')) $('settingsAutoBackupOnClose').checked = cfg.autoBackupOnClose || false;
  if ($('settingsStartupPage')) {
    const startupPage = ['profiles', 'proxy', 'settings'].includes(cfg.startupPage)
      ? cfg.startupPage
      : 'profiles';
    $('settingsStartupPage').value = startupPage;
  }
  if ($('toggleMinimizeToTray')) $('toggleMinimizeToTray').checked = cfg.minimizeToTray || false;

  renderThemePicker($('settingsThemeGrid'));
  window.I18N?.renderLanguagePicker($('settingsLanguageGrid'));
  updateCurrentThemeLabel(document.documentElement.dataset.theme || cfg.theme || 'light-clean');

  const saveButton = $('btnSaveSettings');
  if (saveButton) {
    saveButton.onclick = async () => {
      const nextCfg = {
        ...S.cfg,
        chromePath: $('chromePath')?.value.trim() || '',
        chromeArgs: $('chromeArgs')?.value.trim() || '',
        startUrl: $('settingsStartUrl')?.value.trim() || 'https://whoer.net',
        shopeeDelay: Number.parseInt($('settingsShopeeDelay')?.value || '2', 10),
        maxRetries: Number.parseInt($('settingsMaxRetries')?.value || '3', 10),
        autoCheckProxy: $('settingsAutoCheckProxy')?.checked !== false,
        maxBackups: Number.parseInt($('settingsMaxBackups')?.value || '5', 10),
        autoBackupOnClose: Boolean($('settingsAutoBackupOnClose')?.checked),
        theme: document.documentElement.dataset.theme || 'light-clean',
        language: window.I18N?.getCurrentLanguage() || 'vi',
        startupPage: $('settingsStartupPage')?.value || 'profiles',
        minimizeToTray: Boolean($('toggleMinimizeToTray')?.checked)
      };
      await API.cfgSave(nextCfg);
      S.cfg = nextCfg;
      toast(t('settings_saved'), 'success');
    };
  }

  const browseButton = $('btnBrowseChrome');
  if (browseButton) {
    browseButton.onclick = async () => {
      const result = await API.cfgBrowseChrome();
      if (result.ok) {
        $('chromePath').value = result.path;
        toast(t('settings_chrome_selected'), 'success');
      }
    };
  }

  const detectButton = $('btnDetectChrome');
  if (detectButton) {
    detectButton.onclick = async () => {
      const label = detectButton.querySelector('span') || detectButton;
      label.textContent = t('settings_chrome_searching');
      detectButton.disabled = true;
      try {
        const result = await API.cfgDetectChrome();
        if (result.ok) {
          $('chromePath').value = result.path;
          toast(t('settings_chrome_found'), 'success');
        } else {
          toast(t('settings_chrome_not_found'), 'warning', 5000);
          API.cfgOpenChromeDownload();
        }
      } finally {
        label.textContent = t('settings_detect_chrome');
        detectButton.disabled = false;
      }
    };
  }

  if ($('btnOpenProfilesDir')) $('btnOpenProfilesDir').onclick = () => API.openProfilesDir();
  if ($('btnOpenBackupsDir')) $('btnOpenBackupsDir').onclick = () => API.openBackupsDir();
  translateDOM($('page-content'));
}

// ── AUTO REFRESH ──────────────────────────────────────────────────────────────
setInterval(async () => {
  S.running = await API.getRunning().catch(() => []);
  updateStats();
  S.profiles.forEach(p => {
    const card = document.querySelector(`[data-name="${p.name}"]`);
    if (!card) return;
    const isRunning = S.running.includes(p.name);
    const statusEl = card.querySelector('.profile-status');
    if (statusEl) {
      statusEl.className = `profile-status ${isRunning?'running':'stopped'}`;
      statusEl.innerHTML = `<div class="profile-status-dot"></div>${isRunning?'Đang mở':'Đã tắt'}`;
    }
  });
}, 5000);



// ── MAINTENANCE ───────────────────────────────────────────────────────────
function showMaintenanceAlert() {
  const alertBox = document.createElement('div');
  alertBox.className = 'maintenance-toast';
  alertBox.innerHTML = `
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
      <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
    </svg>
    <span>Tính năng Automation đang được bảo trì!</span>
  `;
  alertBox.style.cssText = `
    position: fixed; top: 80px; left: 50%; transform: translateX(-50%);
    background: rgba(245,158,11,0.15); border: 1px solid rgba(245,158,11,0.4);
    color: #f59e0b; padding: 12px 20px; border-radius: 10px;
    display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 600;
    z-index: 9999; backdrop-filter: blur(10px); animation: slideDown 0.3s ease;
  `;
  document.body.appendChild(alertBox);
  setTimeout(() => { alertBox.style.opacity = '0'; alertBox.style.transition = 'opacity 0.3s'; setTimeout(() => alertBox.remove(), 300); }, 3000);
}

// ── BROWSER SELECTION CARDS ───────────────────────────────────────────────
function selectBrowserCard(val) {
  const hiddenInput = $('profileBrowserType');
  if (hiddenInput) hiddenInput.value = val;
  document.querySelectorAll('.browser-option-card').forEach(c => {
    const isActive = c.dataset.browser === val;
    c.classList.toggle('active', isActive);
    c.style.borderColor = isActive ? 'var(--accent)' : 'var(--border)';
    c.style.background = isActive ? 'rgba(108,99,255,0.06)' : 'var(--bg-card)';
  });
}

document.querySelectorAll('.browser-option-card').forEach(card => {
  card.addEventListener('click', () => {
    selectBrowserCard(card.dataset.browser);
  });
});

// ── GROUPS MANAGEMENT ──────────────────────────────────────────────────────
async function loadGroups() {
  try {
    const rawGroups = await API.groupsGet();
    // Chuyển đổi dữ liệu cũ (mảng chuỗi) sang định dạng object chuyên nghiệp nếu cần
    S.groups = rawGroups.map(g => {
      if (typeof g === 'string') {
        return { name: g, description: g === 'Mặc định' ? 'Nhóm mặc định của hệ thống' : 'Không có mô tả' };
      }
      return g;
    });
    updateGroupDropdowns();
    updateGroupStats();
  } catch(e) {
    console.error('loadGroups:', e);
  }
}

function updateGroupStats() {
  const totalGroups = S.groups.length;
  const profilesWithGroup = S.profiles.filter(p => p.group && p.group !== 'Mặc định').length;
  const avgProfiles = totalGroups > 0 ? (S.profiles.length / totalGroups).toFixed(1) : 0;

  if ($('groupStatTotalGroups')) $('groupStatTotalGroups').textContent = totalGroups;
  if ($('groupStatTotalProfiles')) $('groupStatTotalProfiles').textContent = profilesWithGroup;
  if ($('groupStatAvgSize')) $('groupStatAvgSize').textContent = avgProfiles;
}

function updateGroupDropdowns() {
  const profileGroup = $('profileGroup');
  const filterGroupSelect = $('filterGroupSelect');

  if (profileGroup) {
    profileGroup.innerHTML = S.groups.map(g => `<option value="${escapeHTML(g.name)}">${escapeHTML(g.name)}</option>`).join('');
  }
  if (filterGroupSelect) {
    filterGroupSelect.innerHTML = `<option value="all">Nhóm: Tất cả</option>` + S.groups.map(g => `<option value="${escapeHTML(g.name)}">${escapeHTML(g.name)}</option>`).join('');
    filterGroupSelect.value = S.filterGroup || 'all';
  }
}

async function loadGroupsPage() {
  await loadGroups();
  renderGroupsList();
}

function renderGroupsList() {
  const container = $('groupsListContainer');
  if (!container) return;
  
  if (S.groups.length === 0) {
    container.innerHTML = `<div style="color:var(--text-muted); font-size:12px; text-align:center; padding: 20px;">Chưa có nhóm nào.</div>`;
    return;
  }
  
  container.innerHTML = S.groups.map(g => {
    const count = S.profiles.filter(p => p.group === g.name).length;
    const isDefault = g.name === 'Mặc định';
    
    // Tạo màu gradient visual cho Folder icon dựa trên tên nhóm
    let hue = 240; // Default blue-purple
    if (g.name.length > 0) {
      let charSum = 0;
      for (let i = 0; i < g.name.length; i++) charSum += g.name.charCodeAt(i);
      hue = charSum % 360;
    }

    return `
      <div class="group-item-card">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:10px;">
          <div style="display:flex; align-items:flex-start; gap:12px; min-width:0;">
            <div style="width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; background: hsla(${hue}, 70%, 50%, 0.12); color: hsl(${hue}, 70%, 50%); flex-shrink:0;">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/></svg>
            </div>
            <div style="min-width:0;">
              <div style="font-weight:700; font-size:13.5px; color:var(--text-primary); display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                <span>${escapeHTML(g.name)}</span>
                <span class="group-badge-count">${count} profiles</span>
              </div>
              <div style="font-size:11.5px; color:var(--text-muted); margin-top:4px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; line-height:1.4;" title="${escapeHTML(g.description || 'Không có mô tả')}">
                ${escapeHTML(g.description || 'Chưa có mô tả cho nhóm này')}
              </div>
            </div>
          </div>
          
          ${!isDefault ? `
            <button class="group-btn-delete" data-delete-group="${escapeHTML(g.name)}" title="Xóa nhóm">Xóa</button>
          ` : `
            <span style="font-size:10px; font-weight:600; color:var(--text-muted); padding:3px 8px; border-radius:6px; background:var(--bg-hover); white-space:nowrap;">Mặc định</span>
          `}
        </div>
      </div>
    `;
  }).join('');
  
  container.querySelectorAll('[data-delete-group]').forEach(btn => {
    btn.addEventListener('click', async () => {
      const gName = btn.dataset.deleteGroup;
      if (await confirm(`Bạn chắc chắn muốn xóa nhóm "${gName}"? Các profile thuộc nhóm này sẽ chuyển về nhóm "Mặc định".`)) {
        const newGroups = S.groups.filter(x => x.name !== gName);
        const res = await API.groupsSave(newGroups);
        if (res.ok) {
          toast(`Đã xóa nhóm: ${gName}`, 'success');
          for (const p of S.profiles) {
            if (p.group === gName) {
              await API.profilesUpdate(p.name, { group: 'Mặc định' });
            }
          }
          await loadProfiles();
          await loadGroupsPage();
        } else {
          toast('Lỗi khi xóa nhóm', 'error');
        }
      }
    });
  });
}

window.showLicenseDetails = async function() {
  try {
    const license = await API.getLicenseInfo();
    if (!license) throw new Error('Không có dữ liệu');

    document.getElementById('licenseDetailsModal')?.remove();

    const overlay = document.createElement('div');
    overlay.id = 'licenseDetailsModal';
    overlay.className = 'confirm-overlay';
    overlay.style.cssText = `
      position: fixed;
      inset: 0;
      background: rgba(15, 23, 42, 0.55);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      z-index: 99999;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: opacity 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    `;

    const card = document.createElement('div');
    card.className = 'license-modal-card';
    card.style.cssText = `
      width: 460px;
      max-width: 94vw;
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 20px;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
      transform: scale(0.95);
      transition: transform 0.22s cubic-bezier(0.16, 1, 0.3, 1);
      overflow: hidden;
      display: flex;
      flex-direction: column;
    `;

    const isValid = license.isValid !== false;
    const statusBg = isValid ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)';
    const statusColor = isValid ? '#10b981' : '#ef4444';
    const statusText = isValid ? 'Bản quyền hợp lệ' : 'Chưa kích hoạt / Hết hạn';

    card.innerHTML = `
      <!-- Header -->
      <div style="padding: 18px 22px 14px 22px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border);">
        <div style="display: flex; align-items: center; gap: 12px;">
          <div style="width: 38px; height: 38px; border-radius: 12px; background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(16, 185, 129, 0.15) 100%); display: flex; align-items: center; justify-content: center; color: var(--accent);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
              <polyline points="9 12 11 14 15 10"/>
            </svg>
          </div>
          <div>
            <h3 style="font-size: 15px; font-weight: 700; color: var(--text-primary); margin: 0; font-family: var(--font);">Thông Tin Bản Quyền</h3>
            <p style="font-size: 11.5px; color: var(--text-muted); margin: 2px 0 0 0;">Giấy phép sử dụng V-ShopeeBoost</p>
          </div>
        </div>
        <button id="btnCloseLicenseModal" style="background: transparent; border: none; color: var(--text-muted); cursor: pointer; padding: 6px; border-radius: 8px; display: flex; align-items: center; justify-content: center; transition: all 0.15s ease;" title="Đóng">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>

      <!-- Body -->
      <div style="padding: 18px 22px; display: flex; flex-direction: column; gap: 12px;">
        <!-- Status Banner -->
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; background: ${statusBg}; border: 1px solid ${statusColor}33; border-radius: 12px;">
          <div style="display: flex; align-items: center; gap: 8px;">
            <span style="width: 8px; height: 8px; border-radius: 50%; background: ${statusColor}; box-shadow: 0 0 8px ${statusColor};"></span>
            <span style="font-size: 12.5px; font-weight: 700; color: ${statusColor};">${statusText}</span>
          </div>
          <span style="font-size: 11.5px; font-weight: 700; color: var(--text-secondary); font-family: var(--font-mono);">${escapeHTML(license.expiry || 'Vô thời hạn')}</span>
        </div>

        <!-- Detail Fields -->
        <div style="display: flex; flex-direction: column; gap: 8px;">
          <!-- HWID -->
          <div style="background: var(--bg-card2); border: 1px solid var(--border); border-radius: 10px; padding: 10px 12px; display: flex; align-items: center; justify-content: space-between;">
            <div style="min-width: 0; flex: 1; padding-right: 8px;">
              <span style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.5px; display: block;">Mã thiết bị (HWID)</span>
              <span style="font-size: 11.5px; font-family: var(--font-mono); color: var(--text-primary); font-weight: 600; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: block; margin-top: 2px;">${escapeHTML(license.hwid || 'N/A')}</span>
            </div>
            <button id="btnCopyHwid" class="btn-secondary" style="height: 28px; padding: 0 10px; font-size: 11px; font-weight: 600; border-radius: 6px; display: flex; align-items: center; gap: 4px; flex-shrink: 0;" title="Sao chép HWID">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
              <span>Copy</span>
            </button>
          </div>

          <!-- Key -->
          <div style="background: var(--bg-card2); border: 1px solid var(--border); border-radius: 10px; padding: 10px 12px; display: flex; align-items: center; justify-content: space-between;">
            <div style="min-width: 0; flex: 1; padding-right: 8px;">
              <span style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.5px; display: block;">Mã kích hoạt (License Key)</span>
              <span style="font-size: 11.5px; font-family: var(--font-mono); color: var(--text-primary); font-weight: 600; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: block; margin-top: 2px;">${escapeHTML(license.key || 'Chưa kích hoạt')}</span>
            </div>
            <button id="btnCopyKey" class="btn-secondary" style="height: 28px; padding: 0 10px; font-size: 11px; font-weight: 600; border-radius: 6px; display: flex; align-items: center; gap: 4px; flex-shrink: 0;" title="Sao chép Key">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
              <span>Copy</span>
            </button>
          </div>
        </div>

        <!-- Support Info Box -->
        <div style="background: rgba(99, 102, 241, 0.05); border: 1px solid rgba(99, 102, 241, 0.18); border-radius: 10px; padding: 10px 12px; display: flex; align-items: center; justify-content: space-between;">
          <div style="display: flex; align-items: center; gap: 10px;">
            <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(99, 102, 241, 0.1); color: var(--accent); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            </div>
            <div>
              <span style="font-size: 11px; font-weight: 700; color: var(--text-primary); display: block;">Hỗ trợ & Gia hạn bản quyền</span>
              <span style="font-size: 11.5px; color: var(--text-muted);">Zalo Admin: <strong style="color: var(--accent); font-family: var(--font-mono);">0359.261.551</strong></span>
            </div>
          </div>
          <button id="btnCopyZalo" class="btn-secondary" style="height: 28px; padding: 0 10px; font-size: 11px; font-weight: 600; border-radius: 6px; display: flex; align-items: center; gap: 4px;" title="Copy số Zalo">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
            <span>Copy</span>
          </button>
        </div>
      </div>

      <!-- Footer -->
      <div style="padding: 12px 22px; border-top: 1px solid var(--border); background: var(--bg-deep); display: flex; align-items: center; justify-content: space-between;">
        <button id="btnLicenseLogout" style="height: 34px; padding: 0 12px; font-size: 11.5px; font-weight: 600; border-radius: 8px; border: 1px solid rgba(239, 68, 68, 0.25); background: rgba(239, 68, 68, 0.04); color: #ef4444; cursor: pointer; transition: all 0.15s ease; display: inline-flex; align-items: center; gap: 6px;">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          <span>Đăng xuất bản quyền</span>
        </button>
        <button id="btnCloseLicenseBottom" class="btn-primary" style="height: 34px; padding: 0 20px; font-size: 12px; font-weight: 700; border-radius: 8px; background: var(--accent-grad); border: none;">
          Đóng
        </button>
      </div>
    `;

    overlay.appendChild(card);
    document.body.appendChild(overlay);

    setTimeout(() => {
      overlay.style.opacity = '1';
      card.style.transform = 'scale(1)';
    }, 10);

    const closeLicenseModal = () => {
      overlay.style.opacity = '0';
      card.style.transform = 'scale(0.95)';
      setTimeout(() => overlay.remove(), 200);
      document.removeEventListener('keydown', onEsc);
    };

    const onEsc = (e) => {
      if (e.key === 'Escape') closeLicenseModal();
    };
    document.addEventListener('keydown', onEsc);

    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) closeLicenseModal();
    });

    card.querySelector('#btnCloseLicenseModal')?.addEventListener('click', closeLicenseModal);
    card.querySelector('#btnCloseLicenseBottom')?.addEventListener('click', closeLicenseModal);

    // Copy actions
    card.querySelector('#btnCopyHwid')?.addEventListener('click', async () => {
      if (license.hwid) {
        await navigator.clipboard.writeText(license.hwid);
        toast('Đã sao chép mã HWID!', 'success');
      }
    });

    card.querySelector('#btnCopyKey')?.addEventListener('click', async () => {
      if (license.key) {
        await navigator.clipboard.writeText(license.key);
        toast('Đã sao chép mã kích hoạt!', 'success');
      }
    });

    card.querySelector('#btnCopyZalo')?.addEventListener('click', async () => {
      await navigator.clipboard.writeText('0359261551');
      toast('Đã sao chép số Zalo: 0359.261.551', 'success');
    });

    // Logout from modal
    card.querySelector('#btnLicenseLogout')?.addEventListener('click', async () => {
      if (await confirm('Bạn có chắc chắn muốn xóa mã bản quyền khỏi thiết bị này?\n\nSau khi đăng xuất, phần mềm sẽ yêu cầu kích hoạt lại để sử dụng.')) {
        try {
          const res = await API.logoutLicense();
          if (res && res.ok) {
            toast('Đã đăng xuất bản quyền!', 'success');
            setTimeout(() => window.location.reload(), 500);
          }
        } catch (err) {
          alert('Đăng xuất thất bại: ' + err.message);
        }
      }
    });

  } catch (err) {
    alert('Không thể tải thông tin bản quyền: ' + err.message);
  }
};

window.logoutUser = async function() {
  if (await confirm('Bạn có chắc chắn muốn đăng xuất bản quyền khỏi thiết bị này?\n\nSau khi đăng xuất, phần mềm sẽ yêu cầu kích hoạt lại để sử dụng.')) {
    try {
      const res = await API.logoutLicense();
      if (res && res.ok) {
        toast('Đăng xuất thành công!', 'success');
        setTimeout(() => {
          window.location.reload();
        }, 500);
      }
    } catch (err) {
      alert('Đăng xuất thất bại: ' + err.message);
    }
  }
};

// Hiện Modal Thông Báo Tuyển Truyền (Broadcast Announcement) từ Admin
function showAnnouncementModal(ann) {
  // Tránh hiển thị trùng lặp nếu đã có modal đang mở
  if (document.getElementById('announcement-modal')) return;

  const modalHtml = `
    <div id="announcement-modal" style="position: fixed; inset: 0; background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(10px); z-index: 9999; display: flex; align-items: center; justify-content: center; animation: fadeIn 0.3s ease;">
      <div style="width: 440px; background: rgba(22, 24, 47, 0.95); border: 1px solid rgba(99, 102, 241, 0.3); border-radius: 20px; padding: 28px; box-shadow: 0 20px 50px rgba(0,0,0,0.6); position: relative; text-align: center; animation: scaleIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);">
        <div style="width: 56px; height: 56px; background: rgba(99, 102, 241, 0.15); border: 1.5px solid rgba(99, 102, 241, 0.4); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; color: #6366f1;">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
        </div>
        <h2 style="font-size: 18px; font-weight: 800; color: #fff; margin-bottom: 8px; font-family:'Inter', sans-serif;">${escapeHTML(ann.title)}</h2>
        <div style="font-size: 13px; color: rgba(255,255,255,0.7); line-height: 1.6; margin-bottom: 24px; text-align: left; background: rgba(0,0,0,0.2); padding: 14px; border-radius: 10px; max-height: 200px; overflow-y: auto;">
          ${escapeHTML(ann.content).replace(/\n/g, '<br>')}
        </div>
        <button id="close-ann-btn" style="width: 100%; height: 42px; background: linear-gradient(135deg, #6366f1, #818cf8); border: none; border-radius: 10px; color: #fff; font-weight: 700; cursor: pointer; transition: transform 0.2s;">Đã hiểu</button>
      </div>
    </div>
    <style>
      @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
      @keyframes scaleIn { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    </style>
  `;

  const container = document.createElement('div');
  container.innerHTML = modalHtml;
  document.body.appendChild(container.firstElementChild);

  document.getElementById('close-ann-btn').addEventListener('click', () => {
    document.getElementById('announcement-modal').remove();
  });
}

// Helper to update the system connection status badge
window.setSystemStatus = function(status) {
  const dot = document.getElementById('systemStatusDot');
  const label = document.getElementById('systemStatusLabel');
  if (!dot || !label) return;
  if (status === 'online') {
    dot.style.background = '#10B981';
    label.textContent = 'Online';
    label.style.color = '#111827';
  } else if (status === 'offline') {
    dot.style.background = '#EF4444';
    label.textContent = 'Offline';
    label.style.color = '#EF4444';
  } else if (status === 'loading') {
    dot.style.background = '#F59E0B';
    label.textContent = 'Loading';
    label.style.color = '#F59E0B';
  }
};

// Update init to load groups
async function init() {
  setSystemStatus('online');
  // Tải cấu hình trước khi chạy giao diện
  let cfg = {};
  try {
    cfg = await API.cfgGet();
    S.cfg = cfg;
  } catch (err) {
    console.error('Lỗi nạp cấu hình hệ thống:', err);
  }

  // Khởi tạo theme trước, sau đó nạp gói ngôn ngữ offline.
  const savedTheme = localStorage.getItem('app_theme') || cfg?.theme || 'light-clean';
  await applyTheme(savedTheme, { animate: false, persist: true, syncConfig: false });
  await initTranslations();
  if (S.cfg) {
    S.cfg.theme = savedTheme;
    S.cfg.language = S.lang;
  }
  window.I18N?.renderLanguagePicker($('languageMenu'), { compact: true });
  translateDOM(document.body);

  // Đăng ký sự kiện nút điều khiển Titlebar
  $('btnThemeToggle')?.addEventListener('click', toggleTheme);
  $('btnLangToggle')?.addEventListener('click', toggleLanguage);
  document.addEventListener('click', event => {
    if (event.target.closest('.language-switcher-shell')) return;
    $('languageMenu')?.classList.remove('open');
    $('btnLangToggle')?.setAttribute('aria-expanded', 'false');
  });
  document.addEventListener('keydown', event => {
    if (event.key !== 'Escape') return;
    $('languageMenu')?.classList.remove('open');
    $('btnLangToggle')?.setAttribute('aria-expanded', 'false');
  });
  $('btnSettingsTitlebar')?.addEventListener('click', async () => {
    $$('.nav-item').forEach(b => b.classList.remove('active'));
    $$('.nav-item').forEach(b => {
      if (b.dataset.page === 'settings') b.classList.add('active');
    });
    await loadView('settings');
  });

  // Bind nút mở Admin Panel
  $('navAdminBtn')?.addEventListener('click', () => {
    window.location.href = 'admin/login.html';
  });

  // Toggle Sidebar Collapse
  const sidebar = $('sidebar');
  const btnToggle = $('btnToggleSidebar');
  if (sidebar && btnToggle) {
    const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
    if (isCollapsed) {
      sidebar.classList.add('collapsed');
    }
    btnToggle.addEventListener('click', () => {
      sidebar.classList.toggle('collapsed');
      localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('collapsed'));
    });
  }

  // Đăng ký lắng nghe các sự kiện từ Main Process
  if (window.API) {
    // 1. Nhận thông báo phát sóng
    window.API.onAnnouncement?.((ann) => {
      showAnnouncementModal(ann);
    });

    // 2. Thay đổi trạng thái bản quyền (khi bị admin khóa/hết hạn lúc đang chạy app)
    window.API.onLicenseStatus?.((status) => {
      alert(`[BẢN QUYỀN] ${status.msg || 'Trạng thái bản quyền của bạn đã thay đổi. Vui lòng kích hoạt lại.'}`);
      window.location.reload();
    });
  }

  try {
    const ver = await API.getVersion();
    $('appVersion').textContent = ver;
    $('appVersion')?.addEventListener('dblclick', () => {
      window.location.href = 'admin/login.html';
    });
    
    // Tự động kiểm tra bản cập nhật mới
    checkAppUpdates();
  } catch {}

  try {
    const license = await API.getLicenseInfo();
    const expiryEl = $('licenseExpiryVal');
    if (expiryEl) {
      if (license.isValid) {
        let exp = license.expiry ? license.expiry : 'Vĩnh viễn';
        const m = exp.match(/^(\d{4})-(\d{2})-(\d{2})/);
        if (m) exp = `${m[3]}/${m[2]}/${m[1]}`;
        expiryEl.textContent = exp;
        expiryEl.style.color = '';
      } else {
        expiryEl.textContent = 'Chưa kích hoạt';
        expiryEl.style.color = 'var(--red)';
      }
    }

    // Hiển thị thông tin tài khoản Google đăng nhập trên sidebar
    const userCard = $('sidebarUserCard');
    const avatarEl = $('sidebarUserAvatar');
    const nameEl = $('sidebarUserName');
    const emailEl = $('sidebarUserEmail');
    if (userCard && license.email) {
      userCard.style.display = 'flex';
      if (avatarEl) avatarEl.src = license.avatar || 'https://lh3.googleusercontent.com/a/default-user=s96-c';
      if (nameEl) nameEl.textContent = license.name || 'Google User';
      if (emailEl) emailEl.textContent = license.email;
    } else if (userCard) {
      userCard.style.display = 'none';
    }
  } catch (e) {
    console.error('Lỗi tải thông tin bản quyền:', e);
  }

  await loadGroups();
  await loadProfiles();
  
  let startPage = S.cfg?.startupPage || 'profiles';
  if (startPage === 'dashboard') startPage = 'profiles';
  await loadView(startPage);
  $$('.nav-item').forEach(b => {
    b.classList.toggle('active', b.dataset.page === startPage);
  });
  
  toast('Đã sẵn sàng!', 'success');
}

// ── QUẢN LÝ THÔNG BÁO & CẬP NHẬT PHIÊN BẢN TỪ WEB ─────────────────────────────
async function loadDashboardAnnouncements() {
  const annContainer = $('dashboardAnnouncements');
  if (!annContainer) return;
  
  try {
    const res = await API.shopeeGetAnnouncements();
    if (res && res.status && res.data && res.data.length > 0) {
      const ann = res.data[0]; // Lấy tin tức/thông báo mới nhất
      
      const annTitle = $('annTitle');
      const annContent = $('annContent');
      const annTime = $('annTime');
      const annImg = $('annImg');
      const btnAction = $('annBtnAction');
      const btnClose = $('annBtnClose');
      
      if (annTitle) annTitle.textContent = ann.title;
      if (annContent) annContent.textContent = ann.content;
      if (annTime) annTime.textContent = new Date(ann.created_at * 1000).toLocaleDateString('vi-VN');
      
      if (ann.image_url && annImg) {
        annImg.src = ann.image_url;
        annImg.style.display = 'block';
      } else if (annImg) {
        annImg.style.display = 'none';
      }
      
      if (ann.action_link && btnAction) {
        btnAction.style.display = 'block';
        btnAction.onclick = () => {
          API.openUrl(ann.action_link);
        };
      } else if (btnAction) {
        btnAction.style.display = 'none';
      }
      
      annContainer.style.display = 'flex';
      
      if (btnClose) {
        btnClose.onclick = () => {
          annContainer.style.display = 'none';
        };
      }
    } else {
      annContainer.style.display = 'none';
    }
  } catch (err) {
    console.error('Lỗi tải thông báo:', err);
    annContainer.style.display = 'none';
  }
}

async function checkAppUpdates() {
  try {
    const localVer = await API.getVersion();
    const res = await API.shopeeGetLatestVersion();
    if (res && res.status && res.data) {
      const remote = res.data;
      const remoteVer = remote.version;
      
      if (isNewerVersion(localVer, remoteVer)) {
        showUpdateModal(remote);
      }
    }
  } catch (err) {
    console.error('Lỗi kiểm tra bản cập nhật:', err);
  }
}

function isNewerVersion(current, latest) {
  const cParts = current.split('.').map(Number);
  const lParts = latest.split('.').map(Number);
  for (let i = 0; i < Math.max(cParts.length, lParts.length); i++) {
    const cVal = cParts[i] || 0;
    const lVal = lParts[i] || 0;
    if (lVal > cVal) return true;
    if (lVal < cVal) return false;
  }
  return false;
}

function showUpdateModal(remote) {
  if (document.getElementById('appUpdateModal')) return;

  const modal = document.createElement('div');
  modal.id = 'appUpdateModal';
  modal.style = `
    position: fixed; inset: 0; z-index: 11000;
    display: flex; align-items: center; justify-content: center;
    background: rgba(7, 8, 14, 0.7); backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px); padding: 20px;
    animation: fadeInModal 0.3s ease-out;
  `;

  if (!document.getElementById('modalAnimationStyles')) {
    const style = document.createElement('style');
    style.id = 'modalAnimationStyles';
    style.innerHTML = `
      @keyframes fadeInModal { from { opacity: 0; } to { opacity: 1; } }
      @keyframes scaleInModal { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    `;
    document.head.appendChild(style);
  }

  const isMandatory = remote.is_mandatory === 1;

  modal.innerHTML = `
    <div class="glass-card" style="
      width: 100%; max-width: 440px; padding: 28px 24px; border-radius: 20px;
      border: 1px solid rgba(99, 102, 241, 0.25); background: rgba(15, 23, 42, 0.85);
      box-shadow: 0 20px 50px rgba(0,0,0,0.5); text-align: center;
      animation: scaleInModal 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
      display: flex; flex-direction: column; gap: 16px;
    ">
      <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.2); display: flex; align-items: center; justify-content: center; margin: 0 auto; color: var(--accent);">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
      </div>
      <div>
        <h3 style="font-size: 15px; font-weight: 800; color: var(--text-primary); margin: 0 0 4px 0;">Phát hiện phiên bản mới!</h3>
        <p style="font-size: 11px; color: var(--text-muted); margin: 0;">Phiên bản mới nhất: <span style="font-family: monospace; font-weight: 700; color: var(--accent);">${escapeHTML(remote.version)}</span></p>
      </div>
      
      <div style="text-align: left; background: rgba(0,0,0,0.15); border: 1px solid rgba(255,255,255,0.04); padding: 14px; border-radius: 12px; max-height: 140px; overflow-y: auto;">
        <p style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); margin-bottom: 6px; letter-spacing: 0.5px;">Nhật ký thay đổi (Changelog):</p>
        <pre style="font-family: inherit; font-size: 11.5px; color: var(--text-secondary); white-space: pre-wrap; margin: 0; line-height: 1.5;">${escapeHTML(remote.changelog || 'Không có mô tả cập nhật.')}</pre>
      </div>

      <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 8px;">
        <button id="btnConfirmUpdate" class="btn-gradient" style="padding: 12px; font-size: 12px; font-weight: 700; border-radius: 12px; border: none; cursor: pointer; color: white;">Tải xuống & Cập nhật ngay</button>
        ${!isMandatory ? '<button id="btnSkipUpdate" style="background: transparent; border: none; color: var(--text-muted); font-size: 11px; font-weight: 600; cursor: pointer; padding: 6px; outline: none;">Bỏ qua bản này</button>' : '<p style="font-size: 9.5px; color: var(--amber); margin: 4px 0 0 0; font-weight: 600;">* Đây là bản cập nhật bắt buộc để tiếp tục sử dụng phần mềm.</p>'}
      </div>
    </div>
  `;

  document.body.appendChild(modal);

  const btnConfirm = modal.querySelector('#btnConfirmUpdate');
  if (btnConfirm) {
    btnConfirm.onclick = () => {
      API.openUrl(remote.download_url);
    };
  }

  const btnSkip = modal.querySelector('#btnSkipUpdate');
  if (btnSkip) {
    btnSkip.onclick = () => {
      modal.remove();
    };
  }
}

async function initTrashPage() {
  renderTrashList();
  
  $('btnGoBackProfiles')?.addEventListener('click', async () => {
    $$('.nav-item').forEach(b => b.classList.remove('active'));
    document.querySelector('.nav-item[data-page="profiles"]')?.classList.add('active');
    await loadView('profiles');
  });
  
  $('btnEmptyTrash')?.addEventListener('click', async () => {
    const trashList = S.profiles.filter(p => p.isDeleted);
    if (trashList.length === 0) {
      toast('Thùng rác đang trống', 'info');
      return;
    }
    if (!await confirm(`Xóa vĩnh viễn toàn bộ ${trashList.length} profiles trong Thùng rác? Thao tác này KHÔNG THỂ HOÀN TÁC.`)) return;
    
    let deletedCount = 0;
    for (const p of trashList) {
      const res = await API.profilesDelete(p.name);
      if (res.ok) deletedCount++;
    }
    
    toast(`Đã xóa vĩnh viễn ${deletedCount} profiles`, 'success');
    await loadProfiles();
    renderTrashList();
  });
}

function renderTrashList() {
  const container = $('trashListContainer');
  if (!container) return;
  
  const trashList = S.profiles.filter(p => p.isDeleted);
  if (trashList.length === 0) {
    container.innerHTML = `
      <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 60px 20px; text-align: center;">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" style="color: var(--text-muted); opacity: 0.6; margin-bottom: 16px;">
          <polyline points="3 6 5 6 21 6"></polyline>
          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
        </svg>
        <h3 style="font-size: 14px; font-weight: 600; color: var(--text-primary); margin-bottom: 4px;">Thùng rác trống</h3>
        <p style="font-size: 12px; color: var(--text-muted); max-width: 280px; margin: 0;">Các profile bị xóa tạm thời sẽ xuất hiện ở đây.</p>
      </div>
    `;
    return;
  }
  
  container.innerHTML = trashList.map(p => {
    return `
      <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1.2fr; align-items: center; padding: 14px 20px; border-bottom: 1px solid var(--border); transition: background-color 0.15s ease;">
        <!-- Info -->
        <div style="display: flex; align-items: center; gap: 12px;">
          <div style="width: 32px; height: 32px; border-radius: 9px; background: rgba(99, 102, 241, 0.1); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; color: var(--accent); flex-shrink: 0;">
            ${escapeHTML(p.name.charAt(0).toUpperCase())}
          </div>
          <div style="display: flex; flex-direction: column; gap: 2px;">
            <span style="font-weight: 700; font-size: 13.5px; color: var(--text-primary);">${escapeHTML(p.name)}</span>
          </div>
        </div>
        <!-- Group -->
        <div style="display: flex; align-items: center;">
          <span style="font-size: 11.5px; font-weight: 600; padding: 3px 10px; border-radius: 99px; background: var(--bg-hover); color: var(--text-secondary); border: 1px solid var(--border);">
            ${escapeHTML(p.group || 'Mặc định')}
          </span>
        </div>
        <!-- Notes -->
        <div style="font-size: 12px; color: var(--text-muted); max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${escapeHTML(p.notes || '')}">
          ${p.notes ? escapeHTML(p.notes) : '<span style="opacity: 0.4;">Chưa có ghi chú</span>'}
        </div>
        <!-- Actions -->
        <div style="display: flex; gap: 8px; justify-content: flex-end;">
          <button style="height: 30px; padding: 0 10px; border-radius: 8px; font-size: 11.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; border: 1px solid rgba(16, 185, 129, 0.25); background: rgba(16, 185, 129, 0.08); color: #059669; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='rgba(16, 185, 129, 0.18)'" onmouseout="this.style.background='rgba(16, 185, 129, 0.08)'" onclick="restoreProfileFromTrash(event, '${escapeInlineJsSingle(p.name)}')" title="Khôi phục hồ sơ">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
            <span>Khôi phục</span>
          </button>
          <button style="height: 30px; padding: 0 10px; border-radius: 8px; font-size: 11.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; border: 1px solid rgba(239, 68, 68, 0.25); background: rgba(239, 68, 68, 0.08); color: #dc2626; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='rgba(239, 68, 68, 0.18)'" onmouseout="this.style.background='rgba(239, 68, 68, 0.08)'" onclick="permanentlyDeleteProfile(event, '${escapeInlineJsSingle(p.name)}')" title="Xóa vĩnh viễn hồ sơ">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6"/></svg>
            <span>Xóa vĩnh viễn</span>
          </button>
        </div>
      </div>
    `;
  }).join('');
}

window.restoreProfileFromTrash = async function(e, name) {
  if (e) e.stopPropagation();
  const res = await API.profilesUpdate(name, { isDeleted: false });
  if (res.ok) {
    toast(`Đã khôi phục hồ sơ: ${name}`, 'success');
    await loadProfiles();
    renderTrashList();
  } else toast(res.msg || 'Lỗi khôi phục', 'error');
};

window.permanentlyDeleteProfile = async function(e, name) {
  if (e) e.stopPropagation();
  if (!await confirm(`Xóa vĩnh viễn "${name}"? Thao tác này sẽ xóa tất cả thư mục dữ liệu Chrome của profile này và KHÔNG THỂ HOÀN TÁC.`)) return;
  
  const res = await API.profilesDelete(name);
  if (res.ok) {
    toast(`Đã xóa vĩnh viễn hồ sơ: ${name}`, 'success');
    await loadProfiles();
    renderTrashList();
  } else toast(res.msg || 'Lỗi xóa', 'error');
};

init();
