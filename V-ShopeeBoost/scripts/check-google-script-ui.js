'use strict';

const fs = require('fs');
const path = require('path');
const vm = require('vm');

const sourcePath = path.resolve(__dirname, '..', 'license-server-google-script.js');
const source = fs.readFileSync(sourcePath, 'utf8');

if (/var\s+ADMIN_PASSWORD\s*=\s*["'`]/.test(source)) {
  throw new Error('ADMIN_PASSWORD must come from Script Properties, not source code');
}

const context = vm.createContext({
  PropertiesService: {
    getScriptProperties: () => ({ getProperty: () => 'test-admin-password' })
  }
});
vm.runInContext(source, context, { filename:sourcePath });

if (typeof context.getAdminUI !== 'function') {
  throw new Error('getAdminUI was not defined');
}

const html = context.getAdminUI();
const inlineScripts = [];
const scriptPattern = /<script(?:\s[^>]*)?>([\s\S]*?)<\/script>/gi;
let match;
while ((match = scriptPattern.exec(html))) {
  if (match[1].trim()) inlineScripts.push(match[1]);
}

for (let index = 0; index < inlineScripts.length; index++) {
  new vm.Script(inlineScripts[index], {
    filename:`license-server-admin-ui-inline-${index + 1}.js`
  });
}

process.stdout.write(`Google Apps Script admin UI syntax passed for ${inlineScripts.length} inline script(s).\n`);
