<!DOCTYPE html>
<html lang="vi" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Đăng Nhập PolyMee - FPT Polytechnic') ?></title>

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

    <!-- PolyMee Design-Engineered Stylesheet (design-taste-frontend-v1) -->
    <link rel="stylesheet" href="/assets/css/style.css">

    <style>
    :root {
        --ux-page: #F8FAFC;
        --ux-ink: #0F172A;
        --ux-accent: #EA580C;
        --ux-accent-hover: #C2410C;
        --ux-accent-gradient: linear-gradient(135deg, #F97316 0%, #EA580C 100%);
        --ux-navy-gradient: linear-gradient(180deg, #0F172A 0%, #1E293B 100%);
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
        background-image: 
            radial-gradient(at 0% 0%, rgba(242, 111, 33, 0.08) 0px, transparent 50%),
            radial-gradient(at 100% 100%, rgba(10, 25, 47, 0.08) 0px, transparent 50%);
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
        background: #FFFFFF;
        border: 1.5px solid #E2E8F0;
        border-radius: 24px;
        box-shadow: 0 16px 40px rgba(10, 25, 47, 0.08);
        width: 100%;
        max-width: 460px;
        overflow: hidden;
        animation: cardFadeIn 0.25s var(--ease-out);
    }

    @keyframes cardFadeIn {
        from { opacity: 0; transform: translateY(8px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .auth-header {
        background: var(--ux-navy-gradient);
        padding: 32px 28px 24px;
        color: #FFFFFF;
        text-align: center;
        position: relative;
    }

    .auth-logo-badge {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        background: var(--ux-accent-gradient);
        display: grid;
        place-items: center;
        font-size: 24px;
        color: #FFFFFF;
        margin: 0 auto 14px;
        box-shadow: 0 6px 18px rgba(242, 111, 33, 0.4);
    }

    .form-control {
        border-radius: 12px;
        padding: 12px 14px;
        border: 1.5px solid #CBD5E1;
        font-size: 14px;
        transition: all 0.18s var(--ease-out);
    }

    .form-control:focus {
        border-color: var(--ux-accent);
        box-shadow: 0 0 0 3.5px rgba(242, 111, 33, 0.15);
    }

    .btn-submit {
        background: var(--ux-accent-gradient);
        color: #FFFFFF;
        border: none;
        border-radius: 12px;
        padding: 12px;
        font-size: 14.5px;
        font-weight: 750;
        width: 100%;
        cursor: pointer;
        transition: transform 120ms var(--ease-out), box-shadow 150ms ease;
        box-shadow: 0 4px 14px rgba(242, 111, 33, 0.32);
    }

    .btn-submit:active {
        transform: scale(0.97); /* Emil Kowalski active rule */
    }

    .demo-account-pill {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 12px;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        cursor: pointer;
        text-decoration: none;
        color: var(--ux-ink);
        transition: all 0.15s ease;
    }

    .demo-account-pill:hover {
        background: #FFF7ED;
        border-color: #FDBA74;
        transform: translateY(-1px);
    }

    .demo-account-pill:active {
        transform: scale(0.97);
    }

    .avatar-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        font-weight: 800;
        font-size: 12px;
        flex: none;
    }
    </style>
</head>
<body>

    <!-- Header / Brand Strip -->
    <header class="py-3 px-4 border-bottom bg-white d-flex justify-content-between align-items-center">
        <a href="/dashboard" class="d-flex align-items-center gap-2 text-decoration-none">
            <span class="rounded-3 px-2 py-1 bg-warning text-white fw-black" style="background:var(--ux-accent-gradient) !important;">
                <i class="fa-solid fa-graduation-cap"></i>
            </span>
            <span class="fw-bold fs-5 tracking-tight text-dark">Poly<strong style="color:var(--ux-accent);">Mee</strong></span>
            <span class="badge bg-light text-muted border">WEB212</span>
        </a>
        <a href="/dashboard" class="btn-fpt-secondary btn-fpt-sm">
            <i class="bi bi-grid-3x3-gap-fill me-1"></i> Vào Bảng Lịch (Dashboard)
        </a>
    </header>

    <!-- Main Auth Content -->
    <div class="auth-container">
        <div class="auth-card">
            
            <!-- Auth Header -->
            <div class="auth-header">
                <div class="auth-logo-badge">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <h4 class="fw-bold mb-1">Đăng Nhập PolyMee</h4>
                <p class="small mb-0 text-white-50">Cổng Quản Trị Hoạt Động & Nghiệm Thu Thù Lao CTV</p>
            </div>

            <div class="p-4">

                <!-- Flash Message Notification -->
                <?php if (!empty($flash)): ?>
                <div class="alert <?= $flash['type'] === 'success' ? 'alert-success' : ($flash['type'] === 'info' ? 'alert-info' : 'alert-danger') ?> py-2.5 px-3 small rounded-3 d-flex align-items-center justify-content-between mb-3" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi <?= $flash['type'] === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' ?>"></i>
                        <span><?= $flash['message'] ?></span>
                    </div>
                    <button type="button" class="btn-close btn-close-sm" onclick="this.parentElement.remove()"></button>
                </div>
                <?php endif; ?>

                <!-- Form Đăng Nhập -->
                <form action="/auth/login" method="POST" id="loginForm">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Email FPT hoặc Mã Sinh Viên (MSSV)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person-fill"></i></span>
                            <input 
                                type="text" 
                                name="account" 
                                id="loginAccount" 
                                class="form-control border-start-0 ps-0" 
                                placeholder="Ví dụ: PI03084 hoặc phuonganh@fpt.edu.vn" 
                                value="<?= htmlspecialchars($oldAccount ?? '') ?>" 
                                required 
                                autofocus
                            >
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold small text-dark mb-0">Mật Khẩu</label>
                            <span class="text-muted small" style="font-size:11.5px;">Mặc định: <strong>123456</strong></span>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shield-lock-fill"></i></span>
                            <input 
                                type="password" 
                                name="password" 
                                id="loginPassword" 
                                class="form-control border-start-0 border-end-0 px-0" 
                                placeholder="Nhập mật khẩu..." 
                                required
                            >
                            <button class="btn btn-outline-secondary border-start-0 text-muted" type="button" onclick="togglePasswordVisibility('loginPassword', this)">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4" style="font-size: 12.5px;">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" id="rememberMe" checked>
                            <label class="form-check-label text-muted" for="rememberMe">Ghi nhớ đăng nhập</label>
                        </div>
                        <a href="javascript:void(0)" onclick="alert('Vui lòng liên hệ Phòng CTSV FPT Polytechnic để cấp lại mật khẩu.')" class="text-decoration-none fw-semibold" style="color:var(--ux-accent);">Quên mật khẩu?</a>
                    </div>

                    <button type="submit" class="btn-fpt-primary w-100 py-2.5 mb-3">
                        <i class="bi bi-box-arrow-in-right me-1.5"></i> Đăng Nhập Vào Hệ Thống
                    </button>
                </form>

                <div class="text-center pb-3 border-bottom mb-3" style="font-size: 13px;">
                    <span class="text-muted">Chưa có tài khoản CTV?</span>
                    <a href="/auth/register" class="fw-bold text-decoration-none ms-1" style="color:var(--ux-accent);">Đăng ký CTV ngay ↗</a>
                </div>

                <!-- ĐĂNG NHẬP NHANH DEMO DÀNH CHO HỘI ĐỒNG CHẤM BÀI / GIẢNG VIÊN -->
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-uppercase text-muted fw-bold" style="font-size:10.5px; letter-spacing:0.04em;">Chấm Bài Demo 1 Chạm:</span>
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle" style="font-size:10px;">Không cần gõ pass</span>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        <a href="/auth/quickLogin/3" class="demo-account-pill">
                            <div class="avatar-circle bg-primary-subtle text-primary">PA</div>
                            <div class="flex-grow-1">
                                <div class="fw-bold small lh-1">Đinh Nguyễn Phương Anh</div>
                                <small class="text-muted" style="font-size:11px;">Mã SV: PI03084 • Role: <strong>Sinh Viên CTV Media</strong></small>
                            </div>
                            <i class="bi bi-chevron-right text-muted small"></i>
                        </a>

                        <a href="/auth/quickLogin/2" class="demo-account-pill">
                            <div class="avatar-circle bg-warning-subtle text-warning">GV</div>
                            <div class="flex-grow-1">
                                <div class="fw-bold small lh-1">Thầy Phan Hoàng Khải</div>
                                <small class="text-muted" style="font-size:11px;">Mã GV: GV0102 • Role: <strong>Ban Tổ Chức (Organizer)</strong></small>
                            </div>
                            <i class="bi bi-chevron-right text-muted small"></i>
                        </a>

                        <a href="/auth/quickLogin/1" class="demo-account-pill">
                            <div class="avatar-circle bg-danger-subtle text-danger">AD</div>
                            <div class="flex-grow-1">
                                <div class="fw-bold small lh-1">Quản Trị Viên (Admin)</div>
                                <small class="text-muted" style="font-size:11px;">Mã: ADMIN001 • Role: <strong>Kế Toán / Toàn Quyền</strong></small>
                            </div>
                            <i class="bi bi-chevron-right text-muted small"></i>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Card Footer -->
            <div class="bg-light px-4 py-3 border-top text-center text-muted" style="font-size: 11.5px;">
                <span>FPT Polytechnic • Môn học WEB212 Phát Triển Dự Án Với AI</span>
            </div>

        </div>
    </div>

    <!-- Toggle Password Script -->
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
    </script>

</body>
</html>

