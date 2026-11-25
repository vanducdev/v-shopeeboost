const fs = require('fs');
const path = require('path');
const https = require('https');
const crypto = require('crypto');
const AdmZip = require('adm-zip');
const {
  atomicWriteJsonSync,
  resolveInside,
  validateChromeStoreId,
  validateExtensionId
} = require('./security-utils');

/**
 * ExtensionRepository: Quản lý lưu trữ thông số trạng thái của Extension trong Profile
 */
class ExtensionRepository {
  static getFilePath(profilePath) {
    return resolveInside(profilePath, 'extensions.json');
  }

  static getExtensionsDir(profilePath) {
    const dir = resolveInside(profilePath, 'extensions');
    if (!fs.existsSync(dir)) {
      fs.mkdirSync(dir, { recursive: true });
    }
    return dir;
  }

  static load(profilePath) {
    const filePath = this.getFilePath(profilePath);
    if (!fs.existsSync(filePath)) {
      // Quét thư mục thực tế nếu file JSON chưa tồn tại
      return this.scanAndSync(profilePath);
    }
    try {
      return JSON.parse(fs.readFileSync(filePath, 'utf8'));
    } catch (e) {
      console.error('Lỗi đọc extensions.json:', e);
      return {};
    }
  }

  static save(profilePath, data) {
    const filePath = this.getFilePath(profilePath);
    atomicWriteJsonSync(filePath, data);
  }

  static scanAndSync(profilePath) {
    const extDir = this.getExtensionsDir(profilePath);
    const data = {};
    
    if (!fs.existsSync(extDir)) return data;

    const items = fs.readdirSync(extDir);
    for (const id of items) {
      const p = path.join(extDir, id);
      if (fs.statSync(p).isDirectory()) {
        const manifestPath = path.join(p, 'manifest.json');
        if (fs.existsSync(manifestPath)) {
          try {
            const manifest = JSON.parse(fs.readFileSync(manifestPath, 'utf8'));
            data[id] = {
              id,
              name: manifest.name || id,
              version: manifest.version || '1.0.0',
              enabled: true,
              source: 'zip',
              path: `extensions/${id}`,
              installedTime: new Date().toISOString(),
              permissions: manifest.permissions || [],
              description: manifest.description || '',
              homepage: manifest.homepage_url || ''
            };
          } catch (e) {
            console.error(`Lỗi quét manifest cho extension ${id}:`, e);
          }
        }
      }
    }
    this.save(profilePath, data);
    return data;
  }
}

/**
 * ManifestParser: Phân tích tệp tin manifest.json và trích xuất thông tin
 */
class ManifestParser {
  static parse(extFolderPath) {
    const manifestPath = path.join(extFolderPath, 'manifest.json');
    if (!fs.existsSync(manifestPath)) {
      throw new Error('Không tìm thấy tệp manifest.json trong gói cài đặt.');
    }
    try {
      const manifest = JSON.parse(fs.readFileSync(manifestPath, 'utf8'));
      
      // Tìm background script
      let background = 'None';
      if (manifest.background) {
        if (manifest.background.scripts) {
          background = manifest.background.scripts.join(', ');
        } else if (manifest.background.service_worker) {
          background = manifest.background.service_worker;
        }
      }

      // Tìm content scripts
      let contentScripts = 'None';
      if (manifest.content_scripts && manifest.content_scripts.length) {
        contentScripts = manifest.content_scripts.map(c => c.js ? c.js.join(', ') : '').filter(Boolean).join('; ');
      }

      return {
        name: manifest.name || 'Không rõ tên',
        version: manifest.version || '1.0.0',
        description: manifest.description || 'Không có mô tả',
        permissions: manifest.permissions || [],
        manifestVersion: manifest.manifest_version || 2,
        background,
        contentScripts,
        homepage: manifest.homepage_url || '',
        updateUrl: manifest.update_url || '',
        author: manifest.author || 'Không rõ'
      };
    } catch (e) {
      throw new Error('Tệp manifest.json không đúng định dạng JSON: ' + e.message);
    }
  }
}

/**
 * ZIPInstaller & CRXInstaller: Giải nén và lưu trữ extension vào thư mục của profile
 */
class ZIPInstaller {
  static install(zipFilePath, profilePath, customId = null) {
    const zip = new AdmZip(zipFilePath);
    
    // Tạo một thư mục tạm để giải nén và đọc manifest.json
    const tempDir = path.join(profilePath, 'temp_ext_' + Date.now());
    fs.mkdirSync(tempDir, { recursive: true });
    
    try {
      const entries = zip.getEntries();
      if (entries.length > 5000) throw new Error('Gói extension chứa quá nhiều tệp');
      let unpackedSize = 0;
      for (const entry of entries) {
        const entryName = String(entry.entryName || '').replace(/\\/g, '/');
        const normalized = path.posix.normalize(entryName);
        if (
          !entryName ||
          entryName.includes('\0') ||
          normalized === '..' ||
          normalized.startsWith('../') ||
          path.posix.isAbsolute(normalized)
        ) {
          throw new Error('Gói extension chứa đường dẫn không an toàn');
        }
        unpackedSize += Number(entry.header?.size || 0);
        if (unpackedSize > 200 * 1024 * 1024) throw new Error('Extension sau giải nén vượt quá 200 MB');
      }
      zip.extractAllTo(tempDir, true);
      
      // Phân tích manifest.json
      const meta = ManifestParser.parse(tempDir);
      
      // Tạo ID duy nhất cho extension dựa trên tên và phiên bản (hoặc dùng WebStore ID nếu được cấp)
      const extId = validateExtensionId(customId || crypto.createHash('sha256').update(meta.name + meta.version).digest('hex').substring(0, 32));
      
      // Di chuyển từ tempDir sang thư mục chính thức
      const finalDir = resolveInside(ExtensionRepository.getExtensionsDir(profilePath), extId);
      if (fs.existsSync(finalDir)) {
        fs.rmSync(finalDir, { recursive: true, force: true });
      }
      fs.renameSync(tempDir, finalDir);
      
      // Cập nhật trạng thái lưu trữ
      const db = ExtensionRepository.load(profilePath);
      db[extId] = {
        id: extId,
        name: meta.name,
        version: meta.version,
        enabled: true,
        source: customId ? 'webstore' : 'zip',
        path: `extensions/${extId}`,
        installedTime: new Date().toISOString(),
        permissions: meta.permissions,
        description: meta.description,
        manifestVersion: meta.manifestVersion,
        background: meta.background,
        contentScripts: meta.contentScripts,
        homepage: meta.homepage,
        updateUrl: meta.updateUrl,
        author: meta.author,
        size: this.getDirSize(finalDir)
      };
      ExtensionRepository.save(profilePath, db);
      
      return { ok: true, extId, name: meta.name, version: meta.version };
    } catch (e) {
      if (fs.existsSync(tempDir)) {
        fs.rmSync(tempDir, { recursive: true, force: true });
      }
      throw e;
    }
  }

  static getDirSize(dirPath) {
    let size = 0;
    if (!fs.existsSync(dirPath)) return size;
    const files = fs.readdirSync(dirPath);
    for (const file of files) {
      const p = path.join(dirPath, file);
      const stat = fs.statSync(p);
      if (stat.isDirectory()) {
        size += this.getDirSize(p);
      } else {
        size += stat.size;
      }
    }
    return size;
  }
}

class CRXInstaller {
  static install(crxFilePath, profilePath, customId = null) {
    // Để cài đặt file CRX, chúng ta cần loại bỏ header CRX và giải nén phần ZIP còn lại
    const buffer = fs.readFileSync(crxFilePath);
    
    // Kiểm tra magic header ("Cr24")
    const magic = buffer.toString('utf8', 0, 4);
    if (magic !== 'Cr24') {
      throw new Error('Tệp tin không phải là định dạng CRX hợp lệ (Thiếu magic header).');
    }

    const version = buffer.readUInt32LE(4);
    let zipStartOffset = 0;

    if (version === 2) {
      const pubkeyLength = buffer.readUInt32LE(8);
      const signatureLength = buffer.readUInt32LE(12);
      zipStartOffset = 16 + pubkeyLength + signatureLength;
    } else if (version === 3) {
      const headerLength = buffer.readUInt32LE(8);
      zipStartOffset = 12 + headerLength;
    } else {
      throw new Error('Phiên bản CRX không được hỗ trợ (chỉ hỗ trợ CRX2 và CRX3): ' + version);
    }

    if (zipStartOffset >= buffer.length) {
      throw new Error('Lỗi giải mã cấu trúc tệp CRX: Kích thước header vượt quá dung lượng tệp.');
    }

    // Cắt phần zip buffer
    const zipBuffer = buffer.subarray(zipStartOffset);
    const tempZipPath = path.join(profilePath, `temp_${Date.now()}.zip`);
    fs.writeFileSync(tempZipPath, zipBuffer);

    try {
      const res = ZIPInstaller.install(tempZipPath, profilePath, customId);
      if (res.ok && dbHasExt(profilePath, res.extId)) {
        // Ghi lại nguồn là crx
        const db = ExtensionRepository.load(profilePath);
        if (db[res.extId]) {
          db[res.extId].source = customId ? 'webstore' : 'crx';
          ExtensionRepository.save(profilePath, db);
        }
      }
      fs.unlinkSync(tempZipPath);
      return res;
    } catch (e) {
      if (fs.existsSync(tempZipPath)) fs.unlinkSync(tempZipPath);
      throw e;
    }
  }
}

function dbHasExt(profilePath, extId) {
  const db = ExtensionRepository.load(profilePath);
  return !!db[extId];
}

/**
 * ChromeStoreInstaller: Tải trực tiếp gói cài đặt từ Chrome Web Store
 */
class ChromeStoreInstaller {
  static downloadAndInstall(extId, profilePath) {
    return new Promise((resolve, reject) => {
      // Chuẩn hóa ID
      let cleanedId;
      try {
        cleanedId = validateChromeStoreId(extId.trim().replace(/https:\/\/chromewebstore\.google\.com\/detail\/[^\/]+\/([a-p]{32})/, '$1'));
      } catch {
        return reject(new Error('Extension ID không hợp lệ. Vui lòng nhập chuỗi ID Chrome Web Store.'));
      }

      const downloadUrl = `https://clients2.google.com/service/update2/crx?response=redirect&prodversion=120.0.0.0&acceptformat=crx2,crx3&x=id%3D${cleanedId}%26uc`;
      const tempCrxPath = path.join(profilePath, `download_${cleanedId}_${Date.now()}.crx`);
      
      const file = fs.createWriteStream(tempCrxPath);
      
      let receivedBytes = 0;
      const request = (url, depth = 0) => {
        if (depth > 5) return reject(new Error('Quá nhiều chuyển hướng khi tải extension'));
        let parsed;
        try {
          parsed = new URL(url);
          const allowedHost = parsed.hostname === 'google.com' ||
            parsed.hostname.endsWith('.google.com') ||
            parsed.hostname.endsWith('.googleusercontent.com') ||
            parsed.hostname.endsWith('.gvt1.com');
          if (parsed.protocol !== 'https:' || !allowedHost) throw new Error();
        } catch {
          return reject(new Error('Máy chủ tải extension không được phép'));
        }
        const req = https.get(parsed, { timeout: 15000 }, (response) => {
          // Xử lý chuyển hướng (Redirects)
          if (response.statusCode === 302 || response.statusCode === 301) {
            response.resume();
            request(new URL(response.headers.location, parsed).toString(), depth + 1);
            return;
          }

          if (response.statusCode !== 200) {
            fs.unlink(tempCrxPath, () => {});
            return reject(new Error(`Tải xuống thất bại. Máy chủ Google phản hồi mã lỗi: ${response.statusCode}`));
          }

          response.on('data', chunk => {
            receivedBytes += chunk.length;
            if (receivedBytes > 100 * 1024 * 1024) {
              req.destroy(new Error('Extension tải xuống vượt quá 100 MB'));
            }
          });
          response.pipe(file);
          
          file.on('finish', () => {
            file.close(async () => {
              try {
                const result = CRXInstaller.install(tempCrxPath, profilePath, cleanedId);
                fs.unlink(tempCrxPath, () => {});
                resolve(result);
              } catch (errInstall) {
                fs.unlink(tempCrxPath, () => {});
                reject(errInstall);
              }
            });
          });
        });
        req.on('timeout', () => req.destroy(new Error('Tải extension quá hạn')));
        req.on('error', (err) => {
          fs.unlink(tempCrxPath, () => {});
          reject(new Error('Lỗi kết nối tải tiện ích: ' + err.message));
        });
      };

      request(downloadUrl);
    });
  }
}

/**
 * ExtensionManagerService: Lớp điều phối trung tâm cung cấp tất cả các tác vụ quản lý
 */
class ExtensionManagerService {
  static getExtensions(profilePath) {
    return ExtensionRepository.load(profilePath);
  }

  static toggleExtension(profilePath, extId, enableState) {
    extId = validateExtensionId(extId);
    const db = ExtensionRepository.load(profilePath);
    if (!db[extId]) throw new Error('Không tìm thấy Extension với ID: ' + extId);
    db[extId].enabled = !!enableState;
    ExtensionRepository.save(profilePath, db);
    return { ok: true };
  }

  static toggleAllExtensions(profilePath, enableState) {
    const db = ExtensionRepository.load(profilePath);
    for (const key of Object.keys(db)) {
      db[key].enabled = !!enableState;
    }
    ExtensionRepository.save(profilePath, db);
    return { ok: true };
  }

  static uninstallExtension(profilePath, extId) {
    extId = validateExtensionId(extId);
    const db = ExtensionRepository.load(profilePath);
    if (!db[extId]) throw new Error('Không tìm thấy Extension với ID: ' + extId);
    
    // Xóa thư mục lưu trữ
    const extDir = resolveInside(ExtensionRepository.getExtensionsDir(profilePath), extId);
    if (fs.existsSync(extDir)) {
      fs.rmSync(extDir, { recursive: true, force: true });
    }
    
    delete db[extId];
    ExtensionRepository.save(profilePath, db);
    return { ok: true };
  }

  static cloneExtensions(srcProfilePath, destProfilePath) {
    const srcDb = ExtensionRepository.load(srcProfilePath);
    const destDb = ExtensionRepository.load(destProfilePath);
    
    const srcExtDir = ExtensionRepository.getExtensionsDir(srcProfilePath);
    const destExtDir = ExtensionRepository.getExtensionsDir(destProfilePath);

    for (const [id, ext] of Object.entries(srcDb)) {
      const safeId = validateExtensionId(id);
      const srcFolder = resolveInside(srcExtDir, safeId);
      const destFolder = resolveInside(destExtDir, safeId);
      
      if (fs.existsSync(srcFolder)) {
        if (fs.existsSync(destFolder)) {
          fs.rmSync(destFolder, { recursive: true, force: true });
        }
        
        // Copy thư mục
        const copyDirSync = (src, dest) => {
          fs.mkdirSync(dest, { recursive: true });
          const entries = fs.readdirSync(src, { withFileTypes: true });
          for (const entry of entries) {
            const srcPath = path.join(src, entry.name);
            const destPath = path.join(dest, entry.name);
            if (entry.isDirectory()) {
              copyDirSync(srcPath, destPath);
            } else {
              fs.copyFileSync(srcPath, destPath);
            }
          }
        };
        copyDirSync(srcFolder, destFolder);
        
        // Clone bản ghi DB
        destDb[id] = { ...ext };
      }
    }
    ExtensionRepository.save(destProfilePath, destDb);
    return { ok: true };
  }
}

module.exports = {
  ExtensionRepository,
  ZIPInstaller,
  CRXInstaller,
  ChromeStoreInstaller,
  ExtensionManagerService
};
