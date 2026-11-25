const { contextBridge, ipcRenderer } = require('electron');

contextBridge.exposeInMainWorld('API', {
  // Window
  winMin:   () => ipcRenderer.send('win-min'),
  winMax:   () => ipcRenderer.send('win-max'),
  winClose: () => ipcRenderer.send('win-close'),

  // Profiles
  profilesGet:        ()          => ipcRenderer.invoke('profiles-get'),
  profilesCreate:     (d)         => ipcRenderer.invoke('profiles-create', d),
  profilesUpdate:     (n, d)      => ipcRenderer.invoke('profiles-update', n, d),
  profilesRename:     (o, n)       => ipcRenderer.invoke('profiles-rename', o, n),
  profilesDelete:     (n)         => ipcRenderer.invoke('profiles-delete', n),
  profilesDeleteBulk: (ns)        => ipcRenderer.invoke('profiles-delete-bulk', ns),
  profilesDuplicate:  (n)         => ipcRenderer.invoke('profiles-duplicate', n),
  profilesOpen:       (n, px, url) => ipcRenderer.invoke('profiles-open', n, px, url),
  profilesOpenBulk:   (ns)        => ipcRenderer.invoke('profiles-open-bulk', ns),
  profilesImport:     (t)         => ipcRenderer.invoke('profiles-import', t),
  profilesExport:     (f)         => ipcRenderer.invoke('profiles-export', f),
  profilesClose:      (n)         => ipcRenderer.invoke('profiles-close', n),
  profilesCloseAll:   ()          => ipcRenderer.invoke('profiles-close-all'),
  profilesClearData:  (n, t)      => ipcRenderer.invoke('profiles-clear-data', n, t),
  groupsGet:          ()          => ipcRenderer.invoke('groups-get'),
  groupsSave:         (gs)        => ipcRenderer.invoke('groups-save', gs),

  // Backup
  backupCreate:   (n)  => ipcRenderer.invoke('backup-create', n),
  backupList:     ()    => ipcRenderer.invoke('backup-list'),
  backupRestore:  (n)   => ipcRenderer.invoke('backup-restore', n),
  backupDelete:   (n)   => ipcRenderer.invoke('backup-delete', n),

  // Proxy
  proxyFetch: (d)  => ipcRenderer.invoke('proxy-fetch', d),
  proxyCheck: (p)  => ipcRenderer.invoke('proxy-check', p),
  proxyCheckFull: (p) => ipcRenderer.invoke('proxy-check-full', p),
  proxyRotate: (d) => ipcRenderer.invoke('proxy-rotate', d),

  // Config
  cfgGet:            ()  => ipcRenderer.invoke('cfg-get'),
  cfgSave:           (d) => ipcRenderer.invoke('cfg-save', d),
  cfgBrowseChrome:   ()  => ipcRenderer.invoke('cfg-browse-chrome'),
  cfgDetectChrome:   ()  => ipcRenderer.invoke('cfg-detect-chrome'),
  cfgOpenChromeDownload: () => ipcRenderer.invoke('cfg-open-chrome-download'),
  secureStateGet:     (key)        => ipcRenderer.invoke('secure-state-get', key),
  secureStateSet:     (key, value) => ipcRenderer.invoke('secure-state-set', key, value),

  // Logs
  logsGet:    ()  => ipcRenderer.invoke('logs-get'),
  logsClear:  ()  => ipcRenderer.invoke('logs-clear'),
  logsOpenDir:()  => ipcRenderer.invoke('logs-open-dir'),

  // Utils
  openProfilesDir: ()    => ipcRenderer.invoke('utils-open-profiles-dir'),
  openBackupsDir:  ()    => ipcRenderer.invoke('utils-open-backups-dir'),
  getVersion:      ()    => ipcRenderer.invoke('utils-version'),
  getPlatform:     ()    => ipcRenderer.invoke('utils-platform'),
  getRunning:      ()     => ipcRenderer.invoke('utils-running'),
  openUrl:         (url) => ipcRenderer.invoke('utils-open-url', url),
  readPage:        (n)   => ipcRenderer.invoke('utils-read-page', n),
  
  // Extension Manager
  extensionsGet:          (pName) => ipcRenderer.invoke('extensions-get', pName),
  extensionsToggle:       (pName, id, state) => ipcRenderer.invoke('extensions-toggle', pName, id, state),
  extensionsToggleAll:    (pName, state) => ipcRenderer.invoke('extensions-toggle-all', pName, state),
  extensionsUninstall:    (pName, id) => ipcRenderer.invoke('extensions-uninstall', pName, id),
  extensionsClone:        (src, dest) => ipcRenderer.invoke('extensions-clone', src, dest),
  extensionsInstallStore: (pName, id) => ipcRenderer.invoke('extensions-install-store', pName, id),
  extensionsInstallFile:  (pName, path, type) => ipcRenderer.invoke('extensions-install-file', pName, path, type),
  extensionsOpenFolder:   (pName, id) => ipcRenderer.invoke('extensions-open-folder', pName, id),

  // License & Auth
  getLicenseInfo:  ()    => ipcRenderer.invoke('license-get-info'),
  activateLicense: (k)   => ipcRenderer.invoke('license-activate', k),
  logoutLicense:   ()    => ipcRenderer.invoke('license-logout'),
  loginGoogle:     ()    => ipcRenderer.invoke('license-login-google'),
  loginManual:     (t)   => ipcRenderer.invoke('license-login-manual', t),

  // Admin APIs
  adminLogin:      (u, p)     => ipcRenderer.invoke('admin-login', u, p),
  adminLogout:     ()         => ipcRenderer.invoke('admin-logout'),
  adminApiCall:    (act, pms) => ipcRenderer.invoke('admin-api-call', act, pms),
  adminSaveGasPass:(pass)     => ipcRenderer.invoke('admin-save-gas-password', pass),
  adminGetGasPass:()         => ipcRenderer.invoke('admin-get-gas-password'),

  // Events
  onLog:           (cb) => ipcRenderer.on('log', (_, d) => cb(d)),
  offLog:          ()   => ipcRenderer.removeAllListeners('log'),
  onAnnouncement:  (cb) => ipcRenderer.on('announcement-received', (_, ann) => cb(ann)),
  offAnnouncement: ()   => ipcRenderer.removeAllListeners('announcement-received'),
  onLicenseStatus: (cb) => ipcRenderer.on('license-status-changed', (_, status) => cb(status)),
  offLicenseStatus:()   => ipcRenderer.removeAllListeners('license-status-changed'),
  onLoginSuccess:  (cb) => ipcRenderer.on('login-success', (_, user) => cb(user)),
  offLoginSuccess: ()   => ipcRenderer.removeAllListeners('login-success'),

  shopeeGetAnnouncements: () => ipcRenderer.invoke('shopee-get-announcements'),
  shopeeGetLatestVersion: () => ipcRenderer.invoke('shopee-get-latest-version')
});
