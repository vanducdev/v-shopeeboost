# V-ShopeeBoost — Chrome Web Store Listing

---

## SHORT DESCRIPTION (≤132 ký tự)

Quản lý đa tài khoản Shopee chuyên nghiệp. Cookie manager, đăng nhập 1-click, backup/restore. Hỗ trợ 8 thị trường.

**Character count: 117/132** ✅

---

## FULL DESCRIPTION (≤16.000 ký tự)

```
V-ShopeeBoost — Công Cụ Quản Lý Tài Khoản Shopee Chuyên Nghiệp

Bạn đang quản lý nhiều shop Shopee và cảm thấy mệt mỏi khi phải đăng nhập lại từng tài khoản mỗi ngày? V-ShopeeBoost là giải pháp hoàn hảo cho bạn!

✅ TÍNH NĂNG CHÍNH:

🔑 Cookie Manager Thông Minh
• Lấy cookie từ tab Shopee đang mở chỉ với 1 click
• Hỗ trợ SPC_F token và toàn bộ cookie session
• Tự động điền domain theo trang đang mở
• Nhập cookie dạng JSON array hoặc chuỗi key=value

⚡ Đăng Nhập 1-Click
• Lưu không giới hạn tài khoản shop
• Đăng nhập nhanh chỉ cần nhấn nút ▶
• Tự động mở tab Shopee sau khi đăng nhập
• Tìm kiếm shop theo tên hoặc domain

🏪 Quản Lý Đa Tài Khoản
• Lưu tên shop, SPC_F, cookie, domain
• Sắp xếp theo lần sử dụng gần nhất
• Xem thống kê: số lượng cookie, thời gian cuối đăng nhập
• Xóa, chỉnh sửa từng shop dễ dàng

💾 Backup & Restore
• Xuất toàn bộ dữ liệu ra file JSON
• Khôi phục từ file backup chỉ 1 click
• Nhập dữ liệu JSON thủ công từ clipboard
• Xuất danh sách shop để chia sẻ

🌏 Hỗ Trợ 8 Thị Trường Shopee
• 🇻🇳 shopee.vn  🇸🇬 shopee.sg  🇲🇾 shopee.my
• 🇮🇩 shopee.co.id  🇵🇭 shopee.ph  🇹🇼 shopee.tw
• 🇹🇭 shopee.co.th  🌐 shopee.com

📋 Lịch Sử & Thống Kê
• Theo dõi lịch sử đăng nhập 50 bản ghi gần nhất
• Thống kê tổng số lần đăng nhập
• Hiển thị thời gian "vừa xong / X phút trước"

⚙️ Cài Đặt Linh Hoạt
• Chọn domain Shopee mặc định
• Bật/tắt tự động mở tab sau đăng nhập
• Bật/tắt thông báo trên trang Shopee
• Trang Options với thống kê dung lượng

🔒 BẢO MẬT & QUYỀN RIÊNG TƯ:
• Tất cả dữ liệu lưu CỤC BỘ trên máy bạn
• Không thu thập, không gửi dữ liệu ra server
• Không có backend, không analytics
• Open permissions: chỉ dùng quyền thực sự cần thiết

---

🎨 GIAO DIỆN HIỆN ĐẠI:
• Dark mode premium với gradient branding
• Animation mượt, spring transitions
• Font Inter chuyên nghiệp
• Responsive, tối ưu cho màn hình extension

---

⚠️ LƯU Ý QUAN TRỌNG:
Extension này chỉ dành cho người dùng hợp pháp quản lý các tài khoản Shopee của chính họ. Vui lòng tuân thủ điều khoản sử dụng của Shopee.

---

Phát triển bởi VMaxTech | app.vmaxtech.vn
```

---

## CATEGORY

**Category:** Productivity

**Sub-category:** Tools

---

## PERMISSIONS JUSTIFICATION

| Permission | Lý do cần thiết |
|-----------|-----------------|
| `cookies` | Đọc/ghi cookie trên các trang Shopee để thực hiện đăng nhập. Đây là chức năng cốt lõi của extension |
| `tabs` | Lấy URL tab hiện tại để tự động nhận diện domain Shopee; mở tab mới sau khi đăng nhập |
| `storage` | Lưu trữ danh sách shop, cookie, lịch sử và cài đặt cục bộ trên thiết bị người dùng |
| `activeTab` | Truy cập tab Shopee đang active khi người dùng nhấn "Lấy Cookie" |
| `scripting` | Inject content script vào trang Shopee để hiển thị toast notification sau khi đăng nhập thành công |

**Host permissions:** `https://*.shopee.vn/*, https://*.shopee.com/*, ...`
→ Cần thiết để đọc/ghi cookie trên các domain Shopee được hỗ trợ

---

## PRIVACY DISCLOSURE (Single Purpose)

**Single Purpose:** Quản lý cookie và đa tài khoản Shopee cho phép đăng nhập nhanh

**Data Usage:**
- Extension xử lý: cookie Shopee, thông tin shop do người dùng nhập
- Lưu trữ: `chrome.storage.local` (cục bộ, không sync)
- **Không** gửi dữ liệu ra bất kỳ server nào
- **Không** có remote code execution
- **Không** tracking hoặc analytics

**Privacy Policy URL:** `chrome-extension://[extension-id]/pages/privacy.html`

---

## RELEASE NOTES (v4.0.0)

```
🎉 V-ShopeeBoost v4.0.0 — Major Release

✨ MỚI:
• Bộ icon PNG chuyên nghiệp mới (16/32/48/128/256/512px)
• Tab Cài đặt với toggle switches
• Options Page nâng cao với thống kê dung lượng
• Trang Privacy Policy, Terms of Service, Support, Changelog
• Version checker tích hợp
• Nút "+ Thêm shop" trên tab danh sách

🔧 SỬA LỖI:
• Fix icon không hợp lệ (ico → PNG)
• Fix bug data model loginStats không nhất quán
• Fix setUninstallURL API compatibility MV3
• Fix clearCookies URL construction
• Chuẩn hóa log prefix toàn extension

⚡ CẢI THIỆN:
• Giao diện popup nâng cấp toàn diện — premium dark mode
• Content script: chống double-injection, MutationObserver tối ưu
• Loại bỏ unlimitedStorage permission không cần thiết
• Data migration tự động từ v3.x
• Performance: Promise.all parallel loading
```

---

## STORE SCREENSHOTS CHECKLIST

| Asset | Kích thước | File |
|-------|-----------|------|
| Icon | 128×128 PNG | `icons/icon128.png` |
| Screenshot 1 (popup main) | 1280×800 | `store/screenshots/sc1_main.png` |
| Screenshot 2 (shop list) | 1280×800 | `store/screenshots/sc2_shops.png` |
| Screenshot 3 (tools/settings) | 1280×800 | `store/screenshots/sc3_tools.png` |
| Small screenshot | 640×400 | `store/screenshots/sc_small.png` |
| Promotional Tile | 440×280 | `store/promo/promo_tile.png` |
| Marquee Banner | 1400×560 | `store/promo/marquee.png` |

> **📸 Ghi chú:** Screenshots cần được chụp từ extension đang chạy thực tế trong Chrome.
> Dùng DevTools → Device Toolbar để set viewport 1280×800.
