const fs = require('fs');
const path = require('path');

// Đọc file PNG
const pngPath = path.join(__dirname, 'img', 'icon.4.0.png');
const icoPath = path.join(__dirname, 'build', 'icon.ico');

// Copy PNG thành ICO (Electron có thể tự xử lý)
fs.copyFileSync(pngPath, icoPath);

console.log('Đã copy icon.png sang icon.ico');
