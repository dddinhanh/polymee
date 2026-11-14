<!DOCTYPE html>
<html lang="vi" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="dark light">
    <title><?= htmlspecialchars($pageTitle ?? 'Đăng Ký Cộng Tác Viên - PolyMee Hub') ?></title>

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
    }

    body {
        font-family: "Plus Jakarta Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        background-color: var(--ux-page);
        color: var(--ux-ink);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        margin: 0;
    }

    .num-tabular {
        font-variant-numeric: tabular-nums;
    }

    .auth-container {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 16px;
    }

    .auth-card {
        background: var(--ux-surface);
        border: 1px solid var(--ux-line);
        border-radius: 24px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
        width: 100%;
        max-width: 680px;
        overflow: hidden;
        animation: cardFadeIn 0.25s var(--ease-out);
    }

    @keyframes cardFadeIn {
        from { opacity: 0; transform: translateY(8px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .auth-header {
        background: var(--ux-navy-gradient);
        border-bottom: 1px solid var(--ux-line);
        padding: 28px 32px 22px;
        color: #FFFFFF;
        position: relative;
    }

    .form-control, .form-select {
        border-radius: 12px;
        padding: 10px 14px;
        background-color: var(--ux-surface-2);
        border: 1px solid var(--ux-line);
        color: var(--ux-ink);
        font-size: 13.5px;
        transition: all 0.18s var(--ease-out);
    }

    .form-control:focus, .form-select:focus {
        background-color: var(--ux-surface-3);
        border-color: var(--ux-accent);
        color: #FFFFFF;
        box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.25);
    }

    .form-section-title {
        font-size: 11.5px;
        text-transform: uppercase;
        font-weight: 800;
        letter-spacing: 0.05em;
        color: var(--ux-accent);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    </style>
</head>
<body>

    <!-- Header / Brand Strip (No Image Logo) -->
    <header class="py-2.5 px-4 border-bottom d-flex justify-content-between align-items-center" style="background: var(--ux-surface); border-color: var(--ux-line) !important;">
        <a href="/dashboard" class="d-flex align-items-center gap-3 text-decoration-none">
            <div class="bg-white p-1.5 px-2.5 rounded-3 border shadow-xs" style="height: 38px; display: inline-flex; align-items: center;">
                <img src="/assets/images/fpt_polytechnic_logo.png" alt="FPT Polytechnic" style="height: 24px; width: auto; object-fit: contain;">
            </div>
            <div class="vr mx-1" style="height: 22px; opacity: 0.25;"></div>
            <span class="fw-bolder fs-5 tracking-tight" style="color: var(--ux-ink);">Poly<strong style="color:var(--ux-accent);">Mee</strong></span>
        </a>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm btn-outline-secondary border-0 px-2 py-1 rounded-circle" onclick="toggleRegisterTheme()" title="Chuyển chế độ Sáng / Tối">
                <i class="bi bi-sun-fill dark-icon text-warning"></i>
                <i class="bi bi-moon-stars-fill light-icon text-secondary d-none"></i>
            </button>
            <a href="/auth/login" class="btn-fpt-secondary btn-fpt-sm">
                <i class="bi bi-box-arrow-in-right me-1"></i> Đã Có Tài Khoản? Đăng Nhập
            </a>
        </div>
    </header>

    <!-- Main Auth Content -->
    <div class="auth-container">
        <div class="auth-card">
            
            <!-- Auth Header (No Image Logo) -->
            <div class="auth-header d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="fw-bolder mb-1" style="color: #FFFFFF;">Đăng Ký Cộng Tác Viên FPT Polytechnic</h4>
                    <p class="small mb-0 text-white-50">Tạo hồ sơ nhận thù lao quyết toán và đăng ký ca trực sự kiện</p>
                </div>
                <div class="d-none d-sm-block text-end">
                    <span class="badge font-monospace fw-bold px-2.5 py-1.5" style="background: rgba(245, 158, 11, 0.2); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.4);">Role: Student CTV</span>
                </div>
            </div>

            <div class="p-4 p-md-5">

                <!-- Flash Message Notification -->
                <?php if (!empty($flash)): ?>
                <div class="alert <?= $flash['type'] === 'success' ? 'alert-success' : 'alert-danger' ?> py-2.5 px-3 small rounded-3 d-flex align-items-center justify-content-between mb-4" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi <?= $flash['type'] === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' ?>"></i>
                        <div><?= $flash['message'] ?></div>
                    </div>
                    <button type="button" class="btn-close btn-close-sm" onclick="this.parentElement.remove()"></button>
                </div>
                <?php endif; ?>

                <form action="/auth/register" method="POST" id="registerForm">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">

                    <!-- PHẦN 1: THÔNG TIN SINH VIÊN -->
                    <div class="form-section-title">
                        <i class="bi bi-person-badge-fill"></i> 1. Thông Tin Sinh Viên & Tài Khoản
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small" style="color: var(--ux-ink);">Họ và Tên Sinh Viên <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" placeholder="Ví dụ: Nguyễn Văn An" value="<?= htmlspecialchars($old['full_name'] ?? '') ?>" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small" style="color: var(--ux-ink);">Mã Sinh Viên (MSSV) <span class="text-danger">*</span></label>
                            <input type="text" name="student_code" class="form-control text-uppercase num-tabular" placeholder="Ví dụ: PS20268" value="<?= htmlspecialchars($old['student_code'] ?? '') ?>" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small" style="color: var(--ux-ink);">Email FPT Polytechnic <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="annvps20268@fpt.edu.vn" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small" style="color: var(--ux-ink);">Số Điện Thoại Liên Hệ <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" class="form-control num-tabular" placeholder="09xxxxxxxx" value="<?= htmlspecialchars($old['phone'] ?? '') ?>" required>
                        </div>
                    </div>

                    <!-- THÔNG BÁO HỒ SƠ QUYẾT TOÁN CẬP NHẬT SAU KHI ĐĂNG NHẬP -->
                    <div class="alert rounded-3 py-2.5 px-3 mb-4 d-flex align-items-center gap-2" style="background: var(--ux-surface-2); border: 1px solid var(--ux-line); font-size: 12.5px; color: var(--ux-ink-2);">
                        <i class="bi bi-shield-lock-fill text-warning fs-6 flex-shrink-0"></i>
                        <span>Thông tin Căn cước công dân (CCCD 12 số), Số tài khoản ngân hàng và thẻ VNeID nhận thù lao sẽ được điền an toàn trong mục <strong>Cài Đặt Tài Khoản Cá Nhân</strong> sau khi đăng nhập.</span>
                    </div>

                    <!-- PHẦN 3: KỸ NĂNG & BẢO MẬT -->
                    <div class="form-section-title">
                        <i class="bi bi-shield-lock-fill"></i> 3. Kỹ Năng Thế Mạnh & Mật Khẩu Đăng Nhập
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-bold small" style="color: var(--ux-ink);">Kỹ Năng / Sở Trường CTV</label>
                            <input type="text" name="skills" class="form-control" placeholder="Ví dụ: Media quay chụp, Lễ tân đón tiếp, Hậu cần setup, MC sự kiện..." value="<?= htmlspecialchars($old['skills'] ?? '') ?>">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small" style="color: var(--ux-ink);">Mật Khẩu <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Tối thiểu 6 ký tự" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small" style="color: var(--ux-ink);">Xác Nhận Mật Khẩu <span class="text-danger">*</span></label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu..." required>
                        </div>
                    </div>

                    <div class="form-check mb-4" style="font-size:12.5px;">
                        <input class="form-check-input" type="checkbox" id="termsCheck" required checked>
                        <label class="form-check-label" for="termsCheck" style="color: var(--ux-ink-3);">
                            Tôi cam kết thông tin tài khoản sinh viên FPT Polytechnic chính xác và tuân thủ quy chế CTV.
                        </label>
                    </div>

                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-3 border-top" style="border-color: var(--ux-line) !important;">
                        <a href="/auth/login" class="text-decoration-none small" style="color: var(--ux-ink-3);">
                            <i class="bi bi-arrow-left me-1"></i> Quay lại Đăng nhập
                        </a>
                        <button type="submit" class="btn-fpt-primary px-4 py-2.5">
                            <i class="bi bi-person-plus-fill me-1.5"></i> Hoàn Tất Đăng Ký CTV
                        </button>
                    </div>
                </form>

            </div>

            <!-- Card Footer -->
            <div class="px-4 py-3 border-top text-center" style="background: var(--ux-surface-2); border-color: var(--ux-line) !important; font-size: 11.5px; color: var(--ux-ink-3);">
                <span>FPT Polytechnic • WEB212 Phát Triển Dự Án Website Với AI</span>
            </div>

        </div>
    </div>

    <!-- Scripts -->
    <script>
    function initRegisterTheme() {
        const saved = localStorage.getItem('polymee_theme') || 'light';
        setRegisterTheme(saved);
    }

    function setRegisterTheme(theme) {
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

    function toggleRegisterTheme() {
        const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        setRegisterTheme(isDark ? 'light' : 'dark');
    }

    initRegisterTheme();
    </script>

</body>
</html>
