'use strict';

/**
 * Offline renderer localization runtime.
 *
 * - Loads one JSON bundle at a time (plus Vietnamese source/fallback).
 * - Translates explicit data-i18n keys and legacy exact text nodes.
 * - Observes dynamically rendered page fragments and notifications.
 * - Changes language without reloading or flashing the whole window.
 */
(function createI18nRuntime(global) {
  const scriptUrl = document.currentScript?.src || new URL('./i18n.js', location.href).href;
  const assetRoot = new URL('./', scriptUrl);
  const languageCountryCodes = Object.freeze({
    vi: 'vn',
    en: 'us',
    th: 'th',
    id: 'id',
    ms: 'my',
    fil: 'ph',
    'zh-CN': 'cn',
    ja: 'jp',
    ko: 'kr',
    es: 'es'
  });
  const textRecords = new WeakMap();
  const attributeRecords = new WeakMap();
  const localeCache = new Map();
  const reverseIndex = new Map();
  const templateMatchers = [];
  const queuedRoots = new Set();

  const state = {
    manifest: null,
    currentCode: 'vi',
    initialized: false,
    initPromise: null,
    applying: false,
    flushScheduled: false,
    observer: null
  };

  function normalized(value) {
    return String(value ?? '').replace(/\s+/g, ' ').trim();
  }

  function escapeRegExp(value) {
    return value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  }

  function languageByCode(code) {
    return state.manifest?.languages?.find(language => language.code === code) || null;
  }

  function localeUrl(file) {
    return new URL(`locales/${file}`, assetRoot).href;
  }

  function flagUrl(language) {
    const countryCode = languageCountryCodes[language?.code] || 'vn';
    return new URL(`flag/languages/${countryCode}.svg`, assetRoot).href;
  }

  function applyFlagImage(image, language) {
    if (!image || !language) return;
    image.src = flagUrl(language);
    image.alt = '';
    image.setAttribute('aria-hidden', 'true');
    image.decoding = 'async';
  }

  async function fetchJson(url) {
    const response = await fetch(url, { cache: 'no-cache' });
    if (!response.ok) throw new Error(`Không thể tải ${url}: HTTP ${response.status}`);
    return response.json();
  }

  async function loadLocale(code) {
    if (localeCache.has(code)) return localeCache.get(code);
    const language = languageByCode(code);
    if (!language) throw new Error(`Unsupported language: ${code}`);
    const locale = await fetchJson(localeUrl(language.file));
    if (locale?.$meta?.code !== code || !locale.messages) {
      throw new Error(`Invalid locale bundle: ${language.file}`);
    }
    localeCache.set(code, locale);
    rebuildIndexes();
    return locale;
  }

  function compileTemplate(key, source) {
    const variables = [];
    let cursor = 0;
    let pattern = '^';
    for (const match of source.matchAll(/\{([a-z][a-z0-9]*)\}/gi)) {
      pattern += escapeRegExp(source.slice(cursor, match.index));
      pattern += '(.+?)';
      variables.push(match[1]);
      cursor = match.index + match[0].length;
    }
    pattern += `${escapeRegExp(source.slice(cursor))}$`;
    if (variables.length) templateMatchers.push({ key, variables, regex: new RegExp(pattern, 'u') });
  }

  function rebuildIndexes() {
    reverseIndex.clear();
    templateMatchers.length = 0;

    for (const locale of localeCache.values()) {
      for (const [key, value] of Object.entries(locale.messages || {})) {
        const text = normalized(value);
        if (text && !text.includes('{') && !reverseIndex.has(text)) reverseIndex.set(text, key);
      }
    }

    const source = localeCache.get(state.manifest?.defaultLanguage || 'vi');
    if (source) {
      for (const [key, value] of Object.entries(source.messages)) {
        if (String(value).includes('{')) compileTemplate(key, normalized(value));
      }
    }
  }

  function currentLocale() {
    return localeCache.get(state.currentCode);
  }

  function fallbackLocale() {
    return localeCache.get(state.manifest?.fallbackLanguage || 'en')
      || localeCache.get(state.manifest?.defaultLanguage || 'vi');
  }

  function formatMessage(message, variables = {}) {
    return String(message ?? '').replace(/\{([a-z][a-z0-9]*)\}/gi, (token, name) => {
      const value = variables[name];
      return value === undefined || value === null ? token : String(value);
    });
  }

  function t(key, variables = {}) {
    const message = currentLocale()?.messages?.[key]
      ?? fallbackLocale()?.messages?.[key]
      ?? localeCache.get(state.manifest?.defaultLanguage || 'vi')?.messages?.[key]
      ?? key;
    return formatMessage(message, variables);
  }

  function resolveText(value) {
    const text = normalized(value);
    const exactKey = reverseIndex.get(text);
    if (exactKey) return { key: exactKey, variables: {} };

    for (const matcher of templateMatchers) {
      const match = matcher.regex.exec(text);
      if (!match) continue;
      const variables = Object.fromEntries(matcher.variables.map((name, index) => [name, match[index + 1]]));
      return { key: matcher.key, variables };
    }
    return null;
  }

  function shouldSkipNode(node) {
    const parent = node.parentElement;
    return !parent
      || Boolean(parent.closest('script, style, noscript, svg, code, pre, [data-i18n-skip]'))
      || Boolean(parent.closest('[data-i18n]'));
  }

  function replacePreservingWhitespace(raw, translated) {
    const leading = raw.match(/^\s*/u)?.[0] || '';
    const trailing = raw.match(/\s*$/u)?.[0] || '';
    return `${leading}${translated}${trailing}`;
  }

  function translateTextNode(node) {
    if (!node?.nodeValue || shouldSkipNode(node)) return;
    const raw = node.nodeValue;
    const text = normalized(raw);
    if (!text) return;

    let record = textRecords.get(node);
    if (!record || record.rendered !== text) {
      record = resolveText(text);
      if (!record) return;
    }

    const rendered = t(record.key, record.variables);
    textRecords.set(node, { ...record, rendered: normalized(rendered) });
    if (text !== normalized(rendered)) node.nodeValue = replacePreservingWhitespace(raw, rendered);
  }

  function translateAttribute(element, attribute, explicitKey = '') {
    const raw = element.getAttribute(attribute);
    if (!raw) return;
    const records = attributeRecords.get(element) || {};
    let record = records[attribute];
    const text = normalized(raw);

    if (explicitKey) {
      record = { key: explicitKey, variables: {} };
    } else if (!record || record.rendered !== text) {
      record = resolveText(text);
    }
    if (!record) return;

    const rendered = t(record.key, record.variables);
    records[attribute] = { ...record, rendered: normalized(rendered) };
    attributeRecords.set(element, records);
    if (raw !== rendered) element.setAttribute(attribute, rendered);
  }

  function translateExplicit(root) {
    const elements = [];
    if (root.nodeType === Node.ELEMENT_NODE) elements.push(root);
    if (typeof root.querySelectorAll === 'function') {
      elements.push(...root.querySelectorAll(
        '[data-i18n], [data-i18n-placeholder], [data-i18n-title], [data-i18n-aria-label]'
      ));
    }

    for (const element of elements) {
      const textKey = element.getAttribute('data-i18n');
      if (textKey) element.textContent = t(textKey);
      const placeholderKey = element.getAttribute('data-i18n-placeholder');
      if (placeholderKey) translateAttribute(element, 'placeholder', placeholderKey);
      const titleKey = element.getAttribute('data-i18n-title');
      if (titleKey) translateAttribute(element, 'title', titleKey);
      const ariaKey = element.getAttribute('data-i18n-aria-label');
      if (ariaKey) translateAttribute(element, 'aria-label', ariaKey);
    }
  }

  function translateLegacyAttributes(root) {
    const elements = [];
    if (root.nodeType === Node.ELEMENT_NODE && root.matches('[placeholder], [title], [aria-label]')) elements.push(root);
    if (typeof root.querySelectorAll === 'function') {
      elements.push(...root.querySelectorAll('[placeholder], [title], [aria-label]'));
    }
    for (const element of elements) {
      if (!element.hasAttribute('data-i18n-placeholder')) translateAttribute(element, 'placeholder');
      if (!element.hasAttribute('data-i18n-title')) translateAttribute(element, 'title');
      if (!element.hasAttribute('data-i18n-aria-label')) translateAttribute(element, 'aria-label');
    }
  }

  function translateDOM(root = document.body) {
    if (!root || !state.initialized || state.applying) return;
    state.applying = true;
    try {
      translateExplicit(root);
      translateLegacyAttributes(root);
      if (root.nodeType === Node.TEXT_NODE) {
        translateTextNode(root);
      } else {
        const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT);
        let node;
        while ((node = walker.nextNode())) translateTextNode(node);
      }
    } finally {
      state.applying = false;
    }
  }

  function queueTranslation(root) {
    if (!root) return;
    queuedRoots.add(root.nodeType === Node.TEXT_NODE ? root : root);
    if (state.flushScheduled) return;
    state.flushScheduled = true;
    queueMicrotask(() => {
      state.flushScheduled = false;
      const roots = [...queuedRoots];
      queuedRoots.clear();
      for (const queuedRoot of roots) translateDOM(queuedRoot);
    });
  }

  function startObserver() {
    state.observer?.disconnect();
    state.observer = new MutationObserver(mutations => {
      if (state.applying) return;
      for (const mutation of mutations) {
        if (mutation.type === 'characterData') queueTranslation(mutation.target);
        for (const node of mutation.addedNodes || []) queueTranslation(node);
      }
    });
    state.observer.observe(document.documentElement, {
      childList: true,
      characterData: true,
      subtree: true
    });
  }

  function updateLanguageControls() {
    const language = languageByCode(state.currentCode);
    if (!language) return;
    const flag = document.getElementById('langFlag');
    const currentFlag = document.getElementById('currentLanguageFlag');
    const text = document.getElementById('langText');
    const currentName = document.getElementById('currentLanguageName');
    if (flag instanceof HTMLImageElement) applyFlagImage(flag, language);
    else if (flag) flag.textContent = language.flag;
    if (currentFlag instanceof HTMLImageElement) applyFlagImage(currentFlag, language);
    if (text) text.textContent = language.nativeName;
    if (currentName) currentName.textContent = language.nativeName;
    document.querySelectorAll('[data-language-code]').forEach(button => {
      const active = button.dataset.languageCode === state.currentCode;
      button.classList.toggle('active', active);
      button.setAttribute('aria-checked', String(active));
    });
  }

  function createLanguageOption(language, compact = false) {
    const button = document.createElement('button');
    button.type = 'button';
    button.className = compact ? 'language-menu-item' : 'language-choice-card';
    button.dataset.languageCode = language.code;
    button.dataset.i18nSkip = '';
    button.setAttribute('role', 'radio');

    const flag = document.createElement('img');
    flag.className = 'language-choice-flag';
    flag.width = compact ? 30 : 36;
    flag.height = compact ? 23 : 27;
    applyFlagImage(flag, language);

    const copy = document.createElement('span');
    copy.className = 'language-choice-copy';
    const nativeName = document.createElement('strong');
    nativeName.textContent = language.nativeName;
    const internationalName = document.createElement('small');
    internationalName.textContent = language.name;
    copy.append(nativeName, internationalName);

    const check = document.createElement('span');
    check.className = 'language-choice-check';
    check.textContent = '✓';
    button.append(flag, copy, check);
    button.addEventListener('click', () => {
      void setLanguage(language.code);
      document.getElementById('languageMenu')?.classList.remove('open');
    });
    return button;
  }

  function renderLanguagePicker(container, { compact = false } = {}) {
    if (!container || !state.manifest) return;
    container.replaceChildren(...state.manifest.languages.map(language => createLanguageOption(language, compact)));
    updateLanguageControls();
  }

  async function setLanguage(code, { announce = true, persist = true } = {}) {
    const language = languageByCode(code);
    if (!language) throw new Error(`Unsupported language: ${code}`);
    const previousCode = state.currentCode;

    document.documentElement.classList.add('language-switching');
    document.body?.classList.add('language-switching');
    try {
      await loadLocale(code);
      state.currentCode = code;
      rebuildIndexes();
      if (persist) localStorage.setItem('app_lang', code);
      document.documentElement.lang = language.locale;
      document.documentElement.dir = language.direction || 'ltr';
      translateDOM(document.body);
      updateLanguageControls();
      global.dispatchEvent(new CustomEvent('app-language-changed', {
        detail: { code, previousCode, language, announce }
      }));
    } finally {
      requestAnimationFrame(() => requestAnimationFrame(() => {
        document.documentElement.classList.remove('language-switching');
        document.body?.classList.remove('language-switching');
      }));
    }
  }

  async function init(options = {}) {
    if (state.initPromise) return state.initPromise;
    state.initPromise = (async () => {
      state.manifest = await fetchJson(localeUrl('manifest.json'));
      const requested = options.language || localStorage.getItem('app_lang') || state.manifest.defaultLanguage;
      const selected = languageByCode(requested) ? requested : state.manifest.defaultLanguage;

      await loadLocale(state.manifest.defaultLanguage);
      if (state.manifest.fallbackLanguage !== state.manifest.defaultLanguage) {
        await loadLocale(state.manifest.fallbackLanguage);
      }
      if (!localeCache.has(selected)) await loadLocale(selected);

      state.currentCode = selected;
      state.initialized = true;
      rebuildIndexes();
      const language = languageByCode(selected);
      document.documentElement.lang = language.locale;
      document.documentElement.dir = language.direction || 'ltr';
      translateDOM(document.body);
      updateLanguageControls();
      startObserver();
      return state;
    })();
    return state.initPromise;
  }

  global.I18N = Object.freeze({
    init,
    t,
    setLanguage,
    translateDOM,
    renderLanguagePicker,
    getCurrentLanguage: () => state.currentCode,
    getLanguages: () => [...(state.manifest?.languages || [])],
    getLanguage: languageByCode,
    getFlagUrl: (code) => flagUrl(languageByCode(code)),
    ready: () => state.initPromise || init()
  });
})(window);
