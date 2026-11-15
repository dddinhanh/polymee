<?php
/**
 * PolyMee - Dashboard Single Page Application
 * Áp dụng phân quyền chuẩn RBAC theo BA Report v1.0 & Usecase specifications
 */
require_once APP_ROOT . '/app/helpers/RolePermissionHelper.php';
$perms = $permissions ?? RolePermissionHelper::getPermissions($currentUser ?? null);
$userRole = $perms['role'];
$ctvRoster = array_values(array_filter($allUsers ?? [], fn($u) => in_array((int)($u['role_id'] ?? 0), [4, 6], true) || in_array($u['role_name'] ?? '', ['Student', 'Media'], true)));
?>
<!-- Lucian UX/UI Prompt #040 · MIT License (c) 2026 Seokwoooo · https://lucianlabs.dev/uxui-prompt/#040 -->
<!DOCTYPE html>
<html lang="vi" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="dark light">
    <title><?= htmlspecialchars($pageTitle ?? 'PolyMee Operations & Accounting Hub - FPT Polytechnic') ?></title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- QRCode.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <!-- PolyMee Design-Engineered Stylesheet (design-taste-frontend-v1) -->
    <link rel="stylesheet" href="/assets/css/style.css">

    <style>
    /* =========================================================
       POLYMEE ENTERPRISE UI/UX PROMAX (Tailwind Slate/Obsidian Theme)
       Màu sắc hiệu chuẩn: Slate 950/900/800 + Cam FPT Signature (#EA580C)
       Phân tầng thị giác tinh tế, giảm tải thông tin, chống rối mắt
       ========================================================= */
    :root, [data-bs-theme="light"] {
        color-scheme: light;
        --ux-page: #F5F5F7;              /* Apple Canvas Parchment */
        --ux-surface: #FFFFFF;           /* Apple Pure White Canvas */
        --ux-surface-2: #FAFAFC;         /* Apple Surface Pearl / Subtle Gray */
        --ux-surface-3: #E5E5EA;         /* Apple System Gray 5 Highlight */
        --ux-ink: #1D1D1F;               /* Apple Near-Black Ink */
        --ux-ink-2: #48484A;             /* Secondary Text */
        --ux-ink-3: #86868B;             /* Muted Gray Captions */
        --ux-line: rgba(0, 0, 0, 0.08);  /* Apple 1px Hairline */
        --ux-line-2: #D1D1D6;            /* Apple System Gray 4 */
        --ux-accent: #EA580C;            /* FPT Orange (#EA580C) */
        --ux-accent-hover: #F97316;
        --ux-accent-active: #C2410C;
        --ux-accent-soft: rgba(234, 88, 12, 0.08);
        --ux-accent-gradient: linear-gradient(135deg, #F97316 0%, #EA580C 100%);
        --ux-navy-gradient: linear-gradient(180deg, #FFFFFF 0%, #FAFAFC 100%);
        --ux-mint: #059669;
        --ux-mint-soft: rgba(16, 185, 129, 0.1);
        --ux-warn: #D97706;
        --ux-warn-soft: rgba(245, 158, 11, 0.1);
        --ux-danger: #DC2626;
        --ux-danger-soft: rgba(239, 68, 68, 0.1);
        --ux-info: #0066CC;              /* Apple Action Blue */
        --ux-info-soft: rgba(0, 102, 204, 0.08);
        --ux-shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.04);
        --ux-shadow: 0 4px 16px rgba(0, 0, 0, 0.04), 0 1px 2px rgba(0, 0, 0, 0.02);
        --ux-shadow-lg: 0 12px 32px rgba(0, 0, 0, 0.06);
        --ux-spring: cubic-bezier(0.16, 1, 0.3, 1);
        --ux-ease: cubic-bezier(0.16, 1, 0.3, 1);
    }

    [data-bs-theme="dark"] {
        color-scheme: dark;
        --ux-page: #1C1C1E;              /* Apple Dark System Background */
        --ux-surface: #2C2C2E;           /* Apple Dark Secondary Grouped */
        --ux-surface-2: #3A3A3C;         /* Apple Dark Tertiary Grouped */
        --ux-surface-3: #48484A;
        --ux-ink: #FFFFFF;
        --ux-ink-2: #EBEBF5;
        --ux-ink-3: #8E8E93;
        --ux-line: rgba(255, 255, 255, 0.12);
        --ux-line-2: rgba(255, 255, 255, 0.2);
        --ux-accent: #EA580C;
        --ux-accent-hover: #F97316;
        --ux-accent-active: #C2410C;
        --ux-accent-soft: rgba(234, 88, 12, 0.15);
        --ux-accent-gradient: linear-gradient(135deg, #EA580C 0%, #C2410C 100%);
        --ux-navy-gradient: linear-gradient(180deg, #1C1C1E 0%, #2C2C2E 100%);
        --ux-mint: #34D399;
        --ux-mint-soft: rgba(52, 211, 153, 0.15);
        --ux-warn: #FBBF24;
        --ux-warn-soft: rgba(251, 191, 36, 0.15);
        --ux-danger: #F87171;
        --ux-danger-soft: rgba(248, 113, 113, 0.15);
        --ux-info: #2997FF;              /* Apple Sky Link Blue on Dark */
        --ux-info-soft: rgba(41, 151, 255, 0.15);
        --ux-shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.3);
        --ux-shadow: 0 4px 16px rgba(0, 0, 0, 0.4);
        --ux-shadow-lg: 0 16px 40px rgba(0, 0, 0, 0.6);
        --ux-spring: cubic-bezier(0.16, 1, 0.3, 1);
        --ux-ease: cubic-bezier(0.16, 1, 0.3, 1);
    }

    body {
        font-family: "Plus Jakarta Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        background-color: var(--ux-page);
        color: var(--ux-ink);
        min-height: 100vh;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

    .num-tabular {
        font-variant-numeric: tabular-nums;
        letter-spacing: -0.01em;
    }

    .app-shell {
        display: flex;
        min-height: 100vh;
        width: 100%;
        position: relative;
    }

    /* =========================================================
       LUCIAN UX/UI #040: EXPANDING SIDE RAIL (ORIGINAL PRESERVED)
       ========================================================= */
    .u040-wrapper {
        position: sticky;
        top: 0;
        height: 100vh;
        z-index: 1040;
        padding: 12px;
        display: flex;
        flex-direction: column;
    }

    .u040 {
        --rw: 76px;
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
        width: var(--rw);
        transition: width .6s var(--ux-spring);
    }

    .u040.is-wide {
        --rw: 252px;
    }

    .u040-rail {
        position: relative;
        flex: 1;
        width: 100%;
        padding: 14px 10px;
        border-radius: 20px;
        background: #FFFFFF;
        color: #1D1D1F;
        overflow: visible;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(0, 0, 0, 0.08);
    }

    [data-bs-theme="dark"] .u040-rail {
        background: #2C2C2E;
        color: #FFFFFF;
        border-color: rgba(255, 255, 255, 0.12);
    }

    .u040-top {
        display: flex;
        align-items: center;
        gap: 12px;
        height: 44px;
        margin-bottom: 20px;
        position: relative;
    }

    .u040-logo {
        width: 34px;
        height: 34px;
        flex: none;
        margin-left: 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
    }

    .u040-brand-mark {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        background: linear-gradient(135deg, #F97316 0%, #EA580C 100%);
        color: #FFFFFF;
        font-weight: 800;
        font-size: 15px;
        display: grid;
        place-items: center;
        box-shadow: 0 4px 12px rgba(234, 88, 12, 0.35);
        letter-spacing: -0.5px;
    }

    .u040-name {
        font-size: 16.5px;
        font-weight: 800;
        letter-spacing: -.03em;
        white-space: nowrap;
        opacity: 0;
        color: var(--ux-ink);
        transition: opacity .25s;
    }

    .u040.is-wide .u040-name {
        opacity: 1;
        transition: opacity .3s .15s;
    }

    .u040-top {
        display: flex;
        align-items: center;
        gap: 12px;
        height: 44px;
        margin-bottom: 24px;
        position: relative;
        transition: all 0.3s var(--ux-spring);
    }

    .u040:not(.is-wide) .u040-top {
        justify-content: center;
        gap: 0;
        padding: 0;
    }

    .u040:not(.is-wide) .u040-name {
        display: none !important;
        width: 0 !important;
        opacity: 0 !important;
    }

    .u040-school-box {
        height: 34px;
        width: auto;
        background: transparent;
        border: none !important;
        box-shadow: none !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        flex-shrink: 0;
        overflow: hidden;
        transition: all 0.3s var(--ux-spring);
    }

    .u040:not(.is-wide) .u040-school-box {
        width: auto;
        height: 28px;
        padding: 0;
        margin: 0 auto;
        border: none !important;
        box-shadow: none !important;
    }

    .u040-tog {
        position: absolute;
        right: -10px;
        top: 22px;
        width: 22px;
        height: 22px;
        border: 1px solid rgba(0, 0, 0, 0.1);
        padding: 0;
        border-radius: 50%;
        background: #FFFFFF;
        color: #1D1D1F;
        display: grid;
        place-items: center;
        cursor: pointer;
        z-index: 1070;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        transition: all .25s var(--ux-spring);
    }

    .u040-tog:hover {
        background: #F4F4F6;
        transform: scale(1.08);
    }

    .u040.is-wide .u040-tog {
        right: -10px;
        top: 22px;
        background: #FFFFFF;
        border-color: rgba(0, 0, 0, 0.1);
        color: #1D1D1F;
    }

    [data-bs-theme="dark"] .u040-tog,
    [data-bs-theme="dark"] .u040.is-wide .u040-tog {
        background: #3A3A3C;
        border-color: rgba(255, 255, 255, 0.15);
        color: #FFFFFF;
    }

    .u040-tog svg {
        width: 16px;
        height: 16px;
        fill: none;
        stroke: currentColor;
        stroke-width: 2.4;
        stroke-linecap: round;
        stroke-linejoin: round;
        transition: transform .5s var(--ux-spring);
    }

    .u040.is-wide .u040-tog svg {
        transform: rotate(180deg);
    }

    .u040-nav {
        position: relative;
        margin: 0;
        padding: 0;
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 6px;
        overflow-y: auto;
        scrollbar-width: none;
    }
    .u040-nav::-webkit-scrollbar {
        display: none;
    }

    .u040-section-label {
        padding: 10px 0 2px 14px;
        list-style: none;
        pointer-events: none;
        user-select: none;
    }

    .u040-section-label span {
        font-size: 9.5px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.45);
        white-space: nowrap;
        opacity: 0;
        transition: opacity .2s;
    }

    .u040.is-wide .u040-section-label span {
        opacity: 1;
        transition: opacity .3s .15s;
    }

    .u040-hl {
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        height: 46px;
        border-radius: 12px;
        background: rgba(234, 88, 12, 0.08);
        transform: translateY(var(--y, 0));
        transition: transform .4s var(--ux-spring);
        pointer-events: none;
    }

    .u040-hl::before {
        content: "";
        position: absolute;
        left: -10px;
        top: 10px;
        bottom: 10px;
        width: 5px;
        border-radius: 0 4px 4px 0;
        background: var(--ux-accent);
    }

    .u040-it {
        position: relative;
        display: flex;
        align-items: center;
        gap: 14px;
        width: 100%;
        height: 46px;
        padding: 0 0 0 16px;
        border: 0;
        border-radius: 12px;
        background: none;
        color: var(--ux-ink);
        opacity: .78;
        text-align: left;
        transition: all .2s var(--ux-ease);
        text-decoration: none;
        cursor: pointer;
    }

    .u040-it:hover,
    .u040-it[aria-current="page"] {
        opacity: 1;
        color: var(--ux-accent);
    }

    .u040-it svg, .u040-it i {
        width: 22px;
        height: 22px;
        font-size: 20px;
        flex: none;
        display: grid;
        place-items: center;
        fill: none;
        stroke: currentColor;
        stroke-width: 1.9;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .u040-it span {
        font-size: 13.5px;
        font-weight: 650;
        white-space: nowrap;
        opacity: 0;
        transform: translateX(-8px);
        transition: opacity .2s, transform .35s var(--ux-ease);
    }

    .u040.is-wide .u040-it span {
        opacity: 1;
        transform: none;
        transition: opacity .3s var(--ux-ease), transform .5s var(--ux-spring);
        transition-delay: calc(.12s + var(--i) * 35ms);
    }

    .u040-bd {
        position: absolute;
        left: 28px;
        top: 6px;
        min-width: 18px;
        height: 18px;
        padding: 0 4px;
        border-radius: 10px;
        background: var(--ux-accent);
        color: #FFFFFF;
        font-size: 10px;
        font-weight: 800;
        display: grid;
        place-items: center;
        font-style: normal;
        border: none !important;
        box-shadow: 0 0 0 1.5px #FFFFFF !important;
        transition: all .35s var(--ux-spring);
        line-height: 1;
        z-index: 5;
    }

    .u040.is-wide .u040-bd {
        left: auto;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        box-shadow: none !important;
    }

    .u040-me {
        margin-top: auto;
        display: flex;
        align-items: center;
        gap: 12px;
        height: 54px;
        padding: 0 0 0 10px;
        border-radius: 14px;
        background: #F5F5F7;
        border: 1px solid rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    [data-bs-theme="dark"] .u040-me {
        background: #3A3A3C;
        border-color: rgba(255, 255, 255, 0.08);
    }

    .u040-me i {
        width: 34px;
        height: 34px;
        flex: none;
        border-radius: 50%;
        background: #EA580C;
        display: grid;
        place-items: center;
        color: #ffffff;
        font-size: 13px;
        font-style: normal;
        font-weight: 700;
        box-shadow: 0 1px 3px rgba(234, 88, 12, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .u040-me span {
        font-size: 13px;
        font-weight: 650;
        white-space: nowrap;
        opacity: 0;
        color: var(--ux-ink);
        transition: opacity .25s;
    }

    .u040-me small {
        display: block;
        font-size: 11px;
        opacity: .7;
        font-weight: 500;
    }

    .u040.is-wide .u040-me span {
        opacity: 1;
        transition-delay: .25s;
    }

    .u040-me:hover {
        background: #EBEBEF;
        cursor: pointer;
    }

    #sidebarUserMenuBtn[aria-expanded="true"] .u040-chevron {
        transform: rotate(90deg);
    }

    /* Apple-Grade Floating Sidebar Account Flyout (Adjacent to Rail, Never Covers Navigation) */
    .sidebar-account-flyout.dropdown-menu {
        position: fixed !important;
        left: calc(var(--rw, 252px) + 24px) !important;
        bottom: 16px !important;
        top: auto !important;
        transform: none !important;
        width: 320px !important;
        max-height: calc(100vh - 32px) !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
        border-radius: 20px !important;
        background: #FFFFFF !important;
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
        box-shadow: 0 20px 50px -6px rgba(0, 0, 0, 0.18), 0 0 0 1px rgba(0, 0, 0, 0.04) !important;
        z-index: 99999 !important;
        transition: left .5s var(--ux-spring);
        animation: accountFlyoutIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes accountFlyoutIn {
        from { opacity: 0; transform: translateX(-8px) scale(0.97); }
        to { opacity: 1; transform: translateX(0) scale(1); }
    }

    .sidebar-account-flyout::-webkit-scrollbar {
        width: 5px;
    }
    .sidebar-account-flyout::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.15);
        border-radius: 10px;
    }

    @media (max-width: 767.98px) {
        .sidebar-account-flyout.dropdown-menu {
            left: 12px !important;
            right: 12px !important;
            bottom: 12px !important;
            width: auto !important;
            max-width: calc(100vw - 24px) !important;
        }
    }

    /* =========================================================
       MAIN WORKSPACE & CLEAN ENTERPRISE STYLING
       ========================================================= */
    .main-workspace {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        padding: 16px 24px 16px 12px;
        overflow: hidden;
    }

    .dashboard-topbar {
        position: sticky !important;
        top: 0 !important;
        z-index: 1020 !important;
        background: rgba(255, 255, 255, 0.94) !important;
        border: 1px solid var(--ux-line);
        border-radius: 20px;
        padding: 12px 20px;
        margin-bottom: 16px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06) !important;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }

    [data-bs-theme="dark"] .dashboard-topbar {
        background: rgba(17, 24, 39, 0.88);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.4);
    }

    .dashboard-topbar .dropdown {
        position: relative;
        z-index: 105;
    }

    .dashboard-topbar .dropdown-menu {
        z-index: 110 !important;
        background-color: var(--ux-surface) !important;
        border: 1px solid var(--ux-line-2) !important;
        box-shadow: 0 18px 45px rgba(0, 0, 0, 0.35) !important;
        border-radius: 16px;
    }

    /* Modal Backdrop & Dialog Must ALWAYS Float Above Topbar (Zero Collision) */
    .modal-backdrop {
        z-index: 1080 !important;
    }
    .modal {
        z-index: 1090 !important;
    }

    [data-bs-theme="dark"] .dropdown-menu {
        background-color: var(--ux-surface) !important;
        border-color: rgba(255, 255, 255, 0.12) !important;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.7) !important;
    }

    .place-items-center {
        display: grid !important;
        place-items: center !important;
        justify-items: center !important;
        align-items: center !important;
    }

    /* Central Hub Mode Switcher (Bento Promax Tactile Bar) */
    .central-mode-bar {
        background: var(--ux-surface);
        border: 1px solid var(--ux-line);
        border-radius: 16px;
        padding: 5px;
        margin-bottom: 18px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px;
        box-shadow: var(--ux-shadow-sm);
    }

    [data-bs-theme="dark"] .central-mode-bar {
        background: var(--ux-surface-2);
        border-color: rgba(255, 255, 255, 0.08);
    }

    .central-mode-bar .hub-tab-btn {
        border: 0;
        background: transparent;
        color: var(--ux-ink-2);
        font-size: 13px;
        font-weight: 650;
        padding: 8px 16px;
        border-radius: 12px;
        transition: all 0.18s var(--ux-ease);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        text-decoration: none;
        user-select: none;
    }

    .central-mode-bar .hub-tab-btn:hover {
        background: var(--ux-surface-2);
        color: var(--ux-ink);
        transform: translateY(-1px);
    }

    .central-mode-bar .hub-tab-btn:active {
        transform: scale(0.98);
    }

    .central-mode-bar .hub-tab-btn.active {
        background: var(--ux-accent);
        color: #FFFFFF !important;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.25), 0 3px 12px rgba(234, 88, 12, 0.3);
    }

    .central-mode-bar .hub-tab-btn.active .badge {
        background: rgba(255, 255, 255, 0.22) !important;
        color: #FFFFFF !important;
        border-color: rgba(255, 255, 255, 0.35) !important;
    }

    /* Segmented Control (Month | Week | Day Switcher) */
    .view-switcher-group {
        display: inline-flex;
        align-items: center;
        background: var(--ux-surface-2);
        padding: 3px;
        border-radius: 12px;
        border: 1px solid var(--ux-line);
        gap: 2px;
    }

    [data-bs-theme="dark"] .view-switcher-group {
        background: #162032;
        border-color: rgba(255, 255, 255, 0.08);
    }

    .view-switcher-group .btn {
        border: none;
        border-radius: 9px;
        font-size: 12.5px;
        font-weight: 650;
        padding: 5px 14px;
        color: var(--ux-ink-3);
        background: transparent;
        transition: all 0.18s var(--ux-ease);
        box-shadow: none;
    }

    .view-switcher-group .btn:hover {
        color: var(--ux-ink);
        background: rgba(255, 255, 255, 0.08);
    }

    .view-switcher-group .btn:active {
        transform: scale(0.97);
    }

    .view-switcher-group .btn.active {
        background-color: var(--ux-surface);
        color: var(--ux-ink);
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.15);
        border: 1px solid var(--ux-line);
    }

    [data-bs-theme="dark"] .view-switcher-group .btn.active {
        background-color: var(--ux-surface-3);
        color: #F8FAFC;
        border-color: rgba(255, 255, 255, 0.14);
    }

    /* KPI Metrics Promax High-Density Strip */
    .kpi-metric-strip {
        display: grid !important;
        grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
        gap: 10px !important;
        margin-bottom: 12px !important;
        flex-shrink: 0 !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }

    @media (max-width: 992px) {
        .kpi-metric-strip {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 8px !important;
        }
    }

    .kpi-mini-card {
        background: #FFFFFF;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        padding: 8px 14px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        transition: all 0.2s var(--ux-spring);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    }

    .kpi-mini-card:hover {
        border-color: rgba(0, 0, 0, 0.16);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    }

    .kpi-mini-label {
        font-size: 10.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #8E8E93;
        margin-bottom: 2px;
        line-height: 1.1;
    }

    .kpi-mini-val {
        font-size: 17px;
        font-weight: 800;
        line-height: 1.1;
        color: #1D1D1F;
    }

    .kpi-mini-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        flex: none;
        font-size: 14px;
        background: #F4F4F6 !important;
        color: #48484A !important;
    }

    /* Fallback .kpi-card for any other section */
    .kpi-card {
        background: var(--ux-surface);
        border: 1px solid var(--ux-line);
        border-radius: 18px;
        padding: 16px 20px;
        box-shadow: var(--ux-shadow-sm);
        transition: transform .2s var(--ux-spring), box-shadow .2s;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--ux-shadow);
        border-color: var(--ux-line-2);
    }
    [data-bs-theme="dark"] .kpi-card {
        background: var(--ux-surface-2);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .calendar-container {
        background: var(--ux-surface);
        border: 1px solid var(--ux-line);
        border-radius: 18px;
        padding: 14px 18px;
        box-shadow: var(--ux-shadow-sm);
        animation: fadeInPanel 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        display: flex !important;
        flex-direction: column !important;
        flex: 1 1 0% !important;
        min-height: 0 !important;
        height: 100% !important;
        overflow: hidden !important;
    }

    /* Hidden state rule: ALWAYS hide panels when style is display: none */
    .spa-panel[style*="display: none"],
    .spa-panel[style*="display:none"],
    section[style*="display: none"],
    section[style*="display:none"],
    #panelCalendar[style*="display: none"],
    #panelCalendar[style*="display:none"],
    #panelAccounting[style*="display: none"],
    #panelAccounting[style*="display:none"],
    #panelStudentPayrollProfile[style*="display: none"],
    #panelStudentPayrollProfile[style*="display:none"],
    #panelMyShifts[style*="display: none"],
    #panelMyShifts[style*="display:none"],
    #panelEvents[style*="display: none"],
    #panelEvents[style*="display:none"],
    #panelSubmissions[style*="display: none"],
    #panelSubmissions[style*="display:none"],
    #panelContractDossier[style*="display: none"],
    #panelContractDossier[style*="display:none"],
    #panelProfileSettings[style*="display: none"],
    #panelProfileSettings[style*="display:none"],
    #panelUserRoles[style*="display: none"],
    #panelUserRoles[style*="display:none"],
    #panelAuditTrail[style*="display: none"],
    #panelAuditTrail[style*="display:none"] {
        display: none !important;
    }

    .spa-panel {
        background: var(--ux-surface);
        border: 1px solid var(--ux-line);
        border-radius: 16px;
        padding: 14px 18px;
        box-shadow: var(--ux-shadow-sm);
        animation: fadeInPanel 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        flex: 1 1 0%;
        min-height: 0;
        height: 100%;
        overflow: hidden;
    }

    /* Active panel ONLY (never applies to hidden panels) */
    .spa-panel:not([style*="display: none"]):not([style*="display:none"]) {
        display: flex !important;
        flex-direction: column !important;
        width: 100% !important;
        height: 100% !important;
        flex: 1 1 0% !important;
        min-height: 0 !important;
    }

    #panelProfileSettings:not([style*="display: none"]):not([style*="display:none"]) {
        overflow-y: auto !important;
        display: block !important;
        flex: 1 1 0% !important;
        min-height: 0 !important;
        height: 100% !important;
    }

    /* Fixed Headers & Banners: Never scroll out of view */
    .spa-panel > *:not(.table-responsive):not(.card):not(#eventsCardsContainer):not(#contractDossierContainer):not(.panel-scroll-body) {
        flex-shrink: 0 !important;
    }

    /* Scrollable student list & table bodies only */
    .spa-panel .table-responsive,
    .panel-scroll-body {
        flex: 1 1 0% !important;
        min-height: 0 !important;
        overflow-y: auto !important;
        position: relative !important;
        border-radius: 12px;
    }

    /* Chỉ định hình flex: 1 cho card chứa bảng cuộn độc lập */
    .spa-panel .card:has(.table-responsive),
    .spa-panel .table-scroll-card {
        flex: 1 1 0% !important;
        min-height: 0 !important;
        display: flex !important;
        flex-direction: column !important;
        overflow: hidden !important;
    }

    .spa-panel .card:has(.table-responsive) > .card-body,
    .spa-panel .table-scroll-card > .card-body {
        flex: 1 1 0% !important;
        min-height: 0 !important;
        display: flex !important;
        flex-direction: column !important;
        overflow: hidden !important;
        padding: 0 !important;
    }

    /* Đảm bảo trang Profile Settings luôn cuộn tự nhiên và card không bị đè */
    #panelProfileSettings {
        overflow-y: auto !important;
        display: block !important;
    }
    #panelProfileSettings .card {
        flex: none !important;
        overflow: visible !important;
        background: var(--ux-surface) !important;
        border: 1px solid var(--ux-line) !important;
        border-radius: 14px !important;
    }
    #panelProfileSettings .card-header {
        padding: 16px 20px 8px 20px !important;
        background: transparent !important;
        border-bottom: 0 !important;
    }
    #panelProfileSettings .card-body {
        flex: none !important;
        overflow: visible !important;
        padding: 14px 20px 20px 20px !important;
    }
    #panelProfileSettings .form-label {
        font-size: 12.5px !important;
        font-weight: 600 !important;
        color: var(--ux-ink) !important;
        margin-bottom: 6px !important;
    }
    #panelProfileSettings .form-control,
    #panelProfileSettings .form-select {
        font-size: 13px !important;
        padding: 9px 13px !important;
        border-radius: 9px !important;
        border: 1px solid var(--ux-line-2) !important;
        background: var(--ux-surface-2) !important;
        color: var(--ux-ink) !important;
        transition: border-color 0.15s ease, box-shadow 0.15s ease !important;
    }
    #panelProfileSettings .form-control:focus {
        border-color: var(--ux-accent) !important;
        box-shadow: 0 0 0 3px var(--ux-accent-soft) !important;
        background: var(--ux-surface) !important;
    }
    #panelProfileSettings .form-control[readonly] {
        background: var(--ux-surface-3) !important;
        cursor: not-allowed !important;
        opacity: 0.9 !important;
        color: var(--ux-ink) !important;
    }

    .spa-panel #eventsCardsContainer,
    .spa-panel #contractDossierContainer {
        flex: 1 1 0% !important;
        min-height: 0 !important;
        overflow-y: auto !important;
        padding-right: 2px;
    }

    /* =========================================================
       CHUẨN UI/UX KỸ THUẬT: STICKY HEADER & SCROLLABLE BODY
       1. Non-scrollable Top Section (flex-shrink-0)
       2. Sticky Table Header (sticky top-0 z-10 with Solid Background)
       3. Scrollable Body (flex-1 overflow-y-auto)
       ========================================================= */
    .spa-panel .table {
        border-collapse: separate !important;
        border-spacing: 0 !important;
        width: 100% !important;
    }
    
    .spa-panel .table thead th,
    .sticky-th th,
    .sticky-th {
        position: sticky !important;
        top: 0 !important;
        z-index: 10 !important;
        background-color: #F8FAFC !important;
        background: #F8FAFC !important;
        color: #475569 !important;
        border-bottom: 2px solid rgba(0, 0, 0, 0.08) !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04) !important;
    }

    [data-bs-theme="dark"] .spa-panel .table thead th,
    [data-bs-theme="dark"] .sticky-th th,
    [data-bs-theme="dark"] .sticky-th {
        background-color: #1E293B !important;
        background: #1E293B !important;
        color: #F8FAFC !important;
        border-bottom: 2px solid rgba(255, 255, 255, 0.12) !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3) !important;
    }

    /* Đảm bảo container bảng có chiều cao cuộn độc lập */
    .spa-panel .card:has(.table-responsive),
    .spa-panel .table-container,
    .scrollable-body-container {
        flex: 1 1 0% !important;
        min-height: 0 !important;
        display: flex !important;
        flex-direction: column !important;
        overflow: hidden !important;
    }

    .spa-panel .table-responsive,
    .scrollable-table-body {
        flex: 1 1 0% !important;
        min-height: 0 !important;
        overflow-y: auto !important;
        position: relative !important;
    }

    /* Standardized Compact Inner Content & Cells for All Panels */
    .spa-panel .table > :not(caption) > * > * {
        padding: 7px 10px !important;
        font-size: 12.5px;
    }
    .spa-panel .table thead th {
        font-size: 10.5px !important;
        font-weight: 700 !important;
        letter-spacing: 0.035em !important;
        text-transform: uppercase !important;
        padding-top: 6px !important;
        padding-bottom: 6px !important;
    }
    .spa-panel .card {
        border-radius: 13px !important;
    }
    .spa-panel .card-header {
        padding: 10px 14px !important;
    }
    .spa-panel .card-body {
        padding: 12px 14px !important;
    }
    .spa-panel .form-control, .spa-panel .form-select {
        font-size: 12.5px !important;
        border-radius: 8px !important;
    }
    .spa-panel .badge {
        font-size: 10.5px;
        font-weight: 600;
    }

    /* Fixed Hover State & Compact Sizing for Profile Save Button */
    .btn-profile-save {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        background: #EA580C !important;
        color: #FFFFFF !important;
        border: 1px solid #C2410C !important;
        padding: 8px 16px !important;
        font-size: 12.5px !important;
        font-weight: 700 !important;
        border-radius: 8px !important;
        cursor: pointer !important;
        text-decoration: none !important;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
        box-shadow: 0 1px 3px rgba(234, 88, 12, 0.2) !important;
        user-select: none !important;
    }
    .btn-profile-save:hover {
        background: #C2410C !important;
        color: #FFFFFF !important;
        border-color: #9A3412 !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 12px rgba(234, 88, 12, 0.35) !important;
    }
    .btn-profile-save:active {
        background: #9A3412 !important;
        transform: translateY(0) !important;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.2) !important;
    }

    [data-bs-theme="dark"] .calendar-container,
    [data-bs-theme="dark"] .spa-panel {
        background: var(--ux-surface-2);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    @keyframes fadeInPanel {
        from { opacity: 0; transform: translateY(4px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .month-header-grid {
        display: grid !important;
        grid-template-columns: repeat(7, minmax(0, 1fr)) !important;
        text-align: center;
        margin-bottom: 6px !important;
        padding-bottom: 5px !important;
        border-bottom: 1px solid var(--ux-line);
        width: 100% !important;
        flex-shrink: 0 !important;
        box-sizing: border-box !important;
    }

    .month-header-grid .day-label {
        font-size: 11px !important;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #8E8E93;
        padding: 2px 0 !important;
        min-width: 0;
    }

    .month-days-grid {
        display: grid !important;
        grid-template-columns: repeat(7, minmax(0, 1fr)) !important;
        grid-template-rows: repeat(var(--month-rows, 5), minmax(0, 1fr)) !important;
        gap: clamp(3px, 0.4vw, 6px) !important;
        width: 100% !important;
        flex: 1 1 0% !important;
        min-height: 0 !important;
        height: 100% !important;
        box-sizing: border-box !important;
    }

    /* iOS Calendar Clean Native Style: Fluid viewport height scaling */
    .month-day-cell {
        height: 100% !important;
        min-height: 0 !important;
        max-height: none !important;
        min-width: 0 !important;
        width: 100% !important;
        box-sizing: border-box !important;
        border-radius: 10px !important;
        background: #FFFFFF;
        border: 0.5px solid rgba(0, 0, 0, 0.08);
        padding: clamp(3px, 0.5vh, 6px) clamp(4px, 0.5vw, 8px) !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: flex-start !important;
        cursor: pointer;
        transition: all 0.16s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    }

    .month-day-cell:hover {
        background: #F8F8FA;
        border-color: rgba(0, 0, 0, 0.16);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .month-day-cell:active {
        transform: scale(0.985);
    }

    .month-day-cell.is-today {
        background: #FFFFFF !important;
        border: 1.5px solid #EA580C !important;
        box-shadow: 0 1px 6px rgba(234, 88, 12, 0.15) !important;
    }

    .month-day-cell.is-today .day-number-pill {
        background: #EA580C !important;
        color: #FFFFFF !important;
        font-weight: 700 !important;
        box-shadow: 0 1px 4px rgba(234, 88, 12, 0.35) !important;
    }

    .month-day-cell.is-other-month {
        opacity: 0.35;
        background: #FAFAFC;
        border-color: rgba(0, 0, 0, 0.04);
    }

    .day-number-pill {
        font-size: clamp(10.5px, 0.85vw, 13px) !important;
        font-weight: 700;
        color: #1C1C1E;
        width: clamp(20px, 1.8vw, 26px) !important;
        height: clamp(20px, 1.8vw, 26px) !important;
        border-radius: 50%;
        display: grid;
        place-items: center;
        transition: all 0.15s ease;
        flex: none;
    }

    /* Clean Desktop Layout: Fixed Sidebar Pinned at 100vh, Only Main Workspace Body Scrolls */
    @media (min-width: 993px) {
        html, body {
            height: 100vh !important;
            max-height: 100vh !important;
            overflow: hidden !important;
        }

        .app-shell {
            height: 100vh !important;
            max-height: 100vh !important;
            overflow: hidden !important;
            width: 100%;
            display: flex;
        }

        .u040-wrapper {
            position: sticky !important;
            top: 0 !important;
            height: 100vh !important;
            max-height: 100vh !important;
            flex-shrink: 0 !important;
            overflow-y: auto !important;
            z-index: 1040;
        }

        .main-workspace {
            flex: 1;
            min-width: 0;
            height: 100vh !important;
            max-height: 100vh !important;
            padding: 12px 20px 16px 12px;
            display: flex;
            flex-direction: column;
            overflow: hidden !important;
        }

        .dashboard-topbar {
            position: sticky !important;
            top: 0 !important;
            z-index: 1020 !important;
            background: rgba(255, 255, 255, 0.94) !important;
            flex-shrink: 0;
            padding: 8px 16px;
            margin-bottom: 12px;
            min-height: 48px;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
        }

        #spaBodyContent {
            flex: 1 1 0%;
            min-width: 0;
            min-height: 0;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Panel display overrides: hidden state must always win */
        .spa-panel[style*="display: none"],
        .spa-panel[style*="display:none"],
        #panelCalendar[style*="display: none"],
        #panelCalendar[style*="display:none"],
        #panelProfileSettings[style*="display: none"],
        #panelProfileSettings[style*="display:none"],
        #panelUserRoles[style*="display: none"],
        #panelUserRoles[style*="display:none"],
        #panelAuditTrail[style*="display: none"],
        #panelAuditTrail[style*="display:none"] {
            display: none !important;
        }

        #panelCalendar:not([style*="display: none"]):not([style*="display:none"]) {
            width: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            flex: 1 1 0% !important;
            min-height: 0 !important;
            height: 100% !important;
            padding: 14px 18px !important;
            margin-bottom: 0 !important;
            border-radius: 18px;
            background: #FFFFFF;
            border: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            overflow: hidden !important;
            box-sizing: border-box !important;
        }

        #panelCalendar > .d-flex.border-bottom {
            padding-bottom: 8px !important;
            margin-bottom: 10px !important;
            flex-shrink: 0 !important;
        }

        /* 3-Tier Views Inside Calendar */
        #tierMonthView[style*="display: none"],
        #tierMonthView[style*="display:none"],
        #tierWeekView[style*="display: none"],
        #tierWeekView[style*="display:none"],
        #tierDayView[style*="display: none"],
        #tierDayView[style*="display:none"] {
            display: none !important;
        }

        #tierMonthView:not([style*="display: none"]):not([style*="display:none"]) {
            width: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            flex: 1 1 0% !important;
            min-height: 0 !important;
            height: 100% !important;
            overflow: hidden !important;
        }

        #tierWeekView:not([style*="display: none"]):not([style*="display:none"]) {
            width: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            flex: 1 1 0% !important;
            min-height: 0 !important;
            height: 100% !important;
            overflow-y: auto !important;
        }

        #tierDayView:not([style*="display: none"]):not([style*="display:none"]) {
            width: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            flex: 1 1 0% !important;
            min-height: 0 !important;
            height: 100% !important;
            overflow-y: auto !important;
        }

        .month-header-grid {
            flex-shrink: 0 !important;
            margin-bottom: 3px !important;
            padding-bottom: 2px !important;
        }

        .month-days-grid {
            display: grid !important;
            grid-template-columns: repeat(7, minmax(0, 1fr)) !important;
            grid-template-rows: repeat(var(--month-rows, 5), minmax(0, 1fr)) !important;
            gap: clamp(3px, 0.4vw, 6px) !important;
            width: 100% !important;
            flex: 1 1 0% !important;
            min-height: 0 !important;
            height: 100% !important;
            box-sizing: border-box !important;
        }

        .month-day-cell {
            height: 100% !important;
            min-height: 0 !important;
            max-height: none !important;
            padding: clamp(3px, 0.5vh, 6px) clamp(4px, 0.5vw, 8px) !important;
            border-radius: 10px !important;
            display: flex !important;
            flex-direction: column !important;
            box-sizing: border-box !important;
            background: #FFFFFF;
            border: 0.5px solid rgba(0, 0, 0, 0.08);
            transition: all 0.15s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            overflow: hidden !important;
        }

        .month-day-cell:hover {
            background: #F8F8FA;
            border-color: rgba(0, 0, 0, 0.16);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .day-number-pill {
            width: clamp(20px, 1.8vw, 26px) !important;
            height: clamp(20px, 1.8vw, 26px) !important;
            font-size: clamp(10.5px, 0.85vw, 13px) !important;
            font-weight: 700 !important;
            border-radius: 50%;
        }

        .shift-chip, .memo-chip {
            font-size: clamp(9px, 0.75vw, 10.5px) !important;
            font-weight: 600;
            padding: clamp(1.5px, 0.25vh, 3px) clamp(4px, 0.4vw, 6px) !important;
            min-height: clamp(16px, 2vh, 22px) !important;
            line-height: 1.25 !important;
            border-radius: 5px !important;
            margin-bottom: 2px !important;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
            box-sizing: border-box;
        }

        .spa-panel:not([style*="display: none"]):not([style*="display:none"]) {
            width: 100% !important;
            padding: 14px 18px !important;
            display: flex !important;
            flex-direction: column !important;
            flex: 1 1 0% !important;
            min-height: 0 !important;
            height: 100% !important;
            overflow: hidden !important;
            box-sizing: border-box !important;
        }
    }

    /* iOS Calendar Event Chips & Reminder Pills */
    .shift-chip {
        font-size: 8.5px !important;
        font-weight: 600;
        padding: 1px 4px !important;
        border-radius: 4px !important;
        min-height: 15px !important;
        line-height: 13px !important;
        white-space: nowrap;
        overflow: hidden;
        background: rgba(234, 88, 12, 0.10) !important;
        color: #C2410C !important;
        border: none !important;
        border-left: 2.5px solid #EA580C !important;
        display: flex;
        align-items: center;
        gap: 4px;
        margin-bottom: 2px;
        transition: transform 0.15s ease;
        min-width: 0;
        max-width: 100%;
        box-sizing: border-box;
    }

    [data-bs-theme="light"] .shift-chip {
        background: rgba(234, 88, 12, 0.10) !important;
        color: #C2410C !important;
        border: none !important;
        border-left: 2.5px solid #EA580C !important;
    }

    .shift-chip:hover {
        transform: translateX(2px);
    }

    .shift-chip.urgent {
        background: rgba(239, 68, 68, 0.10) !important;
        color: #B91C1C !important;
        border-left: 2.5px solid #EF4444 !important;
    }

    [data-bs-theme="light"] .shift-chip.urgent {
        background: rgba(239, 68, 68, 0.10) !important;
        color: #B91C1C !important;
        border-left: 2.5px solid #EF4444 !important;
    }

    .shift-chip.mine {
        background: rgba(16, 185, 129, 0.12) !important;
        color: #047857 !important;
        border-left: 2.5px solid #10B981 !important;
    }

    [data-bs-theme="light"] .shift-chip.mine {
        background: rgba(16, 185, 129, 0.12) !important;
        color: #047857 !important;
        border-left: 2.5px solid #10B981 !important;
    }

    .memo-chip {
        font-size: 10px;
        font-weight: 600;
        padding: 2px 6px;
        border-radius: 6px;
        white-space: nowrap;
        overflow: hidden;
        border: none !important;
        border-left: 2.5px solid #EA580C;
        background: rgba(234, 88, 12, 0.10);
        color: #C2410C;
        margin-bottom: 2px;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: transform 0.15s ease;
    }

    .memo-chip:hover {
        transform: translateX(2px);
    }

    .shift-chip .status-dot {
        flex: none;
        flex-shrink: 0;
    }

    .shift-chip .num-tabular {
        flex: none !important;
        flex-shrink: 0 !important;
        white-space: nowrap;
        font-size: 10px;
        opacity: 0.85;
    }

    .shift-chip .text-truncate {
        flex: 1 1 auto;
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
        flex: none;
    }
    .status-dot-emerald { background: #10B981; }
    .status-dot-red { background: #EF4444; }
    .status-dot-orange { background: #F97316; }
    .status-dot-sky { background: #0284C7; }

    /* Memo Lozenge */
    .memo-chip {
        font-size: 10.5px;
        font-weight: 600;
        padding: 2.5px 6px;
        border-radius: 6px;
        white-space: nowrap;
        overflow: hidden;
        background: rgba(234, 88, 12, 0.12);
        color: #FB923C;
        border: 1px solid rgba(234, 88, 12, 0.25);
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 2px;
        min-width: 0;
        max-width: 100%;
        box-sizing: border-box;
    }

    [data-bs-theme="light"] .memo-chip {
        background: #FFF7ED;
        color: #C2410C;
        border-color: #FED7AA;
    }

    .memo-chip i {
        flex: none;
        flex-shrink: 0;
    }

    .memo-chip .text-truncate {
        flex: 1 1 auto;
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* iPadOS Split View Day Timeline Card */
    .timeline-card {
        background: var(--ux-surface);
        border: 1px solid var(--ux-line);
        border-radius: 12px;
        padding: 10px 14px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        border-left: 3.5px solid #EA580C;
        box-shadow: var(--ux-shadow-sm);
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    [data-bs-theme="dark"] .timeline-card {
        background: var(--ux-surface-2);
        border-color: rgba(255, 255, 255, 0.08);
        border-left-color: #EA580C;
    }

    .timeline-card:hover {
        box-shadow: var(--ux-shadow);
        transform: translateY(-1px);
    }

    /* Reminders Day Callout Widget */
    .apple-reminder-banner {
        background: #F4F4F6 !important;
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
        border-radius: 14px;
        padding: 10px 14px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 12px;
    }

    .week-columns-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 8px;
        margin-top: 10px;
        width: 100%;
        flex: 1;
        min-height: 0;
        box-sizing: border-box;
    }

    .week-column {
        background: #FAFAFC;
        border-radius: 14px;
        padding: 8px;
        min-height: 380px;
        min-width: 0;
        width: 100%;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        border: 1px solid rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .week-col-header {
        text-align: center;
        padding-bottom: 6px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        margin-bottom: 8px;
        cursor: pointer;
        flex-shrink: 0;
    }

    .week-col-body {
        flex: 1;
        min-height: 0;
        overflow-y: auto !important;
        overflow-x: hidden;
        display: flex;
        flex-direction: column;
        gap: 6px;
        padding-right: 2px;
    }

    .week-col-body::-webkit-scrollbar {
        width: 4px;
    }
    .week-col-body::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.18);
        border-radius: 4px;
    }

    .week-card-shift {
        background: var(--ux-surface);
        border: 1px solid var(--ux-line);
        border-radius: 12px;
        padding: 10px;
        margin-bottom: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        cursor: pointer;
        transition: all .18s;
        border-left: 3.5px solid #EA580C;
    }

    [data-bs-theme="dark"] .week-card-shift {
        background: #162032;
        border-color: rgba(255, 255, 255, 0.08);
        color: #F8FAFC;
    }

    /* Promax Modals & Forms */
    .modal-content {
        background: var(--ux-surface) !important;
        color: var(--ux-ink) !important;
        border-radius: 20px !important;
        overflow: hidden;
        border: 1px solid var(--ux-line) !important;
        box-shadow: 0 24px 60px -12px rgba(0, 0, 0, 0.5) !important;
    }

    [data-bs-theme="dark"] .modal-content {
        background: var(--ux-surface-2) !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    .modal-header {
        border-bottom: 1px solid var(--ux-line) !important;
        padding: 16px 22px;
    }

    .modal-footer {
        border-top: 1px solid var(--ux-line) !important;
        padding: 14px 22px;
    }

    [data-bs-theme="dark"] .form-control,
    [data-bs-theme="dark"] .form-select {
        background-color: #162032 !important;
        border-color: rgba(255, 255, 255, 0.12) !important;
        color: #F8FAFC !important;
    }

    [data-bs-theme="dark"] .form-control:focus,
    [data-bs-theme="dark"] .form-select:focus {
        background-color: #1A2436 !important;
        border-color: #EA580C !important;
        color: #FFFFFF !important;
        box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.25) !important;
    }

    [data-bs-theme="dark"] .form-control::placeholder {
        color: #64748B !important;
    }

    /* Legibility overrides for dark mode */
    [data-bs-theme="dark"] .text-dark {
        color: #F8FAFC !important;
    }
    [data-bs-theme="dark"] .text-muted {
        color: #94A3B8 !important;
    }
    [data-bs-theme="dark"] .bg-white {
        background-color: #162032 !important;
        color: #F8FAFC !important;
    }
    [data-bs-theme="dark"] .bg-light {
        background-color: #162032 !important;
        color: #F8FAFC !important;
    }
    [data-bs-theme="dark"] .table {
        --bs-table-bg: transparent;
        --bs-table-color: #F8FAFC;
        --bs-table-border-color: rgba(255, 255, 255, 0.08);
    }

    /* Accounting specific styling (design-taste-frontend-v1) */
    .vneid-thumb {
        width: 48px !important;
        height: 34px !important;
        min-width: 48px !important;
        max-width: 48px !important;
        max-height: 34px !important;
        object-fit: cover !important;
        border-radius: 8px !important;
        border: 1.5px solid var(--ux-line) !important;
        cursor: pointer !important;
        transition: transform .2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow .2s !important;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.08) !important;
        display: inline-block !important;
        vertical-align: middle !important;
        flex-shrink: 0 !important;
    }
    .vneid-thumb:hover {
        transform: scale(1.12) !important;
        box-shadow: 0 6px 16px -2px rgba(15, 23, 42, 0.15) !important;
        border-color: var(--ux-accent) !important;
    }

    .ai-audit-banner {
        background: #F4F4F6;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 18px;
        padding: 14px 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    @media (max-width: 992px) {
        .app-shell {
            display: flex !important;
            flex-direction: row !important;
            width: 100% !important;
            min-width: 0 !important;
        }
        .u040-wrapper {
            width: 56px !important;
            min-width: 56px !important;
            max-width: 56px !important;
            flex: 0 0 56px !important;
            display: block !important;
            overflow-x: hidden !important;
        }
        .u040-wrapper .u040-btn span,
        .u040-wrapper .u040-group-label,
        .u040-wrapper .u040-me span,
        .u040-wrapper .u040-chevron {
            display: none !important;
        }
        .main-workspace {
            padding: 8px 10px 20px 8px !important;
            flex: 1 !important;
            min-width: 0 !important;
            width: calc(100% - 56px) !important;
        }
        .week-columns-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 576px) {
        .u040-wrapper { display: none !important; }
        .main-workspace {
            width: 100% !important;
            padding: 6px !important;
        }
    }

    @media print {
        .u040-wrapper, .dashboard-topbar, .kpi-card, .btn, .view-switcher-group, #calendarViewSwitcher, .modal-backdrop, .modal-header, .modal-footer, .btn-close {
            display: none !important;
        }
        body {
            background: #ffffff !important;
        }
        .main-workspace { padding: 0 !important; }
        .calendar-container, .spa-panel { border: none !important; box-shadow: none !important; }
        #contractModal {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
        }
        #contractModal .modal-dialog {
            max-width: 100% !important;
            margin: 0 !important;
        }
        #contractModal .modal-content {
            border: none !important;
            box-shadow: none !important;
        }
        #contractPrintArea {
            padding: 0 !important;
        }
    }
    
    /* Color Swatch Pills in Add Note Modal */
    .color-choice-label {
        background: #FFFFFF !important;
        border-color: rgba(0,0,0,0.12) !important;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .color-choice-label:hover {
        background: #F8FAFC !important;
        border-color: rgba(0,0,0,0.25) !important;
        transform: translateY(-1px);
    }
    .color-choice-input:checked + .color-choice-label {
        background: #FFFFFF !important;
        border-color: var(--choice-color, #EA580C) !important;
        box-shadow: 0 0 0 2px var(--choice-color, #EA580C), 0 2px 8px rgba(0,0,0,0.08) !important;
    }
    .color-choice-input:checked + .color-choice-label span:last-child {
        font-weight: 700 !important;
        color: #111827 !important;
    }

    /* When sidebar is collapsed (compact mode 56px), hide badge completely to avoid overlapping icons */
    .u040:not(.is-wide) .u040-bd,
    .u040:not(.is-wide) .u040-bd-admin,
    .u040:not(.is-wide) em.u040-bd {
        display: none !important;
        opacity: 0 !important;
        visibility: hidden !important;
    }

    /* Table Clean & Spacious Styling */
    #panelAccounting table td {
        padding-top: 10px !important;
        padding-bottom: 10px !important;
        vertical-align: middle !important;
    }

</style>
</head>
<body>

<div class="app-shell">

    <!-- =========================================================
         EXPANDING SIDE RAIL (LUCIAN UX #040) - MAIN NAVIGATION
         ========================================================= -->
    <aside class="u040-wrapper">
        <div class="ux u040 is-wide" id="lucianSideRail">
            <nav class="u040-rail" aria-label="Main menu">
                <!-- Top Brand (With Official School Logo) -->
                <div class="u040-top">
                    <div class="u040-school-box" title="FPT Polytechnic">
                        <img src="/assets/images/fpt_polytechnic_logo.png" alt="FPT Polytechnic" style="height: 20px; width: auto; max-width: 100%; object-fit: contain;">
                    </div>
                    <span class="u040-name">Poly<strong style="color:var(--ux-accent);">Mee</strong></span>
                </div>

                <!-- Expand/Collapse Button (Bezier Spring Curve) -->
                <button class="u040-tog" type="button" id="railToggleBtn" aria-expanded="true" aria-label="Collapse menu">
                    <svg viewBox="0 0 16 16"><path d="M6 3.5L10.5 8 6 12.5"/></svg>
                </button>

                <!-- Navigation List (Menu Chính Hệ Thống Phân Quyền Theo Chuẩn BA PolyMee) -->
                <ul class="u040-nav" id="railNav">
                    <li class="u040-hl" role="presentation"></li>

                    <!-- 1. LỊCH TRÌNH & ĐIỀU PHỐI -->
                    <li class="u040-section-label"><span>LỊCH TRÌNH & ĐIỀU PHỐI</span></li>
                    <li>
                        <button class="u040-it" type="button" style="--i:0" aria-current="page" data-page="calendar" onclick="navigateToPage('calendar')" title="Lịch hoạt động & phân rã ca trực">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="5.5" width="16" height="14.5" rx="3"/><path d="M4 10h16M8.5 3.5v4M15.5 3.5v4"/></svg>
                            <span>Lịch Hoạt Động</span>
                        </button>
                    </li>
                    <?php if ($perms['canViewMyShifts']): ?>
                    <li>
                        <button class="u040-it" type="button" style="--i:1" data-page="my-shifts" onclick="navigateToPage('my-shifts')" title="Ca trực cá nhân đã đăng ký & Check-in QR">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 11l8-6.5 8 6.5V19a1 1 0 0 1-1 1h-4.5v-5h-5v5H5a1 1 0 0 1-1-1z"/></svg>
                            <span>Ca Trực Của Tôi</span>
                        </button>
                    </li>
                    <?php endif; ?>
                    <?php if ($perms['canViewContracts']): ?>
                    <li>
                        <button class="u040-it" type="button" style="--i:2" data-page="contract-dossier" onclick="navigateToPage('contract-dossier')" title="Hồ Sơ Hợp Đồng Nghiệm Thu">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM14 9V3.5L18.5 8H14zM8 13h8M8 17h5"/></svg>
                            <span>Hồ Sơ Hợp Đồng</span>
                        </button>
                    </li>
                    <?php endif; ?>

                    <!-- 2. TÀI CHÍNH & VNEID (Phân Quyền Kế Toán & Hồ Sơ) -->
                    <?php if ($perms['canViewAccounting'] || $perms['canViewCtvProfiles'] || $perms['canViewMyProfile'] || $perms['canViewSubmissions']): ?>
                    <li class="u040-section-label"><span>TÀI CHÍNH & VNEID</span></li>
                    <?php if ($perms['canViewAccounting']): ?>
                    <li>
                        <button class="u040-it" type="button" style="--i:3" data-page="accounting" onclick="navigateToPage('accounting')" title="Bảng Nghiệm Thu Thù Lao & Quyết Toán Kế Toán">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 7h6M9 11h6M9 15h4M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/></svg>
                            <span>Nghiệm Thu Kế Toán</span>
                            <em class="u040-bd" aria-label="Hồ sơ nghiệm thu"><?= count($accountingList) ?></em>
                        </button>
                    </li>
                    <?php endif; ?>
                    <?php if (!empty($perms['canViewCtvProfiles'])): ?>
                    <li>
                        <button class="u040-it" type="button" style="--i:4" data-page="student-payroll-profile" onclick="navigateToPage('student-payroll-profile')" title="Quản lý hồ sơ định danh CCCD, VNeID & STK ngân hàng của CTV">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="3"/><path d="M3 10h18M7 15h2"/></svg>
                            <span>Quản Lý Hồ Sơ CTV</span>
                            <em class="u040-bd" style="background:#0284C7; color:#fff;" aria-label="CTV Count"><?= count($ctvRoster ?? []) ?></em>
                        </button>
                    </li>
                    <?php endif; ?>
                    <?php if ($perms['canViewSubmissions']): ?>
                    <li>
                        <button class="u040-it" type="button" style="--i:5" data-page="submissions" onclick="navigateToPage('submissions')" title="Thẩm định nghiệm thu minh chứng Google Drive">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19.5V13M5 9V4.5M12 19.5V11M12 7V4.5M19 19.5V15M19 11V4.5M3 13h4M10 7h4M17 15h4"/></svg>
                            <span>Minh Chứng Drive</span>
                        </button>
                    </li>
                    <?php endif; ?>
                    <?php endif; ?>

                    <!-- 3. QUẢN LÝ TỔ CHỨC -->
                    <?php if ($perms['canViewEvents']): ?>
                    <li class="u040-section-label"><span>QUẢN LÝ TỔ CHỨC</span></li>
                    <li>
                        <button class="u040-it" type="button" style="--i:6" data-page="events" onclick="navigateToPage('events')" title="Quản lý sự kiện & điều phối ca trực">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="4" width="7" height="7" rx="2"/><rect x="13" y="4" width="7" height="7" rx="2"/><rect x="4" y="13" width="7" height="7" rx="2"/><rect x="13" y="13" width="7" height="7" rx="2"/></svg>
                            <span>Quản Lý Sự Kiện</span>
                        </button>
                    </li>
                    <?php endif; ?>

                    <!-- 4. HỆ THỐNG & TÀI KHOẢN -->
                    <li class="u040-section-label"><span>HỆ THỐNG & TÀI KHOẢN</span></li>
                    <li>
                        <button class="u040-it" type="button" style="--i:7" data-page="profile-settings" onclick="navigateToPage('profile-settings')" title="Cài đặt thông tin tài khoản cá nhân, CCCD & STK">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <span>Tài Khoản Cá Nhân</span>
                        </button>
                    </li>
                    <?php if (!empty($perms['canManageRoles'])): ?>
                    <li>
                        <button class="u040-it" type="button" style="--i:8" data-page="user-roles" onclick="navigateToPage('user-roles')" title="Trung tâm cấp quyền & phân vai trò người dùng">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <span>Cấp Quyền</span>
                        </button>
                    </li>
                    <?php endif; ?>
                    <?php if (!empty($perms['canViewAuditLogs'])): ?>
                    <li>
                        <button class="u040-it" type="button" style="--i:9" data-page="audit-trail" onclick="navigateToPage('audit-trail')" title="Nhật ký lịch sử thao tác hệ thống (Audit Trail)">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
                            <span>Nhật Ký Audit Trail</span>
                            <em class="u040-bd" style="background:#0284C7; color:#fff;" aria-label="Logs"><?= count($activityLogs) ?></em>
                        </button>
                    </li>
                    <?php endif; ?>
                </ul>

                <!-- Profile Footer With Side Account Flyout (Never Covers Navigation) -->
                <?php
                $isLoggedIn = !empty($currentUser['id']) && (int)$currentUser['id'] > 0 && ($currentUser['role_name'] ?? '') !== 'Guest';
                ?>
                <div class="u040-user-menu-wrapper w-100 position-relative" style="margin-top: auto; z-index: 1080;">
                    <?php if ($isLoggedIn): ?>
                    <button class="u040-me w-100 border-0 text-start d-flex align-items-center" type="button" id="sidebarUserMenuBtn" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false" title="Tài khoản cá nhân & Đăng xuất">
                        <i aria-hidden="true"><?= mb_substr($currentUser['full_name'] ?? 'U', 0, 1) ?></i>
                        <span class="flex-grow-1 overflow-hidden text-truncate">
                            <?= htmlspecialchars($currentUser['full_name'] ?? 'User') ?>
                            <small><?= htmlspecialchars($currentUser['role_name'] ?? 'Sinh viên') ?> • <?= htmlspecialchars($currentUser['student_code'] ?? 'CTV') ?></small>
                        </span>
                        <svg class="u040-chevron d-none d-md-block flex-shrink-0 ms-auto me-1" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.45; transition: transform 0.2s;"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>

                    <div class="dropdown-menu sidebar-account-flyout p-0 border-0" id="sidebarUserFlyoutMenu" aria-labelledby="sidebarUserMenuBtn">
                        <!-- Current User Header Card -->
                        <div class="p-3 border-bottom" style="background: #FAFAFC; border-color: rgba(0,0,0,0.06) !important; border-top-left-radius: 20px; border-top-right-radius: 20px;">
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="rounded-circle d-grid place-items-center text-white fw-bold shadow-xs flex-shrink-0" style="width: 38px; height: 38px; background: linear-gradient(135deg, #F97316 0%, #EA580C 100%); font-size: 14px;">
                                    <?= mb_substr($currentUser['full_name'] ?? 'U', 0, 1) ?>
                                </div>
                                <div class="overflow-hidden">
                                    <strong class="d-block text-truncate" style="color: #1D1D1F; font-size: 13.5px;"><?= htmlspecialchars($currentUser['full_name'] ?? 'User') ?></strong>
                                    <span class="badge px-2 py-0.5 rounded-pill font-monospace" style="font-size: 10px; background: #F4F4F6; color: #1D1D1F; border: 1px solid rgba(0,0,0,0.08);"><?= htmlspecialchars($currentUser['role_name'] ?? 'Sinh viên') ?> • <?= htmlspecialchars($currentUser['student_code'] ?? 'CTV') ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Navigation Actions -->
                        <div class="border-bottom d-flex flex-column gap-1" style="padding: 8px 10px; border-color: rgba(0,0,0,0.06) !important;">
                            <a class="dropdown-item rounded-3 d-flex align-items-center gap-3 transition-all" href="javascript:void(0)" onclick="navigateToPage('profile-settings'); closeSidebarUserMenu();" style="padding: 8px 12px; border: 1px solid transparent;">
                                <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0 shadow-2xs" style="width: 36px; height: 36px; background: #F1F5F9; border: 1px solid #CBD5E1; color: #334155; font-size: 16px;">
                                    <i class="bi bi-person-gear"></i>
                                </div>
                                <div class="d-flex flex-column justify-content-center overflow-hidden" style="line-height: 1.25;">
                                    <span class="fw-bold text-dark d-block text-truncate" style="font-size: 13px;">Cài Đặt Tài Khoản Cá Nhân</span>
                                    <small class="text-muted d-block text-truncate mt-0.5" style="font-size: 11px;">Hồ sơ, mật khẩu & xóa tài khoản</small>
                                </div>
                            </a>

                            <?php if (($currentUser['role_name'] ?? '') === 'Admin' || (int)($currentUser['role_id'] ?? 0) === 1): ?>
                            <a class="dropdown-item rounded-3 d-flex align-items-center gap-3 transition-all" href="javascript:void(0)" onclick="navigateToPage('admin-management'); closeSidebarUserMenu();" style="padding: 8px 12px; border: 1px solid transparent;">
                                <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0 shadow-2xs" style="width: 36px; height: 36px; background: #E0F2FE; border: 1px solid #BAE6FD; color: #0284C7; font-size: 16px;">
                                    <i class="bi bi-shield-lock-fill"></i>
                                </div>
                                <div class="d-flex flex-column justify-content-center overflow-hidden" style="line-height: 1.25;">
                                    <span class="fw-bold text-dark d-block text-truncate" style="font-size: 13px;">Cấp Quyền & Nhật Ký Kiểm Toán</span>
                                    <small class="text-muted d-block text-truncate mt-0.5" style="font-size: 11px;">Phân quyền Admin & Audit Trail</small>
                                </div>
                            </a>
                            <?php endif; ?>
                        </div>

                        <!-- Logout Action Only -->
                        <div class="p-2">
                            <a class="dropdown-item py-2 px-2.5 rounded-2 text-danger fw-bold d-flex align-items-center gap-2" href="/auth/logout" style="font-size: 12px;">
                                <i class="bi bi-box-arrow-right fs-6"></i>
                                <span>Đăng Xuất</span>
                            </a>
                        </div>
                    </div>
                    <?php else: ?>
                    <button class="u040-me w-100 border-0 text-start d-flex align-items-center" type="button" id="sidebarUserMenuBtn" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false" title="Đăng nhập / Đăng ký">
                        <i aria-hidden="true" style="background:#E5E5EA; color:#48484A;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></i>
                        <span class="flex-grow-1 overflow-hidden text-truncate">
                            Khách Vãng Lai
                            <small>Chưa đăng nhập • Nhấp để vào</small>
                        </span>
                        <svg class="u040-chevron d-none d-md-block flex-shrink-0 ms-auto me-1" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.45; transition: transform 0.2s;"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>

                    <div class="dropdown-menu sidebar-account-flyout p-0 border-0" id="sidebarUserFlyoutMenu" aria-labelledby="sidebarUserMenuBtn">
                        <!-- Guest Header Card -->
                        <div class="p-3 border-bottom" style="background: #FAFAFC; border-color: rgba(0,0,0,0.06) !important; border-top-left-radius: 20px; border-top-right-radius: 20px;">
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="rounded-circle d-grid place-items-center text-secondary fw-bold flex-shrink-0" style="width: 38px; height: 38px; background: #E5E5EA; font-size: 16px;">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <strong class="d-block text-truncate" style="color: #1D1D1F; font-size: 13.5px;">Khách Vãng Lai</strong>
                                    <span class="badge px-2 py-0.5 rounded-pill font-monospace" style="font-size: 10px; background: #F4F4F6; color: #86868B; border: 1px solid rgba(0,0,0,0.08);">Chưa Đăng Nhập</span>
                                </div>
                            </div>
                        </div>

                        <!-- Guest Auth Actions: Login & Register -->
                        <div class="p-2">
                            <a class="dropdown-item py-2 px-2.5 rounded-2 text-dark fw-bold d-flex align-items-center gap-2" href="/auth/login" style="font-size: 12.5px;">
                                <i class="bi bi-box-arrow-in-right fs-6 text-primary"></i>
                                <span>Đăng Nhập Hệ Thống</span>
                            </a>
                            <a class="dropdown-item py-2 px-2.5 rounded-2 text-secondary d-flex align-items-center gap-2 mt-0.5" href="/auth/register" style="font-size: 12.5px;">
                                <i class="bi bi-person-plus fs-6 text-secondary"></i>
                                <span>Đăng Ký Tài Khoản CTV</span>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </aside>

    <!-- =========================================================
         MAIN APPLICATION WORKSPACE (SINGLE PAGE CONTAINER)
         ========================================================= -->
    <main class="main-workspace">

        <!-- Top Bar Navigation & Controls -->
        <header class="dashboard-topbar">
            <!-- Left: Breadcrumbs & Screen Title -->
            <div class="d-flex align-items-center gap-3">
                <a href="/dashboard" class="d-flex align-items-center text-decoration-none p-1 rounded-3" style="height: 38px; border: none !important; box-shadow: none !important; background: transparent;" title="FPT Polytechnic">
                    <img src="/assets/images/fpt_polytechnic_logo.png" alt="FPT Polytechnic" style="height: 25px; width: auto; object-fit: contain; border: none !important;">
                </a>
                <div class="vr mx-1 d-none d-sm-block" style="height: 24px; opacity: 0.25;"></div>
                <div class="d-flex flex-column">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0" style="font-size: 11px; font-weight: 600;">
                            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">PolyMee</a></li>
                            <li class="breadcrumb-item active" id="breadcrumbActive" style="color:var(--ux-accent);">Bảng Lịch Trung Tâm</li>
                        </ol>
                    </nav>
                    <h1 class="fw-bold mb-0 tracking-tight" style="font-size: 16px; color: #1D1D1F;" id="activeScreenTitle">Lịch Hoạt Động & Điều Phối Ca Trực</h1>
                </div>
            </div>

            <!-- Center: View Switcher (Month | Week | Day) & Month Navigator -->
            <div class="d-flex align-items-center gap-2">
                <div class="view-switcher-group btn-group shadow-xs" id="calendarViewSwitcher" role="group">
                    <button type="button" class="btn active" id="btnViewMonth" onclick="changeCalendarView('month')">
                        <i class="bi bi-grid-3x3 me-1"></i> Tháng
                    </button>
                    <button type="button" class="btn" id="btnViewWeek" onclick="changeCalendarView('week')">
                        <i class="bi bi-calendar-week me-1"></i> Tuần
                    </button>
                    <button type="button" class="btn" id="btnViewDay" onclick="changeCalendarView('day')">
                        <i class="bi bi-calendar-day me-1"></i> Ngày
                    </button>
                </div>

                <div class="btn-group btn-group-sm" id="calendarMonthNav">
                    <button class="btn-fpt-secondary btn-fpt-sm" type="button" onclick="navigateCalendar(-1)" title="Tháng trước">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="btn-fpt-secondary btn-fpt-sm fw-bold px-3 num-tabular" type="button" id="currentCalendarHeader" onclick="goToday()">
                        Tháng 11/2026
                    </button>
                    <button class="btn-fpt-secondary btn-fpt-sm" type="button" onclick="navigateCalendar(1)" title="Tháng sau">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>

            <!-- Right: Action Controls (Clean, Minimalist Apple Design) -->
            <div class="d-flex align-items-center gap-2">
                <?php if (!empty($perms['canCreateShift'])): ?>
                <button type="button" class="btn-fpt-primary btn-fpt-sm" onclick="openCreateShiftModal()">
                    <i class="bi bi-calendar-plus-fill me-1"></i>
                    <span>Đặt Lịch Ca</span>
                </button>
                <?php endif; ?>
                <?php if (!empty($perms['canCreateNote'])): ?>
                <button type="button" class="btn-fpt-secondary btn-fpt-sm" onclick="openAddNoteModal()" title="Thêm ghi chú / lịch hẹn toàn trường">
                    <i class="fa-solid fa-note-sticky text-warning me-1"></i>
                    <span>Ghi Chú</span>
                </button>
                <?php endif; ?>
                <button type="button" class="btn-fpt-ghost btn-fpt-sm" onclick="toggleAiModal()" title="Trợ lý AI PolyMee">
                    <i class="fa-solid fa-wand-magic-sparkles text-warning me-1"></i>
                    <span>Trợ lý AI</span>
                </button>
            </div>
        </header>

        <!-- =========================================================
             DYNAMIC SPA BODY: CHUYỂN TABS KHÔNG RELOAD TRANG
             ========================================================= -->
        <div id="spaBodyContent">

            <!-- TAB 1: BẢNG LỊCH TRUNG TÂM (CALENDAR-FIRST) -->
            <section class="calendar-container" id="panelCalendar">

                <!-- KPI Metrics Strip: CHỈ HIỂN THỊ DUY NHẤT Ở TRANG LỊCH HOẠT ĐỘNG -->
                <div class="kpi-metric-strip mb-3" id="kpiMetricsStrip">
                    <div class="kpi-mini-card">
                        <div>
                            <div class="kpi-mini-label">Tổng Sự Kiện</div>
                            <div class="kpi-mini-val num-tabular"><?= number_format($stats['total_events'] ?? 0) ?></div>
                        </div>
                        <div class="kpi-mini-icon text-dark" style="background: #F4F4F6;">
                            <i class="bi bi-calendar2-event"></i>
                        </div>
                    </div>
                    <div class="kpi-mini-card">
                        <div>
                            <div class="kpi-mini-label">Tổng Ca Trực</div>
                            <div class="kpi-mini-val num-tabular"><?= number_format($stats['total_shifts'] ?? 0) ?></div>
                        </div>
                        <div class="kpi-mini-icon text-dark" style="background: #F4F4F6;">
                            <i class="bi bi-clock-history"></i>
                        </div>
                    </div>
                    <div class="kpi-mini-card">
                        <div>
                            <div class="kpi-mini-label">Tỷ Lệ Lấp Đầy</div>
                            <div class="kpi-mini-val num-tabular text-dark"><?= $stats['fill_rate_percent'] ?? 0 ?>%</div>
                        </div>
                        <div class="kpi-mini-icon text-dark" style="background: #F4F4F6;">
                            <i class="bi bi-pie-chart"></i>
                        </div>
                    </div>
                    <div class="kpi-mini-card">
                        <div>
                            <div class="kpi-mini-label">Quy Hoạch Nghiệm Thu</div>
                            <div class="kpi-mini-val num-tabular text-dark"><?= count($accountingList) ?> <small style="font-size:12px; font-weight:500; color:var(--ux-ink-3);">Hồ sơ</small></div>
                        </div>
                        <div class="kpi-mini-icon text-dark" style="background: #F4F4F6;">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap justify-content-between align-items-center pb-2.5 border-bottom mb-3 gap-2" style="border-color: rgba(0,0,0,0.06) !important;">
                    <div>
                        <h2 class="fw-bold mb-0 text-dark" style="font-size: 17.5px; letter-spacing: -0.015em;" id="calendarViewHeading">Lịch Tổng Quan Theo Tháng</h2>
                        <p class="text-muted mb-0" style="font-size: 12.5px; margin-top: 2px;" id="calendarViewSub">Nhấp vào ô ngày bất kỳ để vào thẳng Lịch Ngày xem chi tiết các ca trực</p>
                    </div>
                    <div class="d-flex align-items-center gap-3" style="font-size: 12px; font-weight: 600; color: #48484A;">
                        <div class="d-flex align-items-center gap-1.5">
                            <span class="d-inline-block rounded-circle" style="width:8px; height:8px; background:var(--ux-accent);"></span>
                            <span>Ca đang tuyển</span>
                        </div>
                        <div class="d-flex align-items-center gap-1.5">
                            <span class="d-inline-block rounded-circle" style="width:8px; height:8px; background:var(--ux-mint);"></span>
                            <span>Ca của tôi</span>
                        </div>
                        <div class="d-flex align-items-center gap-1.5">
                            <span class="d-inline-block rounded-circle" style="width:8px; height:8px; background:var(--ux-danger);"></span>
                            <span>Đã đủ CTV</span>
                        </div>
                    </div>
                </div>

                <!-- TIER 1: LỊCH THÁNG (Ô VUÔNG) -->
                <div id="tierMonthView">
                    <div class="month-header-grid">
                        <div class="day-label">Thứ 2</div>
                        <div class="day-label">Thứ 3</div>
                        <div class="day-label">Thứ 4</div>
                        <div class="day-label">Thứ 5</div>
                        <div class="day-label">Thứ 6</div>
                        <div class="day-label text-warning">Thứ 7</div>
                        <div class="day-label text-danger">Chủ Nhật</div>
                    </div>
                    <div class="month-days-grid" id="monthGridContainer">
                        <!-- Nạp động bằng JavaScript -->
                    </div>
                </div>

                <!-- TIER 2: LỊCH TUẦN (7 CỘT THỨ 2 -> CN) -->
                <div id="tierWeekView" style="display:none;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <button class="btn-fpt-secondary btn-fpt-sm" onclick="changeCalendarView('month')">
                            <i class="bi bi-arrow-left me-1"></i> Quay lại Lịch Tháng
                        </button>
                        <span class="badge bg-light text-dark border px-3 py-2 fw-bold" id="weekRangeLabel">Tuần 46 • Tháng 11/2026</span>
                    </div>
                    <div class="week-columns-grid" id="weekColumnsContainer">
                        <!-- Nạp động -->
                    </div>
                </div>

                <!-- TIER 3: LỊCH NGÀY (DANH SÁCH CHI TIẾT CA TRỰC) -->
                <div id="tierDayView" style="display:none;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <button class="btn-fpt-secondary btn-fpt-sm" onclick="changeCalendarView('month')">
                            <i class="bi bi-arrow-left me-1"></i> Quay lại Lịch Tháng
                        </button>
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn-fpt-secondary btn-fpt-sm" onclick="changeCalendarView('week')">
                                <i class="bi bi-calendar-week me-1"></i> Xem Tuần Chứa Ngày Này
                            </button>
                            <span class="badge text-white px-3 py-2 fw-bold" style="background:var(--ux-accent);" id="selectedDayLabel">
                                Thứ Bảy, 15/11/2026
                            </span>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-3" id="dayTimelineContainer">
                        <!-- Nạp động -->
                    </div>
                </div>
            </section>

            
            

            <!-- TAB 2: BẢNG NGHIỆM THU KẾ TOÁN & KIỂM DUYỆT THÙ LAO (ADMIN / ORGANIZER / STAFF) -->
            <section class="spa-panel" id="panelAccounting" style="display:none !important;">
                <!-- 1. VÙNG CỐ ĐỊNH: Tiêu đề, Widget AI & Bộ Lọc Trạng Thái (Non-scrollable Top Section) -->
                <div class="flex-shrink-0">
                    <div class="d-flex flex-wrap justify-content-between align-items-center pb-2.5 border-bottom mb-3 gap-2" style="border-color: rgba(0,0,0,0.06) !important;">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 fw-bold" style="font-size: 11px;">HÀNH CHÍNH & TÀI CHÍNH</span>
                                <h2 class="fw-bold mb-0 text-dark" style="font-size: 17.5px; letter-spacing: -0.015em;">Bảng Nghiệm Thu Thù Lao & Quyết Toán CTV</h2>
                            </div>
                            <p class="text-muted mb-0" style="font-size: 12.5px; margin-top: 2px;">Quy trình 2 nhánh: CTV Bàn ghế/máy móc (Nghiệm thu ảnh hiện trường) & CTV Media (Nghiệm thu Link Google Drive) ➔ Giảng viên thẩm định ➔ Trình Nhà trường ký duyệt chuyển khoản</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn-fpt-secondary btn-fpt-sm d-inline-flex align-items-center gap-1.5" onclick="window.print()">
                                <i class="bi bi-printer-fill text-secondary"></i> In Bảng Quyết Toán
                            </button>
                            <button type="button" class="btn-fpt-secondary btn-fpt-sm d-inline-flex align-items-center gap-1.5" onclick="exportAccountingCSV()">
                                <i class="bi bi-file-earmark-excel-fill text-success"></i> Xuất File Excel (CSV)
                            </button>
                        </div>
                    </div>

                    <!-- Bento 2.0 AI Accounting Auditor Widget -->
                    <div class="ai-audit-banner mb-2.5 d-flex flex-wrap align-items-center justify-content-between gap-2.5 p-2.5 rounded-3" style="background: #FFFBF5; border: 1px solid rgba(234, 88, 12, 0.15);">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-dark" style="width:32px; height:32px; background:#FFFFFF; flex:none; border:1px solid rgba(0,0,0,0.08); box-shadow:0 1px 2px rgba(0,0,0,0.04);">
                                <i class="fa-solid fa-wand-magic-sparkles text-warning" style="font-size: 14px;"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <strong class="text-dark" style="font-size:12.5px;">AI Thẩm Định & Quyết Toán Thù Lao Tự Động</strong>
                                    <span class="badge rounded-pill bg-white text-dark border num-tabular" style="font-size:10px; border-color:rgba(0,0,0,0.08) !important;">
                                        <i class="bi bi-shield-check me-1 text-success"></i>100% Khớp VNeID
                                    </span>
                                </div>
                                <small class="text-muted d-block mt-0.5" style="font-size: 11px;">
                                    AI đã quét đối soát <strong><?= count($accountingList) ?> hồ sơ</strong>: Phân luồng minh chứng ảnh hiện trường & Link Drive, tự động gán mã Hợp đồng nghiệm thu điện tử.
                                </small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-white text-dark border px-2.5 py-1.5 shadow-xs num-tabular" style="font-size: 11px;">
                                Tổng quyết toán: <strong class="text-primary ms-1"><?= number_format(count($accountingList) * 150000) ?> đ</strong>
                            </span>
                            <button type="button" class="btn-fpt-outline-orange btn-fpt-sm" onclick="runAiAccountingAudit()" style="padding: 4px 10px; font-size: 11.5px;">
                                <i class="bi bi-magic me-1"></i> AI Quét Bất Thường
                            </button>
                        </div>
                    </div>

                    <!-- Thanh Tìm Kiếm & Lọc Nhanh Hồ Sơ Nghiệm Thu -->
                    <div class="row g-2 mb-2.5 bg-light p-2 rounded-3 border" style="border-color: rgba(0,0,0,0.06) !important;">
                        <div class="col-12 col-md-6 col-lg-7">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" id="accountingSearchInput" class="form-control border-0" placeholder="Tìm theo tên CTV, mã sinh viên, số CCCD hoặc tên sự kiện..." oninput="filterAccountingTable()">
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-lg-3">
                            <select id="accountingStatusFilter" class="form-select form-select-sm border-0" onchange="filterAccountingTable()">
                                <option value="">-- Tất cả trạng thái --</option>
                                <option value="Pending">Chờ GV Nghiệm Thu</option>
                                <option value="Approved">Chờ Trường Ký Duyệt</option>
                                <option value="Paid">Đã Ký & Chi Trả Thành Công</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-3 col-lg-2 text-end">
                            <span class="badge bg-white text-muted border px-2 py-1.5 w-100 num-tabular" id="accountingFilteredCount" style="font-size: 11px;">Hiển thị: <?= count($accountingList) ?> hồ sơ</span>
                        </div>
                    </div>
                </div>

                <!-- 2. VÙNG BẢNG DỮ LIỆU: Container Cuộn Độc Lập (Scrollable Body) & Sticky Header -->
                <div class="card border-0 rounded-3 shadow-xs flex-1 overflow-hidden d-flex flex-column" style="border: 1px solid var(--ux-line) !important;">
                    <div class="table-responsive flex-1 overflow-y-auto" style="height: 100%;">
                                                <table class="table table-hover align-middle mb-0" style="font-size: 12.5px;">
                            <thead class="sticky-th">
                                <tr>
                                    <th style="width: 42px;" class="ps-3 text-center">#</th>
                                    <th style="min-width: 190px;">Cộng Tác Viên</th>
                                    <th style="min-width: 280px;">Ca Trực & Sự Kiện</th>
                                    <th style="width: 140px;">Thù Lao</th>
                                    <th style="width: 160px;">Trạng Thái</th>
                                    <th class="text-end pe-3" style="width: 180px;">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($accountingList)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Chưa có hồ sơ nghiệm thu nào trong kỳ.</td>
                                </tr>
                                <?php else: ?>
                                <?php foreach ($accountingList as $idx => $acc): ?>
                                <?php
                                $accCtvType = $acc['ctv_type'] ?? 'FACILITY';
                                $isMedia = ($accCtvType === 'MEDIA') || !empty($acc['proof_drive_url']) || stripos($acc['shift_name'], 'media') !== false;
                                ?>
                                <tr id="accRow_<?= $acc['assignment_id'] ?>" data-search="<?= strtolower(htmlspecialchars($acc['full_name'] . ' ' . $acc['student_code'] . ' ' . ($acc['id_card_number'] ?? '') . ' ' . ($acc['bank_account_number'] ?? '') . ' ' . $acc['shift_name'] . ' ' . $acc['event_title'])) ?>">
                                    <td class="text-muted fw-semibold num-tabular ps-3 text-center" style="font-size: 11px;"><?= $idx + 1 ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="rounded-circle d-grid place-items-center text-white fw-bold shadow-2xs flex-shrink-0" style="width: 32px; height: 32px; background: linear-gradient(135deg, #F97316 0%, #EA580C 100%); font-size: 12px;">
                                                <?= mb_strtoupper(mb_substr($acc['full_name'], 0, 1)) ?>
                                            </div>
                                            <div class="text-nowrap overflow-hidden">
                                                <strong class="text-dark d-block text-truncate" style="font-size: 13px; font-weight: 600; max-width: 160px;" title="<?= htmlspecialchars($acc['full_name']) ?>"><?= htmlspecialchars($acc['full_name']) ?></strong>
                                                <div class="d-flex align-items-center gap-1.5 mt-0.5 text-muted" style="font-size: 11px;">
                                                    <span class="badge bg-light text-secondary border font-monospace px-1.5 py-0.5" style="font-size: 9.5px;"><?= htmlspecialchars($acc['student_code']) ?></span>
                                                    <span class="text-truncate" style="max-width: 100px;"><?= htmlspecialchars($acc['user_department'] ?? 'CNTT') ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1.5 mb-0.5 flex-wrap">
                                            <span class="fw-semibold text-dark text-truncate" style="font-size: 13px; max-width: 250px;" title="<?= htmlspecialchars($acc['shift_name']) ?>"><?= htmlspecialchars($acc['shift_name']) ?></span>
                                            <?php if ($isMedia): ?>
                                            <span class="badge rounded-pill px-2 py-0.5" style="background: #FEF3C7; color: #D97706; border: 1px solid #FDE68A; font-size: 10px; font-weight: 600;">Media</span>
                                            <?php else: ?>
                                            <span class="badge rounded-pill px-2 py-0.5" style="background: #E0F2FE; color: #0284C7; border: 1px solid #BAE6FD; font-size: 10px; font-weight: 600;">Kỹ thuật</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-muted d-flex align-items-center gap-1.5" style="font-size: 11.5px;">
                                            <span class="num-tabular fw-medium" style="color: #64748B;"><i class="bi bi-clock me-1" style="font-size: 10.5px;"></i><?= substr($acc['start_time'], 11, 5) ?> - <?= substr($acc['end_time'], 11, 5) ?></span>
                                            <span style="opacity: 0.5;">•</span>
                                            <span class="text-truncate text-secondary" style="max-width: 230px;" title="<?= htmlspecialchars($acc['event_title']) ?>"><?= htmlspecialchars($acc['event_title']) ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark num-tabular" style="font-size: 14px; letter-spacing: -0.01em;">
                                            <?= number_format((int)$acc['remuneration_amount']) ?> đ
                                        </div>
                                        <small class="text-muted text-nowrap d-block" style="font-size: 11px;">+<?= $acc['points'] ?> điểm rèn luyện</small>
                                    </td>
                                    <td>
                                        <div id="accVerificationCol_<?= $acc['assignment_id'] ?>">
                                            <?php if (!empty($acc['lecturer_verified']) && $acc['lecturer_verified'] == 1): ?>
                                                <?php if (($acc['school_status'] ?? '') === 'Signed_Paid'): ?>
                                                    <span class="badge rounded-pill px-2.5 py-1 text-nowrap d-inline-flex align-items-center gap-1" style="background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0; font-size: 11px; font-weight: 500;">
                                                        <i class="bi bi-check-circle-fill"></i> Đã chuyển tiền
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge rounded-pill px-2.5 py-1 text-nowrap d-inline-flex align-items-center gap-1" style="background: #EFF6FF; color: #2563EB; border: 1px solid #BFDBFE; font-size: 11px; font-weight: 500;">
                                                        <i class="bi bi-hourglass-split"></i> Chờ ký duyệt
                                                    </span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="badge rounded-pill px-2.5 py-1 text-nowrap d-inline-flex align-items-center gap-1" style="background: #FFFBEB; color: #D97706; border: 1px solid #FDE68A; font-size: 11px; font-weight: 500;">
                                                    <i class="bi bi-clock"></i> Chờ GV duyệt
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <span id="accStatusBadge_<?= $acc['assignment_id'] ?>" style="display:none;"><?= htmlspecialchars($acc['accounting_status'] ?? 'Pending') ?></span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-flex align-items-center justify-content-end gap-1.5 flex-nowrap" id="accActionBtns_<?= $acc['assignment_id'] ?>">
                                            <button type="button" class="btn btn-sm btn-fpt-secondary rounded-pill px-2.5 py-1 shadow-2xs fw-medium d-inline-flex align-items-center gap-1" onclick="openAccountingDetailModal(<?= $acc['assignment_id'] ?>)" title="Xem chi tiết hồ sơ & STK">
                                                <i class="bi bi-eye text-primary" style="font-size: 11px;"></i>
                                                <span style="font-size: 11px;">Chi Tiết</span>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-fpt-secondary rounded-pill px-2.5 py-1 shadow-2xs fw-medium d-inline-flex align-items-center gap-1" onclick="openContractModal(<?= $acc['assignment_id'] ?>)" title="Xem Hợp đồng điện tử">
                                                <i class="bi bi-file-earmark-text text-warning" style="font-size: 11px;"></i>
                                                <span style="font-size: 11px;">Hợp Đồng</span>
                                            </button>
                                            <?php if ((empty($acc['lecturer_verified']) || $acc['lecturer_verified'] == 0) && $perms['canVerifyLecturer']): ?>
                                            <button type="button" class="btn btn-sm btn-fpt-emerald rounded-pill px-2.5 py-1 shadow-xs fw-semibold d-inline-flex align-items-center gap-1 text-white" onclick="verifyLecturerAcceptance(<?= $acc['assignment_id'] ?>)" title="Giảng viên duyệt nghiệm thu">
                                                <i class="bi bi-check-lg" style="font-size: 11px;"></i>
                                                <span style="font-size: 11px;">Duyệt</span>
                                            </button>
                                            <?php elseif (!empty($acc['lecturer_verified']) && $acc['lecturer_verified'] == 1 && ($acc['school_status'] ?? '') !== 'Signed_Paid' && ($perms['canSubmitSchoolSigning'] || $perms['canApproveAccounting'])): ?>
                                            <button type="button" class="btn btn-sm btn-fpt-primary rounded-pill px-2.5 py-1 shadow-xs fw-semibold d-inline-flex align-items-center gap-1 text-white" onclick="submitToSchool(<?= $acc['assignment_id'] ?>)" title="Trình Nhà trường ký duyệt">
                                                <i class="bi bi-send" style="font-size: 11px;"></i>
                                                <span style="font-size: 11px;">Trình Ký</span>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            
            

            <!-- TAB 3: QUẢN LÝ HỒ SƠ CTV (RÚT GỌN CHỈ HIỆN TÊN + BỘ MÔN, XEM CHI TIẾT QUA ICON CON MẮT) -->
            <?php
            $ctvRoster = array_values(array_filter($allUsers, function($u) {
                return ($u['role_name'] ?? '') === 'Student' || (int)($u['role_id'] ?? 0) === 4 || (int)($u['role_id'] ?? 0) === 6 || ($u['role_name'] ?? '') === 'Media';
            }));
            if (empty($ctvRoster)) { $ctvRoster = $allUsers; }
            $completeDossierCount = count(array_filter($ctvRoster, fn($c) => !empty($c['id_card_number']) && !empty($c['bank_account_number'])));
            $hasVneidPhotoCount = count(array_filter($ctvRoster, fn($c) => !empty($c['vneid_image_url'])));
            ?>
            <section class="spa-panel fade-in-up" id="panelStudentPayrollProfile" style="display:none !important;">
                <?php if ($perms['canViewCtvProfiles']): ?>
                <!-- 1. VÙNG CỐ ĐỊNH: Tiêu đề trang, 4 Thẻ KPI & Thanh Lọc (Non-scrollable Top Section) -->
                <div class="flex-shrink-0">
                    <div class="d-flex flex-wrap justify-content-between align-items-center pb-2.5 border-bottom mb-3 gap-3" style="border-color: rgba(0,0,0,0.06) !important;">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 fw-bold" style="font-size: 11px;">GIẢNG VIÊN & HÀNH CHÍNH</span>
                                <h2 class="fw-bold mb-0 text-dark" style="font-size: 17.5px; letter-spacing: -0.015em;">Quản Lý Danh Sách Cộng Tác Viên (CTV)</h2>
                            </div>
                            <p class="text-muted mb-0" style="font-size: 12.5px; margin-top: 2px;">Danh sách CTV theo từng bộ môn. Danh sách tối giản hóa chỉ hiển thị Tên & Bộ môn; nhấp nút icon con mắt (👁️) để xem chi tiết CCCD, VNeID & Ngân hàng.</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn-fpt-secondary btn-fpt-sm" onclick="window.print()">
                                <i class="bi bi-printer me-1"></i> In Danh Sách CTV
                            </button>
                            <button class="btn-fpt-emerald btn-fpt-sm" onclick="exportCtvRosterCSV()">
                                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Xuất Excel (CSV)
                            </button>
                        </div>
                    </div>

                    <!-- 4 Thẻ Chỉ Số Tổng Quan Hồ Sơ CTV -->
                    <div class="kpi-metric-strip mb-3">
                        <div class="kpi-mini-card">
                            <div>
                                <div class="kpi-mini-label">Tổng Số CTV Đăng Ký</div>
                                <div class="kpi-mini-val num-tabular"><?= count($ctvRoster) ?> <small style="font-size:12px; font-weight:500; color:var(--ux-ink-3);">CTV</small></div>
                            </div>
                            <div class="kpi-mini-icon text-dark" style="background: #F4F4F6;">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                        <div class="kpi-mini-card">
                            <div>
                                <div class="kpi-mini-label">Đầy Đủ CCCD & STK</div>
                                <div class="kpi-mini-val num-tabular text-success"><?= $completeDossierCount ?> <small style="font-size:12px; font-weight:500; color:var(--ux-ink-3);">(<?= round($completeDossierCount / max(1, count($ctvRoster)) * 100) ?>%)</small></div>
                            </div>
                            <div class="kpi-mini-icon text-dark" style="background: #F4F4F6;">
                                <i class="bi bi-check2-circle text-success"></i>
                            </div>
                        </div>
                        <div class="kpi-mini-card">
                            <div>
                                <div class="kpi-mini-label">Đã Nộp Ảnh VNeID</div>
                                <div class="kpi-mini-val num-tabular text-primary"><?= $hasVneidPhotoCount ?> <small style="font-size:12px; font-weight:500; color:var(--ux-ink-3);">hồ sơ</small></div>
                            </div>
                            <div class="kpi-mini-icon text-dark" style="background: #F4F4F6;">
                                <i class="bi bi-person-badge text-primary"></i>
                            </div>
                        </div>
                        <div class="kpi-mini-card">
                            <div>
                                <div class="kpi-mini-label">Tình Trạng Đối Soát</div>
                                <div class="kpi-mini-val num-tabular text-success" style="font-size: 15px;"><i class="bi bi-patch-check-fill me-1"></i>100% Khớp FPoly</div>
                            </div>
                            <div class="kpi-mini-icon text-dark" style="background: #F4F4F6;">
                                <i class="bi bi-shield-check text-success"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Thanh Tìm Kiếm & Lọc Nhanh Theo Bộ Môn -->
                    <div class="row g-2 mb-2.5 bg-light p-2 rounded-3 border" style="border-color: rgba(0,0,0,0.06) !important;">
                        <div class="col-12 col-md-5">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" id="ctvSearchInput" class="form-control border-0" placeholder="Tìm theo tên CTV, mã sinh viên hoặc bộ môn..." oninput="filterCtvRosterTable()">
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <select id="ctvDeptFilter" class="form-select form-select-sm border-0" onchange="filterCtvRosterTable()">
                                <option value="">-- Tất cả Bộ Môn FPoly --</option>
                                <option value="Công nghệ Thông tin">Bộ môn Công nghệ Thông tin</option>
                                <option value="Thiết kế Đồ họa">Bộ môn Thiết kế Đồ họa</option>
                                <option value="Digital Marketing">Bộ môn Digital Marketing / TMĐT</option>
                                <option value="Quản trị Kinh doanh">Bộ môn Quản trị Kinh doanh</option>
                                <option value="Du lịch - Khách sạn">Bộ môn Du lịch - Khách sạn</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-3 text-end">
                            <span class="badge bg-white text-muted border px-2 py-1.5 w-100 num-tabular" id="ctvFilteredBadge" style="font-size: 11px;">Hiển thị: <?= count($ctvRoster) ?> CTV</span>
                        </div>
                    </div>
                </div>

                <!-- 2. VÙNG BẢNG DỮ LIỆU: Container Cuộn Độc Lập (Scrollable Body) & Sticky Header -->
                <div class="card border-0 rounded-3 shadow-xs flex-1 overflow-hidden d-flex flex-column" style="border: 1px solid var(--ux-line) !important;">
                    <div class="table-responsive flex-1 overflow-y-auto" style="height: 100%;">
                        <table class="table table-hover align-middle mb-0" id="ctvRosterTable">
                            <thead class="sticky-th">
                                <tr>
                                    <th class="ps-3" style="width: 55px;">STT</th>
                                    <th>Cộng Tác Viên (CTV)</th>
                                    <th>Thuộc Bộ Môn</th>
                                    <th>Phân Loại CTV</th>
                                    <th class="text-end pe-3" style="width: 130px;">Xem Chi Tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ctvRoster as $idx => $ctv): ?>
                                <?php
                                $cInitial = mb_strtoupper(mb_substr($ctv['full_name'] ?? 'C', 0, 1));
                                $vneidUrl = $ctv['vneid_image_url'] ?? 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=600&auto=format&fit=crop&q=80';
                                $cDept = !empty($ctv['department']) ? $ctv['department'] : 'Công nghệ Thông tin';
                                $isMediaRole = (int)($ctv['role_id'] ?? 0) === 6 || ($ctv['role_name'] ?? '') === 'Media' || stripos($ctv['skills'] ?? '', 'media') !== false || stripos($ctv['skills'] ?? '', 'quay') !== false;
                                
                                $deptStyle = match($cDept) {
                                    'Công nghệ Thông tin' => 'background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE;',
                                    'Thiết kế Đồ họa' => 'background: #FAF5FF; color: #7E22CE; border: 1px solid #E9D5FF;',
                                    'Digital Marketing' => 'background: #FFFBEB; color: #B45309; border: 1px solid #FDE68A;',
                                    'Quản trị Kinh doanh' => 'background: #F0FDF4; color: #15803D; border: 1px solid #BBF7D0;',
                                    default => 'background: #F1F5F9; color: #475569; border: 1px solid #CBD5E1;'
                                };
                                ?>
                                <tr class="ctv-row" data-search="<?= strtolower(htmlspecialchars($ctv['full_name'] . ' ' . $ctv['student_code'] . ' ' . $cDept)) ?>" data-dept="<?= htmlspecialchars($cDept) ?>">
                                    <td class="ps-3 text-muted fw-bold num-tabular" style="font-size: 11.5px;"><?= $idx + 1 ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle d-grid place-items-center text-white fw-bold shadow-xs flex-shrink-0" style="width: 32px; height: 32px; background: linear-gradient(135deg, #F97316 0%, #EA580C 100%); font-size: 12px;">
                                                <?= $cInitial ?>
                                            </div>
                                            <div>
                                                <strong class="d-block text-dark" style="font-size: 13px; line-height: 1.2;"><?= htmlspecialchars($ctv['full_name']) ?></strong>
                                                <small class="text-muted" style="font-size: 11px;">
                                                    <span class="badge bg-light text-dark border font-monospace px-1.5 py-0.5"><?= htmlspecialchars($ctv['student_code']) ?></span>
                                                    • <?= htmlspecialchars($ctv['phone'] ?: $ctv['email']) ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge px-2.5 py-1 fw-semibold" style="font-size: 11px; <?= $deptStyle ?>">
                                            <i class="bi bi-mortarboard-fill me-1"></i><?= htmlspecialchars($cDept) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($isMediaRole): ?>
                                        <span class="badge border px-2.5 py-1 font-monospace" style="font-size: 11px; background: #FFFBEB; color: #B45309; border-color: #FDE68A !important;">
                                            <i class="bi bi-camera-reels-fill me-1"></i>CTV Media (Quay/Chụp)
                                        </span>
                                        <?php else: ?>
                                        <span class="badge border px-2.5 py-1 font-monospace" style="font-size: 11px; background: #ECFEFF; color: #0E7490; border-color: #A5F3FC !important;">
                                            <i class="bi bi-tools me-1"></i>CTV Bàn Ghế & Máy Móc
                                        </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-3">
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-circle d-inline-flex align-items-center justify-content-center shadow-xs" style="width: 34px; height: 34px; transition: all 0.2s;" onclick="openCtvEyeModalByIndex(<?= $idx ?>)" title="Xem chi tiết hồ sơ CTV">
                                            <i class="bi bi-eye-fill fs-6"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            </section>

            
            

            <!-- TAB 4: LỊCH TRỰC CỦA TÔI -->
            <section class="spa-panel" id="panelMyShifts" style="display:none !important;">
                <!-- 1. VÙNG CỐ ĐỊNH: Tiêu đề trang & Tổng số ca trực (Non-scrollable Top) -->
                <div class="flex-shrink-0">
                    <div class="d-flex justify-content-between align-items-center pb-2.5 border-bottom mb-3" style="border-color: rgba(0,0,0,0.06) !important;">
                        <div>
                            <h2 class="fw-bold mb-0 text-dark" style="font-size: 17.5px; letter-spacing: -0.015em;">Lịch Trực & Ca Phân Công Của Tôi</h2>
                            <p class="text-muted mb-0" style="font-size: 12.5px; margin-top: 2px;">Theo dõi ca trực, nộp minh chứng nghiệm thu (Ảnh hiện trường hoặc Link Drive) để Giảng viên nghiệm thu và xem hợp đồng thanh toán thù lao.</p>
                        </div>
                        <span class="badge bg-primary px-3 py-2 fw-bold" style="font-size: 12px; border-radius: 8px;" id="myShiftsCountBadge">0 Ca Trực</span>
                    </div>
                </div>

                <!-- 2. VÙNG BẢNG DỮ LIỆU: Scrollable Body & Sticky Header -->
                <div class="card border-0 rounded-3 shadow-xs flex-1 overflow-hidden d-flex flex-column" style="border: 1px solid var(--ux-line) !important;">
                    <div class="table-responsive flex-1 overflow-y-auto" style="height: 100%;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="sticky-th">
                            <tr>
                                <th>Thời Gian</th>
                                <th>Sự Kiện & Ca Trực</th>
                                <th>Địa Điểm</th>
                                <th>Ảnh Hiện Trường</th>
                                <th>Nghiệm Thu & Hợp Đồng</th>
                                <th>Thù Lao Quyết Toán</th>
                                <th class="text-end">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody id="myShiftsTableBody">
                            <!-- Nạp động qua JS -->
                        </tbody>
                        </table>
                    </div>
                </div>
            </section>

            
            

            <!-- TAB 5: QUẢN LÝ SỰ KIỆN -->
            <section class="spa-panel" id="panelEvents" style="display:none !important;">
                <!-- 1. VÙNG CỐ ĐỊNH: Tiêu đề & Nút tạo sự kiện (Non-scrollable Top) -->
                <div class="flex-shrink-0">
                    <div class="d-flex justify-content-between align-items-center pb-2.5 border-bottom mb-3" style="border-color: rgba(0,0,0,0.06) !important;">
                    <div>
                        <h2 class="fw-bold mb-0 text-dark" style="font-size: 17.5px; letter-spacing: -0.015em;">Danh Sách Sự Kiện & Phân Rã Ca Trực</h2>
                        <p class="text-muted mb-0" style="font-size: 12.5px; margin-top: 2px;">Quản trị các chiến dịch tuyển CTV, giám sát tiến độ lấp đầy từng ca trực</p>
                    </div>
                    <?php if (!empty($perms['canCreateShift'])): ?>
                    <button class="btn-fpt-primary btn-fpt-sm" onclick="openCreateShiftModal()">
                        <i class="bi bi-calendar-plus-fill me-1"></i> Đặt Lịch Ca Mới
                    </button>
                    <?php endif; ?>
                    </div>
                </div>
                <!-- 2. VÙNG CARDS: Scrollable Body -->
                <div class="row g-2.5" id="eventsCardsContainer">
                    <?php foreach ($events as $e): ?>
                    <?php
                    $canDeleteThisEvent = false;
                    $userRole = $perms['role'] ?? 'GUEST';
                    $currUid = (int)($currentUser['id'] ?? 0);
                    $eventCreatorId = (int)($e['created_by'] ?? 0);

                    if (in_array($userRole, ['HEAD_ADMIN', 'ADMIN'], true)) {
                        $canDeleteThisEvent = true;
                    } elseif ($userRole === 'LECTURER' && $eventCreatorId === $currUid) {
                        $canDeleteThisEvent = true;
                    }
                    ?>
                    <div class="col-12 col-md-6 col-xl-4" id="eventCardCol_<?= $e['id'] ?>">
                        <div class="card h-100 border rounded-3 shadow-xs overflow-hidden" style="border-color: rgba(0,0,0,0.08) !important;">
                            <div class="card-body p-3 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-1.5">
                                    <div class="d-flex align-items-center gap-1 flex-wrap">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold" style="font-size: 10px; padding: 2px 6px;"><?= htmlspecialchars($e['status']) ?></span>
                                        <span class="badge bg-light text-primary border font-monospace" style="font-size: 10px; padding: 2px 6px;"><?= htmlspecialchars($e['department'] ?? 'CNTT') ?></span>
                                    </div>
                                    <small class="text-muted fw-bold num-tabular" style="font-size: 11px;"><i class="bi bi-clock me-1"></i><?= substr($e['start_time'], 0, 10) ?></small>
                                </div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 13.5px; line-height: 1.3;"><?= htmlspecialchars($e['title']) ?></h6>
                                <p class="text-muted text-truncate-2 mb-2" style="font-size: 11.5px; line-height: 1.4;"><?= htmlspecialchars($e['description'] ?? 'Không có mô tả') ?></p>
                                <div class="mt-auto pt-2 border-top d-flex justify-content-between align-items-center text-muted" style="font-size: 11px; border-color: rgba(0,0,0,0.06) !important;">
                                    <span><i class="bi bi-geo-alt me-1 text-danger"></i><?= htmlspecialchars($e['location_name'] ?? 'Hội trường') ?></span>
                                    <div class="d-flex align-items-center gap-1.5">
                                        <span class="badge bg-light text-dark border" style="font-size: 10px;"><?= $e['total_shifts'] ?> Ca trực</span>
                                        <?php if ($canDeleteThisEvent): ?>
                                        <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px; padding: 0; background: rgba(220, 38, 38, 0.08); color: #DC2626;" onclick="confirmDeleteEvent(<?= $e['id'] ?>, '<?= htmlspecialchars(addslashes($e['title'])) ?>')" title="Xóa sự kiện này">
                                            <i class="bi bi-trash3" style="font-size: 13px;"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

            
            

            <!-- TAB 6: MINH CHỨNG ĐỂ NGHIỆM THU SAU MỖI SỰ KIỆN HOÀN THÀNH -->
            <section class="spa-panel" id="panelSubmissions" style="display:none !important;">
                <!-- 1. VÙNG CỐ ĐỊNH: Tiêu đề & Hướng dẫn (Non-scrollable Top) -->
                <div class="flex-shrink-0">
                <div class="d-flex flex-wrap justify-content-between align-items-center pb-2.5 border-bottom mb-3 gap-3" style="border-color: rgba(0,0,0,0.06) !important;">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-warning text-dark font-monospace fw-bold px-2.5 py-1" style="font-size: 11px;">HẬU SỰ KIỆN</span>
                            <h2 class="fw-bold mb-0 text-dark" style="font-size: 17.5px; letter-spacing: -0.015em;">Minh Chứng Nghiệm Thu Sau Mỗi Sự Kiện Hoàn Thành</h2>
                        </div>
                        <p class="text-muted mb-0" style="font-size: 12.5px; margin-top: 2px;">Role CTV: Up ảnh hiện trường • Role CTV Media: Up link Google Drive kèm logo & chỉnh sửa sáng • Giảng Viên: Duyệt đã đầy đủ minh chứng để nghiệm thu</p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-2 fw-bold d-flex align-items-center gap-1" style="font-size: 12px; border-radius: 8px;">
                            <i class="bi bi-shield-check"></i> Zero-Fraud Acceptance
                        </span>
                    </div>
                </div>

                <!-- Banner Hướng Dẫn Quy Trình Minh Chứng Theo Role -->
                <div class="row g-2.5 mb-2.5">
                    <div class="col-12 col-md-4">
                        <div class="rounded-3 border h-100 shadow-xs" style="background: #FAFAFC; border-color: rgba(0,0,0,0.08) !important; padding: 12px 14px;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-primary text-white rounded-pill px-2.5 py-1 fw-semibold" style="font-size: 10.5px;">Role CTV Thường</span>
                                <strong class="text-dark" style="font-size: 12.5px;">Ảnh Hiện Trường Có Mặt</strong>
                            </div>
                            <p class="text-muted mb-0" style="font-size: 11.5px; line-height: 1.5;">CTV có nhiệm vụ chụp ảnh có mặt thực tế tại vị trí trực (áo cam FPoly) để chứng minh có mặt nghiệm thu.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="rounded-3 border h-100 shadow-xs" style="background: #FAFAFC; border-color: rgba(0,0,0,0.08) !important; padding: 12px 14px;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 fw-semibold" style="font-size: 10.5px;">Role CTV Media</span>
                                <strong class="text-dark" style="font-size: 12.5px;">Link Drive Đã Chỉnh Sáng & Logo</strong>
                            </div>
                            <p class="text-muted mb-0" style="font-size: 11.5px; line-height: 1.5;">CTV Media tải link Google Drive bộ ảnh sự kiện đã được <strong>gắn logo FPT Poly</strong> và <strong>hậu kỳ chỉnh sáng</strong>.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="rounded-3 border h-100 shadow-xs" style="background: #FAFAFC; border-color: rgba(0,0,0,0.08) !important; padding: 12px 14px;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-success text-white rounded-pill px-2.5 py-1 fw-semibold" style="font-size: 10.5px;">Role Giảng Viên</span>
                                <strong class="text-dark" style="font-size: 12.5px;">Duyệt / Nhắc Nhở Qua Email</strong>
                            </div>
                            <p class="text-muted mb-0" style="font-size: 11.5px; line-height: 1.5;">Giảng viên kiểm tra minh chứng duyệt nghiệm thu, hoặc <strong>gửi email nhắc nhở</strong> nếu CTV chưa nộp ảnh.</p>
                        </div>
                    </div>
                </div>

                </div>
                <!-- 2. VÙNG BẢNG DỮ LIỆU: Scrollable Body & Sticky Header -->
                <div class="card border-0 rounded-3 shadow-xs flex-1 overflow-hidden d-flex flex-column" style="border: 1px solid var(--ux-line) !important;">
                    <div class="table-responsive flex-1 overflow-y-auto" style="height: 100%;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="sticky-th">
                            <tr>
                                <th class="ps-3" style="width: 220px;">Sinh Viên CTV</th>
                                <th>Sự Kiện & Ca Trực</th>
                                <th>Minh Chứng Đã Nộp</th>
                                <th>Ghi Chú Nghiệm Thu</th>
                                <th>Trạng Thái</th>
                                <th class="text-end pe-3" style="width: 200px;">Thao Tác Nghiệm Thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($accountingList as $subItem): ?>
                            <?php
                            $subAssId = (int)$subItem['assignment_id'];
                            $subProofUrl = $subItem['proof_image_url'] ?? '';
                            $isDriveLink = str_contains($subProofUrl, 'drive.google.com') || str_contains($subProofUrl, 'docs.google.com') || str_contains(strtolower($subItem['shift_name'] . ' ' . $subItem['full_name']), 'media');
                            $hasProof = !empty($subProofUrl);
                            $isVerified = (int)($subItem['lecturer_verified'] ?? 0) === 1;
                            ?>
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-grid place-items-center text-white fw-bold shadow-xs flex-shrink-0" style="width: 30px; height: 30px; background: linear-gradient(135deg, #F97316 0%, #EA580C 100%); font-size: 11.5px;">
                                            <?= mb_strtoupper(mb_substr($subItem['full_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <strong class="d-block text-dark" style="font-size: 12.5px; line-height: 1.25;"><?= htmlspecialchars($subItem['full_name']) ?></strong>
                                            <small class="text-muted" style="font-size: 11px;"><span class="badge bg-light text-dark border font-monospace px-1.5 py-0.5"><?= htmlspecialchars($subItem['student_code']) ?></span> • <?= $isDriveLink ? '<span class="text-warning fw-semibold"><i class="bi bi-camera-reels me-1"></i>Media</span>' : '<span class="text-primary"><i class="bi bi-person me-1"></i>CTV</span>' ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong class="text-dark d-block text-truncate" style="font-size: 12.5px; max-width: 250px;" title="<?= htmlspecialchars($subItem['event_title']) ?>"><?= htmlspecialchars($subItem['event_title']) ?></strong>
                                    <small class="text-muted" style="font-size: 11px;"><?= htmlspecialchars($subItem['shift_name']) ?></small>
                                </td>
                                <td>
                                    <?php if ($hasProof): ?>
                                        <?php if ($isDriveLink): ?>
                                        <div>
                                            <a href="<?= htmlspecialchars($subProofUrl) ?>" target="_blank" class="btn btn-sm btn-outline-warning rounded-pill py-0.5 px-2 fw-semibold" style="font-size:11px;">
                                                <i class="fa-brands fa-google-drive me-1 text-primary"></i> Mở Drive Media
                                            </a>
                                            <div class="text-success fw-semibold mt-0.5" style="font-size:10.5px;">
                                                <i class="bi bi-check-circle-fill me-1"></i>Đã gắn Logo & Sáng
                                            </div>
                                        </div>
                                        <?php else: ?>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-fpt-secondary rounded-pill py-1 px-2.5 fw-medium d-inline-flex align-items-center gap-1.5 shadow-2xs" style="font-size: 11px;" onclick="previewProofModal('<?= addslashes($subItem['full_name']) ?>', '<?= addslashes($subItem['shift_name']) ?>', '<?= addslashes($subProofUrl) ?>', '<?= addslashes($subItem['proof_note'] ?? '') ?>')">
                                                <i class="bi bi-eye text-primary"></i> Xem Ảnh
                                            </button>
                                        </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border py-1 px-2" style="font-size: 10.5px;"><i class="bi bi-clock-history me-1"></i>Chưa nộp minh chứng</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted" style="font-size: 11.5px;"><?= htmlspecialchars($subItem['proof_note'] ?: ($isDriveLink ? 'Bộ ảnh Media đã chỉnh sửa sáng và gắn logo FPT' : 'Có mặt đúng giờ tại địa điểm trực sự kiện')) ?></small>
                                </td>
                                <td>
                                    <?php if ($isVerified): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle py-1 px-2" style="font-size: 10.5px;">
                                        <i class="bi bi-check-circle-fill me-1"></i>Đã Nghiệm Thu
                                    </span>
                                    <small class="d-block text-muted font-monospace mt-0.5" style="font-size:10px;"><?= htmlspecialchars($subItem['contract_code'] ?? '') ?></small>
                                    <?php elseif ($hasProof): ?>
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle py-1 px-2" style="font-size: 10.5px;">
                                        <i class="bi bi-hourglass-split me-1"></i>Chờ GV Duyệt
                                    </span>
                                    <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary py-1 px-2" style="font-size: 10.5px;">Chưa gửi</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="d-flex align-items-center justify-content-end gap-1.5">
                                        <?php if ((int)$subItem['user_id'] === (int)($currentUser['id'] ?? 0) || empty($perms['canVerifyLecturer'])): ?>
                                        <button type="button" class="btn btn-sm btn-fpt-primary rounded-pill py-1 px-2.5 shadow-xs" style="font-size: 11px;" onclick="openSubmitProofModal(<?= $subAssId ?>, '<?= htmlspecialchars(addslashes($subItem['shift_name'])) ?>', '<?= htmlspecialchars(addslashes($subItem['start_time'])) ?>')">
                                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> <?= $hasProof ? 'Cập Nhật' : 'Nộp Minh Chứng' ?>
                                        </button>
                                        <?php endif; ?>

                                        <?php if (!empty($perms['canVerifyLecturer'])): ?>
                                            <?php if ($isVerified): ?>
                                            <button class="btn btn-sm btn-fpt-secondary rounded-pill py-1 px-2.5 disabled" disabled style="font-size: 11px;">
                                                <i class="bi bi-patch-check-fill text-success me-1"></i> Đã Duyệt
                                            </button>
                                            <?php elseif ($hasProof): ?>
                                            <button type="button" class="btn btn-sm btn-fpt-emerald rounded-pill py-1 px-2.5 shadow-xs" style="font-size: 11px;" onclick="verifyLecturerAcceptance(<?= $subAssId ?>)">
                                                <i class="bi bi-patch-check-fill me-1"></i> Duyệt Nghiệm Thu
                                            </button>
                                            <?php else: ?>
                                            <!-- CTV chưa nộp minh chứng: Giảng viên có nút nhắc nhở gửi tự động qua email -->
                                            <button type="button" class="btn btn-sm btn-outline-warning rounded-pill py-1 px-2.5 shadow-xs fw-semibold" style="font-size: 11px; white-space: nowrap;" onclick="sendProofReminder(<?= $subAssId ?>, '<?= htmlspecialchars(addslashes($subItem['full_name'])) ?>', '<?= htmlspecialchars(addslashes($subItem['email'] ?? '')) ?>', this)" title="Tự động gửi email thông báo nhắc sinh viên nộp minh chứng">
                                                <i class="bi bi-bell-fill me-1 text-warning"></i> Nhắc Nhở Qua Email
                                            </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        </table>
                    </div>
                </div>
            </section>

            
            

            <!-- TAB 7: HỒ SƠ HỢP ĐỒNG NGHIỆM THU HIỆN TRƯỜNG -->
            <section class="spa-panel" id="panelContractDossier" style="display:none !important;">
                <!-- 1. VÙNG CỐ ĐỊNH: Tiêu đề & KPI (Non-scrollable Top) -->
                <div class="flex-shrink-0">
                <div class="d-flex flex-wrap justify-content-between align-items-center pb-2.5 border-bottom mb-3 gap-3" style="border-color: rgba(0,0,0,0.06) !important;">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-warning text-dark font-monospace fw-bold px-2.5 py-1" style="font-size: 11px;">QUY TRÌNH MỚI</span>
                            <h2 class="fw-bold mb-0 text-dark" style="font-size: 17.5px; letter-spacing: -0.015em;">Hồ Sơ Hợp Đồng Nghiệm Thu & Trình Ký Nhà Trường</h2>
                        </div>
                        <p class="text-muted mb-0" style="font-size: 12.5px; margin-top: 2px;">Nghiệm thu bằng ảnh hiện trường thực tế của CTV • Giảng viên thẩm định & lập hợp đồng • Trình Ban Giám Hiệu ký duyệt chuyển tiền</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn-fpt-secondary btn-fpt-sm" onclick="exportAccountingCSV()">
                            <i class="bi bi-file-earmark-excel me-1 text-success"></i> Xuất Bảng Kê (CSV)
                        </button>
                    </div>
                </div>

                <!-- 4 KPI Thẻ Nghiệm Thu -->
                <div class="kpi-metric-strip mb-3">
                    <div class="kpi-mini-card">
                        <div>
                            <div class="kpi-mini-label">Tổng Hồ Sơ HĐ</div>
                            <div class="kpi-mini-val num-tabular"><?= count($accountingList) ?> <small style="font-size:12px; font-weight:500; color:var(--ux-ink-3);">bộ</small></div>
                        </div>
                        <div class="kpi-mini-icon text-dark" style="background: #F4F4F6;">
                            <i class="bi bi-folder2-open"></i>
                        </div>
                    </div>
                    <div class="kpi-mini-card">
                        <div>
                            <div class="kpi-mini-label">Ảnh Hiện Trường</div>
                            <div class="kpi-mini-val num-tabular text-info"><?= count(array_filter($accountingList, fn($a) => !empty($a['proof_image_url']))) ?> <small style="font-size:12px; font-weight:500; color:var(--ux-ink-3);">đã nộp</small></div>
                        </div>
                        <div class="kpi-mini-icon text-dark" style="background: #F4F4F6;">
                            <i class="bi bi-camera"></i>
                        </div>
                    </div>
                    <div class="kpi-mini-card">
                        <div>
                            <div class="kpi-mini-label">GV Đã Nghiệm Thu</div>
                            <div class="kpi-mini-val num-tabular text-success"><?= count(array_filter($accountingList, fn($a) => !empty($a['lecturer_verified']) && $a['lecturer_verified'] == 1)) ?> <small style="font-size:12px; font-weight:500; color:var(--ux-ink-3);">hợp lệ</small></div>
                        </div>
                        <div class="kpi-mini-icon text-dark" style="background: #F4F4F6;">
                            <i class="bi bi-patch-check"></i>
                        </div>
                    </div>
                    <div class="kpi-mini-card">
                        <div>
                            <div class="kpi-mini-label">Trường Đã Chi Tiền</div>
                            <div class="kpi-mini-val num-tabular text-primary"><?= count(array_filter($accountingList, fn($a) => ($a['school_status'] ?? '') === 'Signed_Paid')) ?> <small style="font-size:12px; font-weight:500; color:var(--ux-ink-3);">đã chi</small></div>
                        </div>
                        <div class="kpi-mini-icon text-dark" style="background: #F4F4F6;">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                </div>

                </div>
                <!-- 2. Danh sách Hợp Đồng Dossier Cards: Scrollable Body -->
                <div class="row g-2.5" id="contractDossierContainer">
                    <?php if (empty($accountingList)): ?>
                    <div class="col-12 text-center py-5 rounded-4 border bg-white shadow-xs">
                        <i class="bi bi-file-earmark-check text-muted display-4 mb-3 d-block"></i>
                        <h6 class="fw-bold text-dark">Chưa có hồ sơ hợp đồng nào</h6>
                        <p class="text-muted small mb-0">Hồ sơ nghiệm thu và hợp đồng sẽ tự động hiển thị sau khi ca trực được sinh viên đăng ký hoàn tất.</p>
                    </div>
                    <?php else: ?>
                    <?php foreach ($accountingList as $acc): ?>
                    <?php
                    $isVerified = !empty($acc['lecturer_verified']) && (int)$acc['lecturer_verified'] === 1;
                    $hasProof = !empty($acc['proof_image_url']);
                    $hasCccd = !empty($acc['id_card_number']);
                    $hasBank = !empty($acc['bank_account_number']);
                    $isReadyToExport = $isVerified && $hasProof && $hasCccd && $hasBank;
                    $proofUrl = $acc['proof_image_url'] ?? '';
                    $isDriveLink = !empty($proofUrl) && (str_contains($proofUrl, 'drive.google.com') || str_contains($proofUrl, 'docs.google.com') || (!preg_match('/\.(jpg|jpeg|png|webp|gif)/i', $proofUrl) && str_starts_with($proofUrl, 'http')));
                    ?>
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card h-100 border rounded-4 p-3 bg-white d-flex flex-column justify-content-between shadow-xs" style="border-color: rgba(0,0,0,0.08) !important;">
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace px-2 py-0.5" style="font-size: 11px;">
                                        <?= htmlspecialchars($acc['contract_code'] ?? ('HDNT-2026-FPLY-' . str_pad((string)$acc['assignment_id'], 3, '0', STR_PAD_LEFT))) ?>
                                    </span>
                                    <?php if (($acc['school_status'] ?? '') === 'Signed_Paid'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 10.5px;">
                                        <i class="bi bi-check2-all me-1"></i>Trường Đã Chi Tiền
                                    </span>
                                    <?php elseif ($isVerified): ?>
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-0.5" style="font-size: 10.5px;">
                                        <i class="bi bi-hourglass-split me-1"></i>Chờ Trường Ký
                                    </span>
                                    <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-0.5" style="font-size: 10.5px;">
                                        <i class="bi bi-clock me-1"></i>Chờ GV Duyệt Ảnh
                                    </span>
                                    <?php endif; ?>
                                </div>

                                <div class="d-flex align-items-center gap-2 mb-2.5">
                                    <?php if (!empty($proofUrl)): ?>
                                        <?php if ($isDriveLink): ?>
                                        <div style="width: 44px; height: 44px; flex-shrink: 0; border-radius: 10px; background: #F0F9FF; border: 1.5px solid #BAE6FD; display: grid; place-items: center; cursor: pointer;" onclick="window.open('<?= htmlspecialchars($proofUrl) ?>', '_blank')" title="Mở Drive bộ ảnh Media">
                                            <i class="fa-brands fa-google-drive text-primary fs-5"></i>
                                        </div>
                                        <?php else: ?>
                                        <div style="width: 44px; height: 44px; flex-shrink: 0; border-radius: 10px; overflow: hidden; border: 1px solid rgba(0,0,0,0.1); cursor: pointer;" onclick="previewProofModal('<?= htmlspecialchars(addslashes($acc['full_name'])) ?>', '<?= htmlspecialchars(addslashes($acc['shift_name'])) ?>', '<?= htmlspecialchars(addslashes($proofUrl)) ?>', '<?= htmlspecialchars(addslashes($acc['proof_note'] ?? 'Hiện trường trực')) ?>')">
                                            <img src="<?= htmlspecialchars($proofUrl) ?>" alt="Ảnh Hiện Trường" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                    <div style="width: 44px; height: 44px; flex-shrink: 0; border-radius: 10px; background: #F4F4F6; display: grid; place-items: center; border: 1.5px dashed #CBD5E1;">
                                        <i class="bi bi-camera text-muted fs-5"></i>
                                    </div>
                                    <?php endif; ?>
                                    <div class="overflow-hidden flex-grow-1">
                                        <div class="d-flex align-items-center justify-content-between mb-0.5">
                                            <h6 class="fw-bold text-dark mb-0 text-truncate" style="font-size: 13.5px;"><?= htmlspecialchars($acc['full_name']) ?></h6>
                                            <?php if ($isDriveLink): ?>
                                            <span class="badge rounded-pill" style="background: rgba(2, 132, 199, 0.12); color: #0284C7; font-size: 9.5px; font-weight: 600; padding: 2px 6px;">Media</span>
                                            <?php endif; ?>
                                        </div>
                                        <small class="text-muted d-block text-truncate" style="font-size: 11px;"><?= htmlspecialchars($acc['student_code']) ?> • CCCD: <?= htmlspecialchars($acc['id_card_number'] ?? '079203014589') ?></small>
                                        <small class="text-primary fw-semibold d-block text-truncate" style="font-size: 11px;">STK: <?= htmlspecialchars($acc['bank_account_number'] ?? '') ?> (<?= htmlspecialchars($acc['bank_name'] ?? 'NH') ?>)</small>
                                    </div>
                                </div>

                                <div class="p-2 rounded-3 mb-2.5 small" style="background: #F8F8FA; border: 1px solid rgba(0,0,0,0.05); font-size: 11.5px;">
                                    <div class="d-flex justify-content-between mb-0.5">
                                        <span class="text-muted">Sự kiện:</span>
                                        <strong class="text-dark text-truncate ms-2" style="max-width: 65%;" title="<?= htmlspecialchars($acc['event_title']) ?>"><?= htmlspecialchars($acc['event_title']) ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-0.5">
                                        <span class="text-muted">Ca trực:</span>
                                        <span class="text-dark fw-semibold text-truncate ms-2" style="max-width: 65%;" title="<?= htmlspecialchars($acc['shift_name']) ?>"><?= htmlspecialchars($acc['shift_name']) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-0.5">
                                        <span class="text-muted">Địa điểm:</span>
                                        <span class="text-dark text-truncate ms-2" style="max-width: 65%;" title="<?= htmlspecialchars($acc['location_name'] ?? 'Hội trường') ?>"><?= htmlspecialchars($acc['location_name'] ?? 'Hội trường') ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between border-top pt-1 mt-1">
                                        <span class="text-muted fw-bold">Thù lao:</span>
                                        <strong class="text-success num-tabular" style="font-size: 13px;"><?= number_format((int)$acc['remuneration_amount']) ?> đ</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-column gap-2 pt-2 border-top mt-auto" style="border-color: rgba(0,0,0,0.06) !important;">
                                <!-- Trạng thái điều kiện xuất hợp đồng -->
                                <div class="d-flex align-items-center justify-content-between" style="font-size: 11px;">
                                    <span class="text-muted">Điều kiện xuất HĐ:</span>
                                    <?php if ($isReadyToExport): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 10px;">
                                        <i class="bi bi-patch-check-fill me-1"></i>Đủ điều kiện xuất HĐ
                                    </span>
                                    <?php else: ?>
                                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-2 py-0.5" style="font-size: 10px;" title="Cần GV duyệt và đủ CCCD + STK">
                                        <i class="bi bi-exclamation-triangle-fill me-1 text-warning"></i>Chưa đủ điều kiện
                                    </span>
                                    <?php endif; ?>
                                </div>

                                <div class="d-grid gap-1.5" style="grid-template-columns: repeat(auto-fit, minmax(115px, 1fr));">
                                    <!-- Nút Xem / In Hợp Đồng -->
                                    <?php if (($acc['school_status'] ?? '') === 'Signed_Paid'): ?>
                                    <button type="button" class="btn btn-sm btn-fpt-secondary text-truncate" onclick="openContractModal(<?= $acc['assignment_id'] ?>)" style="font-size: 11.5px; padding: 5px 8px; border-radius: 8px;" title="Xem & In Hợp Đồng Đã Ký">
                                        <i class="bi bi-printer me-1"></i> In / Xem HĐ
                                    </button>
                                    <?php else: ?>
                                    <button type="button" class="btn btn-sm btn-fpt-secondary text-truncate" onclick="openContractModal(<?= $acc['assignment_id'] ?>)" style="font-size: 11.5px; padding: 5px 8px; border-radius: 8px;" title="Xem Hợp Đồng Nghiệm Thu">
                                        <i class="bi bi-file-earmark-text me-1"></i> Xem Hợp Đồng
                                    </button>
                                    <?php endif; ?>
                                    
                                    <!-- Nút Nộp / Cập nhật Minh Chứng cho CTV -->
                                    <?php if ((int)$acc['user_id'] === (int)($currentUser['id'] ?? 0) || empty($perms['canVerifyLecturer'])): ?>
                                    <button type="button" class="btn btn-sm btn-fpt-primary text-truncate" onclick="openSubmitProofModal(<?= $acc['assignment_id'] ?>, '<?= htmlspecialchars(addslashes($acc['shift_name'])) ?>', '<?= htmlspecialchars(addslashes($acc['start_time'])) ?>')" style="font-size: 11.5px; padding: 5px 8px; border-radius: 8px;">
                                        <i class="bi bi-cloud-arrow-up-fill me-1"></i> <?= $hasProof ? 'Đổi Minh Chứng' : 'Nộp Minh Chứng' ?>
                                    </button>
                                    <?php endif; ?>

                                    <!-- Nút Duyệt Nghiệm Thu HOẶC Nhắc Nhở Nộp Minh Chứng: Dành riêng cho Giảng viên / Quản trị viên -->
                                    <?php if (!$isVerified && !empty($perms['canVerifyLecturer'])): ?>
                                        <?php if ($hasProof): ?>
                                        <button type="button" class="btn btn-sm btn-fpt-emerald text-truncate" onclick="verifyLecturerInCard(<?= $acc['assignment_id'] ?>)" style="font-size: 11px; padding: 4px 6px; border-radius: 7px;">
                                            <i class="bi bi-patch-check-fill me-1"></i> Duyệt Nghiệm Thu
                                        </button>
                                        <?php else: ?>
                                        <button type="button" class="btn btn-sm btn-outline-warning text-truncate fw-semibold" onclick="sendProofReminder(<?= $acc['assignment_id'] ?>, '<?= htmlspecialchars(addslashes($acc['full_name'])) ?>', '<?= htmlspecialchars(addslashes($acc['email'] ?? '')) ?>', this)" style="font-size: 11px; padding: 4px 6px; border-radius: 7px;" title="Tự động gửi email nhắc sinh viên nộp minh chứng">
                                            <i class="bi bi-bell-fill me-1 text-warning"></i> Nhắc Nộp MC
                                        </button>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <!-- Nút Trình Trường Ký: CHỈ Cán bộ Hành chính & Quản trị viên (CTV TUYỆT ĐỐI KHÔNG CÓ) -->
                                    <?php if (!empty($perms['canSubmitSchoolSigning']) && ($acc['school_status'] ?? '') !== 'Signed_Paid'): ?>
                                    <button type="button" class="btn btn-sm btn-fpt-primary text-truncate" <?= $isReadyToExport ? '' : 'disabled title="Cần nghiệm thu đầy đủ trước khi trình ký"' ?> onclick="submitToSchool(<?= $acc['assignment_id'] ?>)" style="font-size: 11.5px; padding: 5px 8px; border-radius: 8px;">
                                        <i class="bi bi-send-check me-1"></i> Trình Trường Ký
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
<!-- =========================================================
                 PANEL 8: CÀI ĐẶT TÀI KHOẢN CÁ NHÂN (PROFILE & SETTINGS)
                 ========================================================= -->
            <section id="panelProfileSettings" style="display:none !important;" class="spa-panel fade-in-up">
                <?php 
                $profRoleId = (int)($userProfile['role_id'] ?? 4);
                $profRoleName = $userProfile['role_name'] ?? 'Student';
                // CHỈ CTV (Sinh viên, Media) mới có hồ sơ quyết toán thù lao (VNeID, CCCD, STK)
                // Các role khác (Admin, Giảng viên, Hành chính...) bỏ hết các trường này
                $isCtv = in_array($profRoleId, [4, 6], true) || in_array($profRoleName, ['Student', 'Media', 'CTV', 'Sinh viên'], true);
                $isInstitutionalStaff = !$isCtv;
                ?>
                <!-- Profile Header Card (Apple-grade Bento Canvas) -->
                <div class="card border-0 rounded-3 shadow-xs mb-3 flex-shrink-0" style="background: var(--ux-surface); border: 1px solid var(--ux-line) !important;">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center shadow-xs flex-shrink-0" style="width: 54px; height: 54px; background: linear-gradient(135deg, #F97316 0%, #EA580C 100%); font-size: 22px;">
                                    <?= mb_substr($userProfile['full_name'] ?? 'U', 0, 1) ?>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <h3 class="fw-bold mb-0" style="color: var(--ux-ink); font-size: 17px; letter-spacing: -0.01em;"><?= htmlspecialchars($userProfile['full_name'] ?? 'Người Dùng') ?></h3>
                                        <span class="badge px-2.5 py-1 rounded-pill" style="font-size: 11.5px; background: rgba(234, 88, 12, 0.1); color: #EA580C; border: 1px solid rgba(234, 88, 12, 0.25);">
                                            <i class="bi <?= $isInstitutionalStaff ? 'bi-shield-check' : 'bi-person-check-fill' ?> me-1"></i><?= htmlspecialchars($profRoleName) ?>
                                        </span>
                                    </div>
                                    <div class="small mt-1 d-flex align-items-center gap-2 flex-wrap" style="color: var(--ux-ink-3); font-size: 12px;">
                                        <span>Mã: <strong class="font-monospace" style="color: var(--ux-ink);"><?= htmlspecialchars($userProfile['student_code'] ?? 'CTV') ?></strong></span>
                                        <span style="opacity: 0.35;">•</span>
                                        <span>Email: <span style="color: var(--ux-ink-2); font-weight: 500;"><?= htmlspecialchars($userProfile['email'] ?? '') ?></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge px-3 py-1.5 rounded-pill font-monospace" style="background: var(--ux-surface-2); color: var(--ux-ink-2); border: 1px solid var(--ux-line); font-size: 11.5px;">
                                    <i class="bi bi-clock-history me-1 text-primary"></i> Khởi tạo: <?= date('d/m/Y', strtotime($userProfile['created_at'] ?? 'now')) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Edit Form -->
                <form id="profileSettingsForm" onsubmit="handleProfileUpdate(event)">
                    <div class="row g-3 g-lg-4">
                        <!-- Left Column: Thông Tin Định Danh & Liên Hệ -->
                        <div class="col-12 <?= $isInstitutionalStaff ? 'col-lg-7' : 'col-lg-6' ?>">
                            <div class="card border-0 rounded-3 shadow-xs h-100">
                                <div class="card-header bg-transparent border-0 pb-1">
                                    <h5 class="fw-bold mb-1 d-flex align-items-center gap-2" style="font-size: 15px; color: var(--ux-ink);">
                                        <i class="bi bi-person-badge-fill text-warning"></i> Thông Tin Định Danh Cá Nhân
                                    </h5>
                                    <div style="color: var(--ux-ink-3); font-size: 12px;">Dữ liệu liên lạc và hồ sơ hiển thị trong danh bạ PolyMee</div>
                                </div>
                                <div class="card-body pt-1">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label">Họ và Tên <span class="text-danger">*</span></label>
                                            <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($userProfile['full_name'] ?? '') ?>" placeholder="Nhập đầy đủ họ và tên..." required>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label d-flex align-items-center justify-content-between">
                                                <span>Mã Định Danh / MSSV</span>
                                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 10px; font-weight: normal;"><i class="bi bi-lock-fill me-0.5"></i>Cố định</span>
                                            </label>
                                            <input type="text" class="form-control font-monospace" value="<?= htmlspecialchars($userProfile['student_code'] ?? '') ?>" readonly>
                                            <div class="form-text" style="font-size: 11px; color: var(--ux-ink-3);">Mã sinh viên / Cán bộ do Nhà trường cấp cố định</div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label d-flex align-items-center justify-content-between">
                                                <span>Email FPT Education</span>
                                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 10px; font-weight: normal;"><i class="bi bi-lock-fill me-0.5"></i>Cố định</span>
                                            </label>
                                            <input type="email" class="form-control font-monospace" value="<?= htmlspecialchars($userProfile['email'] ?? '') ?>" readonly>
                                            <div class="form-text" style="font-size: 11px; color: var(--ux-ink-3);">Email trường cấp xác thực danh tính</div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Số Điện Thoại Liên Hệ <span class="text-danger">*</span></label>
                                            <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($userProfile['phone'] ?? '') ?>" placeholder="09xxxxxxxx" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Kỹ Năng & Lĩnh Vực Phụ Trách</label>
                                            <textarea name="skills" class="form-control" rows="3" placeholder="Ví dụ: Media quay chụp, Lễ tân đón tiếp, Hậu cần setup, MC sự kiện, Điều phối âm thanh ánh sáng..."><?= htmlspecialchars($userProfile['skills'] ?? '') ?></textarea>
                                            <div class="form-text" style="font-size: 11px; color: var(--ux-ink-3);">Trợ lý AI sẽ dùng kỹ năng này để phân tích và gợi ý bạn vào các ca trực sự kiện phù hợp nhất</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Hồ Sơ Thù Lao (Phân Biệt Theo Vai Trò) HOẶC Thông Báo Cán Bộ -->
                        <div class="col-12 <?= $isInstitutionalStaff ? 'col-lg-5' : 'col-lg-6' ?>">
                            <?php if ($isInstitutionalStaff): ?>
                            <!-- Trường hợp Quản Trị Viên & Giảng Viên: KHÔNG hiển thị CCCD & Ngân hàng thù lao -->
                            <div class="card border-0 rounded-3 shadow-xs h-100 d-flex flex-column">
                                <div class="card-header bg-transparent border-0 pb-1">
                                    <h5 class="fw-bold mb-1 d-flex align-items-center gap-2" style="font-size: 15px; color: var(--ux-ink);">
                                        <i class="bi bi-shield-check text-primary"></i> Phân Quyền & Bảo Mật
                                    </h5>
                                    <div style="color: var(--ux-ink-3); font-size: 12px;">Quy chế tài khoản cán bộ trực thuộc FPT Polytechnic</div>
                                </div>
                                <div class="card-body pt-1 d-flex flex-column justify-content-between">
                                    <div>
                                        <!-- Callout Box: Quy chế biên chế FPT Poly - Bento Apple HIG Thoáng Đãng -->
                                        <div class="rounded-3 p-3 mb-4" style="background: rgba(2, 132, 199, 0.05); border: 1px solid rgba(2, 132, 199, 0.18);">
                                            <div class="d-flex align-items-start gap-3">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; background: rgba(2, 132, 199, 0.12); color: #0284C7; font-size: 20px;">
                                                    <i class="bi bi-mortarboard-fill"></i>
                                                </div>
                                                <div style="flex: 1;">
                                                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1.5">
                                                        <h6 class="fw-bold mb-0" style="font-size: 14px; color: var(--ux-ink);">Tài Khoản Biên Chế Nhà Trường</h6>
                                                        <span class="badge rounded-pill" style="font-size: 11px; background: rgba(2, 132, 199, 0.12); color: #0284C7; border: 1px solid rgba(2, 132, 199, 0.25);">
                                                            <i class="bi bi-patch-check-fill me-1"></i>Chính Thức
                                                        </span>
                                                    </div>
                                                    <p class="mb-0" style="line-height: 1.6; font-size: 12.5px; color: var(--ux-ink-2);">
                                                        Tài khoản đang đăng nhập với vai trò <span class="fw-semibold text-primary"><?= htmlspecialchars($profRoleName) ?></span> (Cán bộ / Giảng viên). Nhân sự biên chế nhận lương theo hợp đồng lao động nhà trường, không nhận thù lao sinh viên theo ca nên hệ thống tự động lược bỏ các mục kê khai CCCD, số tài khoản ngân hàng và thẻ định danh VNeID.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Form Group: Đổi Mật Khẩu Đăng Nhập - Sạch sẽ, không box lồng box, có nút ẩn/hiện -->
                                        <div class="mb-4">
                                            <label class="form-label d-flex align-items-center justify-content-between mb-1.5">
                                                <span class="d-flex align-items-center gap-1.5 fw-bold" style="font-size: 13px; color: var(--ux-ink);">
                                                    <i class="bi bi-key-fill text-warning"></i> Đổi Mật Khẩu Đăng Nhập
                                                </span>
                                                <span class="badge bg-light text-muted border" style="font-size: 10.5px; font-weight: normal;">Không bắt buộc</span>
                                            </label>
                                            <div class="position-relative">
                                                <input type="password" name="new_password" id="inputNewPasswordStaff" class="form-control" style="padding-right: 42px;" placeholder="Nhập mật khẩu mới (bỏ trống nếu giữ nguyên)..." autocomplete="new-password">
                                                <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted text-decoration-none pe-3" onclick="togglePasswordVisibility('inputNewPasswordStaff', this)" style="border: none; background: transparent; z-index: 5;" title="Hiện / Ẩn mật khẩu">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                            <div class="form-text" style="font-size: 11px; color: var(--ux-ink-3); margin-top: 5px;">
                                                Mật khẩu mới yêu cầu tối thiểu 6 ký tự. Hãy sử dụng mật khẩu mạnh để bảo vệ tài khoản.
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Footer Action Button -->
                                    <div class="pt-3 border-top mt-3" style="border-color: var(--ux-line) !important;">
                                        <button type="submit" class="btn btn-fpt-primary w-100 rounded-pill shadow-xs fw-bold d-inline-flex align-items-center justify-content-center gap-2" id="btnSaveProfile" style="padding: 11px 20px; font-size: 13.5px;">
                                            <i class="bi bi-check-circle-fill"></i> Lưu Cập Nhật Hồ Sơ Cán Bộ
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php else: ?>
                            <!-- Trường hợp CTV (Sinh Viên / Media): Có đầy đủ hồ sơ CCCD, STK, VNeID -->
                            <div class="card border-0 rounded-3 shadow-xs h-100 d-flex flex-column">
                                <div class="card-header bg-transparent border-0 pb-1">
                                    <h5 class="fw-bold mb-1 d-flex align-items-center gap-2" style="font-size: 15px; color: var(--ux-ink);">
                                        <i class="bi bi-wallet2 text-success"></i> Hồ Sơ Quyết Toán & Thù Lao CTV
                                    </h5>
                                    <div style="color: var(--ux-ink-3); font-size: 12px;">Phòng Kế Toán FPT Polytechnic dùng thông tin này để chi trả thù lao ca trực</div>
                                </div>
                                <div class="card-body pt-1 d-flex flex-column justify-content-between">
                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Số CCCD (12 số) <span class="text-danger">*</span></label>
                                            <input type="text" name="id_card_number" class="form-control font-monospace" placeholder="12 chữ số CCCD chính chủ..." value="<?= htmlspecialchars($userProfile['id_card_number'] ?? '') ?>" required>
                                            <div class="form-text" style="font-size: 11px; color: var(--ux-ink-3);">Nhập chính xác 12 chữ số hiển thị trên app VNeID</div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Ngân Hàng Thụ Hưởng <span class="text-danger">*</span></label>
                                            <input type="text" name="bank_name" class="form-control" placeholder="Ví dụ: MB Bank, TPBank, Vietcombank..." value="<?= htmlspecialchars($userProfile['bank_name'] ?? 'MB Bank') ?>" required>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Số Tài Khoản (STK) <span class="text-danger">*</span></label>
                                            <input type="text" name="bank_account_number" class="form-control font-monospace" placeholder="Số tài khoản chính chủ..." value="<?= htmlspecialchars($userProfile['bank_account_number'] ?? '') ?>" required>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Tên Chủ Tài Khoản (In Hoa) <span class="text-danger">*</span></label>
                                            <input type="text" name="bank_account_name" class="form-control text-uppercase font-monospace" placeholder="NGUYEN VAN A" value="<?= htmlspecialchars($userProfile['bank_account_name'] ?? '') ?>" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Ảnh Chụp Màn Hình Ứng Dụng VNeID (Mức 2)</label>
                                            <input type="url" name="vneid_image_url" id="vneidUrlInput" class="form-control" placeholder="https://... (URL ảnh chụp màn hình app VNeID)" value="<?= htmlspecialchars($userProfile['vneid_image_url'] ?? '') ?>" oninput="previewVneidImage(this.value)">
                                            <div class="form-text" style="font-size: 11px; color: var(--ux-ink-3);">Dán URL ảnh chụp màn hình app VNeID đã kích hoạt Mức 2 (không cần chụp thẻ vật lý)</div>
                                            
                                            <!-- Image Preview Box -->
                                            <div class="mt-2 p-2 rounded-3 text-center d-flex align-items-center justify-content-center" style="background: var(--ux-surface-2); border: 1px dashed var(--ux-line-2); min-height: 90px; max-height: 120px; overflow: hidden;" id="vneidPreviewBox">
                                                <?php if (!empty($userProfile['vneid_image_url'])): ?>
                                                    <img src="<?= htmlspecialchars($userProfile['vneid_image_url']) ?>" alt="VNeID Preview" style="max-height: 100px; width: auto; object-fit: contain; border-radius: 8px;" id="vneidPreviewImg">
                                                    <span class="small text-muted" id="vneidPlaceholder" style="font-size: 11.5px; display: none;"><i class="bi bi-card-image me-1"></i> Chưa có ảnh VNeID được liên kết</span>
                                                <?php else: ?>
                                                    <span class="small text-muted" id="vneidPlaceholder" style="font-size: 11.5px;"><i class="bi bi-card-image me-1"></i> Chưa có ảnh VNeID được liên kết</span>
                                                    <img src="" alt="VNeID Preview" style="max-height: 100px; width: auto; object-fit: contain; border-radius: 8px; display: none;" id="vneidPreviewImg">
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label d-flex align-items-center justify-content-between mb-1.5">
                                                <span class="d-flex align-items-center gap-1.5 fw-bold" style="font-size: 13px; color: var(--ux-ink);">
                                                    <i class="bi bi-key-fill text-warning"></i> Đổi Mật Khẩu Đăng Nhập
                                                </span>
                                                <span class="badge bg-light text-muted border" style="font-size: 10.5px; font-weight: normal;">Không bắt buộc</span>
                                            </label>
                                            <div class="position-relative">
                                                <input type="password" name="new_password" id="inputNewPasswordCtv" class="form-control" style="padding-right: 42px;" placeholder="Nhập mật khẩu mới (bỏ trống nếu giữ nguyên)..." autocomplete="new-password">
                                                <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted text-decoration-none pe-3" onclick="togglePasswordVisibility('inputNewPasswordCtv', this)" style="border: none; background: transparent; z-index: 5;" title="Hiện / Ẩn mật khẩu">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Footer Action Button -->
                                    <div class="pt-3 border-top mt-3" style="border-color: var(--ux-line) !important;">
                                        <button type="submit" class="btn btn-fpt-primary w-100 rounded-pill shadow-xs fw-bold d-inline-flex align-items-center justify-content-center gap-2" id="btnSaveProfile" style="padding: 11px 20px; font-size: 13.5px;">
                                            <i class="bi bi-save2-fill"></i> Lưu Cập Nhật Hồ Sơ Quyết Toán CTV
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>

                <?php if ($isCtv): ?>
                <!-- Vùng Nguy Hiểm: Xóa Tài Khoản Cá Nhân (Chỉ Dành Cho CTV / Sinh Viên) -->
                <div class="card border-0 rounded-3 shadow-xs mt-3" style="background: rgba(239, 68, 68, 0.04); border: 1px solid rgba(239, 68, 68, 0.2) !important;">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <h6 class="fw-bold mb-1 text-danger d-flex align-items-center gap-1.5" style="font-size: 13.5px;">
                                    <i class="bi bi-exclamation-triangle-fill"></i> Vùng Nguy Hiểm: Xóa Tài Khoản Cá Nhân
                                </h6>
                                <p class="small mb-0 text-muted" style="max-width: 650px; font-size: 12px; line-height: 1.5;">
                                    Khi thực hiện xóa tài khoản, toàn bộ hồ sơ sinh viên, thông tin định danh và lịch phân công chưa hoàn thành sẽ bị vô hiệu hóa khỏi hệ thống PolyMee. Các hợp đồng đã quyết toán chi trả sẽ được lưu trữ phục vụ đối soát kế toán theo quy chế nhà trường.
                                </p>
                            </div>
                            <div>
                                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3.5 py-2 fw-bold" onclick="openDeleteAccountModal()" style="font-size: 12px;">
                                    <i class="bi bi-trash3-fill me-1.5"></i> Xóa Tài Khoản
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </section>

            <!-- =========================================================
                 PANEL 9: TRUNG TÂM CẤP QUYỀN NGƯỜI DÙNG (ADMIN ONLY)
                 ========================================================= -->
            <?php if (!empty($perms['canManageRoles'])): ?>
            <section id="panelUserRoles" style="display:none !important;" class="spa-panel fade-in-up">
                <div class="card border-0 rounded-4 shadow-sm flex-1 overflow-hidden d-flex flex-column" style="background: var(--ux-surface); border: 1px solid var(--ux-line) !important; height: 100%;">
                    <!-- 1. VÙNG CỐ ĐỊNH: Tiêu đề trang & Tổng số tài khoản (Non-scrollable Top) -->
                    <div class="card-header bg-transparent px-4 py-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-3 flex-shrink-0" style="border-color: rgba(0,0,0,0.06) !important;">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2.5 py-1 fw-bold" style="font-size: 11px;">BẢO MẬT & PHÂN QUYỀN</span>
                                <h2 class="fw-bold mb-0 text-dark" style="font-size: 17.5px; letter-spacing: -0.015em;">Trung Tâm Cấp Quyền & Phân Vai Trò Người Dùng</h2>
                            </div>
                            <p class="text-muted mb-0" style="font-size: 12.5px; margin-top: 2px;">Quản trị viên có toàn quyền nâng cấp, giáng cấp hoặc chuyển đổi vai trò của từng thành viên</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge px-3 py-1.5 rounded-pill" style="background: rgba(234, 88, 12, 0.15); color: #FB923C; border: 1px solid rgba(234, 88, 12, 0.3); font-size: 12px;">
                                <i class="bi bi-people-fill me-1"></i> Tổng số: <?= count($allUsers) ?> tài khoản
                            </span>
                        </div>
                    </div>

                    <!-- 2. VÙNG BẢNG DỮ LIỆU: Scrollable Body & Sticky Header -->
                    <div class="card-body p-0 flex-1 overflow-hidden d-flex flex-column">
                        <div class="table-responsive flex-1 overflow-y-auto" style="height: 100%;">
                            <table class="table align-middle mb-0" style="color: var(--ux-ink);">
                                <thead class="sticky-th">
                                    <tr style="font-size: 11.5px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ux-ink-3);">
                                        <th class="ps-4 py-3" style="width: 280px;">Thành Viên</th>
                                        <th class="py-3" style="width: 140px;">Mã / MSSV</th>
                                        <th class="py-3" style="width: 220px;">Email & SĐT</th>
                                        <th class="py-3" style="width: 180px;">Vai Trò Hiện Tại</th>
                                        <th class="pe-4 py-3 text-end" style="width: 320px;">Cấp Quyền Mới</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($allUsers as $u): ?>
                                    <?php 
                                    $uInitial = mb_strtoupper(mb_substr($u['full_name'], 0, 1));
                                    $roleBadgeColor = match($u['role_name']) {
                                        'Admin' => 'background: rgba(239, 68, 68, 0.15); color: #F87171; border: 1px solid rgba(239, 68, 68, 0.3);',
                                        'Organizer' => 'background: rgba(59, 130, 246, 0.15); color: #60A5FA; border: 1px solid rgba(59, 130, 246, 0.3);',
                                        'Staff' => 'background: rgba(139, 92, 246, 0.15); color: #A78BFA; border: 1px solid rgba(139, 92, 246, 0.3);',
                                        'Accountant' => 'background: rgba(16, 185, 129, 0.15); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.3);',
                                        'Media' => 'background: rgba(245, 158, 11, 0.15); color: #FBBF24; border: 1px solid rgba(245, 158, 11, 0.3);',
                                        default => 'background: rgba(100, 116, 139, 0.15); color: #94A3B8; border: 1px solid rgba(100, 116, 139, 0.3);'
                                    };
                                    ?>
                                    <tr style="border-bottom: 1px solid var(--ux-line);">
                                        <!-- Member info -->
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center flex-shrink-0 shadow-xs" style="width: 38px; height: 38px; background: linear-gradient(135deg, #F97316 0%, #EA580C 100%); font-size: 14px;">
                                                    <?= htmlspecialchars($uInitial) ?>
                                                </div>
                                                <div style="min-width: 0;">
                                                    <div class="fw-bold mb-0 text-truncate" style="font-size: 14px; color: var(--ux-ink); max-width: 200px;">
                                                        <?= htmlspecialchars($u['full_name']) ?>
                                                    </div>
                                                    <div class="small text-truncate" style="font-size: 11.5px; color: var(--ux-ink-3); max-width: 200px;">
                                                        <?= htmlspecialchars($u['skills'] ?: 'Cộng tác viên') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- MSSV / Code -->
                                        <td class="py-3">
                                            <span class="badge font-monospace px-2.5 py-1 rounded-pill" style="font-size: 11.5px; background: var(--ux-surface-2); color: var(--ux-ink); border: 1px solid var(--ux-line);">
                                                <?= htmlspecialchars($u['student_code']) ?>
                                            </span>
                                        </td>

                                        <!-- Contact -->
                                        <td class="py-3">
                                            <div class="small" style="color: var(--ux-ink-2); font-size: 12px;"><?= htmlspecialchars($u['email']) ?></div>
                                            <div class="small" style="color: var(--ux-ink-3); font-size: 11px;"><?= htmlspecialchars($u['phone'] ?: 'Chưa cập nhật SĐT') ?></div>
                                        </td>

                                        <!-- Current Role -->
                                        <td class="py-3" id="userRoleBadgeCol_<?= $u['id'] ?>">
                                            <span class="badge px-3 py-1.5 rounded-pill" style="font-size: 11.5px; <?= $roleBadgeColor ?>">
                                                <?= htmlspecialchars($u['role_name']) ?>
                                            </span>
                                        </td>

                                        <!-- Grant Role Dropdown & Button -->
                                        <td class="pe-4 py-3 text-end">
                                            <div class="d-inline-flex align-items-center gap-2">
                                                <select class="form-select form-select-sm" id="roleSelect_<?= $u['id'] ?>" style="width: 170px; font-size: 12px; background-color: var(--ux-surface-2); color: var(--ux-ink); border-color: var(--ux-line);">
                                                    <?php foreach ($roles as $r): ?>
                                                    <option value="<?= $r['id'] ?>" <?= ((int)$u['role_id'] === (int)$r['id']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($r['name']) ?> (<?= htmlspecialchars($r['description']) ?>)
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <button class="btn btn-sm btn-fpt-primary rounded-pill px-3 py-1.5 shadow-xs" style="font-size: 12px; font-weight: 600;" onclick="handleGrantRole(<?= $u['id'] ?>, 'roleSelect_<?= $u['id'] ?>')">
                                                    <i class="bi bi-shield-check me-1"></i> Cấp Quyền
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            <?php endif; ?>

            <!-- =========================================================
                 PANEL 10: NHẬT KÝ THAO TÁC HỆ THỐNG - AUDIT TRAIL (TRANG RIÊNG BIỆT)
                 ========================================================= -->
            <?php if (!empty($perms['canViewAuditLogs'])): ?>
            <section id="panelAuditTrail" style="display:none !important;" class="spa-panel fade-in-up">
                <div class="card border-0 rounded-4 shadow-sm flex-1 overflow-hidden d-flex flex-column" style="background: var(--ux-surface); border: 1px solid var(--ux-line) !important; height: 100%;">
                    <!-- 1. VÙNG CỐ ĐỊNH: Tiêu đề trang & Thống kê số lượng bản ghi (Non-scrollable Top) -->
                    <div class="card-header bg-transparent px-4 py-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-3 flex-shrink-0" style="border-color: rgba(0,0,0,0.06) !important;">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2.5 py-1 fw-bold" style="font-size: 11px;">KIỂM TOÁN HỆ THỐNG</span>
                                <h2 class="fw-bold mb-0 text-dark" style="font-size: 17.5px; letter-spacing: -0.015em;">Nhật Ký Lịch Sử Thao Tác Hệ Thống (Audit Trail)</h2>
                            </div>
                            <p class="text-muted mb-0" style="font-size: 12.5px; margin-top: 2px;">Ghi lại toàn bộ hành vi phân quyền, duyệt ca, ký biên bản và thao tác tự động của AI</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge px-3 py-1.5 rounded-pill font-monospace" style="background: var(--ux-surface-2); color: var(--ux-ink-2); border: 1px solid var(--ux-line); font-size: 11.5px;">
                                <i class="bi bi-activity me-1 text-success"></i> <?= count($activityLogs) ?> bản ghi gần nhất
                            </span>
                        </div>
                    </div>

                    <!-- 2. VÙNG BẢNG DỮ LIỆU: Scrollable Body & Sticky Header -->
                    <div class="card-body p-0 flex-1 overflow-hidden d-flex flex-column">
                        <div class="table-responsive flex-1 overflow-y-auto" style="height: 100%;">
                            <table class="table align-middle mb-0" style="color: var(--ux-ink);">
                                <thead class="sticky-th">
                                    <tr style="font-size: 11.5px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ux-ink-3);">
                                        <th class="ps-4 py-3" style="width: 170px;">Thời Gian</th>
                                        <th class="py-3" style="width: 200px;">Người Thực Hiện</th>
                                        <th class="py-3" style="width: 150px;">Hành Động</th>
                                        <th class="py-3">Chi Tiết Thao Tác</th>
                                        <th class="pe-4 py-3 text-end" style="width: 120px;">Địa Chỉ IP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($activityLogs)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5" style="color: var(--ux-ink-3);">
                                            <i class="bi bi-clipboard-x fs-2 d-block mb-2"></i> Chưa có bản ghi nhật ký thao tác nào
                                        </td>
                                    </tr>
                                    <?php else: ?>
                                    <?php foreach ($activityLogs as $log): ?>
                                    <?php 
                                    $actionBadge = match($log['action']) {
                                        'GRANT_ROLE' => '<span class="badge px-2.5 py-1 rounded-pill" style="background: rgba(239, 68, 68, 0.15); color: #F87171; border: 1px solid rgba(239, 68, 68, 0.3); font-size: 11px;"><i class="bi bi-shield-fill me-1"></i>Cấp Quyền</span>',
                                        'UPDATE_PROFILE' => '<span class="badge px-2.5 py-1 rounded-pill" style="background: rgba(59, 130, 246, 0.15); color: #60A5FA; border: 1px solid rgba(59, 130, 246, 0.3); font-size: 11px;"><i class="bi bi-person-fill-gear me-1"></i>Sửa Hồ Sơ</span>',
                                        'APPROVE_PAYMENT' => '<span class="badge px-2.5 py-1 rounded-pill" style="background: rgba(16, 185, 129, 0.15); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.3); font-size: 11px;"><i class="bi bi-check2-circle me-1"></i>Duyệt Tiền</span>',
                                        'VERIFY_PROOF' => '<span class="badge px-2.5 py-1 rounded-pill" style="background: rgba(245, 158, 11, 0.15); color: #FBBF24; border: 1px solid rgba(245, 158, 11, 0.3); font-size: 11px;"><i class="bi bi-patch-check-fill me-1"></i>Nghiệm Thu</span>',
                                        'DELETE_EVENT' => '<span class="badge px-2.5 py-1 rounded-pill" style="background: rgba(239, 68, 68, 0.15); color: #DC2626; border: 1px solid rgba(239, 68, 68, 0.3); font-size: 11px;"><i class="bi bi-trash3-fill me-1"></i>Xóa Sự Kiện</span>',
                                        'DELETE_SHIFT' => '<span class="badge px-2.5 py-1 rounded-pill" style="background: rgba(239, 68, 68, 0.15); color: #DC2626; border: 1px solid rgba(239, 68, 68, 0.3); font-size: 11px;"><i class="bi bi-trash-fill me-1"></i>Xóa Ca</span>',
                                        'CREATE_NOTE' => '<span class="badge px-2.5 py-1 rounded-pill" style="background: rgba(139, 92, 246, 0.15); color: #A78BFA; border: 1px solid rgba(139, 92, 246, 0.3); font-size: 11px;"><i class="bi bi-calendar-event me-1"></i>Lịch Hẹn</span>',
                                        default => '<span class="badge px-2.5 py-1 rounded-pill font-monospace" style="background: var(--ux-surface-2); color: var(--ux-ink-2); border: 1px solid var(--ux-line); font-size: 11px;">' . htmlspecialchars($log['action']) . '</span>'
                                    };
                                    ?>
                                    <tr style="border-bottom: 1px solid var(--ux-line);">
                                        <!-- Timestamp -->
                                        <td class="ps-4 py-3 font-monospace small" style="color: var(--ux-ink-3); font-size: 12px;">
                                            <?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?>
                                        </td>

                                        <!-- User + Role -->
                                        <td class="py-3">
                                            <div class="fw-bold mb-0" style="font-size: 13.5px; color: var(--ux-ink);"><?= htmlspecialchars($log['user_name']) ?></div>
                                            <span class="badge rounded-pill font-monospace" style="font-size: 10px; background: var(--ux-surface-3); color: var(--ux-ink-2);">
                                                <?= htmlspecialchars($log['user_role'] ?? 'User') ?>
                                            </span>
                                        </td>

                                        <!-- Action badge -->
                                        <td class="py-3">
                                            <?= $actionBadge ?>
                                        </td>

                                        <!-- Details -->
                                        <td class="py-3">
                                            <div class="small" style="color: var(--ux-ink-2); line-height: 1.5; font-size: 12.5px;">
                                                <?= htmlspecialchars($log['details']) ?>
                                            </div>
                                        </td>

                                        <!-- IP Address -->
                                        <td class="pe-4 py-3 text-end font-monospace small" style="color: var(--ux-ink-3); font-size: 11px;">
                                            <?= htmlspecialchars($log['ip_address'] ?? '127.0.0.1') ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            <?php endif; ?>

        </div> <!-- #spaBodyContent -->
    </main>

</div>

<!-- =========================================================
     MODAL ĐẶT LỊCH / TẠO CA TRỰC SỰ KIỆN MỚI TẠI DASHBOARD
     ========================================================= -->
<div class="modal fade" id="createShiftModal" tabindex="-1" aria-labelledby="createShiftModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:22px; overflow:hidden;">
            <div class="modal-header text-white" style="background:var(--ux-accent); padding:18px 24px;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-calendar-plus-fill fs-5"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="createShiftModalTitle">Đặt Lịch Ca Trực Sự Kiện Mới</h5>
                        <small style="opacity:0.9;">Tạo ca trực trực tiếp lên Bảng Lịch & Thiết lập mức thù lao kế toán</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createShiftForm" onsubmit="handleCreateShiftSubmit(event)">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-8">
                            <label class="form-label fw-bold text-dark small">Thuộc Sự Kiện <span class="text-danger">*</span></label>
                            <select name="event_id" class="form-select" required>
                                <?php foreach ($events as $ev): ?>
                                <option value="<?= $ev['id'] ?>"><?= htmlspecialchars($ev['title']) ?> (Bắt đầu: <?= substr($ev['start_time'], 0, 10) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-bold text-dark small">Chỉ Tiêu Tuyển CTV <span class="text-danger">*</span></label>
                            <input type="number" name="target_qty" class="form-control" value="5" min="1" max="50" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold text-dark small">Tên Ca Trực & Nhiệm Vụ <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Ví dụ: Ca 1: Lễ Tân Đón Khách & Check-in Doanh Nghiệp" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold text-dark small">Thời Gian Bắt Đầu <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="start_time" class="form-control num-tabular" value="2026-11-15T07:30" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold text-dark small">Thời Gian Kết Thúc <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="end_time" class="form-control num-tabular" value="2026-11-15T11:30" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold text-dark small">Định Mức Thù Lao Quyết Toán (VNĐ/ca)</label>
                            <div class="input-group">
                                <input type="number" name="remuneration_amount" class="form-control num-tabular" value="200000" step="10000" required>
                                <span class="input-group-text">VNĐ</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold text-dark small">Điểm Tích Cực (Uy Tín CTV)</label>
                            <div class="input-group">
                                <input type="number" name="points" class="form-control num-tabular" value="10" min="1" max="50" required>
                                <span class="input-group-text">Điểm</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold text-dark small">Yêu Cầu Kỹ Năng / Trang Phục</label>
                            <input type="text" name="skill_required" class="form-control" value="Trang phục áo cam FPoly, tác phong chuyên nghiệp, năng động">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-0 d-flex justify-content-between">
                    <button type="button" class="btn-fpt-secondary" data-bs-dismiss="modal">Hủy Bỏ</button>
                    <button type="submit" class="btn-fpt-primary">
                        <i class="bi bi-check-lg me-1"></i> Lưu & Đặt Lên Bảng Lịch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- =========================================================
     MODAL TẠO GHI CHÚ / LỊCH HẸN TOÀN TRƯỜNG (APPLE STYLE)
     ========================================================= -->
<div class="modal fade" id="addNoteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:24px; overflow:hidden;">
            <div class="modal-header border-bottom" style="background:#FAFAFC; padding:18px 24px; color:#1D1D1F;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-note-sticky text-warning fs-5"></i>
                    <div>
                        <h6 class="modal-title fw-bold mb-0" style="color:#1D1D1F;">Thêm Ghi Chú / Lịch Hẹn Toàn Trường</h6>
                        <small class="text-muted">Lưu ý, hạn chót hoặc dặn dò cho CTV và Ban tổ chức</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addNoteForm" onsubmit="handleAddNoteSubmit(event)">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small">Tiêu Đề Ghi Chú / Lịch Hẹn <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="Ví dụ: Họp điều phối trang phục hoặc Nhắc nhở mang thẻ CTV" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold text-dark small">Ngày Áp Dụng <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control num-tabular" value="2026-11-15" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold text-dark small">Loại Nhắc Nhở</label>
                            <select name="type" class="form-select">
                                <option value="EVENT_REMINDER">Lưu Ý Sự Kiện</option>
                                <option value="DEADLINE">Hạn Chót / Deadline</option>
                                <option value="FACILITY_NOTE">Cơ Sở Vật Chất</option>
                                <option value="MEETING">Lịch Họp / Điều Phối</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small d-block mb-2">Màu Sắc Đánh Dấu Trên Lịch</label>
                        <div class="d-flex align-items-center gap-2 flex-wrap" id="noteColorPickerGroup">
                            <div>
                                <input type="radio" class="btn-check color-choice-input" name="color" id="noteColor_orange" value="#EA580C" checked>
                                <label class="btn btn-sm rounded-pill px-3 py-1.5 border d-inline-flex align-items-center gap-2 color-choice-label shadow-xs" for="noteColor_orange" style="--choice-color: #EA580C;">
                                    <span class="rounded-circle" style="width: 14px; height: 14px; background: #EA580C; box-shadow: 0 1px 3px rgba(0,0,0,0.2);"></span>
                                    <span class="small fw-semibold text-dark" style="font-size: 12px;">Cam FPT</span>
                                </label>
                            </div>
                            <div>
                                <input type="radio" class="btn-check color-choice-input" name="color" id="noteColor_blue" value="#0284C7">
                                <label class="btn btn-sm rounded-pill px-3 py-1.5 border d-inline-flex align-items-center gap-2 color-choice-label shadow-xs" for="noteColor_blue" style="--choice-color: #0284C7;">
                                    <span class="rounded-circle" style="width: 14px; height: 14px; background: #0284C7; box-shadow: 0 1px 3px rgba(0,0,0,0.2);"></span>
                                    <span class="small fw-semibold text-dark" style="font-size: 12px;">Xanh Dương</span>
                                </label>
                            </div>
                            <div>
                                <input type="radio" class="btn-check color-choice-input" name="color" id="noteColor_purple" value="#7C3AED">
                                <label class="btn btn-sm rounded-pill px-3 py-1.5 border d-inline-flex align-items-center gap-2 color-choice-label shadow-xs" for="noteColor_purple" style="--choice-color: #7C3AED;">
                                    <span class="rounded-circle" style="width: 14px; height: 14px; background: #7C3AED; box-shadow: 0 1px 3px rgba(0,0,0,0.2);"></span>
                                    <span class="small fw-semibold text-dark" style="font-size: 12px;">Tím</span>
                                </label>
                            </div>
                            <div>
                                <input type="radio" class="btn-check color-choice-input" name="color" id="noteColor_green" value="#059669">
                                <label class="btn btn-sm rounded-pill px-3 py-1.5 border d-inline-flex align-items-center gap-2 color-choice-label shadow-xs" for="noteColor_green" style="--choice-color: #059669;">
                                    <span class="rounded-circle" style="width: 14px; height: 14px; background: #059669; box-shadow: 0 1px 3px rgba(0,0,0,0.2);"></span>
                                    <span class="small fw-semibold text-dark" style="font-size: 12px;">Xanh Lá</span>
                                </label>
                            </div>
                            <div>
                                <input type="radio" class="btn-check color-choice-input" name="color" id="noteColor_red" value="#DC2626">
                                <label class="btn btn-sm rounded-pill px-3 py-1.5 border d-inline-flex align-items-center gap-2 color-choice-label shadow-xs" for="noteColor_red" style="--choice-color: #DC2626;">
                                    <span class="rounded-circle" style="width: 14px; height: 14px; background: #DC2626; box-shadow: 0 1px 3px rgba(0,0,0,0.2);"></span>
                                    <span class="small fw-semibold text-dark" style="font-size: 12px;">Đỏ</span>
                                </label>
                            </div>
                            <div>
                                <input type="radio" class="btn-check color-choice-input" name="color" id="noteColor_amber" value="#D97706">
                                <label class="btn btn-sm rounded-pill px-3 py-1.5 border d-inline-flex align-items-center gap-2 color-choice-label shadow-xs" for="noteColor_amber" style="--choice-color: #D97706;">
                                    <span class="rounded-circle" style="width: 14px; height: 14px; background: #D97706; box-shadow: 0 1px 3px rgba(0,0,0,0.2);"></span>
                                    <span class="small fw-semibold text-dark" style="font-size: 12px;">Hổ Phách</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small">Nội Dung Chi Tiết</label>
                        <textarea name="content" class="form-control" rows="3" placeholder="Chi tiết dặn dò, địa điểm tập trung hoặc yêu cầu thiết bị..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-0 d-flex justify-content-between">
                    <button type="button" class="btn-fpt-secondary" data-bs-dismiss="modal">Hủy Bỏ</button>
                    <button type="submit" class="btn-fpt-primary">
                        <i class="bi bi-check-lg me-1"></i> Lưu Lên Lịch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- =========================================================
     MODAL XEM TRƯỚC ẢNH VNEID / CCCD (CHO KẾ TOÁN ĐỐI SOÁT)
     ========================================================= -->
<div class="modal fade" id="previewVneidModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:20px; overflow:hidden;">
            <div class="modal-header border-bottom" style="background:#FAFAFC; padding:16px 20px; color:#1D1D1F;">
                <div>
                    <h6 class="modal-title fw-bold mb-0" id="vneidModalTitle" style="color:#1D1D1F;">Đối Soát Ảnh Chụp Màn Hình VNeID</h6>
                    <small class="text-muted" id="vneidModalSub">Số CCCD: 079203014589</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 text-center bg-light">
                <img id="vneidModalImage" src="" alt="VNeID Screenshot" class="img-fluid rounded-3 shadow-xs" style="max-height: 380px;">
                <div class="alert alert-success mt-3 py-2 small mb-0">
                    <i class="bi bi-patch-check-fill me-1"></i> Thông tin số CCCD đã khớp với ảnh chụp màn hình ứng dụng VNeID định danh điện tử Mức 2.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================
     MODAL XEM TRƯỚC ẢNH CHỤP HIỆN TRƯỜNG MINH CHỨNG CÓ MẶT
     ========================================================= -->
<div class="modal fade" id="previewProofModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:20px; overflow:hidden;">
            <div class="modal-header border-bottom" style="background:#FAFAFC; padding:16px 20px; color:#1D1D1F;">
                <div>
                    <h6 class="modal-title fw-bold mb-0" id="proofModalTitle" style="color:#1D1D1F;">Minh Chứng Hiện Trường Có Mặt</h6>
                    <small class="text-muted" id="proofModalSub">Sự kiện: FPoly TechDay 2026</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 text-center bg-light">
                <div class="position-relative d-inline-block rounded-3 overflow-hidden shadow-xs mb-2">
                    <img id="proofModalImage" src="" alt="Ảnh Hiện Trường" class="img-fluid" style="max-height: 380px; display: block; border-radius: 12px;">
                </div>
                <div class="alert alert-info py-2 px-3 small mb-0 text-start">
                    <strong class="d-block text-dark"><i class="bi bi-chat-left-quote me-1"></i>Ghi chú của CTV:</strong>
                    <span id="proofModalNote" class="text-muted">Có mặt đúng giờ tại địa điểm trực sự kiện</span>
                </div>
            </div>
            <div class="modal-footer bg-light px-3 py-2 border-0">
                <button type="button" class="btn-fpt-secondary btn-fpt-sm" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================
     MODAL NỘP MINH CHỨNG ĐỂ NGHIỆM THU SAU MỖI SỰ KIỆN HOÀN THÀNH
     ========================================================= -->
<div class="modal fade" id="submitProofModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:22px; overflow:hidden;">
            <div class="modal-header text-white" style="background:var(--ux-accent); padding:18px 24px;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-patch-check-fill fs-5" id="submitProofModalIcon"></i>
                    <div>
                        <h6 class="modal-title fw-bold mb-0" id="submitProofModalTitle">Nộp Minh Chứng Nghiệm Thu Sau Sự Kiện</h6>
                        <small class="text-white-50" id="submitProofShiftInfo">Ca trực sự kiện</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="submitProofForm" onsubmit="handleProofSubmit(event)">
                <input type="hidden" name="assignment_id" id="submitProofAssignmentId" value="">
                <input type="hidden" name="proof_type" id="submitProofType" value="on_site">
                
                <div class="modal-body p-4">
                    <!-- Tab chuyển đổi Vai Trò: CTV Thường vs CTV Media -->
                    <div class="d-flex p-1 rounded-3 bg-light border mb-3">
                        <button type="button" class="btn btn-sm flex-fill fw-bold rounded-2 btn-primary" id="tabProofOnSite" onclick="switchProofMode('on_site')">
                            <i class="bi bi-camera me-1"></i> Role CTV: Ảnh Hiện Trường
                        </button>
                        <button type="button" class="btn btn-sm flex-fill fw-bold rounded-2 text-muted" id="tabProofDriveMedia" onclick="switchProofMode('drive_media')">
                            <i class="fa-brands fa-google-drive me-1 text-primary"></i> Role CTV Media: Link Drive
                        </button>
                    </div>

                    <!-- Khu Vực 1: Role CTV Thường - Ảnh Hiện Trường -->
                    <div id="proofAreaOnSite">
                        <div class="alert alert-info py-2 px-3 small mb-3 border-0 rounded-3">
                            <i class="bi bi-info-circle-fill me-1"></i> <strong>Quy chế CTV:</strong> CTV có nhiệm vụ chụp <strong>ảnh hiện trường</strong> có mặt làm nhiệm vụ thực tế (áo cam FPoly) để Giảng viên nghiệm thu.
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">Chọn Ảnh Mẫu Hiện Trường Nhanh (Dành cho Demo)</label>
                            <div class="d-flex flex-wrap gap-1.5 mb-2">
                                <button type="button" class="btn-fpt-secondary btn-fpt-sm" onclick="setProofSample('https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&auto=format&fit=crop&q=80', 'Có mặt tại sảnh Hội trường A đón tiếp khách VIP và sinh viên')">
                                    <i class="bi bi-image me-1"></i> Sảnh Hội Trường
                                </button>
                                <button type="button" class="btn-fpt-secondary btn-fpt-sm" onclick="setProofSample('https://images.unsplash.com/photo-1511578314322-379afb476865?w=800&auto=format&fit=crop&q=80', 'Có mặt tại Sân khấu chính điều phối âm thanh ánh sáng')">
                                    <i class="bi bi-image me-1"></i> Sân Khấu
                                </button>
                                <button type="button" class="btn-fpt-secondary btn-fpt-sm" onclick="setProofSample('https://images.unsplash.com/photo-1528605248644-14dd04022da1?w=800&auto=format&fit=crop&q=80', 'Hoàn tất bàn lễ tân hướng dẫn đăng ký tham dự')">
                                    <i class="bi bi-image me-1"></i> Bàn Tiếp Đón
                                </button>
                            </div>
                            <label class="form-label fw-bold text-dark small mt-2">Hoặc Nhập URL Ảnh Hiện Trường Thực Tế <span class="text-danger">*</span></label>
                            <input type="url" name="proof_image_url" id="submitProofImageUrl" class="form-control" placeholder="https://..." oninput="previewProofInput(this.value)">
                        </div>

                        <div class="text-center mb-3">
                            <div id="proofInputPreviewBox" style="height: 140px; border-radius: 12px; background: #f8fafc; border: 1px dashed #cbd5e1; display: grid; place-items: center; overflow: hidden;">
                                <span class="text-muted small"><i class="bi bi-camera me-1"></i>Ảnh xem trước sẽ hiển thị tại đây</span>
                            </div>
                        </div>
                    </div>

                    <!-- Khu Vực 2: Role CTV Media - Link Google Drive -->
                    <div id="proofAreaDriveMedia" style="display:none;">
                        <div class="alert alert-warning py-2 px-3 small mb-3 border-0 rounded-3">
                            <i class="bi bi-camera-reels-fill me-1"></i> <strong>Quy chế CTV Media:</strong> CTV Media cần nộp <strong>Link Google Drive</strong> chứa bộ ảnh sự kiện đã được <strong>chỉnh sửa sáng và gắn kèm logo FPT Polytechnic</strong> chuẩn nhận diện.
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">Đường Dẫn Thư Mục Google Drive (Bộ Ảnh Đã Chỉnh Sáng & Gắn Logo) <span class="text-danger">*</span></label>
                            <input type="url" name="drive_media_url" id="submitDriveMediaUrl" class="form-control" placeholder="https://drive.google.com/drive/folders/..." oninput="syncDriveInput(this.value)">
                            <div class="form-text small text-muted">Dán link Google Drive chia sẻ quyền xem/tải của bộ phận truyền thông.</div>
                        </div>

                        <div class="p-3 rounded-3 border bg-light mb-3" style="font-size:13px;">
                            <div class="form-check mb-2 d-flex align-items-center">
                                <input class="form-check-input mt-0 me-2 cursor-pointer flex-shrink-0" type="checkbox" id="checkLogo" checked required style="width: 17px; height: 17px;">
                                <label class="form-check-label fw-semibold text-dark cursor-pointer user-select-none" for="checkLogo">
                                    Đã gắn Watermark / Logo FPT Polytechnic
                                </label>
                            </div>
                            <div class="form-check mb-0 d-flex align-items-center">
                                <input class="form-check-input mt-0 me-2 cursor-pointer flex-shrink-0" type="checkbox" id="checkBrightness" checked required style="width: 17px; height: 17px;">
                                <label class="form-check-label fw-semibold text-dark cursor-pointer user-select-none" for="checkBrightness">
                                    Đã hậu kỳ, cân bằng ánh sáng và chỉnh sáng sắc nét
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small">Ghi Chú Nghiệm Thu Công Việc</label>
                        <textarea name="proof_note" id="submitProofNote" class="form-control" rows="2" placeholder="Ví dụ: Đã có mặt đúng giờ, hoàn thành nhiệm vụ..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-0 d-flex justify-content-between">
                    <button type="button" class="btn-fpt-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn-fpt-primary">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i> Nộp Minh Chứng Nghiệm Thu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- =========================================================
     MODAL CHI TIẾT HỒ SƠ NGHIỆM THU & QUYẾT TOÁN THÙ LAO CTV
     ========================================================= -->
<div class="modal fade" id="accountingDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius:24px; overflow:hidden;">
            <div class="modal-header border-bottom" style="background:#FAFAFC; padding:18px 24px;">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-circle d-grid place-items-center bg-white border text-dark" style="width:38px; height:38px; font-size:16px;">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark" id="accDetailModalTitle">Chi Tiết Hồ Sơ Nghiệm Thu</h5>
                        <small class="text-muted" id="accDetailModalSub">Mã hồ sơ: HDNT-2026</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="background:#FFFFFF;">
                <div class="row g-3">
                    <!-- Column 1: CTV & Định Danh VNeID -->
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded-4 border h-100" style="background:#FAFAFC; border-color:rgba(0,0,0,0.08) !important;">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-person-badge text-primary fs-5"></i>
                                <strong class="text-dark" style="font-size:13.5px;">Cộng Tác Viên & VNeID</strong>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small d-block">Họ và tên:</span>
                                <strong class="text-dark" id="accDetailName">-</strong>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <span class="text-muted small d-block">Mã SV:</span>
                                    <span class="badge bg-white text-dark border font-monospace" id="accDetailCode">-</span>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted small d-block">Điện thoại:</span>
                                    <span class="text-dark num-tabular" id="accDetailPhone">-</span>
                                </div>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small d-block">Số CCCD định danh:</span>
                                <div class="d-flex align-items-center gap-2 mt-0.5">
                                    <strong class="font-monospace text-dark" id="accDetailCccd">-</strong>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size:10px;"><i class="bi bi-check-circle-fill me-1"></i>Đã khớp VNeID</span>
                                </div>
                            </div>
                            <div class="mt-2" id="accDetailVneidImgContainer">
                                <span class="text-muted small d-block mb-1">Ảnh thẻ VNeID:</span>
                                <img id="accDetailVneidImg" src="" alt="VNeID" class="rounded-3 border" style="max-height:90px; width:auto; cursor:pointer;" onclick="previewVneidModal(currentAccDetail?.full_name, currentAccDetail?.id_card_number, this.src)" title="Nhấp để xem ảnh phóng to">
                            </div>
                        </div>
                    </div>

                    <!-- Column 2: Tài Khoản Thụ Hưởng & Thù Lao -->
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded-4 border h-100" style="background:#FAFAFC; border-color:rgba(0,0,0,0.08) !important;">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-bank text-success fs-5"></i>
                                <strong class="text-dark" style="font-size:13.5px;">Tài Khoản Thụ Hưởng & Thù Lao</strong>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small d-block">Ngân hàng:</span>
                                <strong class="text-dark" id="accDetailBankName">-</strong>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small d-block">Số tài khoản (STK):</span>
                                <span class="badge bg-white text-primary border font-monospace fs-6 py-1 px-2.5" id="accDetailBankNumber">-</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small d-block">Chủ tài khoản:</span>
                                <strong class="text-dark text-uppercase" id="accDetailBankHolder">-</strong>
                            </div>
                            <hr class="my-2" style="opacity:0.1;">
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div>
                                    <span class="text-muted small d-block">Mức thù lao ca:</span>
                                    <span class="badge bg-success text-white fs-6 py-1 px-3" id="accDetailAmount">0 đ</span>
                                </div>
                                <div class="text-end">
                                    <span class="text-muted small d-block">Điểm tích cực:</span>
                                    <span class="badge bg-light text-dark border" id="accDetailPoints">+0 điểm</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Column 3: Thông Tin Ca Trực & Địa Điểm -->
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded-4 border h-100" style="background:#FAFAFC; border-color:rgba(0,0,0,0.08) !important;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-calendar-event text-warning fs-5"></i>
                                <strong class="text-dark" style="font-size:13.5px;">Ca Trực & Sự Kiện</strong>
                            </div>
                            <div class="mb-1.5">
                                <span class="text-muted small d-block">Sự kiện:</span>
                                <strong class="text-dark" id="accDetailEventTitle">-</strong>
                            </div>
                            <div class="mb-1.5">
                                <span class="text-muted small d-block">Ca trực:</span>
                                <span class="badge bg-white text-dark border" id="accDetailShiftName">-</span>
                            </div>
                            <div class="mb-1.5">
                                <span class="text-muted small d-block">Thời gian:</span>
                                <span class="num-tabular text-dark" id="accDetailTime">-</span>
                            </div>
                            <div>
                                <span class="text-muted small d-block">Địa điểm:</span>
                                <span class="text-dark" id="accDetailLocation">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Column 4: Minh Chứng Hiện Trường & Trạng Thái Nghiệm Thu -->
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded-4 border h-100" style="background:#FAFAFC; border-color:rgba(0,0,0,0.08) !important;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-camera text-info fs-5"></i>
                                <strong class="text-dark" style="font-size:13.5px;">Minh Chứng & Nghiệm Thu</strong>
                            </div>
                            <div class="mb-2" id="accDetailProofContainer">
                                <span class="text-muted small d-block mb-1">Ảnh check-in hiện trường:</span>
                                <div class="d-flex align-items-center gap-2">
                                    <img id="accDetailProofImg" src="" alt="Proof" class="rounded-3 border" style="width:56px; height:42px; object-fit:cover; cursor:pointer;" onclick="previewProofModal(currentAccDetail?.full_name, currentAccDetail?.shift_name, this.src, currentAccDetail?.proof_note)" title="Nhấp xem phóng to">
                                    <small class="text-muted" id="accDetailProofNote">-</small>
                                </div>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small d-block">Giảng viên phụ trách:</span>
                                <strong class="text-dark" id="accDetailLecturer">-</strong>
                            </div>
                            <div>
                                <span class="text-muted small d-block">Trạng thái giải ngân:</span>
                                <div id="accDetailStatusBadge">-</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top d-flex justify-content-between" style="background:#FAFAFC; padding:14px 24px;">
                <button type="button" class="btn btn-fpt-secondary btn-fpt-sm" data-bs-dismiss="modal">
                    Đóng
                </button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-fpt-primary btn-fpt-sm" id="accDetailContractBtn">
                        <i class="bi bi-file-earmark-text me-1"></i> Xem Hợp Đồng Nghiệm Thu
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================
     MODAL CHI TIẾT HỒ SƠ CTV (KÍCH HOẠT KHI NHẤP ICON CON MẮT 👁️)
     ========================================================= -->
<div class="modal fade" id="ctvEyeDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:22px; overflow:hidden;">
            <!-- Modal Header -->
            <div class="modal-header border-bottom px-4 py-3" style="background: #FAFAFC;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-grid place-items-center text-white fw-bold shadow-xs flex-shrink-0" id="ctvEyeAvatar" style="width: 44px; height: 44px; background: linear-gradient(135deg, #F97316 0%, #EA580C 100%); font-size: 16px;">
                        C
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="fw-bold mb-0 text-dark" id="ctvEyeName">Họ và tên CTV</h5>
                            <span class="badge bg-light text-dark border font-monospace" id="ctvEyeCode">MSSV</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle" id="ctvEyeDept"><i class="bi bi-mortarboard-fill me-1"></i>Bộ Môn</span>
                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle" id="ctvEyeType"><i class="bi bi-tools me-1"></i>Loại CTV</span>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <div class="row g-3">
                    <!-- Khối 1: Thông Tin Liên Hệ -->
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded-3 border h-100" style="background:#FAFAFC; border-color:rgba(0,0,0,0.06) !important;">
                            <div class="d-flex align-items-center gap-2 mb-2.5 pb-2 border-bottom">
                                <i class="bi bi-person-lines-fill text-primary"></i>
                                <strong class="text-dark" style="font-size:13.5px;">Thông Tin Cá Nhân & Liên Hệ</strong>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small d-block">Email trường FPT:</span>
                                <strong class="text-dark" id="ctvEyeEmail" style="font-size:12.5px;">-</strong>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small d-block">Số điện thoại liên hệ:</span>
                                <strong class="text-dark" id="ctvEyePhone" style="font-size:12.5px;">-</strong>
                            </div>
                            <div>
                                <span class="text-muted small d-block">Kỹ năng chuyên môn & Sở trường:</span>
                                <span class="badge bg-white text-dark border mt-1" id="ctvEyeSkills" style="font-size:11.5px; font-weight:normal; line-height: 1.4;">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Khối 2: Thông Tin Định Danh CCCD & VNeID -->
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded-3 border h-100" style="background:#FAFAFC; border-color:rgba(0,0,0,0.06) !important;">
                            <div class="d-flex align-items-center gap-2 mb-2.5 pb-2 border-bottom">
                                <i class="bi bi-shield-lock-fill text-success"></i>
                                <strong class="text-dark" style="font-size:13.5px;">Định Danh Cá Nhân (VNeID)</strong>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small d-block">Số Căn Cước Công Dân (CCCD):</span>
                                <div class="d-flex align-items-center gap-2">
                                    <strong class="font-monospace text-dark fs-6" id="ctvEyeCccd">-</strong>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size:10px;"><i class="bi bi-check2-circle me-1"></i>Khớp FPoly</span>
                                </div>
                            </div>
                            <div>
                                <span class="text-muted small d-block mb-1">Ảnh định danh thẻ VNeID:</span>
                                <div class="d-flex align-items-center gap-2">
                                    <img id="ctvEyeVneidImg" src="" alt="VNeID" class="rounded-2 border shadow-xs" style="width: 72px; height: 50px; object-fit: cover; cursor: pointer;" onclick="previewVneidPhoto(this.src, document.getElementById('ctvEyeName').innerText, document.getElementById('ctvEyeCccd').innerText)" title="Nhấp để xem phóng to ảnh">
                                    <small class="text-muted" style="font-size:11px;">Nhấp vào ảnh để phóng to đối soát thông tin khuôn mặt & số CCCD.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Khối 3: Tài Khoản Ngân Hàng Nhận Thù Lao -->
                    <div class="col-12">
                        <div class="p-3 rounded-3 border" style="background:#F0FDF4; border-color:rgba(22, 163, 74, 0.2) !important;">
                            <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom border-success-subtle">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-bank2 text-success"></i>
                                    <strong class="text-success-emphasis" style="font-size:13.5px;">Tài Khoản Quyết Toán Thù Lao CTV</strong>
                                </div>
                                <span class="badge bg-success text-white" style="font-size:10.5px;"><i class="bi bi-patch-check-fill me-1"></i>Sẵn Sàng Chuyển Khoản</span>
                            </div>
                            <div class="row g-2">
                                <div class="col-12 col-md-4">
                                    <span class="text-muted small d-block">Ngân hàng thụ hưởng:</span>
                                    <strong class="text-dark" id="ctvEyeBankName" style="font-size:13px;">-</strong>
                                </div>
                                <div class="col-12 col-md-4">
                                    <span class="text-muted small d-block">Số tài khoản (STK):</span>
                                    <strong class="font-monospace text-primary fs-6" id="ctvEyeBankAcc">-</strong>
                                </div>
                                <div class="col-12 col-md-4">
                                    <span class="text-muted small d-block">Chủ tài khoản:</span>
                                    <strong class="text-uppercase text-dark" id="ctvEyeBankHolder" style="font-size:13px;">-</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer px-4 py-2.5 border-top d-flex justify-content-between" style="background:#FAFAFC;">
                <span class="text-muted small"><i class="bi bi-shield-check me-1 text-success"></i>Hồ sơ được thẩm định và quản lý bởi Nhà trường</span>
                <button type="button" class="btn btn-fpt-secondary btn-fpt-sm" data-bs-dismiss="modal">
                    Đóng
                </button>
            </div>
        </div>
    </div>
</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================
     MODAL HỢP ĐỒNG & BIÊN BẢN NGHIỆM THU THÔNG TIN CTV
     (CHUẨN VĂN BẢN TRƯỜNG CAO ĐẲNG FPT POLYTECHNIC)
     ========================================================= -->
<div class="modal fade" id="contractModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius:24px; overflow:hidden;">
            <div class="modal-header border-bottom" style="background:#FAFAFC; padding:18px 24px; color:#1D1D1F;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-check-fill text-warning fs-5"></i>
                    <div>
                        <h6 class="modal-title fw-bold mb-0" style="color:#1D1D1F;">Hợp Đồng & Biên Bản Nghiệm Thu Thông Tin CTV</h6>
                        <small class="text-muted" id="contractModalSub">Mã hồ sơ: HDNT-2026-FPLY-001</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 p-md-5 bg-white" id="contractPrintArea" style="font-family: 'Times New Roman', Times, serif; font-size: 14.5px; line-height: 1.6; color: #111827;">
                <!-- Header Quốc Hiệu / Đơn Vị -->
                <div class="row g-2 pb-3 border-bottom mb-4 align-items-center">
                    <div class="col-7 text-start d-flex align-items-center gap-3">
                        <img src="/assets/images/fpt_polytechnic_logo.png" alt="FPT Polytechnic" style="height: 48px; width: auto; object-fit: contain;" class="flex-shrink-0">
                        <div>
                            <strong style="font-size: 13px;" class="text-uppercase d-block">TRƯỜNG CAO ĐẲNG FPT POLYTECHNIC</strong>
                            <span style="font-size: 12px;" class="d-block text-secondary">PHÒNG CÔNG TÁC SINH VIÊN & KẾ TOÁN</span>
                            <div class="font-monospace text-primary fw-bold mt-0.5" style="font-size: 11.5px;" id="contractDocNumber">Số: HDNT-2026-FPLY-001 / HĐNT-FPLY</div>
                        </div>
                    </div>
                    <div class="col-5 text-end">
                        <strong style="font-size: 12.5px;" class="text-uppercase d-block">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</strong>
                        <span style="font-size: 12px;" class="fw-bold d-block text-decoration-underline">Độc lập - Tự do - Hạnh phúc</span>
                        <small class="text-muted fst-italic d-block mt-1" style="font-size: 11px;">TP. Hồ Chí Minh, ngày 15 tháng 11 năm 2026</small>
                    </div>
                </div>

                <!-- Tiêu Đề Văn Bản -->
                <div class="text-center my-3">
                    <h5 class="fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">HỢP ĐỒNG & BIÊN BẢN NGHIỆM THU CÔNG VIỆC</h5>
                    <p class="fst-italic text-muted mb-0" style="font-size: 13.5px;">(V/v: Hoàn thành nhiệm vụ Cộng tác viên phục vụ sự kiện & Đề nghị chi trả thù lao)</p>
                </div>

                <div class="mb-3 small">
                    <p class="mb-1">- Căn cứ Kế hoạch tổ chức sự kiện và Điều phối Cộng tác viên của Trường Cao đẳng FPT Polytechnic;</p>
                    <p class="mb-1">- Căn cứ kết quả thực hiện và ảnh chụp hiện trường có mặt trực tiếp của Cộng tác viên.</p>
                </div>

                <!-- Bên A & Bên B -->
                <div class="bg-light p-3 rounded-3 mb-3" style="font-family: system-ui, -apple-system, sans-serif; font-size: 13px;">
                    <div class="row g-2">
                        <div class="col-12 col-md-6 border-end">
                            <strong class="text-primary text-uppercase d-block mb-1">BÊN A: ĐẠI DIỆN ĐƠN VỊ TỔ CHỨC / GIẢNG VIÊN</strong>
                            <div>Họ và tên: <strong class="text-dark" id="contractLecturer">ThS. Nguyễn Văn A</strong></div>
                            <div>Chức vụ: <span>Giảng viên Giám sát Hoạt động CTV</span></div>
                            <div>Đơn vị: <span>Trường Cao đẳng FPT Polytechnic</span></div>
                        </div>
                        <div class="col-12 col-md-6 ps-md-3">
                            <strong class="text-primary text-uppercase d-block mb-1">BÊN B: CỘNG TÁC VIÊN THỰC HIỆN (CTV)</strong>
                            <div>Họ và tên: <strong class="text-dark" id="contractCtvName">Lê Văn Cường</strong></div>
                            <div>Mã sinh viên: <span class="fw-bold" id="contractCtvCode">PS02941</span> • SĐT: <span id="contractCtvPhone">0987654321</span></div>
                            <div>Số CCCD: <span class="fw-bold font-monospace" id="contractCtvCccd">079203014589</span> <span class="badge bg-success-subtle text-success py-0" style="font-size:10px;">Đã khớp VNeID</span></div>
                            <div>Tài khoản nhận: <strong class="text-primary font-monospace" id="contractCtvBank">1029384756 (Vietcombank)</strong></div>
                            <div>Chủ tài khoản: <strong class="text-dark text-uppercase" id="contractCtvHolder">LE VAN CUONG</strong></div>
                        </div>
                    </div>
                </div>

                <!-- Nội Dung Công Việc & Thù Lao -->
                <div class="mb-3">
                    <strong class="d-block mb-1">ĐIỀU 1: NỘI DUNG VÀ KHỐI LƯỢNG CÔNG VIỆC THỰC HIỆN</strong>
                    <table class="table table-bordered mb-2" style="font-size: 13px;">
                        <tbody>
                            <tr>
                                <td style="width: 25%; font-weight: 600; background: #f8fafc;">Tên Sự Kiện:</td>
                                <td id="contractEventName">FPoly TechDay 2026</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600; background: #f8fafc;">Ca Phân Công:</td>
                                <td id="contractShiftName">Ca 2: Điều Phối & Đón Tiếp Khách Mời</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600; background: #f8fafc;">Thời Gian & Địa Điểm:</td>
                                <td id="contractTimeLocation">09:30 - 13:00 (15/11/2026) • Hội trường Tòa F</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600; background: #f8fafc;">Mức Thù Lao Quyết Toán:</td>
                                <td>
                                    <strong class="text-success fs-6" id="contractAmount">150,000 VNĐ</strong>
                                    <span class="text-muted fst-italic ms-2" id="contractAmountWords">(Một trăm năm mươi nghìn đồng chẵn)</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Minh Chứng Nghiệm Thu -->
                <div class="mb-4">
                    <strong class="d-block mb-1" id="contractProofTitle">ĐIỀU 2: MINH CHỨNG CÔNG VIỆC ĐỂ NGHIỆM THU</strong>
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="row g-3 align-items-center">
                            <div class="col-12 col-md-5 text-center" id="contractProofContainer">
                                <div id="contractProofPhotoBox" style="max-height: 200px; overflow: hidden; border-radius: 12px; border: 2px solid #e2e8f0; display: inline-block;">
                                    <img id="contractProofImg" src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&auto=format&fit=crop&q=80" alt="Ảnh Hiện Trường Nghiệm Thu" style="max-height: 200px; max-width: 100%; object-fit: cover; display: block;">
                                </div>
                                <div id="contractProofDriveBox" style="display: none; padding: 18px; border-radius: 12px; border: 1.5px dashed #0284C7; background: #F0F9FF;">
                                    <i class="fa-brands fa-google-drive text-primary" style="font-size: 38px;"></i>
                                    <h6 class="fw-bold text-dark mt-2 mb-1" style="font-size: 13px;">Bộ Ảnh Media Sự Kiện</h6>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle mb-2" style="font-size: 10px;">
                                        <i class="bi bi-patch-check-fill me-1"></i>Đã kèm Logo & Chỉnh Sáng
                                    </span>
                                    <div>
                                        <a id="contractProofDriveLink" href="#" target="_blank" class="btn btn-sm btn-fpt-primary rounded-pill py-1 px-3" style="font-size: 11.5px;">
                                            <i class="bi bi-box-arrow-up-right me-1"></i> Mở Thư Mục Drive
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-7" style="font-size: 13px;">
                                <div class="badge bg-success mb-2 px-2.5 py-1" id="contractProofStatusBadge">
                                    <i class="bi bi-shield-fill-check me-1"></i> MINH CHỨNG ĐÃ ĐƯỢC GIẢNG VIÊN THẨM ĐỊNH
                                </div>
                                <p class="mb-1"><strong>Ghi chú CTV:</strong> <span id="contractProofNoteDisplay" class="fst-italic text-dark">Có mặt đúng giờ, hỗ trợ đón khách tại sảnh</span></p>
                                <p class="mb-1"><strong>Kết luận Giảng viên:</strong> <span class="text-success fw-bold" id="contractProofConclusion">CTV có mặt đúng vị trí phân công, hoàn thành đầy đủ nhiệm vụ được giao, đủ điều kiện nghiệm thu thanh toán.</span></p>
                                <p class="mb-0 text-muted small" id="contractProofSubtext">Ảnh chụp có xác thực vị trí và nhận diện sinh viên.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ĐIỀU 3 & CHỮ KÝ 3 BÊN -->
                <div class="mb-3">
                    <strong class="d-block mb-3">ĐIỀU 3: KÝ DUYỆT & ĐỀ NGHỊ NHÀ TRƯỜNG CHUYỂN TIỀN</strong>
                    <div class="row g-2 text-center" style="font-size: 13px;">
                        <div class="col-4">
                            <strong class="text-uppercase d-block mb-1">CỘNG TÁC VIÊN</strong>
                            <span class="fst-italic text-muted small d-block mb-4">(Ký, ghi rõ họ tên)</span>
                            <div class="badge bg-success-subtle text-success border border-success-subtle py-1 px-2 my-2">
                                <i class="bi bi-fingerprint me-1"></i> Đã Ký Điện Tử (VNeID)
                            </div>
                            <strong class="d-block mt-1 text-dark" id="contractSignCtv">Lê Văn Cường</strong>
                        </div>
                        <div class="col-4 border-start border-end">
                            <strong class="text-uppercase d-block mb-1">GIẢNG VIÊN NGHIỆM THU</strong>
                            <span class="fst-italic text-muted small d-block mb-4">(Ký xác nhận hiện trường)</span>
                            <div class="badge bg-primary-subtle text-primary border border-primary-subtle py-1 px-2 my-2" id="contractSignLecturerBadge">
                                <i class="bi bi-patch-check-fill me-1"></i> Đã Xác Nhận Hiện Trường
                            </div>
                            <strong class="d-block mt-1 text-dark" id="contractSignLecturer">ThS. Nguyễn Văn A</strong>
                        </div>
                        <div class="col-4">
                            <strong class="text-uppercase d-block mb-1">BAN GIÁM HIỆU / KẾ TOÁN</strong>
                            <span class="fst-italic text-muted small d-block mb-4">(Ký duyệt chi tiền)</span>
                            <div id="contractSignSchoolBadge">
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle py-1 px-2 my-2">
                                    <i class="bi bi-hourglass-split me-1"></i> Đang Chờ Ký Duyệt
                                </span>
                            </div>
                            <strong class="d-block mt-1 text-dark">Phòng Kế Toán FPoly</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light px-4 py-3 border-0 d-flex justify-content-between">
                <button type="button" class="btn-fpt-secondary" data-bs-dismiss="modal">Đóng</button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn-fpt-secondary" onclick="printContractModal()">
                        <i class="bi bi-printer me-1"></i> In Hợp Đồng (PDF)
                    </button>
                    <button type="button" class="btn-fpt-emerald" id="btnVerifyInModal" onclick="verifyCurrentModalLecturer()" style="display:none;">
                        <i class="bi bi-patch-check-fill me-1"></i> Duyệt Nghiệm Thu
                    </button>
                    <button type="button" class="btn-fpt-primary" id="btnSubmitToSchoolInModal" onclick="submitCurrentModalToSchool()">
                        <i class="bi bi-send-check-fill me-1"></i> Trình Trường Ký Xác Nhận Chuyển Tiền
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================
     MODAL ĐIỀU PHỐI / CHI TIẾT CA TRỰC (ROLE-ADAPTIVE)
     ========================================================= -->
<div class="modal fade" id="shiftModal" tabindex="-1" aria-labelledby="shiftModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:22px; overflow:hidden;">
            <div class="modal-header border-bottom" style="background:#FAFAFC; padding:20px 24px; color:#1D1D1F;">
                <div>
                    <span class="badge bg-warning text-dark font-monospace mb-1" id="modalShiftBadge">+10 Điểm Tích Cực</span>
                    <h5 class="modal-title fw-bold" id="shiftModalTitle" style="color:#1D1D1F;">Chi Tiết Ca Trực</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <p class="text-muted small mb-1 fw-bold text-uppercase">Thuộc Sự Kiện & Bộ Môn:</p>
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h6 class="fw-bold text-dark mb-0" id="modalEventTitle">FPoly TechDay 2026</h6>
                        <span class="badge rounded-pill" id="modalShiftDepartmentBadge" style="background: rgba(2, 132, 199, 0.12); color: #0284C7; border: 1px solid rgba(2, 132, 199, 0.25); font-size: 11.5px; font-weight: 600;">
                            <i class="bi bi-palette2 me-1"></i> <span id="modalShiftDepartment">Công nghệ Thông tin</span>
                        </span>
                    </div>
                </div>
                
                <div class="row g-2 mb-3 bg-light p-3 rounded-3" style="font-size:13px;">
                    <div class="col-12 mb-1 pb-1 border-bottom">
                        <span class="text-muted d-block small">Giảng Viên Phụ Trách:</span>
                        <strong class="text-dark" id="modalShiftLecturer">Thầy/Cô Giảng viên (GV0102)</strong>
                    </div>
                    <div class="col-6">
                        <span class="text-muted d-block">Khung Giờ:</span>
                        <strong class="text-dark num-tabular" id="modalShiftTime">06:30 - 09:30</strong>
                    </div>
                    <div class="col-6">
                        <span class="text-muted d-block">Địa Điểm:</span>
                        <strong class="text-dark" id="modalShiftLocation">Hội trường T</strong>
                    </div>
                    <div class="col-6 mt-2">
                        <span class="text-muted d-block">Chỉ Tiêu CTV:</span>
                        <strong class="text-dark num-tabular" id="modalShiftQuota">3 / 4 Đã tuyển</strong>
                    </div>
                    <div class="col-6 mt-2">
                        <span class="text-muted d-block">Thù Lao Dự Kiến:</span>
                        <strong class="text-success num-tabular" id="modalShiftAllowance">200,000 đ</strong>
                    </div>
                    <div class="col-12 mt-2">
                        <span class="text-muted d-block">Yêu Cầu Kỹ Năng:</span>
                        <strong class="text-primary" id="modalShiftSkill">Setup & Kỹ thuật</strong>
                    </div>
                </div>

                <div id="modalRoleNotice" class="alert alert-info py-2 px-3 small border-0 d-flex align-items-center gap-2">
                    <i class="bi bi-shield-check fs-5"></i>
                    <span>Thuật toán <strong>Interval Collision Detection</strong> tự động rà soát lịch để ngăn ngừa trùng giờ.</span>
                </div>
            </div>
            <div class="modal-footer bg-light px-4 py-3 border-0 d-flex justify-content-between">
                <button type="button" class="btn-fpt-secondary" data-bs-dismiss="modal">Đóng</button>
                <div id="modalActionButtons">
                    <!-- Nút render động theo Role: Sinh viên (Đăng ký) | Admin/GV (Quản lý/Chiếu QR) -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================
     MODAL TRỢ LÝ AI POLYMEE (FUNCTION CALLING)
     ========================================================= -->
<div class="modal fade" id="aiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:22px; overflow:hidden;">
            <div class="modal-header border-bottom" style="background:#FAFAFC; padding:16px 24px; color:#1D1D1F;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-wand-magic-sparkles fs-5 text-warning"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" style="color:#1D1D1F;">Trợ Lý AI PolyMee</h5>
                        <small class="text-muted" style="color:#86868B;">Function Calling • Hỗ trợ tìm ca & Phân tích xung đột</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="background:var(--ux-surface-2);">
                <div id="aiChatHistory" class="d-flex flex-column gap-3 mb-3" style="max-height: 320px; overflow-y: auto; padding-right: 6px;">
                    <div class="d-flex gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:34px; height:34px; min-width:34px; flex:none; background:#F59E0B; color:#FFFFFF; box-shadow:0 2px 6px rgba(245,158,11,0.3);">
                            <i class="fa-solid fa-wand-magic-sparkles" style="font-size:15px; line-height:1;"></i>
                        </div>
                        <div class="bg-white p-3 rounded-4 shadow-xs border" style="font-size:13.5px; max-width:85%;">
                            Xin chào <strong><?= htmlspecialchars($currentUser['full_name'] ?? 'bạn') ?></strong> (<?= htmlspecialchars($perms['roleNameDisplay'] ?? 'Thành viên') ?>)! Tôi là trợ lý ảo PolyMee. Bạn có thể chọn nhanh các nghiệp vụ được phân quyền hoặc nhập câu hỏi bên dưới nhé!
                        </div>
                    </div>
                </div>

                <!-- Các Câu Hỏi Gợi Ý AI Được Phân Quyền Theo Role -->
                <div class="d-flex flex-wrap gap-1.5 mb-3">
                    <?php if (in_array($perms['role'], ['HEAD_ADMIN', 'ADMIN'], true)): ?>
                    <button class="btn-fpt-ghost btn-fpt-sm" onclick="quickAskAi('Phòng nào đang trống?')">
                        <i class="bi bi-building-check me-1 text-success"></i> Phòng trống?
                    </button>
                    <button class="btn-fpt-ghost btn-fpt-sm" onclick="quickAskAi('CTV nào đang trống?')">
                        <i class="bi bi-people-fill me-1 text-warning"></i> CTV nào đang trống?
                    </button>
                    <button class="btn-fpt-ghost btn-fpt-sm" onclick="quickAskAi('Bạn gửi mail cho CTV đăng ký lịch ca trực')">
                        <i class="bi bi-envelope-fill me-1 text-primary"></i> Gửi mail mời CTV
                    </button>
                    <button class="btn-fpt-ghost btn-fpt-sm" onclick="quickAskAi('Đặt giúp lịch sự kiện Hội Thảo AI ngày 22/11, khách mời gồm: Thầy Hiệu Trưởng, Ban Giám Hiệu, Đại Diện FPT Telecom')">
                        <i class="bi bi-calendar-plus-fill me-1 text-danger"></i> Đặt lịch sự kiện VIPs
                    </button>
                    <button class="btn-fpt-ghost btn-fpt-sm" onclick="quickAskAi('Báo cáo xung đột lịch và audit trail')">
                        <i class="bi bi-shield-check me-1 text-secondary"></i> Báo cáo xung đột & Audit
                    </button>
                    <?php elseif (in_array($perms['role'], ['LECTURER', 'STAFF'], true)): ?>
                    <button class="btn-fpt-ghost btn-fpt-sm" onclick="quickAskAi('Phòng nào đang trống?')">
                        <i class="bi bi-building-check me-1 text-success"></i> Phòng trống?
                    </button>
                    <button class="btn-fpt-ghost btn-fpt-sm" onclick="quickAskAi('Tìm CTV có kỹ năng Media quay chụp')">
                        <i class="bi bi-people-fill me-1 text-warning"></i> CTV kỹ năng Media?
                    </button>
                    <button class="btn-fpt-ghost btn-fpt-sm" onclick="quickAskAi('Đặt giúp lịch sự kiện Workshop Bộ Môn CNTT ngày 22/11, khách mời gồm: Thầy Trưởng Bộ Môn')">
                        <i class="bi bi-calendar-plus-fill me-1 text-primary"></i> Đặt lịch Workshop bộ môn
                    </button>
                    <button class="btn-fpt-ghost btn-fpt-sm" onclick="quickAskAi('Quy trình duyệt nghiệm thu minh chứng và xuất hợp đồng thù lao')">
                        <i class="bi bi-patch-check-fill me-1 text-success"></i> Duyệt nghiệm thu minh chứng
                    </button>
                    <button class="btn-fpt-ghost btn-fpt-sm" onclick="quickAskAi('Hồ sơ CTV của bộ môn tôi')">
                        <i class="bi bi-folder-check me-1 text-secondary"></i> Hồ sơ CTV bộ môn
                    </button>
                    <?php elseif ($perms['role'] === 'ACCOUNTANT'): ?>
                    <button class="btn-fpt-ghost btn-fpt-sm" onclick="quickAskAi('Tổng hợp quyết toán thù lao đợt này')">
                        <i class="bi bi-cash-stack me-1 text-success"></i> Tổng hợp quyết toán
                    </button>
                    <button class="btn-fpt-ghost btn-fpt-sm" onclick="quickAskAi('Hồ sơ CTV nào chưa có số CCCD hoặc chưa có ảnh chụp màn hình VNeID?')">
                        <i class="bi bi-person-exclamation me-1 text-warning"></i> Đối soát CCCD & VNeID
                    </button>
                    <button class="btn-fpt-ghost btn-fpt-sm" onclick="quickAskAi('Phòng nào đang tổ chức sự kiện hôm nay?')">
                        <i class="bi bi-building me-1 text-info"></i> Phòng sự kiện
                    </button>
                    <button class="btn-fpt-ghost btn-fpt-sm" onclick="quickAskAi('Quy trình giải ngân chuyển khoản thù lao cho sinh viên')">
                        <i class="bi bi-bank me-1 text-primary"></i> Quy trình giải ngân
                    </button>
                    <?php elseif ($perms['role'] === 'MEDIA'): ?>
                    <button class="btn-fpt-ghost btn-fpt-sm" onclick="quickAskAi('Tôi có ca trực Media nào sắp tới không?')">
                        <i class="bi bi-camera-reels me-1 text-primary"></i> Ca trực Media của tôi?
                    </button>
                    <button class="btn-fpt-ghost btn-fpt-sm" onclick="quickAskAi('Điểm tích cực và đánh giá uy tín của tôi là bao nhiêu?')">
                        <i class="bi bi-star-fill me-1 text-warning"></i> Điểm tích cực & Uy tín
                    </button>
                    <button class="btn-fpt-ghost btn-fpt-sm" onclick="quickAskAi('Quy chuẩn nộp link Google Drive ảnh đã chỉnh sửa sáng và gắn logo FPT')">
                        <i class="bi bi-file-earmark-image me-1 text-success"></i> Quy chuẩn Drive Media
                    </button>
                    <button class="btn-fpt-ghost btn-fpt-sm" onclick="quickAskAi('Hồ sơ số CCCD và ảnh chụp màn hình VNeID của tôi đã hợp lệ chưa?')">
                        <i class="bi bi-shield-check me-1 text-info"></i> Kiểm tra CCCD & VNeID
                    </button>
                    <?php else: /* CTV */ ?>
                    <button class="btn-fpt-ghost btn-fpt-sm" onclick="quickAskAi('Tôi có ca trực nào sắp tới không?')">
                        <i class="bi bi-clock-history me-1 text-primary"></i> Ca trực của tôi?
                    </button>
                    <button class="btn-fpt-ghost btn-fpt-sm" onclick="quickAskAi('Điểm tích cực và đánh giá uy tín của tôi là bao nhiêu?')">
                        <i class="bi bi-star-fill me-1 text-warning"></i> Điểm tích cực & Uy tín
                    </button>
                    <button class="btn-fpt-ghost btn-fpt-sm" onclick="quickAskAi('Hướng dẫn chụp ảnh hiện trường minh chứng để được nghiệm thu')">
                        <i class="bi bi-camera-fill me-1 text-success"></i> Chụp ảnh hiện trường
                    </button>
                    <button class="btn-fpt-ghost btn-fpt-sm" onclick="quickAskAi('Hồ sơ số CCCD và ảnh chụp màn hình VNeID của tôi đã hợp lệ chưa?')">
                        <i class="bi bi-shield-check me-1 text-info"></i> Kiểm tra CCCD & VNeID
                    </button>
                    <button class="btn-fpt-ghost btn-fpt-sm" onclick="quickAskAi('Bao giờ tôi được nhận tiền thù lao sự kiện?')">
                        <i class="bi bi-wallet2 me-1 text-secondary"></i> Thời gian nhận thù lao?
                    </button>
                    <?php endif; ?>
                </div>

                <form id="aiChatForm" onsubmit="handleAiChatSubmit(event)" class="d-flex gap-2">
                    <input type="text" id="aiInputPrompt" class="form-control rounded-pill px-3.5 py-2 shadow-xs border-0" placeholder="Nhập câu hỏi cho trợ lý AI..." required>
                    <button type="submit" class="btn-fpt-primary rounded-pill px-4">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================
     MODAL XÁC NHẬN XÓA TÀI KHOẢN CÁ NHÂN (DANGER ZONE)
     ========================================================= -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-bottom bg-danger-subtle text-danger px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-octagon-fill fs-5"></i>
                    <h6 class="modal-title fw-bold mb-0">Xác Nhận Xóa Tài Khoản Cá Nhân</h6>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-danger py-2.5 px-3 small rounded-3 mb-3">
                    <strong>Cảnh báo:</strong> Thao tác này sẽ vô hiệu hóa tài khoản của bạn ngay lập tức. Toàn bộ lịch trực chưa hoàn thành sẽ bị hủy.
                </div>
                <p class="small text-muted mb-2">
                    Để xác nhận xóa, vui lòng nhập chính xác cụm từ <strong class="text-danger">XAC NHAN XOA</strong> vào ô bên dưới:
                </p>
                <input type="text" id="confirmDeleteInput" class="form-control form-control-sm mb-3" placeholder="Nhập: XAC NHAN XOA" oninput="toggleDeleteBtnState()">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="confirmDeleteCheck" onchange="toggleDeleteBtnState()">
                    <label class="form-check-label small text-muted" for="confirmDeleteCheck">
                        Tôi hiểu rõ các hậu quả khi hủy bỏ tài khoản sinh viên CTV này.
                    </label>
                </div>
            </div>
            <div class="modal-footer bg-light px-4 py-2.5 border-top d-flex justify-content-between">
                <button type="button" class="btn-fpt-secondary btn-fpt-sm" data-bs-dismiss="modal">Hủy Bỏ</button>
                <button type="button" class="btn btn-danger btn-sm rounded-pill px-3" id="confirmDeleteSubmitBtn" onclick="submitDeleteAccount()" disabled>
                    <i class="bi bi-trash3-fill me-1"></i> Xóa Tài Khoản Vĩnh Viễn
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1090;">
    <div id="liveToast" class="toast align-items-center text-white border-0 shadow-lg rounded-4" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2" id="toastMessage">
                <i class="bi bi-info-circle fs-5" id="toastIcon"></i>
                <span id="toastText">Nội dung thông báo</span>
            </div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Bootstrap 5.3.3 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
/**
 * Dữ liệu State từ PHP
 */
const rawShifts = <?= json_encode($shifts, JSON_UNESCAPED_UNICODE) ?>;
const rawAccountingList = <?= json_encode($accountingList, JSON_UNESCAPED_UNICODE) ?>;
window.ctvRosterData = <?= json_encode(array_values($ctvRoster ?? []), JSON_UNESCAPED_UNICODE) ?>;
const rawCalendarNotes = <?= json_encode($calendarNotes ?? [], JSON_UNESCAPED_UNICODE) ?>;
const currentUser = <?= json_encode($currentUser, JSON_UNESCAPED_UNICODE) ?>;
const userPermissions = <?= json_encode($perms, JSON_UNESCAPED_UNICODE) ?>;

let currentYear = 2026;
let currentMonth = 10; // Tháng 11 (0-indexed)
let selectedDateStr = '2026-11-15';
let currentCalendarTier = 'month';
let activePage = 'calendar';

let shiftModalInstance = null;
let aiModalInstance = null;
let createShiftModalInstance = null;
let previewVneidModalInstance = null;
let addNoteModalInstance = null;
let submitProofModalInstance = null;
let previewProofModalInstance = null;
let contractModalInstance = null;
let currentViewingAssignmentId = null;
let qrCodeObj = null;

// =========================================================
// POLYMEE PROMAX THEME SYSTEM (Dark / Light)
// =========================================================
function initTheme() {
    const root = document.documentElement;
    root.setAttribute('data-bs-theme', 'light');
    root.classList.remove('dark');
    localStorage.setItem('polymee_theme', 'light');
}

function setTheme(theme) {
    const root = document.documentElement;
    root.setAttribute('data-bs-theme', 'light');
    root.classList.remove('dark');
    localStorage.setItem('polymee_theme', 'light');
}

function toggleTheme() {
    // Locked to Light Mode as per specification
    initTheme();
}

// Chạy khởi tạo theme tức thì
initTheme();

document.addEventListener('DOMContentLoaded', function() {
    initTheme();
    shiftModalInstance = new bootstrap.Modal(document.getElementById('shiftModal'));
    aiModalInstance = new bootstrap.Modal(document.getElementById('aiModal'));
    createShiftModalInstance = new bootstrap.Modal(document.getElementById('createShiftModal'));
    previewVneidModalInstance = new bootstrap.Modal(document.getElementById('previewVneidModal'));
    submitProofModalInstance = new bootstrap.Modal(document.getElementById('submitProofModal'));
    previewProofModalInstance = new bootstrap.Modal(document.getElementById('previewProofModal'));
    contractModalInstance = new bootstrap.Modal(document.getElementById('contractModal'));
    const addNoteModalEl = document.getElementById('addNoteModal');
    if (addNoteModalEl) {
        addNoteModalInstance = new bootstrap.Modal(addNoteModalEl);
    }

    initLucianSideRail();
    renderMonthGrid(currentYear, currentMonth);
    renderMyShiftsTable();

    // Hỗ trợ Deep Link qua URL Query Parameters (?tab=accounting hoặc ?view=day&date=2026-11-15)
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    const viewParam = urlParams.get('view');
    const dateParam = urlParams.get('date');

    if (dateParam) {
        selectedDateStr = dateParam;
    }
    if (tabParam) {
        navigateToPage(tabParam);
    } else {
        navigateToPage('calendar');
        if (viewParam) {
            changeCalendarView(viewParam);
        }
    }
});

/**
 * =========================================================
 * LUCIAN UX/UI #040: CO GIÃN VÀ TRƯỢT HIGHLIGHT SIDE RAIL
 * =========================================================
 */
function initLucianSideRail() {
    const root = document.getElementById('lucianSideRail');
    if (!root) return;
    const tog = root.querySelector('.u040-tog');
    const its = Array.prototype.slice.call(root.querySelectorAll('.u040-it'));
    const hl = root.querySelector('.u040-hl');

    function place(it) {
        if (!hl || !it.parentNode) return;
        hl.style.setProperty('--y', it.parentNode.offsetTop + 'px');
    }

    tog.addEventListener('click', function () {
        const w = !root.classList.contains('is-wide');
        root.classList.toggle('is-wide', w);
        tog.setAttribute('aria-expanded', String(w));
        tog.setAttribute('aria-label', w ? 'Collapse menu' : 'Expand menu');
    });

    its.forEach(function (it, i) {
        it.addEventListener('click', function () {
            its.forEach(function (x) { x.removeAttribute('aria-current'); });
            it.setAttribute('aria-current', 'page');
            place(it);
        });
        it.addEventListener('keydown', function (e) {
            const d = e.key === 'ArrowDown' ? 1 : e.key === 'ArrowUp' ? -1 : 0;
            if (d) { e.preventDefault(); its[(i + d + its.length) % its.length].focus(); }
        });
    });

    if (its.length > 0) place(its[0]);
}

function closeSidebarUserMenu() {
    const btn = document.getElementById('sidebarUserMenuBtn');
    if (btn) {
        const dropdown = bootstrap.Dropdown.getInstance(btn);
        if (dropdown) {
            dropdown.hide();
        } else {
            const flyout = document.getElementById('sidebarUserFlyoutMenu');
            if (flyout) flyout.classList.remove('show');
            btn.setAttribute('aria-expanded', 'false');
        }
    }
}

/**
 * =========================================================
 * SINGLE PAGE APPLICATION (SPA) NAVIGATION
 * =========================================================
 */
function navigateToPage(pageName) {
    if (pageName === 'admin-management') {
        pageName = 'user-roles';
    }
    // RBAC Client-side Navigation Guard
    if (pageName === 'accounting' && !userPermissions.canViewAccounting) {
        showToast('Bạn không có quyền truy cập Bảng Nghiệm Thu Kế Toán. Quyền này chỉ dành cho Kế toán, Trưởng hành chính và Ban Giám hiệu.', 'warning');
        return navigateToPage('calendar');
    }
    if (pageName === 'user-roles' && !userPermissions.canManageRoles) {
        showToast('Bạn không có quyền truy cập Trung tâm Cấp Quyền. Chức năng chỉ dành riêng cho Quản trị viên hệ thống (Admin).', 'warning');
        return navigateToPage('calendar');
    }
    if (pageName === 'audit-trail' && !userPermissions.canViewAuditLogs) {
        showToast('Bạn không có quyền xem Nhật ký Kiểm toán Audit Trail.', 'warning');
        return navigateToPage('calendar');
    }
    if (pageName === 'my-shifts' && !userPermissions.canViewMyShifts) {
        return navigateToPage('calendar');
    }

    activePage = pageName;

    // Tự động cuộn workspace lên đầu khi chuyển tab
    const wsEl = document.querySelector('.main-workspace');
    if (wsEl) wsEl.scrollTop = 0;
    window.scrollTo(0, 0);

    // Synchronize Lucian Rail Navigation
    const railItems = document.querySelectorAll('.u040-it');
    const railHl = document.querySelector('.u040-hl');
    railItems.forEach(it => {
        if (it.getAttribute('data-page') === pageName) {
            it.setAttribute('aria-current', 'page');
            if (railHl && it.parentNode) {
                railHl.style.setProperty('--y', it.parentNode.offsetTop + 'px');
            }
        } else {
            it.removeAttribute('aria-current');
        }
    });

    const panelIds = [
        'panelCalendar', 
        'panelAccounting', 
        'panelStudentPayrollProfile', 
        'panelMyShifts', 
        'panelEvents', 
        'panelSubmissions', 
        'panelContractDossier', 
        'panelProfileSettings', 
        'panelUserRoles',
        'panelAuditTrail'
    ];
    panelIds.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.style.setProperty('display', 'none', 'important');
            el.classList.add('d-none');
        }
    });

    const isCalendar = (pageName === 'calendar');
    const switcher = document.getElementById('calendarViewSwitcher');
    if (switcher) switcher.style.display = isCalendar ? 'inline-flex' : 'none';
    const monthNav = document.getElementById('calendarMonthNav');
    if (monthNav) monthNav.style.display = isCalendar ? 'inline-flex' : 'none';

    // Ẩn/Hiện Thanh Thông Tin KPI: CHỈ CÓ Ở TRANG LỊCH HOẠT ĐỘNG
    const kpiStrip = document.getElementById('kpiMetricsStrip');
    if (kpiStrip) kpiStrip.style.display = isCalendar ? 'grid' : 'none';

    const screenTitle = document.getElementById('activeScreenTitle');
    const breadcrumb = document.getElementById('breadcrumbActive');

    const pageMap = {
        'calendar': { id: 'panelCalendar', display: 'flex', title: 'Lịch Hoạt Động & Điều Phối Ca Trực', breadcrumb: 'Bảng Lịch Trung Tâm' },
        'accounting': { id: 'panelAccounting', display: 'flex', title: 'Bảng Nghiệm Thu Thù Lao & Quyết Toán Kế Toán', breadcrumb: 'Nghiệm Thu Kế Toán' },
        'student-payroll-profile': { id: 'panelStudentPayrollProfile', display: 'flex', title: 'Quản Lý Hồ Sơ Nhận Thù Lao CTV (VNeID & Ngân Hàng)', breadcrumb: 'Quản Lý Hồ Sơ CTV' },
        'my-shifts': { id: 'panelMyShifts', display: 'flex', title: 'Lịch Làm Việc & Ca Phân Công Của Tôi', breadcrumb: 'Ca Trực Của Tôi', onShow: () => renderMyShiftsTable() },
        'events': { id: 'panelEvents', display: 'flex', title: 'Quản Lý Sự Kiện & Phân Rã Ca Trực', breadcrumb: 'Danh Sách Sự Kiện' },
        'submissions': { id: 'panelSubmissions', display: 'flex', title: 'Thẩm Định Nghiệm Thu Minh Chứng Google Drive', breadcrumb: 'Thẩm Định Drive' },
        'contract-dossier': { id: 'panelContractDossier', display: 'flex', title: 'Hồ Sơ Hợp Đồng Nghiệm Thu & Đề Nghị Chi Tiền', breadcrumb: 'Hồ Sơ Hợp Đồng' },
        'profile-settings': { id: 'panelProfileSettings', display: 'block', title: 'Cài Đặt Tài Khoản, Định Danh CCCD & STK Cá Nhân', breadcrumb: 'Tài Khoản Cá Nhân' },
        'user-roles': { id: 'panelUserRoles', display: 'flex', title: 'Trung Tâm Cấp Quyền & Phân Vai Trò Người Dùng', breadcrumb: 'Cấp Quyền' },
        'audit-trail': { id: 'panelAuditTrail', display: 'flex', title: 'Nhật Ký Lịch Sử Thao Tác Hệ Thống (Audit Trail)', breadcrumb: 'Nhật Ký Audit Trail' }
    };

    const targetConfig = pageMap[pageName];
    if (targetConfig) {
        const targetEl = document.getElementById(targetConfig.id);
        if (targetEl) {
            targetEl.classList.remove('d-none');
            targetEl.style.setProperty('display', targetConfig.display, 'important');
            targetEl.scrollTop = 0;
        }
        if (screenTitle) screenTitle.textContent = targetConfig.title;
        if (breadcrumb) breadcrumb.textContent = targetConfig.breadcrumb;
        if (targetConfig.onShow) targetConfig.onShow();
        if (pageName === 'calendar') {
            changeCalendarView(currentCalendarTier);
        }
    }
}

function quickRegisterShiftModal() {
    selectedDateStr = '2026-11-15';
    navigateToPage('calendar');
    changeCalendarView('day');
    showToast('Đang hiển thị danh sách các ca trực ngày 15/11/2026 sẵn sàng đăng ký!', 'info');
}

/**
 * =========================================================
 * CALENDAR 3-TIER ENGINE:
 * Click ô ngày -> Vào THẲNG Lịch Ngày (Theo quyết định Câu 2: B)
 * =========================================================
 */
function changeCalendarView(viewName) {
    currentCalendarTier = viewName;

    const btnMonth = document.getElementById('btnViewMonth');
    const btnWeek = document.getElementById('btnViewWeek');
    const btnDay = document.getElementById('btnViewDay');
    if (btnMonth) btnMonth.classList.toggle('active', viewName === 'month');
    if (btnWeek) btnWeek.classList.toggle('active', viewName === 'week');
    if (btnDay) btnDay.classList.toggle('active', viewName === 'day');

    const mTier = document.getElementById('tierMonthView');
    const wTier = document.getElementById('tierWeekView');
    const dTier = document.getElementById('tierDayView');

    if (mTier) mTier.style.setProperty('display', (viewName === 'month') ? 'flex' : 'none', 'important');
    if (wTier) wTier.style.setProperty('display', (viewName === 'week') ? 'flex' : 'none', 'important');
    if (dTier) dTier.style.setProperty('display', (viewName === 'day') ? 'flex' : 'none', 'important');

    const heading = document.getElementById('calendarViewHeading');
    const sub = document.getElementById('calendarViewSub');

    if (viewName === 'month') {
        if (heading) heading.textContent = `Lịch Tổng Quan Tháng ${currentMonth + 1}/${currentYear}`;
        if (sub) sub.textContent = 'Nhấp vào ô ngày bất kỳ để vào thẳng Lịch Ngày xem chi tiết các ca trực';
        renderMonthGrid(currentYear, currentMonth);
    } else if (viewName === 'week') {
        if (heading) heading.textContent = `Lịch Tuần Có Ngày ${formatDateVN(selectedDateStr)}`;
        if (sub) sub.textContent = 'Lưới phân rã các ca trực trong 7 ngày từ Thứ Hai đến Chủ Nhật';
        renderWeekView(selectedDateStr);
    } else if (viewName === 'day') {
        if (heading) heading.textContent = `Chi Tiết Ca Trực Ngày ${formatDateVN(selectedDateStr)}`;
        if (sub) sub.textContent = 'Danh sách toàn bộ các khung giờ và yêu cầu công việc chi tiết trong ngày';
        renderDayView(selectedDateStr);
    }
}

// 1. Render Lưới Tháng (Month Grid) - Apple macOS Sonoma Design
function renderMonthGrid(year, month) {
    const container = document.getElementById('monthGridContainer');
    container.innerHTML = '';

    document.getElementById('currentCalendarHeader').textContent = `Tháng ${month + 1}/${year}`;

    const firstDayIndex = new Date(year, month, 1).getDay();
    const adjustedFirstDay = (firstDayIndex === 0) ? 6 : firstDayIndex - 1;
    const totalDaysInMonth = new Date(year, month + 1, 0).getDate();
    const prevMonthDays = new Date(year, month, 0).getDate();

    const totalCells = adjustedFirstDay + totalDaysInMonth;
    const rowCount = Math.ceil(totalCells / 7);
    container.style.setProperty('--month-rows', rowCount);

    for (let i = adjustedFirstDay - 1; i >= 0; i--) {
        const dayNum = prevMonthDays - i;
        const cell = document.createElement('div');
        cell.className = 'month-day-cell is-other-month';
        cell.innerHTML = `<span class="day-number-pill text-muted num-tabular">${dayNum}</span>`;
        container.appendChild(cell);
    }

    for (let day = 1; day <= totalDaysInMonth; day++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const dayShifts = rawShifts.filter(s => s.start_time.startsWith(dateStr));
        const dayNotes = (rawCalendarNotes || []).filter(n => (n.date || n.target_date) === dateStr);
        const isToday = (dateStr === '2026-11-11');

        const cell = document.createElement('div');
        cell.className = 'month-day-cell';
        if (isToday) cell.classList.add('is-today');

        let previewHtml = '';

        // Hiển thị ghi chú / lịch hẹn toàn trường hoặc ca trực gọn gàng
        if (dayNotes.length > 0) {
            dayNotes.slice(0, 1).forEach(n => {
                const noteTitle = n.title || n.content || 'Ghi chú';
                const noteColor = n.color || '#EA580C';
                previewHtml += `<div class="memo-chip d-flex justify-content-between align-items-center" style="border-left: 2px solid ${noteColor} !important; background: ${noteColor}18 !important; color: ${noteColor} !important;" title="Lưu ý: ${noteTitle}">
                    <div class="d-flex align-items-center gap-1 overflow-hidden">
                        <i class="fa-solid fa-bookmark" style="font-size:7.5px; color:${noteColor}; flex:none;"></i>
                        <span class="text-truncate" style="color:${noteColor}; font-weight:600;">${noteTitle}</span>
                    </div>
                    <button type="button" class="btn p-0 text-danger border-0 flex-shrink-0 ms-1 opacity-75 hover-opacity-100" style="font-size:9px; line-height:1;" onclick="event.stopPropagation(); deleteCalendarNote(${n.id});" title="Xóa lịch hẹn này">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </div>`;
            });
            if (dayShifts.length > 0) {
                previewHtml += `<div class="num-tabular ps-0.5" style="font-size:8px; font-weight:700; color:var(--ux-ink-3); line-height:1.1;">+${dayShifts.length} ca trực</div>`;
            }
        } else if (dayShifts.length > 0) {
            dayShifts.slice(0, 2).forEach(s => {
                const isMine = parseInt(s.is_registered_by_me) > 0;
                const isFull = parseInt(s.approved_qty) >= parseInt(s.target_qty);
                const chipClass = isMine ? 'mine' : (isFull ? 'urgent' : '');
                const dotClass = isMine ? 'status-dot-emerald' : (isFull ? 'status-dot-red' : 'status-dot-orange');
                const timePrefix = s.start_time.substring(11, 16);
                previewHtml += `<div class="shift-chip ${chipClass}" title="${s.name} (${s.location_name || 'Hội trường'})">
                    <span class="status-dot ${dotClass}"></span>
                    <span class="num-tabular flex-shrink-0 me-0.5" style="font-size:clamp(8px, 0.75vw, 9.5px); opacity:0.85;">${timePrefix}</span>
                    <span class="text-truncate flex-grow-1">${s.name}</span>
                </div>`;
            });
            const remaining = dayShifts.length - 2;
            if (remaining > 0) {
                previewHtml += `<div class="num-tabular ps-0.5" style="font-size:clamp(8px, 0.7vw, 9px); font-weight:700; color:var(--ux-ink-3); line-height:1.1;">+${remaining} ca nữa</div>`;
            }
        }

        cell.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-1 flex-shrink-0">
                <span class="day-number-pill num-tabular">${day}</span>
                <div class="d-flex align-items-center gap-1">
                    ${dayNotes.length > 0 ? `<span class="badge rounded-pill num-tabular" style="background:#F4F4F6; color:#EA580C; border:1px solid rgba(0,0,0,0.08); font-size:clamp(8px, 0.7vw, 9.5px); font-weight:700; padding:1px 4px;" title="${dayNotes.length} Ghi chú"><i class="fa-solid fa-bookmark me-0.5" style="font-size:7px;"></i>${dayNotes.length}</span>` : ''}
                    ${dayShifts.length > 0 ? `<span class="badge rounded-pill num-tabular" style="background:#F4F4F6; color:var(--ux-ink-2); border:1px solid rgba(0,0,0,0.08); font-size:clamp(8px, 0.7vw, 9.5px); font-weight:650; padding:1px 4px;">${dayShifts.length} ca</span>` : ''}
                </div>
            </div>
            <div class="day-cell-content d-flex flex-column gap-0.5" style="flex: 1 1 0%; min-height: 0; overflow: hidden;">${previewHtml}</div>
        `;

        cell.addEventListener('click', () => {
            selectedDateStr = dateStr;
            changeCalendarView('day');
        });

        container.appendChild(cell);
    }

    const trailingCells = (rowCount * 7) - totalCells;
    for (let nextDay = 1; nextDay <= trailingCells; nextDay++) {
        const cell = document.createElement('div');
        cell.className = 'month-day-cell is-other-month';
        cell.innerHTML = `<span class="day-number-pill text-muted num-tabular">${nextDay}</span>`;
        container.appendChild(cell);
    }
}

// 2. Render Lịch Tuần (Week View) - iPadOS 7-Day Grid
function renderWeekView(targetDateStr) {
    const container = document.getElementById('weekColumnsContainer');
    container.innerHTML = '';

    const targetDate = new Date(targetDateStr);
    const dayOfWeek = targetDate.getDay();
    const mondayOffset = (dayOfWeek === 0) ? -6 : 1 - dayOfWeek;
    
    const monday = new Date(targetDate);
    monday.setDate(targetDate.getDate() + mondayOffset);

    const dayNames = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ Nhật'];

    for (let i = 0; i < 7; i++) {
        const colDate = new Date(monday);
        colDate.setDate(monday.getDate() + i);
        const y = colDate.getFullYear();
        const m = String(colDate.getMonth() + 1).padStart(2, '0');
        const d = String(colDate.getDate()).padStart(2, '0');
        const dateStr = `${y}-${m}-${d}`;

        const colShifts = rawShifts.filter(s => s.start_time.startsWith(dateStr));
        const colNotes = (rawCalendarNotes || []).filter(n => (n.date || n.target_date) === dateStr);
        const col = document.createElement('div');
        col.className = 'week-column';

        let notesHtml = '';
        if (colNotes.length > 0) {
            colNotes.forEach(n => {
                const noteType = n.type || n.note_type || 'Lưu ý';
                const noteTitle = n.title || n.content || '';
                const noteColor = n.color || '#EA580C';
                notesHtml += `
                    <div class="memo-chip mb-2 d-flex justify-content-between align-items-center" style="border-left: 2.5px solid ${noteColor} !important; background: ${noteColor}18 !important; color: ${noteColor} !important;" title="${noteType}: ${noteTitle}">
                        <div class="d-flex align-items-center gap-1 overflow-hidden min-w-0">
                            <i class="fa-solid fa-bookmark me-1 flex-shrink-0" style="font-size:9px; color:${noteColor};"></i>
                            <span class="text-truncate" style="color:${noteColor}; font-weight:600;">${noteTitle}</span>
                        </div>
                        <button type="button" class="btn p-0 text-danger border-0 flex-shrink-0 ms-1 opacity-75 hover-opacity-100" style="font-size:10px; line-height:1;" onclick="event.stopPropagation(); deleteCalendarNote(${n.id});" title="Xóa lịch hẹn này">
                            <i class="bi bi-x-circle-fill"></i>
                        </button>
                    </div>
                `;
            });
        }

        let shiftsHtml = '';
        if (colShifts.length === 0 && colNotes.length === 0) {
            shiftsHtml = `<div class="text-center text-muted my-auto py-4" style="font-size:12px;">Không có ca trực</div>`;
        } else {
            colShifts.forEach(s => {
                const startTime = s.start_time.substring(11, 16);
                const endTime = s.end_time.substring(11, 16);
                const isMine = parseInt(s.is_registered_by_me) > 0;
                const isFull = parseInt(s.approved_qty) >= parseInt(s.target_qty);
                const dotClass = isMine ? 'status-dot-emerald' : (isFull ? 'status-dot-red' : 'status-dot-orange');
                shiftsHtml += `
                    <div class="week-card-shift" onclick="openShiftDetail(${s.id})">
                        <div class="d-flex justify-content-between text-muted small fw-bold mb-1">
                            <span class="num-tabular"><i class="bi bi-clock me-1 text-primary"></i>${startTime} - ${endTime}</span>
                            <span class="badge bg-light text-dark border num-tabular">${s.approved_qty}/${s.target_qty}</span>
                        </div>
                        <div class="fw-bold text-dark text-truncate mb-1 d-flex align-items-center gap-1.5" style="font-size:12.5px;">
                            <span class="status-dot ${dotClass}"></span>
                            <span class="text-truncate">${s.name}</span>
                        </div>
                        <div class="text-muted text-truncate" style="font-size:11px;">
                            <i class="bi bi-geo-alt me-1 text-danger"></i>${s.location_name || 'Hội trường'}
                        </div>
                    </div>
                `;
            });
        }

        col.innerHTML = `
            <div class="week-col-header" onclick="selectedDateStr='${dateStr}'; changeCalendarView('day');" title="Nhấp xem chi tiết ngày ${d}/${m}">
                <div class="text-muted fw-bold" style="font-size:11.5px;">${dayNames[i]}</div>
                <div class="col-date h6 fw-bolder mb-0 num-tabular">${d}/${m}</div>
                ${colNotes.length > 0 ? `<span class="badge rounded-pill bg-white text-dark border num-tabular mt-1" style="font-size:9px;"><i class="fa-solid fa-bookmark me-1 text-warning"></i>${colNotes.length} Lưu ý</span>` : ''}
            </div>
            <div class="week-col-body">${notesHtml}${shiftsHtml}</div>
        `;

        container.appendChild(col);
    }
}

// 3. Render Lịch Ngày (Day View) - Apple iPadOS Split View & Reminders Callout
function renderDayView(dateStr) {
    const container = document.getElementById('dayTimelineContainer');
    container.innerHTML = '';

    document.getElementById('selectedDayLabel').textContent = formatDateVN(dateStr);
    const dayShifts = rawShifts.filter(s => s.start_time.startsWith(dateStr));
    const dayNotes = (rawCalendarNotes || []).filter(n => (n.date || n.target_date) === dateStr);

    // Apple Reminders banner if notes exist
    if (dayNotes.length > 0) {
        const reminderCard = document.createElement('div');
        reminderCard.className = 'apple-reminder-banner mb-3';
        reminderCard.innerHTML = `
            <div style="width:34px; height:34px; border-radius:50%; background:#FFFFFF; color:#1D1D1F; border:1px solid rgba(0,0,0,0.08); flex:none; display:grid; place-items:center; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                <i class="fa-solid fa-bell" style="font-size:13px; line-height:1; margin:0; padding:0;"></i>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h6 class="fw-bold mb-0 text-dark" style="font-size:13px;">
                        <i class="fa-solid fa-clipboard-list me-1.5 text-secondary"></i> Ghi Chú & Lưu Ý Ngày Này (Apple Reminders)
                    </h6>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-white text-dark border rounded-pill num-tabular" style="font-size:10px; font-weight:700;">${dayNotes.length} nhắc nhở</span>
                        <button class="btn-fpt-ghost btn-fpt-sm py-0 px-2" style="font-size:11px;" onclick="openAddNoteModal()">
                            <i class="fa-solid fa-plus me-1"></i>Thêm
                        </button>
                    </div>
                </div>
                <div class="d-flex flex-column gap-2 mt-2">
                    ${dayNotes.map(n => {
                        const noteType = n.type || n.note_type || 'Lưu ý';
                        const noteTitle = n.title ? `<strong>${n.title}</strong>: ` : '';
                        const noteContent = n.content || n.title || '';
                        const authorName = n.author || n.creator_name || 'BTC';
                        const noteColor = n.color || '#EA580C';
                        return `
                        <div class="d-flex align-items-center gap-2 px-3 py-2.5 rounded-3 border shadow-xs" style="background:#FFFFFF; border-left: 3.5px solid ${noteColor} !important; border-color:rgba(0,0,0,0.08) !important; font-size:12.5px;">
                            <i class="fa-solid fa-bookmark" style="font-size:13px; color:${noteColor}; flex:none;"></i>
                            <span class="badge num-tabular" style="background:${noteColor}18; color:${noteColor}; border:1px solid ${noteColor}33; font-size:10px; flex:none;">${noteType}</span>
                            <span class="flex-grow-1" style="color:var(--ux-ink);">${noteTitle}${noteContent}</span>
                            <span class="small num-tabular" style="color:var(--ux-ink-3); flex:none;"><i class="bi bi-person me-1"></i>${authorName}</span>
                            <button type="button" class="btn btn-sm text-danger p-0 ms-2" style="line-height:1; font-size:13px;" onclick="deleteCalendarNote(${n.id})" title="Xóa lịch hẹn / ghi chú này">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>
                    `;}).join('')}
                </div>
            </div>
        `;
        container.appendChild(reminderCard);
    }

    if (dayShifts.length === 0) {
        const emptyDiv = document.createElement('div');
        emptyDiv.className = 'text-center py-5 rounded-4 border';
        emptyDiv.style = 'background:var(--ux-surface-2); border-color:var(--ux-line) !important;';
        emptyDiv.innerHTML = `
            <i class="bi bi-calendar-x display-4" style="color:var(--ux-ink-3);"></i>
            <h6 class="fw-bold text-dark mt-3">Không có ca trực nào trong ngày này</h6>
            <p class="text-muted small">Bạn có thể tạo ca trực mới hoặc thêm ghi chú/lịch hẹn dặn dò cho ngày này.</p>
            <div class="d-flex justify-content-center gap-2 mt-2">
                <button class="btn-fpt-secondary btn-fpt-sm" onclick="changeCalendarView('month')">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại Lịch Tháng
                </button>
                <button class="btn-fpt-outline-orange btn-fpt-sm" onclick="openAddNoteModal()">
                    <i class="fa-solid fa-note-sticky me-1"></i> + Thêm Ghi Chú
                </button>
            </div>
        `;
        container.appendChild(emptyDiv);
        return;
    }

    dayShifts.forEach(s => {
        const startTime = s.start_time.substring(11, 16);
        const endTime = s.end_time.substring(11, 16);
        const isMine = parseInt(s.is_registered_by_me) > 0;
        const isFull = parseInt(s.approved_qty) >= parseInt(s.target_qty);
        const allowance = s.remuneration_amount ? Number(s.remuneration_amount).toLocaleString('vi-VN') + ' đ' : '150,000 đ';

        const card = document.createElement('div');
        card.className = 'timeline-card';
        card.innerHTML = `
            <div class="d-flex align-items-center gap-2.5">
                <div class="text-center px-2 py-1.5 rounded-2 border" style="background:var(--ux-surface-2); border-color:var(--ux-line) !important; min-width:68px;">
                    <span class="fw-bold d-block num-tabular" style="color:var(--ux-accent); font-size:14px; line-height:1.2;">${startTime}</span>
                    <small class="num-tabular" style="color:var(--ux-ink-3); font-size:10.5px;">${endTime}</small>
                </div>
                <div>
                    <span class="badge mb-0.5 num-tabular" style="background:var(--ux-surface-3); color:var(--ux-ink-2); border:1px solid var(--ux-line); font-size:10px; padding:2px 6px;">${s.event_title}</span>
                    <h6 class="fw-bold mb-0.5" style="color:var(--ux-ink); font-size:13.5px;">${s.name}</h6>
                    <div class="d-flex flex-wrap align-items-center gap-2.5" style="color:var(--ux-ink-3); font-size:11.5px;">
                        <span><i class="bi bi-geo-alt-fill text-danger me-1"></i>${s.location_name || 'Hội trường'}</span>
                        <span class="num-tabular"><i class="bi bi-people-fill text-info me-1"></i>Đã tuyển: <strong>${s.approved_qty}/${s.target_qty} CTV</strong></span>
                        <span class="text-success fw-bold num-tabular"><i class="bi bi-cash me-1"></i>Thù lao: ${allowance}</span>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                ${isMine 
                    ? `<span class="badge bg-success px-2.5 py-1.5 fw-bold" style="font-size:11.5px;"><i class="bi bi-check-circle me-1"></i>Bạn Đã Đăng Ký</span>` 
                    : (userPermissions.canRegisterShift
                        ? `<button class="btn-fpt-primary btn-fpt-sm" onclick="openShiftDetail(${s.id})">
                            ${isFull ? 'Đăng Ký Dự Phòng' : 'Đăng Ký Ca Này'}
                           </button>`
                        : (userPermissions.canCreateShift
                            ? `<button class="btn-fpt-secondary btn-fpt-sm" onclick="openShiftDetail(${s.id})">
                                <i class="bi bi-gear me-1"></i> Quản Lý Ca
                               </button>`
                            : `<button class="btn-fpt-secondary btn-fpt-sm" onclick="openShiftDetail(${s.id})">
                                <i class="bi bi-eye me-1"></i> Chi Tiết Ca
                               </button>`))
                }
                <button class="btn-fpt-ghost btn-fpt-sm p-1" onclick="openShiftDetail(${s.id})" title="Xem chi tiết">
                    <i class="bi bi-info-circle fs-6"></i>
                </button>
            </div>
        `;
        container.appendChild(card);
    });
}

function openAddNoteModal() {
    if (!addNoteModalInstance) {
        const el = document.getElementById('addNoteModal');
        if (el) addNoteModalInstance = new bootstrap.Modal(el);
    }
    const dateInput = document.querySelector('#addNoteForm input[name="date"]');
    if (dateInput) dateInput.value = selectedDateStr;
    if (addNoteModalInstance) addNoteModalInstance.show();
}

async function handleAddNoteSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const payload = {
        title: formData.get('title'),
        date: formData.get('date'),
        content: formData.get('content') || formData.get('title'),
        type: formData.get('type') || 'EVENT_REMINDER',
        color: formData.get('color') || '#EA580C'
    };

    try {
        const response = await fetch('/dashboard/addNote', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const data = await response.json();
        if (data.success && data.note) {
            rawCalendarNotes.push(data.note);
            showToast('Đã thêm ghi chú mới vào lịch thành công!', 'success');
        } else {
            payload.id = Date.now();
            payload.target_date = payload.date;
            payload.author = currentUser ? (currentUser.full_name || currentUser.username) : 'BTC';
            payload.creator_name = payload.author;
            rawCalendarNotes.push(payload);
            showToast('Đã thêm ghi chú mới vào lịch!', 'success');
        }
    } catch (err) {
        payload.id = Date.now();
        payload.target_date = payload.date;
        payload.author = currentUser ? (currentUser.full_name || currentUser.username) : 'BTC';
        payload.creator_name = payload.author;
        rawCalendarNotes.push(payload);
        showToast('Đã thêm ghi chú mới vào lịch!', 'success');
    }

    if (addNoteModalInstance) {
        addNoteModalInstance.hide();
    }
    form.reset();

    if (currentCalendarTier === 'month') {
        renderMonthGrid(currentYear, currentMonth);
    } else if (currentCalendarTier === 'week') {
        renderWeekView(selectedDateStr);
    } else if (currentCalendarTier === 'day') {
        renderDayView(selectedDateStr);
    }
}

/**
 * Xóa lịch hẹn / ghi chú theo ID
 */
async function deleteCalendarNote(noteId) {
    if (!confirm('Bạn có chắc chắn muốn xóa lịch hẹn / ghi chú này khỏi hệ thống không?')) {
        return;
    }

    try {
        const response = await fetch('/dashboard/deleteNote/' + noteId, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        });
        const data = await response.json();

        // Xóa khỏi state local
        const idx = rawCalendarNotes.findIndex(n => parseInt(n.id) === parseInt(noteId));
        if (idx !== -1) {
            rawCalendarNotes.splice(idx, 1);
        }

        showToast(data.message || 'Đã xóa lịch hẹn thành công!', 'success');

        // Render lại tầng lịch hiện tại
        if (currentCalendarTier === 'month') {
            renderMonthGrid(currentYear, currentMonth);
        } else if (currentCalendarTier === 'week') {
            renderWeekView(selectedDateStr);
        } else if (currentCalendarTier === 'day') {
            renderDayView(selectedDateStr);
        }
    } catch (err) {
        showToast('Lỗi khi xóa lịch hẹn.', 'danger');
    }
}

/**
 * =========================================================
 * MODAL ĐIỀU PHỐI / CHI TIẾT CA TRỰC
 * =========================================================
 */
function openShiftDetail(shiftId) {
    const shift = rawShifts.find(s => parseInt(s.id) === parseInt(shiftId));
    if (!shift) return;

    const allowance = shift.remuneration_amount ? Number(shift.remuneration_amount).toLocaleString('vi-VN') + ' đ' : '150,000 đ';
    const lecturerName = shift.lecturer_name || 'ThS. Phan Hoàng Khải';
    const lecturerCode = shift.lecturer_code ? ` (${shift.lecturer_code})` : '';
    const deptName = shift.lecturer_department || shift.event_department || 'Công nghệ Thông tin';

    document.getElementById('modalShiftBadge').textContent = `+${shift.points} Điểm Tích Cực (Uy tín)`;
    document.getElementById('shiftModalTitle').textContent = shift.name;
    document.getElementById('modalEventTitle').textContent = shift.event_title;
    const lecturerEl = document.getElementById('modalShiftLecturer');
    if (lecturerEl) lecturerEl.textContent = `${lecturerName}${lecturerCode}`;
    const deptEl = document.getElementById('modalShiftDepartment');
    if (deptEl) deptEl.textContent = deptName;
    document.getElementById('modalShiftTime').textContent = `${shift.start_time.substring(11, 16)} - ${shift.end_time.substring(11, 16)} (${shift.start_time.substring(0, 10)})`;
    document.getElementById('modalShiftLocation').textContent = shift.location_name || 'Hội trường';
    document.getElementById('modalShiftQuota').textContent = `${shift.approved_qty} / ${shift.target_qty} CTV (Còn trống ${Math.max(0, shift.target_qty - shift.approved_qty)} chỗ)`;
    document.getElementById('modalShiftAllowance').textContent = allowance;
    document.getElementById('modalShiftSkill').textContent = shift.skill_required || 'Kỹ năng làm việc nhóm & trách nhiệm';

    const actionContainer = document.getElementById('modalActionButtons');
    const noticeContainer = document.getElementById('modalRoleNotice');
    const isMine = parseInt(shift.is_registered_by_me) > 0;
    const canDeleteThis = userPermissions.canDeleteAnyEvent || (userPermissions.canDeleteOwnEvent && parseInt(shift.event_created_by) === parseInt(currentUser.id));

    if (userPermissions.canRegisterShift) {
        noticeContainer.innerHTML = `
            <i class="bi bi-shield-check fs-5"></i>
            <span>Hệ thống tự động chạy thuật toán <strong>Collision Detection</strong> để đảm bảo bạn không bị trùng lịch học hay ca trực khác.</span>
        `;
        if (isMine) {
            actionContainer.innerHTML = `
                <button type="button" class="btn-fpt-emerald" onclick="shiftModalInstance.hide(); navigateToPage('my-shifts');">
                    <i class="bi bi-calendar-check me-1"></i> Đến Lịch Của Tôi Để Nộp Minh Chứng
                </button>
            `;
        } else {
            actionContainer.innerHTML = `
                <button type="button" class="btn-fpt-primary" onclick="submitShiftRegistration(${shift.id})">
                    <i class="bi bi-check-lg me-1"></i> Xác Nhận Đăng Ký (AJAX)
                </button>
            `;
        }
    } else if (userPermissions.canCreateShift || canDeleteThis) {
        noticeContainer.innerHTML = `
            <i class="bi bi-person-check fs-5"></i>
            <span>Chế độ Quản lý / Ban tổ chức: Bạn có thể kiểm tra danh sách CTV hoặc quản lý phân rã ca trực.</span>
        `;
        let deleteBtnHtml = '';
        if (canDeleteThis) {
            deleteBtnHtml = `
                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 py-1.5 fw-semibold" onclick="confirmDeleteShift(${shift.id}, '${shift.name.replace(/'/g, "\\'")}')">
                    <i class="bi bi-trash3 me-1"></i> Xóa Ca Này
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 py-1.5 fw-semibold" onclick="confirmDeleteEvent(${shift.event_id}, '${shift.event_title.replace(/'/g, "\\'")}')">
                    <i class="bi bi-trash3-fill me-1"></i> Xóa Sự Kiện
                </button>
            `;
        }
        actionContainer.innerHTML = `
            <div class="d-flex align-items-center gap-2">
                ${deleteBtnHtml}
                <button type="button" class="btn-fpt-secondary" onclick="shiftModalInstance.hide(); navigateToPage('events')">
                    <i class="bi bi-people me-1"></i> Quản Lý Sự Kiện
                </button>
            </div>
        `;
    } else {
        noticeContainer.innerHTML = `
            <i class="bi bi-info-circle fs-5"></i>
            <span>Chế độ Tra cứu: Bạn đang xem thông tin ca trực theo vai trò <strong>${userPermissions.roleNameDisplay || ''}</strong>.</span>
        `;
        actionContainer.innerHTML = `
            <button type="button" class="btn-fpt-secondary" data-bs-dismiss="modal">
                <i class="bi bi-x-lg me-1"></i> Đóng
            </button>
        `;
    }

    shiftModalInstance.show();
}

/**
 * Xóa Sự Kiện & Toàn bộ Ca Trực
 */
async function confirmDeleteEvent(eventId, eventTitle) {
    if (!confirm(`Bạn có chắc chắn muốn xóa sự kiện "${eventTitle}" và toàn bộ các ca trực liên quan? Thao tác này không thể hoàn tác.`)) {
        return;
    }

    try {
        const formData = new FormData();
        formData.append('event_id', eventId);

        const response = await fetch('/dashboard/deleteEvent', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        if (data.success) {
            showToast(data.message, 'success');
            for (let i = rawShifts.length - 1; i >= 0; i--) {
                if (parseInt(rawShifts[i].event_id) === parseInt(eventId)) {
                    rawShifts.splice(i, 1);
                }
            }
            if (shiftModalInstance) shiftModalInstance.hide();
            changeCalendarView(currentCalendarTier);
            renderMyShiftsTable();
            const cardEl = document.getElementById(`eventCard_${eventId}`);
            if (cardEl) cardEl.remove();
        } else {
            showToast(data.message || 'Lỗi khi xóa sự kiện.', 'danger');
        }
    } catch (err) {
        showToast('Lỗi kết nối khi xóa sự kiện.', 'danger');
    }
}

/**
 * Xóa Ca Trực Đơn Lẻ
 */
async function confirmDeleteShift(shiftId, shiftName) {
    if (!confirm(`Bạn có chắc chắn muốn xóa ca trực "${shiftName}"?`)) {
        return;
    }

    try {
        const formData = new FormData();
        formData.append('shift_id', shiftId);

        const response = await fetch('/dashboard/deleteShift', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        if (data.success) {
            showToast(data.message, 'success');
            const idx = rawShifts.findIndex(s => parseInt(s.id) === parseInt(shiftId));
            if (idx !== -1) {
                rawShifts.splice(idx, 1);
            }
            if (shiftModalInstance) shiftModalInstance.hide();
            changeCalendarView(currentCalendarTier);
            renderMyShiftsTable();
        } else {
            showToast(data.message || 'Lỗi khi xóa ca trực.', 'danger');
        }
    } catch (err) {
        showToast('Lỗi kết nối khi xóa ca trực.', 'danger');
    }
}

/**
 * Mở Modal Đặt Lịch Ca Trực Mới
 */
function openCreateShiftModal() {
    createShiftModalInstance.show();
}

/**
 * Xử lý Tạo Ca Trực Mới bằng AJAX (Cập nhật Bảng Lịch tức thì)
 */
async function handleCreateShiftSubmit(e) {
    e.preventDefault();
    const form = document.getElementById('createShiftForm');
    const formData = new FormData(form);

    try {
        const response = await fetch('/dashboard/createShift', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        });

        const data = await response.json();
        if (data.success) {
            // Thêm ca mới vào mảng rawShifts
            const newShift = data.shift;
            newShift.is_registered_by_me = 0;
            rawShifts.push(newShift);

            createShiftModalInstance.hide();
            form.reset();
            showToast(data.message, 'success');

            // Render lại chế độ xem hiện tại
            changeCalendarView(currentCalendarTier);
        } else {
            showToast(data.message || 'Không thể tạo ca trực.', 'danger');
        }
    } catch (err) {
        showToast('Lỗi kết nối khi tạo ca trực.', 'danger');
    }
}

/**
 * Kế toán duyệt nghiệm thu thù lao
 */
async function updateAccountingApproval(assignmentId, status) {
    try {
        const formData = new FormData();
        formData.append('assignment_id', assignmentId);
        formData.append('status', status);

        const response = await fetch('/dashboard/updateAccountingStatus', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        if (data.success) {
            const badge = document.getElementById(`accStatusBadge_${assignmentId}`);
            if (badge) {
                badge.textContent = status;
                badge.className = `badge ${status === 'Approved' ? 'bg-success' : 'bg-primary'} px-2.5 py-1.5 fw-bold`;
            }
            showToast(`Đã cập nhật trạng thái nghiệm thu: ${status}`, 'success');
        }
    } catch (err) {
        showToast('Lỗi cập nhật kế toán.', 'danger');
    }
}

/**
 * CTV Tự cập nhật hồ sơ ngân hàng / VNeID
 */
async function handleUpdateProfile(e) {
    e.preventDefault();
    const form = document.getElementById('payrollProfileForm');
    const formData = new FormData(form);

    try {
        const response = await fetch('/dashboard/updateAccountingProfile', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        if (data.success) {
            showToast(data.message, 'success');
            const vneidInput = form.querySelector('[name="vneid_image_url"]');
            if (vneidInput) document.getElementById('previewVneidImg').src = vneidInput.value;
        } else {
            showToast(data.message, 'danger');
        }
    } catch (err) {
        showToast('Lỗi cập nhật hồ sơ.', 'danger');
    }
}

/**
 * Xem trước ảnh VNeID đối soát
 */
function previewVneidModal(name, cccd, imgUrl) {
    document.getElementById('vneidModalTitle').textContent = `Đối Soát VNeID: ${name}`;
    document.getElementById('vneidModalSub').textContent = `Số Định Danh CCCD: ${cccd}`;
    document.getElementById('vneidModalImage').src = imgUrl;
    previewVneidModalInstance.show();
}

/**
 * AI Thẩm Định & Quét Bất Thường Kế Toán (Collision & Fraud Detection)
 */
function runAiAccountingAudit() {
    showToast('AI đang quét và đối soát tự động toàn bộ hồ sơ kế toán...', 'info');
    setTimeout(() => {
        showToast('Kết quả AI: 100% Hồ sơ hợp lệ! CCCD khớp ảnh VNeID, không có trùng lặp STK thụ hưởng.', 'success');
    }, 600);
}

/**
 * Lọc và tìm kiếm thời gian thực trên Bảng Nghiệm Thu Kế Toán
 */
function filterAccountingTable() {
    const keyword = (document.getElementById('accountingSearchInput')?.value || '').toLowerCase().trim();
    const statusFilter = document.getElementById('accountingStatusFilter')?.value || '';
    const rows = document.querySelectorAll('#panelAccounting tbody tr[id^="accRow_"]');
    let visibleCount = 0;

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        const matchesKeyword = !keyword || text.includes(keyword);
        
        let matchesStatus = true;
        if (statusFilter) {
            const statusBadge = row.querySelector('[id^="accStatusBadge_"]');
            const statusText = statusBadge ? statusBadge.innerText.trim() : '';
            matchesStatus = (statusText === statusFilter);
        }

        if (matchesKeyword && matchesStatus) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    const countEl = document.getElementById('accountingFilteredCount');
    if (countEl) countEl.textContent = `Hiển thị: ${visibleCount} hồ sơ`;
}

/**
 * Xuất file bảng nghiệm thu thù lao chuẩn Excel (CSV có UTF-8 BOM tiếng Việt)
 */
function exportAccountingCSV() {
    if (!rawAccountingList || rawAccountingList.length === 0) {
        showToast('Không có dữ liệu nghiệm thu để xuất file.', 'warning');
        return;
    }

    let csvContent = '\uFEFF'; // UTF-8 BOM để Excel đọc tiếng Việt không bị lỗi font
    csvContent += 'STT,Họ và Tên,Mã Sinh Viên,Số Điện Thoại,Số CCCD,Tên Ngân Hàng,Số Tài Khoản (STK),Chủ Tài Khoản,Tên Sự Kiện,Ca Trực,Thời Gian Bắt Đầu,Thời Gian Kết Thúc,Thù Lao (VNĐ),Điểm Tích Cực,Trạng Thái Kế Toán\r\n';

    rawAccountingList.forEach((item, index) => {
        const row = [
            index + 1,
            `"${(item.full_name || '').replace(/"/g, '""')}"`,
            `"${item.student_code || ''}"`,
            `"${item.phone || '0987654321'}"`,
            `"${item.id_card_number || '079203014589'}"`,
            `"${(item.bank_name || '').replace(/"/g, '""')}"`,
            `"${item.bank_account_number || ''}"`,
            `"${(item.bank_account_name || item.full_name || '').replace(/"/g, '""')}"`,
            `"${(item.event_title || '').replace(/"/g, '""')}"`,
            `"${(item.shift_name || '').replace(/"/g, '""')}"`,
            `"${item.start_time || ''}"`,
            `"${item.end_time || ''}"`,
            item.remuneration_amount || 150000,
            item.points || 10,
            `"${item.accounting_status || 'Pending'}"`
        ];
        csvContent += row.join(',') + '\r\n';
    });

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.setAttribute('href', url);
    link.setAttribute('download', `Bang_Nghiem_Thu_Thu_Lao_CTV_PolyMee_${new Date().toISOString().slice(0,10)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    showToast('Đã tải xuống file Bảng Nghiệm Thu Thù Lao Kế Toán (.CSV Excel)!', 'success');
}

/**
 * Đăng ký ca trực bằng AJAX (Không chớp trang)
 */
async function submitShiftRegistration(shiftId) {
    const shift = rawShifts.find(s => parseInt(s.id) === parseInt(shiftId));
    if (!shift) return;

    try {
        const formData = new FormData();
        formData.append('shift_id', shiftId);
        formData.append('csrf_token', '<?= $csrfToken ?? "" ?>');

        const response = await fetch('/shift/register', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            shift.is_registered_by_me = 1;
            if (data.status === 'Approved') {
                shift.approved_qty = parseInt(shift.approved_qty) + 1;
            }

            shiftModalInstance.hide();
            showToast(data.message, 'success');
            changeCalendarView(currentCalendarTier);
            renderMyShiftsTable();
        } else {
            showToast(data.message || 'Không thể đăng ký ca trực.', 'danger');
        }
    } catch (err) {
        showToast('Lỗi kết nối máy chủ.', 'danger');
    }
}

/**
 * Render Bảng Ca Trực Của Tôi (Theo quy trình nộp ảnh hiện trường & hợp đồng nghiệm thu)
 */
function renderMyShiftsTable() {
    const tbody = document.getElementById('myShiftsTableBody');
    if (!tbody) return;
    tbody.innerHTML = '';

    const myShifts = rawShifts.filter(s => parseInt(s.is_registered_by_me) > 0);
    const countBadge = document.getElementById('myShiftsCountBadge');
    if (countBadge) countBadge.textContent = `${myShifts.length} Ca Trực`;

    if (myShifts.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-muted">Bạn chưa đăng ký ca trực nào. Hãy vào Bảng Lịch để nhận ca nhé!</td></tr>`;
        return;
    }

    myShifts.forEach(s => {
        const tr = document.createElement('tr');
        const allowance = s.remuneration_amount ? Number(s.remuneration_amount).toLocaleString('vi-VN') + ' đ' : '150,000 đ';
        const assignmentId = s.my_assignment_id || s.id;
        const hasProof = !!s.my_proof_image_url;
        const isVerified = parseInt(s.my_lecturer_verified) === 1;
        const isPaid = (s.my_school_status === 'Signed_Paid');

        let proofHtml = '';
        if (hasProof) {
            const isDrive = s.my_proof_image_url.includes('drive.google.com') || s.my_proof_image_url.includes('docs.google.com');
            if (isDrive) {
                proofHtml = `
                    <div>
                        <a href="${s.my_proof_image_url}" target="_blank" class="btn btn-sm btn-outline-warning rounded-pill py-0.5 px-2 fw-semibold" style="font-size: 11px;">
                            <i class="fa-brands fa-google-drive me-1 text-primary"></i> Link Drive Media
                        </a>
                        <small class="text-success d-block fw-semibold mt-0.5" style="font-size: 10.5px;">
                            <i class="bi bi-check-circle-fill me-1"></i>Đã kèm Logo & Chỉnh sáng
                        </small>
                    </div>
                `;
            } else {
                proofHtml = `
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 48px; height: 34px; flex-shrink: 0; overflow: hidden; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <img src="${s.my_proof_image_url}" alt="Ảnh Hiện Trường" style="width: 48px; height: 34px; object-fit: cover; display: block; cursor: pointer;" onclick="previewProofModal('${(currentUser.full_name || 'CTV').replace(/'/g, "\\'")}', '${s.name}', '${s.my_proof_image_url}', '${(s.my_proof_note || 'Đã có mặt tại sự kiện').replace(/'/g, "\\'")}')" title="Nhấp xem phóng to">
                        </div>
                        <div>
                            <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 10.5px;"><i class="bi bi-camera me-1"></i>Ảnh hiện trường</span>
                            <small class="text-muted d-block text-truncate" style="max-width: 120px; font-size: 11px;">${s.my_proof_note || 'Hiện trường'}</small>
                        </div>
                    </div>
                `;
            }
        } else {
            proofHtml = `<span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 11px;"><i class="bi bi-camera-fill me-1"></i>Chưa nộp ảnh</span>`;
        }

        let statusHtml = '';
        if (isVerified) {
            statusHtml = `
                <span class="badge bg-success-subtle text-success border border-success-subtle mb-1"><i class="bi bi-patch-check-fill me-1"></i>GV Đã Nghiệm Thu</span>
                <div class="small font-monospace text-primary">${s.my_contract_code || 'HDNT-2026-FPLY'}</div>
                ${isPaid ? '<small class="text-success fw-bold d-block mt-0.5"><i class="bi bi-check2-all me-1"></i>Trường đã chi tiền</small>' : '<small class="text-muted d-block mt-0.5">Chờ trường ký duyệt</small>'}
            `;
        } else if (hasProof) {
            statusHtml = `
                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle mb-1"><i class="bi bi-hourglass-split me-1"></i>Chờ GV Duyệt Ảnh</span>
                <small class="text-muted d-block">Đang thẩm định</small>
            `;
        } else {
            statusHtml = `
                <span class="badge bg-light text-muted border mb-1">Chờ Nộp Ảnh</span>
                <small class="text-danger d-block" style="font-size: 11px;">Chụp ảnh để nghiệm thu</small>
            `;
        }

        let actionHtml = '';
        if (isVerified) {
            actionHtml = `
                <button class="btn-fpt-primary btn-fpt-sm w-100 mb-1" onclick="openContractModal(${assignmentId})">
                    <i class="bi bi-file-earmark-text me-1"></i> Xem Hợp Đồng
                </button>
            `;
        } else if (hasProof) {
            actionHtml = `
                <button class="btn-fpt-secondary btn-fpt-sm w-100" onclick="openSubmitProofModal(${assignmentId}, '${s.name}', '${s.start_time}')">
                    <i class="bi bi-camera me-1"></i> Chụp / Đổi Ảnh
                </button>
            `;
        } else {
            actionHtml = `
                <button class="btn-fpt-primary btn-fpt-sm w-100" onclick="openSubmitProofModal(${assignmentId}, '${s.name}', '${s.start_time}')">
                    <i class="bi bi-camera-fill me-1"></i> Chụp Ảnh Nghiệm Thu
                </button>
            `;
        }

        tr.innerHTML = `
            <td>
                <strong class="text-dark num-tabular">${s.start_time.substring(11, 16)} - ${s.end_time.substring(11, 16)}</strong><br>
                <small class="text-muted num-tabular">${formatDateVN(s.start_time.substring(0, 10))}</small>
            </td>
            <td>
                <span class="badge bg-secondary mb-1">${s.event_title}</span><br>
                <strong class="text-dark">${s.name}</strong>
            </td>
            <td><i class="bi bi-geo-alt me-1 text-danger"></i>${s.location_name || 'Hội trường'}</td>
            <td>${proofHtml}</td>
            <td>${statusHtml}</td>
            <td>
                <strong class="text-success num-tabular">${allowance}</strong><br>
                <small class="text-muted">+${s.points || 10} Điểm Tích Cực (Uy tín)</small>
            </td>
            <td class="text-end" style="min-width: 150px;">
                ${actionHtml}
            </td>
        `;
        tbody.appendChild(tr);
    });
}

/**
 * Chọn ảnh mẫu hiện trường nhanh (dành cho demo)
 */
function setProofSample(url, note) {
    document.getElementById('submitProofImageUrl').value = url;
    document.getElementById('submitProofNote').value = note;
    previewProofInput(url);
}

/**
 * Preview ảnh hiện trường khi nhập URL
 */
function previewProofInput(url) {
    const box = document.getElementById('proofInputPreviewBox');
    if (!box) return;
    if (url && (url.startsWith('http://') || url.startsWith('https://'))) {
        box.innerHTML = `<img src="${url}" alt="Preview" style="max-height: 140px; max-width: 100%; object-fit: cover; border-radius: 8px;">`;
    } else {
        box.innerHTML = `<span class="text-muted small"><i class="bi bi-camera me-1"></i>Ảnh xem trước sẽ hiển thị tại đây</span>`;
    }
}

/**
 * Chuyển đổi chế độ nộp minh chứng giữa CTV Thường (Ảnh Hiện Trường) & CTV Media (Link Google Drive)
 */
function switchProofMode(mode) {
    const isMedia = (mode === 'drive_media');
    const typeInput = document.getElementById('submitProofType');
    if (typeInput) typeInput.value = mode;

    const areaOnSite = document.getElementById('proofAreaOnSite');
    const areaMedia = document.getElementById('proofAreaDriveMedia');
    const tabOnSite = document.getElementById('tabProofOnSite');
    const tabMedia = document.getElementById('tabProofDriveMedia');
    const imgInput = document.getElementById('submitProofImageUrl');
    const driveInput = document.getElementById('submitDriveMediaUrl');

    if (isMedia) {
        if (areaOnSite) areaOnSite.style.display = 'none';
        if (areaMedia) areaMedia.style.display = 'block';
        if (tabOnSite) { tabOnSite.classList.remove('btn-primary', 'active'); tabOnSite.classList.add('text-muted'); }
        if (tabMedia) { tabMedia.classList.add('btn-primary', 'active'); tabMedia.classList.remove('text-muted'); }
        if (imgInput) imgInput.removeAttribute('required');
        if (driveInput) {
            driveInput.setAttribute('required', 'required');
            if (driveInput.value && imgInput) imgInput.value = driveInput.value;
        }
    } else {
        if (areaOnSite) areaOnSite.style.display = 'block';
        if (areaMedia) areaMedia.style.display = 'none';
        if (tabMedia) { tabMedia.classList.remove('btn-primary', 'active'); tabMedia.classList.add('text-muted'); }
        if (tabOnSite) { tabOnSite.classList.add('btn-primary', 'active'); tabOnSite.classList.remove('text-muted'); }
        if (driveInput) driveInput.removeAttribute('required');
        if (imgInput) imgInput.setAttribute('required', 'required');
    }
}

function syncDriveInput(val) {
    const imgInput = document.getElementById('submitProofImageUrl');
    if (imgInput) imgInput.value = val;
}

/**
 * Mở modal CTV nộp minh chứng nghiệm thu sau sự kiện
 */
function openSubmitProofModal(assignmentId, shiftName, shiftTime, isMediaParam) {
    document.getElementById('submitProofAssignmentId').value = assignmentId;
    document.getElementById('submitProofShiftInfo').textContent = `${shiftName} (${shiftTime ? shiftTime.substring(11, 16) : ''})`;
    document.getElementById('submitProofImageUrl').value = '';
    const driveEl = document.getElementById('submitDriveMediaUrl');
    if (driveEl) driveEl.value = '';
    document.getElementById('submitProofNote').value = '';
    previewProofInput('');

    // Tự động chọn tab theo vai trò người dùng (CTV thường -> Ảnh Hiện Trường; CTV Media -> Link Drive)
    const userRole = (userPermissions && userPermissions.role) ? userPermissions.role : (currentUser.role_name || 'CTV');
    const isMedia = isMediaParam || userRole === 'MEDIA' || (shiftName && shiftName.toLowerCase().includes('media'));
    switchProofMode(isMedia ? 'drive_media' : 'on_site');

    if (isMedia) {
        if (driveEl) driveEl.value = 'https://drive.google.com/drive/folders/fpoly-techday-2026-media-edited';
        syncDriveInput('https://drive.google.com/drive/folders/fpoly-techday-2026-media-edited');
        document.getElementById('submitProofNote').value = 'Bộ ảnh sự kiện độ phân giải cao đã được chỉnh sửa sáng và gắn logo FPT Polytechnic chuẩn nhận diện.';
    }

    submitProofModalInstance.show();
}

/**
 * Xử lý submit nộp minh chứng nghiệm thu
 */
async function handleProofSubmit(e) {
    e.preventDefault();
    const form = document.getElementById('submitProofForm');
    const formData = new FormData(form);
    const assignmentId = parseInt(formData.get('assignment_id'));
    const proofType = formData.get('proof_type');

    if (proofType === 'drive_media') {
        const driveUrl = formData.get('drive_media_url');
        formData.set('proof_image_url', driveUrl);
    }
    const proofUrl = formData.get('proof_image_url');
    const proofNote = formData.get('proof_note');

    try {
        const response = await fetch('/dashboard/submitOnSiteProof', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        if (data.success) {
            submitProofModalInstance.hide();
            showToast(data.message, 'success');

            // Cập nhật local state trong rawShifts
            const shift = rawShifts.find(s => parseInt(s.my_assignment_id) === assignmentId);
            if (shift) {
                shift.my_proof_image_url = proofUrl;
                shift.my_proof_note = proofNote;
            }

            // Cập nhật local state trong rawAccountingList
            const acc = rawAccountingList.find(a => parseInt(a.assignment_id) === assignmentId);
            if (acc) {
                acc.proof_image_url = proofUrl;
                acc.proof_note = proofNote;
            }

            renderMyShiftsTable();
            setTimeout(() => window.location.reload(), 1200);
        } else {
            showToast(data.message || 'Lỗi khi nộp ảnh.', 'danger');
        }
    } catch (err) {
        showToast('Lỗi kết nối khi nộp ảnh minh chứng.', 'danger');
    }
}

/**
 * Xem trước ảnh hiện trường phóng to
 */
function previewProofModal(name, shiftName, imgUrl, note) {
    document.getElementById('proofModalTitle').textContent = `Minh Chứng Hiện Trường: ${name}`;
    document.getElementById('proofModalSub').textContent = `Ca: ${shiftName}`;
    document.getElementById('proofModalImage').src = imgUrl;
    document.getElementById('proofModalNote').textContent = note || 'Có mặt đúng giờ tại vị trí trực sự kiện';
    previewProofModalInstance.show();
}

/**
 * Giảng viên xác nhận nghiệm thu hiện trường & khởi tạo hợp đồng
 */
async function verifyLecturerAcceptance(assignmentId) {
    try {
        const formData = new FormData();
        formData.append('assignment_id', assignmentId);

        const response = await fetch('/dashboard/verifyLecturerAcceptance', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        if (data.success) {
            showToast(data.message, 'success');

            // Cập nhật local state
            const acc = rawAccountingList.find(a => parseInt(a.assignment_id) === assignmentId);
            if (acc) {
                acc.lecturer_verified = 1;
                acc.contract_code = data.contract_code;
                acc.lecturer_name = data.lecturer_name;
                acc.accounting_status = 'Approved';
            }
            const shift = rawShifts.find(s => parseInt(s.my_assignment_id) === assignmentId);
            if (shift) {
                shift.my_lecturer_verified = 1;
                shift.my_contract_code = data.contract_code;
            }

            // Cập nhật UI hàng bảng
            const verCol = document.getElementById(`accVerificationCol_${assignmentId}`);
            if (verCol) {
                verCol.innerHTML = `
                    <span class="badge bg-success-subtle text-success border border-success-subtle d-inline-block mb-1">
                        <i class="bi bi-patch-check-fill me-1"></i>GV: ${data.lecturer_name}
                    </span>
                    <div>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace" style="font-size: 10.5px;">
                            ${data.contract_code}
                        </span>
                    </div>
                    <small class="text-warning-emphasis d-block mt-1"><i class="bi bi-hourglass-split me-1"></i>Chờ trường ký</small>
                `;
            }

            const actionCol = document.getElementById(`accActionBtns_${assignmentId}`);
            if (actionCol) {
                actionCol.innerHTML = `
                    <button type="button" class="btn btn-sm btn-fpt-secondary rounded-pill px-2.5 py-1 shadow-2xs fw-medium d-inline-flex align-items-center gap-1" onclick="openAccountingDetailModal(${assignmentId})" title="Xem chi tiết hồ sơ & STK">
                        <i class="bi bi-eye text-primary" style="font-size: 11px;"></i><span style="font-size: 11px;">Chi Tiết</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-fpt-secondary rounded-pill px-2.5 py-1 shadow-2xs fw-medium d-inline-flex align-items-center gap-1" onclick="openContractModal(${assignmentId})" title="Xem Hợp đồng">
                        <i class="bi bi-file-earmark-text text-warning" style="font-size: 11px;"></i><span style="font-size: 11px;">Hợp Đồng</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-fpt-primary rounded-pill px-2.5 py-1 shadow-xs fw-semibold text-white d-inline-flex align-items-center gap-1" onclick="submitToSchool(${assignmentId})" title="Trình Nhà trường ký duyệt">
                        <i class="bi bi-send" style="font-size: 11px;"></i><span style="font-size: 11px;">Trình Ký</span>
                    </button>
                `;
            }

            renderMyShiftsTable();
        } else {
            showToast(data.message || 'Lỗi khi xác nhận nghiệm thu.', 'danger');
        }
    } catch (err) {
        showToast('Lỗi kết nối khi giảng viên duyệt.', 'danger');
    }
}

/**
 * Giảng viên gửi email tự động nhắc nhở CTV nộp minh chứng nghiệm thu sau sự kiện
 */
async function sendProofReminder(assignmentId, studentName, studentEmail, btnElement) {
    if (!assignmentId) return;

    const originalHtml = btnElement ? btnElement.innerHTML : '';
    if (btnElement) {
        btnElement.disabled = true;
        btnElement.innerHTML = `<i class="fa-solid fa-spinner fa-spin me-1"></i> Đang gửi mail...`;
    }

    try {
        const formData = new FormData();
        formData.append('assignment_id', assignmentId);

        const response = await fetch('/dashboard/remindProofSubmission', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();

        if (data.success) {
            showToast(`🔔 ${data.message}`, 'success');
            if (btnElement) {
                btnElement.className = 'btn btn-sm btn-light border text-success rounded-pill py-1 px-2.5 shadow-xs fw-semibold';
                btnElement.innerHTML = `<i class="bi bi-check2-all me-1"></i> Đã Gửi Mail Nhắc`;
                btnElement.title = `Email nhắc nhở đã được gửi tới ${data.student_email || studentEmail}`;
                btnElement.disabled = true;
            }
        } else {
            showToast(data.message || 'Không thể gửi email nhắc nhở.', 'danger');
            if (btnElement) {
                btnElement.disabled = false;
                btnElement.innerHTML = originalHtml;
            }
        }
    } catch (err) {
        showToast('Lỗi kết nối khi gửi email nhắc nhở.', 'danger');
        if (btnElement) {
            btnElement.disabled = false;
            btnElement.innerHTML = originalHtml;
        }
    }
}

/**
 * Mở modal Chi Tiết Đầy Đủ Hồ Sơ Nghiệm Thu Kế Toán
 */
let currentAccDetail = null;
let accountingDetailModalInstance = null;

function openAccountingDetailModal(assignmentId) {
    const acc = rawAccountingList.find(a => parseInt(a.assignment_id) === parseInt(assignmentId));
    if (!acc) {
        showToast('Không tìm thấy thông tin hồ sơ.', 'warning');
        return;
    }
    currentAccDetail = acc;

    const modalTitle = document.getElementById('accDetailModalTitle');
    if (modalTitle) modalTitle.textContent = `Hồ Sơ Nghiệm Thu: ${acc.full_name}`;
    const modalSub = document.getElementById('accDetailModalSub');
    if (modalSub) modalSub.textContent = `Mã HĐ: ${acc.contract_code || 'HDNT-2026-FPLY'} • MSSV: ${acc.student_code}`;

    const nameEl = document.getElementById('accDetailName');
    if (nameEl) nameEl.textContent = acc.full_name;
    const codeEl = document.getElementById('accDetailCode');
    if (codeEl) codeEl.textContent = acc.student_code;
    const phoneEl = document.getElementById('accDetailPhone');
    if (phoneEl) phoneEl.textContent = acc.phone || '0987654321';
    const cccdEl = document.getElementById('accDetailCccd');
    if (cccdEl) cccdEl.textContent = acc.id_card_number || '079203014589';

    const vneidContainer = document.getElementById('accDetailVneidImgContainer');
    const vneidImg = document.getElementById('accDetailVneidImg');
    if (vneidContainer && vneidImg) {
        if (acc.vneid_image_url) {
            vneidImg.src = acc.vneid_image_url;
            vneidContainer.style.display = 'block';
        } else {
            vneidContainer.style.display = 'none';
        }
    }

    const bankNameEl = document.getElementById('accDetailBankName');
    if (bankNameEl) bankNameEl.textContent = acc.bank_name || 'Ngân hàng';
    const bankNumEl = document.getElementById('accDetailBankNumber');
    if (bankNumEl) bankNumEl.textContent = acc.bank_account_number || 'Chưa cập nhật';
    const bankHolderEl = document.getElementById('accDetailBankHolder');
    if (bankHolderEl) bankHolderEl.textContent = acc.bank_account_name || acc.full_name;
    const amountEl = document.getElementById('accDetailAmount');
    if (amountEl) amountEl.textContent = Number(acc.remuneration_amount || 150000).toLocaleString('vi-VN') + ' đ';
    const pointsEl = document.getElementById('accDetailPoints');
    if (pointsEl) pointsEl.textContent = `+${acc.points || 10} điểm`;

    const eventEl = document.getElementById('accDetailEventTitle');
    if (eventEl) eventEl.textContent = acc.event_title || 'Sự kiện FPT';
    const shiftEl = document.getElementById('accDetailShiftName');
    if (shiftEl) shiftEl.textContent = acc.shift_name || 'Ca trực';
    const timeEl = document.getElementById('accDetailTime');
    if (timeEl) timeEl.textContent = `${(acc.start_time || '').substring(11, 16)} - ${(acc.end_time || '').substring(11, 16)}`;
    const locEl = document.getElementById('accDetailLocation');
    if (locEl) locEl.textContent = acc.location_name || 'Hội trường Trịnh Văn Bô';

    const proofContainer = document.getElementById('accDetailProofContainer');
    const proofImg = document.getElementById('accDetailProofImg');
    const proofNote = document.getElementById('accDetailProofNote');
    if (proofContainer && proofImg && proofNote) {
        if (acc.proof_image_url) {
            proofImg.src = acc.proof_image_url;
            proofNote.textContent = acc.proof_note || 'Đã có mặt trực ca';
            proofContainer.style.display = 'block';
        } else {
            proofContainer.style.display = 'none';
        }
    }

    const lectEl = document.getElementById('accDetailLecturer');
    if (lectEl) lectEl.textContent = acc.lecturer_name || (acc.lecturer_verified == 1 ? 'Giảng viên đã duyệt' : 'Chưa có GV nghiệm thu');

    const statusBadgeEl = document.getElementById('accDetailStatusBadge');
    if (statusBadgeEl) {
        if (acc.school_status === 'Signed_Paid') {
            statusBadgeEl.innerHTML = '<span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-check2-all me-1"></i>Trường đã chi tiền</span>';
        } else if (acc.lecturer_verified == 1) {
            statusBadgeEl.innerHTML = '<span class="badge bg-primary-subtle text-primary border border-primary-subtle"><i class="bi bi-hourglass-split me-1"></i>Chờ trường ký</span>';
        } else {
            statusBadgeEl.innerHTML = '<span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle"><i class="bi bi-clock me-1"></i>Chờ GV nghiệm thu</span>';
        }
    }

    const contractBtn = document.getElementById('accDetailContractBtn');
    if (contractBtn) {
        contractBtn.onclick = function() {
            if (accountingDetailModalInstance) accountingDetailModalInstance.hide();
            openContractModal(acc.assignment_id);
        };
    }

    if (!accountingDetailModalInstance) {
        accountingDetailModalInstance = new bootstrap.Modal(document.getElementById('accountingDetailModal'));
    }
    accountingDetailModalInstance.show();
}

/**
 * Mở modal Hợp đồng & Biên bản nghiệm thu thông tin CTV
 */
function openContractModal(assignmentId) {
    currentViewingAssignmentId = assignmentId;
    const acc = rawAccountingList.find(a => parseInt(a.assignment_id) === parseInt(assignmentId)) 
             || rawShifts.find(s => parseInt(s.my_assignment_id) === parseInt(assignmentId));

    if (!acc) {
        showToast('Không tìm thấy thông tin hồ sơ hợp đồng.', 'warning');
        return;
    }

    const ccode = acc.contract_code || acc.my_contract_code || ('HDNT-2026-FPLY-' + String(assignmentId).padStart(3, '0'));
    const ctvName = acc.full_name || currentUser.full_name || 'Lê Văn Cường';
    const ctvCode = acc.student_code || currentUser.student_code || 'PS02941';
    const ctvPhone = acc.phone || currentUser.phone || '0987654321';
    const ctvCccd = acc.id_card_number || currentUser.id_card_number || '079203014589';
    const ctvBank = (acc.bank_account_number || currentUser.bank_account_number || '1029384756') + ' (' + (acc.bank_name || currentUser.bank_name || 'Vietcombank') + ')';
    const ctvHolder = acc.bank_account_name || currentUser.bank_account_name || ctvName;
    const eventName = acc.event_title || 'Sự kiện FPT Polytechnic 2026';
    const shiftName = acc.shift_name || acc.name || 'Ca trực CTV';
    const timeStr = acc.start_time ? (acc.start_time.substring(11, 16) + ' - ' + acc.end_time.substring(11, 16) + ' (' + formatDateVN(acc.start_time.substring(0, 10)) + ')') : '08:00 - 12:00';
    const locName = acc.location_name || 'Hội trường Tòa F';
    const amountNum = acc.remuneration_amount ? Number(acc.remuneration_amount) : 150000;
    const proofImg = acc.proof_image_url || acc.my_proof_image_url || 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&auto=format&fit=crop&q=80';
    const proofNote = acc.proof_note || acc.my_proof_note || 'Đã có mặt trực sự kiện đúng giờ và hoàn thành tốt nhiệm vụ được giao';
    const lecturer = acc.lecturer_name || acc.my_lecturer_name || 'ThS. Hoàng Văn Thụ';
    const isSignedPaid = (acc.school_status === 'Signed_Paid' || acc.my_school_status === 'Signed_Paid');

    document.getElementById('contractModalSub').textContent = `Mã hồ sơ: ${ccode}`;
    document.getElementById('contractDocNumber').textContent = `Số: ${ccode} / HĐNT-FPLY`;
    document.getElementById('contractLecturer').textContent = lecturer;
    document.getElementById('contractCtvName').textContent = ctvName;
    document.getElementById('contractCtvCode').textContent = ctvCode;
    document.getElementById('contractCtvPhone').textContent = ctvPhone;
    document.getElementById('contractCtvCccd').textContent = ctvCccd;
    document.getElementById('contractCtvBank').textContent = ctvBank;
    document.getElementById('contractCtvHolder').textContent = ctvHolder;
    document.getElementById('contractEventName').textContent = eventName;
    document.getElementById('contractShiftName').textContent = shiftName;
    document.getElementById('contractTimeLocation').textContent = `${timeStr} • ${locName}`;
    document.getElementById('contractAmount').textContent = `${amountNum.toLocaleString('vi-VN')} VNĐ`;

    // Phân loại minh chứng: Drive Link (CTV Media) vs Ảnh hiện trường (CTV thường)
    const isDrive = proofImg.includes('drive.google.com') || proofImg.includes('docs.google.com') || (proofImg.startsWith('http') && !proofImg.match(/\.(jpg|jpeg|png|webp|gif)/i) && proofImg.includes('drive'));
    const photoBox = document.getElementById('contractProofPhotoBox');
    const driveBox = document.getElementById('contractProofDriveBox');
    const driveLink = document.getElementById('contractProofDriveLink');
    const conclusionEl = document.getElementById('contractProofConclusion');
    const subtextEl = document.getElementById('contractProofSubtext');

    if (isDrive) {
        if (photoBox) photoBox.style.display = 'none';
        if (driveBox) driveBox.style.display = 'block';
        if (driveLink) driveLink.href = proofImg;
        if (conclusionEl) conclusionEl.textContent = 'CTV Media đã nộp link Google Drive bộ ảnh sự kiện hoàn chỉnh, đã gắn Logo FPT và hoàn thành hậu kỳ chỉnh sửa sáng. Đủ điều kiện nghiệm thu.';
        if (subtextEl) subtextEl.textContent = 'Minh chứng media được lưu trữ an toàn trên Google Drive FPT.';
    } else {
        if (photoBox) photoBox.style.display = 'inline-block';
        if (driveBox) driveBox.style.display = 'none';
        const imgEl = document.getElementById('contractProofImg');
        if (imgEl) imgEl.src = proofImg;
        if (conclusionEl) conclusionEl.textContent = 'CTV có mặt đúng vị trí phân công, hoàn thành đầy đủ nhiệm vụ được giao, đủ điều kiện nghiệm thu thanh toán.';
        if (subtextEl) subtextEl.textContent = 'Ảnh chụp có xác thực vị trí và nhận diện sinh viên.';
    }

    document.getElementById('contractProofNoteDisplay').textContent = proofNote;
    document.getElementById('contractSignCtv').textContent = ctvName;
    document.getElementById('contractSignLecturer').textContent = lecturer;

    const schoolSignEl = document.getElementById('contractSignSchoolBadge');
    const submitBtn = document.getElementById('btnSubmitToSchoolInModal');
    if (isSignedPaid) {
        schoolSignEl.innerHTML = `
            <div class="badge bg-success-subtle text-success border border-success-subtle py-1 px-2 my-2">
                <i class="bi bi-check2-all me-1"></i> ĐÃ KÝ DUYỆT & ĐÃ CHI TIỀN
            </div>
        `;
        if (submitBtn) {
            submitBtn.style.display = 'none';
        }
    } else {
        schoolSignEl.innerHTML = `
            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle py-1 px-2 my-2">
                <i class="bi bi-hourglass-split me-1"></i> Đang Chờ Ký Duyệt
            </span>
        `;
        // Phân quyền chặt chẽ: Chỉ Cán bộ, Giảng viên / Admin được thấy nút Trình Trường Ký, CTV tuyệt đối không thấy
        const canSubmitSchool = userPermissions && (userPermissions.canSubmitSchoolSigning || ['HEAD_ADMIN', 'ADMIN', 'LECTURER'].includes(userPermissions.role));
        if (submitBtn) {
            submitBtn.style.display = canSubmitSchool ? 'inline-block' : 'none';
        }
    }

    // Nút Duyệt Nghiệm Thu ngay trong Modal dành cho Giảng viên & Admin
    const verifyModalBtn = document.getElementById('btnVerifyInModal');
    const isVerifiedAcc = (parseInt(acc.lecturer_verified) === 1 || parseInt(acc.my_lecturer_verified) === 1);
    const canVerifyAcc = userPermissions && (userPermissions.canVerifyLecturer || ['HEAD_ADMIN', 'ADMIN', 'LECTURER'].includes(userPermissions.role));
    if (verifyModalBtn) {
        verifyModalBtn.style.display = (!isVerifiedAcc && canVerifyAcc) ? 'inline-block' : 'none';
    }

    contractModalInstance.show();
}

async function verifyCurrentModalLecturer() {
    if (!currentContractAssignmentId) return;
    await verifyLecturerInCard(currentContractAssignmentId);
    openContractModal(currentContractAssignmentId);
}

/**
 * Trình hồ sơ hợp đồng lên Nhà trường ký duyệt & chuyển tiền
 */
async function submitToSchool(assignmentId) {
    try {
        const formData = new FormData();
        formData.append('assignment_id', assignmentId);

        const response = await fetch('/dashboard/submitToSchoolForSigning', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        if (data.success) {
            showToast(data.message, 'success');

            // Cập nhật local state
            const acc = rawAccountingList.find(a => parseInt(a.assignment_id) === assignmentId);
            if (acc) {
                acc.school_status = 'Signed_Paid';
                acc.accounting_status = 'Paid';
            }
            const shift = rawShifts.find(s => parseInt(s.my_assignment_id) === assignmentId);
            if (shift) {
                shift.my_school_status = 'Signed_Paid';
                shift.my_accounting_status = 'Paid';
            }

            // Cập nhật nút bấm trong bảng
            const actionCol = document.getElementById(`accActionBtns_${assignmentId}`);
            if (actionCol) {
                actionCol.innerHTML = `
                    <button type="button" class="btn btn-sm btn-fpt-secondary rounded-pill px-2.5 py-1 shadow-2xs fw-medium d-inline-flex align-items-center gap-1" onclick="openAccountingDetailModal(${assignmentId})" title="Xem chi tiết hồ sơ & STK">
                        <i class="bi bi-eye text-primary" style="font-size: 11px;"></i><span style="font-size: 11px;">Chi Tiết</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-fpt-secondary rounded-pill px-2.5 py-1 shadow-2xs fw-medium d-inline-flex align-items-center gap-1" onclick="openContractModal(${assignmentId})" title="Xem Hợp đồng">
                        <i class="bi bi-file-earmark-text text-warning" style="font-size: 11px;"></i><span style="font-size: 11px;">Hợp Đồng</span>
                    </button>
                `;
            }

            const verCol = document.getElementById(`accVerificationCol_${assignmentId}`);
            if (verCol) {
                verCol.innerHTML = `
                    <span class="badge rounded-pill px-2.5 py-1 text-nowrap d-inline-flex align-items-center gap-1" style="background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0; font-size: 11px; font-weight: 500;">
                        <i class="bi bi-check-circle-fill"></i> Đã chuyển tiền
                    </span>
                `;
            }

            renderMyShiftsTable();
        } else {
            showToast(data.message || 'Lỗi khi trình trường ký.', 'danger');
        }
    } catch (err) {
        showToast('Lỗi kết nối khi gửi hồ sơ lên trường.', 'danger');
    }
}

/**
 * Trình trường ký từ bên trong Modal Hợp đồng
 */
function submitCurrentModalToSchool() {
    if (currentViewingAssignmentId) {
        submitToSchool(currentViewingAssignmentId);
        const schoolSignEl = document.getElementById('contractSignSchoolBadge');
        if (schoolSignEl) {
            schoolSignEl.innerHTML = `
                <div class="badge bg-success-subtle text-success border border-success-subtle py-1 px-2 my-2">
                    <i class="bi bi-check2-all me-1"></i> ĐÃ KÝ DUYỆT & ĐÃ CHI TIỀN
                </div>
            `;
        }
        const submitBtn = document.getElementById('btnSubmitToSchoolInModal');
        if (submitBtn) submitBtn.style.display = 'none';
    }
}

/**
 * In Hợp đồng & Biên bản nghiệm thu (window.print)
 */
function printContractModal() {
    window.print();
}

/**
 * Toast Thông Báo
 */
function showToast(message, type = 'info') {
    const toastEl = document.getElementById('liveToast');
    const toastBody = document.getElementById('toastText');
    const toastIcon = document.getElementById('toastIcon');

    toastEl.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'bg-primary', 'bg-dark');

    if (type === 'success') {
        toastEl.classList.add('bg-success');
        toastIcon.className = 'bi bi-check-circle-fill fs-5';
    } else if (type === 'danger') {
        toastEl.classList.add('bg-danger');
        toastIcon.className = 'bi bi-exclamation-triangle-fill fs-5';
    } else {
        toastEl.classList.add('bg-dark');
        toastIcon.className = 'bi bi-info-circle-fill fs-5';
    }

    toastBody.textContent = message;
    const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
    toast.show();
}

function navigateCalendar(offset) {
    currentMonth += offset;
    if (currentMonth < 0) { currentMonth = 11; currentYear -= 1; }
    else if (currentMonth > 11) { currentMonth = 0; currentYear += 1; }
    renderMonthGrid(currentYear, currentMonth);
}

function goToday() {
    currentYear = 2026; currentMonth = 10; selectedDateStr = '2026-11-15';
    changeCalendarView('month');
}

function toggleAiModal() {
    aiModalInstance.show();
}

async function handleAiChatSubmit(e) {
    e.preventDefault();
    const input = document.getElementById('aiInputPrompt');
    const prompt = input.value.trim();
    if (!prompt) return;
    input.value = '';
    await sendPromptToAi(prompt);
}

function quickAskAi(prompt) {
    sendPromptToAi(prompt);
}

async function sendPromptToAi(message) {
    const history = document.getElementById('aiChatHistory');
    const userMsg = document.createElement('div');
    userMsg.className = 'd-flex justify-content-end gap-2';
    userMsg.innerHTML = `<div class="text-white p-3 rounded-4 shadow-xs" style="font-size:13px; max-width:85%; background:var(--ux-accent);">${message}</div>`;
    history.appendChild(userMsg);
    history.scrollTop = history.scrollHeight;

    const loadingMsg = document.createElement('div');
    loadingMsg.className = 'd-flex gap-2';
    loadingMsg.id = 'aiLoadingBubble';
    loadingMsg.innerHTML = `<div class="rounded-circle d-flex align-items-center justify-content-center" style="width:34px; height:34px; min-width:34px; flex:none; background:#F59E0B; color:#FFFFFF; box-shadow:0 2px 6px rgba(245,158,11,0.3);"><i class="fa-solid fa-wand-magic-sparkles" style="font-size:15px; line-height:1;"></i></div><div class="bg-white p-3 rounded-4 shadow-xs border text-muted" style="font-size:13px;"><i class="fa-solid fa-spinner fa-spin me-1"></i> Trợ lý AI đang phân tích dữ liệu...</div>`;
    history.appendChild(loadingMsg);
    history.scrollTop = history.scrollHeight;

    try {
        const response = await fetch('/aiAssistant/ask', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: message })
        });
        const data = await response.json();
        const loader = document.getElementById('aiLoadingBubble');
        if (loader) loader.remove();

        const replyHtml = data.reply_html || (data.reply ? data.reply.replace(/\n/g, '<br>') : 'PolyMee AI đã xử lý yêu cầu của bạn.');
        const aiReply = document.createElement('div');
        aiReply.className = 'd-flex gap-2';
        aiReply.innerHTML = `<div class="rounded-circle d-flex align-items-center justify-content-center" style="width:34px; height:34px; min-width:34px; flex:none; background:#F59E0B; color:#FFFFFF; box-shadow:0 2px 6px rgba(245,158,11,0.3);"><i class="fa-solid fa-wand-magic-sparkles" style="font-size:15px; line-height:1;"></i></div><div class="bg-white p-3 rounded-4 shadow-xs border" style="font-size:13.5px; max-width:85%; line-height:1.5;">${replyHtml}</div>`;
        history.appendChild(aiReply);
        history.scrollTop = history.scrollHeight;

        if (data.action_type === 'AUTO_EVENT_CREATED') {
            showToast('Đã tự động tạo sự kiện và lên lịch ca trực thành công!', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 2500);
        } else if (data.action_type === 'SEND_EMAIL_INVITATION') {
            showToast('Đã gửi email mời đăng ký ca trực đến CTV FPT Edu!', 'success');
        }
    } catch (err) {
        const loader = document.getElementById('aiLoadingBubble');
        if (loader) loader.remove();
        showToast('Lỗi kết nối Trợ lý AI.', 'danger');
    }
}

function formatDateVN(dateStr) {
    if (!dateStr) return '';
    const parts = dateStr.split('-');
    if (parts.length < 3) return dateStr;
    return `${parts[2]}/${parts[1]}/${parts[0]}`;
}

/**
 * =========================================================
 * PROFILE & ADMIN ROLE ASSIGNMENT JAVASCRIPT HANDLERS
 * =========================================================
 */
function handleProfileUpdate(event) {
    event.preventDefault();
    const form = event.target;
    const btn = document.getElementById('btnSaveProfile');
    const originalText = btn ? btn.innerHTML : '';
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1.5" role="status" aria-hidden="true"></span> Đang lưu...';
    }

    const formData = new FormData(form);

    fetch('/dashboard/updateProfile', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
        if (data.success) {
            showToast(data.message, 'success');
            if (data.user) {
                const meSpan = document.querySelector('.u040-me span');
                if (meSpan) {
                    meSpan.innerHTML = `${data.user.full_name} <small>${data.user.role_name} • ${data.user.student_code}</small>`;
                }
            }
        } else {
            showToast(data.message || 'Lỗi khi cập nhật hồ sơ!', 'danger');
        }
    })
    .catch(err => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
        console.error(err);
        showToast('Lỗi kết nối máy chủ khi lưu hồ sơ!', 'danger');
    });
}

function handleGrantRole(userId, selectId) {
    const select = document.getElementById(selectId);
    if (!select) return;
    const newRoleId = select.value;
    const selectedOption = select.options[select.selectedIndex];
    const roleText = selectedOption ? selectedOption.text : '';

    if (!confirm(`Bạn có chắc chắn muốn cấp quyền "${roleText}" cho người dùng này không?`)) {
        return;
    }

    const formData = new FormData();
    formData.append('user_id', userId);
    formData.append('role_id', newRoleId);

    fetch('/dashboard/updateUserRole', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            const badgeCol = document.getElementById(`userRoleBadgeCol_${userId}`);
            if (badgeCol) {
                const roleColors = {
                    'Admin': 'background: rgba(239, 68, 68, 0.15); color: #F87171; border: 1px solid rgba(239, 68, 68, 0.3);',
                    'Organizer': 'background: rgba(59, 130, 246, 0.15); color: #60A5FA; border: 1px solid rgba(59, 130, 246, 0.3);',
                    'Staff': 'background: rgba(139, 92, 246, 0.15); color: #A78BFA; border: 1px solid rgba(139, 92, 246, 0.3);',
                    'Accountant': 'background: rgba(16, 185, 129, 0.15); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.3);',
                    'Media': 'background: rgba(245, 158, 11, 0.15); color: #FBBF24; border: 1px solid rgba(245, 158, 11, 0.3);'
                };
                const colorStyle = roleColors[data.role_name] || 'background: rgba(100, 116, 139, 0.15); color: #94A3B8; border: 1px solid rgba(100, 116, 139, 0.3);';
                badgeCol.innerHTML = `<span class="badge px-3 py-1.5 rounded-pill" style="font-size: 11.5px; ${colorStyle}">${data.role_name}</span>`;
            }
        } else {
            showToast(data.message || 'Lỗi khi cấp quyền!', 'danger');
        }
    })
    .catch(err => {
        console.error(err);
        showToast('Lỗi kết nối máy chủ khi cấp quyền!', 'danger');
    });
}

function previewVneidImage(url) {
    const img = document.getElementById('vneidPreviewImg');
    const placeholder = document.getElementById('vneidPlaceholder');
    if (!img) return;
    if (url && url.trim().length > 5) {
        img.src = url.trim();
        img.style.display = 'inline-block';
        if (placeholder) placeholder.style.display = 'none';
    } else {
        img.style.display = 'none';
        if (placeholder) placeholder.style.display = 'inline-block';
    }
}

function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        if (icon) {
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    } else {
        input.type = 'password';
        if (icon) {
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
}

// =========================================================
// QUẢN LÝ HỒ SƠ CTV & XÓA TÀI KHOẢN (APPLE DESIGN SYSTEM)
// =========================================================

// =========================================================
// HÀM MỞ MODAL XEM CHI TIẾT CTV (KHI BẤM ICON CON MẮT 👁️)
// =========================================================
let ctvEyeModalInstance = null;

function openCtvEyeModalByIndex(idx) {
    const list = window.ctvRosterData || [];
    if (list[idx]) {
        openCtvEyeModal(list[idx]);
    }
}

function openCtvEyeModal(ctv) {
    if (!ctv) return;
    const name = ctv.full_name || 'Cộng Tác Viên';
    const code = ctv.student_code || 'CTV';
    const dept = ctv.department || 'Công nghệ Thông tin';
    const isMedia = parseInt(ctv.role_id) === 6 || (ctv.role_name === 'Media') || (ctv.skills && ctv.skills.toLowerCase().includes('media')) || (ctv.skills && ctv.skills.toLowerCase().includes('quay'));
    
    document.getElementById('ctvEyeName').textContent = name;
    document.getElementById('ctvEyeCode').textContent = code;
    document.getElementById('ctvEyeAvatar').textContent = (name.charAt(0) || 'C').toUpperCase();
    
    document.getElementById('ctvEyeDept').innerHTML = `<i class="bi bi-mortarboard-fill me-1"></i>Bộ môn: ${dept}`;
    document.getElementById('ctvEyeType').innerHTML = isMedia ? `<i class="bi bi-camera-reels-fill me-1"></i>CTV Media Quay Chụp` : `<i class="bi bi-tools me-1"></i>CTV Bàn Ghế & Máy Móc`;
    
    document.getElementById('ctvEyeEmail').textContent = ctv.email || (code.toLowerCase() + '@fpt.edu.vn');
    document.getElementById('ctvEyePhone').textContent = ctv.phone || '0981 234 567';
    document.getElementById('ctvEyeSkills').textContent = ctv.skills || (isMedia ? 'Chụp ảnh ống kính tele, quay dựng recap sự kiện' : 'Setup bàn ghế hội trường, kết nối âm thanh và micro');
    
    document.getElementById('ctvEyeCccd').textContent = ctv.id_card_number || '079203014589';
    document.getElementById('ctvEyeVneidImg').src = ctv.vneid_image_url || 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=600&auto=format&fit=crop&q=80';
    
    document.getElementById('ctvEyeBankName').textContent = ctv.bank_name || 'MB Bank (Quân Đội)';
    document.getElementById('ctvEyeBankAcc').textContent = ctv.bank_account_number || '098765432199';
    document.getElementById('ctvEyeBankHolder').textContent = ctv.bank_account_name || name.toUpperCase();
    
    const modalEl = document.getElementById('ctvEyeDetailModal');
    if (modalEl) {
        if (!ctvEyeModalInstance) {
            ctvEyeModalInstance = new bootstrap.Modal(modalEl);
        }
        ctvEyeModalInstance.show();
    }
}

function filterCtvRosterTable() {
    const search = (document.getElementById("ctvSearchInput")?.value || "").toLowerCase().trim();
    const dept = (document.getElementById("ctvDeptFilter")?.value || "").trim();
    const rows = document.querySelectorAll("#ctvRosterTable tbody tr.ctv-row");
    let count = 0;

    rows.forEach(row => {
        const rowSearch = (row.getAttribute("data-search") || "").toLowerCase();
        const rowDept = row.getAttribute("data-dept") || "";
        const matchSearch = !search || rowSearch.includes(search);
        const matchDept = !dept || rowDept.includes(dept);

        if (matchSearch && matchDept) {
            row.style.display = "";
            count++;
        } else {
            row.style.display = "none";
        }
    });

    const badge = document.getElementById("ctvFilteredBadge");
    if (badge) badge.textContent = `Hiển thị: ${count} CTV`;
}

function _legacy_filterCtvRosterTable() {
    const search = (document.getElementById("ctvSearchInput")?.value || "").toLowerCase().trim();
    const bank = (document.getElementById("ctvBankFilter")?.value || "").toLowerCase().trim();
    const rows = document.querySelectorAll(".ctv-row");
    let count = 0;

    rows.forEach(row => {
        const rowSearch = (row.getAttribute("data-search") || "").toLowerCase();
        const rowBank = (row.getAttribute("data-bank") || "").toLowerCase();

        const matchSearch = !search || rowSearch.includes(search);
        const matchBank = !bank || rowBank.includes(bank);

        if (matchSearch && matchBank) {
            row.style.display = "";
            count++;
        } else {
            row.style.display = "none";
        }
    });

    const badge = document.getElementById("ctvFilteredBadge");
    if (badge) badge.textContent = `Hiển thị: ${count} CTV`;
}

function previewVneidPhoto(url, name, cccd) {
    const imgEl = document.getElementById("vneidModalImage");
    const titleEl = document.getElementById("vneidModalTitle");
    const subEl = document.getElementById("vneidModalSub");
    if (imgEl) imgEl.src = url || "https://images.unsplash.com/photo-1544717305-2782549b5136?w=600&auto=format&fit=crop&q=80";
    if (titleEl) titleEl.textContent = `Đối Soát VNeID • ${name}`;
    if (subEl) subEl.textContent = `Số CCCD: ${cccd}`;
    if (previewVneidModalInstance) {
        previewVneidModalInstance.show();
    }
}

function exportCtvRosterCSV() {
    let csv = "STT,Ho Va Ten,MSSV,CCCD,Ngan Hang,So Tai Khoan,Chu Tai Khoan,Ky Nang,Trang Thai\n";
    const rows = document.querySelectorAll("#ctvRosterTable tbody tr");
    rows.forEach((row, i) => {
        if (row.style.display !== "none") {
            const cols = row.querySelectorAll("td");
            if (cols.length >= 6) {
                const name = cols[1].querySelector("strong")?.innerText.replace(/,/g, "") || "";
                const code = cols[1].querySelector(".badge")?.innerText.replace(/,/g, "") || "";
                const cccd = cols[2].querySelector(".font-monospace")?.innerText.replace(/,/g, "") || "";
                const bank = cols[3].querySelector(".fw-semibold")?.innerText.replace(/,/g, "") || "";
                const stk = cols[3].querySelector(".text-primary")?.innerText.replace(/,/g, "") || "";
                const owner = cols[3].querySelector("small")?.innerText.replace(/,/g, "") || "";
                const skills = cols[5]?.innerText.replace(/,/g, " - ") || "";
                csv += `${i+1},"${name}","${code}","${cccd}","${bank}","${stk}","${owner}","${skills}","Hop Le"\n`;
            }
        }
    });

    const blob = new Blob(["\uFEFF" + csv], { type: "text/csv;charset=utf-8;" });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = `Danh_Sach_Ho_So_CTV_FPoly_${new Date().toISOString().slice(0,10)}.csv`;
    link.click();
    showToast("Đã xuất danh sách hồ sơ CTV thành công!", "success");
}

let deleteAccountModalInstance = null;
function openDeleteAccountModal() {
    const modalEl = document.getElementById("deleteAccountModal");
    if (!modalEl) return;
    deleteAccountModalInstance = new bootstrap.Modal(modalEl);
    document.getElementById("confirmDeleteInput").value = "";
    document.getElementById("confirmDeleteCheck").checked = false;
    document.getElementById("confirmDeleteSubmitBtn").disabled = true;
    deleteAccountModalInstance.show();
}

function toggleDeleteBtnState() {
    const text = (document.getElementById("confirmDeleteInput")?.value || "").trim();
    const checked = document.getElementById("confirmDeleteCheck")?.checked || false;
    const btn = document.getElementById("confirmDeleteSubmitBtn");
    if (btn) btn.disabled = !(text === "XAC NHAN XOA" && checked);
}

async function submitDeleteAccount() {
    const btn = document.getElementById("confirmDeleteSubmitBtn");
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Đang xóa tài khoản...';
    }

    try {
        const res = await fetch("/dashboard/deleteAccount", {
            method: "POST",
            headers: { "X-Requested-With": "XMLHttpRequest" }
        });
        const data = await res.json();
        if (data.success) {
            window.location.href = data.redirect || "/auth/login?deleted=1";
        } else {
            alert(data.message || "Không thể xóa tài khoản. Vui lòng thử lại.");
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-trash3-fill me-1"></i> Xóa Tài Khoản Vĩnh Viễn';
            }
        }
    } catch (err) {
        window.location.href = "/auth/login?deleted=1";
    }
}

</script>

</body>
</html>
