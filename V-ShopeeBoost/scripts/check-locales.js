'use strict';

const fs = require('node:fs');
const path = require('node:path');

const root = path.resolve(__dirname, '..');
const localesDir = path.join(root, 'locales');
const manifest = JSON.parse(fs.readFileSync(path.join(localesDir, 'manifest.json'), 'utf8'));

if (!Array.isArray(manifest.languages) || manifest.languages.length !== 10) {
  throw new Error('Locale manifest must contain exactly 10 languages');
}

const seenCodes = new Set();
const seenFiles = new Set();
let expectedKeys = null;

for (const language of manifest.languages) {
  if (!language.code || !language.file || !language.nativeName || !language.flag) {
    throw new Error(`Invalid language metadata: ${JSON.stringify(language)}`);
  }
  if (seenCodes.has(language.code) || seenFiles.has(language.file)) {
    throw new Error(`Duplicate locale: ${language.code}/${language.file}`);
  }
  seenCodes.add(language.code);
  seenFiles.add(language.file);

  const filePath = path.join(localesDir, language.file);
  const locale = JSON.parse(fs.readFileSync(filePath, 'utf8'));
  if (locale.$meta?.code !== language.code || !locale.messages || typeof locale.messages !== 'object') {
    throw new Error(`Invalid locale file: ${language.file}`);
  }

  const keys = Object.keys(locale.messages).sort();
  if (!expectedKeys) expectedKeys = keys;
  if (keys.length !== expectedKeys.length || keys.some((key, index) => key !== expectedKeys[index])) {
    throw new Error(`Locale keys do not match: ${language.file}`);
  }
  for (const [key, value] of Object.entries(locale.messages)) {
    if (typeof value !== 'string' || !value.trim()) throw new Error(`Empty translation ${language.file}:${key}`);
  }
}

if (!seenCodes.has(manifest.defaultLanguage) || !seenCodes.has(manifest.fallbackLanguage)) {
  throw new Error('Default/fallback language is missing');
}

const sourceRoots = [
  path.join(root, 'index.html'),
  path.join(root, 'app.js'),
  path.join(root, 'pages'),
  path.join(root, 'admin'),
];
const sourceFiles = [];
const collectSourceFiles = (target) => {
  const stats = fs.statSync(target);
  if (stats.isDirectory()) {
    for (const entry of fs.readdirSync(target)) {
      collectSourceFiles(path.join(target, entry));
    }
    return;
  }
  if (/\.(?:html|js)$/i.test(target)) sourceFiles.push(target);
};
sourceRoots.forEach(collectSourceFiles);

const declaredKeys = new Set(expectedKeys);
const missingKeys = new Map();
const keyPattern = /\bdata-i18n(?:-(?:placeholder|title|aria-label))?=["']([^"']+)["']/g;
for (const filePath of sourceFiles) {
  const source = fs.readFileSync(filePath, 'utf8');
  for (const match of source.matchAll(keyPattern)) {
    if (declaredKeys.has(match[1])) continue;
    const relativePath = path.relative(root, filePath);
    if (!missingKeys.has(relativePath)) missingKeys.set(relativePath, new Set());
    missingKeys.get(relativePath).add(match[1]);
  }
}

if (missingKeys.size) {
  const details = [...missingKeys.entries()]
    .map(([file, keys]) => `${file}: ${[...keys].join(', ')}`)
    .join('\n');
  throw new Error(`Missing data-i18n keys:\n${details}`);
}

console.log(`Locale validation passed: ${manifest.languages.length} languages, ${expectedKeys.length} messages each.`);
