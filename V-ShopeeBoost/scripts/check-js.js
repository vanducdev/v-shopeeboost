'use strict';

const fs = require('fs');
const path = require('path');
const { spawnSync } = require('child_process');

const root = path.resolve(__dirname, '..');
const files = [];

function collect(directory) {
  for (const entry of fs.readdirSync(directory, { withFileTypes:true })) {
    const fullPath = path.join(directory, entry.name);
    if (entry.isDirectory()) collect(fullPath);
    else if (entry.isFile() && entry.name.endsWith('.js')) files.push(fullPath);
  }
}

for (const entry of fs.readdirSync(root, { withFileTypes:true })) {
  if (entry.isFile() && entry.name.endsWith('.js')) files.push(path.join(root, entry.name));
}
for (const directory of ['admin', 'Extension-ShopeeBoost', 'pages', 'scripts', 'tests']) {
  collect(path.join(root, directory));
}

let failed = 0;
for (const file of files) {
  const result = spawnSync(process.execPath, ['--check', file], { encoding:'utf8' });
  if (result.status !== 0) {
    failed++;
    process.stderr.write(result.stderr || `Syntax error: ${file}\n`);
  }
}

if (failed) {
  process.stderr.write(`JavaScript syntax check failed for ${failed} file(s).\n`);
  process.exit(1);
}

process.stdout.write(`JavaScript syntax check passed for ${files.length} file(s).\n`);
