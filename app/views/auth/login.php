<!DOCTYPE html>
<html lang="vi" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="dark light">
    <title><?= htmlspecialchars($pageTitle ?? 'Đăng Nhập PolyMee - FPT Polytechnic Hub') ?></title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- PolyMee Design-Engineered Stylesheet -->
    <link rel="stylesheet" href="/assets/css/style.css">

    <style>
    :root, [data-bs-theme="light"] {
        color-scheme: light;
        --ux-page: #F5F5F7;
        --ux-surface: #FFFFFF;
        --ux-surface-2: #FAFAFC;
        --ux-surface-3: #E5E5EA;
        --ux-ink: #1D1D1F;
        --ux-ink-2: #48484A;
        --ux-ink-3: #86868B;
        --ux-line: rgba(0, 0, 0, 0.08);
        --ux-line-2: #D1D1D6;
        --ux-accent: #EA580C;
        --ux-accent-hover: #F97316;
        --ux-accent-soft: rgba(234, 88, 12, 0.08);
        --ux-navy-gradient: linear-gradient(180deg, #FFFFFF 0%, #FAFAFC 100%);
        --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
        --ux-spring: cubic-bezier(0.16, 1, 0.3, 1);
    }

    [data-bs-theme="dark"] {
        color-scheme: dark;
        --ux-page: #1C1C1E;
        --ux-surface: #2C2C2E;
        --ux-surface-2: #3A3A3C;
        --ux-surface-3: #48484A;
        --ux-ink: #FFFFFF;
        --ux-ink-2: #EBEBF5;
        --ux-ink-3: #8E8E93;
        --ux-line: rgba(255, 255, 255, 0.12);
        --ux-line-2: rgba(255, 255, 255, 0.2);
        --ux-accent: #EA580C;
        --ux-accent-hover: #F97316;
        --ux-accent-soft: rgba(234, 88, 12, 0.15);
        --ux-navy-gradient: linear-gradient(180deg, #1C1C1E 0%, #2C2C2E 100%);
        --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
        --ux-spring: cubic-bezier(0.16, 1, 0.3, 1);
    }

    html, body {
        height: 100vh;
        max-height: 100vh;
        overflow: hidden;
    }

    body {
        font-family: "Plus Jakarta Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        background-color: #F4F4F6;
        color: var(--ux-ink);
        height: 100vh;
        max-height: 100vh;
        margin: 0;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .num-tabular {
        font-variant-numeric: tabular-nums;
    }

    /* Split-Screen Layout: Fits Exactly Inside 100vh Without Window Scroll */
    .auth-split-wrapper {
        height: 100vh;
        max-height: 100vh;
        display: flex;
        flex: 1;
        overflow: hidden;
    }

    /* Left Column: Chuẩn Nền Màu Xám Apple & Hệ Thống (#F4F4F6) */
    .auth-hero-pane {
        flex: 1.1;
        background: #F4F4F6 !important;
        border-right: 1px solid rgba(0, 0, 0, 0.08);
        padding: 36px 44px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100vh;
        max-height: 100vh;
        overflow: hidden;
        position: relative;
    }

    .auth-form-pane {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px 24px;
        background: #FFFFFF;
        height: 100vh;
        max-height: 100vh;
        overflow-y: auto;
        position: relative;
    }

    @media (max-width: 992px) {
        html, body, .auth-split-wrapper, .auth-form-pane {
            height: auto;
            max-height: none;
            overflow: auto;
        }
        .auth-hero-pane {
            display: none;
        }
        .auth-form-pane {
            padding: 30px 16px;
        }
    }

    .login-glass-card {
        width: 100%;
        max-width: 440px;
        background: #FFFFFF;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 20px;
        padding: 24px 26px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        position: relative;
    }

    [data-bs-theme="dark"] .login-glass-card {
        background: #2C2C2E;
        border-color: rgba(255, 255, 255, 0.12);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
    }

    .feature-pill {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 12px 16px;
        border-radius: 14px;
        background: #FFFFFF;
        border: 1px solid rgba(0, 0, 0, 0.06);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        transition: all 0.2s var(--ux-spring);
    }

    .feature-pill:hover {
        background: #FFFFFF;
        transform: translateY(-2px);
        border-color: rgba(234, 88, 12, 0.3);
        box-shadow: 0 4px 12px rgba(234, 88, 12, 0.08);
    }

    .feature-icon-box {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        flex: none;
        font-size: 15px;
    }

    .demo-account-list {
        max-height: 195px;
        overflow-y: auto;
        padding: 8px 6px;
        box-sizing: border-box;
        scroll-padding-top: 8px;
    }

    .demo-account-pill {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 7px 10px;
        border-radius: 12px;
        background: #F4F4F6;
        border: 1.5px solid rgba(0, 0, 0, 0.06);
        text-decoration: none;
        color: var(--ux-ink);
        box-sizing: border-box;
        transition: all 0.18s var(--ux-spring);
        margin: 1px 0;
    }

    .demo-account-pill:hover {
        background: #FFFFFF;
        border-color: var(--ux-accent);
        color: var(--ux-ink) !important;
        box-shadow: 0 2px 8px rgba(234, 88, 12, 0.12);
    }

    .demo-account-pill:hover .fw-bold {
        color: var(--ux-accent) !important;
    }

    .demo-account-pill:hover small {
        color: var(--ux-ink-3) !important;
    }

    .demo-account-pill:hover small strong {
        color: var(--ux-ink) !important;
    }

    .demo-account-pill:hover i {
        color: var(--ux-accent) !important;
        transform: translateX(2px);
    }

    [data-bs-theme="dark"] .demo-account-pill {
        background: #3A3A3C;
        border-color: rgba(255, 255, 255, 0.08);
        color: #F5F5F7 !important;
    }

    [data-bs-theme="dark"] .demo-account-pill:hover {
        background: #2C2C2E;
        border-color: var(--ux-accent);
        color: #FFFFFF !important;
    }

    .demo-account-pill:active {
        transform: scale(0.98);
    }

    .avatar-circle {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        font-weight: 800;
        font-size: 10.5px;
        line-height: 1;
        flex: none;
    }

    .form-control {
        border-radius: 12px;
        padding: 11px 14px;
        background-color: var(--ux-surface-2);
        border: 1px solid var(--ux-line);
        color: var(--ux-ink);
        font-size: 13.5px;
        transition: all 0.18s var(--ux-spring);
    }

    .form-control:focus {
        background-color: var(--ux-surface-3);
        border-color: var(--ux-accent);
        color: #FFFFFF;
        box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.25);
    }

    .input-group-text {
        background-color: var(--ux-surface-2);
        border: 1px solid var(--ux-line);
        color: var(--ux-ink-3);
        border-radius: 12px 0 0 12px;
    }

    .brand-glass-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: rgba(255, 255, 255, 0.95);
        padding: 8px 16px;
        border-radius: 14px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    }
    </style>
</head>
<body>

    <div class="auth-split-wrapper">

        <!-- Left Column: Hero Identity Showcase -->
        <div class="auth-hero-pane">
            <div>
                <!-- School Brand & Identity -->
                <div class="d-flex align-items-center justify-content-between mb-5">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-inline-flex align-items-center justify-content-center p-1 rounded-3" style="height: 44px; border: none !important; box-shadow: none !important; background: transparent;">
                            <img src="/assets/images/fpt_polytechnic_logo.png" alt="FPT Polytechnic" style="height: 28px; width: auto; object-fit: contain;">
                        </div>
                        <div class="vr" style="height: 26px; opacity: 0.3; background-color: var(--ux-line-2);"></div>
                        <span class="fw-bolder fs-4 tracking-tight" style="color: var(--ux-ink);">Poly<strong style="color: var(--ux-accent);">Mee</strong></span>
                    </div>
                    <span class="badge rounded-pill num-tabular" style="background: rgba(234, 88, 12, 0.15); color: #FB923C; border: 1px solid rgba(234, 88, 12, 0.3); font-size: 11px; padding: 6px 12px;">
                        <i class="bi bi-cpu-fill me-1"></i> AI Powered Hub
                    </span>
                </div>

                <!-- Hero Headline -->
                <h1 class="display-6 fw-bolder mb-3" style="letter-spacing: -0.03em; line-height: 1.2;">
                    Nền Tảng Điều Phối Sự Kiện & <span style="color: var(--ux-accent);">Nghiệm Thu Thù Lao CTV</span>
                </h1>
                <p class="mb-4" style="color: var(--ux-ink-3); font-size: 15px; max-width: 520px; line-height: 1.6;">
                    PolyMee đồng bộ hóa toàn bộ quy trình từ đăng ký ca trực, kiểm tra xung đột thời gian thực, chụp ảnh minh chứng tại chỗ, ký hợp đồng điện tử và quyết toán tài chính FPT Polytechnic.
                </p>

                <!-- 4 Core Engine Showcases -->
                <div class="d-flex flex-column gap-3 mb-5" style="max-width: 540px;">
                    <div class="feature-pill">
                        <div class="feature-icon-box text-warning" style="background: rgba(245, 158, 11, 0.12);">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <strong style="color: var(--ux-ink); font-size: 13.5px;">Interval Collision Engine</strong>
                            <div style="font-size: 12px; color: var(--ux-ink-3);">Tự động phát hiện và chặn trùng ca trực tức thời theo mốc thời gian giao thoa.</div>
                        </div>
                    </div>

                    <div class="feature-pill">
                        <div class="feature-icon-box text-success" style="background: rgba(16, 185, 129, 0.12);">
                            <i class="bi bi-camera-fill"></i>
                        </div>
                        <div>
                            <strong style="color: var(--ux-ink); font-size: 13.5px;">Minh Chứng Hiện Trường (Zero Fraud)</strong>
                            <div style="font-size: 12px; color: var(--ux-ink-3);">CTV chụp ảnh có mặt thực tế, Giảng viên nghiệm thu và tự động sinh hợp đồng chuẩn A4.</div>
                        </div>
                    </div>

                    <div class="feature-pill">
                        <div class="feature-icon-box" style="color: var(--ux-accent); background: var(--ux-accent-soft);">
                            <i class="bi bi-robot"></i>
                        </div>
                        <div>
                            <strong style="color: var(--ux-ink); font-size: 13.5px;">Trợ Lý AI Function Calling 4.0</strong>
                            <div style="font-size: 12px; color: var(--ux-ink-3);">Admin/BTC có thể hỏi tự nhiên: tra cứu phòng trống, lọc CTV rảnh, gửi email và đặt lịch tự động.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Ticker -->
            <div class="pt-4 border-top d-flex align-items-center justify-content-between" style="border-color: var(--ux-line) !important; font-size: 12px; color: var(--ux-ink-3);">
                <div class="d-flex align-items-center gap-3">
                    <span class="d-flex align-items-center gap-1.5"><span class="rounded-circle d-inline-block" style="width: 7px; height: 7px; background: #10B981;"></span> Hệ Thống Hoạt Động 100%</span>
                    <span>•</span>
                    <span>Bản Quyền © 2026 FPT Polytechnic</span>
                </div>
                <span class="num-tabular font-monospace">Fall 2026 • WEB212</span>
            </div>
        </div>

        <!-- Right Column: Login Card & 1-Click Demo -->
        <div class="auth-form-pane">
            <div class="login-glass-card">

                <!-- Header on Mobile / Card Header -->
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-inline-flex align-items-center justify-content-center p-1 rounded-3" style="height: 40px; border: none !important; box-shadow: none !important; background: transparent;">
                            <img src="/assets/images/fpt_polytechnic_logo.png" alt="FPT Polytechnic" style="height: 26px; width: auto; object-fit: contain;">
                        </div>
                        <div class="vr" style="height: 24px; opacity: 0.25;"></div>
                        <div>
                            <h4 class="fw-bolder mb-0" style="color: var(--ux-ink); font-size: 19px;">Đăng Nhập PolyMee</h4>
                            <p class="small mb-0" style="color: var(--ux-ink-3); font-size: 11.5px;">Cổng điều phối sự kiện FPT Polytechnic</p>
                        </div>
                    </div>

                </div>

                <!-- Flash Notification -->
                <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-info py-2.5 px-3 small rounded-3 d-flex align-items-center justify-content-between mb-3" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>Tài khoản của bạn đã được xóa thành công khỏi hệ thống PolyMee.</span>
                    </div>
                    <button type="button" class="btn-close btn-close-sm" onclick="this.parentElement.remove()"></button>
                </div>
                <?php endif; ?>
                <?php if (!empty($flash)): ?>
                <div class="alert <?= $flash['type'] === 'success' ? 'alert-success' : ($flash['type'] === 'info' ? 'alert-info' : 'alert-danger') ?> py-2.5 px-3 small rounded-3 d-flex align-items-center justify-content-between mb-3" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi <?= $flash['type'] === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' ?>"></i>
                        <span><?= $flash['message'] ?></span>
                    </div>
                    <button type="button" class="btn-close btn-close-sm" onclick="this.parentElement.remove()"></button>
                </div>
                <?php endif; ?>

                <!-- Form Đăng Nhập Chính -->
                <form action="/auth/login" method="POST" id="loginForm" class="mb-3">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold small" style="color: var(--ux-ink);">Email FPT hoặc Mã Sinh Viên (MSSV)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                            <input 
                                type="text" 
                                name="account" 
                                id="loginAccount" 
                                class="form-control" 
                                placeholder="Ví dụ: PI03084 hoặc phuonganh@fpt.edu.vn" 
                                value="<?= htmlspecialchars($oldAccount ?? '') ?>" 
                                required 
                                autofocus
                            >
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold small mb-0" style="color: var(--ux-ink);">Mật Khẩu</label>
                            <span class="small num-tabular" style="font-size: 11px; color: var(--ux-ink-3);">Mặc định: <strong>123456</strong></span>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-shield-lock-fill"></i></span>
                            <input 
                                type="password" 
                                name="password" 
                                id="loginPassword" 
                                class="form-control" 
                                placeholder="Nhập mật khẩu..." 
                                required
                            >
                            <button class="btn btn-outline-secondary border" type="button" onclick="togglePasswordVisibility('loginPassword', this)">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3" style="font-size: 12px;">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" id="rememberMe" checked>
                            <label class="form-check-label" for="rememberMe" style="color: var(--ux-ink-3);">Ghi nhớ đăng nhập</label>
                        </div>
                        <a href="javascript:void(0)" onclick="alert('Vui lòng liên hệ Phòng CTSV FPT Polytechnic để cấp lại mật khẩu.')" class="text-decoration-none fw-semibold" style="color: var(--ux-accent);">Quên mật khẩu?</a>
                    </div>

                    <button type="submit" class="btn-fpt-primary w-100 py-2.5">
                        <i class="bi bi-box-arrow-in-right me-1.5"></i> Đăng Nhập Vào Hệ Thống
                    </button>
                </form>

                <div class="text-center pb-2.5 border-bottom mb-3" style="font-size: 12.5px; border-color: var(--ux-line) !important;">
                    <span style="color: var(--ux-ink-3);">Chưa có tài khoản CTV?</span>
                    <a href="/auth/register" class="fw-bold text-decoration-none ms-1" style="color: var(--ux-accent);">Đăng ký tài khoản mới ↗</a>
                </div>

                <!-- 1-Click Demo Accounts (Dành cho Giảng viên / Hội đồng chấm thi) -->
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 0.05em; color: var(--ux-ink-3);">
                            <i class="bi bi-stars text-warning me-1"></i> Chấm Điểm 1 Chạm (7 Demo Roles Chuẩn BA):
                        </span>
                        <span class="badge num-tabular" style="font-size: 9.5px; background: rgba(245, 158, 11, 0.12); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.25);">Auto Login</span>
                    </div>

                    <div class="demo-account-list d-flex flex-column gap-2">
                        <a href="/auth/quickLogin/3" class="demo-account-pill">
                            <div class="avatar-circle bg-primary-subtle text-primary">PA</div>
                            <div class="flex-grow-1">
                                <div class="fw-bold small lh-1">Đinh Nguyễn Phương Anh</div>
                                <small style="font-size: 11px; color: var(--ux-ink-3);">PI03084 • Role: <strong style="color: var(--ux-ink-2);">CTV Media & Nhiếp Ảnh</strong></small>
                            </div>
                            <i class="bi bi-chevron-right small" style="color: var(--ux-ink-3);"></i>
                        </a>

                        <a href="/auth/quickLogin/4" class="demo-account-pill">
                            <div class="avatar-circle bg-emerald-subtle text-success">ĐM</div>
                            <div class="flex-grow-1">
                                <div class="fw-bold small lh-1">Lê Đức Mạnh</div>
                                <small style="font-size: 11px; color: var(--ux-ink-3);">PS20262 • Role: <strong style="color: var(--ux-ink-2);">Cộng Tác Viên (CTV)</strong></small>
                            </div>
                            <i class="bi bi-chevron-right small" style="color: var(--ux-ink-3);"></i>
                        </a>

                        <a href="/auth/quickLogin/2" class="demo-account-pill">
                            <div class="avatar-circle bg-warning-subtle text-warning-emphasis">GV</div>
                            <div class="flex-grow-1">
                                <div class="fw-bold small lh-1">Thầy Phan Hoàng Khải</div>
                                <small style="font-size: 11px; color: var(--ux-ink-3);">GV0102 • Role: <strong style="color: var(--ux-ink-2);">Giảng Viên / Ban Tổ Chức</strong></small>
                            </div>
                            <i class="bi bi-chevron-right small" style="color: var(--ux-ink-3);"></i>
                        </a>

                        <a href="/auth/quickLogin/8" class="demo-account-pill">
                            <div class="avatar-circle bg-info-subtle text-info-emphasis">HC</div>
                            <div class="flex-grow-1">
                                <div class="fw-bold small lh-1">Lê Đình Tuấn</div>
                                <small style="font-size: 11px; color: var(--ux-ink-3);">HC0201 • Role: <strong style="color: var(--ux-ink-2);">Nhân Viên Hành Chính CSVC</strong></small>
                            </div>
                            <i class="bi bi-chevron-right small" style="color: var(--ux-ink-3);"></i>
                        </a>

                        <a href="/auth/quickLogin/13" class="demo-account-pill">
                            <div class="avatar-circle bg-dark-subtle text-dark">PS</div>
                            <div class="flex-grow-1">
                                <div class="fw-bold small lh-1">ThS. Phạm Hồng Sơn</div>
                                <small style="font-size: 11px; color: var(--ux-ink-3);">HC0001 • Role: <strong style="color: var(--ux-ink-2);">Trưởng Hành Chính / BGH</strong></small>
                            </div>
                            <i class="bi bi-chevron-right small" style="color: var(--ux-ink-3);"></i>
                        </a>

                        <a href="/auth/quickLogin/7" class="demo-account-pill">
                            <div class="avatar-circle bg-success-subtle text-success">KT</div>
                            <div class="flex-grow-1">
                                <div class="fw-bold small lh-1">Đặng Thu Thảo</div>
                                <small style="font-size: 11px; color: var(--ux-ink-3);">KT0105 • Role: <strong style="color: var(--ux-ink-2);">Kế Toán / Quyền Lợi & Quyết Toán</strong></small>
                            </div>
                            <i class="bi bi-chevron-right small" style="color: var(--ux-ink-3);"></i>
                        </a>

                        <a href="/auth/quickLogin/1" class="demo-account-pill">
                            <div class="avatar-circle bg-danger-subtle text-danger">AD</div>
                            <div class="flex-grow-1">
                                <div class="fw-bold small lh-1">Quản Trị Viên (Admin)</div>
                                <small style="font-size: 11px; color: var(--ux-ink-3);">ADMIN001 • Role: <strong style="color: var(--ux-ink-2);">Quản Trị Toàn Hệ Thống</strong></small>
                            </div>
                            <i class="bi bi-chevron-right small" style="color: var(--ux-ink-3);"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Scripts -->
    <script>
    function togglePasswordVisibility(fieldId, btn) {
        const field = document.getElementById(fieldId);
        const icon = btn.querySelector('i');
        if (field.type === 'password') {
            field.type = 'text';
            icon.className = 'bi bi-eye-slash-fill';
        } else {
            field.type = 'password';
            icon.className = 'bi bi-eye-fill';
        }
    }

    function initAuthTheme() {
        const saved = localStorage.getItem('polymee_theme') || 'light';
        setAuthTheme(saved);
    }

    function setAuthTheme(theme) {
        const root = document.documentElement;
        root.setAttribute('data-bs-theme', theme);
        if (theme === 'dark') {
            root.classList.add('dark');
            document.querySelectorAll('.dark-icon').forEach(i => i.classList.remove('d-none'));
            document.querySelectorAll('.light-icon').forEach(i => i.classList.add('d-none'));
        } else {
            root.classList.remove('dark');
            document.querySelectorAll('.dark-icon').forEach(i => i.classList.add('d-none'));
            document.querySelectorAll('.light-icon').forEach(i => i.classList.remove('d-none'));
        }
        localStorage.setItem('polymee_theme', theme);
    }

    function toggleAuthTheme() {
        const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        setAuthTheme(isDark ? 'light' : 'dark');
    }

    initAuthTheme();
    </script>

</body>
</html>
