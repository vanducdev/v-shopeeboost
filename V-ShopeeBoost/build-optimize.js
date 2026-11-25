/**
 * V-ShopeeBoost Build & Optimize Script
 * Tự động tối ưu Chromium, Extension, chạy Electron Builder và phân tích dung lượng.
 */

const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

let JavaScriptObfuscator;
try {
  JavaScriptObfuscator = require('javascript-obfuscator');
} catch (e) {
  console.warn('⚠️ Cảnh báo: Chưa cài đặt javascript-obfuscator. Quá trình build sẽ bỏ qua bước xáo trộn mã nguồn.');
}

const PROJECT_DIR = __dirname;
const PACKAGE_VERSION = require(path.join(PROJECT_DIR, 'package.json')).version;
const CHROMIUM_DIR = path.join(PROJECT_DIR, 'ChromeCore141');
const EXTENSION_DIR = path.join(PROJECT_DIR, 'Extension-ShopeeBoost');
const DIST_DIR = path.join(PROJECT_DIR, 'dist');

// --- Helper: Format Bytes ---
function formatBytes(bytes, decimals = 2) {
  if (bytes === 0) return '0 Bytes';
  const k = 1024;
  const dm = decimals < 0 ? 0 : decimals;
  const sizes = ['Bytes', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

// --- Helper: Get Directory Size ---
function getDirSize(dirPath) {
  let size = 0;
  if (!fs.existsSync(dirPath)) return 0;
  const files = fs.readdirSync(dirPath);
  for (let i = 0; i < files.length; i++) {
    const filePath = path.join(dirPath, files[i]);
    const stats = fs.statSync(filePath);
    if (stats.isDirectory()) {
      size += getDirSize(filePath);
    } else {
      size += stats.size;
    }
  }
  return size;
}

// --- Step 1: Optimize Chromium ---
function optimizeChromium() {
  console.log('\n=========================================');
  console.log('1. Đang tối ưu hóa Chromium...');
  console.log('=========================================');

  if (!fs.existsSync(CHROMIUM_DIR)) {
    console.warn('⚠️ Không tìm thấy thư mục ChromeCore141. Bỏ qua bước này.');
    return;
  }

  const localesDir = path.join(CHROMIUM_DIR, '141.0.7390.125', 'Locales');
  if (fs.existsSync(localesDir)) {
    const allowedLocales = ['vi.pak', 'en-US.pak', 'en-GB.pak'];
    const files = fs.readdirSync(localesDir);
    let deletedCount = 0;
    let savedSize = 0;

    files.forEach(file => {
      if (!allowedLocales.includes(file) && file.endsWith('.pak')) {
        const filePath = path.join(localesDir, file);
        try {
          const stats = fs.statSync(filePath);
          savedSize += stats.size;
          fs.unlinkSync(filePath);
          deletedCount++;
        } catch (err) {
          console.error(`Không thể xóa ${file}: ${err.message}`);
        }
      }
    });
    console.log(`✅ Đã xóa ${deletedCount} file locales không dùng. Tiết kiệm: ${formatBytes(savedSize)}`);
  }

  // Xóa debug.log nếu có
  const debugLog = path.join(CHROMIUM_DIR, 'debug.log');
  if (fs.existsSync(debugLog)) {
    try {
      fs.unlinkSync(debugLog);
      console.log('✅ Đã xóa file debug.log thừa trong Chromium.');
    } catch {}
  }
}

// --- Step 2: Optimize Extension ---
function optimizeExtension() {
  console.log('\n=========================================');
  console.log('2. Đang tối ưu hóa Extension-ShopeeBoost...');
  console.log('=========================================');

  if (!fs.existsSync(EXTENSION_DIR)) {
    console.warn('⚠️ Không tìm thấy thư mục Extension-ShopeeBoost. Bỏ qua.');
    return;
  }

  // Xóa thư mục .git
  const gitDir = path.join(EXTENSION_DIR, '.git');
  if (fs.existsSync(gitDir)) {
    try {
      fs.rmSync(gitDir, { recursive: true, force: true });
      console.log('✅ Đã xóa thư mục .git trong Extension.');
    } catch (err) {
      console.warn(`Không thể xóa thư mục .git: ${err.message}`);
    }
  }

  // Xóa file phát triển
  const devFiles = ['README.md', 'icons/icon-generator.html'];
  devFiles.forEach(file => {
    const filePath = path.join(EXTENSION_DIR, file);
    if (fs.existsSync(filePath)) {
      try {
        fs.unlinkSync(filePath);
        console.log(`✅ Đã xóa file phát triển: ${file}`);
      } catch {}
    }
  });

  // Safe JS/CSS Minifier (Strip comments and collapse whitespace)
  const minifyFile = (filePath) => {
    if (!fs.existsSync(filePath)) return;
    try {
      let content = fs.readFileSync(filePath, 'utf8');
      const originalSize = Buffer.byteLength(content);

      // Simple regex-based minifier that safely strips comments and extra spaces
      // Remove multi-line comments
      content = content.replace(/\/\*[\s\S]*?\*\//g, '');
      // Remove single-line comments
      content = content.replace(/(?:^|[^:])\/\/.*$/gm, '');
      // Remove empty lines and collapse duplicate newlines/whitespaces
      content = content.replace(/^\s*[\r\n]/gm, '');
      content = content.replace(/[ \t]+/g, ' ');

      fs.writeFileSync(filePath, content, 'utf8');
      const minifiedSize = Buffer.byteLength(content);
      const diff = originalSize - minifiedSize;
      console.log(`⚡ Minified ${path.basename(filePath)}: ${formatBytes(originalSize)} -> ${formatBytes(minifiedSize)} (-${((diff/originalSize)*100).toFixed(1)}%)`);
    } catch (err) {
      console.error(`Lỗi khi nén ${filePath}: ${err.message}`);
    }
  };

  const jsFiles = ['background.js', 'content.js', 'popup.js'];
  jsFiles.forEach(file => {
    minifyFile(path.join(EXTENSION_DIR, file));
  });
}

// --- Step 3: Run Electron Builder ---
function runBuild() {
  console.log('\n=========================================');
  console.log('3. Đang thực thi Electron Builder...');
  console.log('=========================================');

  // Thực thi build
  try {
    const builderCmd = 'npx electron-builder --win --x64';
    console.log(`Chạy lệnh: ${builderCmd}`);
    execSync(builderCmd, { stdio: 'inherit', cwd: PROJECT_DIR });
    console.log('✅ Build thành công!');
  } catch (err) {
    console.error('❌ Build thất bại. Chi tiết lỗi:', err.message);
    process.exit(1);
  }
}

// --- Step 4: Post-Build Analysis ---
function analyzeResult() {
  console.log('\n=========================================');
  console.log('4. Phân tích dung lượng sau khi build...');
  console.log('=========================================');

  const setupName = `V-ShopeeBoost-Setup-${PACKAGE_VERSION}.exe`;
  const setupFile = path.join(DIST_DIR, setupName);
  const unpackedDir = path.join(DIST_DIR, 'win-unpacked');

  if (!fs.existsSync(setupFile)) {
    console.error(`❌ Không tìm thấy file cài đặt ${setupName}!`);
    return;
  }

  const setupSize = fs.statSync(setupFile).size;
  console.log(`\n📦 FILE CÀI ĐẶT CUỐI CÙNG:`);
  console.log(`   👉 dist/${setupName}: ${formatBytes(setupSize)} (${(setupSize / 1024 / 1024).toFixed(2)} MB)`);

  if (setupSize < 100 * 1024 * 1024) {
    console.log('   🎉 Đạt mục tiêu xuất sắc: DƯỚI 100MB!');
  } else if (setupSize < 130 * 1024 * 1024) {
    console.log('   ✅ Đạt mục tiêu tốt: DƯỚI 130MB!');
  } else if (setupSize < 150 * 1024 * 1024) {
    console.log('   ⚠️ Đạt mục tiêu chấp nhận được: DƯỚI 150MB!');
  } else {
    console.log('   ❌ Dung lượng vượt quá 150MB. Cần tối ưu thêm.');
  }

  if (!fs.existsSync(unpackedDir)) {
    console.warn('⚠️ Không tìm thấy thư mục win-unpacked để phân tích chi tiết.');
    return;
  }

  console.log(`\n📂 THƯ MỤC ỨNG DỤNG SAU KHI CÀI ĐẶT (win-unpacked):`);
  const totalUnpackedSize = getDirSize(unpackedDir);
  console.log(`   Tổng dung lượng giải nén: ${formatBytes(totalUnpackedSize)}`);

  // Phân tích các thành phần chính trong win-unpacked
  const components = [];

  // 1. Chromium
  const chromSize = getDirSize(path.join(unpackedDir, 'resources', 'ChromeCore141'));
  components.push({ name: 'ChromeCore141 (trong resources)', size: chromSize });

  // 2. App.asar (Source code app)
  const asarPath = path.join(unpackedDir, 'resources', 'app.asar');
  let asarSize = 0;
  if (fs.existsSync(asarPath)) {
    asarSize = fs.statSync(asarPath).size;
  }
  components.push({ name: 'App Source Code (app.asar)', size: asarSize });

  // 3. Extension-ShopeeBoost
  const extSize = getDirSize(path.join(unpackedDir, 'resources', 'Extension-ShopeeBoost'));
  components.push({ name: 'Extension-ShopeeBoost', size: extSize });

  // 4. Electron framework files (exe, dlls, paks ở root)
  let electronCoreSize = 0;
  const rootFiles = fs.readdirSync(unpackedDir);
  rootFiles.forEach(file => {
    if (file !== 'resources' && file !== 'locales') {
      const filePath = path.join(unpackedDir, file);
      const stats = fs.statSync(filePath);
      if (!stats.isDirectory()) {
        electronCoreSize += stats.size;
      }
    }
  });
  // Electron locales
  const elecLocalesSize = getDirSize(path.join(unpackedDir, 'locales'));
  components.push({ name: 'Electron Core (exe, dlls, resources)', size: electronCoreSize });
  components.push({ name: 'Electron Locales', size: elecLocalesSize });

  // Sắp xếp giảm dần theo dung lượng
  components.sort((a, b) => b.size - a.size);

  console.log('\n📊 CHI TIẾT THÀNH PHẦN CHIẾM DUNG LƯỢNG:');
  components.forEach(c => {
    const percentage = ((c.size / totalUnpackedSize) * 100).toFixed(1);
    const barLength = Math.round(parseFloat(percentage) / 2);
    const bar = '█'.repeat(barLength) + '░'.repeat(50 - barLength);
    console.log(`   - ${c.name.padEnd(40)}: ${formatBytes(c.size).padEnd(12)} (${percentage}%)  [${bar}]`);
  });

  console.log('\n💡 ĐỀ XUẤT GIẢM DUNG LƯỢNG THÊM:');
  console.log('   1. Nén ảnh/icons của Extension bằng tinypng hoặc định dạng WebP.');
  console.log('   2. Dọn dẹp bớt locales của chính Electron trong thư mục locales của Electron (loại bỏ các ngôn ngữ không dùng tương tự Chromium).');
  console.log('   3. Kiểm tra xem có thể lược bớt một số file dll lớn không cần thiết của Chromium như dxcompiler.dll (16.2MB) nếu app không sử dụng WebGL phức tạp.');
  console.log('=========================================\n');
}

// --- Step 4.5: Obfuscate files ---
function obfuscateFiles() {
  if (!JavaScriptObfuscator) {
    console.log('⚠️ Bỏ qua xáo trộn mã nguồn do thiếu thư viện.');
    return;
  }
  console.log('\n=========================================');
  console.log('2.5. Đang xáo trộn mã nguồn (Obfuscation)...');
  console.log('=========================================');
  
  const filesToObfuscate = ['main.js', 'app.js', 'extension-services.js'];
  
  filesToObfuscate.forEach(file => {
    const filePath = path.join(PROJECT_DIR, file);
    if (fs.existsSync(filePath)) {
      try {
        const backupPath = filePath + '.bak';
        // Tạo file backup
        fs.copyFileSync(filePath, backupPath);
        
        const sourceCode = fs.readFileSync(filePath, 'utf8');
        const obfuscatedResult = JavaScriptObfuscator.obfuscate(sourceCode, {
          compact: true,
          controlFlowFlattening: true,
          controlFlowFlatteningThreshold: 0.5,
          numbersToExpressions: true,
          simplify: true,
          stringArray: true,
          stringArrayEncoding: ['base64'],
          stringArrayThreshold: 0.5
        });
        
        fs.writeFileSync(filePath, obfuscatedResult.getObfuscatedCode(), 'utf8');
        console.log(`✅ Đã xáo trộn và bảo vệ: ${file}`);
      } catch (err) {
        console.error(`❌ Lỗi khi xáo trộn ${file}:`, err.message);
      }
    }
  });
}

function restoreOriginalFiles() {
  if (!JavaScriptObfuscator) return;
  console.log('\n=========================================');
  console.log('3.5. Đang khôi phục lại mã nguồn gốc...');
  console.log('=========================================');
  
  const filesToRestore = ['main.js', 'app.js', 'extension-services.js'];
  
  filesToRestore.forEach(file => {
    const filePath = path.join(PROJECT_DIR, file);
    const backupPath = filePath + '.bak';
    if (fs.existsSync(backupPath)) {
      try {
        fs.copyFileSync(backupPath, filePath);
        fs.unlinkSync(backupPath);
        console.log(`✅ Đã khôi phục: ${file}`);
      } catch (err) {
        console.error(`❌ Không thể khôi phục ${file}:`, err.message);
      }
    }
  });
}

// --- Main Execution ---
function main() {
  const start = Date.now();
  try {
    optimizeChromium();
    optimizeExtension();
    obfuscateFiles();
    runBuild();
  } catch (err) {
    console.error('❌ Quá trình build gặp lỗi:', err.message);
  } finally {
    restoreOriginalFiles();
  }
  analyzeResult();
  const duration = ((Date.now() - start) / 1000).toFixed(1);
  console.log(`✨ Hoàn thành toàn bộ quy trình trong ${duration} giây.`);
}

main();
