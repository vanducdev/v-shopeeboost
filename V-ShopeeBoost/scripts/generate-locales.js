'use strict';

/**
 * Generates the ten offline locale bundles used by the renderer.
 *
 * The source UI is currently Vietnamese-first. This script extracts visible
 * strings from the app HTML/JS, assigns stable keys, translates in batches,
 * and writes deterministic JSON files. It is a developer/build-time tool;
 * the installed app never sends UI text to an online translation service.
 */

const crypto = require('node:crypto');
const fs = require('node:fs');
const path = require('node:path');

const ROOT = path.resolve(__dirname, '..');
const LOCALES_DIR = path.join(ROOT, 'locales');
const TRANSLATE_ENDPOINT = 'https://translate.googleapis.com/translate_a/single';

const LANGUAGES = [
  { code: 'vi', locale: 'vi-VN', name: 'Vietnamese', nativeName: 'Tiếng Việt', file: 'Vietnamese.json', flag: '🇻🇳', target: null },
  { code: 'en', locale: 'en-US', name: 'English', nativeName: 'English', file: 'English.json', flag: '🇺🇸', target: 'en' },
  { code: 'th', locale: 'th-TH', name: 'Thai', nativeName: 'ภาษาไทย', file: 'Thai.json', flag: '🇹🇭', target: 'th' },
  { code: 'id', locale: 'id-ID', name: 'Indonesian', nativeName: 'Bahasa Indonesia', file: 'Indonesian.json', flag: '🇮🇩', target: 'id' },
  { code: 'ms', locale: 'ms-MY', name: 'Malay', nativeName: 'Bahasa Melayu', file: 'Malay.json', flag: '🇲🇾', target: 'ms' },
  { code: 'fil', locale: 'fil-PH', name: 'Filipino', nativeName: 'Filipino', file: 'Filipino.json', flag: '🇵🇭', target: 'tl' },
  { code: 'zh-CN', locale: 'zh-CN', name: 'Chinese Simplified', nativeName: '简体中文', file: 'Chinese-Simplified.json', flag: '🇨🇳', target: 'zh-CN' },
  { code: 'ja', locale: 'ja-JP', name: 'Japanese', nativeName: '日本語', file: 'Japanese.json', flag: '🇯🇵', target: 'ja' },
  { code: 'ko', locale: 'ko-KR', name: 'Korean', nativeName: '한국어', file: 'Korean.json', flag: '🇰🇷', target: 'ko' },
  { code: 'es', locale: 'es-ES', name: 'Spanish', nativeName: 'Español', file: 'Spanish.json', flag: '🇪🇸', target: 'es' }
];

const CORE_MESSAGES = {
  search_placeholder: 'Tìm kiếm profile, proxy...',
  nav_profiles: 'Hồ sơ',
  nav_groups: 'Nhóm',
  nav_proxy: 'Proxy',
  nav_backup: 'Sao lưu',
  nav_extensions: 'Tiện ích',
  nav_shopee: 'Tương tác Shopee',
  nav_trash: 'Thùng rác',
  nav_logs: 'Nhật ký',
  nav_settings: 'Cài đặt',
  nav_logout: 'Đăng xuất tài khoản',
  status_running: 'Đang chạy',
  status_expiry: 'Hạn dùng',
  status_version: 'Phiên bản',
  common_online: 'Trực tuyến',
  common_offline: 'Ngoại tuyến',
  common_loading: 'Đang tải',
  common_save: 'Lưu',
  common_cancel: 'Hủy',
  common_close: 'Đóng',
  common_confirm: 'Xác nhận',
  common_delete: 'Xóa',
  common_edit: 'Chỉnh sửa',
  common_add: 'Thêm',
  common_import: 'Nhập',
  common_export: 'Xuất',
  common_copy: 'Sao chép',
  common_refresh: 'Làm mới',
  common_search: 'Tìm kiếm',
  common_all: 'Tất cả',
  common_none: 'Không có',
  common_auto: 'Tự động',
  common_default: 'Mặc định',
  common_enabled: 'Đã bật',
  common_disabled: 'Đã tắt',
  title_theme_toggle: 'Đổi giao diện',
  title_language: 'Đổi ngôn ngữ',
  title_settings: 'Cài đặt hệ thống',
  title_minimize: 'Thu nhỏ',
  title_maximize: 'Phóng to',
  title_close: 'Đóng',
  settings_title: 'Cài đặt',
  settings_subtitle: 'Tùy chỉnh cách ứng dụng hoạt động và hiển thị',
  settings_tab_browser: 'Trình duyệt',
  settings_tab_automation: 'Tự động hóa',
  settings_tab_backup: 'Sao lưu & Đồng bộ',
  settings_tab_appearance: 'Giao diện & Ngôn ngữ',
  settings_browser_section: 'Đường dẫn & Khởi động Chrome',
  settings_chrome_path: 'Đường dẫn Chrome Executable',
  settings_chrome_path_placeholder: 'Tự động phát hiện Chrome...',
  settings_choose_file: 'Chọn file',
  settings_detect_chrome: 'Tự động tìm',
  settings_chrome_path_hint: 'Nếu để trống, hệ thống sẽ tự tìm Chrome mặc định trên máy tính.',
  settings_start_url: 'Trang khởi động mặc định',
  settings_start_url_hint: 'Trang web này sẽ được mở khi khởi động một Chrome Profile mới.',
  settings_chrome_args: 'Tham số khởi chạy Chrome',
  settings_chrome_args_hint: 'Ngăn cách các tham số bằng khoảng trắng.',
  settings_automation_section: 'Tương tác & Shopee',
  settings_shopee_delay: 'Độ trễ thao tác mặc định',
  settings_seconds: 'giây',
  settings_shopee_delay_hint: 'Thời gian nghỉ giữa các thao tác tự động để giảm rủi ro bị giới hạn.',
  settings_max_retries: 'Số lần thử kết nối lại tối đa',
  settings_max_retries_hint: 'Số lần tự kết nối lại khi yêu cầu thất bại hoặc gặp lỗi mạng.',
  settings_auto_proxy: 'Tự kiểm tra kết nối Proxy',
  settings_auto_proxy_hint: 'Kiểm tra Proxy trước khi khởi động Profile.',
  settings_backup_section: 'Sao lưu Profile tự động',
  settings_max_backups: 'Số bản sao lưu tối đa trên mỗi Profile',
  settings_max_backups_hint: 'Bản sao lưu cũ sẽ tự động được xóa khi vượt quá giới hạn.',
  settings_backup_on_close: 'Tự động sao lưu khi đóng Chrome',
  settings_backup_on_close_hint: 'Nén dữ liệu Profile thành bản sao lưu sau khi trình duyệt đóng.',
  settings_appearance_section: 'Cá nhân hóa giao diện',
  settings_appearance_desc: 'Thay đổi giao diện và ngôn ngữ được áp dụng ngay, không cần khởi động lại.',
  settings_theme_title: 'Chủ đề giao diện',
  settings_theme_desc: 'Chọn bảng màu phù hợp với môi trường làm việc của bạn.',
  settings_language_title: 'Ngôn ngữ ứng dụng',
  settings_language_desc: 'Toàn bộ giao diện sẽ chuyển đổi tức thì và ghi nhớ cho lần mở sau.',
  settings_system_section: 'Hành vi hệ thống',
  settings_startup_page: 'Trang mặc định khi mở ứng dụng',
  settings_startup_profiles: 'Danh sách hồ sơ',
  settings_startup_proxy: 'Quản lý Proxy',
  settings_startup_shopee: 'Tương tác Shopee',
  settings_startup_settings: 'Cài đặt',
  settings_minimize_tray: 'Thu nhỏ vào khay hệ thống',
  settings_minimize_tray_hint: 'Giữ ứng dụng chạy nền khi nhấn nút đóng.',
  settings_save: 'Lưu cài đặt',
  settings_open_profiles: 'Thư mục Profiles',
  settings_open_backups: 'Thư mục Backups',
  settings_saved: 'Đã lưu và áp dụng cài đặt mới',
  settings_chrome_selected: 'Đã chọn Chrome',
  settings_chrome_searching: 'Đang tìm...',
  settings_chrome_found: 'Đã tìm thấy Chrome',
  settings_chrome_not_found: 'Không tìm thấy Chrome trên máy tính',
  theme_light_clean: 'Sáng tinh gọn',
  theme_light_clean_desc: 'Nền sáng trung tính, rõ ràng và dễ đọc.',
  theme_light_warm: 'Sáng ấm',
  theme_light_warm_desc: 'Tông kem ấm, dịu mắt khi làm việc lâu.',
  theme_light_ocean: 'Xanh đại dương',
  theme_light_ocean_desc: 'Tông xanh mát với độ tương phản nhẹ.',
  theme_dark_blue: 'Xanh đêm',
  theme_dark_blue_desc: 'Giao diện tối cân bằng cho môi trường thiếu sáng.',
  theme_dark_purple: 'Tím huyền bí',
  theme_dark_purple_desc: 'Tông tím hiện đại với điểm nhấn neon.',
  theme_dark_green: 'Xanh ngọc',
  theme_dark_green_desc: 'Tông xanh ngọc sâu, tập trung và dịu mắt.',
  language_changed: 'Đã chuyển sang {language}',
  language_loading: 'Đang tải ngôn ngữ...',
  language_error: 'Không thể tải ngôn ngữ đã chọn',
  page_profiles_title: 'Hồ sơ',
  page_profiles_subtitle: 'Quản lý các Chrome Profile của bạn',
  btn_stop_all: 'Dừng tất cả',
  btn_new_profile: 'Tạo hồ sơ mới',
  btn_open_all: 'Mở tất cả',
  btn_create_profile: 'Tạo Profile',
  btn_add_proxy: 'Thêm Proxy',
  btn_settings: 'Cài đặt',
  sort_name: 'Sắp xếp: Tên A-Z',
  sort_created: 'Mới tạo',
  sort_last_opened: 'Mở gần nhất',
  sort_open_count: 'Số lần mở',
  group_all: 'Nhóm: Tất cả',
  table_col_profile: 'Hồ sơ & Nhóm',
  table_col_fingerprints: 'Thiết lập vân tay',
  table_col_proxy: 'Proxy & Kết nối',
  table_col_stats: 'Thống kê',
  table_col_actions: 'Thao tác',
  empty_profiles_title: 'Chưa có hồ sơ nào',
  empty_profiles_desc: 'Tạo hồ sơ đầu tiên để bắt đầu',
  btn_stop_chrome: 'Dừng Chrome',
  btn_open_chrome: 'Mở Chrome',
  no_proxy: 'Không có proxy',
  never_opened: 'Chưa từng mở',
  just_now: 'vừa xong',
  activation_login_google: 'Đăng nhập với Google',
  activation_opening_browser: 'Đang mở trình duyệt. Vui lòng hoàn tất đăng nhập Google.',
  activation_login_failed: 'Đăng nhập Google thất bại',
  activation_license_key_required: 'Vui lòng nhập mã bản quyền',
  activation_manual_token_required: 'Vui lòng dán URL hoặc Access Token',
  activation_copied: 'Đã sao chép',
  activation_copy: 'Sao chép',
  activation_activate: 'Kích hoạt phần mềm',
  activation_logout: 'Đăng xuất'
};

// Context-sensitive UI terms that generic translation commonly treats as
// verbs ("install") or documents ("diary/company profile"). These overrides
// keep the terminology natural in a browser-profile management application.
const CORE_OVERRIDES = {
  en: {
    nav_profiles: 'Profiles', nav_groups: 'Groups', nav_backup: 'Backups', nav_logs: 'Logs',
    nav_settings: 'Settings', settings_title: 'Settings', common_import: 'Import',
    common_export: 'Export', common_refresh: 'Refresh', empty_profiles_title: 'No profiles yet',
    language_changed: 'Switched to {language}'
  },
  th: {
    nav_profiles: 'โปรไฟล์', nav_groups: 'กลุ่ม', nav_backup: 'ข้อมูลสำรอง', nav_logs: 'บันทึกกิจกรรม',
    nav_settings: 'การตั้งค่า', settings_title: 'การตั้งค่า', common_import: 'นำเข้า',
    common_export: 'ส่งออก', common_refresh: 'รีเฟรช', empty_profiles_title: 'ยังไม่มีโปรไฟล์',
    language_changed: 'เปลี่ยนเป็น {language} แล้ว'
  },
  id: {
    nav_profiles: 'Profil', nav_groups: 'Grup', nav_backup: 'Cadangan', nav_logs: 'Log aktivitas',
    nav_settings: 'Pengaturan', settings_title: 'Pengaturan', common_import: 'Impor',
    common_export: 'Ekspor', common_refresh: 'Muat ulang', empty_profiles_title: 'Belum ada profil',
    language_changed: 'Beralih ke {language}'
  },
  ms: {
    nav_profiles: 'Profil', nav_groups: 'Kumpulan', nav_backup: 'Sandaran', nav_logs: 'Log aktiviti',
    nav_settings: 'Tetapan', settings_title: 'Tetapan', common_import: 'Import',
    common_export: 'Eksport', common_refresh: 'Muat semula', empty_profiles_title: 'Belum ada profil',
    language_changed: 'Bertukar kepada {language}'
  },
  fil: {
    nav_profiles: 'Mga Profile', nav_groups: 'Mga Grupo', nav_backup: 'Mga Backup', nav_logs: 'Mga Log',
    nav_settings: 'Mga Setting', settings_title: 'Mga Setting', common_import: 'Mag-import',
    common_export: 'Mag-export', common_refresh: 'I-refresh', empty_profiles_title: 'Wala pang profile',
    language_changed: 'Lumipat sa {language}'
  },
  'zh-CN': {
    nav_profiles: '浏览器配置文件', nav_groups: '分组', nav_backup: '备份', nav_logs: '日志',
    nav_settings: '设置', settings_title: '设置', common_import: '导入',
    common_export: '导出', common_refresh: '刷新', empty_profiles_title: '暂无配置文件',
    language_changed: '已切换到{language}'
  },
  ja: {
    nav_profiles: 'プロファイル', nav_groups: 'グループ', nav_backup: 'バックアップ', nav_logs: 'ログ',
    nav_settings: '設定', settings_title: '設定', common_import: 'インポート',
    common_export: 'エクスポート', common_refresh: '更新', empty_profiles_title: 'プロファイルはまだありません',
    language_changed: '{language}に切り替えました'
  },
  ko: {
    nav_profiles: '프로필', nav_groups: '그룹', nav_backup: '백업', nav_logs: '로그',
    nav_settings: '설정', settings_title: '설정', common_import: '가져오기',
    common_export: '내보내기', common_refresh: '새로고침', empty_profiles_title: '아직 프로필이 없습니다',
    language_changed: '{language}(으)로 전환했습니다'
  },
  es: {
    nav_profiles: 'Perfiles', nav_groups: 'Grupos', nav_backup: 'Copias de seguridad', nav_logs: 'Registros',
    nav_settings: 'Configuración', settings_title: 'Configuración', common_import: 'Importar',
    common_export: 'Exportar', common_refresh: 'Actualizar', empty_profiles_title: 'Aún no hay perfiles',
    language_changed: 'Se cambió a {language}'
  }
};

Object.assign(CORE_OVERRIDES.en, {
  nav_extensions: 'Extensions',
  nav_shopee: 'Shopee Automation',
  title_theme_toggle: 'Change theme',
  title_minimize: 'Minimize',
  title_maximize: 'Maximize',
  settings_startup_profiles: 'Profiles',
  settings_startup_proxy: 'Proxy Management',
  settings_startup_shopee: 'Shopee Automation',
  settings_startup_settings: 'Settings',
  theme_light_clean: 'Clean Light',
  theme_light_clean_desc: 'Neutral light background that is clear and easy to read.',
  theme_light_warm: 'Warm Light',
  theme_light_warm_desc: 'Warm cream tones that stay comfortable during long sessions.',
  theme_light_ocean: 'Ocean Blue',
  theme_light_ocean_desc: 'Cool blue tones with gentle contrast.',
  theme_dark_blue: 'Midnight Blue',
  theme_dark_blue_desc: 'A balanced dark theme for low-light environments.',
  theme_dark_purple: 'Mystic Purple',
  theme_dark_purple_desc: 'Modern purple tones with neon accents.',
  theme_dark_green: 'Emerald Dark',
  theme_dark_green_desc: 'Deep emerald tones for focused, comfortable work.'
});

Object.assign(CORE_OVERRIDES.th, {
  nav_extensions: 'ส่วนขยาย',
  nav_shopee: 'ระบบอัตโนมัติ Shopee',
  status_running: 'กำลังทำงาน',
  title_theme_toggle: 'เปลี่ยนธีม',
  title_minimize: 'ย่อหน้าต่าง',
  title_maximize: 'ขยายหน้าต่าง',
  settings_startup_profiles: 'โปรไฟล์',
  settings_startup_shopee: 'ระบบอัตโนมัติ Shopee',
  settings_startup_settings: 'การตั้งค่า',
  theme_light_clean: 'สว่างสะอาด',
  theme_light_clean_desc: 'พื้นหลังสว่างโทนกลาง ชัดเจนและอ่านง่าย',
  theme_light_warm: 'แสงอุ่น',
  theme_light_warm_desc: 'โทนครีมอบอุ่น สบายตาเมื่อต้องใช้งานเป็นเวลานาน',
  theme_light_ocean: 'น้ำเงินมหาสมุทร',
  theme_light_ocean_desc: 'โทนสีน้ำเงินเย็นตาพร้อมความคมชัดที่นุ่มนวล',
  theme_dark_blue: 'น้ำเงินเที่ยงคืน',
  theme_dark_blue_desc: 'ธีมมืดที่สมดุลสำหรับสภาพแวดล้อมที่มีแสงน้อย',
  theme_dark_purple: 'ม่วงลึกลับ',
  theme_dark_purple_desc: 'โทนสีม่วงทันสมัยพร้อมลูกเล่นนีออน',
  theme_dark_green: 'เขียวมรกตเข้ม',
  theme_dark_green_desc: 'โทนเขียวมรกตเข้ม ช่วยให้มีสมาธิและสบายตา'
});

Object.assign(CORE_OVERRIDES.id, {
  nav_extensions: 'Ekstensi',
  nav_shopee: 'Otomatisasi Shopee',
  status_running: 'Sedang berjalan',
  title_theme_toggle: 'Ubah tema',
  title_minimize: 'Minimalkan',
  title_maximize: 'Maksimalkan',
  settings_startup_profiles: 'Profil',
  settings_startup_shopee: 'Otomatisasi Shopee',
  settings_startup_settings: 'Pengaturan',
  theme_light_clean: 'Terang Bersih',
  theme_light_clean_desc: 'Latar terang netral yang jelas dan mudah dibaca.',
  theme_light_warm: 'Terang Hangat',
  theme_light_warm_desc: 'Nuansa krem hangat yang nyaman untuk penggunaan lama.',
  theme_light_ocean: 'Biru Samudra',
  theme_light_ocean_desc: 'Nuansa biru sejuk dengan kontras yang lembut.',
  theme_dark_blue: 'Biru Tengah Malam',
  theme_dark_blue_desc: 'Tema gelap seimbang untuk lingkungan minim cahaya.',
  theme_dark_purple: 'Ungu Misterius',
  theme_dark_purple_desc: 'Nuansa ungu modern dengan aksen neon.',
  theme_dark_green: 'Hijau Zamrud Gelap',
  theme_dark_green_desc: 'Nuansa zamrud gelap untuk bekerja dengan fokus dan nyaman.'
});

Object.assign(CORE_OVERRIDES.ms, {
  nav_extensions: 'Sambungan',
  nav_shopee: 'Automasi Shopee',
  status_running: 'Sedang berjalan',
  title_theme_toggle: 'Tukar tema',
  title_minimize: 'Minimumkan',
  title_maximize: 'Maksimumkan',
  settings_startup_profiles: 'Profil',
  settings_startup_shopee: 'Automasi Shopee',
  settings_startup_settings: 'Tetapan',
  theme_light_clean: 'Cerah Bersih',
  theme_light_clean_desc: 'Latar cerah neutral yang jelas dan mudah dibaca.',
  theme_light_warm: 'Cerah Hangat',
  theme_light_warm_desc: 'Nada krim hangat yang selesa untuk penggunaan lama.',
  theme_light_ocean: 'Biru Lautan',
  theme_light_ocean_desc: 'Nada biru sejuk dengan kontras yang lembut.',
  theme_dark_blue: 'Biru Tengah Malam',
  theme_dark_blue_desc: 'Tema gelap seimbang untuk persekitaran kurang cahaya.',
  theme_dark_purple: 'Ungu Mistik',
  theme_dark_purple_desc: 'Nada ungu moden dengan aksen neon.',
  theme_dark_green: 'Hijau Zamrud Gelap',
  theme_dark_green_desc: 'Nada zamrud gelap untuk kerja yang fokus dan selesa.'
});

Object.assign(CORE_OVERRIDES.fil, {
  nav_extensions: 'Mga Extension',
  nav_shopee: 'Shopee Automation',
  status_running: 'Tumatakbo',
  title_theme_toggle: 'Palitan ang tema',
  title_minimize: 'I-minimize',
  title_maximize: 'I-maximize',
  settings_startup_profiles: 'Mga Profile',
  settings_startup_shopee: 'Shopee Automation',
  settings_startup_settings: 'Mga Setting',
  theme_light_clean: 'Malinis na Maliwanag',
  theme_light_clean_desc: 'Neutral na maliwanag na background na malinaw at madaling basahin.',
  theme_light_warm: 'Mainit na Maliwanag',
  theme_light_warm_desc: 'Mainit na kremang tono na komportable sa matagal na paggamit.',
  theme_light_ocean: 'Asul na Karagatan',
  theme_light_ocean_desc: 'Malamig na asul na tono na may banayad na contrast.',
  theme_dark_blue: 'Asul na Hatinggabi',
  theme_dark_blue_desc: 'Balanseng madilim na tema para sa mahinang ilaw.',
  theme_dark_purple: 'Mistikong Lila',
  theme_dark_purple_desc: 'Modernong lilang tono na may neon na accent.',
  theme_dark_green: 'Madilim na Esmeralda',
  theme_dark_green_desc: 'Malalim na esmeraldang tono para sa pokus at komportableng trabaho.'
});

Object.assign(CORE_OVERRIDES['zh-CN'], {
  nav_extensions: '扩展程序',
  nav_shopee: 'Shopee 自动化',
  status_running: '正在运行',
  title_theme_toggle: '更换主题',
  title_minimize: '最小化',
  title_maximize: '最大化',
  settings_startup_profiles: '浏览器配置文件',
  settings_startup_shopee: 'Shopee 自动化',
  settings_startup_settings: '设置',
  theme_light_clean: '明亮简洁',
  theme_light_clean_desc: '中性明亮背景，清晰且易于阅读。',
  theme_light_warm: '暖色明亮',
  theme_light_warm_desc: '温暖的奶油色调，长时间使用也舒适。',
  theme_light_ocean: '海洋蓝',
  theme_light_ocean_desc: '清凉蓝色调，搭配柔和对比度。',
  theme_dark_blue: '午夜蓝',
  theme_dark_blue_desc: '适合低光环境的均衡深色主题。',
  theme_dark_purple: '神秘紫',
  theme_dark_purple_desc: '带霓虹点缀的现代紫色调。',
  theme_dark_green: '深邃翡翠',
  theme_dark_green_desc: '深翡翠色调，专注工作且舒适护眼。'
});

Object.assign(CORE_OVERRIDES.ja, {
  nav_extensions: '拡張機能',
  nav_shopee: 'Shopee自動化',
  status_running: '実行中',
  title_theme_toggle: 'テーマを変更',
  title_minimize: '最小化',
  title_maximize: '最大化',
  settings_startup_profiles: 'プロファイル',
  settings_startup_shopee: 'Shopee自動化',
  settings_startup_settings: '設定',
  theme_light_clean: 'クリーンライト',
  theme_light_clean_desc: '明るくニュートラルな背景で、文字が読みやすいテーマです。',
  theme_light_warm: 'ウォームライト',
  theme_light_warm_desc: '長時間の作業でも目に優しい、温かなクリーム色です。',
  theme_light_ocean: 'オーシャンブルー',
  theme_light_ocean_desc: '穏やかなコントラストの涼しげなブルーです。',
  theme_dark_blue: 'ミッドナイトブルー',
  theme_dark_blue_desc: '暗い環境に適した、バランスの良いダークテーマです。',
  theme_dark_purple: 'ミスティックパープル',
  theme_dark_purple_desc: 'ネオンのアクセントを加えたモダンな紫色です。',
  theme_dark_green: 'エメラルドダーク',
  theme_dark_green_desc: '集中しやすく目にも優しい、深いエメラルド色です。'
});

Object.assign(CORE_OVERRIDES.ko, {
  nav_extensions: '확장 프로그램',
  nav_shopee: 'Shopee 자동화',
  status_running: '실행 중',
  title_theme_toggle: '테마 변경',
  title_minimize: '최소화',
  title_maximize: '최대화',
  settings_startup_profiles: '프로필',
  settings_startup_shopee: 'Shopee 자동화',
  settings_startup_settings: '설정',
  theme_light_clean: '클린 라이트',
  theme_light_clean_desc: '선명하고 읽기 쉬운 중성 밝은 배경입니다.',
  theme_light_warm: '웜 라이트',
  theme_light_warm_desc: '오래 사용해도 눈이 편안한 따뜻한 크림 색조입니다.',
  theme_light_ocean: '오션 블루',
  theme_light_ocean_desc: '부드러운 대비의 시원한 파란색 계열입니다.',
  theme_dark_blue: '미드나이트 블루',
  theme_dark_blue_desc: '어두운 환경에 알맞은 균형 잡힌 다크 테마입니다.',
  theme_dark_purple: '미스틱 퍼플',
  theme_dark_purple_desc: '네온 포인트가 돋보이는 현대적인 보라색 계열입니다.',
  theme_dark_green: '에메랄드 다크',
  theme_dark_green_desc: '집중하기 좋고 눈이 편안한 깊은 에메랄드 색조입니다.'
});

Object.assign(CORE_OVERRIDES.es, {
  nav_extensions: 'Extensiones',
  nav_shopee: 'Automatización de Shopee',
  status_running: 'En ejecución',
  title_theme_toggle: 'Cambiar tema',
  title_minimize: 'Minimizar',
  title_maximize: 'Maximizar',
  settings_startup_profiles: 'Perfiles',
  settings_startup_shopee: 'Automatización de Shopee',
  settings_startup_settings: 'Configuración',
  theme_light_clean: 'Claro limpio',
  theme_light_clean_desc: 'Fondo claro neutro, nítido y fácil de leer.',
  theme_light_warm: 'Claro cálido',
  theme_light_warm_desc: 'Tonos crema cálidos y cómodos durante sesiones largas.',
  theme_light_ocean: 'Azul océano',
  theme_light_ocean_desc: 'Tonos azules frescos con un contraste suave.',
  theme_dark_blue: 'Azul medianoche',
  theme_dark_blue_desc: 'Tema oscuro equilibrado para entornos con poca luz.',
  theme_dark_purple: 'Púrpura místico',
  theme_dark_purple_desc: 'Tonos morados modernos con detalles de neón.',
  theme_dark_green: 'Esmeralda oscuro',
  theme_dark_green_desc: 'Tonos esmeralda profundos para trabajar con concentración y comodidad.'
});

function decodeHtml(value) {
  return value
    .replace(/&nbsp;/gi, ' ')
    .replace(/&amp;/gi, '&')
    .replace(/&lt;/gi, '<')
    .replace(/&gt;/gi, '>')
    .replace(/&quot;/gi, '"')
    .replace(/&#39;|&apos;/gi, "'")
    .replace(/&#(\d+);/g, (_, code) => String.fromCodePoint(Number(code)))
    .replace(/&#x([0-9a-f]+);/gi, (_, code) => String.fromCodePoint(parseInt(code, 16)));
}

function normalizeText(value) {
  return decodeHtml(String(value))
    .replace(/\$\{[^}]+\}/g, () => `{value${normalizeText.placeholderIndex++}}`)
    .replace(/\s+/g, ' ')
    .trim();
}
normalizeText.placeholderIndex = 1;

function isUsefulText(value) {
  if (!value || value.length < 2 || value.length > 220) return false;
  if (/^(?:https?:|file:|data:|javascript:|[.#][a-z0-9_-]+|--[a-z0-9-]+)/i.test(value)) return false;
  if (/^[\d\s%+.,:/\\|()[\]_-]+$/.test(value)) return false;
  if (/[{};]\s*(?:display|color|width|height|background|return|const|let|var)\b/i.test(value)) return false;
  if (/<\/?[a-z][^>]*>/i.test(value)) return false;
  return /[\p{L}]/u.test(value);
}

function addText(set, raw) {
  normalizeText.placeholderIndex = 1;
  const value = normalizeText(raw);
  if (isUsefulText(value)) set.add(value);
}

function extractHtmlStrings(filePath, set) {
  let html = fs.readFileSync(filePath, 'utf8');
  html = html
    .replace(/<style\b[^>]*>[\s\S]*?<\/style>/gi, ' ')
    .replace(/<script\b[^>]*>[\s\S]*?<\/script>/gi, ' ')
    .replace(/<!--[\s\S]*?-->/g, ' ');

  for (const match of html.matchAll(/>([^<>]+)</g)) addText(set, match[1]);
  for (const match of html.matchAll(/\b(?:placeholder|title|aria-label)="([^"]+)"/gi)) addText(set, match[1]);
}

function extractJavaScriptStrings(filePath, set) {
  const source = fs.readFileSync(filePath, 'utf8');
  const stringPattern = /(['"`])((?:\\.|(?!\1)[\s\S]){1,240}?)\1/g;
  for (const match of source.matchAll(stringPattern)) {
    const value = match[2]
      .replace(/\\n/g, ' ')
      .replace(/\\r/g, ' ')
      .replace(/\\t/g, ' ')
      .replace(/\\(['"`\\])/g, '$1');
    if (/[\u00c0-\u024f\u1e00-\u1eff\u0102\u0103\u0110\u0111]/u.test(value)) addText(set, value);
  }
}

function collectSourceMessages() {
  const strings = new Set(Object.values(CORE_MESSAGES));
  extractHtmlStrings(path.join(ROOT, 'index.html'), strings);
  for (const entry of fs.readdirSync(path.join(ROOT, 'pages'), { withFileTypes: true })) {
    const filePath = path.join(ROOT, 'pages', entry.name);
    if (entry.isFile() && entry.name.endsWith('.html')) extractHtmlStrings(filePath, strings);
    if (entry.isFile() && entry.name.endsWith('.js')) extractJavaScriptStrings(filePath, strings);
  }
  for (const entry of fs.readdirSync(path.join(ROOT, 'admin'), { withFileTypes: true })) {
    const filePath = path.join(ROOT, 'admin', entry.name);
    if (entry.isFile() && entry.name.endsWith('.html')) extractHtmlStrings(filePath, strings);
  }
  extractJavaScriptStrings(path.join(ROOT, 'app.js'), strings);

  const messages = { ...CORE_MESSAGES };
  const knownSources = new Set(Object.values(messages));
  for (const source of [...strings].sort((a, b) => a.localeCompare(b, 'vi'))) {
    if (knownSources.has(source)) continue;
    const hash = crypto.createHash('sha1').update(source).digest('hex').slice(0, 12);
    messages[`auto.${hash}`] = source;
  }
  return Object.fromEntries(Object.entries(messages).sort(([a], [b]) => a.localeCompare(b)));
}

function splitBatches(entries, maxChars = 7000) {
  const batches = [];
  let batch = [];
  let size = 0;
  for (const entry of entries) {
    const cost = entry[1].length + 32;
    if (batch.length && size + cost > maxChars) {
      batches.push(batch);
      batch = [];
      size = 0;
    }
    batch.push(entry);
    size += cost;
  }
  if (batch.length) batches.push(batch);
  return batches;
}

function translatedPayload(json) {
  if (!Array.isArray(json?.[0])) throw new Error('Unexpected translation response');
  return json[0].map(part => part?.[0] || '').join('');
}

async function requestTranslation(text, target, attempt = 1) {
  const body = new URLSearchParams({
    client: 'gtx',
    sl: 'auto',
    tl: target,
    dt: 't',
    q: text
  });
  try {
    const response = await fetch(TRANSLATE_ENDPOINT, {
      method: 'POST',
      headers: {
        'content-type': 'application/x-www-form-urlencoded;charset=UTF-8',
        'user-agent': 'V-ShopeeBoost-Locale-Generator/5.1'
      },
      body,
      signal: AbortSignal.timeout(30000)
    });
    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    return translatedPayload(await response.json());
  } catch (error) {
    if (attempt >= 3) throw error;
    await new Promise(resolve => setTimeout(resolve, attempt * 1000));
    return requestTranslation(text, target, attempt + 1);
  }
}

async function translateBatch(batch, target) {
  const markerFor = index => `__VSB_${String(index).padStart(4, '0')}__`;
  const payload = batch.map(([, value], index) => `${markerFor(index)}\n${value}`).join('\n');
  const translated = await requestTranslation(payload, target);
  const values = new Map();
  const markerPattern = /__VSB_(\d{4})__\s*([\s\S]*?)(?=__VSB_\d{4}__|$)/g;
  for (const match of translated.matchAll(markerPattern)) {
    values.set(Number(match[1]), match[2].trim());
  }

  const result = {};
  for (let index = 0; index < batch.length; index++) {
    const [key, source] = batch[index];
    let value = values.get(index);
    if (!value) value = (await requestTranslation(source, target)).trim();
    const sourcePlaceholders = [...source.matchAll(/\{[a-z][a-z0-9]*\}/gi)].map(match => match[0]);
    let placeholderIndex = 0;
    value = String(value || source).replace(/\{[^{}]+\}/g, () => sourcePlaceholders[placeholderIndex++] || '');
    result[key] = value || source;
  }
  return result;
}

async function translateMessages(sourceMessages, language) {
  if (!language.target) return { ...sourceMessages };
  const entries = Object.entries(sourceMessages);
  const existingPath = path.join(LOCALES_DIR, language.file);
  let translated = {};
  try {
    const existing = JSON.parse(fs.readFileSync(existingPath, 'utf8'));
    if (existing?.$meta?.code === language.code && existing.messages) {
      translated = Object.fromEntries(
        entries.filter(([key]) => typeof existing.messages[key] === 'string')
          .map(([key]) => [key, existing.messages[key]])
      );
    }
  } catch {}

  const missingEntries = entries.filter(([key]) => !translated[key]);
  const batches = splitBatches(missingEntries);
  if (!missingEntries.length) {
    process.stdout.write(`  ${language.name}: reused ${entries.length} existing messages.\n`);
  }
  for (let index = 0; index < batches.length; index++) {
    process.stdout.write(`  ${language.name}: new batch ${index + 1}/${batches.length}\r`);
    Object.assign(translated, await translateBatch(batches[index], language.target));
  }
  if (missingEntries.length) {
    process.stdout.write(`  ${language.name}: added ${missingEntries.length}; ${entries.length} messages complete.\n`);
  }
  const overrides = CORE_OVERRIDES[language.code] || {};
  return Object.fromEntries(entries.map(([key]) => [key, overrides[key] || translated[key] || sourceMessages[key]]));
}

function writeJson(filePath, data) {
  fs.mkdirSync(path.dirname(filePath), { recursive: true });
  fs.writeFileSync(filePath, `${JSON.stringify(data, null, 2)}\n`, 'utf8');
}

async function main() {
  const sourceMessages = collectSourceMessages();
  console.log(`Collected ${Object.keys(sourceMessages).length} translatable messages.`);

  const manifest = {
    version: 1,
    defaultLanguage: 'vi',
    fallbackLanguage: 'en',
    languages: LANGUAGES.map(({ target, ...language }) => ({ ...language, direction: 'ltr' }))
  };
  writeJson(path.join(LOCALES_DIR, 'manifest.json'), manifest);

  for (const language of LANGUAGES) {
    const messages = await translateMessages(sourceMessages, language);
    writeJson(path.join(LOCALES_DIR, language.file), {
      $meta: {
        code: language.code,
        locale: language.locale,
        name: language.name,
        nativeName: language.nativeName,
        flag: language.flag,
        direction: 'ltr',
        generatedAt: new Date().toISOString()
      },
      messages
    });
  }
}

main().catch(error => {
  console.error(error);
  process.exitCode = 1;
});
