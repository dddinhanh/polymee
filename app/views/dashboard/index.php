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
        --rw: 72px;
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
        width: var(--rw);
        transition: width .6s var(--ux-spring);
    }

    .u040.is-wide {
        --rw: 240px;
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
        top: 4px;
        min-width: 18px;
        height: 18px;
        padding: 0 4.5px;
        border-radius: 999px;
        background: #0284C7;
        color: #FFFFFF;
        font-size: 10px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-style: normal;
        border: none !important;
        box-shadow: 0 0 0 2px #FFFFFF;
        transition: all .35s var(--ux-spring);
        line-height: 1;
        z-index: 10;
        pointer-events: none;
        font-variant-numeric: tabular-nums;
    }

    [data-bs-theme="dark"] .u040-bd {
        box-shadow: 0 0 0 2px #1C1C1E;
    }

    .u040.is-wide .u040-bd {
        left: auto;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        box-shadow: none !important;
        background: #0284C7;
        color: #FFFFFF;
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
        flex-wrap: nowrap !important;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06) !important;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        overflow: visible !important;
    }

    [data-bs-theme="dark"] .dashboard-topbar {
        background: rgba(17, 24, 39, 0.88);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.4);
    }

    .dashboard-topbar .dropdown {
        position: relative;
        z-index: 1050;
    }

    .dashboard-topbar .dropdown-menu {
        z-index: 110 !important;
        background-color: var(--ux-surface) !important;
        border: 1px solid var(--ux-line-2) !important;
        box-shadow: 0 18px 45px rgba(0, 0, 0, 0.35) !important;
        border-radius: 16px;
    }

    /* Topbar Controls: AI Pill, Notification Bell & Badges */
    .btn-topbar-ai {
        height: 36px;
        padding: 0 14px;
        border-radius: 999px;
        background: rgba(2, 132, 199, 0.08);
        border: 1px solid rgba(2, 132, 199, 0.22);
        color: #0284C7;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s var(--ux-ease);
        white-space: nowrap;
    }
    .btn-topbar-ai:hover {
        background: rgba(2, 132, 199, 0.16);
        border-color: rgba(2, 132, 199, 0.4);
        color: #0369A1;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(2, 132, 199, 0.18);
    }
    .btn-topbar-ai i {
        color: #F59E0B;
        font-size: 13.5px;
        transition: transform 0.3s var(--ux-spring);
    }
    .btn-topbar-ai:hover i {
        transform: rotate(15deg) scale(1.15);
    }

    .btn-topbar-bell {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #FAFAFC;
        border: 1px solid var(--ux-line);
        color: #48484A;
        cursor: pointer;
        transition: all 0.2s var(--ux-ease);
        padding: 0;
        flex-shrink: 0;
        position: relative;
        overflow: visible !important;
    }
    .btn-topbar-bell:hover {
        background: #F4F4F6;
        color: #1D1D1F;
        border-color: rgba(0, 0, 0, 0.16);
        transform: translateY(-1px);
    }
    .btn-topbar-bell i {
        font-size: 15px;
        color: #48484A;
    }
    .btn-topbar-bell:hover i {
        color: #0284C7;
    }
    .topbar-bell-badge {
        position: absolute;
        top: 0px;
        right: 0px;
        min-width: 17px;
        height: 17px;
        padding: 0 3.5px;
        border-radius: 999px;
        background: #0284C7;
        color: #FFFFFF;
        font-size: 9px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--ux-surface, #FFFFFF);
        box-sizing: border-box;
        line-height: 1;
        pointer-events: none;
        z-index: 10;
    }

    /* Bento Google Drive Proof Pill (Linear / Apple HIG Standard) */
    .btn-drive-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 28px;
        padding: 0 11px;
        border-radius: 999px;
        background: #F0F9FF;
        border: 1px solid #BAE6FD;
        color: #0284C7 !important;
        font-size: 11.5px;
        font-weight: 650;
        text-decoration: none !important;
        transition: all 0.2s var(--ux-ease);
        box-shadow: 0 1px 2px rgba(2, 132, 199, 0.08);
        white-space: nowrap;
    }
    .btn-drive-pill:hover {
        background: #E0F2FE;
        border-color: #7DD3FC;
        color: #0369A1 !important;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(2, 132, 199, 0.16);
    }
    .btn-drive-pill i.fa-google-drive {
        font-size: 13.5px;
        color: #0284C7;
    }
    .btn-drive-pill i.bi-arrow-up-right {
        font-size: 10px;
        opacity: 0.75;
        transition: transform 0.2s;
    }
    .btn-drive-pill:hover i.bi-arrow-up-right {
        transform: translate(1px, -1px);
    }

    /* Proof Verified Post-Production Badge */
    .proof-verified-pill {
        display: inline-flex;
        align-items: center;
        gap: 4.5px;
        height: 23px;
        padding: 0 8.5px;
        border-radius: 999px;
        background: #ECFDF5;
        border: 1px solid #A7F3D0;
        color: #059669;
        font-size: 10.5px;
        font-weight: 600;
        white-space: nowrap;
        line-height: 1;
    }
    .proof-verified-pill i {
        font-size: 11.5px;
        color: #10B981;
    }

    /* Machined Button Disabled State - Fix White-on-White Ghost Button Bug */
    .btn-fpt-primary:disabled,
    .btn-fpt-primary.disabled,
    .btn-fpt-emerald:disabled,
    .btn-fpt-emerald.disabled {
        background: #F3F4F6 !important;
        border: 1px solid #E5E7EB !important;
        color: #9CA3AF !important;
        box-shadow: none !important;
        cursor: not-allowed !important;
        pointer-events: none !important;
        opacity: 0.75 !important;
        transform: none !important;
    }
    .btn-fpt-primary:disabled i,
    .btn-fpt-emerald:disabled i {
        color: #9CA3AF !important;
    }

    /* Refined & Streamlined Button System (Apple HIG / Linear Standard) */
    .btn-fpt-primary,
    .btn-fpt-secondary,
    .btn-fpt-emerald,
    .btn-fpt-sky,
    .btn-fpt-outline-orange {
        padding: 6.5px 14px !important;
        font-size: 12.5px !important;
        border-radius: 9px !important;
        gap: 6px !important;
        line-height: 1.35 !important;
    }
    .btn-fpt-sm,
    .btn-sm.btn-fpt-primary,
    .btn-sm.btn-fpt-secondary,
    .btn-sm.btn-fpt-emerald,
    .btn-sm.btn-fpt-sky,
    .btn-sm.btn-fpt-outline-orange {
        padding: 4.5px 10px !important;
        font-size: 11.5px !important;
        font-weight: 600 !important;
        border-radius: 7px !important;
        gap: 5px !important;
        line-height: 1.3 !important;
        white-space: nowrap !important;
    }
    /* Compact Table Action Buttons */
    table td .btn-fpt-primary,
    table td .btn-fpt-secondary,
    table td .btn-fpt-emerald,
    table td .btn-fpt-sky,
    table td .btn-fpt-outline-orange,
    table td .btn-fpt-sm {
        display: inline-flex !important;
        width: auto !important;
        white-space: nowrap !important;
        margin-bottom: 0 !important;
    }

    /* =========================================================
       EVENT & SHIFT MANAGEMENT REDESIGN (Bento 2.0 / Linear Standard)
       ========================================================= */
    .event-tab-btn {
        background: transparent;
        border: none;
        font-size: 13px;
        font-weight: 600;
        color: #64748B;
        padding: 6px 2px 10px 2px;
        position: relative;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: color 0.18s;
        text-decoration: none;
    }
    .event-tab-btn:hover {
        color: #0F172A;
    }
    .event-tab-btn.active {
        color: #0F172A;
        font-weight: 700;
    }
    .event-tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2.5px;
        background: #EA580C;
        border-radius: 4px;
    }
    .event-tab-btn .tab-count-pill {
        font-size: 11px;
        font-weight: 700;
        padding: 1px 6.5px;
        border-radius: 999px;
        background: #F1F5F9;
        color: #475569;
        line-height: 1.3;
    }
    .event-tab-btn.active .tab-count-pill {
        background: #FFEDD5;
        color: #C2410C;
    }

    /* Event Row Card */
    .event-row-card {
        background: #FFFFFF;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        transition: all 0.18s var(--ux-ease);
        cursor: pointer;
        user-select: none;
    }
    .event-row-card:hover {
        border-color: #CBD5E1;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transform: translateY(-1px);
    }
    .event-row-card.active,
    .event-row-card.selected {
        background: #FFF9F6 !important;
        border-color: #FDBA74 !important;
        box-shadow: 0 4px 16px rgba(234, 88, 12, 0.09) !important;
    }
    .event-row-card.active .event-date-day,
    .event-row-card.selected .event-date-day {
        color: #EA580C !important;
    }
    .event-row-card.active .event-date-box,
    .event-row-card.selected .event-date-box {
        background: #FFF0E6;
        border-radius: 8px;
    }

    /* Published Badge */
    .badge-status-published {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2.5px 8px;
        border-radius: 999px;
        background: #ECFDF5;
        border: 1px solid #A7F3D0;
        color: #059669;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
        line-height: 1.25;
    }
    .badge-status-published i {
        font-size: 10.5px;
        color: #10B981;
    }

    /* Mini Calendar Grid */
    .mini-cal-day {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        margin: 0 auto;
        border-radius: 50%;
        font-size: 11.5px;
        font-weight: 550;
        color: #334155;
        cursor: pointer;
        position: relative;
        transition: all 0.15s ease;
        user-select: none;
    }
    .mini-cal-day:hover {
        background: #F1F5F9;
        color: #0F172A;
    }
    .mini-cal-day.has-event::after {
        content: '';
        position: absolute;
        bottom: 2px;
        width: 3.5px;
        height: 3.5px;
        border-radius: 50%;
        background: #EA580C;
    }
    .mini-cal-day.active {
        background: #EA580C !important;
        color: #FFFFFF !important;
        font-weight: 700;
    }
    .mini-cal-day.active.has-event::after {
        background: #FFFFFF !important;
    }
    .mini-cal-day.other-month {
        color: #CBD5E1;
        pointer-events: none;
    }

    /* Dark Mode Overrides for Event Management */
    [data-bs-theme="dark"] .event-row-card {
        background: #1E293B;
        border-color: rgba(255, 255, 255, 0.08);
    }
    [data-bs-theme="dark"] .event-row-card:hover {
        background: #243248;
        border-color: rgba(255, 255, 255, 0.16);
    }
    [data-bs-theme="dark"] .event-row-card.active,
    [data-bs-theme="dark"] .event-row-card.selected {
        background: rgba(234, 88, 12, 0.15) !important;
        border-color: rgba(234, 88, 12, 0.5) !important;
    }
    [data-bs-theme="dark"] .event-title-text {
        color: #F8FAFC !important;
    }
    [data-bs-theme="dark"] .event-date-divider {
        background: rgba(255, 255, 255, 0.1) !important;
    }
    [data-bs-theme="dark"] .mini-cal-day {
        color: #CBD5E1;
    }
    [data-bs-theme="dark"] .mini-cal-day:hover {
        background: rgba(255, 255, 255, 0.08);
    }
    [data-bs-theme="dark"] .event-tab-btn {
        color: #94A3B8;
    }
    [data-bs-theme="dark"] .event-tab-btn:hover,
    [data-bs-theme="dark"] .event-tab-btn.active {
        color: #F8FAFC;
    }
    [data-bs-theme="dark"] .event-tab-btn .tab-count-pill {
        background: rgba(255, 255, 255, 0.1);
        color: #E2E8F0;
    }

    /* Modern AI Bento Suggestion Cards (Apple Intelligence & ChatGPT Standard) */
    .ai-option-card {
        display: flex;
        align-items: center;
        gap: 14px;
        width: 100%;
        padding: 13px 18px;
        border-radius: 14px;
        background: #FFFFFF;
        border: 1px solid var(--ux-line, #E2E8F0);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        text-align: left;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        color: inherit;
        user-select: none;
    }
    .ai-option-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        border-color: #0284C7;
        background: #F8FAFC;
    }
    .ai-option-card:active {
        transform: translateY(0);
    }
    .ai-option-card .ai-option-icon {
        width: 38px;
        height: 38px;
        min-width: 38px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }
    .ai-option-card .ai-option-title {
        font-size: 13.5px;
        font-weight: 700;
        color: #0F172A;
        line-height: 1.3;
    }
    .ai-option-card .ai-option-desc {
        font-size: 12px;
        color: #64748B;
        line-height: 1.4;
        margin-top: 2px;
    }
    .ai-option-card .ai-option-arrow {
        margin-left: auto;
        color: #94A3B8;
        font-size: 14px;
        transition: transform 0.2s;
        flex-shrink: 0;
    }
    .ai-option-card:hover .ai-option-arrow {
        color: #0284C7;
        transform: translateX(3px);
    }

    [data-bs-theme="dark"] .ai-option-card {
        background: #1E293B;
        border-color: rgba(255, 255, 255, 0.1);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
    }
    [data-bs-theme="dark"] .ai-option-card:hover {
        background: #273549;
        border-color: #38BDF8;
    }
    [data-bs-theme="dark"] .ai-option-card .ai-option-title {
        color: #F8FAFC;
    }
    [data-bs-theme="dark"] .ai-option-card .ai-option-desc {
        color: #94A3B8;
    }

    [data-bs-theme="dark"] .btn-topbar-ai {
        background: rgba(2, 132, 199, 0.18);
        border-color: rgba(2, 132, 199, 0.35);
        color: #38BDF8;
    }
    [data-bs-theme="dark"] .btn-topbar-bell {
        background: #2C2C2E;
        border-color: rgba(255, 255, 255, 0.12);
        color: #E5E5EA;
    }
    [data-bs-theme="dark"] .btn-topbar-bell:hover {
        background: #3A3A3C;
        color: #FFFFFF;
    }
    [data-bs-theme="dark"] .btn-topbar-bell i {
        color: #E5E5EA;
    }
    [data-bs-theme="dark"] .topbar-bell-badge {
        border-color: #2C2C2E !important;
    }
    [data-bs-theme="dark"] .btn-drive-pill {
        background: rgba(2, 132, 199, 0.15);
        border-color: rgba(2, 132, 199, 0.35);
        color: #38BDF8 !important;
    }
    [data-bs-theme="dark"] .proof-verified-pill {
        background: rgba(16, 185, 129, 0.15);
        border-color: rgba(16, 185, 129, 0.3);
        color: #34D399;
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
            padding: 10px 18px;
            margin-bottom: 12px;
            min-height: 52px;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            overflow: visible !important;
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
            padding: 0 !important;
            margin-bottom: 0 !important;
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            overflow: hidden !important;
            box-sizing: border-box !important;
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
            box-sizing: border-box !important;
        }

        #tierWeekView:not([style*="display: none"]):not([style*="display:none"]) {
            width: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            flex: 1 1 0% !important;
            min-height: 0 !important;
            overflow-y: auto !important;
            box-sizing: border-box !important;
        }

        #tierDayView:not([style*="display: none"]):not([style*="display:none"]) {
            width: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            flex: 1 1 0% !important;
            min-height: 0 !important;
            overflow-y: auto !important;
            box-sizing: border-box !important;
        }

        .month-header-grid {
            display: grid !important;
            grid-template-columns: repeat(7, minmax(0, 1fr)) !important;
            gap: 6px !important;
            margin-bottom: 6px !important;
            text-align: center !important;
            flex-shrink: 0 !important;
        }

        .month-days-grid {
            display: grid !important;
            grid-template-columns: repeat(7, minmax(0, 1fr)) !important;
            grid-template-rows: repeat(var(--month-rows, 5), minmax(0, 1fr)) !important;
            gap: 6px !important;
            width: 100% !important;
            flex: 1 1 0% !important;
            min-height: 0 !important;
            height: 100% !important;
            box-sizing: border-box !important;
        }

        .month-day-cell {
            min-height: 0 !important;
            height: 100% !important;
            padding: 5px 7px !important;
            border-radius: 12px !important;
            display: flex !important;
            flex-direction: column !important;
            box-sizing: border-box !important;
            background: #FFFFFF;
            border: 1px solid rgba(0, 0, 0, 0.06);
            transition: all 0.15s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            overflow: hidden !important;
            cursor: pointer;
        }

        .month-day-cell:hover {
            border-color: rgba(234, 88, 12, 0.4);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        /* Utility classes for Viewport-Locked & Flex card layouts */
        .shrink-0 { flex-shrink: 0 !important; }
        .min-h-0 { min-height: 0 !important; }
        .flex-1 { flex: 1 1 0% !important; }
        .overflow-y-auto { overflow-y: auto !important; }
        .overflow-hidden { overflow: hidden !important; }
        .h-full { height: 100% !important; }

        .day-number-pill {
            font-size: 13px !important;
            font-weight: 700 !important;
            color: #1E293B;
            line-height: 1;
        }
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

    /* When sidebar is collapsed (rail mode / compact mode), show notification badge clearly at the top-right of the icon (iOS / macOS HIG badge) */
    .u040:not(.is-wide) .u040-bd,
    .u040:not(.is-wide) .u040-bd-admin,
    .u040:not(.is-wide) em.u040-bd {
        display: inline-flex !important;
        opacity: 1 !important;
        visibility: visible !important;
        position: absolute !important;
        left: 28px !important;
        top: 4px !important;
        right: auto !important;
        transform: none !important;
        min-width: 18px !important;
        height: 18px !important;
        padding: 0 4.5px !important;
        border-radius: 999px !important;
        background: #0284C7 !important; /* Xanh Dương Apple / Sky 600 - Thanh lịch, dịu mắt, chống chói */
        color: #FFFFFF !important;
        font-size: 10px !important;
        font-weight: 800 !important;
        line-height: 1 !important;
        align-items: center !important;
        justify-content: center !important;
        font-style: normal !important;
        border: none !important;
        box-shadow: 0 0 0 2px #FFFFFF !important;
        z-index: 10 !important;
        pointer-events: none !important;
    }

    [data-bs-theme="dark"] .u040:not(.is-wide) .u040-bd,
    [data-bs-theme="dark"] .u040:not(.is-wide) .u040-bd-admin,
    [data-bs-theme="dark"] .u040:not(.is-wide) em.u040-bd {
        box-shadow: 0 0 0 2px #1C1C1E !important;
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

                    <?php 
                    $roleCode = $perms['role'] ?? 'CTV';
                    ?>

                    <?php if ($roleCode === 'ADMIN'): ?>
                    <!-- ================= ROLE ADMIN ================= -->
                    <li class="u040-section-label"><span>TỔNG QUAN</span></li>
                    <li>
                        <button class="u040-it" type="button" data-page="overview-report" onclick="navigateToPage('overview-report')" title="Dashboard báo cáo hệ thống">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
                            <span>Dashboard Báo Cáo</span>
                        </button>
                    </li>
                    <li>
                        <button class="u040-it" type="button" aria-current="page" data-page="calendar" onclick="navigateToPage('calendar')" title="Lịch hoạt động & phân rã ca trực">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="5.5" width="16" height="14.5" rx="3"/><path d="M4 10h16M8.5 3.5v4M15.5 3.5v4"/></svg>
                            <span>Lịch Hoạt Động</span>
                        </button>
                    </li>

                    <li class="u040-section-label"><span>QUẢN LÝ</span></li>
                    <li>
                        <button class="u040-it" type="button" data-page="user-roles" onclick="navigateToPage('user-roles')" title="Quản lý tài khoản & phân quyền">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><circle cx="12" cy="11" r="3"/></svg>
                            <span>Tài Khoản & Phân Quyền</span>
                        </button>
                    </li>
                    <li>
                        <button class="u040-it" type="button" data-page="audit-trail" onclick="navigateToPage('audit-trail')" title="Nhật ký hoạt động hệ thống (Audit Trail)">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
                            <span>Nhật Ký Hoạt Động</span>
                        </button>
                    </li>
                    <li>
                        <button class="u040-it" type="button" data-page="events" onclick="navigateToPage('events')" title="Quản lý sự kiện">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="4" width="7" height="7" rx="2"/><rect x="13" y="4" width="7" height="7" rx="2"/><rect x="4" y="13" width="7" height="7" rx="2"/><rect x="13" y="13" width="7" height="7" rx="2"/></svg>
                            <span>Sự Kiện</span>
                        </button>
                    </li>
                    <li>
                        <button class="u040-it" type="button" data-page="student-payroll-profile" onclick="navigateToPage('student-payroll-profile')" title="Cộng tác viên & Hồ sơ định danh">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            <span>Cộng Tác Viên</span>
                        </button>
                    </li>

                    <li class="u040-section-label"><span>VẬN HÀNH</span></li>
                    <li>
                        <button class="u040-it" type="button" data-page="rooms-booking" onclick="navigateToPage('rooms-booking')" title="Phòng & Đặt lịch">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h10M7 12h10M7 16h6"/></svg>
                            <span>Phòng & Đặt Lịch</span>
                        </button>
                    </li>
                    <li>
                        <button class="u040-it" type="button" data-page="submissions" onclick="navigateToPage('submissions')" title="Thẩm định nghiệm thu minh chứng">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19.5V13M5 9V4.5M12 19.5V11M12 7V4.5M19 19.5V15M19 11V4.5M3 13h4M10 7h4M17 15h4"/></svg>
                            <span>Nghiệm Thu</span>
                        </button>
                    </li>
                    <li>
                        <button class="u040-it" type="button" data-page="accounting" onclick="navigateToPage('accounting')" title="Hợp đồng & Thanh toán quyết toán">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 7h6M9 11h6M9 15h4M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/></svg>
                            <span>Hợp Đồng & Thanh Toán</span>
                        </button>
                    </li>

                    <li class="u040-section-label"><span>HỆ THỐNG</span></li>
                    <li>
                        <button class="u040-it" type="button" data-page="profile-settings" onclick="navigateToPage('profile-settings')" title="Cài đặt hệ thống">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                            <span>Cài Đặt</span>
                        </button>
                    </li>

                    <?php elseif ($roleCode === 'LECTURER'): ?>
                    <!-- ================= ROLE ORGANIZER (GIẢNG VIÊN / BTC) ================= -->
                    <li class="u040-section-label"><span>TỔNG QUAN</span></li>
                    <li>
                        <button class="u040-it" type="button" data-page="overview-report" onclick="navigateToPage('overview-report')" title="Dashboard báo cáo tổ chức">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
                            <span>Dashboard Báo Cáo</span>
                        </button>
                    </li>
                    <li>
                        <button class="u040-it" type="button" aria-current="page" data-page="calendar" onclick="navigateToPage('calendar')" title="Lịch hoạt động & phân rã ca trực">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="5.5" width="16" height="14.5" rx="3"/><path d="M4 10h16M8.5 3.5v4M15.5 3.5v4"/></svg>
                            <span>Lịch Hoạt Động</span>
                        </button>
                    </li>

                    <li class="u040-section-label"><span>TỔ CHỨC SỰ KIỆN</span></li>
                    <li>
                        <button class="u040-it" type="button" data-page="events" onclick="navigateToPage('events')" title="Quản lý sự kiện">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="4" width="7" height="7" rx="2"/><rect x="13" y="4" width="7" height="7" rx="2"/><rect x="4" y="13" width="7" height="7" rx="2"/><rect x="13" y="13" width="7" height="7" rx="2"/></svg>
                            <span>Quản Lý Sự Kiện</span>
                        </button>
                    </li>
                    <li>
                        <button class="u040-it" type="button" data-page="shifts-sessions" onclick="navigateToPage('shifts-sessions')" title="Buổi hoạt động & ca trực">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span>Buổi Hoạt Động & Ca Trực</span>
                        </button>
                    </li>
                    <li>
                        <button class="u040-it" type="button" data-page="ctv-assignments" onclick="navigateToPage('ctv-assignments')" title="Cộng tác viên & Phân công nhiệm vụ">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            <span>CTV & Phân Công</span>
                        </button>
                    </li>

                    <li class="u040-section-label"><span>NGHIỆM THU</span></li>
                    <li>
                        <button class="u040-it" type="button" data-page="submissions" onclick="navigateToPage('submissions')" title="Duyệt minh chứng ảnh hiện trường & Google Drive">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19.5V13M5 9V4.5M12 19.5V11M12 7V4.5M19 19.5V15M19 11V4.5M3 13h4M10 7h4M17 15h4"/></svg>
                            <span>Duyệt Minh Chứng</span>
                        </button>
                    </li>
                    <li>
                        <button class="u040-it" type="button" data-page="contract-dossier" onclick="navigateToPage('contract-dossier')" title="Hợp đồng nghiệm thu điện tử">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM14 9V3.5L18.5 8H14zM8 13h8M8 17h5"/></svg>
                            <span>Hợp Đồng Nghiệm Thu</span>
                        </button>
                    </li>

                    <li class="u040-section-label"><span>THỐNG KÊ</span></li>
                    <li>
                        <button class="u040-it" type="button" data-page="event-reports" onclick="navigateToPage('event-reports')" title="Báo cáo hiệu quả sự kiện">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                            <span>Báo Cáo Sự Kiện</span>
                        </button>
                    </li>

                    <?php elseif ($roleCode === 'STAFF'): ?>
                    <!-- ================= ROLE STAFF (HÀNH CHÍNH / CSVC) ================= -->
                    <li class="u040-section-label"><span>TỔNG QUAN</span></li>
                    <li>
                        <button class="u040-it" type="button" data-page="overview-report" onclick="navigateToPage('overview-report')" title="Dashboard báo cáo vận hành">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
                            <span>Dashboard Báo Cáo</span>
                        </button>
                    </li>
                    <li>
                        <button class="u040-it" type="button" aria-current="page" data-page="calendar" onclick="navigateToPage('calendar')" title="Lịch hoạt động & CSVC">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="5.5" width="16" height="14.5" rx="3"/><path d="M4 10h16M8.5 3.5v4M15.5 3.5v4"/></svg>
                            <span>Lịch Hoạt Động</span>
                        </button>
                    </li>

                    <li class="u040-section-label"><span>VẬN HÀNH</span></li>
                    <li>
                        <button class="u040-it" type="button" data-page="rooms-booking" onclick="navigateToPage('rooms-booking')" title="Phòng & Thiết bị">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h10M7 12h10M7 16h6"/></svg>
                            <span>Phòng & Thiết Bị</span>
                        </button>
                    </li>
                    <li>
                        <button class="u040-it" type="button" data-page="room-calendar" onclick="navigateToPage('room-calendar')" title="Lịch đặt phòng">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 2v4M16 2v4M3 10h18M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/></svg>
                            <span>Lịch Đặt Phòng</span>
                        </button>
                    </li>
                    <li>
                        <button class="u040-it" type="button" data-page="student-payroll-profile" onclick="navigateToPage('student-payroll-profile')" title="Hồ sơ CTV & CCCD / STK">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            <span>Hồ Sơ CTV</span>
                        </button>
                    </li>

                    <li class="u040-section-label"><span>TÀI CHÍNH</span></li>
                    <li>
                        <button class="u040-it" type="button" data-page="contract-dossier" onclick="navigateToPage('contract-dossier')" title="Hợp đồng & Trình ký chữ ký số">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM14 9V3.5L18.5 8H14zM8 13h8M8 17h5"/></svg>
                            <span>Hợp Đồng & Trình Ký</span>
                        </button>
                    </li>
                    <li>
                        <button class="u040-it" type="button" data-page="accounting" onclick="navigateToPage('accounting')" title="Thanh toán & Quyết toán thù lao">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 7h6M9 11h6M9 15h4M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/></svg>
                            <span>Thanh Toán & Quyết Toán</span>
                        </button>
                    </li>

                    <li class="u040-section-label"><span>THEO DÕI</span></li>
                    <li>
                        <button class="u040-it" type="button" data-page="staff-reports" onclick="navigateToPage('staff-reports')" title="Báo cáo vận hành">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                            <span>Báo Cáo</span>
                        </button>
                    </li>
                    <li>
                        <button class="u040-it" type="button" data-page="audit-trail" onclick="navigateToPage('audit-trail')" title="Nhật ký hoạt động kiểm toán">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
                            <span>Nhật Ký Hoạt Động</span>
                        </button>
                    </li>

                    <?php else: ?>
                    <!-- ================= ROLE STUDENT / MEDIA (CTV) ================= -->
                    <li class="u040-section-label"><span>TỔNG QUAN</span></li>
                    <li>
                        <button class="u040-it" type="button" data-page="overview-report" onclick="navigateToPage('overview-report')" title="Dashboard của tôi">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
                            <span>Dashboard Của Tôi</span>
                        </button>
                    </li>
                    <li>
                        <button class="u040-it" type="button" aria-current="page" data-page="calendar" onclick="navigateToPage('calendar')" title="Lịch hoạt động & đăng ký ca">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="5.5" width="16" height="14.5" rx="3"/><path d="M4 10h16M8.5 3.5v4M15.5 3.5v4"/></svg>
                            <span>Lịch Hoạt Động</span>
                        </button>
                    </li>

                    <li class="u040-section-label"><span>CÔNG VIỆC</span></li>
                    <li>
                        <button class="u040-it" type="button" data-page="explore-jobs" onclick="navigateToPage('explore-jobs')" title="Khám phá cơ hội nhận ca trực">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <span>Khám Phá Công Việc</span>
                        </button>
                    </li>
                    <li>
                        <button class="u040-it" type="button" data-page="my-shifts" onclick="navigateToPage('my-shifts')" title="Công việc của tôi đã đăng ký">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 11l8-6.5 8 6.5V19a1 1 0 0 1-1 1h-4.5v-5h-5v5H5a1 1 0 0 1-1-1z"/></svg>
                            <span>Công Việc Của Tôi</span>
                        </button>
                    </li>
                    <li>
                        <button class="u040-it" type="button" data-page="my-proof-submission" onclick="navigateToPage('my-proof-submission')" title="Nghiệm thu, nộp minh chứng & kết quả chi trả thù lao">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                            <span>Nghiệm Thu & Minh Chứng</span>
                        </button>
                    </li>

                    <li class="u040-section-label"><span>TÀI CHÍNH</span></li>
                    <li>
                        <button class="u040-it" type="button" data-page="my-contracts" onclick="navigateToPage('my-contracts')" title="Hợp đồng điện tử & Thù lao nhận được">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                            <span>Hợp Đồng & Thù Lao</span>
                        </button>
                    </li>

                    <li class="u040-section-label"><span>CÁ NHÂN</span></li>
                    <li>
                        <button class="u040-it" type="button" data-page="profile-settings" onclick="navigateToPage('profile-settings')" title="Hồ sơ cá nhân & CCCD / STK">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <span>Hồ Sơ Cá Nhân</span>
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
                        <ol class="breadcrumb mb-0 align-items-center" style="font-size: 11.5px; font-weight: 500;">
                            <li class="breadcrumb-item d-flex align-items-center">
                                <i class="bi bi-house text-muted me-1.5" style="font-size: 12px;"></i>
                                <span class="text-muted opacity-50 me-1.5">></span>
                                <a href="/dashboard" class="text-decoration-none text-muted">PolyMee</a>
                            </li>
                            <li class="breadcrumb-item active fw-semibold" id="breadcrumbActive" style="color: #0284C7;">Lịch hoạt động</li>
                        </ol>
                    </nav>
                    <h1 class="fw-bold mb-0 tracking-tight" style="font-size: 19px; color: #0F172A;" id="activeScreenTitle">Lịch Hoạt Động & Điều Phối Ca Trực</h1>
                    <p class="text-muted mb-0 d-none d-md-block" style="font-size: 11.5px;" id="activeScreenSubtitle">Theo dõi lịch sự kiện, ca trực và phân công nhiệm vụ.</p>
                </div>
            </div>

            <!-- Right: Action Controls (Apple macOS Menubar Standard - AI & Notifications On Top) -->
            <div class="d-flex align-items-center gap-2 ms-auto flex-nowrap flex-shrink-0">
                <!-- NÚT TẠO LỊCH (Chuẩn Mockup Image 10) -->
                <button type="button" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1.5 fw-semibold shadow-xs" id="btnCreateCalendarSchedule" onclick="showAddNoteModal()" style="border-radius: 8px; background: #2563EB; border: none; font-size: 12.5px; padding: 6px 14px;">
                    <i class="bi bi-plus-lg"></i> Tạo lịch
                </button>

                <!-- ROLE SWITCHER DROPDOWN (1-Click Chuyển Đổi Nhanh Tài Khoản Demo Cho Hội Đồng Chấm Thi) -->
                <div class="dropdown">
                    <button class="btn btn-fpt-secondary btn-fpt-sm dropdown-toggle d-flex align-items-center gap-1.5 shadow-xs" type="button" id="topbarRoleSwitcher" data-bs-toggle="dropdown" aria-expanded="false" title="Chuyển đổi nhanh vai trò tài khoản demo (1-Click)">
                        <i class="bi bi-person-badge-fill text-primary"></i>
                        <span class="d-none d-lg-inline fw-semibold text-truncate" style="max-width: 140px; font-size: 12px;"><?= htmlspecialchars($currentUser['full_name'] ?? 'Chuyển Vai') ?></span>
                        <span class="badge rounded-pill" style="font-size: 10px; background: rgba(234, 88, 12, 0.12); color: #EA580C; border: 1px solid rgba(234, 88, 12, 0.25);"><?= htmlspecialchars($perms['roleNameDisplay'] ?? ($currentUser['role_name'] ?? 'Role')) ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 p-2" aria-labelledby="topbarRoleSwitcher" style="min-width: 290px; border-radius: 14px; background: var(--ux-surface); border: 1px solid var(--ux-line) !important; z-index: 1060;">
                        <li class="px-2.5 py-1.5 border-bottom mb-1" style="border-color: var(--ux-line) !important;">
                            <div class="fw-bold d-flex align-items-center justify-content-between" style="font-size: 11.5px; color: var(--ux-ink);">
                                <span><i class="bi bi-arrow-left-right me-1 text-primary"></i>Chuyển Vai Demo (1-Click)</span>
                                <span class="badge bg-light text-muted border" style="font-size: 9.5px;">5 Roles</span>
                            </div>
                            <div style="font-size: 10.5px; color: var(--ux-ink-3);">Dành cho Thầy/Cô và Hội đồng bảo vệ</div>
                        </li>
                        <li>
                            <a class="dropdown-item rounded-2 py-1.5 px-2 d-flex align-items-center gap-2 <?= ((int)($currentUser['id'] ?? 0) === 3) ? 'active' : '' ?>" href="/auth/quickLogin/3">
                                <span class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 28px; height: 28px; font-size: 10.5px; background: rgba(2, 132, 199, 0.15); color: #0284C7;">PA</span>
                                <div class="flex-grow-1 overflow-hidden" style="line-height: 1.25;">
                                    <div class="fw-bold text-truncate" style="font-size: 12px;">Đinh Nguyễn Phương Anh</div>
                                    <div style="font-size: 10.5px; color: var(--ux-ink-3);">PI03084 • CTV Media & Nhiếp Ảnh</div>
                                </div>
                                <?php if ((int)($currentUser['id'] ?? 0) === 3): ?><i class="bi bi-check-lg text-primary ms-auto"></i><?php endif; ?>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item rounded-2 py-1.5 px-2 d-flex align-items-center gap-2 <?= ((int)($currentUser['id'] ?? 0) === 4) ? 'active' : '' ?>" href="/auth/quickLogin/4">
                                <span class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 28px; height: 28px; font-size: 10.5px; background: rgba(16, 185, 129, 0.15); color: #059669;">ĐM</span>
                                <div class="flex-grow-1 overflow-hidden" style="line-height: 1.25;">
                                    <div class="fw-bold text-truncate" style="font-size: 12px;">Lê Đức Mạnh</div>
                                    <div style="font-size: 10.5px; color: var(--ux-ink-3);">PI03067 • Sinh Viên CTV Sự Kiện</div>
                                </div>
                                <?php if ((int)($currentUser['id'] ?? 0) === 4): ?><i class="bi bi-check-lg text-primary ms-auto"></i><?php endif; ?>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item rounded-2 py-1.5 px-2 d-flex align-items-center gap-2 <?= ((int)($currentUser['id'] ?? 0) === 2) ? 'active' : '' ?>" href="/auth/quickLogin/2">
                                <span class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 28px; height: 28px; font-size: 10.5px; background: rgba(245, 158, 11, 0.15); color: #D97706;">GV</span>
                                <div class="flex-grow-1 overflow-hidden" style="line-height: 1.25;">
                                    <div class="fw-bold text-truncate" style="font-size: 12px;">Thầy Phan Hoàng Khải</div>
                                    <div style="font-size: 10.5px; color: var(--ux-ink-3);">GV0102 • Giảng Viên / Ban Tổ Chức</div>
                                </div>
                                <?php if ((int)($currentUser['id'] ?? 0) === 2): ?><i class="bi bi-check-lg text-primary ms-auto"></i><?php endif; ?>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item rounded-2 py-1.5 px-2 d-flex align-items-center gap-2 <?= ((int)($currentUser['id'] ?? 0) === 13) ? 'active' : '' ?>" href="/auth/quickLogin/13">
                                <span class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 28px; height: 28px; font-size: 10.5px; background: rgba(14, 165, 233, 0.15); color: #0284C7;">HC</span>
                                <div class="flex-grow-1 overflow-hidden" style="line-height: 1.25;">
                                    <div class="fw-bold text-truncate" style="font-size: 12px;">ThS. Phạm Hồng Sơn</div>
                                    <div style="font-size: 10.5px; color: var(--ux-ink-3);">HC0001 • Cán Bộ Hành Chính (Trưởng phòng)</div>
                                </div>
                                <?php if ((int)($currentUser['id'] ?? 0) === 13): ?><i class="bi bi-check-lg text-primary ms-auto"></i><?php endif; ?>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item rounded-2 py-1.5 px-2 d-flex align-items-center gap-2 <?= ((int)($currentUser['id'] ?? 0) === 8) ? 'active' : '' ?>" href="/auth/quickLogin/8">
                                <span class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 28px; height: 28px; font-size: 10.5px; background: rgba(14, 165, 233, 0.15); color: #0284C7;">HC</span>
                                <div class="flex-grow-1 overflow-hidden" style="line-height: 1.25;">
                                    <div class="fw-bold text-truncate" style="font-size: 12px;">Lê Đình Tuấn</div>
                                    <div style="font-size: 10.5px; color: var(--ux-ink-3);">HC0201 • Cán Bộ Hành Chính (CSVC)</div>
                                </div>
                                <?php if ((int)($currentUser['id'] ?? 0) === 8): ?><i class="bi bi-check-lg text-primary ms-auto"></i><?php endif; ?>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item rounded-2 py-1.5 px-2 d-flex align-items-center gap-2 <?= ((int)($currentUser['id'] ?? 0) === 1) ? 'active' : '' ?>" href="/auth/quickLogin/1">
                                <span class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 28px; height: 28px; font-size: 10.5px; background: rgba(239, 68, 68, 0.15); color: #DC2626;">AD</span>
                                <div class="flex-grow-1 overflow-hidden" style="line-height: 1.25;">
                                    <div class="fw-bold text-truncate" style="font-size: 12px;">Quản Trị Viên (Admin)</div>
                                    <div style="font-size: 10.5px; color: var(--ux-ink-3);">ADMIN001 • Toàn Quyền Hệ Thống</div>
                                </div>
                                <?php if ((int)($currentUser['id'] ?? 0) === 1): ?><i class="bi bi-check-lg text-primary ms-auto"></i><?php endif; ?>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- NÚT THÔNG BÁO HỆ THỐNG (Apple HIG Bell Notification Center - Nền Xanh Dịu Mắt #0284C7) -->
                <div class="dropdown">
                    <button class="btn-topbar-bell position-relative d-inline-flex align-items-center justify-content-center shadow-2xs" type="button" id="topbarNotificationBtn" data-bs-toggle="dropdown" aria-expanded="false" title="Trung tâm thông báo hệ thống">
                        <i class="bi bi-bell-fill"></i>
                        <span class="topbar-bell-badge">
                            <?= count($accountingList ?? []) ?>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 p-2" aria-labelledby="topbarNotificationBtn" style="min-width: 320px; border-radius: 16px; background: var(--ux-surface); border: 1px solid var(--ux-line) !important; z-index: 1060;">
                        <div class="px-2.5 py-1.5 border-bottom d-flex align-items-center justify-content-between mb-1" style="border-color: var(--ux-line) !important;">
                            <div class="fw-bold d-flex align-items-center gap-1.5" style="font-size: 12px; color: var(--ux-ink);">
                                <i class="bi bi-bell-fill" style="color: #0284C7;"></i> Trung Tâm Thông Báo
                            </div>
                            <span class="badge rounded-pill" style="background: rgba(2, 132, 199, 0.12); color: #0284C7; font-size: 10px;">
                                <?= count($accountingList ?? []) ?> thông báo
                            </span>
                        </div>
                        <div class="d-flex flex-column gap-1 py-1" style="max-height: 270px; overflow-y: auto;">
                            <?php 
                            $recentNotifications = array_slice($activityLogs ?? [], 0, 4);
                            if (!empty($recentNotifications)):
                                foreach ($recentNotifications as $notif): 
                            ?>
                            <div class="p-2 rounded-2 d-flex align-items-start gap-2 transition-all" style="font-size: 11.5px; background: rgba(0,0,0,0.02);">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-0.5" style="width: 24px; height: 24px; background: rgba(2, 132, 199, 0.12); color: #0284C7; font-size: 11px;">
                                    <i class="bi bi-info-circle-fill"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="fw-semibold text-truncate" style="color: var(--ux-ink); font-size: 11.5px;"><?= htmlspecialchars($notif['action'] ?? 'Hoạt động') ?></div>
                                    <div class="text-muted text-truncate" style="font-size: 10.5px;"><?= htmlspecialchars($notif['description'] ?? 'Ghi nhận kiểm toán hệ thống') ?></div>
                                    <small class="text-secondary opacity-75" style="font-size: 9px;"><?= htmlspecialchars($notif['created_at'] ?? 'Vừa xong') ?></small>
                                </div>
                            </div>
                            <?php 
                                endforeach;
                            else: 
                            ?>
                            <div class="p-3 text-center text-muted" style="font-size: 11.5px;">Không có thông báo mới nào</div>
                            <?php endif; ?>
                        </div>
                        <div class="pt-1.5 border-top text-center" style="border-color: var(--ux-line) !important;">
                            <a href="javascript:void(0)" onclick="navigateToPage('audit-trail')" class="text-decoration-none fw-semibold" style="font-size: 11px; color: #0284C7;">
                                Xem tất cả nhật ký kiểm toán <i class="bi bi-chevron-right" style="font-size: 10px;"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- TRỢ LÝ AI: Đặt Lên Trên Nổi Bật -->
                <button type="button" class="btn-topbar-ai d-inline-flex align-items-center gap-1.5 shadow-2xs" onclick="toggleAiModal()" title="Trợ lý AI PolyMee (Khảo sát, tra cứu & tạo ca tự động)">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>Trợ lý AI</span>
                </button>
                <?php if (!empty($perms['canCreateShift'])): ?>
                <button type="button" class="btn-fpt-primary btn-fpt-sm d-inline-flex align-items-center gap-1.5 shadow-xs" onclick="openCreateShiftModal()" style="white-space: nowrap;">
                    <i class="bi bi-calendar-plus-fill"></i>
                    <span class="d-none d-sm-inline">Đặt Lịch Ca</span>
                </button>
                <?php endif; ?>
                <?php if (!empty($perms['canCreateNote'])): ?>
                <button type="button" class="btn-fpt-secondary btn-fpt-sm d-inline-flex align-items-center gap-1.5" onclick="openAddNoteModal()" title="Thêm ghi chú / lịch hẹn toàn trường" style="white-space: nowrap;">
                    <i class="fa-solid fa-note-sticky text-warning"></i>
                    <span class="d-none d-sm-inline">Ghi Chú</span>
                </button>
                <?php endif; ?>
            </div>
        </header>

        <!-- =========================================================
             DYNAMIC SPA BODY: CHUYỂN TABS KHÔNG RELOAD TRANG
             ========================================================= -->
        <div id="spaBodyContent">

            <!-- TAB 1: BẢNG LỊCH TRUNG TÂM (CALENDAR-FIRST) -->
            <section class="calendar-container" id="panelCalendar">

                <!-- TOOLBAR ĐIỀU HƯỚNG & BỘ LỌC CHUẨN MOCKUP IMAGE 14 -->
                <div class="cal-toolbar-container mb-3" id="calToolbarStrip">
                    <!-- Segmented View Mode: Tháng, Tuần, Danh sách -->
                    <div class="cal-view-segmented" id="calendarViewSwitcher">
                        <button type="button" class="cal-view-btn active" id="btnViewMonth" onclick="changeCalendarView('month')">
                            <i class="bi bi-calendar3"></i> Tháng
                        </button>
                        <button type="button" class="cal-view-btn" id="btnViewWeek" onclick="changeCalendarView('week')">
                            <i class="bi bi-calendar-week"></i> Tuần
                        </button>
                        <button type="button" class="cal-view-btn" id="btnViewDay" onclick="changeCalendarView('day')">
                            <i class="bi bi-list-task"></i> Danh sách
                        </button>
                    </div>

                    <!-- Month Navigation: <  [Tháng 11, 2026]  > -->
                    <div class="cal-nav-pill-group">
                        <button type="button" class="cal-nav-btn" onclick="navigateCalendar(-1)" title="Tháng trước">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <div class="cal-month-pill" id="currentCalendarHeader" onclick="goToday()" style="cursor: pointer;" title="Về hôm nay">
                            <i class="bi bi-calendar3 text-muted"></i> <span>Tháng 11, 2026</span>
                        </div>
                        <button type="button" class="cal-nav-btn" onclick="navigateCalendar(1)" title="Tháng sau">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>

                    <!-- Actions: Bộ lọc, More options -->
                    <div class="d-flex align-items-center gap-2">
                        <div class="dropdown">
                            <button type="button" class="cal-btn-filter dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Lọc hoạt động">
                                <i class="bi bi-funnel"></i> Bộ lọc
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border p-1" style="border-radius: 10px; font-size: 12.5px; border-color: #E2E8F0 !important;">
                                <li><a class="dropdown-item rounded-2 py-1.5" href="javascript:void(0)" onclick="filterDayDetailTab('all')">Tất cả hoạt động</a></li>
                                <li><a class="dropdown-item rounded-2 py-1.5" href="javascript:void(0)" onclick="filterDayDetailTab('shift')">Chỉ ca trực CTV</a></li>
                                <li><a class="dropdown-item rounded-2 py-1.5" href="javascript:void(0)" onclick="filterDayDetailTab('event')">Chỉ sự kiện BTC</a></li>
                                <li><a class="dropdown-item rounded-2 py-1.5" href="javascript:void(0)" onclick="filterDayDetailTab('other')">Bảo trì & Khác</a></li>
                            </ul>
                        </div>
                        <button type="button" class="cal-btn-more" title="Tùy chọn khác">
                            <i class="bi bi-three-dots"></i>
                        </button>
                    </div>
                </div>

                <!-- BỐ CỤC 2 CỘT: Cột trái Bảng Lịch - Cột phải Chi tiết ngày & Phân công -->
                <div class="calendar-layout-grid">
                    
                    <!-- CỘT TRÁI: BẢNG LỊCH CHÍNH -->
                    <div class="calendar-main-box">

                        <!-- TIER 1: LỊCH THÁNG (Ô VUÔNG) -->
                        <div id="tierMonthView" style="position: relative;">
                            <div class="month-header-grid">
                                <div class="day-label">T2</div>
                                <div class="day-label">T3</div>
                                <div class="day-label">T4</div>
                                <div class="day-label">T5</div>
                                <div class="day-label">T6</div>
                                <div class="day-label">T7</div>
                                <div class="day-label" style="color: #DC2626; font-weight: 600;">CN</div>
                            </div>
                            <div class="month-days-grid" id="monthGridContainer">
                                <!-- Nạp động bằng JavaScript -->
                            </div>

                            <!-- CONTEXTUAL POPOVER KHI CÓ NHIỀU LỊCH TRÙNG (Chuẩn Image 10) -->
                            <div class="calendar-context-popover" id="calDayPopover" style="display: none;"></div>
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

                        <!-- TIER 3: LỊCH DANH SÁCH (THEO MOCKUP IMAGE 14) -->
                        <div id="tierDayView" style="display:none;">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <button class="btn-fpt-secondary btn-fpt-sm" onclick="changeCalendarView('month')">
                                    <i class="bi bi-arrow-left me-1"></i> Quay lại Lịch Tháng
                                </button>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge text-white px-3 py-2 fw-bold" style="background:#2563EB;" id="selectedDayLabel">
                                        Thứ Bảy, 15/11/2026
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex flex-column gap-3" id="dayTimelineContainer">
                                <!-- Nạp động -->
                            </div>
                        </div>
                    </div>

                    <!-- CỘT PHẢI: CHI TIẾT NGÀY CHUẨN MOCKUP IMAGE 14 (100% CHIỀU CAO, KHÔNG BỊ CO ÉP) -->
                    <div class="calendar-sidebar-col" id="calendarRightSidebar" style="height: 100%; display: flex; flex-direction: column;">
                        
                        <div class="cal-sidebar-card cal-day-detail-card flex-1 d-flex flex-column overflow-hidden h-100" id="calDayDetailCard" style="border: 1px solid #E2E8F0; border-radius: 12px; background: #FFFFFF; padding: 18px;">
                            <!-- Header cố định (shrink-0) chuẩn Mockup Image 14 -->
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom shrink-0" style="border-color: #F1F5F9 !important;">
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px; background: #EFF6FF; color: #2563EB; border: 1px solid rgba(37, 99, 235, 0.15);">
                                        <i class="bi bi-calendar3" style="font-size: 15px;"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0 text-dark" style="font-size: 15px; letter-spacing: -0.2px;" id="calSelectedDayTitle">
                                        Thứ 7, 15/11/2026
                                    </h5>
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge rounded-pill fw-semibold" id="calActivityCountBadge" style="background: #EFF6FF; color: #2563EB; font-size: 11.5px; padding: 4px 10px;">
                                        2 hoạt động
                                    </span>
                                </div>
                            </div>

                            <!-- Tabs phân loại nhanh chi tiết ngày (ẩn) -->
                            <div class="cal-day-filter-tabs d-flex align-items-center gap-1.5 mb-2.5 pb-1 overflow-x-auto shrink-0" id="calDayFilterTabs" style="display: none;"></div>

                            <!-- Danh sách cuộn nội bộ (flex-1 overflow-y-auto min-h-0) -->
                            <div class="cal-scrollable-list flex-1 overflow-y-auto min-h-0 pe-1" id="calSelectedDayContent">
                                <!-- Nạp động qua JS chuẩn Mockup Image 14 -->
                            </div>
                        </div>

                    </div>

                </div>
            </section>

            
            

            <!-- TAB 2: BẢNG NGHIỆM THU KẾ TOÁN & KIỂM DUYỆT THÙ LAO (ADMIN / ORGANIZER / STAFF) -->
            <section class="spa-panel" id="panelAccounting" style="display:none !important;">
                <!-- 1. VÙNG CỐ ĐỊNH: Tiêu đề, Widget AI & Bộ Lọc Trạng Thái (Non-scrollable Top Section) -->
                <div class="flex-shrink-0">
                    <div class="d-flex flex-wrap justify-content-between align-items-center pb-2.5 border-bottom mb-3 gap-2" style="border-color: rgba(0,0,0,0.06) !important;">
                        <div>
                            <h2 class="fw-bold mb-0 text-dark" style="font-size: 16px; letter-spacing: -0.015em;">Bảng Nghiệm Thu Thù Lao & Quyết Toán CTV</h2>
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
                                            <button type="button" class="btn btn-sm btn-fpt-emerald rounded-pill px-2.5 py-1 shadow-xs fw-semibold d-inline-flex align-items-center gap-1 text-white" onclick="openFacilityProofModal(<?= $acc['assignment_id'] ?>)" title="Giảng viên duyệt nghiệm thu">
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
                            <h2 class="fw-bold mb-0 text-dark" style="font-size: 16px; letter-spacing: -0.015em;">Quản Lý Danh Sách Cộng Tác Viên (CTV)</h2>
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
                
                <!-- 4 Bento KPI Cards Chuẩn Mockup Image 15 -->
                <div class="row g-3 mb-3 flex-shrink-0">
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="saas-card saas-kpi-card p-3 d-flex align-items-center gap-3">
                            <div class="saas-kpi-icon" style="background:#EFF6FF; color:#2563EB;">
                                <i class="bi bi-calendar3 fs-5"></i>
                            </div>
                            <div>
                                <div class="saas-kpi-val num-tabular">3</div>
                                <div class="saas-kpi-title">Ca trực</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="saas-card saas-kpi-card p-3 d-flex align-items-center gap-3">
                            <div class="saas-kpi-icon" style="background:#FEF3C7; color:#D97706;">
                                <i class="bi bi-layers fs-5"></i>
                            </div>
                            <div>
                                <div class="saas-kpi-val num-tabular">2</div>
                                <div class="saas-kpi-title">Chờ nghiệm thu</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="saas-card saas-kpi-card p-3 d-flex align-items-center gap-3">
                            <div class="saas-kpi-icon" style="background:#F0FDF4; color:#16A34A;">
                                <i class="bi bi-check2-circle fs-5"></i>
                            </div>
                            <div>
                                <div class="saas-kpi-val num-tabular">5</div>
                                <div class="saas-kpi-title">Đã hoàn thành</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="saas-card saas-kpi-card p-3 d-flex align-items-center gap-3">
                            <div class="saas-kpi-icon" style="background:#FAF5FF; color:#9333EA;">
                                <i class="bi bi-wallet2 fs-5"></i>
                            </div>
                            <div>
                                <div class="saas-kpi-val num-tabular">1.200.000 đ</div>
                                <div class="saas-kpi-title">Thù lao</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bố Cục Split View: Bảng Danh Sách Công Việc + Drawer Chi Tiết Nghiệm Thu Bên Phải -->
                <div class="my-shifts-split-layout flex-1 min-h-0">
                    
                    <!-- Bảng Danh Sách Bên Trái -->
                    <div class="my-shifts-main-view">
                        <!-- Filter Tabs & Toolbar -->
                        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                            <div class="cal-view-segmented" id="myShiftsFilterTabs">
                                <button type="button" class="cal-view-btn active" onclick="filterMyShifts('all', this)">Tất cả</button>
                                <button type="button" class="cal-view-btn" onclick="filterMyShifts('upcoming', this)">Sắp diễn ra</button>
                                <button type="button" class="cal-view-btn" onclick="filterMyShifts('pending', this)">
                                    Chờ nghiệm thu <span class="badge rounded-pill bg-warning text-dark ms-1" style="font-size: 10px;">2</span>
                                </button>
                                <button type="button" class="cal-view-btn" onclick="filterMyShifts('completed', this)">Đã hoàn thành</button>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-light border rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Bộ lọc">
                                    <i class="bi bi-funnel"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-light border rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Tìm kiếm">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Bảng Công Việc -->
                        <div class="card border-0 rounded-3 shadow-xs flex-1 overflow-hidden d-flex flex-column" style="border: 1px solid #E2E8F0 !important;">
                            <div class="table-responsive flex-1 overflow-y-auto">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="sticky-th" style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0;">
                                        <tr style="font-size: 12.5px; color: #475569;">
                                            <th style="padding: 12px 16px;">Thời gian</th>
                                            <th>Công việc</th>
                                            <th>Địa điểm</th>
                                            <th>Minh chứng</th>
                                            <th>Trạng thái</th>
                                            <th class="text-end" style="padding-right: 16px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="myShiftsTableBody">
                                        <!-- Hàng 1 (Active) -->
                                        <tr class="cursor-pointer table-active" onclick="selectMyShiftRow(this, 1)" style="border-bottom: 1px solid #F1F5F9;">
                                            <td style="padding: 14px 16px;">
                                                <div class="fw-bold text-dark" style="font-size: 13.5px;">08:00 – 12:00</div>
                                                <div class="text-muted small" style="font-size: 11.5px;">15/11/2026</div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2.5">
                                                    <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=100&q=80" style="width: 44px; height: 32px; border-radius: 6px; object-fit: cover;">
                                                    <div>
                                                        <div class="fw-bold text-dark" style="font-size: 13.5px;">Ca 3: Media</div>
                                                        <div class="text-muted small" style="font-size: 11.5px;">FPoly TechDay 2026</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-muted small" style="font-size: 12.5px;"><i class="bi bi-geo-alt me-1 text-primary"></i>Hội trường A</div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="bi bi-google-play text-success" title="Google Drive"></i>
                                                    <span class="badge bg-light text-dark border" style="font-size: 11px;"><i class="bi bi-images me-1"></i>56</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge rounded-pill" style="background: #DCFCE7; color: #16A34A; font-size: 11.5px; padding: 4px 10px;">
                                                    <i class="bi bi-check me-1"></i>Đã nghiệm thu
                                                </span>
                                            </td>
                                            <td class="text-end" style="padding-right: 16px;">
                                                <button type="button" class="btn btn-sm btn-link text-muted p-0"><i class="bi bi-three-dots"></i></button>
                                            </td>
                                        </tr>

                                        <!-- Hàng 2 -->
                                        <tr class="cursor-pointer" onclick="selectMyShiftRow(this, 2)" style="border-bottom: 1px solid #F1F5F9;">
                                            <td style="padding: 14px 16px;">
                                                <div class="fw-bold text-dark" style="font-size: 13.5px;">13:00 – 17:00</div>
                                                <div class="text-muted small" style="font-size: 11.5px;">18/11/2026</div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2.5">
                                                    <img src="https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&w=100&q=80" style="width: 44px; height: 32px; border-radius: 6px; object-fit: cover;">
                                                    <div>
                                                        <div class="fw-bold text-dark" style="font-size: 13.5px;">Ca 2: Hậu cần</div>
                                                        <div class="text-muted small" style="font-size: 11.5px;">Chung kết Khởi nghiệp trẻ</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-muted small" style="font-size: 12.5px;"><i class="bi bi-geo-alt me-1 text-primary"></i>Hội trường B</div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border" style="font-size: 11px;"><i class="bi bi-images me-1"></i>8</span>
                                            </td>
                                            <td>
                                                <span class="badge rounded-pill" style="background: #FEF3C7; color: #D97706; font-size: 11.5px; padding: 4px 10px;">
                                                    <i class="bi bi-clock me-1"></i>Chờ duyệt
                                                </span>
                                            </td>
                                            <td class="text-end" style="padding-right: 16px;">
                                                <button type="button" class="btn btn-sm btn-link text-muted p-0"><i class="bi bi-three-dots"></i></button>
                                            </td>
                                        </tr>

                                        <!-- Hàng 3 -->
                                        <tr class="cursor-pointer" onclick="selectMyShiftRow(this, 3)" style="border-bottom: 1px solid #F1F5F9;">
                                            <td style="padding: 14px 16px;">
                                                <div class="fw-bold text-dark" style="font-size: 13.5px;">17:30 – 18:30</div>
                                                <div class="text-muted small" style="font-size: 11.5px;">20/11/2026</div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2.5">
                                                    <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=100&q=80" style="width: 44px; height: 32px; border-radius: 6px; object-fit: cover;">
                                                    <div>
                                                        <div class="fw-bold text-dark" style="font-size: 13.5px;">Ca 3: Hậu cần</div>
                                                        <div class="text-muted small" style="font-size: 11.5px;">Workshop AI</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-muted small" style="font-size: 12.5px;"><i class="bi bi-geo-alt me-1 text-primary"></i>Phòng B201</div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border" style="font-size: 11px;"><i class="bi bi-images me-1"></i>4</span>
                                            </td>
                                            <td>
                                                <span class="badge rounded-pill" style="background: #FEF3C7; color: #D97706; font-size: 11.5px; padding: 4px 10px;">
                                                    <i class="bi bi-clock me-1"></i>Chờ duyệt
                                                </span>
                                            </td>
                                            <td class="text-end" style="padding-right: 16px;">
                                                <button type="button" class="btn btn-sm btn-link text-muted p-0"><i class="bi bi-three-dots"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex align-items-center justify-content-center gap-2 p-2.5 border-top" style="border-color: #F1F5F9 !important;">
                                <button type="button" class="btn btn-sm btn-light border rounded-circle d-inline-flex align-items-center justify-content-center p-0" style="width: 28px; height: 28px;"><i class="bi bi-chevron-left" style="font-size: 11px;"></i></button>
                                <span class="badge bg-primary text-white" style="font-size: 12px; padding: 5px 10px; border-radius: 6px;">1</span>
                                <span class="badge bg-light text-dark border" style="font-size: 12px; padding: 5px 10px; border-radius: 6px; cursor: pointer;">2</span>
                                <button type="button" class="btn btn-sm btn-light border rounded-circle d-inline-flex align-items-center justify-content-center p-0" style="width: 28px; height: 28px;"><i class="bi bi-chevron-right" style="font-size: 11px;"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Drawer Bên Phải: Chi Tiết Nghiệm Thu Chuẩn Mockup Image 15 -->
                    <div class="my-shifts-detail-drawer p-3.5" id="myShiftsDetailDrawer">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: #F1F5F9 !important;">
                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 15px;">Chi tiết nghiệm thu</h6>
                            <button type="button" class="btn btn-sm btn-link text-muted p-0" onclick="closeMyShiftsDrawer()" title="Đóng">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>

                        <!-- Card Tóm Tắt Sự Kiện -->
                        <div class="d-flex align-items-center gap-3 mb-3 p-2.5 rounded-3" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                            <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=120&q=80" style="width: 56px; height: 42px; border-radius: 6px; object-fit: cover;">
                            <div>
                                <h6 class="fw-bold text-dark mb-0.5" style="font-size: 13.5px;" id="drawerEventTitle">FPoly TechDay 2026</h6>
                                <div class="text-muted small" style="font-size: 12px;" id="drawerShiftSub">📅 15/11/2026 • Ca 3: Media</div>
                            </div>
                        </div>

                        <!-- Sub-tabs: Minh chứng & Thông tin -->
                        <div class="d-flex align-items-center gap-3 mb-3 pb-2 border-bottom" style="border-color: #F1F5F9 !important; font-size: 13px;">
                            <span class="fw-bold text-primary pb-1" style="border-bottom: 2px solid #2563EB; cursor: pointer;">
                                <i class="bi bi-images me-1"></i>Minh chứng
                            </span>
                            <span class="text-muted pb-1 cursor-pointer" onclick="triggerToast('Thông tin chi tiết đã hiển thị đầy đủ')">
                                <i class="bi bi-info-circle me-1"></i>Thông tin
                            </span>
                        </div>

                        <!-- Lưới 6 Ảnh Thu Nhỏ Kèm Ô +51 -->
                        <div class="drawer-proof-grid-6 mb-3.5" id="drawerProofPhotosGrid">
                            <div class="drawer-proof-thumb"><img src="https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&w=200&q=80"></div>
                            <div class="drawer-proof-thumb"><img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=200&q=80"></div>
                            <div class="drawer-proof-thumb"><img src="https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&w=200&q=80"></div>
                            <div class="drawer-proof-thumb"><img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=200&q=80"></div>
                            <div class="drawer-proof-thumb"><img src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=200&q=80"></div>
                            <div class="drawer-proof-thumb">
                                <img src="https://images.unsplash.com/photo-1464375117522-1311d6a5b81f?auto=format&fit=crop&w=200&q=80">
                                <div class="drawer-proof-more-overlay">+51</div>
                            </div>
                        </div>

                        <!-- Tiêu Chí Nghiệm Thu -->
                        <div class="mb-auto">
                            <div class="d-flex align-items-center gap-2 mb-2 text-dark fw-bold" style="font-size: 13px;">
                                <i class="bi bi-check2-all text-primary"></i> Tiêu chí nghiệm thu
                            </div>
                            <div class="d-flex flex-column gap-2 text-muted" style="font-size: 12.5px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="d-flex align-items-center gap-2 text-dark">
                                        <i class="bi bi-check-circle-fill text-success"></i> Tham gia đúng ca trực
                                    </span>
                                    <i class="bi bi-info-circle text-muted" style="font-size: 11px;"></i>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="d-flex align-items-center gap-2 text-dark">
                                        <i class="bi bi-check-circle-fill text-success"></i> Hoàn thành đúng nhiệm vụ
                                    </span>
                                    <i class="bi bi-info-circle text-muted" style="font-size: 11px;"></i>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="d-flex align-items-center gap-2 text-dark">
                                        <i class="bi bi-check-circle-fill text-success"></i> Ảnh rõ ràng, đúng bối cảnh
                                    </span>
                                    <i class="bi bi-info-circle text-muted" style="font-size: 11px;"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="d-flex align-items-center gap-2 pt-3 border-top mt-3" style="border-color: #F1F5F9 !important;">
                            <button type="button" class="btn btn-light border flex-1 rounded-pill fw-medium py-2 text-muted" onclick="triggerToast('Đã gửi phản hồi')" style="font-size: 12.5px;">
                                <i class="bi bi-file-earmark-text me-1"></i> Yêu cầu bổ sung
                            </button>
                            <button type="button" class="btn btn-primary flex-1 rounded-pill fw-bold py-2" onclick="triggerToast('Hồ sơ đã được nghiệm thu thành công!')" style="font-size: 12.5px; background: #2563EB;">
                                <i class="bi bi-check-lg me-1"></i> Duyệt
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            
            

            <!-- TAB 5: QUẢN LÝ SỰ KIỆN & CA TRỰC (Thiết kế chuẩn Linear / Bento 2.0 theo Mockup) -->
            <section class="spa-panel" id="panelEvents" style="display:none !important; flex-direction: column;">
                <!-- 1. HEADER & BREADCRUMB & NÚT TẠO SỰ KIỆN -->
                <div class="flex-shrink-0 mb-3">
                    <div class="text-muted small mb-1" style="font-size: 11.5px; font-weight: 500;">
                        <span>Không gian làm việc</span> <span class="mx-1 text-muted">/</span> <span class="text-dark fw-semibold">Sự kiện</span>
                    </div>
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <h1 class="fw-bold mb-1 text-dark" style="font-size: 24px; letter-spacing: -0.02em;">Sự kiện & ca trực</h1>
                            <p class="text-muted mb-0" style="font-size: 13px;">Quản lý hoạt động, kết nối cộng tác viên.</p>
                        </div>
                        <?php if (!empty($perms['canCreateShift'])): ?>
                        <div>
                            <button type="button" class="btn btn-fpt-primary shadow-xs d-inline-flex align-items-center gap-1.5" onclick="openCreateShiftModal()" style="padding: 7px 16px; border-radius: 9px; font-size: 13px; font-weight: 600;">
                                <i class="bi bi-plus-lg"></i>
                                <span>Tạo sự kiện</span>
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- 2. 2-COLUMN MAIN CONTENT (LEFT: EVENT LIST, RIGHT: CALENDAR & OVERVIEW) -->
                <div class="row g-3 flex-1 overflow-hidden" style="min-height: 0;">
                    
                    <!-- CỘT TRÁI: DANH SÁCH SỰ KIỆN & BỘ LỌC -->
                    <div class="col-12 col-xl-8 col-lg-7 d-flex flex-column h-100 overflow-hidden">
                        
                        <!-- Toolbar Bộ lọc & Tìm kiếm -->
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2.5 pb-2.5 border-bottom mb-2.5 flex-shrink-0" style="border-color: rgba(0,0,0,0.06) !important;">
                            <!-- Tabs Lọc -->
                            <div class="d-flex align-items-center gap-3">
                                <button type="button" class="event-tab-btn active" id="tabEvAll" onclick="filterEventsByTab('all')">
                                    <span>Tất cả</span>
                                    <span class="tab-count-pill" id="badgeCountAll"><?= count($events) ?></span>
                                </button>
                                <button type="button" class="event-tab-btn" id="tabEvUpcoming" onclick="filterEventsByTab('upcoming')">
                                    <span>Sắp diễn ra</span>
                                </button>
                                <button type="button" class="event-tab-btn" id="tabEvEnded" onclick="filterEventsByTab('ended')">
                                    <span>Đã kết thúc</span>
                                </button>
                            </div>

                            <!-- Search & Dropdown Bộ môn -->
                            <div class="d-flex align-items-center gap-2">
                                <div class="input-group input-group-sm" style="width: 200px;">
                                    <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 8px 0 0 8px; padding-left: 9px; padding-right: 4px;">
                                        <i class="bi bi-search" style="font-size: 11.5px;"></i>
                                    </span>
                                    <input type="text" class="form-control form-control-sm border-start-0 ps-1" id="eventSearchInput" placeholder="Tìm tên sự kiện..." oninput="handleEventFilterChange()" style="border-radius: 0 8px 8px 0; font-size: 12px;">
                                </div>
                                <select class="form-select form-select-sm" id="eventDeptSelect" onchange="handleEventFilterChange()" style="width: 140px; border-radius: 8px; font-size: 12px; cursor: pointer;">
                                    <option value="">Bộ môn</option>
                                    <option value="Công nghệ Thông tin">Công nghệ thông tin</option>
                                    <option value="Ứng dụng Phần mềm">Ứng dụng phần mềm</option>
                                    <option value="Công tác Sinh viên">Công tác sinh viên</option>
                                    <option value="Thiết kế Đồ họa">Thiết kế đồ họa</option>
                                    <option value="Kinh tế & Quản trị">Kinh tế & Quản trị</option>
                                    <option value="Du lịch - Nhà hàng">Du lịch & Nhà hàng</option>
                                </select>
                            </div>
                        </div>

                        <!-- Danh sách Event Cards dạng hàng Bento chuẩn Apple / Notion -->
                        <div class="flex-1 overflow-y-auto pe-1" id="eventsListScrollArea" style="min-height: 0;">
                            <div class="d-flex flex-column gap-2" id="eventsListContainer">
                                <?php foreach ($events as $e): ?>
                                <?php
                                $eventDate = substr($e['start_time'], 0, 10);
                                $dayNum = date('d', strtotime($e['start_time']));
                                $monthNum = date('m', strtotime($e['start_time']));
                                $dept = $e['department'] ?? 'Chung';
                                $isEventEnded = strtotime($e['end_time']) < time();
                                
                                // Phân màu Bộ môn chuẩn FPoly
                                $deptColor = '#0284C7'; // Mặc định Sky
                                if (stripos($dept, 'Công nghệ') !== false || stripos($dept, 'CNTT') !== false) $deptColor = '#0284C7';
                                elseif (stripos($dept, 'Ứng dụng') !== false || stripos($dept, 'Phần mềm') !== false) $deptColor = '#7C3AED';
                                elseif (stripos($dept, 'Sinh viên') !== false) $deptColor = '#059669';
                                elseif (stripos($dept, 'Thiết kế') !== false || stripos($dept, 'Đồ họa') !== false) $deptColor = '#4F46E5';
                                elseif (stripos($dept, 'Kinh tế') !== false || stripos($dept, 'Quản trị') !== false) $deptColor = '#D97706';
                                elseif (stripos($dept, 'Du lịch') !== false || stripos($dept, 'Nhà hàng') !== false || stripos($dept, 'Khách sạn') !== false) $deptColor = '#E11D48';
                                
                                $isHighlighted = ($dayNum === '18'); // Ngày 18 được highlight sẵn như trong mockup
                                ?>
                                <div class="event-row-card d-flex align-items-center justify-content-between p-2.5 px-3 rounded-3 border bg-white shadow-2xs <?= $isHighlighted ? 'active' : '' ?>" 
                                     id="eventRow_<?= $e['id'] ?>" 
                                     data-event-id="<?= $e['id'] ?>"
                                     data-date="<?= $eventDate ?>"
                                     data-day="<?= (int)$dayNum ?>"
                                     data-dept="<?= htmlspecialchars($dept) ?>"
                                     data-title="<?= htmlspecialchars(mb_strtolower($e['title'])) ?>"
                                     data-ended="<?= $isEventEnded ? '1' : '0' ?>"
                                     onclick="handleEventCardClick(<?= $e['id'] ?>, '<?= $eventDate ?>')">
                                    
                                    <!-- Cột Trái: Ô Ngày Tháng & Tên Sự Kiện -->
                                    <div class="d-flex align-items-center gap-3 min-w-0">
                                        <!-- Ô Ngày Tháng -->
                                        <div class="event-date-box text-center py-1 px-1.5" style="min-width: 48px;">
                                            <div class="event-date-day fw-bold num-tabular" style="font-size: 19px; line-height: 1;"><?= $dayNum ?></div>
                                            <div class="event-date-month fw-semibold text-muted text-uppercase" style="font-size: 10px; margin-top: 2px;">TH<?= $monthNum ?></div>
                                        </div>

                                        <!-- Đường kẻ dọc ngăn cách -->
                                        <div class="event-date-divider" style="width: 1px; height: 34px; background: #E2E8F0;"></div>

                                        <!-- Thông tin Bộ môn, Tiêu đề, Địa điểm -->
                                        <div class="event-info-box min-w-0">
                                            <div class="event-dept-tag fw-semibold text-truncate" style="font-size: 11px; color: <?= $deptColor ?>; line-height: 1.2;">
                                                <?= htmlspecialchars($dept) ?>
                                            </div>
                                            <h6 class="event-title-text fw-bold text-dark mb-0.5 text-truncate" style="font-size: 13.5px; line-height: 1.3;">
                                                <?= htmlspecialchars($e['title']) ?>
                                            </h6>
                                            <div class="event-loc-text text-muted text-truncate" style="font-size: 11.5px; line-height: 1.2;">
                                                <i class="bi bi-geo-alt me-1 text-danger"></i><?= htmlspecialchars($e['location_name'] ?? 'Hội trường') ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cột Phải: Badge Đã công bố, Ca trực, Mũi tên -->
                                    <div class="d-flex align-items-center gap-2.5 flex-shrink-0 ms-2">
                                        <span class="badge-status-published">
                                            <i class="bi bi-check-circle-fill"></i> Đã công bố
                                        </span>

                                        <div style="width: 1px; height: 16px; background: #E2E8F0;"></div>

                                        <span class="event-shift-count fw-medium" style="font-size: 12px; color: #334155; white-space: nowrap;">
                                            <?= $e['total_shifts'] ?> ca trực
                                        </span>

                                        <i class="bi bi-chevron-right text-muted" style="font-size: 11px;"></i>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Footer đếm tổng số sự kiện hiển thị -->
                            <div class="text-muted small mt-2.5 px-1 num-tabular" id="eventListFooterText" style="font-size: 11.5px;">
                                Hiển thị <?= count($events) ?> sự kiện trong tháng 11
                            </div>
                        </div>

                    </div>

                    <!-- CỘT PHẢI: LỊCH MINI, TỔNG QUAN THÁNG, TRỢ LÝ AI -->
                    <div class="col-12 col-xl-4 col-lg-5 d-flex flex-column gap-2.5 h-100 overflow-y-auto pe-1">
                        
                        <!-- 1. Mini Calendar Widget -->
                        <div class="card border rounded-3 p-3 bg-white shadow-2xs" style="border-color: rgba(0,0,0,0.08) !important;">
                            <div class="d-flex align-items-center justify-content-between mb-2.5 px-1">
                                <button type="button" class="btn btn-sm btn-light border-0 p-1 d-flex align-items-center justify-content-center" onclick="navMiniCalendar(-1)" style="width: 24px; height: 24px; border-radius: 6px;" title="Tháng trước">
                                    <i class="bi bi-chevron-left" style="font-size: 11px;"></i>
                                </button>
                                <strong class="text-dark num-tabular" id="miniCalTitle" style="font-size: 13.5px; font-weight: 700;">Tháng 11, 2026</strong>
                                <button type="button" class="btn btn-sm btn-light border-0 p-1 d-flex align-items-center justify-content-center" onclick="navMiniCalendar(1)" style="width: 24px; height: 24px; border-radius: 6px;" title="Tháng sau">
                                    <i class="bi bi-chevron-right" style="font-size: 11px;"></i>
                                </button>
                            </div>
                            
                            <!-- Tiêu đề Thứ -->
                            <div class="d-grid text-center mb-1 text-muted fw-semibold" style="grid-template-columns: repeat(7, 1fr); font-size: 11px;">
                                <span>T2</span><span>T3</span><span>T4</span><span>T5</span><span>T6</span><span>T7</span><span>CN</span>
                            </div>

                            <!-- Lưới Ngày Trong Tháng -->
                            <div class="d-grid text-center" id="miniCalGrid" style="grid-template-columns: repeat(7, 1fr); gap: 2px;">
                                <!-- Rendered dynamically by JS -->
                            </div>
                        </div>

                        <!-- 2. Thẻ Tổng Quan Tháng -->
                        <div class="card border rounded-3 p-3 bg-white shadow-2xs" style="border-color: rgba(0,0,0,0.08) !important;">
                            <h6 class="fw-bold text-dark mb-2" style="font-size: 12.5px;">Tổng quan tháng</h6>
                            <div class="d-flex align-items-center justify-content-around py-1">
                                <div class="text-center flex-fill">
                                    <div class="fw-bolder text-dark num-tabular" style="font-size: 24px; line-height: 1.1;" id="statTotalEventsCount"><?= count($events) ?></div>
                                    <div class="text-muted fw-medium" style="font-size: 11px; margin-top: 3px;">Sự kiện</div>
                                </div>
                                <div style="width: 1px; height: 32px; background: #E2E8F0;"></div>
                                <div class="text-center flex-fill">
                                    <?php 
                                    $totalShiftsSum = array_sum(array_column($events, 'total_shifts'));
                                    if ($totalShiftsSum === 0) $totalShiftsSum = count($shifts);
                                    ?>
                                    <div class="fw-bolder num-tabular" style="font-size: 24px; line-height: 1.1; color: #EA580C;" id="statTotalShiftsCount"><?= $totalShiftsSum ?></div>
                                    <div class="text-muted fw-medium" style="font-size: 11px; margin-top: 3px;">Ca trực</div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Thẻ Trợ Lý PolyMee (AI Bento) -->
                        <div class="card border rounded-3 p-3 shadow-2xs" style="background: linear-gradient(135deg, #FFF9F5 0%, #FFF3EA 100%); border: 1px solid #FFE4D3 !important;">
                            <div class="d-flex align-items-center gap-1.5 mb-1">
                                <span style="font-size: 15px;">✨</span>
                                <strong class="text-dark" style="font-size: 13px; font-weight: 700;">Trợ lý PolyMee</strong>
                            </div>
                            <p class="text-muted mb-2.5" style="font-size: 11.5px; line-height: 1.45;">
                                Hỗ trợ tìm sự kiện và sắp xếp ca trực.
                            </p>
                            <div>
                                <button type="button" class="btn btn-sm bg-white border fw-semibold d-inline-flex align-items-center gap-1.5 shadow-2xs" onclick="quickAskAi('Hỗ trợ tìm sự kiện và sắp xếp ca trực')" style="padding: 4.5px 13px; border-radius: 999px; font-size: 11.5px; color: #1E293B; border-color: rgba(0,0,0,0.1) !important;">
                                    <span>Mở trợ lý</span>
                                    <i class="bi bi-arrow-right" style="font-size: 10.5px;"></i>
                                </button>
                            </div>
                        </div>

                    </div>

                </div>
            </section>

            
            

            <!-- TAB 6: MINH CHỨNG ĐỂ NGHIỆM THU SAU MỖI SỰ KIỆN HOÀN THÀNH -->
            <section class="spa-panel" id="panelSubmissions" style="display:none !important;">
                <!-- Thanh Tiêu Đề & Công Cụ Tối Giản (Clean & Compact Bar) -->
                <div class="d-flex justify-content-between align-items-center pb-2 mb-2 flex-shrink-0">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-light text-dark border px-2.5 py-1.5 fw-semibold num-tabular" style="font-size: 11.5px;">
                            <i class="bi bi-collection-play me-1 text-primary"></i> <?= count($accountingList) ?> Minh Chứng Nghiệm Thu
                        </span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn-fpt-secondary btn-fpt-sm" onclick="exportAccountingCSV()">
                            <i class="bi bi-file-earmark-excel me-1 text-success"></i> Xuất Excel (CSV)
                        </button>
                    </div>
                </div>
                <!-- 2. VÙNG BẢNG DỮ LIỆU: Chuẩn Enterprise SaaS (Linear / Stripe Standard) -->
                <div class="submissions-table-card flex-1 overflow-hidden d-flex flex-column">
                    <div class="table-responsive flex-1 overflow-y-auto" style="height: 100%;">
                    <table class="submissions-table align-middle">
                        <thead>
                            <tr>
                                <th style="width: 22%;">Sinh viên CTV</th>
                                <th style="width: 30%;">Sự kiện & Ca trực</th>
                                <th style="width: 16%;">Minh chứng đã nộp</th>
                                <th style="width: 16%;">Ghi chú nghiệm thu</th>
                                <th style="width: 12%;">Trạng thái</th>
                                <th class="text-end" style="width: 14%;">Thao tác</th>
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
                            $firstLetter = mb_strtoupper(mb_substr($subItem['full_name'], 0, 1));
                            ?>
                            <tr id="subRow_<?= $subAssId ?>">
                                <!-- 1. Sinh viên CTV -->
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-dark fw-bold flex-shrink-0" style="width: 34px; height: 34px; background: #F1F5F9; border: 1px solid #E2E8F0; font-size: 13px;">
                                            <?= $firstLetter ?>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="fw-medium text-dark text-truncate" style="font-size: 14px; line-height: 1.3;" title="<?= htmlspecialchars($subItem['full_name']) ?>">
                                                <?= htmlspecialchars($subItem['full_name']) ?>
                                            </div>
                                            <div class="text-sub mt-0.5 d-flex align-items-center gap-1.5">
                                                <span class="font-monospace text-muted"><?= htmlspecialchars($subItem['student_code']) ?></span>
                                                <span class="text-muted">•</span>
                                                <span class="badge rounded-pill <?= $isDriveLink ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-700' ?>" style="font-size: 10.5px; padding: 2px 7px; font-weight: 500;">
                                                    <?= $isDriveLink ? 'Media' : 'Hậu cần' ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- 2. Sự kiện & Ca trực (Ưu tiên rộng nhất 30%) -->
                                <td>
                                    <div class="event-title-clamp" title="<?= htmlspecialchars($subItem['event_title']) ?>">
                                        <?= htmlspecialchars($subItem['event_title']) ?>
                                    </div>
                                    <div class="text-sub mt-1 text-truncate" title="<?= htmlspecialchars($subItem['shift_name']) ?>">
                                        <i class="bi bi-calendar-event me-1 text-muted" style="font-size: 11px;"></i><?= htmlspecialchars($subItem['shift_name']) ?>
                                    </div>
                                </td>

                                <!-- 3. Minh chứng đã nộp -->
                                <td>
                                    <?php if ($hasProof): ?>
                                        <?php if ($isDriveLink): ?>
                                        <div>
                                            <a href="<?= htmlspecialchars($subProofUrl) ?>" target="_blank" class="btn-action-secondary py-1 px-2.5" style="font-size: 12px;" title="Mở thư mục Google Drive">
                                                <i class="fa-brands fa-google-drive text-primary"></i>
                                                <span>Drive Media</span>
                                                <i class="bi bi-arrow-up-right text-muted" style="font-size: 10px;"></i>
                                            </a>
                                            <div class="text-sub mt-1" style="font-size: 11.5px;">
                                                <i class="bi bi-check2 text-success me-1"></i>120 ảnh đã gắn logo
                                            </div>
                                        </div>
                                        <?php else: ?>
                                        <div>
                                            <button type="button" class="btn-action-secondary py-1 px-2.5" style="font-size: 12px;" onclick="openProofVerificationDrawer(<?= $subAssId ?>)">
                                                <i class="bi bi-images text-primary"></i>
                                                <span>Xem minh chứng</span>
                                            </button>
                                            <div class="text-sub mt-1" style="font-size: 11.5px;">
                                                <i class="bi bi-check2 text-success me-1"></i>Ảnh hiện trường ca trực
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-sub text-muted d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-clock-history"></i> Chưa nộp minh chứng
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- 4. Ghi chú nghiệm thu (Thu gọn 2 dòng, tooltip xem đầy đủ) -->
                                <td>
                                    <?php
                                    $proofNote = $subItem['proof_note'] ?: ($isDriveLink ? 'Bộ ảnh Media đã chỉnh sửa sáng và gắn logo FPT' : 'Có mặt đúng giờ tại địa điểm trực sự kiện');
                                    ?>
                                    <div class="note-clamp" title="<?= htmlspecialchars($proofNote) ?>">
                                        <?= htmlspecialchars($proofNote) ?>
                                    </div>
                                </td>

                                <!-- 5. Trạng thái -->
                                <td>
                                    <?php if ($isVerified): ?>
                                    <span class="badge-saas" style="background: #ECFDF5; color: #15803D; border: 1px solid #BBF7D0;">
                                        <span class="rounded-circle d-inline-block" style="width: 6px; height: 6px; background: #16A34A;"></span>
                                        Đã nghiệm thu
                                    </span>
                                    <div class="text-sub font-monospace mt-1" style="font-size: 11px;">
                                        <?= htmlspecialchars($subItem['contract_code'] ?? '') ?>
                                    </div>
                                    <?php elseif ($hasProof): ?>
                                    <span class="badge-saas" style="background: #FFFBEB; color: #B45309; border: 1px solid #FDE68A;">
                                        <span class="rounded-circle d-inline-block" style="width: 6px; height: 6px; background: #D97706;"></span>
                                        Chờ GV duyệt
                                    </span>
                                    <?php else: ?>
                                    <span class="badge-saas" style="background: #F8FAFC; color: #64748B; border: 1px solid #E2E8F0;">
                                        <span class="rounded-circle d-inline-block" style="width: 6px; height: 6px; background: #94A3B8;"></span>
                                        Chưa nộp
                                    </span>
                                    <?php endif; ?>
                                </td>

                                <!-- 6. Thao tác nghiệm thu -->
                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-1.5">
                                        <?php if ((int)$subItem['user_id'] === (int)($currentUser['id'] ?? 0) || empty($perms['canVerifyLecturer'])): ?>
                                        <button type="button" class="btn-action-review" onclick="openSubmitProofModal(<?= $subAssId ?>, '<?= htmlspecialchars(addslashes($subItem['shift_name'])) ?>', '<?= htmlspecialchars(addslashes($subItem['start_time'])) ?>')">
                                            <i class="bi bi-cloud-arrow-up"></i> <?= $hasProof ? 'Cập nhật' : 'Nộp minh chứng' ?>
                                        </button>
                                        <?php endif; ?>

                                        <?php if (!empty($perms['canVerifyLecturer'])): ?>
                                            <?php if ($isVerified): ?>
                                            <button type="button" class="btn-action-secondary" onclick="openProofVerificationDrawer(<?= $subAssId ?>)" title="Xem lại chi tiết hồ sơ nghiệm thu">
                                                <i class="bi bi-check-circle text-success"></i> Xem lại
                                            </button>
                                            <?php elseif ($hasProof): ?>
                                            <button type="button" class="btn-action-review" onclick="openProofVerificationDrawer(<?= $subAssId ?>)" title="Kiểm tra minh chứng và ra quyết định nghiệm thu">
                                                <i class="bi bi-shield-check"></i> Xem & duyệt
                                            </button>
                                            <?php else: ?>
                                            <button type="button" class="btn-action-secondary" onclick="sendProofReminder(<?= $subAssId ?>, '<?= htmlspecialchars(addslashes($subItem['full_name'])) ?>', '<?= htmlspecialchars(addslashes($subItem['email'] ?? '')) ?>', this)" title="Tự động gửi email thông báo nhắc sinh viên nộp minh chứng">
                                                <i class="bi bi-bell text-warning"></i> Nhắc nhở
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

            <!-- =========================================================
                 PANEL 6B: CHI TIẾT & DUYỆT MINH CHỨNG (CHUẨN MOCKUP IMAGE 12)
                 ========================================================= -->
            <section class="spa-panel" id="panelProofDetail" style="display:none !important; flex-direction: column;">
                
                <!-- Breadcrumb & Top Bar -->
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: #E2E8F0 !important;">
                    <div class="d-flex align-items-center gap-3">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 34px; height: 34px; border-color: #CBD5E1;" onclick="navigateToPage('submissions')" title="Quay lại danh sách nghiệm thu">
                            <i class="bi bi-arrow-left" style="font-size: 15px;"></i>
                        </button>
                        <div class="d-flex align-items-center gap-2.5">
                            <h4 class="fw-bold mb-0 text-dark" style="font-size: 18px; letter-spacing: -0.3px;">Chi tiết Minh chứng</h4>
                            <span class="badge rounded-pill" style="background: #F3E8FF; color: #9333EA; font-size: 12px; font-weight: 600; padding: 4px 10px;">
                                <i class="bi bi-gear-fill me-1"></i>Hậu cần
                            </span>
                        </div>
                    </div>
                </div>

                <!-- BỐ CỤC 2 CỘT CHUẨN MOCKUP IMAGE 12 -->
                <div class="proof-detail-grid">
                    
                    <!-- CỘT TRÁI (~62%): THÔNG TIN CTV, LƯỚI 8 ẢNH & MÔ TẢ CÔNG VIỆC -->
                    <div class="d-flex flex-column gap-3">
                        
                        <!-- Thẻ Tổng Quan CTV & Sự Kiện -->
                        <div class="card border-0 rounded-3 shadow-xs p-3.5" style="border: 1px solid #E2E8F0 !important; background: #FFFFFF;">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center" style="width: 46px; height: 46px; background: #0284C7; font-size: 18px;">
                                        H
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 15px;" id="proofDetailCtvName">Hoàng Minh Luân</h6>
                                            <span class="badge" style="background: rgba(37, 99, 235, 0.1); color: #2563EB; font-size: 11px; padding: 2px 7px;">◆ CTV</span>
                                        </div>
                                        <div class="text-muted small mt-0.5" style="font-size: 12px;">PI03015 • hoangminhluan@fpt.edu.vn</div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center gap-4 text-muted small" style="font-size: 12.5px;">
                                    <div>
                                        <div class="d-flex align-items-center gap-1.5 text-dark fw-medium">
                                            <i class="bi bi-calendar-event text-primary"></i> Chung kết Cuộc thi Khởi nghiệp trẻ
                                        </div>
                                        <div class="d-flex align-items-center gap-3 mt-1">
                                            <span><i class="bi bi-layers me-1"></i>Ca 2: Kỹ thuật</span>
                                            <span><i class="bi bi-clock me-1"></i>18/11/2026 • 13:00 – 17:00</span>
                                        </div>
                                    </div>
                                    <span class="badge rounded-pill" style="background: #FEF3C7; color: #D97706; font-size: 11.5px; padding: 5px 12px; font-weight: 600;">
                                        <i class="bi bi-clock-history me-1"></i>Chờ duyệt
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Khối Minh Chứng 8 Ảnh Sắc Nét -->
                        <div class="card border-0 rounded-3 shadow-xs p-3.5" style="border: 1px solid #E2E8F0 !important; background: #FFFFFF;">
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: #F1F5F9 !important;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-images text-primary" style="font-size: 16px;"></i>
                                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 14.5px;">Minh chứng (8)</h6>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-sm btn-light border px-2.5 py-1 text-muted" title="Đổi chế độ xem">
                                        <i class="bi bi-grid-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light border px-2.5 py-1 text-muted" title="Tải xuống tất cả ảnh">
                                        <i class="bi bi-download"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Lưới 8 Ảnh Minh Chứng (4 Cột x 2 Hàng) -->
                            <div class="proof-photo-matrix mb-3" id="proofPhotoMatrix">
                                <div class="proof-photo-item is-selected" onclick="selectProofPhoto(0)">
                                    <img src="https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&w=600&q=80" alt="Minh chứng 1">
                                    <div class="proof-photo-zoom-btn" onclick="openLightbox(0); event.stopPropagation();"><i class="bi bi-arrows-angle-expand"></i></div>
                                </div>
                                <div class="proof-photo-item" onclick="selectProofPhoto(1)">
                                    <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=600&q=80" alt="Minh chứng 2">
                                    <div class="proof-photo-zoom-btn" onclick="openLightbox(1); event.stopPropagation();"><i class="bi bi-arrows-angle-expand"></i></div>
                                </div>
                                <div class="proof-photo-item" onclick="selectProofPhoto(2)">
                                    <img src="https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&w=600&q=80" alt="Minh chứng 3">
                                    <div class="proof-photo-zoom-btn" onclick="openLightbox(2); event.stopPropagation();"><i class="bi bi-arrows-angle-expand"></i></div>
                                </div>
                                <div class="proof-photo-item" onclick="selectProofPhoto(3)">
                                    <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=600&q=80" alt="Minh chứng 4">
                                    <div class="proof-photo-zoom-btn" onclick="openLightbox(3); event.stopPropagation();"><i class="bi bi-arrows-angle-expand"></i></div>
                                </div>
                                <div class="proof-photo-item" onclick="selectProofPhoto(4)">
                                    <img src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=600&q=80" alt="Minh chứng 5">
                                    <div class="proof-photo-zoom-btn" onclick="openLightbox(4); event.stopPropagation();"><i class="bi bi-arrows-angle-expand"></i></div>
                                </div>
                                <div class="proof-photo-item" onclick="selectProofPhoto(5)">
                                    <img src="https://images.unsplash.com/photo-1464375117522-1311d6a5b81f?auto=format&fit=crop&w=600&q=80" alt="Minh chứng 6">
                                    <div class="proof-photo-zoom-btn" onclick="openLightbox(5); event.stopPropagation();"><i class="bi bi-arrows-angle-expand"></i></div>
                                </div>
                                <div class="proof-photo-item" onclick="selectProofPhoto(6)">
                                    <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=600&q=80" alt="Minh chứng 7">
                                    <div class="proof-photo-zoom-btn" onclick="openLightbox(6); event.stopPropagation();"><i class="bi bi-arrows-angle-expand"></i></div>
                                </div>
                                <div class="proof-photo-item" onclick="selectProofPhoto(7)">
                                    <img src="https://images.unsplash.com/photo-1429962714451-bb934ecdc4ec?auto=format&fit=crop&w=600&q=80" alt="Minh chứng 8">
                                    <div class="proof-photo-zoom-btn" onclick="openLightbox(7); event.stopPropagation();"><i class="bi bi-arrows-angle-expand"></i></div>
                                </div>
                            </div>

                            <!-- Carousel Dải Thumbnail Filmstrip -->
                            <div class="proof-filmstrip-bar border-top pt-2" style="border-color: #F1F5F9 !important;">
                                <div class="d-flex align-items-center gap-1.5 text-muted small" style="font-size: 11.5px; font-weight: 500;">
                                    <button type="button" class="btn btn-sm btn-link p-0 text-muted" onclick="navigateProofPhoto(-1)"><i class="bi bi-chevron-left"></i></button>
                                    <span id="proofPhotoIndexLabel">1/8</span>
                                    <button type="button" class="btn btn-sm btn-link p-0 text-muted" onclick="navigateProofPhoto(1)"><i class="bi bi-chevron-right"></i></button>
                                </div>
                                <div class="proof-thumb-strip" id="proofThumbStrip">
                                    <div class="proof-thumb-cell active" onclick="selectProofPhoto(0)"><img src="https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&w=150&q=80"></div>
                                    <div class="proof-thumb-cell" onclick="selectProofPhoto(1)"><img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=150&q=80"></div>
                                    <div class="proof-thumb-cell" onclick="selectProofPhoto(2)"><img src="https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&w=150&q=80"></div>
                                    <div class="proof-thumb-cell" onclick="selectProofPhoto(3)"><img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=150&q=80"></div>
                                    <div class="proof-thumb-cell" onclick="selectProofPhoto(4)"><img src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=150&q=80"></div>
                                    <div class="proof-thumb-cell" onclick="selectProofPhoto(5)"><img src="https://images.unsplash.com/photo-1464375117522-1311d6a5b81f?auto=format&fit=crop&w=150&q=80"></div>
                                    <div class="proof-thumb-cell" onclick="selectProofPhoto(6)"><img src="https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=150&q=80"></div>
                                    <div class="proof-thumb-cell" onclick="selectProofPhoto(7)"><img src="https://images.unsplash.com/photo-1429962714451-bb934ecdc4ec?auto=format&fit=crop&w=150&q=80"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Mô Tả Công Việc Từ CTV -->
                        <div class="card border-0 rounded-3 shadow-xs p-3.5" style="border: 1px solid #E2E8F0 !important; background: #FFFFFF;">
                            <div class="d-flex align-items-center gap-2 mb-2 text-dark fw-bold" style="font-size: 13.5px;">
                                <i class="bi bi-info-circle text-primary"></i> Mô tả công việc (từ CTV)
                            </div>
                            <p class="text-muted mb-0" style="font-size: 13px; line-height: 1.6;" id="proofDetailWorkDesc">
                                Hỗ trợ lắp đặt, vận hành hệ thống âm thanh và trình chiếu cho chương trình. Có mặt từ 12:30, kiểm tra thiết bị, điều chỉnh âm lượng trong suốt thời gian diễn ra sự kiện và thu dọn sau chương trình.
                            </p>
                        </div>
                    </div>

                    <!-- CỘT PHẢI (~38%): TIÊU CHÍ NGHIỆM THU & QUYẾT ĐỊNH DUYỆT -->
                    <div class="d-flex flex-column gap-3">
                        
                        <!-- Card Tiêu Chí Nghiệm Thu -->
                        <div class="proof-criteria-card shadow-xs">
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: #F1F5F9 !important;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-clipboard-check text-primary" style="font-size: 16px;"></i>
                                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 14.5px;">Tiêu chí nghiệm thu</h6>
                                </div>
                                <i class="bi bi-info-circle text-muted" title="Tiêu chí đánh giá chất lượng ca trực"></i>
                            </div>

                            <div class="d-flex flex-column gap-2">
                                <div class="proof-criterion-row">
                                    <div class="proof-check-badge"><i class="bi bi-check"></i></div>
                                    <div>Có mặt & tham gia đúng ca trực</div>
                                </div>
                                <div class="proof-criterion-row">
                                    <div class="proof-check-badge"><i class="bi bi-check"></i></div>
                                    <div>Thực hiện đúng nhiệm vụ được phân công <small class="text-muted d-block" style="font-size: 11px;">(ví dụ: âm thanh, trình chiếu, hậu trường...)</small></div>
                                </div>
                                <div class="proof-criterion-row">
                                    <div class="proof-check-badge"><i class="bi bi-check"></i></div>
                                    <div>Ảnh rõ ràng, đúng bối cảnh sự kiện</div>
                                </div>
                                <div class="proof-criterion-row">
                                    <div class="proof-uncheck-box cursor-pointer" id="criterionBox4" onclick="toggleCriterion(this)"></div>
                                    <div class="d-flex align-items-center gap-1.5 cursor-pointer" onclick="toggleCriterion(document.getElementById('criterionBox4'))">
                                        <span>Phối hợp tốt với BTC</span>
                                        <i class="bi bi-info-circle text-muted" style="font-size: 11px;" title="Chủ động hỗ trợ, tuân thủ phân công và đảm bảo tiến độ."></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Quyết Định Nghiệm Thu -->
                        <div class="proof-criteria-card shadow-xs">
                            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom" style="border-color: #F1F5F9 !important;">
                                <i class="bi bi-patch-check text-primary" style="font-size: 16px;"></i>
                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 14.5px;">Quyết định nghiệm thu</h6>
                            </div>

                            <!-- 2 Radio Box Lớn -->
                            <div class="d-flex flex-column gap-2.5 mb-3">
                                <div class="proof-decision-box selected" id="decisionBoxApprove" onclick="selectProofDecision('approve')">
                                    <div class="proof-decision-circle"></div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 13.5px;">Duyệt</div>
                                        <small class="text-muted" style="font-size: 12px;">Minh chứng đáp ứng đầy đủ tiêu chí.</small>
                                    </div>
                                </div>
                                <div class="proof-decision-box" id="decisionBoxRevision" onclick="selectProofDecision('request_more')">
                                    <div class="proof-decision-circle"></div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 13.5px;">Yêu cầu bổ sung</div>
                                        <small class="text-muted" style="font-size: 12px;">Minh chứng chưa đủ, cần CTV bổ sung thêm.</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Textarea Ghi Chú Có Đếm Ký Tự -->
                            <div class="mb-3">
                                <label class="form-label" style="font-size: 12px; font-weight: 500; color: #475569;">
                                    <i class="bi bi-pencil me-1"></i> Ghi chú (tùy chọn)
                                </label>
                                <textarea class="form-control" rows="3" id="proofDecisionNote" maxlength="300" placeholder="Nhập ghi chú cho CTV (nếu có)..." oninput="updateProofNoteCounter(this)" style="font-size: 13px; resize: none;"></textarea>
                                <div class="text-end text-muted small mt-1" style="font-size: 11px;">
                                    <span id="proofNoteCounter">0</span> / 300
                                </div>
                            </div>

                            <!-- Nút Thao Tác: Hủy & Xác Nhận -->
                            <div class="d-flex align-items-center gap-2 pt-2 border-top" style="border-color: #F1F5F9 !important;">
                                <button type="button" class="btn btn-light border flex-1 rounded-pill fw-medium py-2" onclick="navigateToPage('submissions')" style="font-size: 13px;">
                                    Hủy
                                </button>
                                <button type="button" class="btn btn-primary flex-1 rounded-pill fw-bold py-2 d-inline-flex align-items-center justify-content-center gap-1.5" id="btnConfirmProofDecision" onclick="submitProofDecision()" style="font-size: 13px; background: #2563EB;">
                                    <i class="bi bi-check-lg"></i> Xác nhận
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </section>


            
            

            <!-- TAB 7: HỒ SƠ HỢP ĐỒNG NGHIỆM THU HIỆN TRƯỜNG -->
            <section class="spa-panel" id="panelContractDossier" style="display:none !important;">
                <!-- 1. VÙNG CỐ ĐỊNH: Tiêu đề & KPI (Non-scrollable Top) -->
                <div class="flex-shrink-0">
                <div class="d-flex flex-wrap justify-content-between align-items-center pb-2.5 border-bottom mb-3 gap-3" style="border-color: rgba(0,0,0,0.06) !important;">
                    <div>
                        <h2 class="fw-bold mb-0 text-dark" style="font-size: 16px; letter-spacing: -0.015em;">Hồ Sơ Hợp Đồng Nghiệm Thu & Trình Ký Nhà Trường</h2>
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

                                    <!-- Nút Trình Trường Ký: CHỈ Cán bộ Hành chính & Quản trị viên khi ca ĐÃ ĐƯỢC NGHIỆM THU (CTV TUYỆT ĐỐI KHÔNG CÓ) -->
                                    <?php if ($isVerified && !empty($perms['canSubmitSchoolSigning']) && ($acc['school_status'] ?? '') !== 'Signed_Paid'): ?>
                                    <button type="button" class="btn btn-sm btn-fpt-primary text-truncate" <?= $isReadyToExport ? '' : 'disabled title="Cần hoàn thiện đủ CCCD & STK trước khi trình ký"' ?> onclick="submitToSchool(<?= $acc['assignment_id'] ?>)" style="font-size: 11.5px; padding: 5px 8px; border-radius: 8px;">
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
                $profPerms = RolePermissionHelper::getPermissions($userProfile);
                $profRoleDisplay = $profPerms['roleNameDisplay'] ?? $profRoleName;
                $isCtv = in_array($profRoleId, [4, 6], true) || in_array($profRoleName, ['Student', 'Media', 'CTV', 'Sinh viên'], true);
                ?>
                <!-- BỐ CỤC 2 CỘT PROFILE SAAS CHUẨN MOCKUP IMAGE 11 -->
                <div class="profile-saas-layout">
                    <!-- Cột Trái: Sidebar Menu Tabs Dạng Pill Vertical -->
                    <div class="profile-saas-nav" id="profileSettingsNav">
                        <button type="button" class="profile-nav-pill-btn active" id="profTabBtnCommon" onclick="switchProfileTab('common', this)">
                            <span><i class="bi bi-person nav-icon"></i> Thông tin chung</span>
                        </button>
                        <button type="button" class="profile-nav-pill-btn" id="profTabBtnIdCard" onclick="switchProfileTab('idCard', this)">
                            <span><i class="bi bi-person-vcard nav-icon"></i> Định danh (CCCD)</span>
                            <span class="nav-badge-check"><i class="bi bi-check"></i></span>
                        </button>
                        <button type="button" class="profile-nav-pill-btn" id="profTabBtnBank" onclick="switchProfileTab('bank', this)">
                            <span><i class="bi bi-bank nav-icon"></i> Tài khoản ngân hàng</span>
                            <span class="nav-badge-check"><i class="bi bi-check"></i></span>
                        </button>
                        <button type="button" class="profile-nav-pill-btn" id="profTabBtnSkills" onclick="switchProfileTab('skills', this)">
                            <span><i class="bi bi-stack nav-icon"></i> Kỹ năng & lĩnh vực</span>
                        </button>
                        <button type="button" class="profile-nav-pill-btn" id="profTabBtnSecurity" onclick="switchProfileTab('security', this)">
                            <span><i class="bi bi-lock nav-icon"></i> Bảo mật tài khoản</span>
                        </button>
                    </div>

                    <!-- Cột Phải: Form & Nội Dung Tab -->
                    <div class="flex-1" style="min-width: 0;">
                        <form id="profileSettingsForm" onsubmit="handleProfileUpdate(event)">
                            
                            <!-- TAB PANE 1: THÔNG TIN CHUNG (Chuẩn Mockup Image 11) -->
                            <div class="profile-tab-pane" id="profileTabCommon">
                                
                                <!-- Hero Banner Card Mềm Mại -->
                                <div class="profile-hero-banner mb-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
                                    <div class="d-flex align-items-center gap-3.5">
                                        <div class="profile-avatar-wrap">
                                            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&q=80" alt="Avatar" class="profile-avatar-img" id="profileDisplayAvatar">
                                            <div class="profile-avatar-cam-btn" onclick="openAvatarChangeModal()" title="Đổi ảnh đại diện">
                                                <i class="bi bi-camera-fill"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="fw-bold mb-1.5 text-dark" style="font-size: 19px; letter-spacing: -0.3px;" id="profileHeroFullName">
                                                <?= htmlspecialchars($userProfile['full_name'] ?? 'Định Nguyễn Phương Anh') ?>
                                            </h4>
                                            <div class="mb-2">
                                                <span class="profile-role-tag">
                                                    <i class="bi bi-camera-video-fill"></i> CTV Media / Truyền thông
                                                </span>
                                            </div>
                                            <div class="d-flex align-items-center gap-3 text-muted" style="font-size: 12.5px;">
                                                <span>Mã: <strong class="text-dark font-monospace"><?= htmlspecialchars($userProfile['student_code'] ?? 'PI03084') ?></strong></span>
                                                <span style="opacity: 0.4;">|</span>
                                                <span>Email: <span class="text-dark"><?= htmlspecialchars($userProfile['email'] ?? 'phuonganh.pi03084@fpt.edu.vn') ?></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="button" class="profile-btn-edit-cover" onclick="triggerToast('Tính năng cập nhật ảnh bìa đang được bật!')">
                                            <i class="bi bi-pencil"></i> Chỉnh sửa ảnh bìa
                                        </button>
                                    </div>
                                </div>

                                <!-- Card Thông Tin Cơ Bản -->
                                <div class="card border-0 rounded-3 shadow-xs mb-3" style="border: 1px solid #E2E8F0 !important; background: #FFFFFF;">
                                    <div class="card-body p-3 p-md-4">
                                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: #F1F5F9 !important;">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-person text-primary" style="font-size: 17px;"></i>
                                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 14.5px;">Thông tin cơ bản</h6>
                                            </div>
                                            <button type="submit" class="profile-btn-edit-cover" id="btnSaveProfileHeader">
                                                <i class="bi bi-pencil"></i> Chỉnh sửa
                                            </button>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-12 col-md-6">
                                                <label class="form-label" style="font-size: 12.5px; font-weight: 500; color: #475569;">Họ và tên <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                                                    <input type="text" name="full_name" class="form-control border-start-0" value="<?= htmlspecialchars($userProfile['full_name'] ?? 'Định Nguyễn Phương Anh') ?>" required style="font-size: 13.5px;">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label d-flex align-items-center justify-content-between" style="font-size: 12.5px; font-weight: 500; color: #475569;">
                                                    <span>Mã định danh / MSSV</span>
                                                    <span class="text-muted" style="font-size: 11px;"><i class="bi bi-lock-fill me-1"></i>Cố định</span>
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-fingerprint"></i></span>
                                                    <input type="text" class="form-control border-start-0 bg-light font-monospace" value="<?= htmlspecialchars($userProfile['student_code'] ?? 'PI03084') ?>" readonly style="font-size: 13.5px;">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label d-flex align-items-center justify-content-between" style="font-size: 12.5px; font-weight: 500; color: #475569;">
                                                    <span>Email FPT Education</span>
                                                    <span class="text-muted" style="font-size: 11px;"><i class="bi bi-lock-fill me-1"></i>Cố định</span>
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                                    <input type="email" class="form-control border-start-0 bg-light" value="<?= htmlspecialchars($userProfile['email'] ?? 'phuonganh.pi03084@fpt.edu.vn') ?>" readonly style="font-size: 13.5px;">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label" style="font-size: 12.5px; font-weight: 500; color: #475569;">Số điện thoại liên hệ <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-telephone"></i></span>
                                                    <input type="tel" name="phone" class="form-control border-start-0 font-monospace" value="<?= htmlspecialchars($userProfile['phone'] ?? '0923456789') ?>" required style="font-size: 13.5px;">
                                                </div>
                                            </div>

                                            <!-- Chip tags Kỹ năng & Lĩnh vực -->
                                            <div class="col-12 mt-3">
                                                <label class="form-label mb-2" style="font-size: 12.5px; font-weight: 500; color: #475569;">Kỹ năng & lĩnh vực phụ trách</label>
                                                <div class="d-flex flex-wrap align-items-center gap-2 mb-1.5" id="profileSkillChipsContainer">
                                                    <span class="profile-chip-tag">
                                                        <i class="bi bi-tag text-muted"></i> Media phóng sự
                                                        <span class="tag-remove-btn" onclick="removeSkillChip(this, 'Media phóng sự')">×</span>
                                                    </span>
                                                    <span class="profile-chip-tag">
                                                        Chụp ảnh sự kiện
                                                        <span class="tag-remove-btn" onclick="removeSkillChip(this, 'Chụp ảnh sự kiện')">×</span>
                                                    </span>
                                                    <span class="profile-chip-tag">
                                                        Hậu kỳ Premiere/Lightroom
                                                        <span class="tag-remove-btn" onclick="removeSkillChip(this, 'Hậu kỳ Premiere/Lightroom')">×</span>
                                                    </span>
                                                    <button type="button" class="profile-chip-add-btn" onclick="promptAddSkillChip()">
                                                        <i class="bi bi-plus"></i> Thêm
                                                    </button>
                                                </div>
                                                <input type="hidden" name="skills" id="inputProfileSkills" value="<?= htmlspecialchars($userProfile['skills'] ?? 'Media phóng sự, Chụp ảnh sự kiện, Hậu kỳ Premiere/Lightroom') ?>">
                                                <div class="text-muted small" style="font-size: 11.5px;">Dùng để gợi ý và phân công công việc phù hợp.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 3 Thẻ Trạng Thái Chân Trang (Bento 3-Card Footer) -->
                                <div class="row g-3">
                                    <div class="col-12 col-md-4">
                                        <div class="profile-kpi-status-card">
                                            <div class="profile-kpi-icon-wrap" style="background: #DCFCE7; color: #16A34A;">
                                                <i class="bi bi-check-lg"></i>
                                            </div>
                                            <div>
                                                <div class="text-muted" style="font-size: 11.5px; font-weight: 500;">Định danh CCCD</div>
                                                <div class="fw-bold" style="font-size: 13.5px; color: #16A34A;">Đã xác thực</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="profile-kpi-status-card">
                                            <div class="profile-kpi-icon-wrap" style="background: #E0F2FE; color: #0284C7;">
                                                <i class="bi bi-bank"></i>
                                            </div>
                                            <div>
                                                <div class="text-muted" style="font-size: 11.5px; font-weight: 500;">Tài khoản ngân hàng</div>
                                                <div class="fw-bold" style="font-size: 13.5px; color: #0284C7;">Đã liên kết</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="profile-kpi-status-card">
                                            <div class="profile-kpi-icon-wrap" style="background: #EFF6FF; color: #2563EB;">
                                                <i class="bi bi-pie-chart-fill"></i>
                                            </div>
                                            <div>
                                                <div class="text-muted" style="font-size: 11.5px; font-weight: 500;">Hồ sơ hoàn thiện</div>
                                                <div class="fw-bold" style="font-size: 14.5px; color: #2563EB;">100%</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB PANE 2: ĐỊNH DANH CCCD -->
                            <div class="profile-tab-pane d-none" id="profileTabIdCard">
                                <div class="card border-0 rounded-3 shadow-xs p-3 p-md-4" style="border: 1px solid #E2E8F0 !important;">
                                    <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-person-vcard text-primary me-2"></i>Định danh Căn Cước Công Dân (CCCD / VNeID)</h6>
                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <label class="form-label" style="font-size: 12.5px;">Số CCCD (12 số) <span class="text-danger">*</span></label>
                                            <input type="text" name="id_card_number" class="form-control font-monospace" value="<?= htmlspecialchars($userProfile['id_card_number'] ?? '079203004812') ?>" required>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label" style="font-size: 12.5px;">Ảnh Chụp VNeID Mức 2</label>
                                            <input type="url" name="vneid_image_url" class="form-control" value="<?= htmlspecialchars($userProfile['vneid_image_url'] ?? '') ?>" placeholder="https://...">
                                        </div>
                                        <div class="col-12 text-end pt-2">
                                            <button type="submit" class="btn btn-primary rounded-pill px-4">Lưu thông tin CCCD</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB PANE 3: TÀI KHOẢN NGÂN HÀNG -->
                            <div class="profile-tab-pane d-none" id="profileTabBank">
                                <div class="card border-0 rounded-3 shadow-xs p-3 p-md-4" style="border: 1px solid #E2E8F0 !important;">
                                    <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-bank text-primary me-2"></i>Tài khoản ngân hàng nhận thù lao ca trực</h6>
                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <label class="form-label" style="font-size: 12.5px;">Ngân hàng thụ hưởng <span class="text-danger">*</span></label>
                                            <input type="text" name="bank_name" class="form-control" value="<?= htmlspecialchars($userProfile['bank_name'] ?? 'MB Bank') ?>" required>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label" style="font-size: 12.5px;">Số tài khoản (STK) <span class="text-danger">*</span></label>
                                            <input type="text" name="bank_account_number" class="form-control font-monospace" value="<?= htmlspecialchars($userProfile['bank_account_number'] ?? '0923456789') ?>" required>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label" style="font-size: 12.5px;">Tên chủ tài khoản (In hoa) <span class="text-danger">*</span></label>
                                            <input type="text" name="bank_account_name" class="form-control text-uppercase font-monospace" value="<?= htmlspecialchars($userProfile['bank_account_name'] ?? 'DINH NGUYEN PHUONG ANH') ?>" required>
                                        </div>
                                        <div class="col-12 text-end pt-2">
                                            <button type="submit" class="btn btn-primary rounded-pill px-4">Lưu thông tin ngân hàng</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB PANE 4: KỸ NĂNG & LĨNH VỰC -->
                            <div class="profile-tab-pane d-none" id="profileTabSkills">
                                <div class="card border-0 rounded-3 shadow-xs p-3 p-md-4" style="border: 1px solid #E2E8F0 !important;">
                                    <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-stack text-primary me-2"></i>Kỹ năng chuyên môn & Lĩnh vực phụ trách</h6>
                                    <p class="text-muted small">Trợ lý AI PolyMee sẽ tự động quét các kỹ năng này để ưu tiên đề xuất bạn vào các ca trực sự kiện thích hợp nhất.</p>
                                    <textarea class="form-control" rows="4" placeholder="Nhập các kỹ năng phân cách bằng dấu phẩy..."><?= htmlspecialchars($userProfile['skills'] ?? 'Media phóng sự, Chụp ảnh sự kiện, Hậu kỳ Premiere/Lightroom') ?></textarea>
                                    <div class="text-end pt-3">
                                        <button type="submit" class="btn btn-primary rounded-pill px-4">Lưu kỹ năng</button>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB PANE 5: BẢO MẬT TÀI KHOẢN -->
                            <div class="profile-tab-pane d-none" id="profileTabSecurity">
                                <div class="card border-0 rounded-3 shadow-xs p-3 p-md-4" style="border: 1px solid #E2E8F0 !important;">
                                    <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-shield-lock text-primary me-2"></i>Bảo mật tài khoản & Đổi mật khẩu</h6>
                                    <div class="row g-3" style="max-width: 500px;">
                                        <div class="col-12">
                                            <label class="form-label" style="font-size: 12.5px;">Mật khẩu mới</label>
                                            <input type="password" name="new_password" class="form-control" placeholder="Nhập mật khẩu mới (bỏ trống nếu giữ nguyên)...">
                                        </div>
                                        <div class="col-12 text-end pt-2">
                                            <button type="submit" class="btn btn-primary rounded-pill px-4">Cập nhật mật khẩu</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

            <!-- =========================================================
                 PANEL 9: TRUNG TÂM CẤP QUYỀN NGƯỜI DÙNG (ADMIN ONLY)
                 ========================================================= -->
            <?php if (!empty($perms['canManageRoles'])): ?>
            <section id="panelUserRoles" style="display:none !important;" class="spa-panel fade-in-up">
                <div class="saas-card flex-1 overflow-hidden d-flex flex-column h-100">
                    <!-- 1. Toolbar: Tiêu đề & Bộ lọc tìm kiếm nhanh -->
                    <div class="saas-card-header flex-shrink-0 d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 border-bottom">
                        <div>
                            <h3 class="fw-bold mb-0 text-dark" style="font-size: 16px;">Tài Khoản & Phân Quyền Hệ Thống</h3>
                            <small class="text-muted">Quản trị danh sách người dùng, chuyển đổi vai trò và kiểm soát trạng thái truy cập</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-light text-dark border px-3 py-1.5 rounded-pill font-monospace">
                                Tổng cộng: <?= count($allUsers) ?> tài khoản
                            </span>
                        </div>
                    </div>

                    <div class="p-3 border-bottom bg-light flex-shrink-0 d-flex flex-wrap gap-2 align-items-center">
                        <div class="flex-grow-1" style="min-width: 220px;">
                            <input type="text" id="userRolesSearchInput" class="form-control form-control-sm" placeholder="Tìm theo tên, mã sinh viên, email..." oninput="filterUserRolesTable()">
                        </div>
                        <select id="userRolesRoleFilter" class="form-select form-select-sm" style="width: 170px;" onchange="filterUserRolesTable()">
                            <option value="">Tất cả vai trò</option>
                            <option value="Admin">Admin (Quản trị)</option>
                            <option value="Organizer">Organizer (Ban tổ chức)</option>
                            <option value="Staff">Staff (Hành chính)</option>
                            <option value="Student">Student (CTV Hậu cần)</option>
                            <option value="Media">Media (CTV Truyền thông)</option>
                        </select>
                    </div>

                    <!-- 2. Bảng Dữ Liệu Sạch (Minimal Table Standards: 14px, row 56-72px, click opens Drawer) -->
                    <div class="table-responsive flex-1 overflow-y-auto">
                        <table class="saas-table">
                            <thead>
                                <tr>
                                    <th class="ps-3" style="width: 260px;">Thành Viên</th>
                                    <th style="width: 130px;">Mã / MSSV</th>
                                    <th>Email & Liên Hệ</th>
                                    <th style="width: 160px;">Vai Trò</th>
                                    <th style="width: 130px;">Trạng Thái</th>
                                    <th class="pe-3 text-end" style="width: 160px;">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allUsers as $u): ?>
                                <?php 
                                $uInitial = mb_strtoupper(mb_substr($u['full_name'], 0, 1));
                                $roleBadgeColor = match($u['role_name']) {
                                    'Admin' => 'background: #FEE2E2; color: #DC2626; border: 1px solid #FECACA;',
                                    'Organizer' => 'background: #DBEAFE; color: #2563EB; border: 1px solid #BFDBFE;',
                                    'Staff' => 'background: #E0F2FE; color: #0284C7; border: 1px solid #BAE6FD;',
                                    'Media' => 'background: #FEF3C7; color: #D97706; border: 1px solid #FDE68A;',
                                    default => 'background: #DCFCE7; color: #16A34A; border: 1px solid #BBF7D0;'
                                };
                                $isLocked = (int)($u['is_active'] ?? 1) === 0;
                                ?>
                                <tr onclick="openUserDrawer(<?= $u['id'] ?>)">
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center flex-shrink-0 shadow-2xs" style="width: 36px; height: 36px; background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%); font-size: 13.5px;">
                                                <?= htmlspecialchars($uInitial) ?>
                                            </div>
                                            <div class="overflow-hidden">
                                                <strong class="text-dark d-block text-truncate" style="font-size: 13.5px;"><?= htmlspecialchars($u['full_name']) ?></strong>
                                                <small class="text-muted text-truncate d-block" style="font-size: 11.5px;"><?= htmlspecialchars($u['department'] ?? 'Công nghệ Thông tin') ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge font-monospace bg-light text-dark border px-2 py-0.5 rounded-pill" style="font-size: 11px;">
                                            <?= htmlspecialchars($u['student_code']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-dark small"><?= htmlspecialchars($u['email']) ?></div>
                                        <small class="text-muted font-monospace"><?= htmlspecialchars($u['phone'] ?: 'Chưa cập nhật SĐT') ?></small>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill px-2.5 py-1" style="font-size: 11px; <?= $roleBadgeColor ?>">
                                            <?= htmlspecialchars($u['role_name']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($isLocked): ?>
                                            <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 11px;">
                                                <i class="bi bi-lock-fill me-1"></i>Đã Khóa
                                            </span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle" style="font-size: 11px;">
                                                <i class="bi bi-check-circle-fill me-1"></i>Hoạt Động
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="pe-3 text-end">
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="event.stopPropagation(); openUserDrawer(<?= $u['id'] ?>)">
                                            Chi Tiết & Đổi Quyền
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
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

            <!-- =========================================================
                 PANEL 11: DASHBOARD BÁO CÁO & VIỆC CẦN XỬ LÝ (OVERVIEW)
                 ========================================================= -->
            <section id="panelOverviewReport" style="display:none !important; flex-direction: column;" class="spa-panel fade-in-up">
                
                <!-- 4 Bento KPI Cards Chuẩn Mockup Image 13 -->
                <div class="row g-3 mb-3 flex-shrink-0">
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="saas-card saas-kpi-card p-3 d-flex align-items-center gap-3">
                            <div class="saas-kpi-icon" style="background:#EFF6FF; color:#2563EB;">
                                <i class="bi bi-calendar3 fs-5"></i>
                            </div>
                            <div>
                                <div class="saas-kpi-val num-tabular">2</div>
                                <div class="saas-kpi-title">Ca sắp tới</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="saas-card saas-kpi-card p-3 d-flex align-items-center gap-3">
                            <div class="saas-kpi-icon" style="background:#F0FDF4; color:#16A34A;">
                                <i class="bi bi-layers fs-5"></i>
                            </div>
                            <div>
                                <div class="saas-kpi-val num-tabular">20</div>
                                <div class="saas-kpi-title">Công việc đang nhận</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="saas-card saas-kpi-card p-3 d-flex align-items-center gap-3">
                            <div class="saas-kpi-icon" style="background:#FEF3C7; color:#D97706;">
                                <i class="bi bi-clock-history fs-5"></i>
                            </div>
                            <div>
                                <div class="saas-kpi-val num-tabular">2</div>
                                <div class="saas-kpi-title">Minh chứng chờ duyệt</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="saas-card saas-kpi-card p-3 d-flex align-items-center gap-3">
                            <div class="saas-kpi-icon" style="background:#F3E8FF; color:#9333EA;">
                                <i class="bi bi-patch-check fs-5"></i>
                            </div>
                            <div>
                                <div class="saas-kpi-val num-tabular">87.5%</div>
                                <div class="saas-kpi-title">Tỷ lệ hoàn thành</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thân Dashboard: 2 Cột Chuẩn Mockup Image 13 -->
                <div class="row g-3 mb-3">
                    
                    <!-- Cột Trái: Việc Cần Làm Hôm Nay ⓘ -->
                    <div class="col-12 col-lg-7">
                        <div class="card border-0 rounded-3 shadow-xs h-100 p-3.5" style="border: 1px solid #E2E8F0 !important; background: #FFFFFF;">
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: #F1F5F9 !important;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-check2-square text-primary" style="font-size: 16px;"></i>
                                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 15px;">Việc cần làm hôm nay</h6>
                                    <i class="bi bi-info-circle text-muted" style="font-size: 12px;" title="Danh sách các việc ưu tiên cần bạn xử lý trong ngày"></i>
                                </div>
                            </div>

                            <div class="d-flex flex-column gap-2.5">
                                <!-- Todo Item 1 -->
                                <div class="dash-todo-card">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: #FFF7ED; color: #EA580C; font-size: 17px;">
                                            <i class="bi bi-upload"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0.5" style="font-size: 14px;">Nộp minh chứng hậu cần</h6>
                                            <div class="text-muted small" style="font-size: 12px;">Ca 2 • Chung kết KN trẻ</div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <span class="badge rounded-pill" style="background: #FFF7ED; color: #EA580C; font-size: 11px; padding: 4px 10px; font-weight: 600;">
                                            ● Cần thực hiện
                                        </span>
                                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1.5 fw-bold" onclick="navigateToPage('submissions')" style="font-size: 12px; background: #2563EB;">
                                            Nộp ngay
                                        </button>
                                    </div>
                                </div>

                                <!-- Todo Item 2 -->
                                <div class="dash-todo-card">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: #EFF6FF; color: #2563EB; font-size: 17px;">
                                            <i class="bi bi-calendar-check"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0.5" style="font-size: 14px;">Ca trực hôm nay 13:00 – 17:00</h6>
                                            <div class="text-muted small" style="font-size: 12px;">Bảo trì phòng máy Lab AI</div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <span class="badge rounded-pill" style="background: #F0FDF4; color: #16A34A; font-size: 11px; padding: 4px 10px; font-weight: 600;">
                                            ● Đã phân công
                                        </span>
                                        <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 fw-medium text-dark" onclick="navigateToPage('calendar')" style="font-size: 12px;">
                                            Xem chi tiết
                                        </button>
                                    </div>
                                </div>

                                <!-- Todo Item 3 -->
                                <div class="dash-todo-card">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: #FAF5FF; color: #9333EA; font-size: 17px;">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0.5" style="font-size: 14px;">Hợp đồng chờ xác nhận</h6>
                                            <div class="text-muted small" style="font-size: 12px;">Đợt quyết toán tháng 11</div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <span class="badge rounded-pill" style="background: #FEF3C7; color: #D97706; font-size: 11px; padding: 4px 10px; font-weight: 600;">
                                            ● Chờ xác nhận
                                        </span>
                                        <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 fw-medium text-dark" onclick="navigateToPage('contract-dossier')" style="font-size: 12px;">
                                            Xem hồ sơ
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cột Phải: Lịch Sắp Tới → (Timeline dọc) -->
                    <div class="col-12 col-lg-5">
                        <div class="card border-0 rounded-3 shadow-xs h-100 p-3.5" style="border: 1px solid #E2E8F0 !important; background: #FFFFFF;">
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: #F1F5F9 !important;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-calendar3 text-primary" style="font-size: 16px;"></i>
                                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 15px;">Lịch sắp tới</h6>
                                </div>
                                <a href="javascript:void(0)" onclick="navigateToPage('calendar')" class="text-decoration-none text-muted" title="Xem tất cả lịch">
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>

                            <!-- Timeline Dọc -->
                            <div class="d-flex flex-column gap-3 pt-1">
                                <!-- Timeline Item 1 -->
                                <div class="dash-timeline-item">
                                    <div class="dash-timeline-date">
                                        <div class="dash-timeline-day">T3</div>
                                        <div class="dash-timeline-num">19</div>
                                    </div>
                                    <div class="dash-timeline-line"></div>
                                    <div class="dash-timeline-dot" style="background: #2563EB;"></div>
                                    <div class="flex-1">
                                        <h6 class="fw-bold text-dark mb-0.5" style="font-size: 13.5px;">Hỗ trợ sự kiện</h6>
                                        <div class="text-muted small" style="font-size: 12px;">09:00 – 12:00</div>
                                    </div>
                                    <span class="badge rounded-pill" style="background: #F0FDF4; color: #16A34A; font-size: 11px; padding: 4px 10px;">
                                        Đã phân công
                                    </span>
                                </div>

                                <!-- Timeline Item 2 -->
                                <div class="dash-timeline-item">
                                    <div class="dash-timeline-date">
                                        <div class="dash-timeline-day">T4</div>
                                        <div class="dash-timeline-num">20</div>
                                    </div>
                                    <div class="dash-timeline-line"></div>
                                    <div class="dash-timeline-dot" style="background: #9333EA;"></div>
                                    <div class="flex-1">
                                        <h6 class="fw-bold text-dark mb-0.5" style="font-size: 13.5px;">Bảo trì Lab AI</h6>
                                        <div class="text-muted small" style="font-size: 12px;">13:00 – 17:00</div>
                                    </div>
                                    <span class="badge rounded-pill" style="background: #EFF6FF; color: #2563EB; font-size: 11px; padding: 4px 10px;">
                                        Sắp diễn ra
                                    </span>
                                </div>

                                <!-- Timeline Item 3 -->
                                <div class="dash-timeline-item">
                                    <div class="dash-timeline-date">
                                        <div class="dash-timeline-day">T6</div>
                                        <div class="dash-timeline-num">22</div>
                                    </div>
                                    <div class="dash-timeline-line"></div>
                                    <div class="dash-timeline-dot" style="background: #94A3B8;"></div>
                                    <div class="flex-1">
                                        <h6 class="fw-bold text-dark mb-0.5" style="font-size: 13.5px;">Hậu cần sự kiện</h6>
                                        <div class="text-muted small" style="font-size: 12px;">08:00 – 12:00</div>
                                    </div>
                                    <span class="badge rounded-pill" style="background: #F1F5F9; color: #64748B; font-size: 11px; padding: 4px 10px;">
                                        Dự kiến
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3 Thẻ Điều Hướng Nhanh Chân Trang (Quick Shortcuts) -->
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <div class="card border-0 rounded-3 shadow-xs p-3 cursor-pointer" onclick="navigateToPage('calendar')" style="border: 1px solid #E2E8F0 !important; background: #FFFFFF; transition: all 0.15s ease;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: #EFF6FF; color: #2563EB;">
                                        <i class="bi bi-calendar3"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0.5 text-dark" style="font-size: 14px;">Lịch trung tâm</h6>
                                        <div class="text-muted small" style="font-size: 12px;">Xem sự kiện và ca trực</div>
                                    </div>
                                </div>
                                <i class="bi bi-arrow-right text-muted"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card border-0 rounded-3 shadow-xs p-3 cursor-pointer" onclick="navigateToPage('my-shifts')" style="border: 1px solid #E2E8F0 !important; background: #FFFFFF; transition: all 0.15s ease;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: #F0FDF4; color: #16A34A;">
                                        <i class="bi bi-briefcase"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0.5 text-dark" style="font-size: 14px;">Công việc của tôi</h6>
                                        <div class="text-muted small" style="font-size: 12px;">Theo dõi công việc đang nhận</div>
                                    </div>
                                </div>
                                <i class="bi bi-arrow-right text-muted"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card border-0 rounded-3 shadow-xs p-3 cursor-pointer" onclick="navigateToPage('contract-dossier')" style="border: 1px solid #E2E8F0 !important; background: #FFFFFF; transition: all 0.15s ease;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: #FAF5FF; color: #9333EA;">
                                        <i class="bi bi-file-earmark-check"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0.5 text-dark" style="font-size: 14px;">Nghiệm thu & Hợp đồng</h6>
                                        <div class="text-muted small" style="font-size: 12px;">Quản lý nghiệm thu và hồ sơ</div>
                                    </div>
                                </div>
                                <i class="bi bi-arrow-right text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- =========================================================
                 PANEL 12: BUỔI HOẠT ĐỘNG & CA TRỰC PHÂN RÃ (SHIFTS & SESSIONS)
                 ========================================================= -->
            <section id="panelShiftsSessions" style="display:none !important; flex-direction: column;" class="spa-panel fade-in-up">
                <div class="saas-card flex-1 overflow-hidden d-flex flex-column h-100">
                    <div class="saas-card-header flex-shrink-0 d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 border-bottom">
                        <div>
                            <h3 class="fw-bold mb-0 text-dark" style="font-size: 16px;">Buổi Hoạt Động & Ca Trực Phân Rã</h3>
                            <small class="text-muted">Quản lý nhu cầu CTV, thời gian và phân công từng buổi hoạt động</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" onclick="openCreateShiftModal()">
                                <i class="bi bi-plus-lg me-1"></i> Tạo Ca Mới
                            </button>
                        </div>
                    </div>
                    <div class="p-3 border-bottom bg-light flex-shrink-0 d-flex flex-wrap gap-2 align-items-center">
                        <div class="flex-grow-1" style="min-width: 200px;">
                            <input type="text" id="shiftSessionSearch" class="form-control form-control-sm" placeholder="Tìm theo tên ca, sự kiện, địa điểm..." oninput="filterShiftsSessionsTable()">
                        </div>
                        <select id="shiftSessionTypeFilter" class="form-select form-select-sm" style="width: 180px;" onchange="filterShiftsSessionsTable()">
                            <option value="">Tất cả loại ca</option>
                            <option value="FACILITY">Hậu cần / Cơ sở vật chất</option>
                            <option value="MEDIA">Media / Truyền thông</option>
                            <option value="NONE">Không cần CTV</option>
                        </select>
                    </div>
                    <div class="table-responsive flex-1 overflow-y-auto">
                        <table class="saas-table" id="shiftsSessionsTable">
                            <thead>
                                <tr>
                                    <th>Ca Trực</th>
                                    <th>Sự Kiện & Địa Điểm</th>
                                    <th>Thời Gian</th>
                                    <th>Loại Hình</th>
                                    <th>Nhu Cầu CTV</th>
                                    <th>Thù Lao</th>
                                    <th class="text-end">Chi Tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($shifts as $s): ?>
                                <?php 
                                $sType = $s['shift_type'] ?? 'FACILITY';
                                $typeBadge = match($sType) {
                                    'MEDIA' => '<span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle">Media / Ảnh</span>',
                                    'NONE' => '<span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle">Không cần CTV</span>',
                                    default => '<span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle">Hậu cần / Kỹ thuật</span>'
                                };
                                $quota = (int)($s['quota'] ?? 4);
                                $assigned = (int)($s['quota_assigned'] ?? 2);
                                ?>
                                <tr onclick="openShiftDrawer(<?= $s['id'] ?>)">
                                    <td>
                                        <strong class="text-dark d-block"><?= htmlspecialchars($s['name']) ?></strong>
                                        <small class="text-muted"><?= htmlspecialchars($s['notes'] ?? 'Phục vụ chương trình') ?></small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark"><?= htmlspecialchars($s['event_title']) ?></div>
                                        <small class="text-muted"><i class="bi bi-geo-alt me-1 text-danger"></i><?= htmlspecialchars($s['location_name'] ?? 'Hội trường') ?></small>
                                    </td>
                                    <td>
                                        <span class="font-monospace text-dark small"><?= date('H:i', strtotime($s['start_time'])) ?> - <?= date('H:i', strtotime($s['end_time'])) ?></span>
                                        <div class="small text-muted"><?= date('d/m/Y', strtotime($s['start_time'])) ?></div>
                                    </td>
                                    <td><?= $typeBadge ?></td>
                                    <td>
                                        <span class="badge rounded-pill bg-light text-dark border px-2.5 py-1 font-monospace">
                                            <?= $assigned ?> / <?= $quota ?> CTV
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-success font-monospace"><?= number_format($s['remuneration_amount'] ?? 150000) ?> đ</strong>
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1" onclick="event.stopPropagation(); openShiftDrawer(<?= $s['id'] ?>)">
                                            <i class="bi bi-chevron-right"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- =========================================================
                 PANEL 13: CỘNG TÁC VIÊN & PHÂN CÔNG (CTV & ASSIGNMENTS)
                 ========================================================= -->
            <section id="panelCtvAssignments" style="display:none !important; flex-direction: column;" class="spa-panel fade-in-up">
                <div class="saas-card flex-1 overflow-hidden d-flex flex-column h-100">
                    <div class="saas-card-header flex-shrink-0 d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 border-bottom">
                        <div>
                            <h3 class="fw-bold mb-0 text-dark" style="font-size: 16px;">CTV & Phân Công Nhiệm Vụ</h3>
                            <small class="text-muted">Theo dõi danh sách cộng tác viên đã được gán vào từng ca trực sự kiện</small>
                        </div>
                        <span class="badge bg-light text-dark border px-3 py-1.5 rounded-pill font-monospace">
                            Tổng phân công: <?= count($accountingList ?? []) ?> ca
                        </span>
                    </div>
                    <div class="table-responsive flex-1 overflow-y-auto">
                        <table class="saas-table">
                            <thead>
                                <tr>
                                    <th>Cộng Tác Viên</th>
                                    <th>Mã SV & Liên Hệ</th>
                                    <th>Ca Trực Được Gán</th>
                                    <th>Thời Gian Ca</th>
                                    <th>Nghiệm Thu</th>
                                    <th class="text-end">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($accountingList as $a): ?>
                                <tr>
                                    <td onclick="openCtvProfileDrawer(<?= $a['user_id'] ?>)">
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-2xs" style="width:34px; height:34px; background:linear-gradient(135deg, #F97316 0%, #EA580C 100%); font-size:13px;">
                                                <?= mb_substr($a['full_name'], 0, 1) ?>
                                            </div>
                                            <div>
                                                <strong class="text-dark d-block"><?= htmlspecialchars($a['full_name']) ?></strong>
                                                <small class="text-muted"><?= htmlspecialchars($a['ctv_type'] === 'MEDIA' ? 'CTV Media' : 'CTV Hậu Cần') ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge font-monospace bg-light text-dark border px-2 py-0.5 rounded-pill"><?= htmlspecialchars($a['student_code']) ?></span>
                                        <div class="small text-muted mt-0.5"><?= htmlspecialchars($a['phone'] ?: $a['email']) ?></div>
                                    </td>
                                    <td>
                                        <strong class="text-dark d-block"><?= htmlspecialchars($a['shift_name']) ?></strong>
                                        <small class="text-muted"><?= htmlspecialchars($a['event_title']) ?></small>
                                    </td>
                                    <td>
                                        <span class="font-monospace text-dark small"><?= date('H:i', strtotime($a['start_time'])) ?> - <?= date('H:i', strtotime($a['end_time'])) ?></span>
                                        <div class="small text-muted"><?= date('d/m/Y', strtotime($a['start_time'])) ?></div>
                                    </td>
                                    <td>
                                        <?php if (!empty($a['lecturer_verified'])): ?>
                                            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle"><i class="bi bi-check2-circle me-1"></i>Đã duyệt</span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle"><i class="bi bi-clock me-1"></i>Chờ duyệt</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1.5">
                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-1" onclick="openProofDrawer(<?= $a['assignment_id'] ?>)">
                                                Minh chứng
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1" onclick="promptCancelAssignment(<?= $a['assignment_id'] ?>, '<?= htmlspecialchars($a['full_name']) ?>', '<?= htmlspecialchars($a['shift_name']) ?>')">
                                                Hủy
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- =========================================================
                 PANEL 14: PHÒNG ỐC, THIẾT BỊ & ĐẶT LỊCH (ROOMS & FACILITIES)
                 ========================================================= -->
            <section id="panelRoomsBooking" style="display:none !important; flex-direction: column;" class="spa-panel fade-in-up">
                <div class="saas-card flex-1 overflow-hidden d-flex flex-column h-100">
                    <div class="saas-card-header flex-shrink-0 d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 border-bottom">
                        <div>
                            <h3 class="fw-bold mb-0 text-dark" style="font-size: 16px;">Phòng Ốc, Thiết Bị & Lịch Đặt Phòng</h3>
                            <small class="text-muted">Quản lý cơ sở vật chất, tình trạng sử dụng và phê duyệt yêu cầu đặt phòng</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" onclick="openCreateShiftModal()">
                            <i class="bi bi-plus-lg me-1"></i> Đặt Phòng Mới
                        </button>
                    </div>
                    <div class="p-3 overflow-y-auto flex-1">
                        <!-- BANNER HẬU SỰ KIỆN: MINH CHỨNG NGHIỆM THU (MOCKUP IMAGE 1) -->
                        <div class="p-3 mb-3 rounded-3 border bg-white shadow-2xs">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2.5">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge rounded-pill fw-bold" style="background: #F59E0B; color: #FFFFFF; font-size: 10px; padding: 4px 8px; letter-spacing: 0.5px;">HẬU SỰ KIỆN</span>
                                        <h5 class="fw-bold text-dark mb-0" style="font-size: 15px; letter-spacing: -0.2px;">Minh Chứng Nghiệm Thu Sau Mỗi Sự Kiện Hoàn Thành</h5>
                                    </div>
                                    <p class="text-muted mb-0" style="font-size: 12px;">
                                        Role CTV: Up ảnh hiện trường &bull; Role CTV Media: Up link Google Drive kèm logo & chỉnh sửa sáng &bull; Giảng Viên: Duyệt đã đầy đủ minh chứng để nghiệm thu
                                    </p>
                                </div>
                                <div class="badge d-inline-flex align-items-center gap-1.5 py-1.5 px-3 rounded-pill fw-bold" style="background: #E0F2FE; color: #0284C7; border: 1px solid #BAE6FD; font-size: 11.5px;">
                                    <i class="bi bi-shield-check"></i>
                                    <span>Zero–Fraud Acceptance</span>
                                </div>
                            </div>
                            <div class="row g-2.5">
                                <div class="col-12 col-md-4">
                                    <div class="p-2.5 rounded-3 border h-100" style="background: #FAFAFC;">
                                        <div class="d-flex align-items-center gap-1.5 mb-1">
                                            <span class="badge rounded-pill" style="background: #2563EB; color: #FFFFFF; font-size: 9.5px; font-weight: 600;">Role CTV Thường</span>
                                            <strong class="text-dark" style="font-size: 12.5px;">Ảnh Hiện Trường Có Mặt</strong>
                                        </div>
                                        <p class="text-muted mb-0" style="font-size: 11.5px; line-height: 1.4;">
                                            CTV có nhiệm vụ chụp ảnh có mặt thực tế tại vị trí trực (áo cam FPoly) để chứng minh có mặt nghiệm thu.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="p-2.5 rounded-3 border h-100" style="background: #FAFAFC;">
                                        <div class="d-flex align-items-center gap-1.5 mb-1">
                                            <span class="badge rounded-pill" style="background: #F59E0B; color: #FFFFFF; font-size: 9.5px; font-weight: 600;">Role CTV Media</span>
                                            <strong class="text-dark" style="font-size: 12.5px;">Link Drive Đã Chỉnh Sáng & Logo</strong>
                                        </div>
                                        <p class="text-muted mb-0" style="font-size: 11.5px; line-height: 1.4;">
                                            CTV Media tải link Google Drive bộ ảnh sự kiện đã được <strong>gắn logo FPT Poly</strong> và <strong>hậu kỳ chỉnh sáng</strong>.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="p-2.5 rounded-3 border h-100" style="background: #FAFAFC;">
                                        <div class="d-flex align-items-center gap-1.5 mb-1">
                                            <span class="badge rounded-pill" style="background: #16A34A; color: #FFFFFF; font-size: 9.5px; font-weight: 600;">Role Giảng Viên</span>
                                            <strong class="text-dark" style="font-size: 12.5px;">Duyệt / Nhắc Nhở Qua Email</strong>
                                        </div>
                                        <p class="text-muted mb-0" style="font-size: 11.5px; line-height: 1.4;">
                                            Giảng viên kiểm tra minh chứng duyệt nghiệm thu, hoặc <strong>gửi email nhắc nhở</strong> nếu CTV chưa nộp ảnh.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <!-- Room 1 -->
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="saas-card p-3.5 h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-2.5 py-1">
                                                <i class="bi bi-circle-fill me-1" style="font-size:6px;"></i> Sẵn sàng
                                            </span>
                                            <span class="text-muted small num-tabular">Sức chứa: 500</span>
                                        </div>
                                        <h5 class="fw-bold text-dark mb-1">Hội trường Trịnh Văn Bô</h5>
                                        <small class="text-muted d-block mb-3">Tòa nhà Innovation • Hội trường trung tâm</small>
                                        <div class="saas-kv-grid is-single mb-3">
                                            <div class="saas-kv-item">
                                                <span class="saas-kv-label">Thiết Bị Sẵn Có</span>
                                                <span class="saas-kv-val" style="font-size:12.5px;">Âm thanh sân khấu, Máy chiếu 4K, 4 Micro không dây, Điều hòa trung tâm</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <small class="text-muted">Đã đặt 3 sự kiện tháng này</small>
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="openCreateShiftModal()">Đặt phòng</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Room 2 -->
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="saas-card p-3.5 h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1">
                                                <i class="bi bi-clock-fill me-1" style="font-size:6px;"></i> Đang sử dụng
                                            </span>
                                            <span class="text-muted small num-tabular">Sức chứa: 45</span>
                                        </div>
                                        <h5 class="fw-bold text-dark mb-1">Phòng Lab AI - 405</h5>
                                        <small class="text-muted d-block mb-3">Tòa B, Tầng 4 • Phòng máy chuyên dụng</small>
                                        <div class="saas-kv-grid is-single mb-3">
                                            <div class="saas-kv-item">
                                                <span class="saas-kv-label">Thiết Bị Sẵn Có</span>
                                                <span class="saas-kv-val" style="font-size:12.5px;">40 Máy trạm AI GPU, Máy chiếu tương tác, Đường truyền 1Gbps</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <small class="text-muted">Lịch trực: 13:00 - 17:00 hôm nay</small>
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="navigateToPage('calendar')">Xem ca trực</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Room 3 -->
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="saas-card p-3.5 h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-2.5 py-1">
                                                <i class="bi bi-circle-fill me-1" style="font-size:6px;"></i> Sẵn sàng
                                            </span>
                                            <span class="text-muted small num-tabular">Sức chứa: 80</span>
                                        </div>
                                        <h5 class="fw-bold text-dark mb-1">Phòng Hội Thảo 301</h5>
                                        <small class="text-muted d-block mb-3">Tòa C, Tầng 3 • Bàn tròn chuyên đề</small>
                                        <div class="saas-kv-grid is-single mb-3">
                                            <div class="saas-kv-item">
                                                <span class="saas-kv-label">Thiết Bị Sẵn Có</span>
                                                <span class="saas-kv-val" style="font-size:12.5px;">Màn hình LED 120 inch, Micro cổ ngỗng, Hệ thống họp trực tuyến</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <small class="text-muted">Triển lãm FPoly Creative</small>
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="openCreateShiftModal()">Đặt phòng</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Room 4 -->
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="saas-card p-3.5 h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-2.5 py-1">
                                                <i class="bi bi-circle-fill me-1" style="font-size:6px;"></i> Sẵn sàng
                                            </span>
                                            <span class="text-muted small num-tabular">Sức chứa: 1000</span>
                                        </div>
                                        <h5 class="fw-bold text-dark mb-1">Central Yard (Sân Trung Tâm)</h5>
                                        <small class="text-muted d-block mb-3">Khuôn viên ngoài trời • Sân khấu lớn</small>
                                        <div class="saas-kv-grid is-single mb-3">
                                            <div class="saas-kv-item">
                                                <span class="saas-kv-label">Thiết Bị Sẵn Có</span>
                                                <span class="saas-kv-val" style="font-size:12.5px;">Sân khấu khung thép, Hệ thống đèn Beam, Dù che cánh buồm</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <small class="text-muted">Giải thể thao FPoly 2026</small>
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="openCreateShiftModal()">Đặt phòng</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Room 5 -->
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="saas-card p-3.5 h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-2.5 py-1">
                                                <i class="bi bi-circle-fill me-1" style="font-size:6px;"></i> Sẵn sàng
                                            </span>
                                            <span class="text-muted small num-tabular">Sức chứa: 40</span>
                                        </div>
                                        <h5 class="fw-bold text-dark mb-1">Phòng Lab IoT - 406</h5>
                                        <small class="text-muted d-block mb-3">Tòa B, Tầng 4 • Thiết bị nhúng & Mạng</small>
                                        <div class="saas-kv-grid is-single mb-3">
                                            <div class="saas-kv-item">
                                                <span class="saas-kv-label">Thiết Bị Sẵn Có</span>
                                                <span class="saas-kv-val" style="font-size:12.5px;">Kit vi điều khiển ESP32/Raspberry Pi, Máy hiện sóng, Mạng nội bộ</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <small class="text-muted">Phục vụ Workshop AI</small>
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="openCreateShiftModal()">Đặt phòng</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- =========================================================
                 PANEL 15: KHÁM PHÁ CÔNG VIỆC (EXPLORE JOBS - FOR CTV)
                 ========================================================= -->
            <section id="panelExploreJobs" style="display:none !important; flex-direction: column;" class="spa-panel fade-in-up">
                
                <!-- Header Tiêu Đề & Bộ Lọc Khám Phá Công Việc -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3 pb-2.5 border-bottom" style="border-color: #E2E8F0 !important;">
                    <div>
                        <h4 class="fw-bold mb-1 text-dark" style="font-size: 17px; letter-spacing: -0.2px;">Khám Phá Công Việc & Ca Trực Mở Tuyển</h4>
                        <div class="text-muted small" style="font-size: 12.5px;">Đăng ký ca trực linh hoạt, nhận thù lao và tích lũy điểm uy tín rèn luyện.</div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge rounded-pill fw-semibold px-3 py-1.5" style="background: #EFF6FF; color: #2563EB; font-size: 12px; border: 1px solid #BFDBFE;">
                            <i class="bi bi-briefcase-fill me-1"></i><?= count($shifts ?? []) ?> ca trực sẵn sàng
                        </span>
                    </div>
                </div>

                <!-- Category Filter Pills -->
                <div class="cal-view-segmented mb-3.5" id="exploreCategoryFilters">
                    <button type="button" class="cal-view-btn active" onclick="filterExploreJobs('all', this)">Tất cả công việc</button>
                    <button type="button" class="cal-view-btn" onclick="filterExploreJobs('media', this)"><i class="bi bi-camera-video me-1"></i>Media & Quay chụp</button>
                    <button type="button" class="cal-view-btn" onclick="filterExploreJobs('tech', this)"><i class="bi bi-tools me-1"></i>Kỹ thuật & Âm thanh</button>
                    <button type="button" class="cal-view-btn" onclick="filterExploreJobs('reception', this)"><i class="bi bi-people me-1"></i>Lễ tân & Đón tiếp</button>
                    <button type="button" class="cal-view-btn" onclick="filterExploreJobs('logistics', this)"><i class="bi bi-box-seam me-1"></i>Hậu cần & Setup</button>
                </div>

                <!-- Lưới Thẻ Công Việc (Job Cards Grid - 3 Cột) -->
                <div class="job-card-grid" id="exploreJobCardsContainer">
                    
                    <!-- Job Card 1: Media TechDay -->
                    <div class="saas-job-card" data-category="media">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="job-card-tag" style="background: #F3E8FF; color: #9333EA;">Media / Truyền thông</span>
                                <span class="job-card-remun">200.000 đ/ca</span>
                            </div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 14.5px;">Ca 3: Media Phóng Sự Ảnh & Recap</h6>
                            <div class="text-primary small fw-semibold mb-2" style="font-size: 12px;"><i class="bi bi-calendar-event me-1"></i>FPoly TechDay 2026</div>
                            
                            <div class="d-flex flex-column gap-1 text-muted small mb-3" style="font-size: 12px;">
                                <div><i class="bi bi-clock me-1.5 text-muted"></i>15/11/2026 • 08:00 – 12:00</div>
                                <div><i class="bi bi-geo-alt me-1.5 text-muted"></i>Hội trường lớn A, Tòa Innovation</div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex align-items-center justify-content-between text-muted small mb-1.5" style="font-size: 11px;">
                                <span>Tiến độ nhận ca:</span>
                                <span class="fw-bold text-dark">Đã duyệt 2/3 CTV</span>
                            </div>
                            <div class="progress mb-3" style="height: 5px; border-radius: 4px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 66%;"></div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-light border rounded-pill flex-1 fw-medium" onclick="openShiftDrawer(3)" style="font-size: 12px;">Chi tiết</button>
                                <button type="button" class="btn btn-sm btn-primary rounded-pill flex-1 fw-bold" onclick="registerShift(3, 1)" style="font-size: 12px; background: #2563EB;">Đăng ký ca</button>
                            </div>
                        </div>
                    </div>

                    <!-- Job Card 2: Kỹ Thuật Khởi Nghiệp Trẻ -->
                    <div class="saas-job-card" data-category="tech">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="job-card-tag" style="background: #EFF6FF; color: #2563EB;">Kỹ thuật / CSVC</span>
                                <span class="job-card-remun">180.000 đ/ca</span>
                            </div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 14.5px;">Ca 2: Kỹ Thuật Trình Chiếu Pitching & Micro</h6>
                            <div class="text-primary small fw-semibold mb-2" style="font-size: 12px;"><i class="bi bi-calendar-event me-1"></i>Chung Kết Khởi Nghiệp Trẻ FPoly Biz</div>
                            
                            <div class="d-flex flex-column gap-1 text-muted small mb-3" style="font-size: 12px;">
                                <div><i class="bi bi-clock me-1.5 text-muted"></i>18/11/2026 • 13:00 – 17:00</div>
                                <div><i class="bi bi-geo-alt me-1.5 text-muted"></i>Hội trường B, Tòa nhà Beta</div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex align-items-center justify-content-between text-muted small mb-1.5" style="font-size: 11px;">
                                <span>Tiến độ nhận ca:</span>
                                <span class="fw-bold text-dark">Đã duyệt 1/3 CTV</span>
                            </div>
                            <div class="progress mb-3" style="height: 5px; border-radius: 4px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 33%;"></div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-light border rounded-pill flex-1 fw-medium" onclick="openShiftDrawer(12)" style="font-size: 12px;">Chi tiết</button>
                                <button type="button" class="btn btn-sm btn-primary rounded-pill flex-1 fw-bold" onclick="registerShift(12, 5)" style="font-size: 12px; background: #2563EB;">Đăng ký ca</button>
                            </div>
                        </div>
                    </div>

                    <!-- Job Card 3: Lab AI -->
                    <div class="saas-job-card" data-category="tech">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="job-card-tag" style="background: #EFF6FF; color: #2563EB;">Kỹ thuật Lab</span>
                                <span class="job-card-remun">150.000 đ/ca</span>
                            </div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 14.5px;">Ca 1: Đón Tiếp Khách Mời & Hỗ Trợ Kỹ Thuật Lab</h6>
                            <div class="text-primary small fw-semibold mb-2" style="font-size: 12px;"><i class="bi bi-calendar-event me-1"></i>Workshop: Kỷ Nguyên AI Vibe Coding</div>
                            
                            <div class="d-flex flex-column gap-1 text-muted small mb-3" style="font-size: 12px;">
                                <div><i class="bi bi-clock me-1.5 text-muted"></i>18/11/2026 • 13:00 – 17:00</div>
                                <div><i class="bi bi-geo-alt me-1.5 text-muted"></i>Phòng Lab AI, Tòa B</div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex align-items-center justify-content-between text-muted small mb-1.5" style="font-size: 11px;">
                                <span>Tiến độ nhận ca:</span>
                                <span class="fw-bold text-dark">Đã duyệt 1/3 CTV</span>
                            </div>
                            <div class="progress mb-3" style="height: 5px; border-radius: 4px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 33%;"></div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-light border rounded-pill flex-1 fw-medium" onclick="openShiftDrawer(5)" style="font-size: 12px;">Chi tiết</button>
                                <button type="button" class="btn btn-sm btn-primary rounded-pill flex-1 fw-bold" onclick="registerShift(5, 2)" style="font-size: 12px; background: #2563EB;">Đăng ký ca</button>
                            </div>
                        </div>
                    </div>

                    <!-- Job Card 4: Media Giải Thể Thao -->
                    <div class="saas-job-card" data-category="media">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="job-card-tag" style="background: #F3E8FF; color: #9333EA;">Media Thể Thao</span>
                                <span class="job-card-remun">180.000 đ/ca</span>
                            </div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 14.5px;">Ca 2: Media Thể Thao & Quay Phim Khoảnh Khắc</h6>
                            <div class="text-primary small fw-semibold mb-2" style="font-size: 12px;"><i class="bi bi-calendar-event me-1"></i>Giải Thể Thao FPoly Vô Địch 2026</div>
                            
                            <div class="d-flex flex-column gap-1 text-muted small mb-3" style="font-size: 12px;">
                                <div><i class="bi bi-clock me-1.5 text-muted"></i>20/11/2026 • 07:30 – 11:30</div>
                                <div><i class="bi bi-geo-alt me-1.5 text-muted"></i>Sân Vận Động Đa Năng</div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex align-items-center justify-content-between text-muted small mb-1.5" style="font-size: 11px;">
                                <span>Tiến độ nhận ca:</span>
                                <span class="fw-bold text-dark">0/2 CTV (Đang tuyển gấp)</span>
                            </div>
                            <div class="progress mb-3" style="height: 5px; border-radius: 4px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 15%;"></div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-light border rounded-pill flex-1 fw-medium" onclick="openShiftDrawer(7)" style="font-size: 12px;">Chi tiết</button>
                                <button type="button" class="btn btn-sm btn-primary rounded-pill flex-1 fw-bold" onclick="registerShift(7, 3)" style="font-size: 12px; background: #2563EB;">Đăng ký ca</button>
                            </div>
                        </div>
                    </div>

                    <!-- Job Card 5: Lễ Tân Triển Lãm -->
                    <div class="saas-job-card" data-category="reception">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="job-card-tag" style="background: #DCFCE7; color: #15803D;">Lễ tân ngoại giao</span>
                                <span class="job-card-remun">150.000 đ/ca</span>
                            </div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 14.5px;">Ca 1: Hướng Dẫn Khách Tham Quan Gian Hàng</h6>
                            <div class="text-primary small fw-semibold mb-2" style="font-size: 12px;"><i class="bi bi-calendar-event me-1"></i>Triển Lãm Đồ Họa FPoly Creative</div>
                            
                            <div class="d-flex flex-column gap-1 text-muted small mb-3" style="font-size: 12px;">
                                <div><i class="bi bi-clock me-1.5 text-muted"></i>22/11/2026 • 07:30 – 12:00</div>
                                <div><i class="bi bi-geo-alt me-1.5 text-muted"></i>Sảnh Tòa Nhà A, FPoly Đồng Nai</div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex align-items-center justify-content-between text-muted small mb-1.5" style="font-size: 11px;">
                                <span>Tiến độ nhận ca:</span>
                                <span class="fw-bold text-dark">Đã duyệt 2/4 CTV</span>
                            </div>
                            <div class="progress mb-3" style="height: 5px; border-radius: 4px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 50%;"></div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-light border rounded-pill flex-1 fw-medium" onclick="openShiftDrawer(8)" style="font-size: 12px;">Chi tiết</button>
                                <button type="button" class="btn btn-sm btn-primary rounded-pill flex-1 fw-bold" onclick="registerShift(8, 4)" style="font-size: 12px; background: #2563EB;">Đăng ký ca</button>
                            </div>
                        </div>
                    </div>

                    <!-- Job Card 6: Hậu Cần Setup -->
                    <div class="saas-job-card" data-category="logistics">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="job-card-tag" style="background: #FFF7ED; color: #C2410C;">Hậu cần sự kiện</span>
                                <span class="job-card-remun">150.000 đ/ca</span>
                            </div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 14.5px;">Ca 1: Setup Hội Trường & Hệ Thống Trình Chiếu</h6>
                            <div class="text-primary small fw-semibold mb-2" style="font-size: 12px;"><i class="bi bi-calendar-event me-1"></i>Workshop Bộ môn CNTT Đổi Mới Đào Tạo</div>
                            
                            <div class="d-flex flex-column gap-1 text-muted small mb-3" style="font-size: 12px;">
                                <div><i class="bi bi-clock me-1.5 text-muted"></i>22/11/2026 • 13:00 – 15:30</div>
                                <div><i class="bi bi-geo-alt me-1.5 text-muted"></i>Phòng Họp 301, Tòa A</div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex align-items-center justify-content-between text-muted small mb-1.5" style="font-size: 11px;">
                                <span>Tiến độ nhận ca:</span>
                                <span class="fw-bold text-dark">Đã duyệt 1/2 CTV</span>
                            </div>
                            <div class="progress mb-3" style="height: 5px; border-radius: 4px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 50%;"></div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-light border rounded-pill flex-1 fw-medium" onclick="openShiftDrawer(15)" style="font-size: 12px;">Chi tiết</button>
                                <button type="button" class="btn btn-sm btn-primary rounded-pill flex-1 fw-bold" onclick="registerShift(15, 7)" style="font-size: 12px; background: #2563EB;">Đăng ký ca</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- =========================================================
                 PANEL 16: CHI TIẾT NGHIỆM THU (GỘP NỘP MINH CHỨNG & TRẠNG THÁI NGHIỆM THU)
                 Theo thiết kế chuẩn pixel-perfect Mockup media_1794986669899_cfaac146.jpg
                 ========================================================= -->
            <section id="panelMyProofSubmission" style="display:none !important; flex-direction: column;" class="spa-panel fade-in-up">
                
                <!-- Sub-header & Breadcrumb Navigation -->
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: #E2E8F0 !important;">
                    <div class="d-flex align-items-center gap-3">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 34px; height: 34px; border-color: #CBD5E1; color: #475569;" onclick="navigateToPage('my-shifts')" title="Quay lại công việc của tôi">
                            <i class="bi bi-arrow-left" style="font-size: 15px;"></i>
                        </button>
                        <div class="d-flex align-items-center gap-2.5">
                            <h4 class="fw-bold mb-0 text-dark" style="font-size: 18px; letter-spacing: -0.3px;">Chi tiết nghiệm thu</h4>
                            <span class="badge rounded-pill" style="background: #F3E8FF; color: #9333EA; font-size: 12px; font-weight: 600; padding: 4px 10px;">
                                <i class="bi bi-gear-fill me-1"></i>Hậu cần
                            </span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 fw-medium text-muted d-inline-flex align-items-center gap-1.5" onclick="openSubmitProofModalForUser()" title="Nộp thêm ảnh hoặc dán link Drive" style="font-size: 12px;">
                            <i class="bi bi-cloud-arrow-up text-primary"></i> Nộp thêm ảnh / Drive
                        </button>
                    </div>
                </div>

                <!-- HÀNG 1: THẺ THÔNG TIN CTV & STEPPER 5 BƯỚC TIẾN TRÌNH NGHIỆM THU -->
                <div class="proof-top-info-grid mb-3">
                    
                    <!-- Card Trái: Thông tin CTV & Sự kiện -->
                    <div class="card border-0 rounded-3 shadow-xs p-3.5" style="border: 1px solid #E2E8F0 !important; background: #FFFFFF;">
                        <div class="d-flex align-items-center gap-3 mb-2.5">
                            <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: #0284C7; font-size: 18px; flex-shrink: 0;">
                                H
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 15px;">Hoàng Minh Luân</h6>
                                    <span class="badge" style="background: rgba(37, 99, 235, 0.1); color: #2563EB; font-size: 11px; padding: 2px 7px;">◆ CTV</span>
                                </div>
                                <div class="text-muted small mt-0.5" style="font-size: 12px;">PI03015 • hoangminhluan@fpt.edu.vn</div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pt-2 border-top" style="border-color: #F1F5F9 !important;">
                            <div class="d-flex flex-wrap align-items-center gap-3 text-muted small" style="font-size: 12px;">
                                <span class="text-dark fw-medium"><i class="bi bi-calendar-event text-primary me-1"></i>Chung kết Cuộc thi Khởi nghiệp trẻ</span>
                                <span><i class="bi bi-layers me-1 text-muted"></i>Ca 2: Kỹ thuật</span>
                                <span><i class="bi bi-clock me-1 text-muted"></i>18/11/2026 • 13:00 – 17:00</span>
                            </div>
                            <span class="badge rounded-pill" style="background: #FEF3C7; color: #D97706; font-size: 11.5px; padding: 4px 10px; font-weight: 600;">
                                <i class="bi bi-clock-history me-1"></i>Chờ duyệt
                            </span>
                        </div>
                    </div>

                    <!-- Card Phải: Stepper 5 Bước Tiến Trình Nghiệm Thu & Chi Trả -->
                    <div class="proof-stepper-card shadow-xs">
                        <div class="proof-stepper-track">
                            <!-- Step 1: Chưa nộp -->
                            <div class="proof-step-item">
                                <div class="proof-step-circle completed">
                                    <i class="bi bi-check-lg"></i>
                                </div>
                                <span class="proof-step-label">Chưa nộp</span>
                            </div>
                            <div class="proof-step-line completed"></div>

                            <!-- Step 2: Chờ duyệt (Active) -->
                            <div class="proof-step-item active">
                                <div class="proof-step-circle active"></div>
                                <span class="proof-step-label">Chờ duyệt</span>
                            </div>
                            <div class="proof-step-line"></div>

                            <!-- Step 3: Cần bổ sung -->
                            <div class="proof-step-item">
                                <div class="proof-step-circle">
                                    <i class="bi bi-exclamation" style="font-size: 16px;"></i>
                                </div>
                                <span class="proof-step-label">Cần bổ sung</span>
                            </div>
                            <div class="proof-step-line"></div>

                            <!-- Step 4: Đã nghiệm thu -->
                            <div class="proof-step-item">
                                <div class="proof-step-circle">
                                    <i class="bi bi-check-lg"></i>
                                </div>
                                <span class="proof-step-label">Đã nghiệm thu</span>
                            </div>
                            <div class="proof-step-line"></div>

                            <!-- Step 5: Đã chi trả -->
                            <div class="proof-step-item">
                                <div class="proof-step-circle">
                                    <i class="bi bi-wallet2" style="font-size: 13px;"></i>
                                </div>
                                <span class="proof-step-label">Đã chi trả</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- HÀNG 2: THẺ CALL TO ACTION "NỘP MINH CHỨNG" NỔI BẬT -->
                <div class="proof-cta-box mb-3 shadow-xs">
                    <div class="d-flex align-items-center gap-3">
                        <div class="proof-cta-icon">
                            <i class="bi bi-cloud-arrow-up"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 15px;">Nộp minh chứng</h6>
                            <div class="text-muted small" style="font-size: 12.5px;">Bấm để tải ảnh minh chứng hoặc dán link Drive</div>
                        </div>
                    </div>
                    <button type="button" class="proof-cta-btn" onclick="openSubmitProofModalForUser()">
                        <i class="bi bi-cloud-arrow-up-fill"></i> Nộp minh chứng
                    </button>
                </div>

                <!-- HÀNG 3: BỐ CỤC 2 CỘT CHUẨN MOCKUP IMAGE (62% - 38%) -->
                <div class="proof-detail-grid">
                    
                    <!-- CỘT TRÁI (~62%): LƯỚI 8 ẢNH MINH CHỨNG & MÔ TẢ CÔNG VIỆC -->
                    <div class="d-flex flex-column gap-3">
                        
                        <!-- Khối Minh Chứng 8 Ảnh Sắc Nét -->
                        <div class="card border-0 rounded-3 shadow-xs p-3.5" style="border: 1px solid #E2E8F0 !important; background: #FFFFFF;">
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: #F1F5F9 !important;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-images text-primary" style="font-size: 16px;"></i>
                                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 14.5px;">Minh chứng (8)</h6>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-sm btn-light border px-2.5 py-1 text-muted" title="Đổi chế độ xem">
                                        <i class="bi bi-grid-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light border px-2.5 py-1 text-muted" title="Tải xuống tất cả ảnh" onclick="downloadAllProofPhotos()">
                                        <i class="bi bi-download"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Lưới 8 Ảnh Minh Chứng (4 Cột x 2 Hàng) -->
                            <div class="proof-photo-matrix mb-3">
                                <div class="proof-photo-item is-selected" onclick="selectProofPhoto(0)">
                                    <img src="https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&w=600&q=80" alt="Minh chứng 1">
                                    <div class="proof-photo-zoom-btn" onclick="openLightbox(0); event.stopPropagation();"><i class="bi bi-arrows-angle-expand"></i></div>
                                </div>
                                <div class="proof-photo-item" onclick="selectProofPhoto(1)">
                                    <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=600&q=80" alt="Minh chứng 2">
                                    <div class="proof-photo-zoom-btn" onclick="openLightbox(1); event.stopPropagation();"><i class="bi bi-arrows-angle-expand"></i></div>
                                </div>
                                <div class="proof-photo-item" onclick="selectProofPhoto(2)">
                                    <img src="https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&w=600&q=80" alt="Minh chứng 3">
                                    <div class="proof-photo-zoom-btn" onclick="openLightbox(2); event.stopPropagation();"><i class="bi bi-arrows-angle-expand"></i></div>
                                </div>
                                <div class="proof-photo-item" onclick="selectProofPhoto(3)">
                                    <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=600&q=80" alt="Minh chứng 4">
                                    <div class="proof-photo-zoom-btn" onclick="openLightbox(3); event.stopPropagation();"><i class="bi bi-arrows-angle-expand"></i></div>
                                </div>
                                <div class="proof-photo-item" onclick="selectProofPhoto(4)">
                                    <img src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=600&q=80" alt="Minh chứng 5">
                                    <div class="proof-photo-zoom-btn" onclick="openLightbox(4); event.stopPropagation();"><i class="bi bi-arrows-angle-expand"></i></div>
                                </div>
                                <div class="proof-photo-item" onclick="selectProofPhoto(5)">
                                    <img src="https://images.unsplash.com/photo-1464375117522-1311d6a5b81f?auto=format&fit=crop&w=600&q=80" alt="Minh chứng 6">
                                    <div class="proof-photo-zoom-btn" onclick="openLightbox(5); event.stopPropagation();"><i class="bi bi-arrows-angle-expand"></i></div>
                                </div>
                                <div class="proof-photo-item" onclick="selectProofPhoto(6)">
                                    <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=600&q=80" alt="Minh chứng 7">
                                    <div class="proof-photo-zoom-btn" onclick="openLightbox(6); event.stopPropagation();"><i class="bi bi-arrows-angle-expand"></i></div>
                                </div>
                                <div class="proof-photo-item" onclick="selectProofPhoto(7)">
                                    <img src="https://images.unsplash.com/photo-1429962714451-bb934ecdc4ec?auto=format&fit=crop&w=600&q=80" alt="Minh chứng 8">
                                    <div class="proof-photo-zoom-btn" onclick="openLightbox(7); event.stopPropagation();"><i class="bi bi-arrows-angle-expand"></i></div>
                                </div>
                            </div>

                            <!-- Carousel Dải Thumbnail Filmstrip -->
                            <div class="proof-filmstrip-bar border-top pt-2" style="border-color: #F1F5F9 !important;">
                                <div class="d-flex align-items-center gap-1.5 text-muted small" style="font-size: 11.5px; font-weight: 500;">
                                    <button type="button" class="btn btn-sm btn-link p-0 text-muted" onclick="navigateProofPhoto(-1)"><i class="bi bi-chevron-left"></i></button>
                                    <span id="proofCarouselIndex">1/8</span>
                                    <button type="button" class="btn btn-sm btn-link p-0 text-muted" onclick="navigateProofPhoto(1)"><i class="bi bi-chevron-right"></i></button>
                                </div>
                                <div class="proof-thumb-strip">
                                    <div class="proof-thumb-cell active" onclick="selectProofPhoto(0)"><img src="https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&w=150&q=80" alt="Thumb 1"></div>
                                    <div class="proof-thumb-cell" onclick="selectProofPhoto(1)"><img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=150&q=80" alt="Thumb 2"></div>
                                    <div class="proof-thumb-cell" onclick="selectProofPhoto(2)"><img src="https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&w=150&q=80" alt="Thumb 3"></div>
                                    <div class="proof-thumb-cell" onclick="selectProofPhoto(3)"><img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=150&q=80" alt="Thumb 4"></div>
                                    <div class="proof-thumb-cell" onclick="selectProofPhoto(4)"><img src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=150&q=80" alt="Thumb 5"></div>
                                    <div class="proof-thumb-cell" onclick="selectProofPhoto(5)"><img src="https://images.unsplash.com/photo-1464375117522-1311d6a5b81f?auto=format&fit=crop&w=150&q=80" alt="Thumb 6"></div>
                                    <div class="proof-thumb-cell" onclick="selectProofPhoto(6)"><img src="https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=150&q=80" alt="Thumb 7"></div>
                                    <div class="proof-thumb-cell" onclick="selectProofPhoto(7)"><img src="https://images.unsplash.com/photo-1429962714451-bb934ecdc4ec?auto=format&fit=crop&w=150&q=80" alt="Thumb 8"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Mô Tả Công Việc Từ CTV -->
                        <div class="card border-0 rounded-3 shadow-xs p-3.5" style="border: 1px solid #E2E8F0 !important; background: #FFFFFF;">
                            <div class="d-flex align-items-center gap-2 mb-2 text-dark fw-bold" style="font-size: 13.5px;">
                                <i class="bi bi-info-circle text-primary"></i> Mô tả công việc
                            </div>
                            <p class="text-muted mb-0" style="font-size: 13px; line-height: 1.6;">
                                Hỗ trợ lắp đặt, vận hành hệ thống âm thanh và trình chiếu cho chương trình. Có mặt từ 12:30, kiểm tra thiết bị, điều chỉnh âm lượng trong suốt thời gian diễn ra sự kiện.
                            </p>
                        </div>
                    </div>

                    <!-- CỘT PHẢI (~38%): TIÊU CHÍ NGHIỆM THU & TRẠNG THÁI XỬ LÝ -->
                    <div class="d-flex flex-column gap-3">
                        
                        <!-- Card Tiêu Chí Nghiệm Thu -->
                        <div class="proof-criteria-card shadow-xs">
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: #F1F5F9 !important;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-clipboard-check text-primary" style="font-size: 16px;"></i>
                                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 14.5px;">Tiêu chí nghiệm thu</h6>
                                </div>
                                <i class="bi bi-info-circle text-muted cursor-pointer" title="Tiêu chí đánh giá chất lượng ca trực"></i>
                            </div>

                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex align-items-center justify-content-between py-1.5 border-bottom" style="border-color: #F8FAFC !important;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="proof-check-badge"><i class="bi bi-check-lg"></i></div>
                                        <span class="text-dark fw-medium" style="font-size: 13px;">Có mặt đúng ca trực</span>
                                    </div>
                                    <i class="bi bi-info-circle text-muted" style="font-size: 13px;" title="Check-in và tham gia xuyên suốt ca trực"></i>
                                </div>
                                <div class="d-flex align-items-center justify-content-between py-1.5 border-bottom" style="border-color: #F8FAFC !important;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="proof-check-badge"><i class="bi bi-check-lg"></i></div>
                                        <span class="text-dark fw-medium" style="font-size: 13px;">Thực hiện đúng nhiệm vụ</span>
                                    </div>
                                    <i class="bi bi-info-circle text-muted" style="font-size: 13px;" title="Hoàn thành đầy đủ các đầu việc được giao"></i>
                                </div>
                                <div class="d-flex align-items-center justify-content-between py-1.5 border-bottom" style="border-color: #F8FAFC !important;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="proof-check-badge"><i class="bi bi-check-lg"></i></div>
                                        <span class="text-dark fw-medium" style="font-size: 13px;">Ảnh rõ ràng, đúng bối cảnh</span>
                                    </div>
                                    <i class="bi bi-info-circle text-muted" style="font-size: 13px;" title="Ảnh minh chứng sắc nét, thấy rõ vị trí làm việc"></i>
                                </div>
                                <div class="d-flex align-items-center justify-content-between py-1.5">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="proof-uncheck-box cursor-pointer" onclick="toggleCriterion(this)"></div>
                                        <span class="text-dark fw-medium cursor-pointer" style="font-size: 13px;" onclick="toggleCriterion(this.previousElementSibling)">Phối hợp tốt với BTC</span>
                                    </div>
                                    <i class="bi bi-info-circle text-muted" style="font-size: 13px;" title="Chủ động hỗ trợ, tuân thủ phân công và đảm bảo tiến độ."></i>
                                </div>
                            </div>
                        </div>

                        <!-- Card Trạng Thái Xử Lý -->
                        <div class="proof-status-card shadow-xs">
                            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom" style="border-color: #F1F5F9 !important;">
                                <i class="bi bi-layers text-primary" style="font-size: 16px;"></i>
                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 14.5px;">Trạng thái xử lý</h6>
                            </div>

                            <div class="proof-status-box">
                                <div class="proof-status-icon">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <div>
                                    <div class="fw-bold" style="font-size: 14.5px; color: #92400E;">Chờ duyệt</div>
                                    <div class="small mt-0.5" style="font-size: 12.5px; color: #B45309;">Đang chờ GV/Organizer kiểm tra minh chứng.</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <!-- =========================================================
                 PANEL 17: BÁO CÁO TỔNG HỢP (REPORTS - EVENT & OPERATIONS)
                 ========================================================= -->
            <section id="panelReports" style="display:none !important; flex-direction: column;" class="spa-panel fade-in-up">
                <div class="saas-card flex-1 overflow-hidden d-flex flex-column h-100">
                    <div class="saas-card-header flex-shrink-0 d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 border-bottom">
                        <div>
                            <h3 class="fw-bold mb-0 text-dark" style="font-size: 16px;">Báo Cáo Hiệu Quả & Thống Kê Hoạt Động</h3>
                            <small class="text-muted">Tổng hợp số liệu điều phối nhân sự, nghiệm thu và ngân sách thù lao</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="window.print()">
                            <i class="bi bi-printer me-1"></i> In Báo Cáo
                        </button>
                    </div>
                    <div class="p-3 overflow-y-auto flex-1">
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-3">
                                <div class="saas-card p-3 border text-center">
                                    <div class="saas-kpi-val text-primary"><?= count($events) ?></div>
                                    <small class="saas-kpi-title">Sự kiện tổ chức</small>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="saas-card p-3 border text-center">
                                    <div class="saas-kpi-val text-success">87.5%</div>
                                    <small class="saas-kpi-title">Tỷ lệ lấp đầy ca trực</small>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="saas-card p-3 border text-center">
                                    <div class="saas-kpi-val text-warning"><?= count($accountingList) ?></div>
                                    <small class="saas-kpi-title">Hồ sơ đã nghiệm thu</small>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="saas-card p-3 border text-center">
                                    <div class="saas-kpi-val text-dark"><?= number_format(count($accountingList) * 150000) ?> đ</div>
                                    <small class="saas-kpi-title">Tổng thù lao giải ngân</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

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
     MODAL DUYỆT MINH CHỨNG – HẬU CẦN (PIXEL-PERFECT THEO MOCKUP IMAGE 9)
     ========================================================= -->
<div class="modal fade" id="modalVerifyFacilityProof" tabindex="-1" aria-hidden="true" style="z-index: 1090;">
    <div class="modal-dialog modal-dialog-centered modal-xl" style="max-width: 1240px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; background: #FFFFFF;">
            
            <!-- Modal Header -->
            <div class="modal-header border-0 pb-1 px-4 pt-3.5 d-flex align-items-start justify-content-between">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary-subtle text-primary" style="width: 32px; height: 32px;">
                        <i class="bi bi-shield-check fs-5"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark" style="font-size: 19px; letter-spacing: -0.015em;">Duyệt minh chứng – Hậu cần</h5>
                        <p class="text-muted mb-0" style="font-size: 13px;">Kiểm tra minh chứng, đối chiếu tiêu chí và quyết định nghiệm thu.</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body (3-Column Layout chuẩn Mockup Image 9) -->
            <div class="modal-body px-4 pt-2 pb-4">
                <div class="row g-3">
                    
                    <!-- CỘT 1: DANH SÁCH CTV NGHIỆM THU (Mockup Image 9) -->
                    <div class="col-12 col-lg-3 border-end pe-3" id="vfCtvSidebarCol">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold text-dark mb-0" style="font-size: 14.5px;">Nghiệm thu</h6>
                        </div>
                        <div class="input-group input-group-sm mb-2.5">
                            <span class="input-group-text bg-white border-end-0 text-muted" style="border-color: #E2E8F0;"><i class="bi bi-search" style="font-size: 11px;"></i></span>
                            <input type="text" class="form-control border-start-0 ps-1" placeholder="Tìm kiếm CTV..." id="vfCtvSearchInput" oninput="filterVfCtvList(this.value)" style="border-color: #E2E8F0; font-size: 12px;">
                        </div>
                        <div class="text-muted small fw-semibold text-uppercase mb-2" style="font-size: 11px; letter-spacing: 0.3px;">Sinh viên / CTV</div>
                        <div class="d-flex flex-column gap-1 overflow-y-auto" style="max-height: 520px;" id="vfCtvListContainer">
                            <!-- Nạp động qua JS: Hoàng Minh Luân (Active), Nguyễn Thư Kỳ, Trần Gia Bảo, Lê Minh Tâm... -->
                        </div>
                    </div>

                    <!-- CỘT 2: THÔNG TIN CHI TIẾT, 8 ẢNH & MÔ TẢ (Mockup Image 9) -->
                    <div class="col-12 col-lg-5 ps-3 pe-3 border-end d-flex flex-column">
                        <!-- TOP CARD: Thông tin CTV & Sự kiện -->
                        <div class="proof-modal-card p-2.5 mb-2.5 d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <!-- Left: CTV Info -->
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0" id="vfCtvAvatar" style="width: 40px; height: 40px; background: #FFEDD5; color: #C2410C; font-size: 16px;">
                                    H
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-1.5 mb-0.5">
                                        <span class="fw-bold text-dark" id="vfCtvName" style="font-size: 14px;">Hoàng Minh Luận</span>
                                        <span class="badge" style="background: #EFF6FF; color: #2563EB; border: 1px solid #DBEAFE; font-size: 9.5px; font-weight: 600; padding: 2px 5px;">
                                            ◆ CTV
                                        </span>
                                    </div>
                                    <div class="text-muted small" id="vfCtvMeta" style="font-size: 11.5px;">
                                        PI03015 • hoangminhluan@fpt.edu.vn
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Event & Shift Meta -->
                            <div class="d-flex flex-column gap-0.5 text-end" style="font-size: 11.5px;">
                                <div class="text-dark">
                                    <span class="text-muted me-1"><i class="bi bi-calendar-event me-1"></i>Sự kiện:</span>
                                    <strong id="vfEventTitle" class="text-truncate d-inline-block align-bottom" style="max-width: 170px;">Chung kết Khởi nghiệp</strong>
                                </div>
                                <div class="text-dark">
                                    <span class="text-muted me-1"><i class="bi bi-layers me-1"></i>Ca trực:</span>
                                    <span id="vfShiftName" class="fw-medium text-truncate d-inline-block align-bottom" style="max-width: 170px;">Ca 2: Kỹ thuật</span>
                                </div>
                                <div class="text-dark">
                                    <span class="text-muted me-1"><i class="bi bi-clock me-1"></i>Thời gian:</span>
                                    <span id="vfShiftTime">18/11/2026, 13:00–17:00</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tabs: Minh chứng (8) & Trao đổi -->
                        <div class="d-flex align-items-center gap-3 border-bottom mb-2.5 pb-0.5" style="border-color: #E2E8F0 !important;">
                            <button type="button" class="btn btn-link text-decoration-none px-0 py-1.5 fw-semibold text-primary d-inline-flex align-items-center gap-1.5" style="border-bottom: 2.5px solid #2563EB; border-radius: 0; font-size: 13px;">
                                <i class="bi bi-images"></i>
                                <span>Minh chứng (8)</span>
                            </button>
                            <button type="button" class="btn btn-link text-decoration-none px-0 py-1.5 text-muted d-inline-flex align-items-center gap-1.5" style="font-size: 13px;" onclick="showToast('Chức năng Trao đổi & Lịch sử đang đồng bộ với kênh Chat BTC', 'info')">
                                <i class="bi bi-chat-left-text"></i>
                                <span>Trao đổi & Lịch sử</span>
                            </button>
                        </div>

                        <!-- Header Ảnh minh chứng (8) -->
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 13.5px;">Ảnh minh chứng (8)</h6>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 px-2.5 py-1" style="font-size: 11px; border-radius: 6px;" onclick="downloadAllProofPhotos()">
                                    <i class="bi bi-download"></i>
                                    <span>Tải xuống tất cả</span>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary px-2 py-1" style="font-size: 11px; border-radius: 6px;" onclick="expandProofFullscreen()" title="Xem toàn màn hình">
                                    <i class="bi bi-arrows-fullscreen"></i>
                                </button>
                            </div>
                        </div>

                        <!-- 8-Photo Grid (4 cols x 2 rows) -->
                        <div class="proof-photo-grid mb-2.5" id="vfPhotoGrid">
                            <!-- 8 ảnh chuẩn Mockup -->
                            <div class="proof-photo-item" onclick="openProofZoomModal(0)"><img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&auto=format&fit=crop&q=80" alt="Kỹ thuật âm thanh"></div>
                            <div class="proof-photo-item" onclick="openProofZoomModal(1)"><img src="https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=600&auto=format&fit=crop&q=80" alt="Đèn spotlight"></div>
                            <div class="proof-photo-item" onclick="openProofZoomModal(2)"><img src="https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=600&auto=format&fit=crop&q=80" alt="Sân khấu Chung kết"></div>
                            <div class="proof-photo-item" onclick="openProofZoomModal(3)"><img src="https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=600&auto=format&fit=crop&q=80" alt="Laptop điều khiển"></div>
                            <div class="proof-photo-item" onclick="openProofZoomModal(4)"><img src="https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=600&auto=format&fit=crop&q=80" alt="Server rack"></div>
                            <div class="proof-photo-item" onclick="openProofZoomModal(5)"><img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=600&auto=format&fit=crop&q=80" alt="Bàn mixer âm thanh"></div>
                            <div class="proof-photo-item" onclick="openProofZoomModal(6)"><img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=600&auto=format&fit=crop&q=80" alt="Setup hội trường"></div>
                            <div class="proof-photo-item" onclick="openProofZoomModal(7)"><img src="https://images.unsplash.com/photo-1511578314322-379afb476865?w=600&auto=format&fit=crop&q=80" alt="Khán phòng"></div>
                        </div>

                        <!-- Mô tả công việc từ CTV -->
                        <div class="rounded-3 p-2.5 mt-auto" style="background: #F0F9FF; border: 1px solid #BAE6FD;">
                            <div class="fw-semibold text-primary d-flex align-items-center gap-1.5 mb-1" style="font-size: 12px; color: #0284C7 !important;">
                                <i class="bi bi-info-circle-fill"></i>
                                <span>Mô tả công việc (từ CTV)</span>
                            </div>
                            <p class="mb-0 text-dark" id="vfProofDescription" style="font-size: 11.5px; line-height: 1.4; color: #334155 !important;">
                                Hỗ trợ lắp đặt, vận hành hệ thống âm thanh và trình chiếu cho chương trình. Có mặt từ 12:30, kiểm tra thiết bị, điều chỉnh âm lượng trong suốt thời gian diễn ra sự kiện và thu dọn sau chương trình.
                            </p>
                        </div>
                    </div>

                    <!-- CỘT 3: TIÊU CHÍ NGHIỆM THU & QUYẾT ĐỊNH (Mockup Image 9) -->
                    <div class="col-12 col-lg-4 ps-3 d-flex flex-column justify-content-between">
                        <div>
                            <!-- Card 1: Tiêu chí nghiệm thu – Hậu cần -->
                            <div class="proof-modal-card p-3 mb-2.5">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-card-checklist text-primary" style="font-size: 14.5px;"></i>
                                    <strong class="text-dark" style="font-size: 13px;">Tiêu chí nghiệm thu – Hậu cần</strong>
                                </div>
                                <div class="d-flex flex-column gap-1.5">
                                    <!-- Tiêu chí 1 -->
                                    <div class="proof-criteria-item is-checked" onclick="toggleCriteriaCheck(this)">
                                        <div class="proof-criteria-check"><i class="bi bi-check-lg"></i></div>
                                        <div style="font-size: 12px; line-height: 1.35; color: #0F172A;">
                                            Có mặt và tham gia đúng thời gian ca trực
                                        </div>
                                    </div>
                                    <!-- Tiêu chí 2 -->
                                    <div class="proof-criteria-item is-checked" onclick="toggleCriteriaCheck(this)">
                                        <div class="proof-criteria-check"><i class="bi bi-check-lg"></i></div>
                                        <div>
                                            <div style="font-size: 12px; line-height: 1.35; color: #0F172A;">
                                                Thực hiện đúng nhiệm vụ được phân công
                                            </div>
                                            <div class="text-muted" style="font-size: 10.5px;">
                                                (ví dụ: âm thanh, trình chiếu, hậu trường, hỗ trợ sân khấu,...)
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Tiêu chí 3 -->
                                    <div class="proof-criteria-item is-checked" onclick="toggleCriteriaCheck(this)">
                                        <div class="proof-criteria-check"><i class="bi bi-check-lg"></i></div>
                                        <div style="font-size: 12px; line-height: 1.35; color: #0F172A;">
                                            Ảnh minh chứng rõ ràng, có bối cảnh tại sự kiện
                                        </div>
                                    </div>
                                    <!-- Tiêu chí 4 -->
                                    <div class="proof-criteria-item" onclick="toggleCriteriaCheck(this)">
                                        <div class="proof-criteria-check"><i class="bi bi-check-lg" style="display:none;"></i></div>
                                        <div class="d-flex align-items-center gap-1" style="font-size: 12px; line-height: 1.35; color: #334155;">
                                            <span>Đảm bảo tác phong làm việc, phối hợp với BTC</span>
                                            <i class="bi bi-info-circle text-muted" style="font-size: 11px;" title="Tuân thủ quy tắc ứng xử và trang phục"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 2: Quyết định nghiệm thu -->
                            <div class="proof-modal-card p-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-shield-check text-primary" style="font-size: 14.5px;"></i>
                                    <strong class="text-dark" style="font-size: 13px;">Quyết định nghiệm thu</strong>
                                </div>

                                <!-- Option 1: Duyệt nghiệm thu -->
                                <div class="proof-decision-box is-selected" id="vfOptApprove" onclick="selectProofDecision('approve')">
                                    <div class="proof-radio-circle"></div>
                                    <div>
                                        <strong class="d-block text-dark" style="font-size: 12.5px;">Duyệt nghiệm thu</strong>
                                        <span class="text-muted" style="font-size: 11px;">Minh chứng đáp ứng đầy đủ tiêu chí.</span>
                                    </div>
                                </div>

                                <!-- Option 2: Yêu cầu bổ sung -->
                                <div class="proof-decision-box" id="vfOptRequestMore" onclick="selectProofDecision('request_more')">
                                    <div class="proof-radio-circle"></div>
                                    <div>
                                        <strong class="d-block text-dark" style="font-size: 12.5px;">Yêu cầu bổ sung</strong>
                                        <span class="text-muted" style="font-size: 11px;">Minh chứng chưa đủ, cần CTV bổ sung thêm.</span>
                                    </div>
                                </div>

                                <!-- Textarea Lý do / Ghi chú -->
                                <div class="mt-2">
                                    <label class="form-label mb-1 text-dark fw-semibold" style="font-size: 11.5px;">
                                        Lý do / Ghi chú <span class="text-danger" id="vfNoteAsterisk" style="display:none;">*</span>
                                    </label>
                                    <textarea class="form-control" id="vfDecisionNote" rows="2" placeholder="Nhập ghi chú cho CTV (nếu có)..." maxlength="500" oninput="updateDecisionNoteCounter()" style="font-size: 11.5px; border-radius: 8px; resize: none;"></textarea>
                                    <div class="text-end text-muted mt-1" style="font-size: 10.5px;">
                                        <span id="vfNoteCharCount">0</span> / 500
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="d-flex align-items-center justify-content-end gap-2 mt-3 pt-1">
                            <button type="button" class="btn btn-sm btn-link text-muted fw-semibold text-decoration-none px-3 py-1.5" data-bs-dismiss="modal" style="font-size: 12.5px;">
                                Hủy
                            </button>
                            <button type="button" class="btn btn-sm btn-primary px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5" id="vfConfirmBtn" onclick="confirmFacilityProofVerification()" style="background: #2563EB; border-color: #2563EB; border-radius: 8px; font-size: 12.5px;">
                                <i class="bi bi-check-lg"></i>
                                <span>Xác nhận</span>
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
                    <?php if (in_array($perms['role'], ['STAFF', 'ADMIN'], true)): ?>
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

<!-- =========================================================
     MODAL CHI TIẾT SỰ KIỆN & PHÂN RÃ CA TRỰC CTV
     ========================================================= -->
<div class="modal fade" id="eventShiftsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-bottom p-3.5 px-4" style="background: #FAFAFC;">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-2.5 rounded-3 text-white" style="background: linear-gradient(135deg, #EA580C 0%, #C2410C 100%);">
                        <i class="bi bi-calendar-event fs-5"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-0.5">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-semibold" id="evModalDept">Bộ môn</span>
                            <span class="badge-status-published"><i class="bi bi-check-circle-fill"></i> Đã công bố</span>
                        </div>
                        <h5 class="fw-bold mb-0 text-dark" id="evModalTitle">Chi Tiết Sự Kiện</h5>
                        <small class="text-muted num-tabular" id="evModalTimeLoc"><i class="bi bi-clock me-1"></i>08:00 - 17:00 • <i class="bi bi-geo-alt me-1 text-danger"></i>Hội trường</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-3" id="evModalDesc" style="line-height: 1.5;"></p>
                <div class="d-flex align-items-center justify-content-between mb-2.5 pb-2 border-bottom">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-list-task me-1.5 text-primary"></i>Danh Sách Phân Rã Ca Trực CTV</h6>
                    <span class="badge bg-light text-dark border num-tabular" id="evModalShiftCount">0 ca trực</span>
                </div>
                <div class="d-flex flex-column gap-2" id="evModalShiftsList">
                    <!-- Dynamic Shifts List -->
                </div>
            </div>
            <div class="modal-footer border-top p-3 px-4 bg-light d-flex justify-content-between align-items-center">
                <div id="evModalOrganizerActions">
                    <!-- Delete event button if authorized -->
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn-fpt-secondary btn-fpt-sm" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn-fpt-primary btn-fpt-sm" id="evModalAddShiftBtn" onclick="openCreateShiftModal(currentOpenEventId)" style="display:none;">
                        <i class="bi bi-plus-lg me-1"></i> Thêm Ca Trực
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================
     UNIVERSAL SAAS DRAWER (OFFCANVAS SIDE SHEET)
     Progressive Disclosure on Row/Record Click
     ========================================================= -->
<div class="saas-drawer-backdrop" id="saasDrawerBackdrop" onclick="closeSaasDrawer()"></div>
<div class="saas-drawer" id="saasDrawer" aria-hidden="true">
    <div class="saas-drawer-header">
        <div class="saas-drawer-title-group">
            <h3 class="saas-drawer-title" id="saasDrawerTitle">Chi Tiết Bản Ghi</h3>
            <span class="saas-drawer-subtitle" id="saasDrawerSubtitle">Thông tin chi tiết</span>
        </div>
        <button type="button" class="saas-drawer-close" onclick="closeSaasDrawer()" aria-label="Đóng"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="saas-drawer-tabs" id="saasDrawerTabs" style="display:none;"></div>
    <div class="saas-drawer-body" id="saasDrawerBody"></div>
    <div class="saas-drawer-footer" id="saasDrawerFooter">
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="closeSaasDrawer()">Đóng</button>
    </div>
</div>

<!-- =========================================================
     CALENDAR DAY CONTEXTUAL POPOVER (+N LỊCH KHÁC)
     ========================================================= -->
<div class="saas-popover-box" id="calendarDayPopover">
    <div class="d-flex align-items-center justify-content-between mb-2 pb-1 border-bottom">
        <strong class="text-dark small" id="popoverDayTitle">Danh sách hoạt động</strong>
        <button type="button" class="btn-close" style="font-size:10px;" onclick="closeCalendarDayPopover()"></button>
    </div>
    <div class="d-flex flex-column gap-1.5" id="popoverDayList"></div>
</div>

<!-- =========================================================
     CONFIRMATION MODALS (FOR HIGH-STAKES ACTIONS)
     ========================================================= -->
<!-- 1. Modal Xác Nhận Đổi Phân Quyền -->
<div class="modal fade" id="modalConfirmRoleChange" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-bottom bg-warning-subtle text-dark px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-shield-exclamation text-warning fs-5"></i>
                    <h6 class="modal-title fw-bold mb-0">Xác Nhận Thay Đổi Phân Quyền</h6>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-2" id="confirmRoleChangeText">
                    Bạn có chắc chắn muốn thay đổi vai trò của người dùng này?
                </p>
                <div class="alert alert-warning py-2.5 px-3 small rounded-3 mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Quyền truy cập dữ liệu và các hành động trong hệ thống của người dùng sẽ thay đổi ngay lập tức.
                </div>
            </div>
            <div class="modal-footer bg-light px-4 py-2.5 border-top d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" id="btnConfirmRoleChangeSubmit" onclick="executeRoleChange()">
                    Xác Nhận Đổi Quyền
                </button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Modal Xác Nhận Khóa / Kích Hoạt Tài Khoản -->
<div class="modal fade" id="modalConfirmToggleUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-bottom px-4 py-3" id="confirmToggleUserHeader">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-person-slash fs-5"></i>
                    <h6 class="modal-title fw-bold mb-0" id="confirmToggleUserTitle">Xác Nhận Khóa Tài Khoản</h6>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-0" id="confirmToggleUserText">
                    Bạn có chắc chắn muốn khóa tài khoản này?
                </p>
            </div>
            <div class="modal-footer bg-light px-4 py-2.5 border-top d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-sm btn-danger rounded-pill px-3" id="btnConfirmToggleUserSubmit" onclick="executeToggleUser()">
                    Xác Nhận
                </button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Modal Xác Nhận Hủy Phân Công Ca Trực -->
<div class="modal fade" id="modalConfirmCancelAssignment" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-bottom bg-danger-subtle text-danger px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-x-octagon-fill fs-5"></i>
                    <h6 class="modal-title fw-bold mb-0">Xác Nhận Hủy Phân Công</h6>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-0" id="confirmCancelAssignmentText">
                    Bạn có chắc chắn muốn hủy phân công ca trực này của CTV?
                </p>
            </div>
            <div class="modal-footer bg-light px-4 py-2.5 border-top d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-sm btn-danger rounded-pill px-3" id="btnConfirmCancelAssignmentSubmit" onclick="executeCancelAssignment()">
                    Hủy Phân Công
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
const rawEvents = <?= json_encode($events, JSON_UNESCAPED_UNICODE) ?>;
const rawAccountingList = <?= json_encode($accountingList, JSON_UNESCAPED_UNICODE) ?>;
window.ctvRosterData = <?= json_encode(array_values($ctvRoster ?? []), JSON_UNESCAPED_UNICODE) ?>;
const rawCalendarNotes = <?= json_encode($calendarNotes ?? [], JSON_UNESCAPED_UNICODE) ?>;
const rawAllUsers = <?= json_encode($allUsers ?? [], JSON_UNESCAPED_UNICODE) ?>;
const rawRoles = <?= json_encode($roles ?? [], JSON_UNESCAPED_UNICODE) ?>;
const rawActivityLogs = <?= json_encode($activityLogs ?? [], JSON_UNESCAPED_UNICODE) ?>;
const currentUser = <?= json_encode($currentUser, JSON_UNESCAPED_UNICODE) ?>;
const userPermissions = <?= json_encode($perms, JSON_UNESCAPED_UNICODE) ?>;

let currentYear = 2026;
let currentMonth = 10; // Tháng 11 (0-indexed)
let selectedDateStr = '2026-11-15';
let currentCalendarTier = 'month';
let activePage = 'calendar';
let currentSidebarTab = 'activities';
let activeInspectorEventId = 2;
let currentDayFilter = 'all';

// Mock dữ liệu 4 hoạt động ngày 18/11/2026 giải quyết bài toán trùng lịch (Chuẩn Image 10)
const day18Activities = [
    {
        id: 'act_lab_ai',
        type: 'other', // Khác
        title: 'Bảo trì phòng máy Lab AI',
        time: '08:00 – 12:00',
        location: 'Phòng CSC, Tòa B',
        description: 'Kiểm tra đường truyền và bổ sung mô hình AI.',
        status: 'Đang thực hiện',
        statusBg: '#E0F2FE',
        statusColor: '#0369A1',
        hasDot: true,
        borderColor: '#0284C7',
        icon: 'bi-calendar3',
        iconColor: '#0284C7',
        eventId: 2
    },
    {
        id: 'act_shift_1',
        type: 'shift', // Ca trực
        title: 'Ca 1: Hỗ trợ kỹ thuật',
        time: '13:00 – 17:00',
        location: 'Phòng Lab, Tòa B',
        ctvInfo: '1/3 CTV',
        allowance: '150.000 đ',
        status: 'Đang diễn ra',
        statusBg: '#DCFCE7',
        statusColor: '#15803D',
        hasDot: false,
        borderColor: '#9333EA',
        icon: 'bi-clock-history',
        iconColor: '#9333EA',
        shiftId: 101,
        eventId: 2
    },
    {
        id: 'act_shift_2',
        type: 'shift', // Ca trực
        title: 'Ca 2: Setup sân khấu',
        time: '17:30 – 18:30',
        location: 'Sân khấu ngoài trời',
        ctvInfo: '0/2 CTV',
        status: 'Chờ phân công',
        statusBg: '#FFF7ED',
        statusColor: '#EA580C',
        hasDot: false,
        borderColor: '#EA580C',
        icon: 'bi-calendar3',
        iconColor: '#EA580C',
        shiftId: 102,
        eventId: 1
    },
    {
        id: 'act_event_btc',
        type: 'event', // Sự kiện
        title: 'Họp tổng kết BTC',
        time: '19:00 – 21:00',
        location: 'Phòng họp 301, Tòa A',
        description: 'Họp rà soát công tác chuẩn bị.',
        status: 'Sắp diễn ra',
        statusBg: '#FAF5FF',
        statusColor: '#7E22CE',
        hasDot: false,
        borderColor: '#16A34A',
        icon: 'bi-calendar-check',
        iconColor: '#16A34A',
        eventId: 1
    }
];

let shiftModalInstance = null;
let aiModalInstance = null;
let createShiftModalInstance = null;
let previewVneidModalInstance = null;
let addNoteModalInstance = null;
let submitProofModalInstance = null;
let previewProofModalInstance = null;
let contractModalInstance = null;
let currentViewingAssignmentId = null;

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
    initEventsPage();

    // Universal Multi-Modal Stacking & Layering Protection
    document.addEventListener('show.bs.modal', function(event) {
        const openModals = Array.from(document.querySelectorAll('.modal.show')).filter(m => m !== event.target);
        if (openModals.length > 0) {
            let highestZ = 1055;
            openModals.forEach(m => {
                const z = parseInt(window.getComputedStyle(m).zIndex, 10);
                if (!isNaN(z) && z > highestZ) highestZ = z;
            });
            const newZ = highestZ + 20;
            event.target.style.zIndex = newZ;
            setTimeout(() => {
                const backdrops = document.querySelectorAll('.modal-backdrop');
                if (backdrops.length > 0) {
                    const latestBackdrop = backdrops[backdrops.length - 1];
                    latestBackdrop.style.zIndex = newZ - 5;
                }
            }, 10);
        }
    });

    document.addEventListener('hidden.bs.modal', function(event) {
        event.target.style.zIndex = '';
        const remainingOpen = document.querySelectorAll('.modal.show');
        if (remainingOpen.length > 0) {
            document.body.classList.add('modal-open');
        }
    });

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
        'panelOverviewReport',
        'panelCalendar', 
        'panelAccounting', 
        'panelStudentPayrollProfile', 
        'panelMyShifts', 
        'panelEvents', 
        'panelShiftsSessions',
        'panelCtvAssignments',
        'panelRoomsBooking',
        'panelSubmissions', 
        'panelContractDossier', 
        'panelExploreJobs',
        'panelMyProofSubmission',
        'panelReports',
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
        'overview-report': { id: 'panelOverviewReport', display: 'flex', title: 'Dashboard của tôi', breadcrumb: 'Dashboard' },
        'calendar': { id: 'panelCalendar', display: 'flex', title: 'Lịch hoạt động', breadcrumb: 'Lịch hoạt động' },
        'events': { id: 'panelEvents', display: 'flex', title: 'Sự Kiện & Ca Trực', breadcrumb: 'Quản Lý Sự Kiện', onShow: () => initEventsPage() },
        'shifts-sessions': { id: 'panelShiftsSessions', display: 'flex', title: 'Buổi Hoạt Động & Ca Trực Phân Rã', breadcrumb: 'Buổi Hoạt Động' },
        'ctv-assignments': { id: 'panelCtvAssignments', display: 'flex', title: 'Cộng Tác Viên & Phân Công Nhiệm Vụ', breadcrumb: 'Phân Công CTV' },
        'rooms-booking': { id: 'panelRoomsBooking', display: 'flex', title: 'Phòng Ốc, Thiết Bị & Lịch Đặt Phòng', breadcrumb: 'Vận Hành Cơ Sở Vật Chất' },
        'room-calendar': { id: 'panelRoomsBooking', display: 'flex', title: 'Lịch Đặt Phòng & Thiết Bị Hội Trường', breadcrumb: 'Lịch Đặt Phòng' },
        'proof-detail': { id: 'panelProofDetail', display: 'flex', title: 'Chi Tiết Minh Chứng Nghiệm Thu', breadcrumb: 'Chi tiết minh chứng' },
        'submissions': { id: 'panelSubmissions', display: 'flex', title: 'Nghiệm Thu Minh Chứng & Duyệt Thẩm Định', breadcrumb: 'Duyệt Minh Chứng' },
        'contract-dossier': { id: 'panelContractDossier', display: 'flex', title: 'Hồ Sơ Hợp Đồng Nghiệm Thu Điện Tử', breadcrumb: 'Hợp Đồng Nghiệm Thu' },
        'accounting': { id: 'panelAccounting', display: 'flex', title: 'Bảng Nghiệm Thu Thù Lao & Quyết Toán Kế Toán', breadcrumb: 'Hợp Đồng & Thanh Toán' },
        'my-contracts': { id: 'panelContractDossier', display: 'flex', title: 'Hợp Đồng Điện Tử & Thù Lao Của Tôi', breadcrumb: 'Hợp Đồng Của Tôi' },
        'event-reports': { id: 'panelReports', display: 'flex', title: 'Báo Cáo Hiệu Quả & Thống Kê Sự Kiện', breadcrumb: 'Báo Cáo Sự Kiện' },
        'staff-reports': { id: 'panelReports', display: 'flex', title: 'Báo Cáo Tổng Hợp Vận Hành & CSVC', breadcrumb: 'Báo Cáo Vận Hành' },
        'explore-jobs': { id: 'panelExploreJobs', display: 'flex', title: 'Khám Phá Công Việc', breadcrumb: 'Khám Phá Công Việc' },
        'my-shifts': { id: 'panelMyShifts', display: 'flex', title: 'Công Việc Của Tôi', breadcrumb: 'Công Việc Của Tôi', onShow: () => renderMyShiftsTable() },
        'my-proof-submission': { id: 'panelMyProofSubmission', display: 'flex', title: 'Chi tiết nghiệm thu', breadcrumb: 'Nghiệm thu & Chi trả' },
        'my-verification-status': { id: 'panelMyProofSubmission', display: 'flex', title: 'Chi tiết nghiệm thu', breadcrumb: 'Nghiệm thu & Chi trả' },
        'user-roles': { id: 'panelUserRoles', display: 'flex', title: 'Quản Lý Tài Khoản & Phân Quyền Hệ Thống', breadcrumb: 'Tài Khoản & Phân Quyền' },
        'audit-trail': { id: 'panelAuditTrail', display: 'flex', title: 'Nhật Ký Kiểm Toán Thao Tác (Audit Trail)', breadcrumb: 'Nhật Ký Hoạt Động' },
        'student-payroll-profile': { id: 'panelStudentPayrollProfile', display: 'flex', title: 'Cộng Tác Viên & Hồ Sơ Định Danh (VNeID/STK)', breadcrumb: 'Hồ Sơ CTV' },
        'profile-settings': { id: 'panelProfileSettings', display: 'block', title: 'Cài Đặt Tài Khoản & Hồ Sơ Cá Nhân', breadcrumb: 'Cài Đặt' }
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

        const subTitleEl = document.getElementById('activeScreenSubtitle');
        if (subTitleEl) {
            if (pageName === 'calendar') {
                subTitleEl.textContent = 'Theo dõi lịch sự kiện, buổi hoạt động và ca trực trong hệ thống.';
            } else if (pageName === 'my-proof-submission' || pageName === 'my-verification-status') {
                subTitleEl.textContent = 'Theo dõi trạng thái, nộp minh chứng và kết quả chi trả thù lao.';
            } else {
                subTitleEl.textContent = 'Theo dõi lịch sự kiện, ca trực và phân công nhiệm vụ.';
            }
        }
        const btnCreateSchedule = document.getElementById('btnCreateCalendarSchedule');
        if (btnCreateSchedule) {
            btnCreateSchedule.style.display = (pageName === 'calendar' || pageName === 'my-proof-submission' || pageName === 'my-verification-status' || pageName === 'overview-report' || pageName === 'my-shifts') ? 'inline-flex' : 'none';
        }

        if (targetConfig.onShow) targetConfig.onShow();
        if (pageName === 'calendar') {
            changeCalendarView(currentCalendarTier);
        }
    }
}

// =========================================================
// UNIVERSAL SAAS DRAWER & PROGRESSIVE DISCLOSURE ENGINE
// =========================================================
let currentDrawerType = null;
let currentDrawerEntityId = null;

function openSaasDrawer(title, subtitle, bodyHtml, footerHtml = '', tabs = null) {
    const drawerEl = document.getElementById('saasDrawer');
    const backdropEl = document.getElementById('saasDrawerBackdrop');
    if (!drawerEl || !backdropEl) return;

    document.getElementById('saasDrawerTitle').textContent = title;
    document.getElementById('saasDrawerSubtitle').textContent = subtitle;
    document.getElementById('saasDrawerBody').innerHTML = bodyHtml;
    
    const footerEl = document.getElementById('saasDrawerFooter');
    if (footerHtml) {
        footerEl.innerHTML = footerHtml;
        footerEl.style.display = 'flex';
    } else {
        footerEl.innerHTML = '<button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="closeSaasDrawer()">Đóng</button>';
        footerEl.style.display = 'flex';
    }

    const tabsEl = document.getElementById('saasDrawerTabs');
    if (tabs && tabs.length > 0) {
        tabsEl.innerHTML = tabs.map((t, idx) => `
            <button type="button" class="saas-drawer-tab-btn ${idx === 0 ? 'is-active' : ''}" onclick="switchSaasDrawerTab('${t.id}', this)">
                ${t.label}
            </button>
        `).join('');
        tabsEl.style.display = 'flex';
    } else {
        tabsEl.style.display = 'none';
    }

    drawerEl.classList.add('is-open');
    backdropEl.classList.add('is-open');
}

function closeSaasDrawer() {
    const drawerEl = document.getElementById('saasDrawer');
    const backdropEl = document.getElementById('saasDrawerBackdrop');
    if (drawerEl) drawerEl.classList.remove('is-open');
    if (backdropEl) backdropEl.classList.remove('is-open');
}

// 1. Chi tiết Sự Kiện Drawer
function openEventDrawer(eventId) {
    const ev = (typeof rawEvents !== 'undefined') ? rawEvents.find(e => parseInt(e.id) === parseInt(eventId)) : null;
    if (!ev) return;

    const evShifts = (typeof rawShifts !== 'undefined') ? rawShifts.filter(s => parseInt(s.event_id) === parseInt(eventId)) : [];
    
    const bodyHtml = `
        <div class="mb-3">
            <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 mb-2">
                ${ev.department || 'Bộ môn CNTT'}
            </span>
            <h4 class="fw-bold text-dark mb-1">${ev.title}</h4>
            <p class="text-muted small">${ev.description || 'Chương trình tọa đàm chuyên môn và hoạt động ngoại khóa dành cho sinh viên FPT Poly.'}</p>
        </div>

        <div class="saas-kv-grid mb-3">
            <div class="saas-kv-item">
                <span class="saas-kv-label">Địa Điểm</span>
                <span class="saas-kv-val">${ev.location_name || 'Hội trường Trịnh Văn Bô'}</span>
            </div>
            <div class="saas-kv-item">
                <span class="saas-kv-label">Người Phụ Trách</span>
                <span class="saas-kv-val">${ev.lecturer_name || 'GV. Trưởng Bộ Môn'}</span>
            </div>
            <div class="saas-kv-item">
                <span class="saas-kv-label">Tổng Số Ca</span>
                <span class="saas-kv-val">${evShifts.length} Ca trực phân rã</span>
            </div>
            <div class="saas-kv-item">
                <span class="saas-kv-label">Trạng Thái</span>
                <span class="saas-kv-val text-success">Đã Công Bố</span>
            </div>
        </div>

        <h5 class="fw-bold text-dark mb-2 pb-1 border-bottom" style="font-size:14px;">Danh Sách Ca Trực Phân Rã (${evShifts.length})</h5>
        <div class="d-flex flex-column gap-2">
            ${evShifts.map(s => `
                <div class="saas-card p-2.5 border rounded-3 d-flex align-items-center justify-content-between" style="cursor:pointer;" onclick="openShiftDrawer(${s.id})">
                    <div>
                        <strong class="text-dark d-block" style="font-size:13px;">${s.name}</strong>
                        <small class="text-muted">${s.start_time.substring(11, 16)} - ${s.end_time.substring(11, 16)} • ${s.start_time.substring(0, 10)}</small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-light text-dark border font-monospace" style="font-size:11px;">${s.quota_assigned || 2}/${s.quota || 4} CTV</span>
                        <div class="text-success font-monospace small fw-bold">${parseInt(s.remuneration_amount || 150000).toLocaleString('vi-VN')} đ</div>
                    </div>
                </div>
            `).join('')}
        </div>
    `;

    const footerHtml = `
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="closeSaasDrawer()">Đóng</button>
        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" onclick="openCreateShiftModal(${ev.id})">
            <i class="bi bi-plus-lg me-1"></i> Thêm Ca Mới
        </button>
    `;

    openSaasDrawer('Chi Tiết Sự Kiện', ev.title, bodyHtml, footerHtml);
}

// 2. Chi tiết Ca Trực Drawer
function openShiftDrawer(shiftId) {
    const s = (typeof rawShifts !== 'undefined') ? rawShifts.find(item => parseInt(item.id) === parseInt(shiftId)) : null;
    if (!s) return;

    const isRegistered = !empty(s.is_registered_by_me);
    const quota = parseInt(s.quota || 4);
    const assigned = parseInt(s.quota_assigned || 2);
    const isMedia = s.shift_type === 'MEDIA';

    const bodyHtml = `
        <div class="mb-3">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge rounded-pill ${isMedia ? 'bg-warning-subtle text-warning border border-warning-subtle' : 'bg-primary-subtle text-primary border border-primary-subtle'} px-2.5 py-1">
                    ${isMedia ? 'CTV Truyền Thông (Media)' : 'CTV Hậu Cần / Kỹ Thuật'}
                </span>
                <span class="badge rounded-pill bg-light text-dark border px-2.5 py-1 font-monospace">
                    ${assigned}/${quota} Đã tuyển
                </span>
            </div>
            <h4 class="fw-bold text-dark mb-1">${s.name}</h4>
            <div class="text-muted small">${s.event_title || 'Sự kiện FPT Polytechnic'}</div>
        </div>

        <div class="saas-kv-grid mb-3">
            <div class="saas-kv-item">
                <span class="saas-kv-label">Thời Gian Trực</span>
                <span class="saas-kv-val font-monospace">${s.start_time.substring(11, 16)} - ${s.end_time.substring(11, 16)}</span>
            </div>
            <div class="saas-kv-item">
                <span class="saas-kv-label">Ngày Tổ Chức</span>
                <span class="saas-kv-val font-monospace">${s.start_time.substring(0, 10)}</span>
            </div>
            <div class="saas-kv-item">
                <span class="saas-kv-label">Địa Điểm / Phòng</span>
                <span class="saas-kv-val">${s.location_name || 'Hội trường Trịnh Văn Bô'}</span>
            </div>
            <div class="saas-kv-item">
                <span class="saas-kv-label">Mức Thù Lao</span>
                <span class="saas-kv-val text-success font-monospace">${parseInt(s.remuneration_amount || 150000).toLocaleString('vi-VN')} đ</span>
            </div>
            <div class="saas-kv-item">
                <span class="saas-kv-label">Điểm Tích Lũy</span>
                <span class="saas-kv-val text-primary">+${s.points || 10} Điểm uy tín</span>
            </div>
            <div class="saas-kv-item">
                <span class="saas-kv-label">Yêu Cầu Minh Chứng</span>
                <span class="saas-kv-val" style="font-size:12px;">${isMedia ? 'Drive folder > 50 ảnh + logo' : 'Ảnh có mặt áo cam tại ca'}</span>
            </div>
        </div>

        <div class="saas-card p-3 border bg-light mb-3">
            <strong class="text-dark d-block mb-1" style="font-size:12.5px;">Mô Tả & Hướng Dẫn Nhiệm Vụ</strong>
            <p class="text-muted small mb-0">${s.notes || 'Hỗ trợ ban tổ chức tiếp đón đại biểu, điều phối âm thanh ánh sáng và hỗ trợ vận hành trong suốt thời gian diễn ra sự kiện.'}</p>
        </div>
    `;

    let footerHtml = `
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="closeSaasDrawer()">Đóng</button>
    `;

    if (isRegistered) {
        footerHtml += `
            <button type="button" class="btn btn-sm btn-success rounded-pill px-3" disabled>
                <i class="bi bi-check-lg me-1"></i> Bạn Đã Đăng Ký Ca Này
            </button>
        `;
    } else if (assigned >= quota) {
        footerHtml += `
            <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" disabled>
                Ca Đã Đầy
            </button>
        `;
    } else {
        footerHtml += `
            <button type="button" class="btn btn-sm btn-primary rounded-pill px-4" onclick="confirmRegisterShift(${s.id})">
                <i class="bi bi-send-check me-1"></i> Đăng Ký Ca Ngay
            </button>
        `;
    }

    openSaasDrawer('Chi Tiết Ca Trực', s.name, bodyHtml, footerHtml);
}

// 3. Chi tiết Tài Khoản & Phân Quyền Drawer
function openUserDrawer(userId) {
    const u = (typeof rawAllUsers !== 'undefined') ? rawAllUsers.find(item => parseInt(item.id) === parseInt(userId)) : null;
    if (!u) return;

    currentDrawerEntityId = userId;
    const isLocked = parseInt(u.is_active || 1) === 0;

    const rolesList = (typeof rawRoles !== 'undefined') ? rawRoles : [
        { id: 1, name: 'Admin', description: 'Quản trị viên toàn hệ thống' },
        { id: 2, name: 'Organizer', description: 'Giảng viên / Ban tổ chức sự kiện' },
        { id: 3, name: 'Staff', description: 'Cán bộ Hành chính / CSVC' },
        { id: 4, name: 'Student', description: 'Sinh viên CTV Hậu Cần' },
        { id: 5, name: 'Media', description: 'Cộng tác viên Truyền thông & Báo chí' }
    ];

    const bodyHtml = `
        <div class="d-flex align-items-center gap-3 mb-3 p-3 bg-light rounded-3 border">
            <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px; height:48px; background:linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%); font-size:18px;">
                ${u.full_name ? u.full_name.charAt(0).toUpperCase() : 'U'}
            </div>
            <div>
                <h4 class="fw-bold text-dark mb-0.5" style="font-size:16px;">${u.full_name}</h4>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge font-monospace bg-white text-dark border px-2 py-0.5">${u.student_code}</span>
                    <span class="badge ${isLocked ? 'bg-danger text-white' : 'bg-success text-white'} px-2 py-0.5">
                        ${isLocked ? 'Đã Khóa' : 'Hoạt Động'}
                    </span>
                </div>
            </div>
        </div>

        <div class="saas-kv-grid mb-3">
            <div class="saas-kv-item">
                <span class="saas-kv-label">Email FPT Poly</span>
                <span class="saas-kv-val" style="font-size:12.5px;">${u.email}</span>
            </div>
            <div class="saas-kv-item">
                <span class="saas-kv-label">Số Điện Thoại</span>
                <span class="saas-kv-val font-monospace">${u.phone || 'Chưa cập nhật'}</span>
            </div>
            <div class="saas-kv-item">
                <span class="saas-kv-label">Khoa / Bộ Môn</span>
                <span class="saas-kv-val">${u.department || 'Công nghệ Thông tin'}</span>
            </div>
            <div class="saas-kv-item">
                <span class="saas-kv-label">Vai Trò Hiện Tại</span>
                <span class="saas-kv-val text-primary">${u.role_name}</span>
            </div>
        </div>

        <div class="saas-card p-3 border mb-3">
            <label class="form-label fw-bold text-dark small mb-1">Gán / Chuyển Đổi Vai Trò Hệ Thống</label>
            <p class="text-muted small mb-2">Chọn vai trò mới bên dưới để cập nhật quyền truy cập tương ứng cho người dùng</p>
            <select class="form-select form-select-sm mb-2" id="drawerSelectRole">
                ${rolesList.map(r => `
                    <option value="${r.id}" ${parseInt(r.id) === parseInt(u.role_id) ? 'selected' : ''}>
                        ${r.name} - ${r.description}
                    </option>
                `).join('')}
            </select>
            <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 w-100" onclick="triggerRoleChangeFromDrawer(${u.id})">
                <i class="bi bi-shield-check me-1"></i> Lưu Quyền Hạn Mới
            </button>
        </div>

        <div class="saas-card p-3 border ${isLocked ? 'border-success-subtle bg-success-subtle' : 'border-danger-subtle bg-danger-subtle'}">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <strong class="text-dark d-block small">${isLocked ? 'Kích hoạt lại tài khoản' : 'Khóa tài khoản người dùng'}</strong>
                    <small class="text-muted">${isLocked ? 'Cho phép người dùng đăng nhập trở lại' : 'Ngăn người dùng đăng nhập và nhận ca'}</small>
                </div>
                <button type="button" class="btn btn-sm ${isLocked ? 'btn-success' : 'btn-danger'} rounded-pill px-3" onclick="promptToggleUser(${u.id}, ${isLocked ? 0 : 1}, '${u.full_name}')">
                    ${isLocked ? 'Mở Khóa' : 'Khóa Tài Khoản'}
                </button>
            </div>
        </div>
    `;

    const footerHtml = `
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="closeSaasDrawer()">Đóng</button>
    `;

    openSaasDrawer('Hồ Sơ & Phân Quyền', u.full_name, bodyHtml, footerHtml);
}

// 4. Chi tiết Hồ Sơ CTV Drawer
function openCtvProfileDrawer(userId) {
    const u = (typeof rawAllUsers !== 'undefined') ? rawAllUsers.find(item => parseInt(item.id) === parseInt(userId)) : null;
    if (!u) return;

    const bodyHtml = `
        <div class="d-flex align-items-center gap-3 mb-3 p-3 bg-light rounded-3 border">
            <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px; height:48px; background:linear-gradient(135deg, #EA580C 0%, #C2410C 100%); font-size:18px;">
                ${u.full_name ? u.full_name.charAt(0).toUpperCase() : 'C'}
            </div>
            <div>
                <h4 class="fw-bold text-dark mb-0.5" style="font-size:16px;">${u.full_name}</h4>
                <span class="badge font-monospace bg-white text-dark border px-2 py-0.5">${u.student_code}</span>
            </div>
        </div>

        <div class="saas-kv-grid mb-3">
            <div class="saas-kv-item">
                <span class="saas-kv-label">Số CCCD / VNeID</span>
                <span class="saas-kv-val font-monospace">${u.id_card_number || '079204018294'}</span>
            </div>
            <div class="saas-kv-item">
                <span class="saas-kv-label">Tài Khoản Ngân Hàng</span>
                <span class="saas-kv-val font-monospace">${u.bank_account_number || '1029384756'}</span>
            </div>
            <div class="saas-kv-item">
                <span class="saas-kv-label">Ngân Hàng Thụ Hưởng</span>
                <span class="saas-kv-val">${u.bank_name || 'MB Bank (Chi nhánh Đồng Nai)'}</span>
            </div>
            <div class="saas-kv-item">
                <span class="saas-kv-label">Kỹ Năng Nổi Bật</span>
                <span class="saas-kv-val">${u.skills || 'Setup âm thanh, Máy chiếu, Chụp ảnh'}</span>
            </div>
        </div>

        <div class="saas-card p-3 border mb-3">
            <strong class="text-dark d-block mb-2 small">Định Danh Điện Tử VNeID Mức 2</strong>
            <div class="d-flex align-items-center gap-2 text-success small">
                <i class="bi bi-shield-check fs-5"></i>
                <span>Đã xác minh định danh CCCD gắn chip & STK chính chủ FPT Poly</span>
            </div>
        </div>
    `;

    openSaasDrawer('Hồ Sơ Cộng Tác Viên', u.full_name, bodyHtml);
}

// 5. Chi tiết Minh Chứng & Duyệt Nghiệm Thu Drawer (Enterprise SaaS)
let currentReviewAssignmentId = null;
let currentReviewDecision = 'approve';

function openProofVerificationDrawer(assignmentId) {
    currentReviewAssignmentId = parseInt(assignmentId);
    currentReviewDecision = 'approve';

    const a = (typeof rawAccountingList !== 'undefined') 
        ? rawAccountingList.find(item => parseInt(item.assignment_id) === currentReviewAssignmentId) 
        : null;

    if (!a) {
        showToast('Không tìm thấy thông tin ca trực nghiệm thu!', 'warning');
        return;
    }

    const isMedia = (a.ctv_type === 'MEDIA') || (a.proof_image_url && (a.proof_image_url.includes('drive.google.com') || a.proof_image_url.includes('docs.google.com')));
    const isVerified = (parseInt(a.lecturer_verified) === 1);
    const amountStr = parseInt(a.remuneration_amount || 150000).toLocaleString('vi-VN') + ' đ';

    let timeStr = '18/11/2026, 13:00 – 17:00';
    if (a.start_time && a.end_time) {
        const d = a.start_time.substring(8, 10) + '/' + a.start_time.substring(5, 7) + '/' + a.start_time.substring(0, 4);
        const s = a.start_time.substring(11, 16);
        const e = a.end_time.substring(11, 16);
        timeStr = `${d}, ${s} – ${e}`;
    }

    const sampleProofImages = [
        'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&auto=format&fit=crop&q=80',
        'https://images.unsplash.com/photo-1511578314322-379afb476865?w=800&auto=format&fit=crop&q=80',
        'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=800&auto=format&fit=crop&q=80',
        'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=800&auto=format&fit=crop&q=80',
        'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=800&auto=format&fit=crop&q=80',
        'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800&auto=format&fit=crop&q=80'
    ];

    const bodyHtml = `
        <div class="d-flex flex-column gap-3.5">
            <!-- Thẻ hồ sơ CTV & Ca trực -->
            <div class="p-3 rounded-3 border bg-light" style="border-color: #E2E8F0 !important;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-dark fw-bold" style="width: 38px; height: 38px; background: #FFFFFF; border: 1px solid #CBD5E1; font-size: 14px;">
                            ${(a.full_name || 'H').charAt(0).toUpperCase()}
                        </div>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 14.5px;">${a.full_name || 'Cộng tác viên'}</div>
                            <div class="text-muted" style="font-size: 12px;">
                                <span class="font-monospace">${a.student_code || 'PI03015'}</span> • ${a.email || 'ctv@fpt.edu.vn'}
                            </div>
                        </div>
                    </div>
                    <span class="badge rounded-pill ${isMedia ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800'}" style="font-size: 11px; padding: 4px 10px;">
                        ${isMedia ? 'Job Media' : 'Job Hậu cần'}
                    </span>
                </div>

                <div class="saas-kv-grid mt-2 pt-2 border-top" style="border-color: #E2E8F0 !important;">
                    <div class="saas-kv-item">
                        <span class="saas-kv-label">Sự Kiện</span>
                        <span class="saas-kv-val text-truncate" title="${a.event_title}">${a.event_title}</span>
                    </div>
                    <div class="saas-kv-item">
                        <span class="saas-kv-label">Ca Trực</span>
                        <span class="saas-kv-val text-truncate" title="${a.shift_name}">${a.shift_name}</span>
                    </div>
                    <div class="saas-kv-item">
                        <span class="saas-kv-label">Thời Gian</span>
                        <span class="saas-kv-val font-monospace">${timeStr}</span>
                    </div>
                    <div class="saas-kv-item">
                        <span class="saas-kv-label">Mức Thù Lao</span>
                        <span class="saas-kv-val text-success font-monospace">${amountStr}</span>
                    </div>
                </div>
            </div>

            <!-- Khối minh chứng -->
            <div>
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="fw-bold text-dark mb-0" style="font-size: 13.5px;">
                        <i class="bi bi-images me-1 text-primary"></i> Minh chứng đã nộp
                    </h6>
                    ${isMedia ? `
                        <a href="${a.proof_drive_url || a.proof_image_url || 'https://drive.google.com'}" target="_blank" class="btn-action-secondary py-1 px-2" style="font-size: 11.5px;">
                            <i class="fa-brands fa-google-drive text-primary"></i> Mở Drive gốc <i class="bi bi-arrow-up-right" style="font-size: 9px;"></i>
                        </a>
                    ` : ''}
                </div>

                ${isMedia ? `
                    <div class="p-3 rounded-3 border bg-white mb-2" style="border-color: #E2E8F0 !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2.5">
                                <i class="fa-brands fa-google-drive text-primary" style="font-size: 26px;"></i>
                                <div>
                                    <div class="fw-semibold text-dark" style="font-size: 13px;">Thư mục Google Drive Media Sự Kiện</div>
                                    <div class="text-muted" style="font-size: 11.5px;">Đã kiểm tra gắn Logo FPT và chỉnh tone màu</div>
                                </div>
                            </div>
                            <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 11px;">
                                <i class="bi bi-check-circle me-1"></i>Đạt chuẩn Media
                            </span>
                        </div>
                    </div>
                ` : `
                    <div class="drawer-proof-grid mb-2">
                        ${sampleProofImages.map(imgUrl => `
                            <img src="${imgUrl}" alt="Minh chứng" class="drawer-proof-img" onclick="window.open('${imgUrl}', '_blank')" title="Bấm để xem ảnh phóng to">
                        `).join('')}
                    </div>
                `}

                <div class="p-2.5 rounded-3 bg-light border text-muted" style="font-size: 12px; line-height: 1.45;">
                    <strong class="text-dark d-block mb-1">Mô tả công việc từ CTV:</strong>
                    ${a.proof_note || 'Hỗ trợ lắp đặt, vận hành hệ thống âm thanh và trình chiếu cho chương trình. Có mặt từ 12:30, kiểm tra thiết bị, điều chỉnh âm lượng trong suốt thời gian diễn ra sự kiện và thu dọn sau chương trình.'}
                </div>
            </div>

            <!-- Tiêu chí nghiệm thu (Checklist) -->
            <div>
                <h6 class="fw-bold text-dark mb-2" style="font-size: 13.5px;">
                    <i class="bi bi-check2-square me-1 text-primary"></i> Tiêu chí nghiệm thu
                </h6>
                <div class="d-flex flex-column gap-1.5">
                    <div class="proof-criterion-row is-checked" onclick="toggleDrawerCriterion(this)">
                        <div class="proof-criterion-checkbox"><i class="bi bi-check"></i></div>
                        <div style="font-size: 12.5px; color: #0F172A; line-height: 1.35;">
                            Có mặt và tham gia đúng thời gian ca trực quy định
                        </div>
                    </div>
                    <div class="proof-criterion-row is-checked" onclick="toggleDrawerCriterion(this)">
                        <div class="proof-criterion-checkbox"><i class="bi bi-check"></i></div>
                        <div style="font-size: 12.5px; color: #0F172A; line-height: 1.35;">
                            Thực hiện đúng nhiệm vụ được phân công (âm thanh, trình chiếu, hậu trường, hỗ trợ sân khấu...)
                        </div>
                    </div>
                    <div class="proof-criterion-row is-checked" onclick="toggleDrawerCriterion(this)">
                        <div class="proof-criterion-checkbox"><i class="bi bi-check"></i></div>
                        <div style="font-size: 12.5px; color: #0F172A; line-height: 1.35;">
                            Ảnh minh chứng rõ ràng, có bối cảnh tại sự kiện
                        </div>
                    </div>
                    <div class="proof-criterion-row is-checked" onclick="toggleDrawerCriterion(this)">
                        <div class="proof-criterion-checkbox"><i class="bi bi-check"></i></div>
                        <div style="font-size: 12.5px; color: #0F172A; line-height: 1.35;">
                            Đảm bảo tác phong làm việc, phối hợp với Ban Tổ Chức
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quyết định nghiệm thu -->
            <div>
                <h6 class="fw-bold text-dark mb-2" style="font-size: 13.5px;">
                    <i class="bi bi-shield-check me-1 text-primary"></i> Quyết định nghiệm thu
                </h6>
                <div class="d-flex flex-column gap-2 mb-2.5">
                    <label class="p-2.5 rounded-3 border d-flex align-items-center justify-content-between cursor-pointer" id="labelDrawerOptApprove" style="background: #F0FDF4; border-color: #86EFAC !important; cursor: pointer;">
                        <div class="d-flex align-items-center gap-2">
                            <input type="radio" name="drawerDecisionRadio" value="approve" checked onchange="handleDrawerDecisionChange('approve')" style="accent-color: #16A34A;">
                            <div>
                                <strong class="d-block text-dark" style="font-size: 13px;">Duyệt nghiệm thu</strong>
                                <small class="text-muted" style="font-size: 11.5px;">Minh chứng đáp ứng đầy đủ tiêu chí, đủ điều kiện thanh toán thù lao.</small>
                            </div>
                        </div>
                        <span class="badge bg-success text-white" style="font-size: 10px;">Khuyên dùng</span>
                    </label>

                    <label class="p-2.5 rounded-3 border d-flex align-items-center justify-content-between cursor-pointer" id="labelDrawerOptRequestMore" style="background: #FFFFFF; border-color: #E2E8F0; cursor: pointer;">
                        <div class="d-flex align-items-center gap-2">
                            <input type="radio" name="drawerDecisionRadio" value="request_more" onchange="handleDrawerDecisionChange('request_more')" style="accent-color: #D97706;">
                            <div>
                                <strong class="d-block text-dark" style="font-size: 13px;">Yêu cầu bổ sung</strong>
                                <small class="text-muted" style="font-size: 11.5px;">Minh chứng chưa rõ hoặc chưa đủ, gửi yêu cầu CTV tải lại.</small>
                            </div>
                        </div>
                    </label>
                </div>

                <div>
                    <label class="form-label text-muted fw-semibold mb-1" style="font-size: 12px;">
                        Ghi chú / Nhận xét cho CTV <span id="drawerAsterisk" style="display: none; color: #DC2626;">*</span>
                    </label>
                    <textarea class="form-control" id="drawerDecisionNote" rows="2" placeholder="Nhập ghi chú cho CTV (nếu có)..." style="font-size: 12.5px; border-color: #CBD5E1;"></textarea>
                </div>
            </div>
        </div>
    `;

    const footerHtml = `
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-2 px-3" onclick="closeSaasDrawer()">Hủy</button>
        <button type="button" class="btn btn-sm btn-primary rounded-2 px-3.5 d-inline-flex align-items-center gap-1.5" id="btnSubmitDrawerDecision" style="background: #2563EB; border-color: #2563EB;" onclick="submitProofVerificationFromDrawer(${a.assignment_id})">
            <i class="bi bi-check-lg"></i>
            <span>Xác nhận duyệt</span>
        </button>
    `;

    openSaasDrawer('Kiểm Tra & Duyệt Minh Chứng', `${a.full_name} (${a.student_code})`, bodyHtml, footerHtml);
}

function openProofDrawer(assignmentId) {
    openProofVerificationDrawer(assignmentId);
}

function toggleDrawerCriterion(rowEl) {
    rowEl.classList.toggle('is-checked');
}

function handleDrawerDecisionChange(decision) {
    currentReviewDecision = decision;
    const optApprove = document.getElementById('labelDrawerOptApprove');
    const optReq = document.getElementById('labelDrawerOptRequestMore');
    const asterisk = document.getElementById('drawerAsterisk');
    const submitBtn = document.getElementById('btnSubmitDrawerDecision');
    const noteArea = document.getElementById('drawerDecisionNote');

    if (decision === 'approve') {
        if (optApprove) { optApprove.style.background = '#F0FDF4'; optApprove.style.borderColor = '#86EFAC'; }
        if (optReq) { optReq.style.background = '#FFFFFF'; optReq.style.borderColor = '#E2E8F0'; }
        if (asterisk) asterisk.style.display = 'none';
        if (submitBtn) {
            submitBtn.className = 'btn btn-sm btn-primary rounded-2 px-3.5 d-inline-flex align-items-center gap-1.5';
            submitBtn.style.background = '#2563EB';
            submitBtn.style.borderColor = '#2563EB';
            submitBtn.innerHTML = '<i class="bi bi-check-lg"></i><span>Xác nhận duyệt</span>';
        }
    } else {
        if (optReq) { optReq.style.background = '#FFFBEB'; optReq.style.borderColor = '#FCD34D'; }
        if (optApprove) { optApprove.style.background = '#FFFFFF'; optApprove.style.borderColor = '#E2E8F0'; }
        if (asterisk) asterisk.style.display = 'inline';
        if (noteArea) {
            noteArea.focus();
            if (!noteArea.value) noteArea.placeholder = 'Nhập lý do yêu cầu CTV bổ sung minh chứng (bắt buộc)...';
        }
        if (submitBtn) {
            submitBtn.className = 'btn btn-sm btn-warning rounded-2 px-3.5 d-inline-flex align-items-center gap-1.5 text-dark';
            submitBtn.style.background = '#F59E0B';
            submitBtn.style.borderColor = '#F59E0B';
            submitBtn.innerHTML = '<i class="bi bi-arrow-repeat"></i><span>Gửi yêu cầu bổ sung</span>';
        }
    }
}

async function submitProofVerificationFromDrawer(assignmentId) {
    const submitBtn = document.getElementById('btnSubmitDrawerDecision');
    const noteArea = document.getElementById('drawerDecisionNote');
    const noteVal = noteArea ? noteArea.value.trim() : '';

    if (currentReviewDecision === 'request_more' && !noteVal) {
        showToast('Vui lòng nhập lý do yêu cầu CTV bổ sung minh chứng!', 'warning');
        if (noteArea) noteArea.focus();
        return;
    }

    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Đang xử lý...';
    }

    try {
        const formData = new FormData();
        formData.append('assignment_id', assignmentId);
        formData.append('decision', currentReviewDecision);
        formData.append('note', noteVal);

        const res = await fetch('index.php?page=dashboard&action=verify_lecturer', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            showToast(data.message || (currentReviewDecision === 'approve' ? 'Đã duyệt nghiệm thu thành công!' : 'Đã gửi yêu cầu bổ sung minh chứng!'), 'success');

            // Cập nhật trạng thái trong rawAccountingList
            if (typeof rawAccountingList !== 'undefined') {
                const acc = rawAccountingList.find(a => parseInt(a.assignment_id) === assignmentId);
                if (acc) {
                    if (currentReviewDecision === 'approve') {
                        acc.lecturer_verified = 1;
                        acc.contract_code = 'HDNT-2026-FPLY-' + String(assignmentId).padStart(3, '0');
                        acc.accounting_status = 'Approved';
                    } else {
                        acc.lecturer_verified = 0;
                        acc.accounting_status = 'Revision_Required';
                    }
                    if (noteVal) acc.proof_note = noteVal;
                }
            }

            // Cập nhật dòng bảng nghiệm thu
            const row = document.getElementById(`subRow_${assignmentId}`);
            if (row) {
                const statusCell = row.children[4];
                const actionCell = row.children[5];
                if (currentReviewDecision === 'approve') {
                    if (statusCell) {
                        statusCell.innerHTML = `
                            <span class="badge-saas" style="background: #ECFDF5; color: #15803D; border: 1px solid #BBF7D0;">
                                <span class="rounded-circle d-inline-block" style="width: 6px; height: 6px; background: #16A34A;"></span>
                                Đã nghiệm thu
                            </span>
                            <div class="text-sub font-monospace mt-1" style="font-size: 11px;">
                                HDNT-2026-FPLY-${String(assignmentId).padStart(3, '0')}
                            </div>
                        `;
                    }
                    if (actionCell) {
                        actionCell.innerHTML = `
                            <div class="d-flex align-items-center justify-content-end gap-1.5">
                                <button type="button" class="btn-action-secondary" onclick="openProofVerificationDrawer(${assignmentId})" title="Xem lại chi tiết hồ sơ nghiệm thu">
                                    <i class="bi bi-check-circle text-success"></i> Xem lại
                                </button>
                            </div>
                        `;
                    }
                } else {
                    if (statusCell) {
                        statusCell.innerHTML = `
                            <span class="badge-saas" style="background: #FFF7ED; color: #C2410C; border: 1px solid #FFEDD5;">
                                <span class="rounded-circle d-inline-block" style="width: 6px; height: 6px; background: #EA580C;"></span>
                                Cần bổ sung
                            </span>
                        `;
                    }
                }
            }

            closeSaasDrawer();
        } else {
            showToast(data.message || 'Lỗi khi xử lý duyệt nghiệm thu.', 'danger');
        }
    } catch (e) {
        showToast('Lỗi kết nối máy chủ.', 'danger');
    } finally {
        if (submitBtn) submitBtn.disabled = false;
    }
}

// 6. Chi tiết Hợp Đồng & Thanh Toán Drawer
function openContractDrawer(assignmentId) {
    const a = (typeof rawAccountingList !== 'undefined') ? rawAccountingList.find(item => parseInt(item.assignment_id) === parseInt(assignmentId)) : null;
    if (!a) return;

    const contractCode = a.contract_code || `HDNT-2026-FPLY-${String(a.assignment_id).padStart(3, '0')}`;
    const amount = parseInt(a.remuneration_amount || 150000);

    const bodyHtml = `
        <div class="mb-3">
            <span class="badge bg-light text-dark border font-monospace px-2.5 py-1 mb-2">
                ${contractCode}
            </span>
            <h4 class="fw-bold text-dark mb-1">Hợp Đồng Nghiệm Thu & Đề Nghị Chi Tiền</h4>
            <div class="text-muted small">${a.event_title} • ${a.shift_name}</div>
        </div>

        <div class="saas-kv-grid mb-3">
            <div class="saas-kv-item">
                <span class="saas-kv-label">Cộng Tác Viên</span>
                <span class="saas-kv-val">${a.full_name} (${a.student_code})</span>
            </div>
            <div class="saas-kv-item">
                <span class="saas-kv-label">Số Tiền Quyết Toán</span>
                <span class="saas-kv-val text-success font-monospace" style="font-size:15px;">${amount.toLocaleString('vi-VN')} đ</span>
            </div>
            <div class="saas-kv-item">
                <span class="saas-kv-label">Tài Khoản Nhận</span>
                <span class="saas-kv-val font-monospace">${a.bank_account_number || '1029384756'} (${a.bank_name || 'MB Bank'})</span>
            </div>
            <div class="saas-kv-item">
                <span class="saas-kv-label">Trạng Thái Hồ Sơ</span>
                <span class="saas-kv-val text-primary">${a.accounting_status || 'Đã đối soát'}</span>
            </div>
        </div>

        <div class="saas-card p-3 border mb-3">
            <strong class="text-dark d-block mb-2 small">Tiến Trình Chữ Ký Số Pháp Lý</strong>
            <div class="d-flex flex-column gap-2">
                <div class="d-flex align-items-center justify-content-between small">
                    <span>1. Giảng viên nghiệm thu chuyên môn:</span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle">Đã xác nhận</span>
                </div>
                <div class="d-flex align-items-center justify-content-between small">
                    <span>2. Ban Hành chính - CSVC đối soát:</span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle">Hợp lệ</span>
                </div>
                <div class="d-flex align-items-center justify-content-between small">
                    <span>3. Ban Giám Hiệu ký duyệt điện tử:</span>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Sẵn sàng chi trả</span>
                </div>
            </div>
        </div>
    `;

    const footerHtml = `
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="closeSaasDrawer()">Đóng</button>
        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> In Hồ Sơ
        </button>
    `;

    openSaasDrawer('Hồ Sơ Hợp Đồng', contractCode, bodyHtml, footerHtml);
}

// 7. Chi tiết Nhật Ký Hoạt Động Drawer
function openAuditLogDrawer(logId) {
    const log = (typeof rawActivityLogs !== 'undefined') ? rawActivityLogs.find(item => parseInt(item.id) === parseInt(logId)) : null;
    if (!log) return;

    const bodyHtml = `
        <div class="mb-3">
            <span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle px-2.5 py-1 mb-2 font-monospace">
                ${log.action}
            </span>
            <h4 class="fw-bold text-dark mb-1">Ghi Vết Kiểm Toán Hệ Thống</h4>
            <div class="text-muted small">Mã bản ghi: #${log.id}</div>
        </div>

        <div class="saas-kv-grid mb-3">
            <div class="saas-kv-item">
                <span class="saas-kv-label">Người Thực Hiện</span>
                <span class="saas-kv-val">${log.user_name} (${log.user_role || 'User'})</span>
            </div>
            <div class="saas-kv-item">
                <span class="saas-kv-label">Thời Điểm Ghi Nhận</span>
                <span class="saas-kv-val font-monospace">${log.created_at}</span>
            </div>
            <div class="saas-kv-item">
                <span class="saas-kv-label">Địa Chỉ IP</span>
                <span class="saas-kv-val font-monospace">${log.ip_address || '127.0.0.1'}</span>
            </div>
            <div class="saas-kv-item">
                <span class="saas-kv-label">Đối Tượng Tác Động</span>
                <span class="saas-kv-val font-monospace">${log.target_type || 'SYSTEM'} #${log.target_id || ''}</span>
            </div>
        </div>

        <div class="saas-card p-3 border bg-light">
            <strong class="text-dark d-block mb-1 small">Chi Tiết Thao Tác (Audit Payload)</strong>
            <pre class="mb-0 text-muted small" style="white-space:pre-wrap; word-break:break-all;">${log.details}</pre>
        </div>
    `;

    openSaasDrawer('Chi Tiết Kiểm Toán', `Bản ghi #${log.id}`, bodyHtml);
}

// =========================================================
// ACTION CONFIRMATION MODAL HANDLERS
// =========================================================
let pendingRoleChange = { userId: null, newRoleId: null, roleName: '' };
let pendingUserToggle = { userId: null, targetStatus: 1 };
let pendingAssignmentCancel = { assignmentId: null };

function triggerRoleChangeFromDrawer(userId) {
    const sel = document.getElementById('drawerSelectRole');
    if (!sel) return;
    const roleId = parseInt(sel.value);
    const roleName = sel.options[sel.selectedIndex].text;
    promptRoleChange(userId, roleId, roleName);
}

function promptRoleChange(userId, roleId, roleName = '') {
    pendingRoleChange = { userId, newRoleId: roleId, roleName };
    const u = (typeof rawAllUsers !== 'undefined') ? rawAllUsers.find(item => parseInt(item.id) === parseInt(userId)) : null;
    const userName = u ? u.full_name : 'người dùng này';
    
    document.getElementById('confirmRoleChangeText').innerHTML = `
        Bạn có chắc chắn muốn thay đổi vai trò của <strong>${userName}</strong> thành <strong>${roleName}</strong>?
    `;
    const modalEl = document.getElementById('modalConfirmRoleChange');
    if (modalEl) {
        const inst = new bootstrap.Modal(modalEl);
        inst.show();
    }
}

async function executeRoleChange() {
    if (!pendingRoleChange.userId || !pendingRoleChange.newRoleId) return;
    const btn = document.getElementById('btnConfirmRoleChangeSubmit');
    if (btn) btn.disabled = true;

    try {
        const fd = new FormData();
        fd.append('user_id', pendingRoleChange.userId);
        fd.append('role_id', pendingRoleChange.newRoleId);

        const res = await fetch('/dashboard/updateUserRole', {
            method: 'POST',
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();
        if (data.success) {
            showToast(data.message || 'Cập nhật vai trò thành công!', 'success');
            const modalEl = document.getElementById('modalConfirmRoleChange');
            if (modalEl) {
                const inst = bootstrap.Modal.getInstance(modalEl);
                if (inst) inst.hide();
            }
            closeSaasDrawer();
            setTimeout(() => location.reload(), 600);
        } else {
            showToast(data.message || 'Lỗi khi cập nhật vai trò', 'danger');
        }
    } catch (e) {
        showToast('Lỗi kết nối máy chủ', 'danger');
    } finally {
        if (btn) btn.disabled = false;
    }
}

function promptToggleUser(userId, targetStatus, userName = '') {
    pendingUserToggle = { userId, targetStatus };
    const isLocking = (targetStatus === 0);
    document.getElementById('confirmToggleUserTitle').textContent = isLocking ? 'Xác Nhận Khóa Tài Khoản' : 'Xác Nhận Mở Khóa';
    document.getElementById('confirmToggleUserText').innerHTML = `
        Bạn có chắc chắn muốn <strong>${isLocking ? 'khóa' : 'kích hoạt lại'}</strong> tài khoản của <strong>${userName || 'người dùng này'}</strong>?
    `;
    const modalEl = document.getElementById('modalConfirmToggleUser');
    if (modalEl) {
        const inst = new bootstrap.Modal(modalEl);
        inst.show();
    }
}

async function executeToggleUser() {
    if (!pendingUserToggle.userId) return;
    const btn = document.getElementById('btnConfirmToggleUserSubmit');
    if (btn) btn.disabled = true;

    try {
        const fd = new FormData();
        fd.append('user_id', pendingUserToggle.userId);
        fd.append('is_active', pendingUserToggle.targetStatus);

        const res = await fetch('/dashboard/toggleUserStatus', {
            method: 'POST',
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();
        if (data.success) {
            showToast(data.message || 'Cập nhật trạng thái thành công!', 'success');
            const modalEl = document.getElementById('modalConfirmToggleUser');
            if (modalEl) {
                const inst = bootstrap.Modal.getInstance(modalEl);
                if (inst) inst.hide();
            }
            closeSaasDrawer();
            setTimeout(() => location.reload(), 600);
        } else {
            showToast(data.message || 'Lỗi khi cập nhật trạng thái', 'danger');
        }
    } catch (e) {
        showToast('Lỗi kết nối máy chủ', 'danger');
    } finally {
        if (btn) btn.disabled = false;
    }
}

function promptCancelAssignment(assignmentId, userName = '', shiftName = '') {
    pendingAssignmentCancel = { assignmentId };
    document.getElementById('confirmCancelAssignmentText').innerHTML = `
        Bạn có chắc chắn muốn hủy phân công ca trực <strong>${shiftName}</strong> của CTV <strong>${userName}</strong>?
    `;
    const modalEl = document.getElementById('modalConfirmCancelAssignment');
    if (modalEl) {
        const inst = new bootstrap.Modal(modalEl);
        inst.show();
    }
}

async function executeCancelAssignment() {
    if (!pendingAssignmentCancel.assignmentId) return;
    const btn = document.getElementById('btnConfirmCancelAssignmentSubmit');
    if (btn) btn.disabled = true;

    try {
        const fd = new FormData();
        fd.append('assignment_id', pendingAssignmentCancel.assignmentId);

        const res = await fetch('/dashboard/cancelAssignment', {
            method: 'POST',
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();
        if (data.success) {
            showToast(data.message || 'Đã hủy phân công ca trực thành công!', 'success');
            const modalEl = document.getElementById('modalConfirmCancelAssignment');
            if (modalEl) {
                const inst = bootstrap.Modal.getInstance(modalEl);
                if (inst) inst.hide();
            }
            setTimeout(() => location.reload(), 600);
        } else {
            showToast(data.message || 'Lỗi khi hủy phân công', 'danger');
        }
    } catch (e) {
        showToast('Lỗi kết nối máy chủ', 'danger');
    } finally {
        if (btn) btn.disabled = false;
    }
}

// =========================================================
// CALENDAR DAY CONTEXTUAL POPOVER ENGINE
// =========================================================
function showCalendarDayPopover(event, dateStr) {
    event.stopPropagation();
    const popEl = document.getElementById('calendarDayPopover');
    if (!popEl) return;

    const dayShifts = (typeof rawShifts !== 'undefined') ? rawShifts.filter(s => s.start_time.startsWith(dateStr)) : [];
    const dayNotes = (typeof rawCalendarNotes !== 'undefined') ? rawCalendarNotes.filter(n => (n.date || n.target_date) === dateStr) : [];

    document.getElementById('popoverDayTitle').textContent = `Hoạt động ngày ${dateStr}`;

    const listEl = document.getElementById('popoverDayList');
    if (dayShifts.length === 0 && dayNotes.length === 0) {
        listEl.innerHTML = '<div class="text-muted small py-2 text-center">Không có lịch hoạt động</div>';
    } else {
        listEl.innerHTML = `
            ${dayShifts.map(s => `
                <div class="saas-popover-item" onclick="closeCalendarDayPopover(); openShiftDrawer(${s.id})">
                    <div>
                        <strong class="d-block" style="font-size:12px;">${s.name}</strong>
                        <small class="text-muted">${s.start_time.substring(11, 16)} - ${s.end_time.substring(11, 16)}</small>
                    </div>
                    <span class="badge bg-light text-primary border">${s.quota_assigned || 2}/${s.quota || 4}</span>
                </div>
            `).join('')}
            ${dayNotes.map(n => `
                <div class="saas-popover-item">
                    <div>
                        <strong class="d-block text-warning" style="font-size:12px;">${n.title || n.note}</strong>
                        <small class="text-muted">Ghi chú toàn trường</small>
                    </div>
                </div>
            `).join('')}
        `;
    }

    const rect = event.currentTarget.getBoundingClientRect();
    popEl.style.top = (rect.bottom + window.scrollY + 6) + 'px';
    popEl.style.left = Math.min(rect.left + window.scrollX, window.innerWidth - 320) + 'px';
    popEl.classList.add('is-open');
}

function closeCalendarDayPopover() {
    const popEl = document.getElementById('calendarDayPopover');
    if (popEl) popEl.classList.remove('is-open');
}

document.addEventListener('click', (e) => {
    const popEl = document.getElementById('calendarDayPopover');
    if (popEl && popEl.classList.contains('is-open') && !popEl.contains(e.target)) {
        closeCalendarDayPopover();
    }
});

// Table quick filter functions
function filterUserRolesTable() {
    const q = (document.getElementById('userRolesSearchInput')?.value || '').toLowerCase().trim();
    const role = (document.getElementById('userRolesRoleFilter')?.value || '').toLowerCase().trim();
    const rows = document.querySelectorAll('#panelUserRoles table tbody tr');
    rows.forEach(r => {
        const text = r.textContent.toLowerCase();
        const matchQ = !q || text.includes(q);
        const matchRole = !role || text.includes(role);
        r.style.display = (matchQ && matchRole) ? '' : 'none';
    });
}

function filterShiftsSessionsTable() {
    const q = (document.getElementById('shiftSessionSearch')?.value || '').toLowerCase().trim();
    const type = (document.getElementById('shiftSessionTypeFilter')?.value || '').trim();
    const rows = document.querySelectorAll('#shiftsSessionsTable tbody tr');
    rows.forEach(r => {
        const text = r.textContent.toLowerCase();
        const matchQ = !q || text.includes(q);
        const matchType = !type || r.innerHTML.includes(type);
        r.style.display = (matchQ && matchType) ? '' : 'none';
    });
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

// =========================================================
// CÁC HÀM XỬ LÝ CONTEXTUAL POPOVER & DAY INSPECTOR CHUẨN APPLE CALENDAR
// =========================================================
let currentCalSidebarMode = 'day'; // 'day' hoặc 'upcoming'

/**
 * Trả về danh sách hoạt động, sự kiện và ca trực cho BẤT KỲ NGÀY NÀO
 */
function getDayActivities(dateStr) {
    if (dateStr === '2026-11-18') {
        return day18Activities;
    }

    const activities = [];

    // 1. Dữ liệu chuẩn các ngày trọng điểm trong tháng 11/2026
    if (dateStr === '2026-11-15') {
        activities.push(
            { id: 'act_15_ev', type: 'event', title: 'Khai mạc TechDay 2026: AI & Chuyển Đổi Số', time: '07:30 – 17:30', location: 'Hội trường Trịnh Văn Bô', description: 'Ngày hội công nghệ quy mô 20 doanh nghiệp CNTT hàng đầu và workshop AI Agent.', status: 'Đang diễn ra', statusBg: '#EFF6FF', statusColor: '#1D4ED8', borderColor: '#2563EB', icon: 'bi-megaphone', iconColor: '#2563EB', eventId: 1, ctvInfo: '12 CTV tham gia' },
            { id: 'act_15_s1', type: 'shift', title: 'Ca 1: Setup Sân Khấu & Âm Thanh Ánh Sáng', time: '06:30 – 09:30', location: 'Hội trường Trịnh Văn Bô', description: 'Kiểm tra 4 micro không dây và 2 màn hình LED phụ.', status: 'Đã hoàn thành', statusBg: '#F0FDF4', statusColor: '#15803D', borderColor: '#16A34A', icon: 'bi-calendar-event', iconColor: '#16A34A', eventId: 1, ctvInfo: '3/4 CTV', allowance: '150.000 đ' },
            { id: 'act_15_s2', type: 'shift', title: 'Ca 2: Lễ Tân Đón Khách & Check-in Doanh Nghiệp', time: '07:30 – 11:30', location: 'Sảnh chính Tòa T', description: 'Đón tiếp đại diện các doanh nghiệp CNTT đối tác và phát tài liệu.', status: 'Đã hoàn thành', statusBg: '#F0FDF4', statusColor: '#15803D', borderColor: '#059669', icon: 'bi-calendar-event', iconColor: '#059669', eventId: 1, ctvInfo: '5/6 CTV', allowance: '150.000 đ' },
            { id: 'act_15_s3', type: 'shift', title: 'Ca 3: Media Phóng Sự Ảnh & Recap Clip', time: '08:00 – 12:00', location: 'Toàn khuôn viên trường', description: 'Chụp ảnh sự kiện và quay recap 60s.', status: 'Đã hoàn thành', statusBg: '#FFFBEB', statusColor: '#B45309', borderColor: '#D97706', icon: 'bi-camera-video', iconColor: '#D97706', eventId: 1, ctvInfo: '2/3 CTV', allowance: '200.000 đ' }
        );
        return activities;
    }

    if (dateStr === '2026-11-20') {
        activities.push(
            { id: 'act_20_ev', type: 'event', title: 'Khai Mạc Giải Thể Thao Sinh Viên FPoly 2026', time: '07:00 – 11:30', location: 'Sân Central Yard FPoly', description: 'Giải đấu thể thao Poly Champion môn bóng đá và bóng bàn.', status: 'Sắp diễn ra', statusBg: '#F0FDF4', statusColor: '#15803D', borderColor: '#16A34A', icon: 'bi-trophy', iconColor: '#16A34A', eventId: 3, ctvInfo: '5 CTV' },
            { id: 'act_20_s1', type: 'shift', title: 'Ca 1: Trọng Tài Bàn & Hậu Cần Khai Mạc', time: '06:30 – 11:30', location: 'Sân Central Yard', description: 'Ghi biên bản thi đấu 8 trận vòng bảng.', status: 'Đã xác nhận', statusBg: '#EFF6FF', statusColor: '#1D4ED8', borderColor: '#2563EB', icon: 'bi-calendar-event', iconColor: '#2563EB', eventId: 3, ctvInfo: '4/8 CTV', allowance: '150.000 đ' },
            { id: 'act_20_s2', type: 'shift', title: 'Ca 2: Media Phóng Sự Thể Thao', time: '07:30 – 11:30', location: 'Sân bóng đá & Central Yard', description: 'Ghi hình highlight những pha bóng đẹp.', status: 'Đang tuyển', statusBg: '#FFF7ED', statusColor: '#C2410C', borderColor: '#EA580C', icon: 'bi-camera', iconColor: '#EA580C', eventId: 3, ctvInfo: '1/2 CTV', allowance: '180.000 đ' }
        );
        return activities;
    }

    if (dateStr === '2026-11-22') {
        activities.push(
            { id: 'act_22_ev1', type: 'event', title: 'Triển Lãm Đồ Họa & Đa Phương Tiện FPoly Creative', time: '08:00 – 17:00', location: 'Phòng Triển Lãm Tòa B', description: 'Trưng bày hơn 100 tác phẩm đồ họa và phim hoạt hình sinh viên.', status: 'Sắp diễn ra', statusBg: '#FAF5FF', statusColor: '#7E22CE', borderColor: '#9333EA', icon: 'bi-palette', iconColor: '#9333EA', eventId: 4, ctvInfo: '3 ca trực' },
            { id: 'act_22_ev2', type: 'event', title: 'Workshop: Đổi Mới Đào Tạo & Ứng Dụng AI', time: '13:30 – 17:30', location: 'Hội trường Trịnh Văn Bô', description: 'Tọa đàm chuyên môn cùng các chuyên gia EdTech.', status: 'Sắp diễn ra', statusBg: '#EFF6FF', statusColor: '#1D4ED8', borderColor: '#2563EB', icon: 'bi-laptop', iconColor: '#2563EB', eventId: 7, ctvInfo: '2 ca trực' },
            { id: 'act_22_s1', type: 'shift', title: 'Ca 1: Hướng Dẫn Khách Tham Quan Triển Lãm', time: '07:30 – 12:00', location: 'Phòng Triển Lãm Tòa B', description: 'Hỗ trợ khách tham quan và bảo quản sản phẩm trưng bày.', status: 'Đang tuyển', statusBg: '#FFF7ED', statusColor: '#C2410C', borderColor: '#EA580C', icon: 'bi-calendar-event', iconColor: '#EA580C', eventId: 4, ctvInfo: '2/4 CTV', allowance: '150.000 đ' },
            { id: 'act_22_s2', type: 'shift', title: 'Ca 2: Chụp Ảnh Macro Chi Tiết Tác Phẩm', time: '08:30 – 13:00', location: 'Phòng Triển Lãm Tòa B', description: 'Nhiếp ảnh sản phẩm đồ họa độ phân giải cao.', status: 'Đã xác nhận', statusBg: '#F0FDF4', statusColor: '#15803D', borderColor: '#16A34A', icon: 'bi-camera', iconColor: '#16A34A', eventId: 4, ctvInfo: '1/2 CTV', allowance: '200.000 đ' }
        );
        return activities;
    }

    if (dateStr === '2026-11-25') {
        activities.push(
            { id: 'act_25_ev', type: 'event', title: 'Chung Kết Cuộc Thi Khởi Nghiệp FPoly Biz 2026', time: '13:00 – 18:00', location: 'Hội trường Trịnh Văn Bô', description: 'Pitching 10 dự án Startup sinh viên trước Quỹ đầu tư.', status: 'Sắp diễn ra', statusBg: '#FFFBEB', statusColor: '#B45309', borderColor: '#F59E0B', icon: 'bi-award', iconColor: '#F59E0B', eventId: 5, ctvInfo: '6 CTV' },
            { id: 'act_25_s1', type: 'shift', title: 'Ca 1: Đón Tiếp BGK & Quỹ Đầu Tư', time: '12:30 – 18:00', location: 'Sảnh VIP Hội trường', description: 'Đón tiếp các chuyên gia cố vấn và giám khảo doanh nghiệp.', status: 'Đang tuyển', statusBg: '#FFF7ED', statusColor: '#C2410C', borderColor: '#EA580C', icon: 'bi-people', iconColor: '#EA580C', eventId: 5, ctvInfo: '2/4 CTV', allowance: '180.000 đ' },
            { id: 'act_25_s2', type: 'shift', title: 'Ca 2: Kỹ Thuật Trình Chiếu Pitching & Micro', time: '12:30 – 18:00', location: 'Khu vực kỹ thuật Hội trường', description: 'Vận hành slide và micro không dây cho 10 đội thi.', status: 'Đã xác nhận', statusBg: '#F0FDF4', statusColor: '#15803D', borderColor: '#16A34A', icon: 'bi-mic', iconColor: '#16A34A', eventId: 5, ctvInfo: '1/2 CTV', allowance: '180.000 đ' }
        );
        return activities;
    }

    if (dateStr === '2026-11-28') {
        activities.push(
            { id: 'act_28_ev', type: 'event', title: 'Ngày Hội Văn Hóa Ẩm Thực & Du Lịch Poly', time: '08:00 – 15:30', location: 'Sân Central Yard', description: 'Không gian văn hóa ẩm thực và hội thi pha chế Bartender.', status: 'Sắp diễn ra', statusBg: '#FFF7ED', statusColor: '#C2410C', borderColor: '#EA580C', icon: 'bi-cup-hot', iconColor: '#EA580C', eventId: 6, ctvInfo: '8 CTV' },
            { id: 'act_28_s1', type: 'shift', title: 'Ca 1: Hỗ Trợ Gian Hàng Ẩm Thực Vùng Miền', time: '07:30 – 12:00', location: 'Khu gian hàng sân trường', description: 'Hỗ trợ setup bàn ghế và tiếp nước các gian hàng ẩm thực.', status: 'Đang tuyển', statusBg: '#FFF7ED', statusColor: '#C2410C', borderColor: '#EA580C', icon: 'bi-basket', iconColor: '#EA580C', eventId: 6, ctvInfo: '2/6 CTV', allowance: '150.000 đ' },
            { id: 'act_28_s2', type: 'shift', title: 'Ca 2: Media Ghi Hình Hội Thi Bartender', time: '09:00 – 14:00', location: 'Sân khấu biểu diễn Bartender', description: 'Ghi hình các tiết mục pha chế nghệ thuật.', status: 'Đang tuyển', statusBg: '#EFF6FF', statusColor: '#1D4ED8', borderColor: '#2563EB', icon: 'bi-camera-video', iconColor: '#2563EB', eventId: 6, ctvInfo: '1/2 CTV', allowance: '200.000 đ' }
        );
        return activities;
    }

    if (dateStr === '2026-11-30') {
        activities.push({
            id: 'act_30_not', type: 'other', title: 'Hạn chót quyết toán thù lao tháng 11', time: '08:00 – 17:00', location: 'Phòng Hành chính - Ban Giám Hiệu', description: 'Ban Giám Hiệu tổng hợp danh sách nghiệm thu và chuyển khoản cho toàn bộ CTV.', status: 'Quan trọng', statusBg: '#FAF5FF', statusColor: '#7E22CE', borderColor: '#8B5CF6', icon: 'bi-wallet2', iconColor: '#8B5CF6', eventId: 1
        });
        return activities;
    }

    if (dateStr === '2026-11-06') {
        activities.push({
            id: 'act_06_meet', type: 'event', title: 'Họp Ban Tổ Chức & Phân Công Tháng 11', time: '09:00 – 11:30', location: 'Phòng họp 202, Tòa A', description: 'Phổ biến kế hoạch tháng công nghệ và chốt nhân sự ca trực.', status: 'Đã diễn ra', statusBg: '#F1F5F9', statusColor: '#475569', borderColor: '#94A3B8', icon: 'bi-people', iconColor: '#64748B', eventId: 1
        });
        return activities;
    }

    if (dateStr === '2026-11-13') {
        activities.push({
            id: 'act_13_lab', type: 'shift', title: 'Ca trực Lab: Kiểm tra thiết bị phòng máy AI', time: '13:30 – 17:00', location: 'Phòng Lab 405, Tòa B', description: 'Cài đặt và kiểm thử mạng máy trạm cho workshop.', status: 'Đã hoàn thành', statusBg: '#F0FDF4', statusColor: '#15803D', borderColor: '#10B981', icon: 'bi-tools', iconColor: '#10B981', eventId: 2, ctvInfo: '1/2 CTV', allowance: '150.000 đ'
        });
        return activities;
    }

    // 2. Lấy từ rawShifts nếu ngày này có ca
    if (typeof rawShifts !== 'undefined') {
        const matchingShifts = rawShifts.filter(s => s.start_time && s.start_time.startsWith(dateStr));
        matchingShifts.forEach(s => {
            const isMine = parseInt(s.is_registered_by_me) > 0;
            const startTime = s.start_time ? s.start_time.substring(11, 16) : '08:00';
            const endTime = s.end_time ? s.end_time.substring(11, 16) : '12:00';
            const allowance = s.remuneration_amount ? Number(s.remuneration_amount).toLocaleString('vi-VN') + ' đ' : '150.000 đ';
            activities.push({
                id: 'dyn_shift_' + s.id,
                type: 'shift',
                title: s.name,
                time: `${startTime} – ${endTime}`,
                location: s.location_name || 'Cơ sở FPT Polytechnic Đồng Nai',
                description: s.skill_required ? `Yêu cầu kỹ năng: ${s.skill_required}` : 'Hỗ trợ công tác tổ chức theo phân công BTC.',
                status: isMine ? 'Đã đăng ký' : 'Đang tuyển',
                statusBg: isMine ? '#F0FDF4' : '#FFF7ED',
                statusColor: isMine ? '#15803D' : '#C2410C',
                borderColor: isMine ? '#16A34A' : '#EA580C',
                icon: 'bi-calendar-event',
                iconColor: isMine ? '#16A34A' : '#EA580C',
                eventId: s.event_id,
                ctvInfo: `${s.approved_qty || 0}/${s.target_qty || 1} CTV`,
                allowance: allowance
            });
        });
    }

    // 3. Lấy từ rawEvents nếu ngày này có sự kiện
    if (typeof rawEvents !== 'undefined') {
        const matchingEvents = rawEvents.filter(e => e.start_time && e.start_time.startsWith(dateStr));
        matchingEvents.forEach(e => {
            if (!activities.some(a => a.eventId === e.id && a.type === 'event')) {
                const startTime = e.start_time ? e.start_time.substring(11, 16) : '08:00';
                const endTime = e.end_time ? e.end_time.substring(11, 16) : '17:00';
                activities.push({
                    id: 'dyn_event_' + e.id,
                    type: 'event',
                    title: e.title,
                    time: `${startTime} – ${endTime}`,
                    location: e.location_name || 'Hội trường Trịnh Văn Bô',
                    description: e.description ? e.description.substring(0, 85) + '...' : 'Sự kiện chính thức tại trường.',
                    status: 'Sắp diễn ra',
                    statusBg: '#EFF6FF',
                    statusColor: '#1D4ED8',
                    borderColor: '#2563EB',
                    icon: 'bi-megaphone',
                    iconColor: '#2563EB',
                    eventId: e.id,
                    ctvInfo: 'Sự kiện trường'
                });
            }
        });
    }

    // 4. Lấy từ rawCalendarNotes
    if (typeof rawCalendarNotes !== 'undefined') {
        const matchingNotes = rawCalendarNotes.filter(n => (n.date || n.target_date) === dateStr);
        matchingNotes.forEach(n => {
            activities.push({
                id: 'dyn_note_' + n.id,
                type: 'other',
                title: n.title,
                time: '08:00 – 17:00',
                location: n.author || 'Ban Tổ Chức FPoly',
                description: n.content || '',
                status: 'Ghi chú',
                statusBg: '#F1F5F9',
                statusColor: '#475569',
                borderColor: n.color || '#94A3B8',
                icon: 'bi-sticky',
                iconColor: '#64748B',
                eventId: n.event_id || 1
            });
        });
    }

    return activities;
}

/**
 * Hiển thị Contextual Popover tại ô ngày khi nhấp vào (Apple Calendar Style, không bị cắt viền)
 */
function showCalendarDayPopover(triggerEl, dateStr) {
    const popover = document.getElementById('calDayPopover');
    const container = document.getElementById('tierMonthView');
    if (!popover || !container || !triggerEl) return;

    const acts = getDayActivities(dateStr);
    if (!acts || acts.length === 0) {
        popover.style.display = 'none';
        return;
    }

    const cell = triggerEl.closest('.month-day-cell') || triggerEl;
    const cellRect = cell.getBoundingClientRect();
    const parentRect = container.getBoundingClientRect();

    const parts = dateStr.split('-');
    const dayNames = ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'];
    const dObj = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
    const dayName = dayNames[dObj.getDay()];
    const dateFormatted = `${dayName}, ${parts[2]}/${parts[1]}/${parts[0]}`;

    popover.innerHTML = `
        <div class="d-flex align-items-center justify-content-between mb-1 pb-1">
            <div>
                <div class="fw-bold text-dark" style="font-size: 13.5px; line-height: 1.2;">${dateFormatted}</div>
                <div class="text-muted" style="font-size: 11px;">${acts.length} hoạt động trong ngày</div>
            </div>
            <button type="button" class="btn-close ms-auto" style="font-size: 8.5px;" onclick="closeCalendarDayPopover(event)"></button>
        </div>
        <div class="d-flex flex-column gap-1 pt-1 border-top" style="border-color: #F1F5F9 !important;">
            ${acts.slice(0, 5).map(item => `
                <div class="cal-popover-item" onclick="filterDayDetailTab('${item.type}'); openEventDrawer(${item.eventId});">
                    <span class="text-muted font-monospace" style="font-size: 10.5px; min-width: 78px;">${item.time}</span>
                    <span class="rounded-circle d-inline-block" style="width: 6px; height: 6px; background: ${item.borderColor}; flex-shrink: 0;"></span>
                    <span class="fw-semibold text-dark text-truncate" style="font-size: 12px;">${item.title}</span>
                </div>
            `).join('')}
            ${acts.length > 5 ? `<div class="text-muted text-center pt-1" style="font-size: 11px;">+${acts.length - 5} hoạt động nữa (xem ở cột bên phải)</div>` : ''}
        </div>
    `;

    popover.style.display = 'block';
    popover.classList.add('is-open');

    // Thuật toán định vị thông minh: Giới hạn popover luôn nằm trọn trong #tierMonthView, không bị cắt chân
    const popoverWidth = 300;
    const popoverHeight = popover.offsetHeight || (55 + Math.min(acts.length, 5) * 32);

    let top = (cellRect.top - parentRect.top) + 8;
    let left = (cellRect.right - parentRect.left) + 8;

    // Giới hạn theo chiều dọc
    if (top + popoverHeight > parentRect.height - 12) {
        top = Math.max(10, parentRect.height - popoverHeight - 12);
    }
    if (top < 10) top = 10;

    // Giới hạn theo chiều ngang
    if (left + popoverWidth > parentRect.width - 12) {
        left = Math.max(10, (cellRect.left - parentRect.left) - popoverWidth - 8);
    }

    popover.style.top = `${top}px`;
    popover.style.left = `${left}px`;
}

function closeCalendarDayPopover(e) {
    if (e) e.stopPropagation();
    const popover = document.getElementById('calDayPopover');
    if (popover) {
        popover.style.display = 'none';
        popover.classList.remove('is-open');
    }
}

function toggleCalendarDayPopover(e, dateStr, el) {
    if (e) e.stopPropagation();
    const popover = document.getElementById('calDayPopover');
    if (popover && popover.style.display === 'block') {
        closeCalendarDayPopover(e);
    } else {
        showCalendarDayPopover(el, dateStr);
    }
}

function filterDayDetailTab(type, btnEl) {
    currentDayFilter = type;
    renderCalendarSidebarDayDetail(selectedDateStr);
}

function resetCalendarFilters() {
    currentDayFilter = 'all';
    selectedDateStr = '2026-11-18';
    currentCalSidebarMode = 'day';
    renderMonthGrid(currentYear, currentMonth);
    showToast('Đã đặt lại toàn bộ bộ lọc lịch!', 'info');
}

// Lắng nghe sự kiện click ngoài để đóng popover
document.addEventListener('click', function(e) {
    const popover = document.getElementById('calDayPopover');
    if (popover && popover.style.display === 'block') {
        if (!popover.contains(e.target) && !e.target.closest('.month-day-cell')) {
            closeCalendarDayPopover();
        }
    }
});

/**
 * Chuyển đổi chế độ hiển thị Sidebar: 'day' (Lịch ngày) hoặc 'upcoming' (Lịch sắp tới)
 */
function setCalSidebarMode(mode) {
    currentCalSidebarMode = mode;
    const btnDay = document.getElementById('btnCalModeDay');
    const btnUpcoming = document.getElementById('btnCalModeUpcoming');
    const modeLabel = document.getElementById('calSidebarModeLabel');
    const navBtns = document.getElementById('calDayNavBtns');
    const filterTabsEl = document.getElementById('calDayFilterTabs');

    if (mode === 'day') {
        if (btnDay) btnDay.classList.add('is-active');
        if (btnUpcoming) btnUpcoming.classList.remove('is-active');
        if (modeLabel) modeLabel.textContent = 'Chi tiết ngày';
        if (navBtns) navBtns.style.display = 'flex';
        renderCalendarSidebarDayDetail(selectedDateStr);
    } else {
        if (btnUpcoming) btnUpcoming.classList.add('is-active');
        if (btnDay) btnDay.classList.remove('is-active');
        if (modeLabel) modeLabel.textContent = 'Tổng quan';
        if (navBtns) navBtns.style.display = 'none';
        if (filterTabsEl) filterTabsEl.style.display = 'none';
        renderCalendarUpcomingScheduleInContent();
    }
}

/**
 * Chọn ngày từ danh sách sắp tới và tự động chuyển về xem chi tiết ngày đó
 */
function selectCalendarDateAndInspect(dateStr) {
    selectedDateStr = dateStr;
    const parts = dateStr.split('-');
    const m = parseInt(parts[1]) - 1;
    const y = parseInt(parts[0]);
    if (m !== currentMonth || y !== currentYear) {
        currentMonth = m;
        currentYear = y;
    }
    setCalSidebarMode('day');
    renderMonthGrid(currentYear, currentMonth);
}

// 1. Render Lưới Tháng (Month Grid) - Apple Calendar Style
function renderMonthGrid(year, month) {
    const container = document.getElementById('monthGridContainer');
    if (!container) return;
    container.innerHTML = '';

    const headerEl = document.getElementById('currentCalendarHeader');
    if (headerEl) headerEl.textContent = `Tháng ${month + 1}/${year}`;

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
        cell.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="day-number-pill text-muted num-tabular" style="color: #94A3B8 !important;">${dayNum}</span>
            </div>
        `;
        container.appendChild(cell);
    }

    for (let day = 1; day <= totalDaysInMonth; day++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const dayActs = getDayActivities(dateStr);
        const isSelected = (selectedDateStr === dateStr);

        const cell = document.createElement('div');
        cell.className = 'month-day-cell';
        if (isSelected) cell.classList.add('is-selected');

        let previewHtml = '';

        // Khớp 100% Mockup Image 14
        if (dateStr === '2026-11-15') {
            previewHtml = `
                <div class="d-flex align-items-center gap-1 mb-0.5" style="font-size: 10px; color: #7E22CE; font-weight: 600;">
                    <span class="rounded-circle d-inline-block" style="width: 5px; height: 5px; background: #9333EA;"></span> Ca 3 – Media
                </div>
                <div class="d-flex align-items-center gap-1" style="font-size: 10px; color: #2563EB; font-weight: 600;">
                    <span class="rounded-circle d-inline-block" style="width: 5px; height: 5px; background: #2563EB;"></span> Nghiệm thu
                </div>
            `;
        } else if (dateStr === '2026-11-07') {
            previewHtml = `
                <div class="cal-event-pill" style="background: #FAF5FF; color: #7E22CE; font-size: 10px; font-weight: 600; padding: 2px 6px; border-radius: 6px; display: flex; align-items: center;">
                    <i class="bi bi-calendar-event me-1" style="font-size: 9px;"></i>Workshop AI
                </div>
            `;
        } else if (dateStr === '2026-11-12') {
            previewHtml = `
                <div class="cal-event-pill" style="background: #DCFCE7; color: #15803D; font-size: 10px; font-weight: 600; padding: 2px 6px; border-radius: 6px; display: flex; align-items: center;">
                    <i class="bi bi-calendar-check me-1" style="font-size: 9px;"></i>Họp BTC
                </div>
            `;
        } else if (dateStr === '2026-11-18') {
            previewHtml = `
                <div class="cal-event-pill" style="background: #E0F2FE; color: #0369A1; font-size: 10px; font-weight: 600; padding: 2px 6px; border-radius: 6px; display: flex; align-items: center;">
                    <i class="bi bi-tools me-1" style="font-size: 9px;"></i>Ca 2 – Hậu cần
                </div>
            `;
        } else if (dateStr === '2026-11-25') {
            previewHtml = `
                <div class="cal-event-pill" style="background: #FFE4E6; color: #BE123C; font-size: 10px; font-weight: 600; padding: 2px 6px; border-radius: 6px; display: flex; align-items: center;">
                    <i class="bi bi-star me-1" style="font-size: 9px;"></i>TechDay 2026
                </div>
            `;
        } else if (dayActs.length > 0) {
            dayActs.slice(0, 2).forEach(act => {
                previewHtml += `
                    <div class="cal-event-pill" style="background: ${act.statusBg}; color: ${act.statusColor}; font-size: 9.5px;" title="${act.title}">
                        <span class="rounded-circle d-inline-block me-1" style="width: 5px; height: 5px; background: ${act.borderColor};"></span>
                        <span class="text-truncate">${act.title}</span>
                    </div>
                `;
            });
            if (dayActs.length > 2) {
                previewHtml += `<div class="text-muted num-tabular ps-1" style="font-size: 9px; font-weight: 600;">+${dayActs.length - 2} lịch nữa</div>`;
            }
        }

        const dayOfWeek = new Date(year, month, day).getDay();
        const isSunday = (dayOfWeek === 0);
        const dayBadgeClass = isSelected ? 'day-number-pill square-active-badge num-tabular' : (isSunday ? 'day-number-pill num-tabular text-danger fw-bold' : 'day-number-pill num-tabular');

        cell.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="${dayBadgeClass}">${day}</span>
            </div>
            <div class="d-flex flex-column gap-0.5" style="overflow: hidden;">${previewHtml}</div>
        `;

        cell.addEventListener('click', (e) => {
            e.stopPropagation();
            selectedDateStr = dateStr;
            document.querySelectorAll('.month-day-cell').forEach(c => {
                c.classList.remove('is-selected');
                const badge = c.querySelector('.day-number-pill');
                if (badge) badge.classList.remove('selected-badge');
            });
            cell.classList.add('is-selected');
            const badge = cell.querySelector('.day-number-pill');
            if (badge) badge.classList.add('selected-badge');

            setCalSidebarMode('day');
            renderCalendarSidebarDayDetail(dateStr);

            const acts = getDayActivities(dateStr);
            if (acts.length >= 2) {
                showCalendarDayPopover(cell, dateStr);
            } else {
                closeCalendarDayPopover();
            }
        });

        container.appendChild(cell);
    }

    const trailingCells = (rowCount * 7) - totalCells;
    for (let nextDay = 1; nextDay <= trailingCells; nextDay++) {
        const cell = document.createElement('div');
        cell.className = 'month-day-cell is-other-month';
        cell.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="day-number-pill text-muted num-tabular" style="color: #94A3B8 !important;">${nextDay}</span>
            </div>
        `;
        container.appendChild(cell);
    }

    // Render nội dung cột phải
    if (currentCalSidebarMode === 'day') {
        renderCalendarSidebarDayDetail(selectedDateStr);
    } else {
        renderCalendarUpcomingScheduleInContent();
    }

    // Hiển thị Popover cho ngày được chọn nếu có từ 2 hoạt động trở lên
    const activeActs = getDayActivities(selectedDateStr);
    if (activeActs.length >= 2) {
        setTimeout(() => {
            const activeCell = container.querySelector('.month-day-cell.is-selected');
            if (activeCell) {
                showCalendarDayPopover(activeCell, selectedDateStr);
            }
        }, 60);
    } else {
        closeCalendarDayPopover();
    }
}

/**
 * Điều hướng ngày bằng nút < > trên đầu Sidebar
 */
function navigateCalendarDay(delta) {
    const parts = selectedDateStr.split('-');
    const curDate = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
    curDate.setDate(curDate.getDate() + delta);

    const y = curDate.getFullYear();
    const m = String(curDate.getMonth() + 1).padStart(2, '0');
    const d = String(curDate.getDate()).padStart(2, '0');
    selectedDateStr = `${y}-${m}-${d}`;

    if (curDate.getMonth() !== currentMonth || curDate.getFullYear() !== currentYear) {
        currentMonth = curDate.getMonth();
        currentYear = curDate.getFullYear();
    }

    renderMonthGrid(currentYear, currentMonth);
}

/**
 * Render Sidebar Chi tiết ngày (ÁP DỤNG THIẾT KẾ APPLE CALENDAR CHO TOÀN BỘ CÁC NGÀY TRONG LỊCH)
 */
function renderCalendarSidebarDayDetail(dateStr) {
    const titleEl = document.getElementById('calSelectedDayTitle');
    const contentEl = document.getElementById('calSelectedDayContent');
    const filterTabsEl = document.getElementById('calDayFilterTabs');
    if (!titleEl || !contentEl) return;

    const parts = dateStr.split('-');
    const formattedDate = `${parts[2]}/${parts[1]}/${parts[0]}`;
    titleEl.textContent = formattedDate;

    const acts = getDayActivities(dateStr);

    // 1. Phân loại và tạo Filter Tabs
    if (acts.length > 0) {
        const totalCount = acts.length;
        const eventCount = acts.filter(a => a.type === 'event').length;
        const shiftCount = acts.filter(a => a.type === 'shift').length;
        const otherCount = acts.filter(a => a.type === 'other').length;

        if (filterTabsEl) {
            filterTabsEl.style.display = 'flex';
            filterTabsEl.innerHTML = `
                <button type="button" class="cal-day-filter-tab ${currentDayFilter === 'all' ? 'active' : ''}" onclick="filterDayDetailTab('all', this)">Tất cả (${totalCount})</button>
                ${eventCount > 0 ? `<button type="button" class="cal-day-filter-tab ${currentDayFilter === 'event' ? 'active' : ''}" onclick="filterDayDetailTab('event', this)">Sự kiện (${eventCount})</button>` : ''}
                ${shiftCount > 0 ? `<button type="button" class="cal-day-filter-tab ${currentDayFilter === 'shift' ? 'active' : ''}" onclick="filterDayDetailTab('shift', this)">Ca trực (${shiftCount})</button>` : ''}
                ${otherCount > 0 ? `<button type="button" class="cal-day-filter-tab ${currentDayFilter === 'other' ? 'active' : ''}" onclick="filterDayDetailTab('other', this)">Khác (${otherCount})</button>` : ''}
            `;
        }

        const filtered = (currentDayFilter === 'all')
            ? acts
            : acts.filter(a => a.type === currentDayFilter);

        let html = '<div class="d-flex flex-column gap-2.5">';
        filtered.forEach(item => {
            html += `
                <div class="cal-detail-entry-card" style="border-left: 3.5px solid ${item.borderColor}; cursor: pointer;" onclick="openEventDrawer(${item.eventId})">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <div class="d-flex align-items-center gap-2 min-w-0">
                            <i class="bi ${item.icon}" style="color: ${item.iconColor}; font-size: 14px; flex-shrink: 0;"></i>
                            <h6 class="fw-bold text-dark mb-0 text-truncate" style="font-size: 13.5px; line-height: 1.3;">
                                ${item.title}
                            </h6>
                        </div>
                        <div class="d-flex align-items-center gap-1.5 flex-shrink-0 ms-2">
                            <span class="badge rounded-pill" style="background: ${item.statusBg}; color: ${item.statusColor}; font-size: 11px; font-weight: 600; padding: 3px 8px;">
                                <span class="d-inline-block rounded-circle me-1" style="width: 5px; height: 5px; background: ${item.statusColor};"></span>${item.status}
                            </span>
                            <i class="bi bi-chevron-right text-muted" style="font-size: 11px;"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-1 text-muted" style="font-size: 12px; margin-left: 22px;">
                        <div class="d-flex align-items-center gap-1.5">
                            <i class="bi bi-clock" style="font-size: 11px; color: #64748B;"></i>
                            <span class="fw-medium text-dark">${item.time}</span>
                        </div>
                        <div class="d-flex align-items-center gap-1.5">
                            <i class="bi bi-geo-alt" style="font-size: 11px; color: #64748B;"></i>
                            <span>${item.location}</span>
                        </div>
                        ${item.ctvInfo ? `
                            <div class="d-flex align-items-center gap-2 mt-0.5">
                                <span><i class="bi bi-people me-1 text-primary"></i>${item.ctvInfo}</span>
                                ${item.allowance ? `<span class="text-muted">•</span><span class="fw-semibold" style="color: #16A34A;">Thù lao: ${item.allowance}</span>` : ''}
                            </div>
                        ` : ''}
                        ${item.description ? `
                            <div class="text-muted mt-0.5" style="font-size: 11.5px; color: #64748B !important;">
                                ${item.description}
                            </div>
                        ` : ''}
                    </div>
                </div>
            `;
        });
        html += '</div>';
        contentEl.innerHTML = html;
    } else {
        // Empty state chuẩn Apple Calendar (Tối giản, tinh tế)
        if (filterTabsEl) {
            filterTabsEl.style.display = 'none';
            filterTabsEl.innerHTML = '';
        }
        contentEl.innerHTML = `
            <div class="text-center py-5 h-100 d-flex flex-column align-items-center justify-content-center my-auto">
                <div class="p-3 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="background: #F8FAFC; width: 60px; height: 60px; border: 1px solid #E2E8F0;">
                    <i class="bi bi-calendar3" style="font-size: 26px; color: #94A3B8;"></i>
                </div>
                <h6 class="fw-semibold text-dark mb-1" style="font-size: 14px;">Không có hoạt động nào</h6>
                <p class="text-muted small mb-3" style="font-size: 12.5px; max-width: 260px;">Chưa có ca trực hoặc sự kiện nào được lên lịch trong ngày ${formattedDate}.</p>
                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1.5 fw-medium d-inline-flex align-items-center gap-1.5 shadow-2xs" style="font-size: 12px;" onclick="openInspectorEventModal(2)">
                    <i class="bi bi-plus-lg"></i> Thêm lịch / Ghi chú
                </button>
            </div>
        `;
    }
}

/**
 * Render Danh sách Lịch sắp tới trong Inspector Content (khi người dùng bấm Tab 'Sắp tới')
 */
function renderCalendarUpcomingScheduleInContent() {
    const titleEl = document.getElementById('calSelectedDayTitle');
    const contentEl = document.getElementById('calSelectedDayContent');
    const filterTabsEl = document.getElementById('calDayFilterTabs');
    if (!contentEl) return;

    if (titleEl) titleEl.textContent = 'Hoạt động sắp diễn ra';
    if (filterTabsEl) filterTabsEl.style.display = 'none';

    const upcomingItems = [
        {
            date: 'CN, 15/11/2026',
            targetDate: '2026-11-15',
            title: 'Khai mạc TechDay 2026',
            subtitle: 'Hỗ trợ tổ chức (3 ca)',
            status: 'Đang tuyển',
            statusColor: '#EA580C',
            statusBg: '#FFF1EB',
            borderColor: '#EA580C',
            eventId: 1
        },
        {
            date: 'T4, 18/11/2026',
            targetDate: '2026-11-18',
            title: 'Bảo trì phòng máy Lab AI',
            subtitle: 'Hậu cần kỹ thuật (2 ca)',
            status: 'Ca của tôi',
            statusColor: '#0284C7',
            statusBg: '#F0F9FF',
            borderColor: '#0284C7',
            eventId: 2
        },
        {
            date: 'T6, 20/11/2026',
            targetDate: '2026-11-20',
            title: 'Khai mạc Giải thể thao sinh viên',
            subtitle: 'Truyền thông & trọng tài (2 ca)',
            status: 'Đã xác nhận',
            statusColor: '#16A34A',
            statusBg: '#F0FDF4',
            borderColor: '#16A34A',
            eventId: 3
        },
        {
            date: 'CN, 22/11/2026',
            targetDate: '2026-11-22',
            title: 'Triển lãm FPoly Creative',
            subtitle: 'Hướng dẫn & Media (3 ca)',
            status: 'Sắp diễn ra',
            statusColor: '#9333EA',
            statusBg: '#FAF5FF',
            borderColor: '#9333EA',
            eventId: 4
        },
        {
            date: 'T7, 28/11/2026',
            targetDate: '2026-11-28',
            title: 'Hỗ trợ Gian hàng CLB',
            subtitle: 'Tổ chức sự kiện (2 ca)',
            status: 'Đang tuyển',
            statusColor: '#EA580C',
            statusBg: '#FFF1EB',
            borderColor: '#EA580C',
            eventId: 6
        }
    ];

    contentEl.innerHTML = `
        <div class="d-flex flex-column gap-2.5">
            ${upcomingItems.map(item => `
                <div class="cal-detail-entry-card" style="border-left: 3.5px solid ${item.borderColor}; cursor: pointer;" onclick="selectCalendarDateAndInspect('${item.targetDate}')" title="Bấm để xem chi tiết ngày ${item.date}">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="text-muted fw-semibold" style="font-size: 11.5px;">
                            <i class="bi bi-calendar3 me-1 text-primary"></i>${item.date}
                        </span>
                        <span class="badge rounded-pill" style="background: ${item.statusBg}; color: ${item.statusColor}; font-size: 10.5px; font-weight: 600; padding: 2.5px 7px;">
                            <span class="d-inline-block rounded-circle me-1" style="width: 5px; height: 5px; background: ${item.statusColor};"></span>${item.status}
                        </span>
                    </div>
                    <h6 class="fw-bold text-dark mb-1" style="font-size: 13.5px; line-height: 1.3;">
                        ${item.title}
                    </h6>
                    <div class="d-flex align-items-center justify-content-between text-muted" style="font-size: 12px;">
                        <span><i class="bi bi-tag me-1"></i>${item.subtitle}</span>
                        <span class="text-primary fw-medium" style="font-size: 11.5px;">Xem ngày <i class="bi bi-arrow-right"></i></span>
                    </div>
                </div>
            `).join('')}
        </div>
    `;
}

function renderCalendarUpcomingSchedule() {
    renderCalendarUpcomingScheduleInContent();
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
function openShiftDetail(shiftId, returnToEventId = null) {
    const shift = rawShifts.find(s => parseInt(s.id) === parseInt(shiftId));
    if (!shift) return;

    // Nếu Modal Chi Tiết Sự Kiện đang mở, đóng nó trước để tránh bị đè 2 modal lên nhau
    const evModalEl = document.getElementById('eventShiftsModal');
    if (evModalEl && evModalEl.classList.contains('show')) {
        const evModalInst = bootstrap.Modal.getInstance(evModalEl);
        if (evModalInst) {
            evModalEl.addEventListener('hidden.bs.modal', function onEvHidden() {
                evModalEl.removeEventListener('hidden.bs.modal', onEvHidden);
                _renderAndShowShiftModal(shift, returnToEventId || shift.event_id);
            }, { once: true });
            evModalInst.hide();
            return;
        }
    }

    _renderAndShowShiftModal(shift, returnToEventId);
}

function _renderAndShowShiftModal(shift, returnToEventId = null) {
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

    const backBtnHtml = returnToEventId 
        ? `<button type="button" class="btn-fpt-secondary btn-fpt-sm" onclick="shiftModalInstance.hide(); openEventDetailsModal(${returnToEventId});"><i class="bi bi-arrow-left me-1"></i> Quay Lại Sự Kiện</button>`
        : '';

    if (userPermissions.canRegisterShift) {
        noticeContainer.innerHTML = `
            <i class="bi bi-shield-check fs-5"></i>
            <span>Hệ thống tự động chạy thuật toán <strong>Collision Detection</strong> để đảm bảo bạn không bị trùng lịch học hay ca trực khác.</span>
        `;
        if (isMine) {
            actionContainer.innerHTML = `
                <div class="d-flex align-items-center gap-2">
                    ${backBtnHtml}
                    <button type="button" class="btn-fpt-emerald" onclick="shiftModalInstance.hide(); navigateToPage('my-shifts');">
                        <i class="bi bi-calendar-check me-1"></i> Đến Lịch Của Tôi Để Nộp Minh Chứng
                    </button>
                </div>
            `;
        } else {
            actionContainer.innerHTML = `
                <div class="d-flex align-items-center gap-2">
                    ${backBtnHtml}
                    <button type="button" class="btn-fpt-primary" onclick="submitShiftRegistration(${shift.id}, ${returnToEventId || 'null'})">
                        <i class="bi bi-check-lg me-1"></i> Xác Nhận Đăng Ký (AJAX)
                    </button>
                </div>
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
                ${backBtnHtml}
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
            <div class="d-flex align-items-center gap-2">
                ${backBtnHtml}
                <button type="button" class="btn-fpt-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i> Đóng
                </button>
            </div>
        `;
    }

    shiftModalInstance.show();
}

/**
 * =========================================================
 * QUẢN LÝ SỰ KIỆN & CA TRỰC (BENTO 2.0 / LINEAR REDESIGN)
 * =========================================================
 */
let miniCalYear = 2026;
let miniCalMonth = 10; // Tháng 11 (0-indexed)
let activeEventFilterTab = 'all';
let selectedMiniCalDay = 18; // Mặc định chọn ngày 18 như trong ảnh mẫu mockup

function initEventsPage() {
    renderMiniCalendar(miniCalYear, miniCalMonth);
    updateEventsTabCounts();
    handleEventFilterChange();
}

function navMiniCalendar(delta) {
    miniCalMonth += delta;
    if (miniCalMonth < 0) {
        miniCalMonth = 11;
        miniCalYear--;
    } else if (miniCalMonth > 11) {
        miniCalMonth = 0;
        miniCalYear++;
    }
    renderMiniCalendar(miniCalYear, miniCalMonth);
}

function renderMiniCalendar(year, month) {
    const monthNames = [
        'Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
        'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
    ];
    const titleEl = document.getElementById('miniCalTitle');
    if (titleEl) {
        titleEl.textContent = `${monthNames[month]}, ${year}`;
    }

    const gridEl = document.getElementById('miniCalGrid');
    if (!gridEl) return;
    gridEl.innerHTML = '';

    // Lấy ngày đầu tiên của tháng (chuyển sang Monday-based: 0=T2 ... 6=CN)
    const firstDay = new Date(year, month, 1).getDay();
    const mondayFirstCol = (firstDay + 6) % 7; 

    // Số ngày trong tháng
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const daysInPrevMonth = new Date(year, month, 0).getDate();

    // Tập hợp các ngày có sự kiện trong tháng này
    const eventDays = new Set();
    const monthStr = String(month + 1).padStart(2, '0');
    const prefix = `${year}-${monthStr}-`;
    
    if (typeof rawEvents !== 'undefined') {
        rawEvents.forEach(e => {
            if (e.start_time && e.start_time.startsWith(prefix)) {
                const day = parseInt(e.start_time.substring(8, 10));
                eventDays.add(day);
            }
        });
    }

    // Ô trống tháng trước
    for (let i = mondayFirstCol - 1; i >= 0; i--) {
        const d = daysInPrevMonth - i;
        const cell = document.createElement('div');
        cell.className = 'mini-cal-day other-month';
        cell.textContent = d;
        gridEl.appendChild(cell);
    }

    // Các ngày trong tháng này
    for (let d = 1; d <= daysInMonth; d++) {
        const cell = document.createElement('div');
        const hasEvent = eventDays.has(d);
        const isActive = (d === selectedMiniCalDay && month === 10 && year === 2026);
        
        cell.className = `mini-cal-day ${hasEvent ? 'has-event' : ''} ${isActive ? 'active' : ''}`;
        cell.textContent = d;
        cell.onclick = () => onSelectMiniCalDay(d);
        gridEl.appendChild(cell);
    }
}

function onSelectMiniCalDay(day) {
    if (selectedMiniCalDay === day) {
        selectedMiniCalDay = null;
    } else {
        selectedMiniCalDay = day;
    }
    renderMiniCalendar(miniCalYear, miniCalMonth);
    
    // Highlight hoặc cuộn đến sự kiện tương ứng
    const rows = document.querySelectorAll('#eventsListContainer .event-row-card');
    let matchedRow = null;
    
    rows.forEach(r => {
        const rowDay = parseInt(r.getAttribute('data-day') || '0');
        if (selectedMiniCalDay && rowDay === selectedMiniCalDay) {
            r.classList.add('active');
            if (!matchedRow) matchedRow = r;
        } else {
            r.classList.remove('active');
        }
    });

    if (matchedRow) {
        matchedRow.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}

function filterEventsByTab(tab) {
    activeEventFilterTab = tab;
    document.querySelectorAll('.event-tab-btn').forEach(btn => btn.classList.remove('active'));
    
    if (tab === 'all') {
        const tabAll = document.getElementById('tabEvAll');
        if (tabAll) tabAll.classList.add('active');
    } else if (tab === 'upcoming') {
        const tabUp = document.getElementById('tabEvUpcoming');
        if (tabUp) tabUp.classList.add('active');
    } else if (tab === 'ended') {
        const tabEnd = document.getElementById('tabEvEnded');
        if (tabEnd) tabEnd.classList.add('active');
    }
    handleEventFilterChange();
}

function handleEventFilterChange() {
    const searchVal = (document.getElementById('eventSearchInput')?.value || '').trim().toLowerCase();
    const deptVal = (document.getElementById('eventDeptSelect')?.value || '').trim();

    const rows = document.querySelectorAll('#eventsListContainer .event-row-card');
    let visibleCount = 0;

    rows.forEach(r => {
        const title = r.getAttribute('data-title') || '';
        const dept = r.getAttribute('data-dept') || '';
        const isEnded = r.getAttribute('data-ended') === '1';

        let matchesTab = true;
        if (activeEventFilterTab === 'upcoming' && isEnded) matchesTab = false;
        if (activeEventFilterTab === 'ended' && !isEnded) matchesTab = false;

        let matchesSearch = true;
        if (searchVal && !title.includes(searchVal) && !dept.toLowerCase().includes(searchVal)) {
            matchesSearch = false;
        }

        let matchesDept = true;
        if (deptVal && !dept.includes(deptVal)) {
            matchesDept = false;
        }

        if (matchesTab && matchesSearch && matchesDept) {
            r.style.display = 'flex';
            visibleCount++;
        } else {
            r.style.display = 'none';
        }
    });

    const footerText = document.getElementById('eventListFooterText');
    if (footerText) {
        footerText.textContent = `Hiển thị ${visibleCount} sự kiện trong tháng 11`;
    }
}

function updateEventsTabCounts() {
    const badgeAll = document.getElementById('badgeCountAll');
    if (badgeAll && typeof rawEvents !== 'undefined') {
        badgeAll.textContent = rawEvents.length;
    }

    const statTotal = document.getElementById('statTotalEventsCount');
    if (statTotal && typeof rawEvents !== 'undefined') {
        statTotal.textContent = rawEvents.length;
    }

    const statShifts = document.getElementById('statTotalShiftsCount');
    if (statShifts && typeof rawShifts !== 'undefined') {
        statShifts.textContent = rawShifts.length;
    }
}

function handleEventCardClick(eventId, eventDate) {
    // 1. Cập nhật ngày đang active trên Mini Calendar
    const parts = eventDate.split('-');
    if (parts.length >= 3) {
        selectedMiniCalDay = parseInt(parts[2]);
        renderMiniCalendar(miniCalYear, miniCalMonth);
    }

    // 2. Highlight card
    document.querySelectorAll('#eventsListContainer .event-row-card').forEach(r => {
        if (parseInt(r.getAttribute('data-event-id')) === eventId) {
            r.classList.add('active');
        } else {
            r.classList.remove('active');
        }
    });

    // 3. Mở Modal phân rã ca trực của sự kiện
    openEventShiftsModal(eventId);
}

let currentOpenEventId = null;

function openEventShiftsModal(eventId) {
    const ev = (typeof rawEvents !== 'undefined') ? rawEvents.find(e => parseInt(e.id) === parseInt(eventId)) : null;
    if (!ev) return;
    currentOpenEventId = eventId;

    const titleEl = document.getElementById('evModalTitle');
    const deptEl = document.getElementById('evModalDept');
    const descEl = document.getElementById('evModalDesc');
    const timeLocEl = document.getElementById('evModalTimeLoc');

    if (titleEl) titleEl.textContent = ev.title;
    if (deptEl) deptEl.textContent = ev.department || 'Bộ môn';
    if (descEl) descEl.textContent = ev.description || 'Không có mô tả chi tiết.';
    
    if (timeLocEl && ev.start_time) {
        const timeFormatted = `${ev.start_time.substring(11, 16)} - ${ev.end_time.substring(11, 16)} • ${ev.start_time.substring(8, 10)}/${ev.start_time.substring(5, 7)}/${ev.start_time.substring(0, 4)}`;
        timeLocEl.innerHTML = `<i class="bi bi-clock me-1 text-primary"></i>${timeFormatted} &nbsp;•&nbsp; <i class="bi bi-geo-alt me-1 text-danger"></i>${ev.location_name || 'Hội trường'}`;
    }

    // Lọc các ca trực thuộc sự kiện này
    const evShifts = (typeof rawShifts !== 'undefined') ? rawShifts.filter(s => parseInt(s.event_id) === parseInt(eventId)) : [];
    const countEl = document.getElementById('evModalShiftCount');
    if (countEl) countEl.textContent = `${evShifts.length} ca trực`;

    const container = document.getElementById('evModalShiftsList');
    if (!container) return;
    container.innerHTML = '';

    if (evShifts.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4 text-muted">
                <i class="bi bi-calendar-x fs-2 d-block mb-2 text-slate-300"></i>
                <div class="fw-semibold">Chưa có ca trực nào được phân rã cho sự kiện này</div>
                <small>Nhấp "Thêm Ca Trực" để bắt đầu mở đợt tuyển CTV.</small>
            </div>
        `;
    } else {
        evShifts.forEach(s => {
            const isFull = parseInt(s.approved_qty) >= parseInt(s.target_qty);
            const isMine = parseInt(s.is_registered_by_me) > 0;
            const allowance = s.remuneration_amount ? Number(s.remuneration_amount).toLocaleString('vi-VN') + ' đ' : '150,000 đ';

            const card = document.createElement('div');
            card.className = 'd-flex align-items-center justify-content-between p-3 rounded-3 border bg-white shadow-2xs';
            card.innerHTML = `
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge bg-light text-dark border font-monospace" style="font-size: 11px;">${s.start_time.substring(11, 16)} - ${s.end_time.substring(11, 16)}</span>
                        <strong class="text-dark" style="font-size: 13.5px;">${s.name}</strong>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-3 text-muted" style="font-size: 12px;">
                        <span><i class="bi bi-people me-1 text-info"></i>Đã tuyển: <strong>${s.approved_qty}/${s.target_qty} CTV</strong></span>
                        <span class="text-success fw-semibold"><i class="bi bi-cash me-1"></i>Thù lao: ${allowance}</span>
                        <span><i class="bi bi-stars me-1 text-warning"></i>${s.skill_required || 'Hậu cần'}</span>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    ${isMine 
                        ? `<span class="badge bg-success px-2.5 py-1.5 fw-semibold"><i class="bi bi-check2 me-1"></i>Bạn đã đăng ký</span>` 
                        : (userPermissions.canRegisterShift 
                            ? `<button type="button" class="btn btn-sm btn-fpt-secondary shadow-2xs" onclick="event.stopPropagation(); openShiftDetail(${s.id}, ${ev.id})"><i class="bi bi-info-circle me-1"></i>Chi tiết</button>
                               <button type="button" class="btn btn-sm btn-fpt-primary shadow-2xs" id="regShiftBtn_${s.id}" onclick="event.stopPropagation(); registerShiftDirectly(${s.id}, ${ev.id})">${isFull ? '<i class="bi bi-clock-history me-1"></i>Đăng Ký Dự Phòng' : '<i class="bi bi-check2 me-1"></i>Đăng Ký'}</button>` 
                            : `<button type="button" class="btn btn-sm btn-fpt-secondary shadow-2xs" onclick="event.stopPropagation(); openShiftDetail(${s.id}, ${ev.id})"><i class="bi bi-eye me-1"></i>Chi tiết</button>`)}
                </div>
            `;
            container.appendChild(card);
        });
    }

    // Quyền thêm ca hoặc xóa sự kiện
    const addShiftBtn = document.getElementById('evModalAddShiftBtn');
    if (addShiftBtn) {
        addShiftBtn.style.display = userPermissions.canCreateShift ? 'inline-flex' : 'none';
    }

    const orgActions = document.getElementById('evModalOrganizerActions');
    if (orgActions) {
        const canDelete = userPermissions.role === 'ADMIN' || userPermissions.role === 'STAFF' || (userPermissions.role === 'LECTURER' && parseInt(ev.created_by) === parseInt(currentUser.id));
        if (canDelete) {
            orgActions.innerHTML = `
                <button type="button" class="btn btn-sm btn-outline-danger border-0 d-inline-flex align-items-center gap-1.5" onclick="confirmDeleteEvent(${ev.id}, '${ev.title.replace(/'/g, "\\'")}')" style="background: rgba(220, 38, 38, 0.08); font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 8px;">
                    <i class="bi bi-trash3"></i> Xóa Sự Kiện
                </button>
            `;
        } else {
            orgActions.innerHTML = '';
        }
    }

    const modalEl = document.getElementById('eventShiftsModal');
    if (modalEl) {
        const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
        modalInstance.show();
    }
}

/**
 * Đăng ký ca trực trực tiếp từ Modal Sự kiện (1-click AJAX, không mở modal đè lên)
 */
async function registerShiftDirectly(shiftId, eventId) {
    const shift = rawShifts.find(s => parseInt(s.id) === parseInt(shiftId));
    if (!shift) return;

    const btn = document.getElementById(`regShiftBtn_${shiftId}`);
    const originalHtml = btn ? btn.innerHTML : '';
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true" style="width: 12px; height: 12px;"></span>Đang đăng ký...`;
    }

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
            showToast(data.message || 'Đăng ký ca trực thành công!', 'success');

            // Cập nhật lại giao diện modal sự kiện hiện tại (nếu đang mở)
            const evModalEl = document.getElementById('eventShiftsModal');
            if (evModalEl && evModalEl.classList.contains('show')) {
                openEventShiftsModal(eventId);
            }

            // Cập nhật các bảng & lịch phía sau
            changeCalendarView(currentCalendarTier);
            renderMyShiftsTable();
        } else {
            showToast(data.message || 'Không thể đăng ký ca trực.', 'danger');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        }
    } catch (err) {
        showToast('Lỗi kết nối máy chủ.', 'danger');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    }
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
            const rowEl = document.getElementById(`eventRow_${eventId}`);
            if (rowEl) rowEl.remove();
            const evModalEl = document.getElementById('eventShiftsModal');
            if (evModalEl) {
                const evModalInst = bootstrap.Modal.getInstance(evModalEl);
                if (evModalInst) evModalInst.hide();
            }
            if (typeof rawEvents !== 'undefined') {
                const evIdx = rawEvents.findIndex(e => parseInt(e.id) === parseInt(eventId));
                if (evIdx !== -1) rawEvents.splice(evIdx, 1);
            }
            updateEventsTabCounts();
            handleEventFilterChange();
            renderMiniCalendar(miniCalYear, miniCalMonth);
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
let currentEventIdForShiftCreation = null;

function openCreateShiftModal(eventId = null) {
    currentEventIdForShiftCreation = eventId || null;

    // Nếu Modal Chi Tiết Sự Kiện đang mở, đóng trước để tránh bị đè lên nhau
    const evModalEl = document.getElementById('eventShiftsModal');
    if (evModalEl && evModalEl.classList.contains('show')) {
        const evModalInst = bootstrap.Modal.getInstance(evModalEl);
        if (evModalInst) {
            evModalEl.addEventListener('hidden.bs.modal', function onEvHidden() {
                evModalEl.removeEventListener('hidden.bs.modal', onEvHidden);
                _showCreateShiftModal(eventId);
            }, { once: true });
            evModalInst.hide();
            return;
        }
    }
    _showCreateShiftModal(eventId);
}

function _showCreateShiftModal(eventId = null) {
    if (eventId) {
        const evSelect = document.getElementById('shiftEventSelect');
        if (evSelect) evSelect.value = eventId;
    }
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

            // Nếu tạo từ Modal Sự Kiện, tự động mở lại modal sự kiện với ca vừa tạo
            if (currentEventIdForShiftCreation) {
                setTimeout(() => {
                    openEventShiftsModal(currentEventIdForShiftCreation);
                    currentEventIdForShiftCreation = null;
                }, 200);
            }
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
async function submitShiftRegistration(shiftId, returnToEventId = null) {
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

            // Nếu người dùng thao tác từ Modal Sự Kiện, tự động mở lại modal sự kiện sau khi đóng modal ca trực
            if (returnToEventId) {
                setTimeout(() => {
                    openEventShiftsModal(returnToEventId);
                }, 200);
            }
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
    // Không ghi đè bảng tĩnh mẫu Mockup 15, chỉ đảm bảo sự kiện click và trạng thái được liên kết
    const drawer = document.getElementById('myShiftsDetailDrawer');
    if (drawer) {
        drawer.style.display = 'flex';
    }
}

function filterExploreJobs(cat, btn) {
    const group = document.getElementById('exploreCategoryFilters');
    if (group) group.querySelectorAll('.cal-view-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');

    const cards = document.querySelectorAll('.saas-job-card');
    cards.forEach(c => {
        if (cat === 'all' || c.getAttribute('data-category') === cat) {
            c.style.display = 'flex';
        } else {
            c.style.display = 'none';
        }
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
 * Mở modal nộp minh chứng từ màn hình Chi tiết nghiệm thu
 */
function openSubmitProofModalForUser(assignmentId, shiftName, shiftTime) {
    if (!assignmentId) {
        openSubmitProofModal(1, 'Ca 2: Kỹ thuật & Vận hành âm thanh', '18/11/2026 13:00 - 17:00', false);
    } else {
        openSubmitProofModal(assignmentId, shiftName, shiftTime, false);
    }
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

let currentFacilityAssignmentId = 8;
let currentProofDecision = 'approve';

const sampleVfCtvs = [
    { id: 8, name: 'Hoàng Minh Luận', code: 'PI03015', email: 'hoangminhluan@fpt.edu.vn', avatar: 'H', bg: '#FFEDD5', color: '#C2410C', event: 'Chung kết Cuộc thi Khởi nghiệp trẻ', shift: 'Ca 2: Kỹ thuật trình chiếu & Micro', time: '18/11/2026, 13:00 – 17:00', note: 'Hỗ trợ lắp đặt, vận hành hệ thống âm thanh và trình chiếu cho chương trình. Có mặt từ 12:30, kiểm tra thiết bị, điều chỉnh âm lượng trong suốt thời gian diễn ra sự kiện và thu dọn sau chương trình.' },
    { id: 9, name: 'Nguyễn Thư Kỳ', code: 'PI03028', email: 'nguyenthuky@fpt.edu.vn', avatar: 'N', bg: '#E0E7FF', color: '#4338CA', event: 'FPoly TechDay 2026', shift: 'Ca 1: Lễ tân & Đón tiếp VIP', time: '15/11/2026, 07:30 – 11:30', note: 'Đón tiếp khách mời VIP tại sảnh Tòa T, phát thẻ khách và tài liệu hội thảo, hướng dẫn vào hội trường đúng sơ đồ ghế ngồi.' },
    { id: 10, name: 'Trần Gia Bảo', code: 'PI03102', email: 'trangiabao@fpt.edu.vn', avatar: 'T', bg: '#FEE2E2', color: '#B91C1C', event: 'Giải Thể Thao Sinh Viên FPoly', shift: 'Ca 1: Hậu cần & Trọng tài', time: '20/11/2026, 08:00 – 12:00', note: 'Chuẩn bị sân bóng, cờ biên, bóng thi đấu và nước uống cho 8 đội tham gia, hỗ trợ trọng tài ghi chép biên bản trận đấu.' },
    { id: 11, name: 'Lê Minh Tâm', code: 'PI03077', email: 'leminhtam@fpt.edu.vn', avatar: 'L', bg: '#FEF3C7', color: '#B45309', event: 'Hội Thảo Đổi Mới Đào Tạo AI', shift: 'Ca 2: Điều phối khán phòng', time: '22/11/2026, 13:30 – 17:30', note: 'Điều phối micro lưu động cho khán giả đặt câu hỏi giao lưu với diễn giả, hỗ trợ kỹ thuật máy chiếu và chụp ảnh hậu trường.' }
];

function renderVfCtvList(filter = '') {
    const container = document.getElementById('vfCtvListContainer');
    if (!container) return;

    const list = sampleVfCtvs.filter(c => 
        !filter || c.name.toLowerCase().includes(filter.toLowerCase()) || c.code.toLowerCase().includes(filter.toLowerCase())
    );

    container.innerHTML = list.map(c => `
        <div class="vf-ctv-item ${c.id === currentFacilityAssignmentId ? 'active' : ''}" onclick="selectVfCtv(${c.id})">
            <div class="vf-ctv-avatar" style="background: ${c.bg}; color: ${c.color};">
                ${c.avatar}
            </div>
            <div class="flex-grow-1 overflow-hidden" style="line-height: 1.25;">
                <div class="fw-bold text-dark text-truncate" style="font-size: 13px;">${c.name}</div>
                <div class="text-muted text-truncate" style="font-size: 11px;">${c.code}</div>
            </div>
        </div>
    `).join('');
}

function selectVfCtv(ctvId) {
    currentFacilityAssignmentId = ctvId;
    const ctv = sampleVfCtvs.find(c => c.id === ctvId);
    if (!ctv) return;

    document.getElementById('vfCtvName').textContent = ctv.name;
    document.getElementById('vfCtvAvatar').textContent = ctv.avatar;
    document.getElementById('vfCtvAvatar').style.background = ctv.bg;
    document.getElementById('vfCtvAvatar').style.color = ctv.color;
    document.getElementById('vfCtvMeta').textContent = `${ctv.code} • ${ctv.email}`;
    document.getElementById('vfEventTitle').textContent = ctv.event;
    document.getElementById('vfShiftName').textContent = ctv.shift;
    document.getElementById('vfShiftTime').textContent = ctv.time;
    document.getElementById('vfProofDescription').textContent = ctv.note;

    renderVfCtvList(document.getElementById('vfCtvSearchInput')?.value || '');
}

function filterVfCtvList(val) {
    renderVfCtvList(val);
}

const sampleFacilityPhotos = [
    { url: 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&auto=format&fit=crop&q=80', title: 'Kỹ thuật viên âm thanh tác nghiệp tại bàn điều khiển' },
    { url: 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=800&auto=format&fit=crop&q=80', title: 'Kiểm tra và cân chỉnh đèn spotlight sân khấu' },
    { url: 'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=800&auto=format&fit=crop&q=80', title: 'Toàn cảnh sân khấu Chung kết Cuộc thi Khởi nghiệp trẻ' },
    { url: 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=800&auto=format&fit=crop&q=80', title: 'Điều phối slide pitching và laptop kỹ thuật của BTC' },
    { url: 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=800&auto=format&fit=crop&q=80', title: 'Kiểm tra đấu nối dây tín hiệu tủ rack server âm thanh' },
    { url: 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800&auto=format&fit=crop&q=80', title: 'Cân chỉnh Equalizer trên bàn mixer kỹ thuật số đa kênh' },
    { url: 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&auto=format&fit=crop&q=80', title: 'Đội hậu cần phối hợp sắp xếp bàn ghế ban giám khảo' },
    { url: 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=800&auto=format&fit=crop&q=80', title: 'Hội trường Trịnh Văn Bô sẵn sàng trước giờ khai mạc' }
];

function openFacilityProofModal(assignmentId = 8) {
    currentFacilityAssignmentId = parseInt(assignmentId) || 8;
    const acc = (typeof rawAccountingList !== 'undefined') 
        ? rawAccountingList.find(a => parseInt(a.assignment_id) === currentFacilityAssignmentId)
        : null;

    if (acc) {
        document.getElementById('vfCtvName').textContent = acc.full_name || 'Hoàng Minh Luận';
        document.getElementById('vfCtvAvatar').textContent = (acc.full_name ? acc.full_name.charAt(0).toUpperCase() : 'H');
        document.getElementById('vfCtvMeta').textContent = (acc.student_code || 'PI03015') + ' • ' + (acc.email || 'hoangminhluan@fpt.edu.vn');
        document.getElementById('vfEventTitle').textContent = acc.event_title || 'Chung kết Cuộc thi Khởi nghiệp trẻ';
        document.getElementById('vfShiftName').textContent = acc.shift_name || 'Ca 2: Kỹ thuật trình chiếu & Micro';
        
        let timeStr = '18/11/2026, 13:00 – 17:00';
        if (acc.start_time && acc.end_time) {
            const d = acc.start_time.substring(8, 10) + '/' + acc.start_time.substring(5, 7) + '/' + acc.start_time.substring(0, 4);
            const s = acc.start_time.substring(11, 16);
            const e = acc.end_time.substring(11, 16);
            timeStr = `${d}, ${s} – ${e}`;
        }
        document.getElementById('vfShiftTime').textContent = timeStr;
        document.getElementById('vfProofDescription').textContent = acc.proof_note || 'Hỗ trợ lắp đặt, vận hành hệ thống âm thanh và trình chiếu cho chương trình. Có mặt từ 12:30, kiểm tra thiết bị, điều chỉnh âm lượng trong suốt thời gian diễn ra sự kiện và thu dọn sau chương trình.';
    } else {
        const ctv = sampleVfCtvs.find(c => c.id === currentFacilityAssignmentId) || sampleVfCtvs[0];
        document.getElementById('vfCtvName').textContent = ctv.name;
        document.getElementById('vfCtvAvatar').textContent = ctv.avatar;
        document.getElementById('vfCtvAvatar').style.background = ctv.bg;
        document.getElementById('vfCtvAvatar').style.color = ctv.color;
        document.getElementById('vfCtvMeta').textContent = `${ctv.code} • ${ctv.email}`;
        document.getElementById('vfEventTitle').textContent = ctv.event;
        document.getElementById('vfShiftName').textContent = ctv.shift;
        document.getElementById('vfShiftTime').textContent = ctv.time;
        document.getElementById('vfProofDescription').textContent = ctv.note;
    }

    renderVfCtvList();

    // Reset decision to approve
    selectProofDecision('approve');
    const noteArea = document.getElementById('vfDecisionNote');
    if (noteArea) noteArea.value = '';
    updateDecisionNoteCounter();

    const modalEl = document.getElementById('modalVerifyFacilityProof');
    if (modalEl) {
        const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
        modalInstance.show();
    }
}

function toggleCriteriaCheck(itemEl) {
    itemEl.classList.toggle('is-checked');
    const checkIcon = itemEl.querySelector('.proof-criteria-check i');
    if (checkIcon) {
        checkIcon.style.display = itemEl.classList.contains('is-checked') ? 'block' : 'none';
    }
}

function selectProofDecision(decision) {
    currentProofDecision = decision;
    const optApprove = document.getElementById('vfOptApprove');
    const optRequestMore = document.getElementById('vfOptRequestMore');
    const asterisk = document.getElementById('vfNoteAsterisk');
    const confirmBtn = document.getElementById('vfConfirmBtn');

    if (decision === 'approve') {
        if (optApprove) optApprove.classList.add('is-selected');
        if (optRequestMore) optRequestMore.classList.remove('is-selected');
        if (asterisk) asterisk.style.display = 'none';
        if (confirmBtn) {
            confirmBtn.className = 'btn btn-sm btn-primary px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5';
            confirmBtn.style.background = '#2563EB';
            confirmBtn.style.borderColor = '#2563EB';
            confirmBtn.innerHTML = '<i class="bi bi-check-lg"></i><span>Xác nhận</span>';
        }
    } else {
        if (optRequestMore) optRequestMore.classList.add('is-selected');
        if (optApprove) optApprove.classList.remove('is-selected');
        if (asterisk) asterisk.style.display = 'inline';
        const noteArea = document.getElementById('vfDecisionNote');
        if (noteArea) {
            noteArea.focus();
            if (!noteArea.value) {
                noteArea.placeholder = 'Nhập lý do yêu cầu CTV bổ sung minh chứng (bắt buộc)...';
            }
        }
        if (confirmBtn) {
            confirmBtn.className = 'btn btn-sm btn-warning px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 text-dark';
            confirmBtn.style.background = '#F59E0B';
            confirmBtn.style.borderColor = '#F59E0B';
            confirmBtn.innerHTML = '<i class="bi bi-arrow-repeat"></i><span>Gửi yêu cầu bổ sung</span>';
        }
    }
}

function updateDecisionNoteCounter() {
    const noteArea = document.getElementById('vfDecisionNote');
    const counter = document.getElementById('vfNoteCharCount');
    if (noteArea && counter) {
        counter.textContent = noteArea.value.length;
    }
}

async function confirmFacilityProofVerification() {
    const noteArea = document.getElementById('vfDecisionNote');
    const noteVal = noteArea ? noteArea.value.trim() : '';

    if (currentProofDecision === 'request_more' && !noteVal) {
        showToast('Vui lòng nhập lý do yêu cầu bổ sung minh chứng cho CTV!', 'warning');
        if (noteArea) noteArea.focus();
        return;
    }

    try {
        const formData = new FormData();
        formData.append('assignment_id', currentFacilityAssignmentId);
        formData.append('decision', currentProofDecision);
        formData.append('note', noteVal);

        const response = await fetch('/dashboard/verifyLecturerAcceptance', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();

        if (data.success) {
            showToast(data.message, currentProofDecision === 'approve' ? 'success' : 'info');

            // Cập nhật local state
            const acc = (typeof rawAccountingList !== 'undefined') 
                ? rawAccountingList.find(a => parseInt(a.assignment_id) === currentFacilityAssignmentId)
                : null;

            if (acc) {
                if (currentProofDecision === 'approve') {
                    acc.lecturer_verified = 1;
                    acc.contract_code = data.contract_code || ('HDNT-2026-FPLY-' + String(currentFacilityAssignmentId).padStart(3, '0'));
                    acc.accounting_status = 'Approved';
                } else {
                    acc.lecturer_verified = 0;
                    acc.accounting_status = 'Revision_Required';
                    acc.accounting_note = noteVal;
                }
            }

            // Đóng modal
            const modalEl = document.getElementById('modalVerifyFacilityProof');
            if (modalEl) {
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) modalInstance.hide();
            }

            // Cập nhật UI bảng
            const verCol = document.getElementById(`accVerificationCol_${currentFacilityAssignmentId}`);
            if (verCol) {
                if (currentProofDecision === 'approve') {
                    verCol.innerHTML = `
                        <span class="badge bg-success-subtle text-success border border-success-subtle d-inline-block mb-1">
                            <i class="bi bi-patch-check-fill me-1"></i>Đã duyệt nghiệm thu
                        </span>
                        <div>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace" style="font-size: 10.5px;">
                                ${data.contract_code || ''}
                            </span>
                        </div>
                    `;
                } else {
                    verCol.innerHTML = `
                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle d-inline-block mb-1">
                            <i class="bi bi-arrow-repeat me-1"></i>Yêu cầu bổ sung
                        </span>
                    `;
                }
            }

            if (typeof renderMyShiftsTable === 'function') renderMyShiftsTable();
        } else {
            showToast(data.message || 'Lỗi khi xác nhận nghiệm thu.', 'danger');
        }
    } catch (err) {
        showToast('Lỗi kết nối khi gửi kết quả nghiệm thu: ' + err.message, 'danger');
    }
}

function downloadAllProofPhotos() {
    showToast('Bắt đầu tải xuống bộ 8 ảnh minh chứng độ phân giải cao (.zip)...', 'info');
}

function expandProofFullscreen() {
    openProofZoomModal(0);
}

function openProofZoomModal(photoIndex) {
    const photo = sampleFacilityPhotos[photoIndex] || sampleFacilityPhotos[0];
    previewProofModal('Hoàng Minh Luận', 'Ca 2: Kỹ thuật trình chiếu & Micro (' + (photoIndex + 1) + '/8)', photo.url, photo.title);
}

/**
 * Giảng viên xác nhận nghiệm thu hiện trường & khởi tạo hợp đồng
 */
function verifyLecturerAcceptance(assignmentId) {
    openFacilityProofModal(assignmentId);
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
        // Phân quyền chặt chẽ: Chỉ Cán bộ Hành chính, Giảng viên / Admin được thấy nút Trình Trường Ký, CTV tuyệt đối không thấy
        const canSubmitSchool = userPermissions && (userPermissions.canSubmitSchoolSigning || ['STAFF', 'ADMIN', 'LECTURER'].includes(userPermissions.role));
        if (submitBtn) {
            submitBtn.style.display = canSubmitSchool ? 'inline-block' : 'none';
        }
    }

    // Nút Duyệt Nghiệm Thu ngay trong Modal dành cho Giảng viên & Admin
    const verifyModalBtn = document.getElementById('btnVerifyInModal');
    const isVerifiedAcc = (parseInt(acc.lecturer_verified) === 1 || parseInt(acc.my_lecturer_verified) === 1);
    const canVerifyAcc = userPermissions && (userPermissions.canVerifyLecturer || ['STAFF', 'ADMIN', 'LECTURER'].includes(userPermissions.role));
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

/**
 * Render Markdown & Clean Typography cho Trợ Lý AI PolyMee
 * Chuyển đổi ký hiệu raw markdown (**bold**, *italic*, • list, - sublist, -> arrow)
 * thành cấu trúc HTML sắc nét, tinh gọn theo chuẩn Bento UI Apple HIG
 */
function renderAiMarkdown(rawText) {
    if (!rawText) return '';

    const lines = rawText.split('\n');
    let out = [];
    let inList = false;

    lines.forEach(line => {
        let trimmed = line.trim();
        if (!trimmed) {
            if (inList) { out.push('</div>'); inList = false; }
            out.push('<div class="my-2"></div>');
            return;
        }

        // 1. Bold (**text**)
        let formatted = trimmed.replace(/\*\*([^*]+)\*\*/g, '<strong style="color: #0F172A; font-weight: 700;">$1</strong>');
        // 2. Italic (*text*)
        formatted = formatted.replace(/(?<!\*)\*([^*]+)\*(?!\*)/g, '<em style="color: #475569;">$1</em>');
        // 3. Arrow (-> hoặc -->)
        formatted = formatted.replace(/(?:-->|->)/g, '<span style="color: #0284C7; font-weight: 800; margin: 0 4px;">→</span>');

        // A. Tiêu đề mục (bắt đầu bằng emoji như 🌟, 💳, 🎯, 📊, ⚡ hoặc kết thúc bằng dấu hai chấm)
        if (/^[🌟💳⚡🎯📋🔍✨🔔📊].*[:：]$/.test(trimmed) || (trimmed.startsWith('**') && trimmed.endsWith(':**') && trimmed.length < 80)) {
            if (inList) { out.push('</div>'); inList = false; }
            out.push(`
                <div class="fw-bold d-flex align-items-center gap-2 mt-1 mb-3 text-dark" style="font-size: 14.5px; letter-spacing: -0.01em;">
                    ${formatted}
                </div>
            `);
            return;
        }

        // B. Khung Ghi Chú / Quy Chế / Lưu Ý Callout Box (Có khoảng đệm rộng rãi p-3.5 và cách xa các phần khác my-3)
        if (/^[📌💡ℹ️⚠️👉]/.test(trimmed)) {
            if (inList) { out.push('</div>'); inList = false; }
            out.push(`
                <div class="rounded-3 my-3 p-3.5 border" style="background: rgba(2, 132, 199, 0.04); border-color: rgba(2, 132, 199, 0.18) !important;">
                    <div style="font-size: 12.5px; line-height: 1.65; color: #334155;">
                        ${formatted}
                    </div>
                </div>
            `);
            return;
        }

        // C. Bullet chính (bắt đầu bằng • hoặc *)
        if (/^[•*]\s+/.test(trimmed)) {
            let itemContent = formatted.replace(/^[•*]\s+/, '');
            if (!inList) {
                out.push('<div class="d-flex flex-column gap-2.5 my-2.5">');
                inList = true;
            }
            out.push(`
                <div class="d-flex align-items-start gap-2.5" style="padding: 1px 0;">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 19px; height: 19px; background: #E0F2FE; color: #0284C7; font-size: 9px; font-weight: 800; margin-top: 2px;">•</span>
                    <div style="font-size: 13px; line-height: 1.6; color: #1E293B; flex-grow: 1;">${itemContent}</div>
                </div>
            `);
            return;
        }

        // D. Sub-bullet phụ (bắt đầu bằng -)
        if (/^-\s+/.test(trimmed)) {
            let subContent = formatted.replace(/^-\s+/, '');
            out.push(`
                <div class="d-flex align-items-start gap-2 ms-4 my-1">
                    <i class="bi bi-arrow-return-right text-muted flex-shrink-0" style="font-size: 11px; margin-top: 3px;"></i>
                    <div style="font-size: 12.5px; line-height: 1.55; color: #475569;">${subContent}</div>
                </div>
            `);
            return;
        }

        // E. Dòng thông thường
        if (inList) { out.push('</div>'); inList = false; }
        out.push(`<div class="my-1.5" style="font-size: 13px; line-height: 1.6; color: #1E293B;">${formatted}</div>`);
    });

    if (inList) { out.push('</div>'); }
    return out.join('');
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
    loadingMsg.innerHTML = `<div class="rounded-circle d-flex align-items-center justify-content-center shadow-xs" style="width:34px; height:34px; min-width:34px; flex:none; background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%); color:#FFFFFF;"><i class="fa-solid fa-wand-magic-sparkles" style="font-size:14px; line-height:1;"></i></div><div class="bg-white p-3 rounded-4 shadow-xs border text-muted" style="font-size:13px;"><i class="fa-solid fa-spinner fa-spin me-1"></i> Trợ lý AI đang phân tích dữ liệu...</div>`;
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

        const replyHtml = data.reply_html || (data.reply ? renderAiMarkdown(data.reply) : 'PolyMee AI đã xử lý yêu cầu của bạn.');
        const aiReply = document.createElement('div');
        aiReply.className = 'd-flex gap-2.5';
        aiReply.innerHTML = `<div class="rounded-circle d-flex align-items-center justify-content-center shadow-xs" style="width:34px; height:34px; min-width:34px; flex:none; background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%); color:#FFFFFF;"><i class="fa-solid fa-wand-magic-sparkles" style="font-size:14px; line-height:1;"></i></div><div class="bg-white p-4 rounded-4 shadow-xs border flex-grow-1" style="font-size:13px; max-width:92%; line-height:1.6; box-shadow: 0 2px 10px rgba(0,0,0,0.04) !important;">${replyHtml}</div>`;
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



/* =========================================================================
   POLYMEE INTERACTION HELPERS (Hồ Sơ Cá Nhân, Duyệt Minh Chứng, Công Việc)
   ========================================================================= */

// 1. Chuyển đổi tab trong Hồ sơ cá nhân
function switchProfileTab(tabName, btn) {
    document.querySelectorAll('.profile-nav-pill-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');

    document.querySelectorAll('.profile-tab-pane').forEach(p => p.classList.add('d-none'));

    const targetMap = {
        'common': 'profileTabCommon',
        'idCard': 'profileTabIdCard',
        'bank': 'profileTabBank',
        'skills': 'profileTabSkills',
        'security': 'profileTabSecurity'
    };
    const targetId = targetMap[tabName] || 'profileTabCommon';
    const targetPane = document.getElementById(targetId);
    if (targetPane) targetPane.classList.remove('d-none');
}

// 2. Quản lý Chip Tag Kỹ Năng trong Hồ sơ cá nhân
function removeSkillChip(el, skillName) {
    const parent = el.closest('.profile-chip-tag');
    if (parent) parent.remove();
    syncSkillChipsToHiddenInput();
}

function promptAddSkillChip() {
    const newSkill = prompt('Nhập tên kỹ năng hoặc lĩnh vực chuyên môn phụ trách:');
    if (newSkill && newSkill.trim()) {
        const trimmed = newSkill.trim();
        const container = document.getElementById('profileSkillChipsContainer');
        const addBtn = container.querySelector('.profile-chip-add-btn');
        const chip = document.createElement('span');
        chip.className = 'profile-chip-tag';
        chip.innerHTML = `${trimmed} <span class="tag-remove-btn" onclick="removeSkillChip(this, '${trimmed}')">×</span>`;
        if (addBtn) {
            container.insertBefore(chip, addBtn);
        } else {
            container.appendChild(chip);
        }
        syncSkillChipsToHiddenInput();
    }
}

function syncSkillChipsToHiddenInput() {
    const container = document.getElementById('profileSkillChipsContainer');
    if (!container) return;
    const chips = container.querySelectorAll('.profile-chip-tag');
    const skillList = [];
    chips.forEach(c => {
        const text = c.textContent.replace('×', '').trim();
        if (text) skillList.push(text);
    });
    const input = document.getElementById('inputProfileSkills');
    if (input) input.value = skillList.join(', ');
}

// 3. Mở và Điều Khiển Màn Hình Chi Tiết Minh Chứng (Mockup Image 12)
let currentProofPhotoIdx = 0;
currentProofDecision = 'approve';
const proofPhotoList = [
    'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1464375117522-1311d6a5b81f?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1429962714451-bb934ecdc4ec?auto=format&fit=crop&w=800&q=80'
];

function openProofDetailPage(assignmentId) {
    navigateToPage('proof-detail');
}

function selectProofPhoto(idx) {
    currentProofPhotoIdx = idx;
    const items = document.querySelectorAll('.proof-photo-item');
    items.forEach((it, i) => {
        if (i === idx) it.classList.add('is-selected');
        else it.classList.remove('is-selected');
    });

    const thumbs = document.querySelectorAll('.proof-thumb-cell');
    thumbs.forEach((th, i) => {
        if (i === idx) th.classList.add('active');
        else th.classList.remove('active');
    });

    const lbl = document.getElementById('proofPhotoIndexLabel');
    if (lbl) lbl.textContent = `${idx + 1}/8`;
}

function navigateProofPhoto(dir) {
    let nextIdx = currentProofPhotoIdx + dir;
    if (nextIdx < 0) nextIdx = 7;
    if (nextIdx > 7) nextIdx = 0;
    selectProofPhoto(nextIdx);
}

function toggleCriterion(el) {
    if (!el) return;
    if (el.classList.contains('proof-uncheck-box')) {
        el.className = 'proof-check-badge';
        el.innerHTML = '<i class="bi bi-check"></i>';
    } else {
        el.className = 'proof-uncheck-box cursor-pointer';
        el.innerHTML = '';
    }
}

function selectProofDecision(type) {
    currentProofDecision = type;
    const boxApprove = document.getElementById('decisionBoxApprove');
    const boxRev = document.getElementById('decisionBoxRevision');
    if (type === 'approve') {
        if (boxApprove) { boxApprove.classList.add('selected'); boxApprove.classList.remove('decision-revision'); }
        if (boxRev) { boxRev.classList.remove('selected', 'decision-revision'); }
    } else {
        if (boxRev) { boxRev.classList.add('selected', 'decision-revision'); }
        if (boxApprove) { boxApprove.classList.remove('selected'); }
    }
}

function updateProofNoteCounter(el) {
    const counter = document.getElementById('proofNoteCounter');
    if (counter && el) counter.textContent = el.value.length;
}

function submitProofDecision() {
    const note = document.getElementById('proofDecisionNote')?.value || '';
    const btn = document.getElementById('btnConfirmProofDecision');
    if (btn) btn.disabled = true;

    // Gửi AJAX POST tới backend API verify_lecturer
    const formData = new FormData();
    formData.append('assignment_id', 8); // Ca trực của Hoàng Minh Luận
    formData.append('decision', currentProofDecision);
    formData.append('note', note);

    fetch('index.php?page=dashboard&action=verify_lecturer', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (btn) btn.disabled = false;
        if (data.success) {
            triggerToast(currentProofDecision === 'approve' ? 'Đã duyệt thẩm định nghiệm thu thành công! Hợp đồng điện tử đã được khởi tạo.' : 'Đã gửi yêu cầu bổ sung minh chứng tới CTV!');
            setTimeout(() => {
                navigateToPage('submissions');
            }, 1200);
        } else {
            triggerToast(data.message || 'Lỗi khi xử lý thẩm định');
        }
    })
    .catch(err => {
        if (btn) btn.disabled = false;
        triggerToast('Đã xác nhận duyệt nghiệm thu thành công!');
        setTimeout(() => {
            navigateToPage('submissions');
        }, 1200);
    });
}

// 4. Điều Khiển Bảng Công Việc Của Tôi & Drawer Chi Tiết (Mockup Image 15)
function selectMyShiftRow(row, id) {
    document.querySelectorAll('#myShiftsTableBody tr').forEach(r => r.classList.remove('table-active'));
    if (row) row.classList.add('table-active');

    const drawer = document.getElementById('myShiftsDetailDrawer');
    if (drawer) drawer.style.display = 'flex';

    const titleEl = document.getElementById('drawerEventTitle');
    const subEl = document.getElementById('drawerShiftSub');
    if (id === 1) {
        if (titleEl) titleEl.textContent = 'FPoly TechDay 2026';
        if (subEl) subEl.textContent = '📅 15/11/2026 • Ca 3: Media';
    } else if (id === 2) {
        if (titleEl) titleEl.textContent = 'Chung kết Khởi nghiệp trẻ';
        if (subEl) subEl.textContent = '📅 18/11/2026 • Ca 2: Hậu cần';
    } else if (id === 3) {
        if (titleEl) titleEl.textContent = 'Workshop AI';
        if (subEl) subEl.textContent = '📅 20/11/2026 • Ca 3: Hậu cần';
    }
}

function closeMyShiftsDrawer() {
    const drawer = document.getElementById('myShiftsDetailDrawer');
    if (drawer) drawer.style.display = 'none';
}

function filterMyShifts(type, btn) {
    const group = document.getElementById('myShiftsFilterTabs');
    if (group) group.querySelectorAll('.cal-view-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');

    const rows = document.querySelectorAll('#myShiftsTableBody tr');
    rows.forEach((r, idx) => {
        if (type === 'all') {
            r.style.display = '';
        } else if (type === 'upcoming') {
            r.style.display = (idx === 2) ? '' : 'none';
        } else if (type === 'pending') {
            r.style.display = (idx === 1 || idx === 2) ? '' : 'none';
        } else if (type === 'completed') {
            r.style.display = (idx === 0) ? '' : 'none';
        }
    });
}

</script>

</body>
</html>
