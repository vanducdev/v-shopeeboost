<!-- Floating Contact Card Widget -->
<div id="vmax-contact-widget" class="vmax-contact-widget">
    <!-- Welcome Bubble Tooltip -->
    <div id="vmax-chat-welcome" class="vmax-chat-welcome-bubble">
        <button class="vmax-chat-welcome-close" onclick="closeVmaxWelcome(event)">&times;</button>
        <span>Bạn cần tư vấn? Nhắn Zalo hỗ trợ ngay nhé! 💬</span>
    </div>

    <!-- Contact Card Panel -->
    <div id="vmax-contact-card" class="vmax-contact-card">
        <!-- Header -->
        <div class="vmax-card-header">
            <div class="vmax-header-info">
                <h4>Liên Hệ Tư Vấn</h4>
                <p>Chúng tôi sẵn sàng hỗ trợ bạn 24/7</p>
            </div>
            <button class="vmax-close-card" onclick="toggleVmaxContact()" aria-label="Đóng">&times;</button>
        </div>
        
        <!-- Channels List -->
        <div class="vmax-card-body">
            <!-- Facebook -->
            <a href="https://www.facebook.com/vmaxsoftware.vn" target="_blank" rel="noopener" class="vmax-channel-row facebook">
                <div class="vmax-channel-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </div>
                <div class="vmax-channel-details">
                    <span class="vmax-channel-title">Facebook Fanpage</span>
                    <span class="vmax-channel-desc">Hỗ trợ tư vấn, giải đáp thắc mắc</span>
                </div>
                <div class="vmax-channel-action">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </div>
            </a>
            
            <!-- Zalo -->
            <a href="https://zalo.me/0886335455" target="_blank" rel="noopener" class="vmax-channel-row zalo">
                <div class="vmax-channel-icon">
                    <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M24 4C12.95 4 4 11.61 4 21C4 26.24 7.02 30.87 11.75 33.91C11.14 36.19 9.87 39.54 9.17 41.34C8.98 41.83 9.47 42.27 9.94 42.02C13.88 39.95 19.34 37.1 21.6 37.8C22.39 37.93 23.19 38 24 38C35.05 38 44 30.39 44 21C44 11.61 35.05 4 24 4Z" fill="currentColor"/>
                        <text x="14" y="27.5" fill="#FFFFFF" font-family="Arial, sans-serif" font-weight="950" font-size="11.5" letter-spacing="0.5">Zalo</text>
                    </svg>
                </div>
                <div class="vmax-channel-details">
                    <span class="vmax-channel-title">Zalo Chat: 0886.335.455</span>
                    <span class="vmax-channel-desc">Hỗ trợ kỹ thuật & Gia hạn key nhanh</span>
                </div>
                <div class="vmax-channel-action">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </div>
            </a>
            
            <!-- TikTok -->
            <a href="https://www.tiktok.com/@vmaxsoftware.vn" target="_blank" rel="noopener" class="vmax-channel-row tiktok">
                <div class="vmax-channel-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.17-2.86-.74-3.99-1.72-.08-.07-.15-.15-.24-.22v6.8c.02 1.93-.49 3.91-1.66 5.4-1.4 1.83-3.77 2.87-6.07 2.74-2.52-.11-5-1.54-6.22-3.77-1.39-2.48-1.34-5.74.13-8.17 1.4-2.37 4.09-3.79 6.87-3.62 1.13.06 2.22.42 3.14 1.05V3.88c-1.12-.47-2.31-.7-3.53-.67-2.53.07-4.99 1.34-6.32 3.51-1.46 2.37-1.57 5.51-.28 7.99 1.25 2.45 3.86 4.02 6.64 4.09 2.74.08 5.43-1.31 6.78-3.7 1.01-1.78 1.14-3.99 1.12-6.03V.02z"/>
                    </svg>
                </div>
                <div class="vmax-channel-details">
                    <span class="vmax-channel-title">Kênh TikTok</span>
                    <span class="vmax-channel-desc">Video hướng dẫn & Mẹo MMO hữu ích</span>
                </div>
                <div class="vmax-channel-action">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </div>
            </a>
            
            <!-- YouTube -->
            <a href="https://www.youtube.com/@VMAXDigitalSolutions" target="_blank" rel="noopener" class="vmax-channel-row youtube">
                <div class="vmax-channel-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M23.498 6.163a3.003 3.003 0 0 0-2.11-2.11C19.517 3.545 12 3.545 12 3.545s-7.517 0-9.388.508a3.003 3.003 0 0 0-2.11 2.11C0 8.033 0 12 0 12s0 3.967.502 5.837a3.003 3.003 0 0 0 2.11 2.11c1.871.508 9.388.508 9.388.508s7.517 0 9.388-.508a3.003 3.003 0 0 0 2.11-2.11C24 15.967 24 12 24 12s0-3.967-.502-5.837zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                    </svg>
                </div>
                <div class="vmax-channel-details">
                    <span class="vmax-channel-title">Kênh YouTube</span>
                    <span class="vmax-channel-desc">Xem hướng dẫn chi tiết từ A - Z</span>
                </div>
                <div class="vmax-channel-action">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </div>
            </a>
        </div>
        
        <!-- Footer -->
        <div class="vmax-card-footer">
            <span class="vmax-footer-hotline">Hotline: <strong>0886.335.455</strong></span>
            <span class="vmax-status-badge">
                <span class="vmax-status-dot"></span>
                Hỗ trợ 24/7
            </span>
        </div>
    </div>

    <!-- Floating Trigger Button -->
    <button id="vmax-contact-trigger" class="vmax-contact-trigger" onclick="toggleVmaxContact()" aria-label="Liên hệ tư vấn">
        <span class="vmax-pulse-ring"></span>
        <div class="vmax-trigger-icons">
            <!-- Normal Chat Icon -->
            <svg class="vmax-icon-chat" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            <!-- Close Icon -->
            <svg class="vmax-icon-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </div>
        <span class="vmax-trigger-label">Tư vấn trực tuyến</span>
        <!-- Notification badge -->
        <span class="vmax-notification-badge">1</span>
    </button>
</div>

<style>
/* Reset and Container */
.vmax-contact-widget {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 999999;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 16px;
    box-sizing: border-box;
}

.vmax-contact-widget * {
    box-sizing: border-box;
}

/* Floating Trigger Button */
.vmax-contact-trigger {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff;
    border: none;
    outline: none;
    padding: 14px 24px;
    border-radius: 50px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.4), 0 8px 16px -6px rgba(37, 99, 235, 0.3);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    user-select: none;
}

.vmax-contact-trigger:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 30px -5px rgba(37, 99, 235, 0.5), 0 10px 20px -6px rgba(37, 99, 235, 0.4);
    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
}

.vmax-contact-trigger:active {
    transform: translateY(1px);
}

.vmax-trigger-label {
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 0.2px;
    white-space: nowrap;
}

.vmax-trigger-icons {
    width: 20px;
    height: 20px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.vmax-trigger-icons svg {
    position: absolute;
    width: 20px;
    height: 20px;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.vmax-icon-close {
    opacity: 0;
    transform: rotate(-90deg) scale(0.5);
}

/* Notification badge */
.vmax-notification-badge {
    position: absolute;
    top: -4px;
    left: -4px;
    background-color: #ef4444;
    color: white;
    font-size: 10px;
    font-weight: 800;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #ffffff;
    box-shadow: 0 2px 5px rgba(239, 68, 68, 0.3);
}

/* Pulse Ring Animation */
.vmax-pulse-ring {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 50px;
    border: 2.5px solid #2563eb;
    animation: vmax-pulse 2s infinite;
    pointer-events: none;
}

@keyframes vmax-pulse {
    0% {
        transform: scale(1);
        opacity: 0.7;
    }
    100% {
        transform: scale(1.15, 1.35);
        opacity: 0;
    }
}

/* Contact Card Container */
.vmax-contact-card {
    width: 360px;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.5);
    border-radius: 24px;
    box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.15), 0 0 1px rgba(15, 23, 42, 0.1);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    opacity: 0;
    visibility: hidden;
    transform: translateY(20px) scale(0.95);
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    pointer-events: none;
}

/* Active State layout */
.vmax-contact-widget.active .vmax-contact-card {
    opacity: 1;
    visibility: visible;
    transform: translateY(0) scale(1);
    pointer-events: auto;
}

.vmax-contact-widget.active .vmax-icon-chat {
    opacity: 0;
    transform: rotate(90deg) scale(0.5);
}

.vmax-contact-widget.active .vmax-icon-close {
    opacity: 1;
    transform: rotate(0) scale(1);
}

/* Card Header */
.vmax-card-header {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    padding: 20px 24px;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.vmax-header-info h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 800;
    letter-spacing: 0.2px;
}

.vmax-header-info p {
    margin: 4px 0 0 0;
    font-size: 11px;
    color: #94a3b8;
    font-weight: 500;
}

.vmax-close-card {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: #ffffff;
    font-size: 20px;
    line-height: 1;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.vmax-close-card:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.05);
}

/* Card Body */
.vmax-card-body {
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    background: rgba(255, 255, 255, 0.4);
}

/* Channel Row Styling */
.vmax-channel-row {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 14px;
    border-radius: 16px;
    text-decoration: none;
    background: #ffffff;
    border: 1px solid rgba(241, 245, 249, 0.8);
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}

.vmax-channel-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    shrink-0: 0;
    transition: all 0.2s ease;
}

.vmax-channel-icon svg {
    width: 20px;
    height: 20px;
}

.vmax-channel-details {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    min-w: 0;
}

.vmax-channel-title {
    font-size: 13px;
    font-weight: 750;
    color: #1e293b;
}

.vmax-channel-desc {
    font-size: 10px;
    color: #64748b;
    margin-top: 2px;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
}

.vmax-channel-action {
    color: #cbd5e1;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
}

.vmax-channel-action svg {
    width: 16px;
    height: 16px;
    transform: translateX(-3px);
    opacity: 0;
    transition: all 0.2s ease;
}

/* Row Hover States */
.vmax-channel-row:hover {
    transform: translateX(-4px);
    box-shadow: 0 8px 20px -8px rgba(15, 23, 42, 0.08);
    border-color: #cbd5e1;
}

.vmax-channel-row:hover .vmax-channel-action {
    color: #2563eb;
}

.vmax-channel-row:hover .vmax-channel-action svg {
    transform: translateX(0);
    opacity: 1;
}

/* Brand styling custom classes */
.vmax-channel-row.facebook .vmax-channel-icon {
    background: #1877F2;
    box-shadow: 0 4px 10px rgba(24, 119, 242, 0.25);
}
.vmax-channel-row.facebook:hover {
    background: rgba(24, 119, 242, 0.03);
}

.vmax-channel-row.zalo .vmax-channel-icon {
    background: #0068FF;
    box-shadow: 0 4px 10px rgba(0, 104, 255, 0.25);
}
.vmax-channel-row.zalo:hover {
    background: rgba(0, 104, 255, 0.03);
}

.vmax-channel-row.tiktok .vmax-channel-icon {
    background: #09090b;
    box-shadow: 0 4px 10px rgba(9, 9, 11, 0.25);
}
.vmax-channel-row.tiktok:hover {
    background: rgba(9, 9, 11, 0.03);
}

.vmax-channel-row.youtube .vmax-channel-icon {
    background: #FF0000;
    box-shadow: 0 4px 10px rgba(255, 0, 0, 0.25);
}
.vmax-channel-row.youtube:hover {
    background: rgba(255, 0, 0, 0.03);
}

/* Card Footer */
.vmax-card-footer {
    border-top: 1px solid rgba(226, 232, 240, 0.6);
    padding: 14px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #ffffff;
    font-size: 11px;
    color: #64748b;
}

.vmax-footer-hotline strong {
    color: #1e293b;
}

.vmax-status-badge {
    display: flex;
    align-items: center;
    gap: 6px;
    font-weight: 700;
    color: #10b981;
}

.vmax-status-dot {
    width: 6px;
    height: 6px;
    background-color: #10b981;
    border-radius: 50%;
    display: inline-block;
    animation: vmax-ping 1.5s infinite;
}

@keyframes vmax-ping {
    0% {
        transform: scale(0.9);
        opacity: 1;
    }
    50% {
        transform: scale(1.3);
        opacity: 0.4;
    }
    100% {
        transform: scale(0.9);
        opacity: 1;
    }
}

/* Mobile responsive adjustments */
@media (max-width: 480px) {
    .vmax-contact-widget {
        bottom: 16px;
        right: 16px;
    }
    .vmax-contact-card {
        width: calc(100vw - 32px);
    }
    .vmax-trigger-label {
        display: none;
    }
    .vmax-contact-trigger {
        width: 48px;
        height: 48px;
        padding: 0;
        justify-content: center;
        border-radius: 50%;
    }
    .vmax-pulse-ring {
        border-radius: 50%;
    }
    .vmax-notification-badge {
        top: -3px;
        left: -3px;
    }
}

/* Floating Zalo Chat Widget Welcome Tooltip */
.vmax-chat-welcome-bubble {
    position: absolute;
    bottom: 72px;
    right: 0;
    width: 270px;
    background: #ffffff;
    color: #1e293b;
    border: 1px solid rgba(226, 232, 240, 0.9);
    padding: 14px 18px;
    border-radius: 20px;
    box-shadow: 0 15px 30px -5px rgba(15, 23, 42, 0.12), 0 8px 16px -6px rgba(15, 23, 42, 0.06);
    font-size: 12px;
    line-height: 1.5;
    font-weight: 600;
    z-index: 999999;
    opacity: 0;
    visibility: hidden;
    transform: translateY(12px) scale(0.95);
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    pointer-events: none;
}
.vmax-chat-welcome-bubble.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0) scale(1);
    pointer-events: auto;
}
.vmax-chat-welcome-bubble::after {
    content: '';
    position: absolute;
    bottom: -6px;
    right: 28px;
    width: 12px;
    height: 12px;
    background: #ffffff;
    border-right: 1px solid rgba(226, 232, 240, 0.9);
    border-bottom: 1px solid rgba(226, 232, 240, 0.9);
    transform: rotate(45deg);
}
.vmax-chat-welcome-close {
    position: absolute;
    top: 8px;
    right: 10px;
    background: none;
    border: none;
    font-size: 14px;
    line-height: 1;
    color: #94a3b8;
    cursor: pointer;
    padding: 2px;
    font-weight: bold;
}
.vmax-chat-welcome-close:hover {
    color: #64748b;
}
</style>

<script>
function toggleVmaxContact() {
    const widget = document.getElementById('vmax-contact-widget');
    widget.classList.toggle('active');
    
    // Hide welcome bubble when opened
    const welcome = document.getElementById('vmax-chat-welcome');
    if (welcome) {
        welcome.classList.remove('show');
    }
}

function closeVmaxWelcome(event) {
    event.stopPropagation(); // Prevent trigger toggle
    const welcome = document.getElementById('vmax-chat-welcome');
    if (welcome) {
        welcome.classList.remove('show');
    }
    // Store in session storage so it doesn't pop up again in this session
    sessionStorage.setItem('vmax_welcome_closed', 'true');
}

// Auto show welcome bubble after 3 seconds
window.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        const welcomeClosed = sessionStorage.getItem('vmax_welcome_closed');
        const widget = document.getElementById('vmax-contact-widget');
        const welcome = document.getElementById('vmax-chat-welcome');
        // Show only if widget is not active and not closed before
        if (welcome && widget && !widget.classList.contains('active') && welcomeClosed !== 'true') {
            welcome.classList.add('show');
        }
    }, 3000);
});

// Close widget when clicking outside
document.addEventListener('click', function(event) {
    const widget = document.getElementById('vmax-contact-widget');
    if (widget && !widget.contains(event.target)) {
        widget.classList.remove('active');
    }
});
</script>
