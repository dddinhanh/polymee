<!DOCTYPE html>
<html lang="vi" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Đăng Ký Cộng Tác Viên - PolyMee') ?></title>

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
        padding: 28px 32px 22px;
        color: #FFFFFF;
        position: relative;
    }

    .form-control, .form-select {
        border-radius: 12px;
        padding: 10px 14px;
        border: 1.5px solid #CBD5E1;
        font-size: 13.5px;
        transition: all 0.18s var(--ease-out);
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--ux-accent);
        box-shadow: 0 0 0 3.5px rgba(242, 111, 33, 0.15);
    }

    .form-section-title {
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 800;
        letter-spacing: 0.05em;
        color: var(--ux-accent);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-submit {
        background: var(--ux-accent-gradient);
        color: #FFFFFF;
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        font-size: 14.5px;
        font-weight: 750;
        cursor: pointer;
        transition: transform 120ms var(--ease-out), box-shadow 150ms ease;
        box-shadow: 0 4px 14px rgba(242, 111, 33, 0.32);
    }

    .btn-submit:active {
        transform: scale(0.97);
    }
    </style>
</head>
<body>

    <!-- Header / Brand Strip -->
    <header class="py-3 px-4 border-bottom bg-white d-flex justify-content-between align-items-center shadow-xs">
        <a href="/dashboard" class="d-flex align-items-center text-decoration-none">
            <span class="fw-bold fs-5 tracking-tight text-dark">Poly<strong style="color:var(--ux-accent);">Mee</strong></span>
            <span class="badge bg-light text-muted border ms-2">FPT Polytechnic</span>
        </a>
        <a href="/auth/login" class="btn-fpt-secondary btn-fpt-sm">
            <i class="bi bi-box-arrow-in-right me-1"></i> Đã Có Tài Khoản? Đăng Nhập
        </a>
    </header>

    <!-- Main Auth Content -->
    <div class="auth-container">
        <div class="auth-card">
            
            <!-- Auth Header -->
            <div class="auth-header d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="fw-bold mb-1">Đăng Ký Cộng Tác Viên FPT Polytechnic</h4>
                    <p class="small mb-0 text-white-50">Tạo hồ sơ nhận thù lao quyết toán và đăng ký ca trực sự kiện</p>
                </div>
                <div class="d-none d-sm-block text-end">
                    <span class="badge bg-warning text-dark font-monospace fw-bold px-2.5 py-1.5">Role: Student CTV</span>
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
                            <label class="form-label fw-bold small text-dark">Họ và Tên Sinh Viên <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" placeholder="Ví dụ: Nguyễn Văn An" value="<?= htmlspecialchars($old['full_name'] ?? '') ?>" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-dark">Mã Sinh Viên (MSSV) <span class="text-danger">*</span></label>
                            <input type="text" name="student_code" class="form-control text-uppercase num-tabular" placeholder="Ví dụ: PS20268" value="<?= htmlspecialchars($old['student_code'] ?? '') ?>" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-dark">Email FPT Polytechnic <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="annvps20268@fpt.edu.vn" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-dark">Số Điện Thoại Liên Hệ <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" class="form-control num-tabular" placeholder="09xxxxxxxx" value="<?= htmlspecialchars($old['phone'] ?? '') ?>" required>
                        </div>
                    </div>

                    <!-- PHẦN 2: THÔNG TIN KẾ TOÁN & QUYẾT TOÁN THÙ LAO -->
                    <div class="form-section-title">
                        <i class="bi bi-wallet2"></i> 2. Thông Tin Quyết Toán Thù Lao & Ngân Hàng
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-dark">Số Căn Cước Công Dân (CCCD 12 số) <span class="text-danger">*</span></label>
                            <input type="text" name="id_card_number" class="form-control num-tabular" placeholder="Ví dụ: 079203014589" maxlength="12" value="<?= htmlspecialchars($old['id_card_number'] ?? '') ?>" required>
                            <small class="text-muted" style="font-size:11px;">Phục vụ kê khai thuế & đối soát thẻ VNeID</small>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-dark">Tên Ngân Hàng Thụ Hưởng <span class="text-danger">*</span></label>
                            <input type="text" name="bank_name" class="form-control" placeholder="Ví dụ: MB Bank, Vietcombank, TPBank..." value="<?= htmlspecialchars($old['bank_name'] ?? 'MB Bank') ?>" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-dark">Số Tài Khoản Ngân Hàng (STK) <span class="text-danger">*</span></label>
                            <input type="text" name="bank_account_number" class="form-control num-tabular" placeholder="Nhập số tài khoản chính xác..." value="<?= htmlspecialchars($old['bank_account_number'] ?? '') ?>" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-dark">Tên Chủ Tài Khoản (In hoa không dấu)</label>
                            <input type="text" name="bank_account_name" class="form-control text-uppercase" placeholder="NGUYEN VAN AN" value="<?= htmlspecialchars($old['bank_account_name'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- PHẦN 3: KỸ NĂNG & BẢO MẬT -->
                    <div class="form-section-title">
                        <i class="bi bi-shield-lock-fill"></i> 3. Kỹ Năng Thế Mạnh & Mật Khẩu Đăng Nhập
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-bold small text-dark">Kỹ Năng / Sở Trường CTV</label>
                            <input type="text" name="skills" class="form-control" placeholder="Ví dụ: Media quay chụp, Lễ tân đón tiếp, Hậu cần setup, MC sự kiện..." value="<?= htmlspecialchars($old['skills'] ?? '') ?>">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-dark">Mật Khẩu <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Tối thiểu 6 ký tự" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-dark">Xác Nhận Mật Khẩu <span class="text-danger">*</span></label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu..." required>
                        </div>
                    </div>

                    <div class="form-check mb-4" style="font-size:12.5px;">
                        <input class="form-check-input" type="checkbox" id="termsCheck" required checked>
                        <label class="form-check-label text-muted" for="termsCheck">
                            Tôi cam kết thông tin số CCCD và tài khoản ngân hàng chính xác để Phòng Kế Toán FPT Polytechnic thanh toán thù lao.
                        </label>
                    </div>

                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-3 border-top">
                        <a href="/auth/login" class="text-decoration-none small text-muted">
                            <i class="bi bi-arrow-left me-1"></i> Quay lại Đăng nhập
                        </a>
                        <button type="submit" class="btn-fpt-primary px-4 py-2.5">
                            <i class="bi bi-person-plus-fill me-1.5"></i> Hoàn Tất Đăng Ký CTV
                        </button>
                    </div>
                </form>

            </div>

            <!-- Card Footer -->
            <div class="bg-light px-4 py-3 border-top text-center text-muted" style="font-size: 11.5px;">
                <span>FPT Polytechnic • WEB212 Phát Triển Dự Án Website Với AI</span>
            </div>

        </div>
    </div>

</body>
</html>

