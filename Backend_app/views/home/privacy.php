<?php
$pageTitle = 'Chính Sách Bảo Mật - VMAX Software';
include BASE_PATH . '/views/layout/header.php';
?>

<div class="container-xl py-5" style="margin-top: 120px; max-w: 960px;">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-blue-600 font-bold">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chính sách bảo mật</li>
        </ol>
    </nav>

    <div class="bg-white border border-slate-200 rounded-3xl p-6 md:p-10 shadow-sm space-y-8">
        <div class="border-b border-slate-100 pb-5">
            <span class="text-blue-600 text-[10px] font-extrabold uppercase tracking-widest block mb-2">Bảo mật thông tin</span>
            <h1 class="fw-extrabold text-dark tracking-tight mb-2">Chính Sách Bảo Mật</h1>
            <p class="text-secondary small mb-0">Cập nhật lần cuối: Ngày 26 tháng 06 năm 2026</p>
        </div>

        <div class="space-y-6" style="line-height: 1.7; color: #475569; font-size: 0.9rem;">
            <p>
                Chào mừng bạn đến với <strong>VMAX Software Solutions</strong>. Chúng tôi hiểu rằng sự riêng tư và bảo mật thông tin cá nhân của bạn là vô cùng quan trọng. Chính sách bảo mật này mô tả cách chúng tôi thu thập, sử dụng, lưu trữ và bảo vệ thông tin của bạn khi bạn sử dụng các sản phẩm phần mềm (VmaxLogin, V-AutoPost, V-ShopeeBoost Pro) và hệ thống điều khiển dịch vụ SaaS của chúng tôi.
            </p>

            <section class="space-y-3">
                <h3 class="fw-bold text-dark h5">1. Thông Tin Chúng Tôi Thu Thập</h3>
                <p>Chúng tôi chỉ thu thập các thông tin cần thiết để vận hành hệ thống cấp phép bản quyền (SaaS) và cải thiện trải nghiệm người dùng:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong>Thông tin tài khoản:</strong> Địa chỉ email, tên đăng nhập, mật khẩu được mã hóa một chiều (BCrypt) và lịch sử giao dịch nạp tiền mua Key.</li>
                    <li><strong>Thông tin thiết bị (SaaS License):</strong> Địa chỉ IP của máy tính kích hoạt bản quyền, mã định danh phần cứng máy tính (HWID) để thực hiện đối chiếu giới hạn máy chạy theo gói dịch vụ đã mua.</li>
                    <li><strong>Dữ liệu trình duyệt ẩn danh (VmaxLogin):</strong> Mọi dữ liệu về cấu hình vân tay thiết bị (Fingerprint Profiles), cookies, và proxy do người dùng khởi tạo đều được xử lý và lưu trữ cục bộ trên máy tính hoặc mã hóa đồng bộ lên đám mây bảo mật của chúng tôi nếu người dùng bật tính năng đồng bộ.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h3 class="fw-bold text-dark h5">2. Cách Chúng Tôi Sử Dụng Thông Tin</h3>
                <p>Thông tin thu thập được sử dụng cho các mục đích sau:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Kích hoạt, xác thực giấy phép (License Key) sử dụng phần mềm trên các thiết bị máy tính được cho phép.</li>
                    <li>Xử lý tự động các giao dịch nạp tiền và yêu cầu rút tiền hoa hồng tiếp thị liên kết (Affiliate).</li>
                    <li>Hỗ trợ kỹ thuật, xử lý ticket lỗi và giải đáp thắc mắc của khách hàng thông qua trung tâm hỗ trợ.</li>
                    <li>Gửi thông báo quan trọng về việc cập nhật phiên bản phần mềm mới, lịch bảo trì hệ thống hoặc các chương trình khuyến mãi (nếu bạn đăng ký nhận tin).</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h3 class="fw-bold text-dark h5">3. Bảo Mật và Lưu Trữ Dữ Liệu</h3>
                <p>
                    VMAX áp dụng các tiêu chuẩn công nghệ bảo mật cao nhất để bảo vệ thông tin của bạn khỏi bị truy cập, tiết lộ hoặc phá hủy trái phép:
                </p>
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong>Mã hóa đầu cuối (End-to-End Encryption):</strong> Toàn bộ dữ liệu nhạy cảm được truyền tải qua giao thức HTTPS bảo mật với mã hóa SSL/TLS cường độ cao.</li>
                    <li><strong>Mã hóa dữ liệu đám mây:</strong> Các cấu hình profile trình duyệt đồng bộ đám mây được mã hóa bằng thuật toán AES-256 tiêu chuẩn quân đội trước khi lưu trữ lên máy chủ đám mây.</li>
                    <li><strong>Mật khẩu & OTP:</strong> Mật khẩu tài khoản được mã hóa bằng cơ chế muối một chiều bcrypt. Chúng tôi khuyến nghị người dùng kích hoạt **xác thực hai yếu tố (2FA - Google Authenticator)** để bảo vệ tuyệt đối ví số dư.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h3 class="fw-bold text-dark h5">4. Chia Sẻ Thông Tin Với Bên Thứ Ba</h3>
                <p>
                    Chúng tôi **cam kết không bán, trao đổi, hoặc chuyển giao** thông tin cá nhân và dữ liệu cấu hình profile trình duyệt của bạn cho bất kỳ bên thứ ba nào vì mục đích thương mại. Thông tin chỉ được tiết lộ trong các trường hợp bắt buộc theo quy định của pháp luật hoặc khi có yêu cầu chính thức từ cơ quan nhà nước có thẩm quyền.
                </p>
            </section>

            <section class="space-y-3">
                <h3 class="fw-bold text-dark h5">5. Quyền Lợi Của Người Dùng</h3>
                <p>Bạn có toàn quyền kiểm soát dữ liệu cá nhân của mình:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Yêu cầu chỉnh sửa thông tin tài khoản (Email, mật khẩu, avatar) bất cứ lúc nào trong trang cài đặt.</li>
                    <li>Chủ động hủy liên kết (Reset) thiết bị PC cũ ra khỏi Key bản quyền để chuyển quyền sử dụng sang máy mới.</li>
                    <li>Yêu cầu xóa vĩnh viễn tài khoản và dữ liệu liên quan khỏi máy chủ VMAX bằng cách gửi yêu cầu hỗ trợ trực tiếp.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h3 class="fw-bold text-dark h5">6. Thay Đổi Chính Sách Bảo Mật</h3>
                <p>
                    VMAX có quyền cập nhật chính sách bảo mật này bất cứ lúc nào để phù hợp với những thay đổi về công nghệ hoặc luật pháp. Mọi thay đổi sẽ được công bố công khai trên trang web này kèm theo ngày cập nhật mới nhất.
                </p>
            </section>

            <section class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5 text-slate-750 font-medium">
                Nếu bạn có bất kỳ câu hỏi hoặc khiếu nại nào liên quan đến Chính sách bảo mật của chúng tôi, vui lòng liên hệ bộ phận hỗ trợ thông qua mục <a href="/support" class="text-blue-600 underline font-bold">Hỗ trợ kỹ thuật</a> hoặc gửi email về địa chỉ: <strong>support@vmaxtech.vn</strong>.
            </section>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/views/layout/footer.php'; ?>
