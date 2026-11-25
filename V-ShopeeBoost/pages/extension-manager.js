/**
 * V-ShopeeBoost — Extension Manager Frontend Controller
 * Extracted from app.js to prevent memory leaks and improve maintainability.
 */
function initExtensionManagerPage() {
  const state = {
    activeProfile: null,
    selectedProfiles: new Set(),
    extensions: {},
    filterStatus: 'all',
    filterSource: 'all',
    searchQuery: '',
    searchProfileQuery: ''
  };

  // Helper log nội bộ
  function addLog(msg, type = 'info') {
    const time = new Date().toLocaleTimeString('vi-VN');
    const container = $('extLogsContainer');
    if (!container) return;
    const row = document.createElement('div');
    row.className = 'log-row';
    const safeType = ['info','success','warning','error'].includes(type) ? type : 'info';
    row.innerHTML = `<span class="log-time">[${escapeHTML(time)}]</span> <span class="log-${safeType}">${escapeHTML(msg)}</span>`;
    container.appendChild(row);
    container.scrollTop = container.scrollHeight;
  }

  // Khởi tạo danh sách Profile
  function renderProfilesList() {
    const container = $('profileListContainer');
    if (!container) return;
    container.innerHTML = '';
    
    const query = state.searchProfileQuery.toLowerCase();
    const filtered = S.profiles.filter(p => p.name.toLowerCase().includes(query));
    
    if (filtered.length === 0) {
      container.innerHTML = `<div style="color:var(--text-muted); font-size:12px; text-align:center; padding: 20px;">Không tìm thấy profile.</div>`;
      return;
    }

    filtered.forEach(p => {
      const isChecked = state.selectedProfiles.has(p.name);
      const isActive = state.activeProfile && state.activeProfile.name === p.name;
      
      const firstChar = p.name ? p.name.charAt(0).toUpperCase() : 'P';
      let charSum = 0;
      for (let i = 0; i < p.name.length; i++) charSum += p.name.charCodeAt(i);
      const hue = charSum % 360;
      const avatarStyle = `background: hsla(${hue}, 70%, 50%, 0.1); color: hsl(${hue}, 70%, 50%);`;

      const row = document.createElement('div');
      row.className = `ext-profile-row${isActive ? ' active' : ''}`;
      row.innerHTML = `
        <label class="ext-checkbox-container" style="margin: 0; cursor: pointer; flex-shrink: 0;">
          <input type="checkbox" class="profile-checkbox-item" data-name="${escapeHTML(p.name)}" ${isChecked ? 'checked' : ''} style="display: none;">
          <span class="ext-checkmark"></span>
        </label>
        <div class="ext-profile-avatar" style="width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; ${avatarStyle} flex-shrink: 0; margin-left: 2px;">
          ${escapeHTML(firstChar)}
        </div>
        <span style="font-size: 12.5px; font-weight: 500; color: var(--text-primary); flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; margin-left: 6px;">${escapeHTML(p.name)}</span>
      `;
      
      row.addEventListener('click', (e) => {
        if (e.target.closest('.ext-checkbox-container')) return;
        state.activeProfile = p;
        $$('.ext-profile-row').forEach(r => r.classList.remove('active'));
        row.classList.add('active');
        addLog(`Đang tải danh sách extension cho profile: ${p.name}...`, 'info');
        loadExtensionsForActiveProfile();
      });
      
      row.querySelector('.profile-checkbox-item').addEventListener('change', (e) => {
        if (e.target.checked) {
          state.selectedProfiles.add(p.name);
        } else {
          state.selectedProfiles.delete(p.name);
        }
        $('selectedProfilesCount').textContent = `${state.selectedProfiles.size} đã chọn`;
      });
      
      container.appendChild(row);
    });
  }

  // Load extension cho profile đang chọn
  async function loadExtensionsForActiveProfile() {
    if (!state.activeProfile) return;
    try {
      state.extensions = await API.extensionsGet(state.activeProfile.name);
      renderExtensionsTable();
    } catch (e) {
      addLog(`Lỗi tải danh sách extensions: ${e.message}`, 'error');
    }
  }

  // Render bảng extension
  function renderExtensionsTable() {
    const tbody = $('extensionsTableBody');
    if (!tbody) return;
    tbody.innerHTML = '';
    
    const exts = Object.values(state.extensions);
    if (exts.length === 0) {
      tbody.innerHTML = `<tr><td colspan="9" style="text-align: center; color: var(--text-muted); padding: 40px;">Không có extension nào được cài đặt trong profile này</td></tr>`;
      return;
    }
    
    const searchQuery = state.searchQuery.toLowerCase();
    const filtered = exts.filter(ext => {
      const matchesSearch = ext.name.toLowerCase().includes(searchQuery) || ext.id.toLowerCase().includes(searchQuery);
      if (!matchesSearch) return false;
      
      if (state.filterStatus === 'enabled' && !ext.enabled) return false;
      if (state.filterStatus === 'disabled' && ext.enabled) return false;
      
      if (state.filterSource !== 'all' && ext.source !== state.filterSource) return false;
      
      return true;
    });

    if (filtered.length === 0) {
      tbody.innerHTML = `<tr><td colspan="9" style="text-align: center; color: var(--text-muted); padding: 40px;">Không tìm thấy extension phù hợp với bộ lọc</td></tr>`;
      return;
    }

    filtered.forEach(ext => {
      const tr = document.createElement('tr');
      tr.dataset.id = ext.id;
      
      const sizeMB = ext.size ? (ext.size / (1024 * 1024)).toFixed(2) + ' MB' : 'N/A';
      const safeSource = ['zip','crx','webstore'].includes(String(ext.source).toLowerCase()) ? String(ext.source).toLowerCase() : 'zip';
      const sourceBadge = `<span class="ext-badge-type ${safeSource}">${escapeHTML(ext.source)}</span>`;
      
      tr.innerHTML = `
        <td>
          <label class="ext-checkbox-container" style="margin: 0; cursor: pointer; display: inline-block;">
            <input type="checkbox" class="ext-row-checkbox" data-id="${escapeHTML(ext.id)}" style="display: none;">
            <span class="ext-checkmark"></span>
          </label>
        </td>
        <td><img class="ext-icon-img" src="./image/chromium.png" onerror="this.src='./image/chromium.png'"></td>
        <td style="font-weight: 600; color: var(--text-primary); cursor: pointer;" class="btn-show-details">${escapeHTML(ext.name)}</td>
        <td>${escapeHTML(ext.version)}</td>
        <td style="font-family: var(--font-mono); font-size:11px; color: var(--text-muted); max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${escapeHTML(ext.id)}">${escapeHTML(ext.id)}</td>
        <td>${sourceBadge}</td>
        <td>${sizeMB}</td>
        <td>
          <div class="toggle-switch">
            <input type="checkbox" id="toggle-${escapeHTML(ext.id)}" ${ext.enabled ? 'checked' : ''} class="ext-enable-toggle">
            <span class="toggle-slider"></span>
          </div>
        </td>
        <td style="text-align: right;">
          <button class="btn-ghost btn-sm btn-delete-ext" style="color: var(--red); padding: 4px 8px;">Xoá</button>
        </td>
      `;
      
      // Bật/tắt
      tr.querySelector('.ext-enable-toggle').addEventListener('change', async (e) => {
        const stateToSet = e.target.checked;
        try {
          await API.extensionsToggle(state.activeProfile.name, ext.id, stateToSet);
          addLog(`Đã ${stateToSet ? 'bật' : 'tắt'} extension "${ext.name}" trên profile ${state.activeProfile.name}`, 'success');
          loadExtensionsForActiveProfile();
        } catch (err) {
          addLog(`Lỗi khi đổi trạng thái extension: ${err.message}`, 'error');
          e.target.checked = !stateToSet;
        }
      });
      
      // Xem chi tiết
      tr.querySelector('.btn-show-details').addEventListener('click', () => showExtensionDetails(ext));
      
      // Xoá
      tr.querySelector('.btn-delete-ext').addEventListener('click', async (e) => {
        e.stopPropagation();
        if (await confirm(`Bạn có chắc chắn muốn xoá Extension "${ext.name}" khỏi profile này không?`)) {
          try {
            await API.extensionsUninstall(state.activeProfile.name, ext.id);
            addLog(`Đã xoá extension "${ext.name}" khỏi profile ${state.activeProfile.name}`, 'success');
            loadExtensionsForActiveProfile();
          } catch (err) {
            addLog(`Lỗi khi xoá extension: ${err.message}`, 'error');
          }
        }
      });

      // Context menu
      tr.addEventListener('contextmenu', (e) => {
        e.preventDefault();
        showContextMenu(e, ext);
      });
      
      tbody.appendChild(tr);
    });
  }

  // Hiển thị chi tiết Extension
  function showExtensionDetails(ext) {
    const drawer = $('extDetailsDrawer');
    if (!drawer) return;
    
    $('detailIcon').src = ext.icon || './image/chromium.png';
    $('detailName').textContent = ext.name;
    $('detailVersion').textContent = 'Phiên bản: ' + ext.version;
    $('detailId').textContent = ext.id;
    $('detailDesc').textContent = ext.description || 'Không có mô tả';
    $('detailPermissions').textContent = (ext.permissions || []).join(', ') || 'Không có quyền nào';
    $('detailBackground').textContent = ext.background || 'None';
    $('detailContentScripts').textContent = ext.contentScripts || 'None';
    $('detailFolder').textContent = ext.path || '-';
    
    drawer.classList.add('open');

    // Mở thư mục
    $('btnDetailOpenFolder').onclick = () => {
      API.extensionsOpenFolder(state.activeProfile.name, ext.id);
    };

    // Mở store
    $('btnDetailOpenStore').onclick = () => {
      API.openUrl(`https://chromewebstore.google.com/detail/${ext.id}`);
    };
  }

  // Đóng Drawer
  $('btnCloseDetailsDrawer')?.addEventListener('click', () => {
    $('extDetailsDrawer')?.classList.remove('open');
  });

  // Hiển thị Context Menu chuột phải
  function showContextMenu(e, ext) {
    const menu = $('extContextMenu');
    if (!menu) return;
    
    menu.style.left = e.clientX + 'px';
    menu.style.top = e.clientY + 'px';
    menu.style.display = 'block';

    // Xử lý các nút trong context menu
    menu.querySelectorAll('.context-menu-item').forEach(item => {
      item.onclick = async () => {
        const action = item.dataset.action;
        menu.style.display = 'none';
        
        if (action === 'toggle') {
          try {
            await API.extensionsToggle(state.activeProfile.name, ext.id, !ext.enabled);
            addLog(`Đã thay đổi trạng thái extension: ${ext.name}`, 'success');
            loadExtensionsForActiveProfile();
          } catch (err) {
            addLog(`Lỗi: ${err.message}`, 'error');
          }
        } else if (action === 'open-folder') {
          API.extensionsOpenFolder(state.activeProfile.name, ext.id);
        } else if (action === 'open-store') {
          API.openUrl(`https://chromewebstore.google.com/detail/${ext.id}`);
        } else if (action === 'copy-id') {
          navigator.clipboard.writeText(ext.id);
          toast('Đã copy ID!', 'success');
        } else if (action === 'copy-name') {
          navigator.clipboard.writeText(ext.name);
          toast('Đã copy tên!', 'success');
        } else if (action === 'delete') {
          if (await confirm(`Bạn có muốn xóa extension ${ext.name}?`)) {
            await API.extensionsUninstall(state.activeProfile.name, ext.id);
            loadExtensionsForActiveProfile();
          }
        }
      };
    });

    // Tự động đóng menu khi click ra ngoài
    const closeHandler = () => {
      menu.style.display = 'none';
      document.removeEventListener('click', closeHandler);
    };
    setTimeout(() => document.addEventListener('click', closeHandler), 10);
  }

  // Bind các sự kiện lọc, tìm kiếm
  $('searchProfileInput')?.addEventListener('input', (e) => {
    state.searchProfileQuery = e.target.value;
    renderProfilesList();
  });

  $('searchExtInput')?.addEventListener('input', (e) => {
    state.searchQuery = e.target.value;
    renderExtensionsTable();
  });

  $('filterExtStatus')?.addEventListener('change', (e) => {
    state.filterStatus = e.target.value;
    renderExtensionsTable();
  });

  $('filterExtSource')?.addEventListener('change', (e) => {
    state.filterSource = e.target.value;
    renderExtensionsTable();
  });

  // Chọn tất cả profile
  $('btnSelectAllProfiles')?.addEventListener('click', () => {
    S.profiles.forEach(p => state.selectedProfiles.add(p.name));
    renderProfilesList();
    $('selectedProfilesCount').textContent = `${state.selectedProfiles.size} được chọn`;
  });

  // Đảo chọn profile
  $('btnInvertProfiles')?.addEventListener('click', () => {
    S.profiles.forEach(p => {
      if (state.selectedProfiles.has(p.name)) {
        state.selectedProfiles.delete(p.name);
      } else {
        state.selectedProfiles.add(p.name);
      }
    });
    renderProfilesList();
    $('selectedProfilesCount').textContent = `${state.selectedProfiles.size} được chọn`;
  });

  // Refresh
  $('btnRefreshExtensions')?.addEventListener('click', () => {
    loadExtensionsForActiveProfile();
  });

  // Bulk Enable/Disable cho các Profile được check
  const toggleBulkSettings = async (enableState) => {
    if (state.selectedProfiles.size === 0) {
      toast('Vui lòng chọn ít nhất một Profile bên trái!', 'warning');
      return;
    }

    const profiles = Array.from(state.selectedProfiles);
    const progressModal = $('bulkProgressModal');
    const progressTitle = $('progressTitle');
    const progressBarFill = $('progressBarFill');
    const progressText = $('progressText');

    progressTitle.textContent = enableState ? 'Đang kích hoạt Extension...' : 'Đang vô hiệu hóa Extension...';
    progressBarFill.style.width = '0%';
    progressText.textContent = `0 / ${profiles.length} Profiles`;
    progressModal.style.display = 'flex';

    for (let i = 0; i < profiles.length; i++) {
      const pName = profiles[i];
      try {
        await API.extensionsToggleAll(pName, enableState);
        addLog(`Đã cấu hình lại extension cho profile: ${pName}`, 'success');
      } catch (err) {
        addLog(`Lỗi xử lý profile ${pName}: ${err.message}`, 'error');
      }
      const percent = Math.round(((i + 1) / profiles.length) * 100);
      progressBarFill.style.width = `${percent}%`;
      progressText.textContent = `${i + 1} / ${profiles.length} Profiles`;
      await new Promise(r => setTimeout(r, 50));
    }

    setTimeout(() => {
      progressModal.style.display = 'none';
      toast('Đã hoàn thành cấu hình hàng loạt!', 'success');
      loadExtensionsForActiveProfile();
    }, 500);
  };

  $('btnBulkEnable')?.addEventListener('click', () => toggleBulkSettings(true));
  $('btnBulkDisable')?.addEventListener('click', () => toggleBulkSettings(false));

  // Cài đặt từ Web Store
  $('btnInstallStore')?.addEventListener('click', () => {
    if (state.selectedProfiles.size === 0) {
      toast('Vui lòng chọn ít nhất một Profile bên trái để cài đặt!', 'warning');
      return;
    }
    $('storeUrlOrIdInput').value = '';
    openModal('installStoreModal');
  });

  $('btnConfirmInstallStore')?.addEventListener('click', async () => {
    const rawVal = $('storeUrlOrIdInput').value.trim();
    if (!rawVal) {
      toast('Vui lòng nhập ID hoặc Link Web Store', 'warning');
      return;
    }

    let extId = rawVal;
    const match = rawVal.match(/\/detail\/[^\/]+\/([a-z]{32})/);
    if (match) {
      extId = match[1];
    }

    if (!/^[a-z]{32}$/.test(extId)) {
      toast('Định dạng Web Store Link hoặc Extension ID không hợp lệ!', 'error');
      return;
    }

    closeModal('installStoreModal');

    const profiles = Array.from(state.selectedProfiles);
    const progressModal = $('bulkProgressModal');
    const progressTitle = $('progressTitle');
    const progressBarFill = $('progressBarFill');
    const progressText = $('progressText');

    progressTitle.textContent = `Đang tải và cài đặt Extension: ${extId}...`;
    progressBarFill.style.width = '0%';
    progressText.textContent = `0 / ${profiles.length} Profiles`;
    progressModal.style.display = 'flex';

    addLog(`Bắt đầu tải và cài đặt extension ${extId} cho ${profiles.length} profiles...`, 'info');

    for (let i = 0; i < profiles.length; i++) {
      const pName = profiles[i];
      try {
        await API.extensionsInstallStore(pName, extId);
        addLog(`Đã cài thành công extension ${extId} vào profile ${pName}`, 'success');
      } catch (err) {
        addLog(`Cài đặt thất bại cho profile ${pName}: ${err.message}`, 'error');
      }
      const percent = Math.round(((i + 1) / profiles.length) * 100);
      progressBarFill.style.width = `${percent}%`;
      progressText.textContent = `${i + 1} / ${profiles.length} Profiles`;
    }

    setTimeout(() => {
      progressModal.style.display = 'none';
      toast('Đã hoàn tất quá trình cài đặt hàng loạt!', 'success');
      loadExtensionsForActiveProfile();
    }, 500);
  });

  // Xử lý nạp File ZIP / CRX
  let fileSelector = document.getElementById('extFileSelector');
  if (!fileSelector) {
    fileSelector = document.createElement('input');
    fileSelector.type = 'file';
    fileSelector.id = 'extFileSelector';
    fileSelector.style.display = 'none';
    document.body.appendChild(fileSelector);
  }

  const handleFileInstall = (fileType) => {
    if (state.selectedProfiles.size === 0) {
      toast('Vui lòng chọn ít nhất một Profile bên trái để cài đặt!', 'warning');
      return;
    }
    fileSelector.accept = fileType === 'crx' ? '.crx' : '.zip';
    fileSelector.onchange = async (e) => {
      const file = e.target.files[0];
      if (!file) return;
      
      const filePath = file.path;
      const profiles = Array.from(state.selectedProfiles);
      
      const progressModal = $('bulkProgressModal');
      const progressTitle = $('progressTitle');
      const progressBarFill = $('progressBarFill');
      const progressText = $('progressText');

      progressTitle.textContent = `Đang cài đặt gói ${fileType.toUpperCase()}: ${file.name}...`;
      progressBarFill.style.width = '0%';
      progressText.textContent = `0 / ${profiles.length} Profiles`;
      progressModal.style.display = 'flex';

      addLog(`Bắt đầu cài đặt file ${file.name} cho ${profiles.length} profiles...`, 'info');

      for (let i = 0; i < profiles.length; i++) {
        const pName = profiles[i];
        try {
          await API.extensionsInstallFile(pName, filePath, fileType);
          addLog(`Đã cài đặt thành công gói ${fileType.toUpperCase()} cho profile ${pName}`, 'success');
        } catch (err) {
          addLog(`Cài đặt gói thất bại cho profile ${pName}: ${err.message}`, 'error');
        }
        const percent = Math.round(((i + 1) / profiles.length) * 100);
        progressBarFill.style.width = `${percent}%`;
        progressText.textContent = `${i + 1} / ${profiles.length} Profiles`;
      }

      setTimeout(() => {
        progressModal.style.display = 'none';
        toast('Đã cài đặt thành công gói file!', 'success');
        loadExtensionsForActiveProfile();
      }, 500);
    };
    fileSelector.click();
  };

  $('btnImportCRX')?.addEventListener('click', () => handleFileInstall('crx'));
  $('btnImportZIP')?.addEventListener('click', () => handleFileInstall('zip'));

  // Sync Extension từ Active Profile sang các Profile khác
  $('btnSyncExtensions')?.addEventListener('click', async () => {
    if (!state.activeProfile) {
      toast('Vui lòng chọn profile gốc ở danh sách bên trái để lấy dữ liệu!', 'warning');
      return;
    }
    if (state.selectedProfiles.size === 0) {
      toast('Vui lòng chọn ít nhất một Profile đích ở danh sách bên trái để đồng bộ!', 'warning');
      return;
    }

    const src = state.activeProfile.name;
    const dests = Array.from(state.selectedProfiles).filter(p => p !== src);
    
    if (dests.length === 0) {
      toast('Vui lòng chọn các Profile đích khác với Profile gốc!', 'warning');
      return;
    }

    if (await confirm(`Bạn có chắc chắn muốn đồng bộ (sao chép) toàn bộ Extension từ Profile "${src}" sang ${dests.length} Profile đã chọn không? (Ghi đè nếu trùng lặp)`)) {
      const progressModal = $('bulkProgressModal');
      const progressTitle = $('progressTitle');
      const progressBarFill = $('progressBarFill');
      const progressText = $('progressText');

      progressTitle.textContent = `Đang đồng bộ Extensions từ ${src}...`;
      progressBarFill.style.width = '0%';
      progressText.textContent = `0 / ${dests.length} Profiles`;
      progressModal.style.display = 'flex';

      for (let i = 0; i < dests.length; i++) {
        const dest = dests[i];
        try {
          await API.extensionsClone(src, dest);
          addLog(`Đã đồng bộ toàn bộ extension từ ${src} sang ${dest}`, 'success');
        } catch (err) {
          addLog(`Đồng bộ thất bại sang ${dest}: ${err.message}`, 'error');
        }
        const percent = Math.round(((i + 1) / dests.length) * 100);
        progressBarFill.style.width = `${percent}%`;
        progressText.textContent = `${i + 1} / ${dests.length} Profiles`;
      }

      setTimeout(() => {
        progressModal.style.display = 'none';
        toast('Đã đồng bộ thành công!', 'success');
      }, 500);
    }
  });

  // Khởi chạy vẽ danh sách ban đầu
  renderProfilesList();
}
