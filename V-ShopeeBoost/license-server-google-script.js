/**
 * GOOGLE APPS SCRIPT - QUẢN LÝ BẢN QUYỀN V-SHOPEEBOOST (Bản PRO có Admin Panel)
 * 
 * Hướng dẫn thiết lập:
 * 1. Tạo 1 file Google Sheets trên Google Drive.
 * 2. Đặt tên trang tính là: "Keys"
 * 3. Tạo các cột ở dòng 1:
 *    A1: Key
 *    B1: HWID (Để trống khi chưa kích hoạt)
 *    C1: ExpiryDate (Định dạng: YYYY-MM-DD, ví dụ: 2026-12-31)
 *    D1: Status (Active / Blocked)
 *    E1: Note (Tên khách hàng, ghi chú...)
 * 4. Vào menu Tiện ích mở rộng (Extensions) -> Apps Script.
 * 5. Xóa hết code cũ, dán đoạn code này vào.
 * 6. Trong Project Settings → Script Properties, tạo ADMIN_PASSWORD với mật khẩu mạnh.
 * 7. Bấm nút Deploy (Triển khai) -> New deployment (Triển khai mới).
 * 8. Chọn loại triển khai: Web app (Ứng dụng web).
 * 9. Cấu hình:
 *    - Execute as: Me (Tôi)
 *    - Who has access: Anyone (Mọi người)
 * 10. Bấm Deploy, cấp quyền nếu được yêu cầu và COPY lại đường dẫn Web App URL.
 * 11. Dán Web App URL này vào biến LICENSE_API_URL trong file main.js của tool.
 * 12. Để vào trang Admin: Mở trực tiếp Web App URL này trên trình duyệt của bạn!
 */

// Mật khẩu không được đặt trong source. Cấu hình bằng Script Properties.
var ADMIN_PASSWORD = PropertiesService.getScriptProperties().getProperty("ADMIN_PASSWORD");

function doGet(e) {
  var action = e.parameter.action;
  
  // Nếu không có action hoặc action là admin, trả về giao diện quản trị Admin Panel
  if (!action || action === 'admin') {
    return HtmlService.createHtmlOutput(getAdminUI())
      .setTitle("V-ShopeeBoost Pro - Admin Panel")
      .setXFrameOptionsMode(HtmlService.XFrameOptionsMode.ALLOWALL)
      .addMetaTag('viewport', 'width=device-width, initial-scale=1');
  }
  
  return handleRequest(e);
}

function doPost(e) {
  return handleRequest(e);
}

function handleRequest(e) {
  try {
    var params = e.parameter;
    if (e.postData && e.postData.contents) {
      try {
        params = JSON.parse(e.postData.contents);
      } catch(err) {}
    }
    
    var action = params.action || 'check';
    var key = params.key;
    var hwid = params.hwid;
    var password = params.password;
    
    // --- XỬ LÝ CÁC API ADMIN PANEL (YÊU CẦU MẬT KHẨU) ---
    if (action.startsWith('admin_')) {
      if (!ADMIN_PASSWORD) {
        return responseJSON({ ok: false, msg: "Admin chưa được cấu hình an toàn" });
      }
      if (!password || password !== ADMIN_PASSWORD) {
        return responseJSON({ ok: false, msg: "Mật khẩu Admin không chính xác" });
      }
      
      var sheet = getSheet();
      var usersSheet = getUsersSheet();
      var annSheet = getAnnouncementsSheet();
      
      switch (action) {
        case 'admin_list':
          return listKeys(sheet);
        case 'admin_create':
          return createKey(sheet, params);
        case 'admin_update':
          return updateKey(sheet, params);
        case 'admin_delete':
          return deleteKey(sheet, params.keyToDelete);
        case 'admin_list_users':
          return listUsers(usersSheet);
        case 'admin_renew_user_key':
          return renewUserKey(usersSheet, sheet, params);
        case 'admin_delete_user':
          return deleteUser(usersSheet, params.emailToDelete);
        case 'admin_send_user_notification':
          return sendUserNotification(usersSheet, params);
        case 'admin_send_broadcast':
          return sendBroadcast(annSheet, params);
        default:
          return responseJSON({ ok: false, msg: "Hành động Admin không hợp lệ" });
      }
    }
    
    // --- XỬ LÝ API CLIENT ---
    if (action === 'login_or_register') {
      return loginOrRegister(params);
    }
    
    if (action === 'activate_user_key') {
      return activateUserKey(params);
    }
    
    if (action === 'get_announcements') {
      return getAnnouncements(params);
    }
    
    // --- BẢN CŨ CHỈ CHECK KEY ---
    if (!key) {
      return responseJSON({ ok: false, msg: "Thiếu mã kích hoạt (Key)" });
    }
    
    var sheet = getSheet();
    if (!sheet) {
      return responseJSON({ ok: false, msg: "Không tìm thấy bảng tính Keys" });
    }
    
    var data = sheet.getDataRange().getValues();
    var headers = data[0];
    
    var colKey = headers.indexOf("Key");
    var colHWID = headers.indexOf("HWID");
    var colExpiry = headers.indexOf("ExpiryDate");
    var colStatus = headers.indexOf("Status");
    
    if (colKey === -1 || colHWID === -1 || colExpiry === -1 || colStatus === -1) {
      return responseJSON({ ok: false, msg: "Cấu hình cột Google Sheets chưa đúng. Cần: Key, HWID, ExpiryDate, Status" });
    }
    
    var foundIndex = -1;
    for (var i = 1; i < data.length; i++) {
      if (data[i][colKey].toString().trim() === key.trim()) {
        foundIndex = i;
        break;
      }
    }
    
    if (foundIndex === -1) {
      return responseJSON({ ok: false, msg: "Mã kích hoạt (Key) không tồn tại hoặc không chính xác" });
    }
    
    var dbKey = data[foundIndex][colKey];
    var dbHWID = data[foundIndex][colHWID].toString().trim();
    var dbExpiryStr = data[foundIndex][colExpiry];
    var dbStatus = data[foundIndex][colStatus].toString().trim().toLowerCase();
    
    if (dbStatus === 'blocked' || dbStatus === 'block') {
      return responseJSON({ ok: false, msg: "Bản quyền này đã bị khóa hoặc tạm ngưng" });
    }
    
    var expiryDate = new Date(dbExpiryStr);
    var now = new Date();
    expiryDate.setHours(23, 59, 59, 999);
    
    if (isNaN(expiryDate.getTime())) {
      return responseJSON({ ok: false, msg: "Ngày hết hạn bản quyền không hợp lệ" });
    }
    
    if (now.getTime() > expiryDate.getTime()) {
      return responseJSON({ ok: false, msg: "Bản quyền đã hết hạn vào ngày " + formatDate(expiryDate) });
    }
    
    if (action === 'activate') {
      if (!hwid) {
        return responseJSON({ ok: false, msg: "Thiếu mã thiết bị (HWID)" });
      }
      
      if (dbHWID && dbHWID !== hwid) {
        return responseJSON({ ok: false, msg: "Mã kích hoạt này đã được sử dụng cho một thiết bị khác" });
      }
      
      if (!dbHWID) {
        sheet.getCell(foundIndex + 1, colHWID + 1).setValue(hwid);
        dbHWID = hwid;
      }
    } else {
      if (dbHWID && hwid && dbHWID !== hwid) {
        return responseJSON({ ok: false, msg: "Mã kích hoạt không đúng với thiết bị này" });
      }
    }
    
    var diffTime = expiryDate.getTime() - now.getTime();
    var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    return responseJSON({
      ok: true,
      msg: "Xác thực bản quyền thành công",
      expiry: formatDate(expiryDate),
      daysLeft: diffDays,
      hwid: dbHWID
    });
    
  } catch(e) {
    return responseJSON({ ok: false, msg: "Lỗi Server Google App Script: " + e.toString() });
  }
}

// --- HÀM TRỢ GIÚP BACKEND ---

function getSheet() {
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var sheet = ss.getSheetByName("Keys");
  if (!sheet) {
    sheet = ss.insertSheet("Keys");
    sheet.appendRow(["Key", "HWID", "ExpiryDate", "Status", "Note"]);
  }
  return sheet;
}

function getUsersSheet() {
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var sheet = ss.getSheetByName("Users");
  if (!sheet) {
    sheet = ss.insertSheet("Users");
    sheet.appendRow(["Email", "Name", "Avatar", "HWID", "ActiveKey", "ExpiryDate", "CreatedAt", "LastActive", "IP", "Notification"]);
  }
  return sheet;
}

function getAnnouncementsSheet() {
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var sheet = ss.getSheetByName("Announcements");
  if (!sheet) {
    sheet = ss.insertSheet("Announcements");
    sheet.appendRow(["Id", "Title", "Content", "TargetEmail", "CreatedAt", "Status"]);
  }
  return sheet;
}

function responseJSON(data) {
  return ContentService.createTextOutput(JSON.stringify(data))
    .setMimeType(ContentService.MimeType.JSON);
}

function formatDate(date) {
  var d = new Date(date),
      month = '' + (d.getMonth() + 1),
      day = '' + d.getDate(),
      year = d.getFullYear();

  if (month.length < 2) month = '0' + month;
  if (day.length < 2) day = '0' + day;

  return [year, month, day].join('-');
}

// --- CLIENT OAUTH & USER MANAGEMENT ---

function loginOrRegister(params) {
  var email = (params.email || "").trim();
  var name = (params.name || "").trim();
  var avatar = (params.avatar || "").trim();
  var hwid = (params.hwid || "").trim();
  var ip = (params.ip || "").trim();
  
  if (!email) {
    return responseJSON({ ok: false, msg: "Thiếu địa chỉ Email" });
  }
  
  var sheet = getUsersSheet();
  var data = sheet.getDataRange().getValues();
  var headers = data[0];
  
  var colEmail = headers.indexOf("Email");
  var colName = headers.indexOf("Name");
  var colAvatar = headers.indexOf("Avatar");
  var colHWID = headers.indexOf("HWID");
  var colActiveKey = headers.indexOf("ActiveKey");
  var colExpiry = headers.indexOf("ExpiryDate");
  var colLastActive = headers.indexOf("LastActive");
  var colIP = headers.indexOf("IP");
  var colNotif = headers.indexOf("Notification");
  
  var foundIndex = -1;
  for (var i = 1; i < data.length; i++) {
    if (data[i][colEmail].toString().trim().toLowerCase() === email.toLowerCase()) {
      foundIndex = i;
      break;
    }
  }
  
  var nowStr = new Date().toISOString();
  
  if (foundIndex === -1) {
    sheet.appendRow([email, name, avatar, hwid, "", "", nowStr, nowStr, ip, ""]);
    return responseJSON({
      ok: true,
      msg: "Đăng ký người dùng mới thành công",
      user: {
        email: email,
        name: name,
        avatar: avatar,
        hwid: hwid,
        activeKey: "",
        expiryDate: "",
        notification: ""
      }
    });
  } else {
    var rowNum = foundIndex + 1;
    sheet.getRange(rowNum, colName + 1).setValue(name);
    sheet.getRange(rowNum, colAvatar + 1).setValue(avatar);
    sheet.getRange(rowNum, colLastActive + 1).setValue(nowStr);
    sheet.getRange(rowNum, colIP + 1).setValue(ip);
    if (hwid) {
      sheet.getRange(rowNum, colHWID + 1).setValue(hwid);
    }
    
    var dbKey = data[foundIndex][colActiveKey].toString().trim();
    var dbExpiry = data[foundIndex][colExpiry];
    var formattedExpiry = "";
    if (dbExpiry) {
      formattedExpiry = formatDate(new Date(dbExpiry));
    }
    
    var dbNotif = data[foundIndex][colNotif].toString().trim();
    
    var isKeyValid = false;
    var keyMsg = "";
    if (dbKey) {
      var keySheet = getSheet();
      var keyData = keySheet.getDataRange().getValues();
      var colKeyKey = keyData[0].indexOf("Key");
      var colKeyStatus = keyData[0].indexOf("Status");
      var colKeyExpiry = keyData[0].indexOf("ExpiryDate");
      
      var keyIndex = -1;
      for (var k = 1; k < keyData.length; k++) {
        if (keyData[k][colKeyKey].toString().trim() === dbKey) {
          keyIndex = k;
          break;
        }
      }
      
      if (keyIndex !== -1) {
        var status = keyData[keyIndex][colKeyStatus].toString().trim().toLowerCase();
        var expiry = new Date(keyData[keyIndex][colKeyExpiry]);
        expiry.setHours(23,59,59,999);
        var curDate = new Date();
        
        if (status === 'blocked' || status === 'block') {
          keyMsg = "Mã bản quyền đã bị khóa bởi Admin";
        } else if (curDate.getTime() > expiry.getTime()) {
          keyMsg = "Mã bản quyền của bạn đã hết hạn sử dụng";
        } else {
          isKeyValid = true;
        }
      } else {
        keyMsg = "Không tìm thấy thông tin bản quyền trên hệ thống";
      }
    }
    
    return responseJSON({
      ok: true,
      msg: "Đăng nhập thành công",
      user: {
        email: email,
        name: name,
        avatar: avatar,
        hwid: hwid || data[foundIndex][colHWID].toString().trim(),
        activeKey: isKeyValid ? dbKey : "",
        expiryDate: isKeyValid ? formattedExpiry : "",
        notification: dbNotif,
        keyMsg: keyMsg
      }
    });
  }
}

function activateUserKey(params) {
  var email = (params.email || "").trim();
  var key = (params.key || "").trim();
  var hwid = (params.hwid || "").trim();
  
  if (!email || !key) {
    return responseJSON({ ok: false, msg: "Thiếu Email hoặc Mã Key kích hoạt" });
  }
  
  var keySheet = getSheet();
  var keyData = keySheet.getDataRange().getValues();
  var kHeaders = keyData[0];
  
  var colKKey = kHeaders.indexOf("Key");
  var colKHWID = kHeaders.indexOf("HWID");
  var colKExpiry = kHeaders.indexOf("ExpiryDate");
  var colKStatus = kHeaders.indexOf("Status");
  
  var keyFoundIndex = -1;
  for (var i = 1; i < keyData.length; i++) {
    if (keyData[i][colKKey].toString().trim() === key) {
      keyFoundIndex = i;
      break;
    }
  }
  
  if (keyFoundIndex === -1) {
    return responseJSON({ ok: false, msg: "Mã bản quyền (Key) không tồn tại trên hệ thống" });
  }
  
  var dbHWID = keyData[keyFoundIndex][colKHWID].toString().trim();
  var dbExpiry = keyData[keyFoundIndex][colKExpiry];
  var dbStatus = keyData[keyFoundIndex][colKStatus].toString().trim().toLowerCase();
  
  if (dbStatus === 'blocked' || dbStatus === 'block') {
    return responseJSON({ ok: false, msg: "Mã bản quyền này đã bị khóa" });
  }
  
  var expiryDate = new Date(dbExpiry);
  var now = new Date();
  expiryDate.setHours(23, 59, 59, 999);
  
  if (isNaN(expiryDate.getTime())) {
    return responseJSON({ ok: false, msg: "Ngày hết hạn bản quyền không hợp lệ" });
  }
  
  if (now.getTime() > expiryDate.getTime()) {
    return responseJSON({ ok: false, msg: "Mã bản quyền đã hết hạn vào ngày " + formatDate(expiryDate) });
  }
  
  if (dbHWID && hwid && dbHWID !== hwid) {
    return responseJSON({ ok: false, msg: "Key này đã được kích hoạt cho một thiết bị khác" });
  }
  
  if (!dbHWID && hwid) {
    keySheet.getCell(keyFoundIndex + 1, colKHWID + 1).setValue(hwid);
    dbHWID = hwid;
  }
  
  var userSheet = getUsersSheet();
  var userData = userSheet.getDataRange().getValues();
  var uHeaders = userData[0];
  var colUEmail = uHeaders.indexOf("Email");
  var colUActiveKey = uHeaders.indexOf("ActiveKey");
  var colUExpiry = uHeaders.indexOf("ExpiryDate");
  
  var userFoundIndex = -1;
  for (var j = 1; j < userData.length; j++) {
    if (userData[j][colUEmail].toString().trim().toLowerCase() === email.toLowerCase()) {
      userFoundIndex = j;
      break;
    }
  }
  
  var formattedExpiry = formatDate(expiryDate);
  
  if (userFoundIndex !== -1) {
    userSheet.getCell(userFoundIndex + 1, colUActiveKey + 1).setValue(key);
    userSheet.getCell(userFoundIndex + 1, colUExpiry + 1).setValue(formattedExpiry);
  }
  
  return responseJSON({
    ok: true,
    msg: "Kích hoạt bản quyền thành công!",
    expiry: formattedExpiry,
    key: key
  });
}

function getAnnouncements(params) {
  var email = (params.email || "").trim();
  var annSheet = getAnnouncementsSheet();
  var data = annSheet.getDataRange().getValues();
  if (data.length <= 1) return responseJSON({ ok: true, announcement: null });
  
  var headers = data[0];
  var colId = headers.indexOf("Id");
  var colTitle = headers.indexOf("Title");
  var colContent = headers.indexOf("Content");
  var colTarget = headers.indexOf("TargetEmail");
  var colStatus = headers.indexOf("Status");
  
  var latestAnn = null;
  for (var i = data.length - 1; i >= 1; i--) {
    var status = data[i][colStatus].toString().trim().toLowerCase();
    var target = data[i][colTarget].toString().trim().toLowerCase();
    
    if (status !== 'inactive' && status !== 'disable') {
      if (!target || (email && target === email.toLowerCase())) {
        latestAnn = {
          id: data[i][colId].toString().trim(),
          title: data[i][colTitle].toString().trim(),
          content: data[i][colContent].toString().trim()
        };
        break;
      }
    }
  }
  
  return responseJSON({ ok: true, announcement: latestAnn });
}

// --- CÁC HÀM XỬ LÝ CHO ADMIN PANEL ---

function listKeys(sheet) {
  var data = sheet.getDataRange().getValues();
  if (data.length <= 1) {
    return responseJSON({ ok: true, keys: [] });
  }
  
  var headers = data[0];
  var colKey = headers.indexOf("Key");
  var colHWID = headers.indexOf("HWID");
  var colExpiry = headers.indexOf("ExpiryDate");
  var colStatus = headers.indexOf("Status");
  var colNote = headers.indexOf("Note");
  
  var keysList = [];
  for (var i = 1; i < data.length; i++) {
    var expDate = data[i][colExpiry];
    var formattedExp = "";
    if (expDate) {
      formattedExp = formatDate(new Date(expDate));
    }
    
    keysList.push({
      rowIndex: i + 1,
      key: data[i][colKey].toString().trim(),
      hwid: data[i][colHWID].toString().trim(),
      expiry: formattedExp,
      status: data[i][colStatus].toString().trim() || "Active",
      note: colNote !== -1 ? data[i][colNote].toString() : ""
    });
  }
  
  return responseJSON({ ok: true, keys: keysList });
}

function createKey(sheet, params) {
  var key = (params.newKey || "").trim();
  var hwid = (params.newHwid || "").trim();
  var expiry = (params.newExpiry || "").trim();
  var status = params.newStatus || "Active";
  var note = (params.newNote || "").trim();
  
  if (!key) return responseJSON({ ok: false, msg: "Không được để trống Key" });
  if (!expiry) return responseJSON({ ok: false, msg: "Không được để trống ngày hết hạn" });
  
  var data = sheet.getDataRange().getValues();
  var headers = data[0];
  var colKey = headers.indexOf("Key");
  
  for (var i = 1; i < data.length; i++) {
    if (data[i][colKey].toString().trim() === key) {
      return responseJSON({ ok: false, msg: "Key này đã tồn tại trên hệ thống" });
    }
  }
  
  sheet.appendRow([key, hwid, expiry, status, note]);
  return responseJSON({ ok: true, msg: "Tạo Key thành công" });
}

function updateKey(sheet, params) {
  var key = (params.targetKey || "").trim();
  if (!key) return responseJSON({ ok: false, msg: "Thiếu thông tin Key mục tiêu" });
  
  var data = sheet.getDataRange().getValues();
  var headers = data[0];
  var colKey = headers.indexOf("Key");
  var colHWID = headers.indexOf("HWID");
  var colExpiry = headers.indexOf("ExpiryDate");
  var colStatus = headers.indexOf("Status");
  var colNote = headers.indexOf("Note");
  
  var foundIndex = -1;
  for (var i = 1; i < data.length; i++) {
    if (data[i][colKey].toString().trim() === key) {
      foundIndex = i + 1;
      break;
    }
  }
  
  if (foundIndex === -1) return responseJSON({ ok: false, msg: "Không tìm thấy Key cần cập nhật" });
  
  if (params.hasOwnProperty("updatedHwid")) {
    sheet.getCell(foundIndex, colHWID + 1).setValue(params.updatedHwid.trim());
  }
  if (params.hasOwnProperty("updatedExpiry")) {
    sheet.getCell(foundIndex, colExpiry + 1).setValue(params.updatedExpiry.trim());
  }
  if (params.hasOwnProperty("updatedStatus")) {
    sheet.getCell(foundIndex, colStatus + 1).setValue(params.updatedStatus.trim());
  }
  if (params.hasOwnProperty("updatedNote") && colNote !== -1) {
    sheet.getCell(foundIndex, colNote + 1).setValue(params.updatedNote.trim());
  }
  
  return responseJSON({ ok: true, msg: "Cập nhật Key thành công" });
}

function deleteKey(sheet, keyToDelete) {
  if (!keyToDelete) return responseJSON({ ok: false, msg: "Thiếu thông tin Key cần xóa" });
  keyToDelete = keyToDelete.trim();
  
  var data = sheet.getDataRange().getValues();
  var headers = data[0];
  var colKey = headers.indexOf("Key");
  
  for (var i = 1; i < data.length; i++) {
    if (data[i][colKey].toString().trim() === keyToDelete) {
      sheet.deleteRow(i + 1);
      return responseJSON({ ok: true, msg: "Đã xóa Key thành công" });
    }
  }
  
  return responseJSON({ ok: false, msg: "Không tìm thấy Key cần xóa" });
}

function listUsers(usersSheet) {
  var data = usersSheet.getDataRange().getValues();
  if (data.length <= 1) return responseJSON({ ok: true, users: [] });
  
  var headers = data[0];
  var colEmail = headers.indexOf("Email");
  var colName = headers.indexOf("Name");
  var colAvatar = headers.indexOf("Avatar");
  var colHWID = headers.indexOf("HWID");
  var colActiveKey = headers.indexOf("ActiveKey");
  var colExpiry = headers.indexOf("ExpiryDate");
  var colCreatedAt = headers.indexOf("CreatedAt");
  var colLastActive = headers.indexOf("LastActive");
  var colIP = headers.indexOf("IP");
  
  var list = [];
  for (var i = 1; i < data.length; i++) {
    var exp = data[i][colExpiry];
    var formattedExp = "";
    if (exp) {
      formattedExp = formatDate(new Date(exp));
    }
    
    list.push({
      email: data[i][colEmail].toString().trim(),
      name: data[i][colName].toString().trim(),
      avatar: data[i][colAvatar].toString().trim(),
      hwid: data[i][colHWID].toString().trim(),
      activeKey: data[i][colActiveKey].toString().trim(),
      expiry: formattedExp,
      createdAt: data[i][colCreatedAt],
      lastActive: data[i][colLastActive],
      ip: data[i][colIP].toString().trim()
    });
  }
  return responseJSON({ ok: true, users: list });
}

function renewUserKey(usersSheet, keySheet, params) {
  var email = (params.targetEmail || "").trim();
  var expiry = (params.newExpiry || "").trim();
  
  if (!email || !expiry) {
    return responseJSON({ ok: false, msg: "Thiếu Email người dùng hoặc Ngày hết hạn mới" });
  }
  
  var uData = usersSheet.getDataRange().getValues();
  var uHeaders = uData[0];
  var colUEmail = uHeaders.indexOf("Email");
  var colUActiveKey = uHeaders.indexOf("ActiveKey");
  var colUExpiry = uHeaders.indexOf("ExpiryDate");
  
  var uIndex = -1;
  for (var i = 1; i < uData.length; i++) {
    if (uData[i][colUEmail].toString().trim().toLowerCase() === email.toLowerCase()) {
      uIndex = i;
      break;
    }
  }
  
  if (uIndex === -1) {
    return responseJSON({ ok: false, msg: "Không tìm thấy người dùng này" });
  }
  
  var activeKey = uData[uIndex][colUActiveKey].toString().trim();
  usersSheet.getCell(uIndex + 1, colUExpiry + 1).setValue(expiry);
  
  if (activeKey) {
    var kData = keySheet.getDataRange().getValues();
    var kHeaders = kData[0];
    var colKKey = kHeaders.indexOf("Key");
    var colKExpiry = kHeaders.indexOf("ExpiryDate");
    
    for (var j = 1; j < kData.length; j++) {
      if (kData[j][colKKey].toString().trim() === activeKey) {
        keySheet.getCell(j + 1, colKExpiry + 1).setValue(expiry);
        break;
      }
    }
  }
  
  return responseJSON({ ok: true, msg: "Gia hạn tài khoản và Key thành công!" });
}

function deleteUser(usersSheet, emailToDelete) {
  if (!emailToDelete) return responseJSON({ ok: false, msg: "Thiếu Email cần xóa" });
  emailToDelete = emailToDelete.trim().toLowerCase();
  
  var data = usersSheet.getDataRange().getValues();
  var colEmail = data[0].indexOf("Email");
  
  for (var i = 1; i < data.length; i++) {
    if (data[i][colEmail].toString().trim().toLowerCase() === emailToDelete) {
      usersSheet.deleteRow(i + 1);
      return responseJSON({ ok: true, msg: "Đã xóa tài khoản người dùng thành công" });
    }
  }
  return responseJSON({ ok: false, msg: "Không tìm thấy tài khoản để xóa" });
}

function sendUserNotification(usersSheet, params) {
  var email = (params.targetEmail || "").trim();
  var message = (params.message || "").trim();
  
  if (!email) return responseJSON({ ok: false, msg: "Thiếu Email người dùng nhận thông báo" });
  
  var data = usersSheet.getDataRange().getValues();
  var colEmail = data[0].indexOf("Email");
  var colNotif = data[0].indexOf("Notification");
  
  for (var i = 1; i < data.length; i++) {
    if (data[i][colEmail].toString().trim().toLowerCase() === email.toLowerCase()) {
      usersSheet.getCell(i + 1, colNotif + 1).setValue(message);
      return responseJSON({ ok: true, msg: "Gửi thông báo riêng thành công" });
    }
  }
  return responseJSON({ ok: false, msg: "Không tìm thấy tài khoản người dùng" });
}

function sendBroadcast(annSheet, params) {
  var title = (params.title || "").trim();
  var content = (params.content || "").trim();
  var target = (params.targetEmail || "").trim();
  
  if (!title || !content) {
    return responseJSON({ ok: false, msg: "Thiếu tiêu đề hoặc nội dung thông báo" });
  }
  
  var id = new Date().getTime().toString();
  var nowStr = new Date().toISOString();
  
  annSheet.appendRow([id, title, content, target, nowStr, "Active"]);
  return responseJSON({ ok: true, msg: "Phát sóng thông báo thành công!" });
}

// --- GIAO DIỆN WEB HTML CỦA ADMIN PANEL ---

function getAdminUI() {
  return `
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>V-ShopeeBoost Pro - Hệ thống Quản trị Bản quyền</title>
  <!-- Google Fonts & TailwindCSS -->
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- FontAwesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Outfit', 'sans-serif'],
          },
          colors: {
            brand: {
              50: '#f5f3ff',
              100: '#ede9fe',
              500: '#8b5cf6',
              600: '#7c3aed',
              700: '#6d28d9',
              900: '#4c1d95',
            }
          }
        }
      }
    }
  </script>
  <style>
    body {
      background: radial-gradient(circle at 50% 50%, #1e1b4b 0%, #09090b 100%);
      min-height: 100vh;
      color: #f4f4f5;
    }
    .glass-card {
      background: rgba(15, 23, 42, 0.65);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1px solid rgba(255, 255, 255, 0.08);
    }
    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
      background: rgba(255, 255, 255, 0.02);
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: rgba(139, 92, 246, 0.3);
      border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
      background: rgba(139, 92, 246, 0.6);
    }
  </style>
</head>
<body class="p-4 md:p-8">

  <!-- TOAST NOTIFICATION -->
  <div id="toast" class="fixed top-4 right-4 z-50 transform translate-y-[-100px] opacity-0 transition-all duration-300 flex items-center p-4 space-x-3 rounded-xl shadow-lg max-w-sm glass-card border-brand-500">
    <div id="toast-icon" class="text-xl"></div>
    <div id="toast-message" class="text-sm font-medium text-white"></div>
  </div>

  <div class="max-w-6xl mx-auto">
    
    <!-- LOGIN VIEW -->
    <div id="login-view" class="flex items-center justify-center min-h-[80vh]">
      <div class="glass-card p-8 rounded-3xl w-full max-w-md shadow-2xl relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-brand-600 rounded-full blur-3xl opacity-20"></div>
        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-pink-600 rounded-full blur-3xl opacity-10"></div>
        
        <div class="text-center mb-8">
          <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-brand-500/10 text-brand-500 border border-brand-500/20 text-3xl font-extrabold mb-4">V</div>
          <h2 class="text-2xl font-bold tracking-tight text-white">V-ShopeeBoost PRO</h2>
          <p class="text-sm text-zinc-400 mt-1">Hệ thống quản lý bản quyền phần mềm</p>
        </div>
        
        <form onsubmit="handleLogin(event)" class="space-y-5">
          <div>
            <label class="block text-xs font-semibold text-zinc-300 uppercase tracking-wider mb-2">Mật khẩu Admin</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-zinc-400">
                <i class="fa-solid fa-lock"></i>
              </span>
              <input type="password" id="admin-pass" required placeholder="Nhập mật khẩu quản trị..." class="w-full bg-zinc-900/60 border border-zinc-700/80 rounded-xl py-3 pl-10 pr-4 text-white placeholder-zinc-500 focus:outline-none focus:border-brand-500 transition-colors">
            </div>
          </div>
          
          <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-500 active:bg-brand-700 text-white font-semibold rounded-xl shadow-lg shadow-brand-500/20 transition-all hover:scale-[1.01] flex items-center justify-center space-x-2">
            <span>Đăng nhập hệ thống</span>
            <i class="fa-solid fa-right-to-bracket text-sm"></i>
          </button>
        </form>
      </div>
    </div>

    <!-- MAIN DASHBOARD VIEW -->
    <div id="dashboard-view" class="hidden space-y-6">
      
      <!-- HEADER -->
      <div class="glass-card rounded-2xl p-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center space-x-4">
          <div class="w-12 h-12 rounded-xl bg-brand-500/10 text-brand-500 border border-brand-500/20 flex items-center justify-center text-2xl font-bold">V</div>
          <div>
            <h1 class="text-xl font-bold text-white flex items-center gap-2">
              V-ShopeeBoost Pro Admin Panel
              <span class="text-xs bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2 py-0.5 rounded-full font-normal">Đã kết nối Sheets</span>
            </h1>
            <p class="text-xs text-zinc-400">Hệ thống tạo và gia hạn key kích hoạt phần mềm</p>
          </div>
        </div>
        <div class="flex items-center space-x-3">
          <button onclick="openCreateModal()" class="px-4 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-medium rounded-xl text-sm transition-all hover:scale-[1.02] flex items-center space-x-2 shadow-lg shadow-brand-500/20">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Tạo Key Mới</span>
          </button>
          <button onclick="handleLogout()" class="px-3.5 py-2.5 bg-zinc-900 border border-zinc-800 hover:bg-zinc-850 hover:border-zinc-700 text-zinc-300 hover:text-white rounded-xl text-sm transition-colors flex items-center space-x-1.5">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span class="hidden md:inline">Đăng xuất</span>
          </button>
        </div>
      </div>

      <!-- KPI STATS -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="glass-card rounded-2xl p-5 relative overflow-hidden">
          <div class="absolute -right-4 -bottom-4 text-zinc-800/15 text-6xl font-black"><i class="fa-solid fa-key"></i></div>
          <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">Tổng số Key</p>
          <p class="text-3xl font-extrabold text-white mt-2" id="stat-total">0</p>
        </div>
        <div class="glass-card rounded-2xl p-5 relative overflow-hidden">
          <div class="absolute -right-4 -bottom-4 text-emerald-800/15 text-6xl font-black"><i class="fa-solid fa-circle-check"></i></div>
          <p class="text-xs font-semibold text-emerald-400 uppercase tracking-wider">Đang hoạt động</p>
          <p class="text-3xl font-extrabold text-emerald-400 mt-2" id="stat-active">0</p>
        </div>
        <div class="glass-card rounded-2xl p-5 relative overflow-hidden">
          <div class="absolute -right-4 -bottom-4 text-rose-800/15 text-6xl font-black"><i class="fa-solid fa-circle-xmark"></i></div>
          <p class="text-xs font-semibold text-rose-400 uppercase tracking-wider">Đã bị Khóa</p>
          <p class="text-3xl font-extrabold text-rose-400 mt-2" id="stat-blocked">0</p>
        </div>
        <div class="glass-card rounded-2xl p-5 relative overflow-hidden">
          <div class="absolute -right-4 -bottom-4 text-amber-800/15 text-6xl font-black"><i class="fa-solid fa-clock"></i></div>
          <p class="text-xs font-semibold text-amber-400 uppercase tracking-wider">Đã hết hạn</p>
          <p class="text-3xl font-extrabold text-amber-400 mt-2" id="stat-expired">0</p>
        </div>
      </div>

      <!-- KEYS TABLE & CONTROLS -->
      <div class="glass-card rounded-2xl overflow-hidden shadow-xl">
        <!-- Table Header / Search -->
        <div class="p-5 border-b border-zinc-800/80 flex flex-col md:flex-row justify-between items-center gap-4 bg-zinc-950/40">
          <div class="relative w-full md:w-80">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-zinc-500">
              <i class="fa-solid fa-magnifying-glass text-sm"></i>
            </span>
            <input type="text" id="search-input" oninput="filterKeys()" placeholder="Tìm kiếm key, ghi chú..." class="w-full bg-zinc-900/60 border border-zinc-800 rounded-xl py-2 pl-9 pr-4 text-sm text-white placeholder-zinc-500 focus:outline-none focus:border-brand-500 transition-colors">
          </div>
          <div class="flex items-center space-x-2 w-full md:w-auto justify-end">
            <button onclick="fetchKeysList()" class="px-3 py-2 bg-zinc-900 border border-zinc-800 hover:bg-zinc-850 text-zinc-300 rounded-xl text-sm transition-all flex items-center space-x-1.5">
              <i class="fa-solid fa-rotate"></i>
              <span>Làm mới</span>
            </button>
          </div>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto custom-scrollbar">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="border-b border-zinc-800/60 bg-zinc-900/10 text-xs font-semibold uppercase tracking-wider text-zinc-400">
                <th class="py-4 px-5">Mã kích hoạt (Key)</th>
                <th class="py-4 px-5">Khách hàng / Ghi chú</th>
                <th class="py-4 px-5">Thiết bị (HWID)</th>
                <th class="py-4 px-5">Hạn dùng</th>
                <th class="py-4 px-5 text-center">Trạng thái</th>
                <th class="py-4 px-5 text-right">Thao tác</th>
              </tr>
            </thead>
            <tbody id="keys-tbody" class="divide-y divide-zinc-850/50 text-sm text-zinc-200">
              <!-- Keys will load here -->
              <tr>
                <td colspan="6" class="py-10 text-center text-zinc-500">
                  <i class="fa-solid fa-spinner fa-spin text-2xl mb-2 text-brand-500"></i>
                  <p>Đang tải dữ liệu từ Google Sheets...</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>

  </div>

  <!-- MODAL: TẠO KEY MỚI -->
  <div id="create-modal" class="fixed inset-0 bg-black/75 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="glass-card rounded-2xl w-full max-w-lg shadow-2xl relative overflow-hidden transition-all transform scale-95 opacity-0 duration-300" id="create-modal-card">
      <div class="p-6 border-b border-zinc-800 flex justify-between items-center">
        <h3 class="text-lg font-bold text-white"><i class="fa-solid fa-key text-brand-500 mr-2"></i>Tạo Bản Quyền Mới</h3>
        <button onclick="closeCreateModal()" class="text-zinc-500 hover:text-white transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
      </div>
      
      <form onsubmit="handleCreateKey(event)" class="p-6 space-y-4">
        <div>
          <label class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Mã Key Bản Quyền</label>
          <div class="flex space-x-2">
            <input type="text" id="new-key-val" required class="flex-1 bg-zinc-900 border border-zinc-700 rounded-xl py-2 px-3 text-white focus:outline-none focus:border-brand-500 transition-colors" placeholder="V-SHOPEEBOOST-XXXX-XXXX">
            <button type="button" onclick="generateRandomKey()" class="px-3 bg-zinc-800 border border-zinc-700 hover:bg-zinc-700 text-zinc-300 rounded-xl text-sm font-medium transition-colors">Ngẫu nhiên</button>
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Tên khách hàng / Ghi chú</label>
          <input type="text" id="new-key-note" class="w-full bg-zinc-900 border border-zinc-700 rounded-xl py-2 px-3 text-white focus:outline-none focus:border-brand-500 transition-colors" placeholder="Nguyễn Văn A - Zalo: 035...">
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Chọn thời hạn nhanh</label>
            <select id="quick-duration" onchange="applyQuickDuration(this.value)" class="w-full bg-zinc-900 border border-zinc-700 rounded-xl py-2 px-3 text-white focus:outline-none focus:border-brand-500 transition-colors">
              <option value="">Tùy chọn...</option>
              <option value="30">30 ngày (1 tháng)</option>
              <option value="90">90 ngày (3 tháng)</option>
              <option value="180">180 ngày (6 tháng)</option>
              <option value="365">365 ngày (1 năm)</option>
              <option value="36500">Vĩnh viễn (100 năm)</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Ngày hết hạn cụ thể</label>
            <input type="date" id="new-key-expiry" required class="w-full bg-zinc-900 border border-zinc-700 rounded-xl py-2 px-3 text-white focus:outline-none focus:border-brand-500 transition-colors">
          </div>
        </div>

        <div class="pt-4 border-t border-zinc-800/80 flex justify-end space-x-3">
          <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-zinc-900 hover:bg-zinc-850 border border-zinc-800 text-zinc-400 rounded-xl text-sm font-medium transition-colors">Hủy</button>
          <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-500 text-white rounded-xl text-sm font-medium transition-colors">Lưu Lại</button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL: GIA HẠN / SỬA KEY -->
  <div id="edit-modal" class="fixed inset-0 bg-black/75 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="glass-card rounded-2xl w-full max-w-md shadow-2xl relative overflow-hidden transition-all transform scale-95 opacity-0 duration-300" id="edit-modal-card">
      <div class="p-6 border-b border-zinc-800 flex justify-between items-center">
        <h3 class="text-lg font-bold text-white"><i class="fa-solid fa-pen-to-square text-brand-500 mr-2"></i>Chỉnh Sửa Key Bản Quyền</h3>
        <button onclick="closeEditModal()" class="text-zinc-500 hover:text-white transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
      </div>
      
      <form onsubmit="handleUpdateKey(event)" class="p-6 space-y-4">
        <input type="hidden" id="edit-key-target">
        
        <div>
          <label class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-1">Đang chỉnh sửa key</label>
          <p id="edit-key-display" class="font-mono text-brand-400 font-semibold text-sm bg-zinc-900 p-2.5 rounded-xl border border-zinc-850"></p>
        </div>

        <div>
          <label class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Tên khách hàng / Ghi chú</label>
          <input type="text" id="edit-key-note" class="w-full bg-zinc-900 border border-zinc-700 rounded-xl py-2 px-3 text-white focus:outline-none focus:border-brand-500 transition-colors" placeholder="Ghi chú...">
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Gia hạn nhanh</label>
            <select id="edit-quick-duration" onchange="applyEditQuickDuration(this.value)" class="w-full bg-zinc-900 border border-zinc-700 rounded-xl py-2 px-3 text-white focus:outline-none focus:border-brand-500 transition-colors">
              <option value="">Tùy chọn...</option>
              <option value="30">+ 30 ngày</option>
              <option value="90">+ 90 ngày</option>
              <option value="180">+ 180 ngày</option>
              <option value="365">+ 365 ngày</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Ngày hết hạn mới</label>
            <input type="date" id="edit-key-expiry" required class="w-full bg-zinc-900 border border-zinc-700 rounded-xl py-2 px-3 text-white focus:outline-none focus:border-brand-500 transition-colors">
          </div>
        </div>

        <div class="pt-4 border-t border-zinc-800/80 flex justify-end space-x-3">
          <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-zinc-900 hover:bg-zinc-850 border border-zinc-800 text-zinc-400 rounded-xl text-sm font-medium transition-colors">Hủy</button>
          <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-500 text-white rounded-xl text-sm font-medium transition-colors">Cập nhật</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Variables
    var currentPassword = "";
    var allKeysList = [];
    var filteredKeysList = [];

    function escapeHTML(value) {
      return String(value == null ? "" : value)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#39;");
    }

    function encodedArg(value) {
      return encodeURIComponent(String(value == null ? "" : value));
    }
    
    // Toast logic
    function showToast(message, type = "success") {
      var toast = document.getElementById("toast");
      var icon = document.getElementById("toast-icon");
      var msg = document.getElementById("toast-message");
      
      msg.textContent = message;
      if (type === "success") {
        icon.className = "fa-solid fa-circle-check text-emerald-500";
        toast.className = "fixed top-4 right-4 z-50 transform opacity-100 translate-y-0 transition-all duration-300 flex items-center p-4 space-x-3 rounded-xl shadow-lg max-w-sm glass-card border-l-4 border-emerald-500";
      } else {
        icon.className = "fa-solid fa-circle-exclamation text-rose-500";
        toast.className = "fixed top-4 right-4 z-50 transform opacity-100 translate-y-0 transition-all duration-300 flex items-center p-4 space-x-3 rounded-xl shadow-lg max-w-sm glass-card border-l-4 border-rose-500";
      }
      
      setTimeout(function() {
        toast.className = "fixed top-4 right-4 z-50 transform translate-y-[-100px] opacity-0 transition-all duration-300 flex items-center p-4 space-x-3 rounded-xl shadow-lg max-w-sm glass-card border-brand-500";
      }, 3500);
    }

    // Login Handler
    function handleLogin(e) {
      e.preventDefault();
      var pass = document.getElementById("admin-pass").value;
      
      // Gửi request API test
      callAPI({
        action: 'admin_list',
        password: pass
      }, function(response) {
        if (response.ok) {
          currentPassword = pass;
          
          document.getElementById("login-view").classList.add("hidden");
          document.getElementById("dashboard-view").classList.remove("hidden");
          showToast("Đăng nhập thành công!");
          
          renderStatsAndTable(response.keys);
        } else {
          showToast(response.msg || "Mật khẩu quản trị chưa đúng!", "error");
        }
      });
    }

    function handleLogout() {
      currentPassword = "";
      document.getElementById("admin-pass").value = "";
      document.getElementById("dashboard-view").classList.add("hidden");
      document.getElementById("login-view").classList.remove("hidden");
      showToast("Đã đăng xuất khỏi hệ thống!");
    }

    // Call API (Generic Function)
    function callAPI(payload, callback) {
      // Vì Google Apps Script Web App chỉ có một URL duy nhất
      var url = window.location.href.split('?')[0];
      
      fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: Object.keys(payload).map(k => encodeURIComponent(k) + '=' + encodeURIComponent(payload[k])).join('&')
      })
      .then(res => res.json())
      .then(data => {
        callback(data);
      })
      .catch(err => {
        console.error(err);
        showToast("Lỗi kết nối Server Google Sheets!", "error");
      });
    }

    // Get list of keys from Sheets
    function fetchKeysList() {
      document.getElementById("keys-tbody").innerHTML = \`
        <tr>
          <td colspan="6" class="py-10 text-center text-zinc-500">
            <i class="fa-solid fa-spinner fa-spin text-2xl mb-2 text-brand-500"></i>
            <p>Đang tải dữ liệu mới nhất...</p>
          </td>
        </tr>
      \`;
      callAPI({
        action: 'admin_list',
        password: currentPassword
      }, function(response) {
        if (response.ok) {
          renderStatsAndTable(response.keys);
        } else {
          showToast(response.msg || "Không thể tải danh sách keys", "error");
        }
      });
    }

    // Render stats & update keys table
    function renderStatsAndTable(keys) {
      allKeysList = keys || [];
      filterKeys(); // Sẽ render vào table theo filter/search
    }

    function filterKeys() {
      var query = document.getElementById("search-input").value.trim().toLowerCase();
      filteredKeysList = allKeysList.filter(function(k) {
        return String(k.key || "").toLowerCase().includes(query) ||
          String(k.note || "").toLowerCase().includes(query);
      });
      
      // Update KPIs
      var activeCount = 0;
      var blockedCount = 0;
      var expiredCount = 0;
      var now = new Date();
      now.setHours(0,0,0,0);
      
      allKeysList.forEach(function(k) {
        var normalizedStatus = String(k.status || "").toLowerCase();
        var isBlocked = normalizedStatus === 'blocked' || normalizedStatus === 'block';
        var exp = new Date(k.expiry);
        var isExpired = isNaN(exp.getTime()) ? false : exp.getTime() < now.getTime();
        
        if (isBlocked) {
          blockedCount++;
        } else if (isExpired) {
          expiredCount++;
        } else {
          activeCount++;
        }
      });

      document.getElementById("stat-total").textContent = allKeysList.length;
      document.getElementById("stat-active").textContent = activeCount;
      document.getElementById("stat-blocked").textContent = blockedCount;
      document.getElementById("stat-expired").textContent = expiredCount;
      
      // Render Table
      var tbody = document.getElementById("keys-tbody");
      if (filteredKeysList.length === 0) {
        tbody.innerHTML = \`
          <tr>
            <td colspan="6" class="py-12 text-center text-zinc-500">
              <i class="fa-regular fa-folder-open text-3xl mb-3 text-zinc-600 block"></i>
              Không tìm thấy Key nào tương thích
            </td>
          </tr>
        \`;
        return;
      }
      
      tbody.innerHTML = "";
      filteredKeysList.forEach(function(item) {
        var tr = document.createElement("tr");
        tr.className = "hover:bg-zinc-900/30 transition-colors";
        
        // Expiry Date Logic & Color
        var exp = new Date(item.expiry);
        var isExpired = isNaN(exp.getTime()) ? false : exp.getTime() < now.getTime();
        
        var dateBadge = "";
        if (isExpired) {
          dateBadge = \`<span class="text-rose-400 font-semibold"><i class="fa-solid fa-triangle-exclamation mr-1.5"></i>\${escapeHTML(item.expiry)} (Hết hạn)</span>\`;
        } else {
          var diffTime = exp.getTime() - now.getTime();
          var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
          var displayDays = diffDays > 30000 ? "Vĩnh viễn" : diffDays + " ngày";
          dateBadge = \`<span class="text-zinc-300 font-medium">\${escapeHTML(item.expiry)} (\${escapeHTML(displayDays)})</span>\`;
        }

        // Status Badge
        var normalizedItemStatus = String(item.status || "").toLowerCase();
        var isBlocked = normalizedItemStatus === 'blocked' || normalizedItemStatus === 'block';
        var statusBadgeHtml = "";
        if (isBlocked) {
          statusBadgeHtml = \`
            <button onclick="toggleKeyStatus(decodeURIComponent('\${encodedArg(item.key)}'), 'Active')" title="Nhấp để Mở Khóa" class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-rose-500/10 text-rose-400 border border-rose-500/20 hover:bg-rose-500/20 transition-all">
              <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-rose-500"></span> Bị Khóa
            </button>
          \`;
        } else {
          statusBadgeHtml = \`
            <button onclick="toggleKeyStatus(decodeURIComponent('\${encodedArg(item.key)}'), 'Blocked')" title="Nhấp để Khóa Key" class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500/20 transition-all">
              <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-emerald-500"></span> Đang chạy
            </button>
          \`;
        }

        // HWID Display
        var hwidHtml = "";
        if (item.hwid) {
          hwidHtml = \`
            <div class="flex items-center space-x-1.5 max-w-[200px]">
              <span class="font-mono text-xs text-zinc-400 truncate" title="\${escapeHTML(item.hwid)}">\${escapeHTML(item.hwid)}</span>
              <button onclick="resetHwid(decodeURIComponent('\${encodedArg(item.key)}'))" title="Reset HWID (Để khách chuyển máy khác)" class="text-zinc-500 hover:text-brand-400 transition-colors p-1"><i class="fa-solid fa-arrows-spin"></i></button>
            </div>
          \`;
        } else {
          hwidHtml = \`<span class="text-zinc-600 text-xs italic">Chưa kích hoạt</span>\`;
        }

        tr.innerHTML = \`
          <td class="py-3.5 px-5 font-mono font-semibold text-white tracking-wide flex items-center space-x-2">
            <span class="truncate max-w-[180px]">\${escapeHTML(item.key)}</span>
            <button onclick="copyToClipboard(decodeURIComponent('\${encodedArg(item.key)}'))" title="Copy Key" class="text-zinc-500 hover:text-white transition-colors p-1"><i class="fa-regular fa-copy"></i></button>
          </td>
          <td class="py-3.5 px-5 text-zinc-300 font-medium max-w-[180px] truncate" title="\${escapeHTML(item.note || '')}">\${item.note ? escapeHTML(item.note) : '<span class="text-zinc-600 italic">Trống</span>'}</td>
          <td class="py-3.5 px-5">\${hwidHtml}</td>
          <td class="py-3.5 px-5">\${dateBadge}</td>
          <td class="py-3.5 px-5 text-center">\${statusBadgeHtml}</td>
          <td class="py-3.5 px-5 text-right space-x-1.5">
            <button onclick="openEditModal(decodeURIComponent('\${encodedArg(item.key)}'), decodeURIComponent('\${encodedArg(item.expiry)}'), decodeURIComponent('\${encodedArg(item.note)}'))" title="Gia hạn / Sửa" class="p-2 bg-brand-500/10 hover:bg-brand-500 text-brand-400 hover:text-white border border-brand-500/20 rounded-xl transition-all"><i class="fa-solid fa-clock-rotate-left"></i></button>
            <button onclick="deleteKeyConfirm(decodeURIComponent('\${encodedArg(item.key)}'))" title="Xóa Key" class="p-2 bg-rose-500/10 hover:bg-rose-500 text-rose-400 hover:text-white border border-rose-500/20 rounded-xl transition-all"><i class="fa-regular fa-trash-can"></i></button>
          </td>
        \`;
        tbody.appendChild(tr);
      });
    }

    // Clipboard copy helper
    function copyToClipboard(text) {
      var el = document.createElement('textarea');
      el.value = text;
      document.body.appendChild(el);
      el.select();
      document.execCommand('copy');
      document.body.removeChild(el);
      showToast("Đã copy key: " + text);
    }

    // Modal Control: Create Modal
    function openCreateModal() {
      document.getElementById("new-key-val").value = "";
      document.getElementById("new-key-note").value = "";
      document.getElementById("quick-duration").value = "";
      
      // Auto set today to expiry date input
      var tomorrow = new Date();
      tomorrow.setDate(tomorrow.getDate() + 30);
      document.getElementById("new-key-expiry").value = formatDate(tomorrow);
      
      var modal = document.getElementById("create-modal");
      var card = document.getElementById("create-modal-card");
      modal.classList.remove("hidden");
      setTimeout(() => {
        card.classList.remove("scale-95", "opacity-0");
      }, 50);
      
      generateRandomKey();
    }
    
    function closeCreateModal() {
      var modal = document.getElementById("create-modal");
      var card = document.getElementById("create-modal-card");
      card.classList.add("scale-95", "opacity-0");
      setTimeout(() => {
        modal.classList.add("hidden");
      }, 200);
    }

    function generateRandomKey() {
      var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
      var buildStr = 'V-SB-';
      for (var i = 0; i < 4; i++) {
        for (var j = 0; j < 4; j++) {
          buildStr += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        if (i < 3) buildStr += '-';
      }
      document.getElementById("new-key-val").value = buildStr;
    }

    function applyQuickDuration(days) {
      if (!days) return;
      var targetDate = new Date();
      targetDate.setDate(targetDate.getDate() + parseInt(days));
      document.getElementById("new-key-expiry").value = formatDate(targetDate);
    }

    function applyEditQuickDuration(days) {
      if (!days) return;
      var currentVal = new Date(document.getElementById("edit-key-expiry").value);
      if (isNaN(currentVal.getTime())) {
        currentVal = new Date();
      }
      currentVal.setDate(currentVal.getDate() + parseInt(days));
      document.getElementById("edit-key-expiry").value = formatDate(currentVal);
    }

    function formatDate(date) {
      var d = new Date(date),
          month = '' + (d.getMonth() + 1),
          day = '' + d.getDate(),
          year = d.getFullYear();

      if (month.length < 2) month = '0' + month;
      if (day.length < 2) day = '0' + day;

      return [year, month, day].join('-');
    }

    // Modal Control: Edit Modal
    function openEditModal(key, expiry, note) {
      document.getElementById("edit-key-target").value = key;
      document.getElementById("edit-key-display").textContent = key;
      document.getElementById("edit-key-note").value = (note === 'undefined' || note === 'null') ? '' : note;
      document.getElementById("edit-key-expiry").value = expiry;
      document.getElementById("edit-quick-duration").value = "";

      var modal = document.getElementById("edit-modal");
      var card = document.getElementById("edit-modal-card");
      modal.classList.remove("hidden");
      setTimeout(() => {
        card.classList.remove("scale-95", "opacity-0");
      }, 50);
    }

    function closeEditModal() {
      var modal = document.getElementById("edit-modal");
      var card = document.getElementById("edit-modal-card");
      card.classList.add("scale-95", "opacity-0");
      setTimeout(() => {
        modal.classList.add("hidden");
      }, 200);
    }

    // API ACTION: Create Key
    function handleCreateKey(e) {
      e.preventDefault();
      var key = document.getElementById("new-key-val").value.trim();
      var note = document.getElementById("new-key-note").value.trim();
      var expiry = document.getElementById("new-key-expiry").value;
      
      callAPI({
        action: 'admin_create',
        password: currentPassword,
        newKey: key,
        newNote: note,
        newExpiry: expiry,
        newStatus: 'Active',
        newHwid: ''
      }, function(res) {
        if (res.ok) {
          showToast("Đã lưu key mới thành công!");
          closeCreateModal();
          fetchKeysList();
        } else {
          showToast(res.msg || "Tạo key thất bại", "error");
        }
      });
    }

    // API ACTION: Update Key Expiry & Note
    function handleUpdateKey(e) {
      e.preventDefault();
      var key = document.getElementById("edit-key-target").value;
      var note = document.getElementById("edit-key-note").value.trim();
      var expiry = document.getElementById("edit-key-expiry").value;

      callAPI({
        action: 'admin_update',
        password: currentPassword,
        targetKey: key,
        updatedNote: note,
        updatedExpiry: expiry
      }, function(res) {
        if (res.ok) {
          showToast("Cập nhật key thành công!");
          closeEditModal();
          fetchKeysList();
        } else {
          showToast(res.msg || "Không thể cập nhật key", "error");
        }
      });
    }

    // API ACTION: Toggle block status
    function toggleKeyStatus(key, newStatus) {
      var actionStr = newStatus === 'Blocked' ? "Khóa" : "Kích hoạt lại";
      if (!confirm("Bạn chắc chắn muốn " + actionStr + " key này?")) return;
      
      callAPI({
        action: 'admin_update',
        password: currentPassword,
        targetKey: key,
        updatedStatus: newStatus
      }, function(res) {
        if (res.ok) {
          showToast("Đã " + actionStr + " key thành công!");
          fetchKeysList();
        } else {
          showToast(res.msg || "Không thể chuyển trạng thái", "error");
        }
      });
    }

    // API ACTION: Reset HWID
    function resetHwid(key) {
      if (!confirm("Reset HWID sẽ cho phép khách hàng sử dụng key này trên thiết bị mới. Đồng ý?")) return;
      
      callAPI({
        action: 'admin_update',
        password: currentPassword,
        targetKey: key,
        updatedHwid: ''
      }, function(res) {
        if (res.ok) {
          showToast("Đã Reset HWID thành công!");
          fetchKeysList();
        } else {
          showToast(res.msg || "Không thể Reset HWID", "error");
        }
      });
    }

    // API ACTION: Delete key
    function deleteKeyConfirm(key) {
      if (!confirm("CẢNH BÁO: Bạn có chắc chắn muốn XÓA hẳn key: " + key + " khỏi Google Sheets? Hành động không thể hoàn tác!")) return;
      
      callAPI({
        action: 'admin_delete',
        password: currentPassword,
        keyToDelete: key
      }, function(res) {
        if (res.ok) {
          showToast("Đã xóa Key thành công!", "success");
          fetchKeysList();
        } else {
          showToast(res.msg || "Không thể xóa key", "error");
        }
      });
    }
  </script>
</body>
</html>
  `;
}
