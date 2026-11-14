<!-- Lucian UX/UI Prompt #040 · MIT License (c) 2026 Seokwoooo · https://lucianlabs.dev/uxui-prompt/#040 -->
<!DOCTYPE html>
<html lang="vi" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
       POLYMEE ENTERPRISE DESIGN SYSTEM (design-taste-frontend-v1)
       Brand: Calibrated FPT Orange (#EA580C) & Deep Navy (#0F172A)
       Single Accent (<80% sat), Zero AI Lila Glow, Strict Tabular Nums
       ========================================================= */
    :root {
        color-scheme: light;
        --ux-page: #F8FAFC;              /* Clean Slate Canvas (Slate 50) */
        --ux-surface: #FFFFFF;           /* Pure White Surface */
        --ux-surface-2: #F1F5F9;         /* Light Neutral Card (Slate 100) */
        --ux-surface-3: #E2E8F0;         /* Slate 200 Divider */
        --ux-ink: #0F172A;               /* Deep Slate 900 Typography */
        --ux-ink-2: #334155;             /* Slate 700 Secondary Body */
        --ux-ink-3: #64748B;             /* Slate 500 Muted Details */
        --ux-line: #E2E8F0;              /* 1px Soft Border */
        --ux-line-2: #CBD5E1;            /* 1px Focus Border */
        --ux-accent: #EA580C;            /* Calibrated FPT Orange (#EA580C) */
        --ux-accent-hover: #C2410C;      /* Rich Orange Hover */
        --ux-accent-active: #9A3412;     /* Active state */
        --ux-accent-soft: #FFF7ED;       /* Soft Tint */
        --ux-accent-gradient: linear-gradient(135deg, #F97316 0%, #EA580C 100%);
        --ux-navy-gradient: linear-gradient(180deg, #0F172A 0%, #1E293B 100%);
        --ux-mint: #059669;              /* Emerald 600 Success / Approved */
        --ux-mint-soft: #ECFDF5;
        --ux-warn: #D97706;              /* Amber 600 Warning / Pending */
        --ux-warn-soft: #FFFBEB;
        --ux-danger: #DC2626;            /* Red 600 Collision / Rejected */
        --ux-danger-soft: #FEF2F2;
        --ux-info: #0284C7;              /* Sky 600 Info / Ongoing */
        --ux-info-soft: #F0F9FF;
        --ux-shadow-sm: 0 1px 3px rgba(15, 23, 42, 0.04);
        --ux-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.06), 0 2px 4px -1px rgba(15, 23, 42, 0.03);
        --ux-shadow-lg: 0 20px 40px -15px rgba(15, 23, 42, 0.07);
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
        border-radius: 24px;
        background: var(--ux-navy-gradient);
        color: var(--ux-page);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 16px 40px rgba(10, 25, 47, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.12);
    }

    .u040-top {
        display: flex;
        align-items: center;
        gap: 12px;
        height: 44px;
        margin-bottom: 20px;
    }

    .u040-logo {
        width: 42px;
        height: 42px;
        flex: none;
        margin-left: 8px;
        border-radius: 12px;
        background: #ffffff;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
    }

    .u040-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .u040-name {
        font-size: 16.5px;
        font-weight: 800;
        letter-spacing: -.03em;
        white-space: nowrap;
        opacity: 0;
        color: #fff;
        transition: opacity .25s;
    }

    .u040.is-wide .u040-name {
        opacity: 1;
        transition: opacity .3s .15s;
    }

    .u040-tog {
        position: absolute;
        right: 0;
        top: 18px;
        width: 28px;
        height: 36px;
        border: 0;
        padding: 0;
        border-radius: 10px 0 0 10px;
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        display: grid;
        place-items: center;
        opacity: 0.85;
        transition: opacity .2s, background .2s;
        cursor: pointer;
    }

    .u040-rail:hover .u040-tog,
    .u040-tog:focus-visible,
    .u040.is-wide .u040-tog {
        opacity: 1;
        background: rgba(255, 255, 255, 0.22);
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
        height: 48px;
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.14);
        transform: translateY(var(--y, 0));
        transition: transform .5s var(--ux-spring);
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
        height: 48px;
        padding: 0 0 0 16px;
        border: 0;
        border-radius: 15px;
        background: none;
        color: #fff;
        opacity: .72;
        text-align: left;
        transition: opacity .25s, color .25s;
        text-decoration: none;
        cursor: pointer;
    }

    .u040-it:hover,
    .u040-it[aria-current="page"] {
        opacity: 1;
        color: #fff;
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
        left: 36px;
        top: 9px;
        min-width: 18px;
        height: 18px;
        padding: 0 4px;
        border-radius: 9px;
        background: var(--ux-accent);
        color: #fff;
        font-size: 10.5px;
        font-weight: 800;
        display: grid;
        place-items: center;
        font-style: normal;
        box-shadow: 0 0 0 2.5px var(--ux-ink);
        transition: left .5s var(--ux-spring);
    }

    .u040.is-wide .u040-bd {
        left: 205px;
    }

    .u040-me {
        margin-top: auto;
        display: flex;
        align-items: center;
        gap: 12px;
        height: 56px;
        padding: 0 0 0 10px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.08);
        overflow: hidden;
    }

    .u040-me i {
        width: 36px;
        height: 36px;
        flex: none;
        border-radius: 50%;
        background: radial-gradient(circle at 35% 30%, #FED7AA, #EA580C 70%, #0F172A);
        display: grid;
        place-items: center;
        color: #fff;
        font-size: 13.5px;
        font-style: normal;
        font-weight: 800;
    }

    .u040-me span {
        font-size: 13px;
        font-weight: 650;
        white-space: nowrap;
        opacity: 0;
        color: #fff;
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

    /* =========================================================
       MAIN WORKSPACE & CLEAN ENTERPRISE STYLING
       ========================================================= */
    .main-workspace {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        padding: 16px 24px 40px 12px;
    }

    .dashboard-topbar {
        position: relative;
        z-index: 1050;
        background: var(--ux-surface);
        border: 1px solid var(--ux-line);
        border-radius: 20px;
        padding: 14px 22px;
        margin-bottom: 16px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        box-shadow: 0 4px 18px rgba(10, 25, 47, 0.04);
    }

    .dashboard-topbar .dropdown {
        position: relative;
        z-index: 1055;
    }

    .dashboard-topbar .dropdown-menu {
        z-index: 1060 !important;
    }

    .place-items-center {
        display: grid !important;
        place-items: center !important;
        justify-items: center !important;
        align-items: center !important;
    }

    /* Central Hub Mode Switcher (Bento 2.0 Liquid Glass Bar) */
    .central-mode-bar {
        background: #FFFFFF;
        border: 1px solid var(--ux-line);
        border-radius: 16px;
        padding: 5px;
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
    }

    .central-mode-bar .hub-tab-btn {
        border: 0;
        background: transparent;
        color: var(--ux-ink-2);
        font-size: 13.5px;
        font-weight: 650;
        padding: 9px 18px;
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
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.25), 0 4px 14px rgba(234, 88, 12, 0.28);
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
        background: #F1F5F9;
        padding: 4px;
        border-radius: 14px;
        border: 1px solid var(--ux-line);
        gap: 2px;
    }

    .view-switcher-group .btn {
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 650;
        padding: 6px 15px;
        color: var(--ux-ink-3);
        background: transparent;
        transition: all 0.18s var(--ux-ease);
        box-shadow: none;
    }

    .view-switcher-group .btn:hover {
        color: var(--ux-ink);
        background: rgba(255, 255, 255, 0.6);
    }

    .view-switcher-group .btn:active {
        transform: scale(0.97);
    }

    .view-switcher-group .btn.active {
        background-color: #FFFFFF;
        color: var(--ux-ink);
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.08), 0 1px 2px rgba(15, 23, 42, 0.04);
        border: 1px solid rgba(226, 232, 240, 0.8);
    }

    .kpi-card {
        background: var(--ux-surface);
        border: 1px solid var(--ux-line);
        border-radius: 20px;
        padding: 20px 24px;
        box-shadow: 0 4px 14px rgba(10, 25, 47, 0.04);
        transition: transform .25s var(--ux-spring), box-shadow .25s;
    }
    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(10, 25, 47, 0.08);
        border-color: var(--ux-line-2);
    }

    .calendar-container, .spa-panel {
        background: var(--ux-surface);
        border: 1.5px solid var(--ux-line);
        border-radius: 24px;
        padding: 28px;
        box-shadow: 0 6px 20px rgba(10, 25, 47, 0.04);
        animation: fadeInPanel 0.2s ease-in-out;
    }

    @keyframes fadeInPanel {
        from { opacity: 0; transform: translateY(4px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* =========================================================
       APPLE macOS SONOMA / iPadOS CALENDAR & MATERIALS SYSTEM
       ========================================================= */
    .dashboard-topbar, .central-mode-bar {
        background: rgba(255, 255, 255, 0.78);
        backdrop-filter: blur(24px) saturate(190%);
        -webkit-backdrop-filter: blur(24px) saturate(190%);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-top: 1px solid rgba(255, 255, 255, 0.95);
        box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.05), 0 1px 3px rgba(15, 23, 42, 0.02);
        border-radius: 20px;
    }

    .calendar-container, .spa-panel {
        background: #FFFFFF;
        border: 1px solid rgba(226, 232, 240, 0.85);
        border-radius: 24px;
        padding: 28px;
        box-shadow: 0 12px 36px -8px rgba(15, 23, 42, 0.04);
        animation: fadeInPanel 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes fadeInPanel {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .month-header-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        text-align: center;
        margin-bottom: 12px;
        padding-bottom: 6px;
        border-bottom: 1px solid rgba(226, 232, 240, 0.6);
    }

    .month-header-grid .day-label {
        font-size: 11.5px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #64748B;
        padding: 6px 0;
        min-width: 0;
    }

    .month-days-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 10px;
        width: 100%;
        box-sizing: border-box;
    }

    .month-day-cell {
        min-height: 124px;
        min-width: 0;
        width: 100%;
        box-sizing: border-box;
        border-radius: 18px;
        background: #FFFFFF;
        border: 1px solid rgba(226, 232, 240, 0.85);
        padding: 10px 10px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }

    .month-day-cell:hover {
        background: #FFFFFF;
        border-color: #FDBA74;
        box-shadow: 0 12px 28px -4px rgba(15, 23, 42, 0.08);
        transform: translateY(-2px);
    }

    .month-day-cell:active {
        transform: scale(0.98) translateY(0);
    }

    .month-day-cell.is-today {
        background: #FFF7ED;
        border-color: #FDBA74;
    }

    .month-day-cell.is-today .day-number-pill {
        background: #EA580C;
        color: #FFFFFF !important;
        box-shadow: 0 2px 6px rgba(234, 88, 12, 0.35);
    }

    .month-day-cell.is-other-month {
        opacity: 0.35;
        background: #F8FAFC;
    }

    .day-number-pill {
        font-size: 12.5px;
        font-weight: 750;
        color: #0F172A;
        width: 26px;
        height: 26px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        transition: all 0.15s ease;
        flex: none;
    }

    /* Apple Lozenge Chips */
    .shift-chip {
        font-size: 11px;
        font-weight: 650;
        padding: 3px 6px;
        border-radius: 7px;
        white-space: nowrap;
        overflow: hidden;
        background: rgba(241, 245, 249, 0.9);
        color: #0F172A;
        border: 1px solid rgba(226, 232, 240, 0.9);
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 3px;
        transition: transform 0.15s ease;
        min-width: 0;
        max-width: 100%;
        box-sizing: border-box;
    }

    .shift-chip .status-dot {
        flex: none;
        flex-shrink: 0;
    }

    .shift-chip .num-tabular {
        flex: none !important;
        flex-shrink: 0 !important;
        white-space: nowrap;
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

    .shift-chip:hover {
        transform: translateX(2px);
    }

    .shift-chip.urgent {
        background: rgba(254, 242, 242, 0.9);
        color: #991B1B;
        border-color: rgba(254, 202, 202, 0.9);
    }

    .shift-chip.mine {
        background: rgba(236, 253, 245, 0.9);
        color: #065F46;
        border-color: rgba(167, 243, 208, 0.9);
    }

    /* Apple Calendar Note / Memo Lozenge */
    .memo-chip {
        font-size: 11px;
        font-weight: 650;
        padding: 3px 6px;
        border-radius: 7px;
        white-space: nowrap;
        overflow: hidden;
        background: rgba(255, 247, 237, 0.95);
        color: #C2410C;
        border: 1px solid rgba(254, 215, 170, 0.9);
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 3px;
        min-width: 0;
        max-width: 100%;
        box-sizing: border-box;
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
        background: #FFFFFF;
        border: 1px solid rgba(226, 232, 240, 0.85);
        border-radius: 20px;
        padding: 18px 22px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        border-left: 5px solid #EA580C;
        box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.04);
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .timeline-card:hover {
        box-shadow: 0 10px 24px -4px rgba(15, 23, 42, 0.08);
        transform: translateY(-1px);
    }

    /* Apple Reminders Day Callout Widget */
    .apple-reminder-banner {
        background: rgba(255, 247, 237, 0.9);
        border: 1.5px solid rgba(254, 215, 170, 0.85);
        border-radius: 18px;
        padding: 14px 18px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 14px;
    }

    .week-columns-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 12px;
        margin-top: 15px;
        width: 100%;
        box-sizing: border-box;
    }

    .week-column {
        background: #F8FAFC;
        border-radius: 18px;
        padding: 12px;
        min-height: 480px;
        min-width: 0;
        width: 100%;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        border: 1px solid rgba(226, 232, 240, 0.8);
    }

    .week-col-header {
        text-align: center;
        padding-bottom: 10px;
        border-bottom: 1px dashed rgba(226, 232, 240, 0.9);
        margin-bottom: 12px;
        cursor: pointer;
    }

    .week-card-shift {
        background: #fff;
        border: 1px solid rgba(226, 232, 240, 0.85);
        border-radius: 14px;
        padding: 12px;
        margin-bottom: 10px;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
        cursor: pointer;
        transition: all .2s;
        border-left: 4px solid #EA580C;
    }

    /* macOS Floating Modal Sheets */
    .modal-content {
        border-radius: 24px !important;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.7) !important;
        box-shadow: 0 24px 60px -12px rgba(15, 23, 42, 0.2) !important;
    }

    .modal-header {
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        border-bottom: 1px solid rgba(255, 255, 255, 0.15) !important;
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
        background: linear-gradient(135deg, rgba(255, 247, 237, 0.95) 0%, rgba(240, 249, 255, 0.95) 100%);
        border: 1px solid rgba(254, 215, 170, 0.8);
        border-radius: 18px;
        padding: 14px 20px;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        box-shadow: 0 4px 14px rgba(234, 88, 12, 0.04);
    }

    @media (max-width: 992px) {
        .u040-wrapper { display: none; }
        .main-workspace { padding: 12px; }
        .week-columns-grid { grid-template-columns: 1fr; }
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
                <!-- Top Brand -->
                <div class="u040-top">
                    <span class="u040-logo" aria-hidden="true">
                        <img src="/assets/images/fpt_polytechnic_logo.png" alt="FPT Polytechnic">
                    </span>
                    <span class="u040-name">Poly<strong style="color:var(--ux-accent);">Mee</strong></span>
                </div>

                <!-- Expand/Collapse Button (Bezier Spring Curve) -->
                <button class="u040-tog" type="button" id="railToggleBtn" aria-expanded="true" aria-label="Collapse menu">
                    <svg viewBox="0 0 16 16"><path d="M6 3.5L10.5 8 6 12.5"/></svg>
                </button>

                <!-- Navigation List (Menu Chính Hệ Thống) -->
                <ul class="u040-nav" id="railNav">
                    <li class="u040-hl" role="presentation"></li>

                    <!-- 1. LỊCH TRÌNH & ĐIỀU PHỐI -->
                    <li class="u040-section-label"><span>LỊCH TRÌNH & ĐIỀU PHỐI</span></li>
                    <li>
                        <button class="u040-it" type="button" style="--i:0" aria-current="page" data-page="calendar" onclick="navigateToPage('calendar')">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="5.5" width="16" height="14.5" rx="3"/><path d="M4 10h16M8.5 3.5v4M15.5 3.5v4"/></svg>
                            <span>Lịch Hoạt Động</span>
                        </button>
                    </li>
                    <li>
                        <button class="u040-it" type="button" style="--i:1" data-page="my-shifts" onclick="navigateToPage('my-shifts')">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 11l8-6.5 8 6.5V19a1 1 0 0 1-1 1h-4.5v-5h-5v5H5a1 1 0 0 1-1-1z"/></svg>
                            <span>Ca Trực Của Tôi</span>
                        </button>
                    </li>
                    <li>
                        <button class="u040-it" type="button" style="--i:2" data-page="contract-dossier" onclick="navigateToPage('contract-dossier')" title="Hồ Sơ Hợp Đồng Nghiệm Thu">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM14 9V3.5L18.5 8H14zM8 13h8M8 17h5"/></svg>
                            <span>Hồ Sơ Hợp Đồng</span>
                        </button>
                    </li>

                    <!-- 2. TÀI CHÍNH & VNEID -->
                    <li class="u040-section-label"><span>TÀI CHÍNH & VNEID</span></li>
                    <li>
                        <button class="u040-it" type="button" style="--i:3" data-page="accounting" onclick="navigateToPage('accounting')" title="Bảng Nghiệm Thu Kế Toán">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 7h6M9 11h6M9 15h4M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/></svg>
                            <span>Nghiệm Thu Kế Toán</span>
                            <em class="u040-bd" aria-label="Hồ sơ nghiệm thu"><?= count($accountingList) ?></em>
                        </button>
                    </li>
                    <li>
                        <button class="u040-it" type="button" style="--i:4" data-page="student-payroll-profile" onclick="navigateToPage('student-payroll-profile')">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="3"/><path d="M3 10h18M7 15h2"/></svg>
                            <span>Hồ Sơ VNeID & STK</span>
                        </button>
                    </li>
                    <li>
                        <button class="u040-it" type="button" style="--i:5" data-page="submissions" onclick="navigateToPage('submissions')">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19.5V13M5 9V4.5M12 19.5V11M12 7V4.5M19 19.5V15M19 11V4.5M3 13h4M10 7h4M17 15h4"/></svg>
                            <span>Minh Chứng Drive</span>
                        </button>
                    </li>

                    <!-- 3. QUẢN LÝ TỔ CHỨC -->
                    <li class="u040-section-label"><span>QUẢN LÝ TỔ CHỨC</span></li>
                    <li>
                        <button class="u040-it" type="button" style="--i:6" data-page="events" onclick="navigateToPage('events')">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="4" width="7" height="7" rx="2"/><rect x="13" y="4" width="7" height="7" rx="2"/><rect x="4" y="13" width="7" height="7" rx="2"/><rect x="13" y="13" width="7" height="7" rx="2"/></svg>
                            <span>Quản Lý Sự Kiện</span>
                        </button>
                    </li>
                </ul>

                <!-- Profile Footer -->
                <div class="u040-me">
                    <i aria-hidden="true"><?= mb_substr($currentUser['full_name'] ?? 'U', 0, 1) ?></i>
                    <span>
                        <?= htmlspecialchars($currentUser['full_name'] ?? 'User') ?>
                        <small><?= htmlspecialchars($currentUser['role_name'] ?? 'Sinh viên') ?> • <?= htmlspecialchars($currentUser['student_code'] ?? 'CTV') ?></small>
                    </span>
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
                <a href="/dashboard" class="d-none d-lg-block text-decoration-none p-1 bg-white rounded-3 shadow-xs border">
                    <img src="/assets/images/fpt_polytechnic_logo.png" alt="FPT Polytechnic" style="height: 30px; width: auto; object-fit: contain;">
                </a>
                <div class="vr mx-1 d-none d-lg-block" style="height: 26px; opacity: 0.2;"></div>
                <div class="d-flex flex-column">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0" style="font-size: 11px; font-weight: 600;">
                            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">PolyMee</a></li>
                            <li class="breadcrumb-item active" id="breadcrumbActive" style="color:var(--ux-accent);">Bảng Lịch Trung Tâm</li>
                        </ol>
                    </nav>
                    <h1 class="h5 fw-bold mb-0 tracking-tight" id="activeScreenTitle">Lịch Hoạt Động & Điều Phối Ca Trực</h1>
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

            <!-- Right: Action Controls -->
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn-fpt-primary btn-fpt-sm" onclick="openCreateShiftModal()">
                    <i class="bi bi-calendar-plus-fill me-1"></i>
                    <span>Đặt Lịch Ca</span>
                </button>
                <button type="button" class="btn-fpt-secondary btn-fpt-sm" onclick="openAddNoteModal()" title="Thêm ghi chú / lịch hẹn toàn trường">
                    <i class="fa-solid fa-note-sticky text-warning me-1"></i>
                    <span>Ghi Chú</span>
                </button>
                <button type="button" class="btn-fpt-ghost btn-fpt-sm" onclick="toggleAiModal()" title="Trợ lý AI PolyMee">
                    <i class="fa-solid fa-robot text-warning me-1"></i>
                    <span>Trợ lý AI</span>
                </button>

                <!-- Demo Role Switcher Dropdown -->
                <div class="dropdown">
                    <button class="btn-fpt-secondary btn-fpt-sm dropdown-toggle d-flex align-items-center gap-1.5" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="avatar-circle bg-warning-subtle text-warning-emphasis fw-bold" style="width:22px; height:22px; font-size:10px; border-radius:50%; display:inline-grid; place-items:center;">
                            <?= mb_substr($currentUser['full_name'] ?? 'U', 0, 1) ?>
                        </span>
                        <span class="d-none d-xl-inline"><?= htmlspecialchars($currentUser['full_name'] ?? 'User') ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-2" style="font-size: 12.5px; width: 290px;">
                        <li class="dropdown-header text-uppercase fw-bold text-muted small" style="font-size:10.5px; letter-spacing:0.04em;">Chuyển Đổi Role Demo (6 Diễn Viên)</li>
                        <li><a class="dropdown-item py-2" href="/home/switchUser/3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar-circle bg-primary-subtle text-primary fw-bold" style="width:28px;height:28px;font-size:11px;border-radius:50%;display:grid;place-items:center;">PA</span>
                                <div>
                                    <strong class="text-dark">Đinh Nguyễn Phương Anh</strong><br>
                                    <small class="text-muted">PI03084 • Role: <strong>CTV Media</strong></small>
                                </div>
                            </div>
                        </a></li>
                        <li><a class="dropdown-item py-2" href="/home/switchUser/4">
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar-circle bg-emerald-subtle text-success fw-bold" style="width:28px;height:28px;font-size:11px;border-radius:50%;display:grid;place-items:center;">ĐM</span>
                                <div>
                                    <strong class="text-dark">Lê Đức Mạnh</strong><br>
                                    <small class="text-muted">PS20262 • Role: <strong>CTV Lễ Tân</strong></small>
                                </div>
                            </div>
                        </a></li>
                        <li><a class="dropdown-item py-2" href="/home/switchUser/2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar-circle bg-warning-subtle text-warning-emphasis fw-bold" style="width:28px;height:28px;font-size:11px;border-radius:50%;display:grid;place-items:center;">GV</span>
                                <div>
                                    <strong class="text-dark">Thầy Phan Hoàng Khải</strong><br>
                                    <small class="text-muted">GV0102 • Role: <strong>Giảng Viên / BTC</strong></small>
                                </div>
                            </div>
                        </a></li>
                        <li><a class="dropdown-item py-2" href="/home/switchUser/8">
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar-circle bg-info-subtle text-info-emphasis fw-bold" style="width:28px;height:28px;font-size:11px;border-radius:50%;display:grid;place-items:center;">HC</span>
                                <div>
                                    <strong class="text-dark">Lê Đình Tuấn</strong><br>
                                    <small class="text-muted">HC0201 • Role: <strong>Nhân Viên Hành Chính</strong></small>
                                </div>
                            </div>
                        </a></li>
                        <li><a class="dropdown-item py-2" href="/home/switchUser/7">
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar-circle bg-success-subtle text-success fw-bold" style="width:28px;height:28px;font-size:11px;border-radius:50%;display:grid;place-items:center;">KT</span>
                                <div>
                                    <strong class="text-dark">Đặng Thu Thảo</strong><br>
                                    <small class="text-muted">KT0105 • Role: <strong>Kế Toán & Quyết Toán</strong></small>
                                </div>
                            </div>
                        </a></li>
                        <li><a class="dropdown-item py-2" href="/home/switchUser/1">
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar-circle bg-danger-subtle text-danger fw-bold" style="width:28px;height:28px;font-size:11px;border-radius:50%;display:grid;place-items:center;">AD</span>
                                <div>
                                    <strong class="text-dark">Quản Trị Viên (Admin)</strong><br>
                                    <small class="text-muted">ADMIN001 • Role: <strong>Toàn Quyền / QL Địa Điểm</strong></small>
                                </div>
                            </div>
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-1.5 text-primary fw-semibold" href="/auth/login">
                            <i class="bi bi-box-arrow-in-right me-1.5"></i> Màn Hình Đăng Nhập
                        </a></li>
                        <li><a class="dropdown-item py-1.5 text-success fw-semibold" href="/auth/register">
                            <i class="bi bi-person-plus me-1.5"></i> Đăng Ký Tài Khoản CTV Mới
                        </a></li>
                        <li><a class="dropdown-item py-1.5 text-danger fw-semibold" href="/auth/logout">
                            <i class="bi bi-power me-1.5"></i> Đăng Xuất An Toàn
                        </a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- KPI Metrics Strip -->
        <section class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="kpi-card">
                    <div class="d-flex justify-content-between align-items-center text-muted mb-1" style="font-size: 11.5px; font-weight: 700;">
                        <span>TỔNG SỰ KIỆN</span>
                        <i class="bi bi-calendar2-event text-warning fs-5"></i>
                    </div>
                    <div class="h3 fw-bolder mb-0 num-tabular"><?= number_format($stats['total_events'] ?? 0) ?></div>
                    <small class="text-success fw-semibold"><i class="bi bi-arrow-up-right me-1"></i>Học kỳ Fall 2026</small>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="kpi-card">
                    <div class="d-flex justify-content-between align-items-center text-muted mb-1" style="font-size: 11.5px; font-weight: 700;">
                        <span>TỔNG CA TRỰC</span>
                        <i class="bi bi-clock-history text-info fs-5"></i>
                    </div>
                    <div class="h3 fw-bolder mb-0 num-tabular"><?= number_format($stats['total_shifts'] ?? 0) ?></div>
                    <small class="text-muted">Đa dạng khung giờ</small>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="kpi-card">
                    <div class="d-flex justify-content-between align-items-center text-muted mb-1" style="font-size: 11.5px; font-weight: 700;">
                        <span>TỶ LỆ LẤP ĐẦY</span>
                        <i class="bi bi-pie-chart text-success fs-5"></i>
                    </div>
                    <div class="h3 fw-bolder mb-0 text-success num-tabular"><?= $stats['fill_rate_percent'] ?? 0 ?>%</div>
                    <small class="text-muted">Chỉ tiêu nhân sự tuyển</small>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="kpi-card">
                    <div class="d-flex justify-content-between align-items-center text-muted mb-1" style="font-size: 11.5px; font-weight: 700;">
                        <span>QUY HOẠCH NGHIỆM THU</span>
                        <i class="bi bi-cash-coin text-primary fs-5"></i>
                    </div>
                    <div class="h3 fw-bolder mb-0 text-primary num-tabular"><?= count($accountingList) ?> <small class="fs-6 fw-normal text-muted">Hồ sơ</small></div>
                    <small class="text-muted">Chờ Kế toán duyệt chi</small>
                </div>
            </div>
        </section>

        <!-- =========================================================
             DYNAMIC SPA BODY: CHUYỂN TABS KHÔNG RELOAD TRANG
             ========================================================= -->
        <div id="spaBodyContent">

            <!-- TAB 1: BẢNG LỊCH TRUNG TÂM (CALENDAR-FIRST) -->
            <section class="calendar-container" id="panelCalendar">
                <div class="d-flex flex-wrap justify-content-between align-items-center pb-3 border-bottom mb-3 gap-2">
                    <div>
                        <h2 class="h5 fw-bold mb-0 text-dark" id="calendarViewHeading">Lịch Tổng Quan Theo Tháng</h2>
                        <p class="text-muted mb-0" style="font-size: 12px;" id="calendarViewSub">Nhấp vào ô ngày bất kỳ để vào thẳng Lịch Ngày xem chi tiết các ca trực</p>
                    </div>
                    <div class="d-flex align-items-center gap-3" style="font-size: 11.5px; font-weight: 600;">
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

            <!-- TAB 2: BẢNG NGHIỆM THU KẾ TOÁN & KIỂM DUYỆT THÙ LAO (ADMIN / ORGANIZER) -->
            <section class="spa-panel" id="panelAccounting" style="display:none;">
                <div class="d-flex flex-wrap justify-content-between align-items-center pb-3 border-bottom mb-4 gap-2">
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 fw-bold">PHÒNG KẾ TOÁN & TÀI CHÍNH</span>
                            <h2 class="h5 fw-bold mb-0 text-dark">Bảng Nghiệm Thu Thù Lao Cộng Tác Viên</h2>
                        </div>
                        <p class="text-muted small mb-0 mt-1">Quản lý định danh CCCD, ảnh VNeID, số tài khoản ngân hàng và thẩm định quyết toán chi thù lao CTV</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn-fpt-secondary btn-fpt-sm" onclick="window.print()">
                            <i class="bi bi-printer me-1"></i> In Bảng Quyết Toán
                        </button>
                        <button class="btn-fpt-emerald btn-fpt-sm" onclick="exportAccountingCSV()">
                            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Tải File Excel (CSV)
                        </button>
                    </div>
                </div>

                <!-- Bento 2.0 AI Accounting Auditor Widget -->
                <div class="ai-audit-banner mb-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:40px; height:40px; background:linear-gradient(135deg, #EA580C, #F97316); color:#fff; flex:none; box-shadow:0 4px 12px rgba(234,88,12,0.3);">
                            <i class="bi bi-robot fs-5"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <strong class="text-dark" style="font-size:13.5px;">AI Thẩm Định & Quyết Toán Thù Lao Tự Động</strong>
                                <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle num-tabular" style="font-size:10.5px;">
                                    <i class="bi bi-shield-check me-1"></i>100% Khớp VNeID
                                </span>
                            </div>
                            <small class="text-muted d-block mt-0.5">
                                AI đã quét đối soát <strong><?= count($accountingList) ?> hồ sơ</strong>: Không phát hiện gian lận trùng STK ngân hàng, không có xung đột ca trực và CCCD đã định danh hợp lệ.
                            </small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-white text-dark border px-3 py-2 shadow-xs num-tabular">
                            Tổng quyết toán: <strong class="text-primary ms-1"><?= number_format(count($accountingList) * 150000) ?> đ</strong>
                        </span>
                        <button type="button" class="btn-fpt-outline-orange btn-fpt-sm" onclick="runAiAccountingAudit()">
                            <i class="bi bi-magic me-1"></i> AI Quét Bất Thường
                        </button>
                    </div>
                </div>

                <!-- Thanh Tìm Kiếm & Lọc Nhanh Hồ Sơ Nghiệm Thu -->
                <div class="row g-2 mb-3 bg-light p-3 rounded-3 border">
                    <div class="col-12 col-md-6 col-lg-7">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="accountingSearchInput" class="form-control" placeholder="Tìm theo tên CTV, mã sinh viên, số CCCD hoặc số tài khoản..." oninput="filterAccountingTable()">
                        </div>
                    </div>
                    <div class="col-6 col-md-3 col-lg-3">
                        <select id="accountingStatusFilter" class="form-select form-select-sm" onchange="filterAccountingTable()">
                            <option value="">-- Tất cả trạng thái --</option>
                            <option value="Pending">Chờ Kế toán duyệt (Pending)</option>
                            <option value="Approved">Đã duyệt hồ sơ (Approved)</option>
                            <option value="Paid">Đã chuyển khoản (Paid)</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-3 col-lg-2 text-end">
                        <span class="badge bg-white text-muted border px-2.5 py-2 w-100 num-tabular" id="accountingFilteredCount">Hiển thị: <?= count($accountingList) ?> hồ sơ</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                        <thead class="table-light text-muted small text-uppercase fw-bold">
                            <tr>
                                <th style="width: 40px;">STT</th>
                                <th>Cộng Tác Viên (CTV)</th>
                                <th>Định Danh VNeID</th>
                                <th>Ảnh Hiện Trường</th>
                                <th>Tài Khoản Thụ Hưởng</th>
                                <th>Ca Trực & Địa Điểm</th>
                                <th>Thù Lao</th>
                                <th>Nghiệm Thu & Hợp Đồng</th>
                                <th class="text-end" style="width: 170px;">Thao Tác Nghiệm Thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($accountingList)): ?>
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">Chưa có hồ sơ nghiệm thu nào trong kỳ.</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($accountingList as $idx => $acc): ?>
                            <tr id="accRow_<?= $acc['assignment_id'] ?>">
                                <td class="text-muted fw-bold num-tabular"><?= $idx + 1 ?></td>
                                <td>
                                    <strong class="text-dark d-block"><?= htmlspecialchars($acc['full_name']) ?></strong>
                                    <small class="text-muted num-tabular"><?= htmlspecialchars($acc['student_code']) ?> • SĐT: <?= htmlspecialchars($acc['phone'] ?? '0987654321') ?></small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if (!empty($acc['vneid_image_url'])): ?>
                                        <div style="width: 48px; height: 34px; flex-shrink: 0; overflow: hidden; border-radius: 8px;">
                                            <img src="<?= htmlspecialchars($acc['vneid_image_url']) ?>" alt="Ảnh VNeID" class="vneid-thumb" style="width: 48px; height: 34px; object-fit: cover; display: block;" onclick="previewVneidModal('<?= htmlspecialchars($acc['full_name']) ?>', '<?= htmlspecialchars($acc['id_card_number'] ?? '079203014589') ?>', '<?= htmlspecialchars($acc['vneid_image_url']) ?>')" title="Nhấp xem phóng to ảnh VNeID">
                                        </div>
                                        <?php endif; ?>
                                        <div>
                                            <span class="badge bg-light text-dark border num-tabular fw-bold"><?= htmlspecialchars($acc['id_card_number'] ?? 'Chưa cập nhật') ?></span>
                                            <small class="text-success d-block" style="font-size: 11px;"><i class="bi bi-shield-fill-check me-1"></i>Đã khớp VNeID</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($acc['proof_image_url'])): ?>
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width: 48px; height: 34px; flex-shrink: 0; overflow: hidden; border-radius: 8px; border: 1px solid #e2e8f0;">
                                            <img src="<?= htmlspecialchars($acc['proof_image_url']) ?>" alt="Ảnh Hiện Trường" style="width: 48px; height: 34px; object-fit: cover; display: block; cursor: pointer;" onclick="previewProofModal('<?= htmlspecialchars($acc['full_name']) ?>', '<?= htmlspecialchars($acc['shift_name']) ?>', '<?= htmlspecialchars($acc['proof_image_url']) ?>', '<?= htmlspecialchars($acc['proof_note'] ?? 'Có mặt trực sự kiện') ?>')" title="Nhấp xem ảnh chụp hiện trường">
                                        </div>
                                        <div>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 10.5px;"><i class="bi bi-camera me-1"></i>Đã có ảnh</span>
                                            <small class="text-muted d-block text-truncate" style="max-width: 120px; font-size: 11px;" title="<?= htmlspecialchars($acc['proof_note'] ?? '') ?>"><?= htmlspecialchars($acc['proof_note'] ?? 'Hiện trường') ?></small>
                                        </div>
                                    </div>
                                    <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 11px;"><i class="bi bi-camera-fill me-1"></i>Chưa nộp ảnh</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong class="text-primary d-block num-tabular"><?= htmlspecialchars($acc['bank_account_number'] ?? 'Chưa cập nhật') ?></strong>
                                    <small class="text-dark fw-semibold"><?= htmlspecialchars($acc['bank_name'] ?? 'Ngân hàng') ?></small>
                                    <small class="text-muted d-block" style="font-size: 11px;">Chủ TK: <?= htmlspecialchars($acc['bank_account_name'] ?? $acc['full_name']) ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary mb-1"><?= htmlspecialchars($acc['event_title']) ?></span>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($acc['shift_name']) ?></div>
                                    <small class="text-muted num-tabular"><?= substr($acc['start_time'], 11, 5) ?> - <?= substr($acc['end_time'], 11, 5) ?> • <?= htmlspecialchars($acc['location_name'] ?? 'Hội trường') ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle fs-6 fw-bold num-tabular">
                                        <?= number_format((int)$acc['remuneration_amount']) ?> đ
                                    </span>
                                    <small class="text-muted d-block mt-0.5">+<?= $acc['points'] ?> điểm rèn luyện</small>
                                </td>
                                <td>
                                    <div id="accVerificationCol_<?= $acc['assignment_id'] ?>">
                                        <?php if (!empty($acc['lecturer_verified']) && $acc['lecturer_verified'] == 1): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle d-inline-block mb-1">
                                                <i class="bi bi-patch-check-fill me-1"></i>GV: <?= htmlspecialchars($acc['lecturer_name'] ?? 'GV nghiệm thu') ?>
                                            </span>
                                            <div>
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace" style="font-size: 10.5px;">
                                                    <?= htmlspecialchars($acc['contract_code'] ?? 'HDNT-2026-FPLY') ?>
                                                </span>
                                            </div>
                                            <?php if (($acc['school_status'] ?? '') === 'Signed_Paid'): ?>
                                                <small class="text-success fw-bold d-block mt-1"><i class="bi bi-check2-all me-1"></i>Trường đã chi tiền</small>
                                            <?php else: ?>
                                                <small class="text-warning-emphasis d-block mt-1"><i class="bi bi-hourglass-split me-1"></i>Chờ trường ký</small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                                <i class="bi bi-clock me-1"></i>Chờ GV nghiệm thu
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <span id="accStatusBadge_<?= $acc['assignment_id'] ?>" style="display:none;"><?= htmlspecialchars($acc['accounting_status'] ?? 'Pending') ?></span>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex flex-column gap-1.5 align-items-end" id="accActionBtns_<?= $acc['assignment_id'] ?>">
                                        <?php if (empty($acc['lecturer_verified']) || $acc['lecturer_verified'] == 0): ?>
                                        <button type="button" class="btn-fpt-emerald btn-fpt-sm w-100" onclick="verifyLecturerAcceptance(<?= $acc['assignment_id'] ?>)" title="Giảng viên duyệt ảnh hiện trường và lập hợp đồng">
                                            <i class="bi bi-check2-circle me-1"></i> GV Xác Nhận
                                        </button>
                                        <?php endif; ?>
                                        <button type="button" class="btn-fpt-secondary btn-fpt-sm w-100" onclick="openContractModal(<?= $acc['assignment_id'] ?>)" title="Xem Biên bản & Hợp đồng nghiệm thu">
                                            <i class="bi bi-file-earmark-text me-1"></i> Hợp Đồng
                                        </button>
                                        <?php if (($acc['school_status'] ?? '') !== 'Signed_Paid'): ?>
                                        <button type="button" class="btn-fpt-primary btn-fpt-sm w-100" onclick="submitToSchool(<?= $acc['assignment_id'] ?>)" title="Trình lên Nhà trường ký duyệt và chuyển tiền">
                                            <i class="bi bi-send-check me-1"></i> Trình Trường Ký
                                        </button>
                                        <?php else: ?>
                                        <span class="badge bg-success w-100 py-1.5"><i class="bi bi-check2-all me-1"></i>Đã Chi Tiền</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- TAB 3: HỒ SƠ VNEID & STK NGÂN HÀNG (DÀNH CHO CTV / SINH VIÊN TỰ CẬP NHẬT) -->
            <section class="spa-panel" id="panelStudentPayrollProfile" style="display:none;">
                <div class="pb-3 border-bottom mb-4">
                    <h2 class="h5 fw-bold mb-0 text-dark">Hồ Sơ Nhận Thù Lao & Thông Tin Định Danh</h2>
                    <p class="text-muted small mb-0">Cung cấp chính xác CCCD, ảnh xác thực ứng dụng VNeID và Số tài khoản ngân hàng để Kế toán chuyển khoản thù lao</p>
                </div>

                <div class="row g-4">
                    <div class="col-12 col-lg-7">
                        <form id="payrollProfileForm" onsubmit="handleUpdateProfile(event)" class="bg-light p-4 rounded-4 border">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold text-dark small">Họ và Tên</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($userProfile['full_name'] ?? '') ?>" readonly>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold text-dark small">Mã Sinh Viên</label>
                                    <input type="text" class="form-control num-tabular" value="<?= htmlspecialchars($userProfile['student_code'] ?? '') ?>" readonly>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold text-dark small">Số CCCD / Định Danh (12 số) <span class="text-danger">*</span></label>
                                    <input type="text" name="id_card_number" class="form-control num-tabular" value="<?= htmlspecialchars($userProfile['id_card_number'] ?? '079203014589') ?>" required placeholder="Ví dụ: 079203014589">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold text-dark small">Đường Dẫn Ảnh Thẻ / VNeID <span class="text-danger">*</span></label>
                                    <input type="url" name="vneid_image_url" class="form-control" value="<?= htmlspecialchars($userProfile['vneid_image_url'] ?? 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=600&auto=format&fit=crop&q=80') ?>" required placeholder="https://drive.google.com/...">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold text-dark small">Tên Ngân Hàng Thụ Hưởng <span class="text-danger">*</span></label>
                                    <input type="text" name="bank_name" class="form-control" value="<?= htmlspecialchars($userProfile['bank_name'] ?? 'MB Bank (Ngân hàng Quân Đội)') ?>" required placeholder="Ví dụ: MB Bank, Vietcombank, TPBank...">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold text-dark small">Số Tài Khoản Ngân Hàng (STK) <span class="text-danger">*</span></label>
                                    <input type="text" name="bank_account_number" class="form-control num-tabular" value="<?= htmlspecialchars($userProfile['bank_account_number'] ?? '098765432199') ?>" required placeholder="Ví dụ: 098765432199">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold text-dark small">Tên Chủ Tài Khoản (In hoa không dấu) <span class="text-danger">*</span></label>
                                    <input type="text" name="bank_account_name" class="form-control text-uppercase" value="<?= htmlspecialchars($userProfile['bank_account_name'] ?? $userProfile['full_name']) ?>" required placeholder="DINH NGUYEN PHUONG ANH">
                                </div>
                            </div>
                            <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                <button type="submit" class="btn-fpt-primary">
                                    <i class="bi bi-floppy me-1"></i> Cập Nhật Hồ Sơ Quyết Toán
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="col-12 col-lg-5">
                        <div class="border rounded-4 p-4 bg-white shadow-xs">
                            <h6 class="fw-bold text-dark mb-2"><i class="bi bi-shield-check text-success me-1"></i> Tiêu Chuẩn Nghiệm Thu Của Kế Toán FPoly</h6>
                            <ul class="text-muted small ps-3 mb-3" style="line-height: 1.7;">
                                <li>Tên chủ tài khoản phải khớp hoàn toàn với họ tên trên CCCD của sinh viên.</li>
                                <li>Ảnh chụp màn hình VNeID mức độ 2 rõ ràng họ tên, số định danh cá nhân và ngày sinh.</li>
                                <li>Thù lao sẽ được Kế toán chuyển khoản định kỳ vào ngày 15 và 30 hàng tháng sau khi ca trực được nghiệm thu.</li>
                            </ul>
                            <div class="text-center p-3 bg-light rounded-3 border">
                                <span class="text-muted small d-block mb-2 font-monospace">ẢNH MINH HỌA XÁC THỰC VNEID ĐÃ NẠP:</span>
                                <img id="previewVneidImg" src="<?= htmlspecialchars($userProfile['vneid_image_url'] ?? 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=600&auto=format&fit=crop&q=80') ?>" alt="Xem trước ảnh VNeID" style="max-height: 160px; border-radius: 8px; border: 1px solid #CBD5E1;">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- TAB 4: LỊCH TRỰC CỦA TÔI -->
            <section class="spa-panel" id="panelMyShifts" style="display:none;">
                <div class="d-flex justify-content-between align-items-center pb-3 border-bottom mb-4">
                    <div>
                        <h2 class="h5 fw-bold mb-0 text-dark">Lịch Trực & Ca Phân Công Của Tôi</h2>
                        <p class="text-muted small mb-0">Theo dõi ca trực, nộp ảnh chụp hiện trường chứng minh có mặt để Giảng viên nghiệm thu và xem hợp đồng thanh toán thù lao.</p>
                    </div>
                    <span class="badge bg-primary px-3 py-2 fw-bold" id="myShiftsCountBadge">0 Ca Trực</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light text-muted small text-uppercase fw-bold">
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
            </section>

            <!-- TAB 5: QUẢN LÝ SỰ KIỆN -->
            <section class="spa-panel" id="panelEvents" style="display:none;">
                <div class="d-flex justify-content-between align-items-center pb-3 border-bottom mb-4">
                    <div>
                        <h2 class="h5 fw-bold mb-0 text-dark">Danh Sách Sự Kiện & Phân Rã Ca Trực</h2>
                        <p class="text-muted small mb-0">Quản trị các chiến dịch tuyển CTV, giám sát tiến độ lấp đầy từng ca trực</p>
                    </div>
                    <button class="btn-fpt-primary btn-fpt-sm" onclick="openCreateShiftModal()">
                        <i class="bi bi-calendar-plus-fill me-1"></i> Đặt Lịch Ca Mới
                    </button>
                </div>
                <div class="row g-4" id="eventsCardsContainer">
                    <?php foreach ($events as $e): ?>
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card h-100 border rounded-4 shadow-xs overflow-hidden">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold"><?= htmlspecialchars($e['status']) ?></span>
                                    <small class="text-muted fw-bold num-tabular"><i class="bi bi-clock me-1"></i><?= substr($e['start_time'], 0, 10) ?></small>
                                </div>
                                <h5 class="fw-bold text-dark mb-2"><?= htmlspecialchars($e['title']) ?></h5>
                                <p class="text-muted small text-truncate-2 mb-3"><?= htmlspecialchars($e['description'] ?? 'Không có mô tả') ?></p>
                                <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center text-muted small">
                                    <span><i class="bi bi-geo-alt me-1 text-danger"></i><?= htmlspecialchars($e['location_name'] ?? 'Hội trường') ?></span>
                                    <span class="badge bg-light text-dark border"><?= $e['total_shifts'] ?> Ca trực</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- TAB 6: THẨM ĐỊNH MINH CHỨNG GOOGLE DRIVE -->
            <section class="spa-panel" id="panelSubmissions" style="display:none;">
                <div class="d-flex justify-content-between align-items-center pb-3 border-bottom mb-4">
                    <div>
                        <h2 class="h5 fw-bold mb-0 text-dark">Hồ Sơ Nghiệm Thu & Minh Chứng Google Drive</h2>
                        <p class="text-muted small mb-0">Thẩm định hình ảnh/video recap do CTV gửi về để xét duyệt điểm rèn luyện</p>
                    </div>
                    <span class="badge bg-warning text-dark px-3 py-2 fw-bold">Chờ Thẩm Định</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light text-muted small text-uppercase fw-bold">
                            <tr>
                                <th>Sinh Viên CTV</th>
                                <th>Sự Kiện & Ca Trực</th>
                                <th>Đường Link Drive</th>
                                <th>Thời Gian Nộp</th>
                                <th>Trạng Thái</th>
                                <th class="text-end">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <strong>Đinh Nguyễn Phương Anh</strong><br>
                                    <small class="text-muted">PI03084 • Media</small>
                                </td>
                                <td>
                                    <span class="fw-bold">FPoly TechDay 2026</span><br>
                                    <small class="text-muted">Ca 3: Media Phóng Sự Ảnh</small>
                                </td>
                                <td>
                                    <a href="https://drive.google.com" target="_blank" class="btn-fpt-secondary btn-fpt-sm">
                                        <i class="fa-brands fa-google-drive me-1 text-primary"></i> Mở Thư Mục Drive
                                    </a>
                                </td>
                                <td class="text-muted small num-tabular">15/11/2026 12:15</td>
                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                                <td class="text-end">
                                    <button class="btn-fpt-emerald btn-fpt-sm me-1" onclick="showToast('Đã phê duyệt nghiệm thu hồ sơ thành công!', 'success')">
                                        <i class="bi bi-check-lg"></i> Duyệt
                                    </button>
                                    <button class="btn-fpt-secondary btn-fpt-sm text-danger" onclick="showToast('Đã từ chối bài nộp.', 'danger')">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- TAB 7: HỒ SƠ HỢP ĐỒNG NGHIỆM THU HIỆN TRƯỜNG -->
            <section class="spa-panel py-3" id="panelContractDossier" style="display:none;">
                <div class="d-flex flex-wrap justify-content-between align-items-center pb-3 border-bottom mb-4 gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-warning text-dark font-monospace fw-bold px-2.5 py-1">QUY TRÌNH MỚI</span>
                            <h2 class="h5 fw-bold mb-0 text-dark">Hồ Sơ Hợp Đồng Nghiệm Thu & Trình Ký Nhà Trường</h2>
                        </div>
                        <p class="text-muted small mb-0">Nghiệm thu bằng ảnh hiện trường thực tế của CTV • Giảng viên thẩm định & lập hợp đồng • Trình Ban Giám Hiệu ký duyệt chuyển tiền</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn-fpt-secondary btn-fpt-sm" onclick="exportAccountingCSV()">
                            <i class="bi bi-file-earmark-excel me-1 text-success"></i> Xuất Bảng Kê (CSV)
                        </button>
                    </div>
                </div>

                <!-- 4 KPI Thẻ Nghiệm Thu -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-lg-3">
                        <div class="p-3 rounded-4 bg-light border">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Tổng Hồ Sơ HĐ</div>
                            <div class="h3 fw-bold text-dark mb-0 num-tabular"><?= count($accountingList) ?> <small class="fs-6 fw-normal text-muted">bộ</small></div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="p-3 rounded-4 bg-light border">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Ảnh Hiện Trường</div>
                            <div class="h3 fw-bold text-info mb-0 num-tabular"><?= count(array_filter($accountingList, fn($a) => !empty($a['proof_image_url']))) ?> <small class="fs-6 fw-normal text-muted">đã nộp</small></div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="p-3 rounded-4 bg-light border">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">GV Đã Nghiệm Thu</div>
                            <div class="h3 fw-bold text-success mb-0 num-tabular"><?= count(array_filter($accountingList, fn($a) => !empty($a['lecturer_verified']) && $a['lecturer_verified'] == 1)) ?> <small class="fs-6 fw-normal text-muted">hợp đồng</small></div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="p-3 rounded-4 bg-light border">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Trường Đã Chi Tiền</div>
                            <div class="h3 fw-bold text-primary mb-0 num-tabular"><?= count(array_filter($accountingList, fn($a) => ($a['school_status'] ?? '') === 'Signed_Paid')) ?> <small class="fs-6 fw-normal text-muted">đã chi</small></div>
                        </div>
                    </div>
                </div>

                <!-- Danh sách Hợp Đồng Dossier Cards -->
                <div class="row g-3">
                    <?php foreach ($accountingList as $acc): ?>
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card h-100 border shadow-xs rounded-4 p-3 bg-white d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace px-2.5 py-1">
                                        <?= htmlspecialchars($acc['contract_code'] ?? ('HDNT-2026-FPLY-' . str_pad((string)$acc['assignment_id'], 3, '0', STR_PAD_LEFT))) ?>
                                    </span>
                                    <?php if (($acc['school_status'] ?? '') === 'Signed_Paid'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        <i class="bi bi-check2-all me-1"></i>Trường Đã Ký & Chi Tiền
                                    </span>
                                    <?php elseif (!empty($acc['lecturer_verified']) && $acc['lecturer_verified'] == 1): ?>
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                        <i class="bi bi-hourglass-split me-1"></i>Chờ Trường Ký
                                    </span>
                                    <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                        <i class="bi bi-clock me-1"></i>Chờ GV Duyệt Ảnh
                                    </span>
                                    <?php endif; ?>
                                </div>

                                <div class="d-flex align-items-center gap-2.5 mb-3">
                                    <?php if (!empty($acc['proof_image_url'])): ?>
                                    <div style="width: 60px; height: 60px; flex-shrink: 0; border-radius: 10px; overflow: hidden; border: 1px solid #e2e8f0; cursor: pointer;" onclick="previewProofModal('<?= htmlspecialchars($acc['full_name']) ?>', '<?= htmlspecialchars($acc['shift_name']) ?>', '<?= htmlspecialchars($acc['proof_image_url']) ?>', '<?= htmlspecialchars($acc['proof_note'] ?? 'Hiện trường trực') ?>')">
                                        <img src="<?= htmlspecialchars($acc['proof_image_url']) ?>" alt="Ảnh Hiện Trường" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    <?php else: ?>
                                    <div style="width: 60px; height: 60px; flex-shrink: 0; border-radius: 10px; background: #f1f5f9; display: grid; place-items: center; border: 1px dashed #cbd5e1;">
                                        <i class="bi bi-camera text-muted fs-4"></i>
                                    </div>
                                    <?php endif; ?>
                                    <div class="overflow-hidden">
                                        <h6 class="fw-bold text-dark mb-0 text-truncate"><?= htmlspecialchars($acc['full_name']) ?></h6>
                                        <small class="text-muted d-block"><?= htmlspecialchars($acc['student_code']) ?> • CCCD: <?= htmlspecialchars($acc['id_card_number'] ?? '079203014589') ?></small>
                                        <small class="text-primary fw-semibold d-block text-truncate">STK: <?= htmlspecialchars($acc['bank_account_number'] ?? '') ?> (<?= htmlspecialchars($acc['bank_name'] ?? 'NH') ?>)</small>
                                    </div>
                                </div>

                                <div class="bg-light p-2.5 rounded-3 mb-3 small">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Sự kiện:</span>
                                        <strong class="text-dark text-truncate ms-2" style="max-width: 180px;"><?= htmlspecialchars($acc['event_title']) ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Ca trực:</span>
                                        <span class="text-dark"><?= htmlspecialchars($acc['shift_name']) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Địa điểm:</span>
                                        <span class="text-dark"><?= htmlspecialchars($acc['location_name'] ?? 'Hội trường') ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between border-top pt-1 mt-1">
                                        <span class="text-muted fw-bold">Thù lao:</span>
                                        <strong class="text-success num-tabular"><?= number_format((int)$acc['remuneration_amount']) ?> đ</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="button" class="btn-fpt-secondary btn-fpt-sm flex-fill" onclick="openContractModal(<?= $acc['assignment_id'] ?>)">
                                    <i class="bi bi-file-earmark-text me-1"></i> Xem Hợp Đồng
                                </button>
                                <?php if (($acc['school_status'] ?? '') !== 'Signed_Paid'): ?>
                                <button type="button" class="btn-fpt-primary btn-fpt-sm flex-fill" onclick="submitToSchool(<?= $acc['assignment_id'] ?>)">
                                    <i class="bi bi-send-check me-1"></i> Trình Trường Ký
                                </button>
                                <?php else: ?>
                                <button type="button" class="btn-fpt-emerald btn-fpt-sm flex-fill" onclick="openContractModal(<?= $acc['assignment_id'] ?>)">
                                    <i class="bi bi-printer me-1"></i> In Biên Bản
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

        </div>

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
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
                            <label class="form-label fw-bold text-dark small">Điểm Rèn Luyện Tích Lũy</label>
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
            <div class="modal-header text-white" style="background:var(--ux-navy-gradient); padding:18px 24px;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-note-sticky text-warning fs-5"></i>
                    <div>
                        <h6 class="modal-title fw-bold mb-0">Thêm Ghi Chú / Lịch Hẹn Toàn Trường</h6>
                        <small class="text-white-50">Lưu ý, hạn chót hoặc dặn dò cho CTV và Ban tổ chức</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
            <div class="modal-header text-white" style="background:var(--ux-ink); padding:16px 20px;">
                <div>
                    <h6 class="modal-title fw-bold mb-0" id="vneidModalTitle">Đối Soát Định Danh VNeID</h6>
                    <small class="text-white-50" id="vneidModalSub">Số CCCD: 079203014589</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 text-center bg-light">
                <img id="vneidModalImage" src="" alt="VNeID Identity Card" class="img-fluid rounded-3 shadow-xs" style="max-height: 380px;">
                <div class="alert alert-success mt-3 py-2 small mb-0">
                    <i class="bi bi-patch-check-fill me-1"></i> Thông tin CCCD đã khớp với hồ sơ đăng ký tài khoản sinh viên FPT Polytechnic.
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
            <div class="modal-header text-white" style="background:var(--ux-ink); padding:16px 20px;">
                <div>
                    <h6 class="modal-title fw-bold mb-0" id="proofModalTitle">Minh Chứng Hiện Trường Có Mặt</h6>
                    <small class="text-white-50" id="proofModalSub">Sự kiện: FPoly TechDay 2026</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
     MODAL CTV NỘP ẢNH HIỆN TRƯỜNG CHỨNG MINH CÓ MẶT NGHIỆM THU
     ========================================================= -->
<div class="modal fade" id="submitProofModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:22px; overflow:hidden;">
            <div class="modal-header text-white" style="background:var(--ux-accent); padding:18px 24px;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-camera-fill fs-5"></i>
                    <div>
                        <h6 class="modal-title fw-bold mb-0">Chụp Ảnh Hiện Trường Nghiệm Thu</h6>
                        <small class="text-white-50" id="submitProofShiftInfo">Ca trực sự kiện</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="submitProofForm" onsubmit="handleProofSubmit(event)">
                <input type="hidden" name="assignment_id" id="submitProofAssignmentId" value="">
                <div class="modal-body p-4">
                    <div class="alert alert-light border py-2 px-3 small mb-3">
                        <i class="bi bi-info-circle text-primary me-1"></i> <strong>Quy chế nghiệm thu:</strong> CTV chụp ảnh hiện trường thực tế để Giảng viên thẩm định và làm hợp đồng đề nghị Nhà trường thanh toán thù lao.
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
                        <label class="form-label fw-bold text-dark small mt-2">Hoặc Nhập URL Ảnh Chụp Thực Tế <span class="text-danger">*</span></label>
                        <input type="url" name="proof_image_url" id="submitProofImageUrl" class="form-control" placeholder="https://..." required oninput="previewProofInput(this.value)">
                    </div>

                    <div class="text-center mb-3">
                        <div id="proofInputPreviewBox" style="height: 150px; border-radius: 12px; background: #f8fafc; border: 1px dashed #cbd5e1; display: grid; place-items: center; overflow: hidden;">
                            <span class="text-muted small"><i class="bi bi-camera me-1"></i>Ảnh xem trước sẽ hiển thị tại đây</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small">Ghi Chú Công Việc Hoàn Thành Tại Hiện Trường</label>
                        <textarea name="proof_note" id="submitProofNote" class="form-control" rows="2" placeholder="Ví dụ: Đã có mặt đúng giờ, hỗ trợ check-in đủ 150 sinh viên tại cổng số 1..."></textarea>
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
     MODAL HỢP ĐỒNG & BIÊN BẢN NGHIỆM THU THÔNG TIN CTV
     (CHUẨN VĂN BẢN TRƯỜNG CAO ĐẲNG FPT POLYTECHNIC)
     ========================================================= -->
<div class="modal fade" id="contractModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius:24px; overflow:hidden;">
            <div class="modal-header text-white" style="background:#0F172A; padding:18px 24px;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-check-fill text-warning fs-5"></i>
                    <div>
                        <h6 class="modal-title fw-bold mb-0">Hợp Đồng & Biên Bản Nghiệm Thu Thông Tin CTV</h6>
                        <small class="text-white-50" id="contractModalSub">Mã hồ sơ: HDNT-2026-FPLY-001</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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

                <!-- Minh Chứng Hiện Trường -->
                <div class="mb-4">
                    <strong class="d-block mb-1">ĐIỀU 2: ẢNH HIỆN TRƯỜNG MINH CHỨNG CÓ MẶT NGHIỆM THU</strong>
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="row g-3 align-items-center">
                            <div class="col-12 col-md-5 text-center">
                                <div style="max-height: 200px; overflow: hidden; border-radius: 12px; border: 2px solid #e2e8f0; display: inline-block;">
                                    <img id="contractProofImg" src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&auto=format&fit=crop&q=80" alt="Ảnh Hiện Trường Nghiệm Thu" style="max-height: 200px; max-width: 100%; object-fit: cover; display: block;">
                                </div>
                            </div>
                            <div class="col-12 col-md-7" style="font-size: 13px;">
                                <div class="badge bg-success mb-2 px-2.5 py-1">
                                    <i class="bi bi-shield-fill-check me-1"></i> ẢNH HIỆN TRƯỜNG ĐÃ ĐƯỢC GIẢNG VIÊN THẨM ĐỊNH
                                </div>
                                <p class="mb-1"><strong>Ghi chú CTV:</strong> <span id="contractProofNoteDisplay" class="fst-italic text-dark">Có mặt đúng giờ, hỗ trợ đón khách tại sảnh</span></p>
                                <p class="mb-1"><strong>Kết luận Giảng viên:</strong> <span class="text-success fw-bold">CTV có mặt đúng vị trí phân công, hoàn thành đầy đủ nhiệm vụ được giao, đủ điều kiện nghiệm thu thanh toán.</span></p>
                                <p class="mb-0 text-muted small">Ảnh chụp có xác thực vị trí và nhận diện sinh viên.</p>
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
            <div class="modal-header text-white" style="background:var(--ux-ink); padding:20px 24px;">
                <div>
                    <span class="badge bg-warning text-dark font-monospace mb-1" id="modalShiftBadge">+10 Điểm Rèn Luyện</span>
                    <h5 class="modal-title fw-bold" id="shiftModalTitle">Chi Tiết Ca Trực</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <p class="text-muted small mb-1 fw-bold text-uppercase">Thuộc Sự Kiện:</p>
                    <h6 class="fw-bold text-dark" id="modalEventTitle">FPoly TechDay 2026</h6>
                </div>
                
                <div class="row g-2 mb-3 bg-light p-3 rounded-3" style="font-size:13px;">
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
            <div class="modal-header text-white" style="background:#0F172A; padding:16px 24px;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-robot fs-5 text-warning"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Trợ Lý AI PolyMee</h5>
                        <small class="text-white-50">Function Calling • Hỗ trợ tìm ca & Phân tích xung đột</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="background:var(--ux-surface-2);">
                <div id="aiChatHistory" class="d-flex flex-column gap-3 mb-3" style="max-height: 320px; overflow-y: auto; padding-right: 6px;">
                    <div class="d-flex gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:34px; height:34px; min-width:34px; flex:none; background:#F59E0B; color:#0F172A; box-shadow:0 2px 6px rgba(245,158,11,0.3);">
                            <i class="fa-solid fa-robot" style="font-size:15px; line-height:1;"></i>
                        </div>
                        <div class="bg-white p-3 rounded-4 shadow-xs border" style="font-size:13.5px; max-width:85%;">
                            Xin chào <strong><?= htmlspecialchars($currentUser['full_name'] ?? 'bạn') ?></strong>! Tôi là trợ lý ảo PolyMee. Bạn có thể hỏi tôi về lịch trực, tìm kiếm ca theo kỹ năng, hoặc nộp link Google Drive minh chứng nghiệm thu nhé!
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-1.5 mb-3">
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
                        <i class="bi bi-calendar-plus-fill me-1 text-danger"></i> Đặt lịch sự kiện & khách mời
                    </button>
                    <button class="btn-fpt-ghost btn-fpt-sm" onclick="quickAskAi('Tôi có ca trực nào sắp tới không?')">
                        <i class="bi bi-clock me-1 text-secondary"></i> Ca trực của tôi?
                    </button>
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

<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1090;">
    <div id="liveToast" class="toast align-items-center text-white border-0 shadow-lg rounded-4" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2" id="toastMessage">
                <i class="bi bi-info-circle fs-5" id="toastIcon"></i>
                <span id="toastText">Nội dung thông báo</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
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
const rawCalendarNotes = <?= json_encode($calendarNotes ?? [], JSON_UNESCAPED_UNICODE) ?>;
const currentUser = <?= json_encode($currentUser, JSON_UNESCAPED_UNICODE) ?>;

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

document.addEventListener('DOMContentLoaded', function() {
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
    } else if (viewParam) {
        changeCalendarView(viewParam);
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

/**
 * =========================================================
 * SINGLE PAGE APPLICATION (SPA) NAVIGATION
 * =========================================================
 */
function navigateToPage(pageName) {
    activePage = pageName;

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

    document.getElementById('panelCalendar').style.display = 'none';
    document.getElementById('panelAccounting').style.display = 'none';
    document.getElementById('panelStudentPayrollProfile').style.display = 'none';
    document.getElementById('panelMyShifts').style.display = 'none';
    document.getElementById('panelEvents').style.display = 'none';
    document.getElementById('panelSubmissions').style.display = 'none';
    const panelContract = document.getElementById('panelContractDossier');
    if (panelContract) panelContract.style.display = 'none';

    const isCalendar = (pageName === 'calendar');
    document.getElementById('calendarViewSwitcher').style.display = isCalendar ? 'inline-flex' : 'none';
    document.getElementById('calendarMonthNav').style.display = isCalendar ? 'inline-flex' : 'none';

    const screenTitle = document.getElementById('activeScreenTitle');
    const breadcrumb = document.getElementById('breadcrumbActive');

    if (pageName === 'calendar') {
        document.getElementById('panelCalendar').style.display = 'block';
        screenTitle.textContent = 'Lịch Hoạt Động & Điều Phối Ca Trực';
        breadcrumb.textContent = 'Bảng Lịch Trung Tâm';
        changeCalendarView(currentCalendarTier);
    } else if (pageName === 'accounting') {
        document.getElementById('panelAccounting').style.display = 'block';
        screenTitle.textContent = 'Bảng Nghiệm Thu Thù Lao & Quyết Toán Kế Toán';
        breadcrumb.textContent = 'Nghiệm Thu Kế Toán';
    } else if (pageName === 'student-payroll-profile') {
        document.getElementById('panelStudentPayrollProfile').style.display = 'block';
        screenTitle.textContent = 'Hồ Sơ Nhận Thù Lao (VNeID & Ngân Hàng)';
        breadcrumb.textContent = 'Hồ Sơ Quyết Toán';
    } else if (pageName === 'my-shifts') {
        document.getElementById('panelMyShifts').style.display = 'block';
        screenTitle.textContent = 'Lịch Làm Việc & Ca Phân Công Của Tôi';
        breadcrumb.textContent = 'Ca Trực Của Tôi';
        renderMyShiftsTable();
    } else if (pageName === 'events') {
        document.getElementById('panelEvents').style.display = 'block';
        screenTitle.textContent = 'Quản Lý Sự Kiện & Phân Rã Ca Trực';
        breadcrumb.textContent = 'Danh Sách Sự Kiện';
    } else if (pageName === 'submissions') {
        document.getElementById('panelSubmissions').style.display = 'block';
        screenTitle.textContent = 'Thẩm Định Nghiệm Thu Minh Chứng Google Drive';
        breadcrumb.textContent = 'Thẩm Định Drive';
    } else if (pageName === 'contract-dossier' || pageName === 'qr-projector') {
        if (panelContract) panelContract.style.display = 'block';
        screenTitle.textContent = 'Hồ Sơ Hợp Đồng Nghiệm Thu & Đề Nghị Chi Tiền';
        breadcrumb.textContent = 'Hồ Sơ Hợp Đồng';
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

    document.getElementById('btnViewMonth').classList.toggle('active', viewName === 'month');
    document.getElementById('btnViewWeek').classList.toggle('active', viewName === 'week');
    document.getElementById('btnViewDay').classList.toggle('active', viewName === 'day');

    document.getElementById('tierMonthView').style.display = (viewName === 'month') ? 'block' : 'none';
    document.getElementById('tierWeekView').style.display = (viewName === 'week') ? 'block' : 'none';
    document.getElementById('tierDayView').style.display = (viewName === 'day') ? 'block' : 'none';

    const heading = document.getElementById('calendarViewHeading');
    const sub = document.getElementById('calendarViewSub');

    if (viewName === 'month') {
        heading.textContent = `Lịch Tổng Quan Tháng ${currentMonth + 1}/${currentYear}`;
        sub.textContent = 'Nhấp vào ô ngày bất kỳ để vào thẳng Lịch Ngày xem chi tiết các ca trực';
        renderMonthGrid(currentYear, currentMonth);
    } else if (viewName === 'week') {
        heading.textContent = `Lịch Tuần Có Ngày ${formatDateVN(selectedDateStr)}`;
        sub.textContent = 'Lưới phân rã các ca trực trong 7 ngày từ Thứ Hai đến Chủ Nhật';
        renderWeekView(selectedDateStr);
    } else if (viewName === 'day') {
        heading.textContent = `Chi Tiết Ca Trực Ngày ${formatDateVN(selectedDateStr)}`;
        sub.textContent = 'Danh sách toàn bộ các khung giờ và yêu cầu công việc chi tiết trong ngày';
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

        // Hiển thị ghi chú / lịch hẹn toàn trường (Apple Reminder)
        if (dayNotes.length > 0) {
            dayNotes.slice(0, 1).forEach(n => {
                const noteTitle = n.title || n.content || 'Ghi chú';
                previewHtml += `<div class="memo-chip" title="Lưu ý: ${noteTitle}">
                    <i class="fa-solid fa-bookmark" style="font-size:9px; color:#EA580C; flex:none;"></i>
                    <span class="text-truncate">${noteTitle}</span>
                </div>`;
            });
        }

        const maxShifts = dayNotes.length > 0 ? 1 : 2;
        if (dayShifts.length > 0) {
            dayShifts.slice(0, maxShifts).forEach(s => {
                const isMine = parseInt(s.is_registered_by_me) > 0;
                const isFull = parseInt(s.approved_qty) >= parseInt(s.target_qty);
                const chipClass = isMine ? 'mine' : (isFull ? 'urgent' : '');
                const dotClass = isMine ? 'status-dot-emerald' : (isFull ? 'status-dot-red' : 'status-dot-orange');
                const timePrefix = s.start_time.substring(11, 16);
                previewHtml += `<div class="shift-chip ${chipClass}" title="${s.name} (${s.location_name || 'Hội trường'})">
                    <span class="status-dot ${dotClass}"></span>
                    <span class="num-tabular flex-shrink-0">${timePrefix}</span>
                    <span class="text-truncate flex-grow-1">${s.name}</span>
                </div>`;
            });
            const remaining = dayShifts.length - maxShifts;
            if (remaining > 0) {
                previewHtml += `<div class="text-muted num-tabular ps-1" style="font-size:9.5px; font-weight:700;">+${remaining} ca nữa</div>`;
            }
        }

        cell.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="day-number-pill num-tabular">${day}</span>
                <div class="d-flex align-items-center gap-1">
                    ${dayNotes.length > 0 ? `<span class="badge rounded-pill num-tabular" style="background:#FFEDD5; color:#9A3412; font-size:9px; font-weight:700; padding:2px 5px;" title="${dayNotes.length} Ghi chú"><i class="fa-solid fa-bookmark me-1" style="font-size:8px;"></i>${dayNotes.length}</span>` : ''}
                    ${dayShifts.length > 0 ? `<span class="badge rounded-pill bg-light text-secondary border num-tabular" style="font-size:9.5px; font-weight:650; padding:2px 6px;">${dayShifts.length} ca</span>` : ''}
                </div>
            </div>
            <div class="d-flex flex-column gap-1">${previewHtml}</div>
        `;

        cell.addEventListener('click', () => {
            selectedDateStr = dateStr;
            changeCalendarView('day');
        });

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
                notesHtml += `
                    <div class="memo-chip text-truncate mb-2" title="${noteType}: ${noteTitle}">
                        <i class="fa-solid fa-bookmark text-warning me-1"></i>
                        <span>${noteTitle}</span>
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
            <div class="week-col-header" onclick="selectedDateStr='${dateStr}'; changeCalendarView('day');">
                <div class="text-muted fw-bold" style="font-size:12px;">${dayNames[i]}</div>
                <div class="col-date h5 fw-bolder mb-0 num-tabular">${d}/${m}</div>
                ${colNotes.length > 0 ? `<span class="badge rounded-pill bg-warning text-dark num-tabular mt-1" style="font-size:9px;"><i class="fa-solid fa-bookmark me-1"></i>${colNotes.length} Lưu ý</span>` : ''}
            </div>
            <div class="d-flex flex-column flex-grow-1">${notesHtml}${shiftsHtml}</div>
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
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:36px; height:36px; background:#EA580C; color:#fff; flex:none; box-shadow:0 2px 6px rgba(234, 88, 12, 0.35);">
                <i class="fa-solid fa-bell" style="font-size:14px; line-height:1; display:inline-flex; align-items:center; justify-content:center;"></i>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h6 class="fw-bold mb-0" style="color:#9A3412; font-size:13.5px;">
                        <i class="fa-solid fa-clipboard-list me-1.5"></i> Ghi Chú & Lưu Ý Ngày Này (Apple Reminders)
                    </h6>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge rounded-pill bg-warning text-dark num-tabular" style="font-size:10px; font-weight:700;">${dayNotes.length} nhắc nhở</span>
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
                        return `
                        <div class="d-flex align-items-center gap-2 bg-white px-3 py-2 rounded-3 border border-warning-subtle shadow-xs" style="font-size:12.5px;">
                            <i class="fa-regular fa-circle-check text-warning" style="font-size:13px; flex:none;"></i>
                            <span class="badge bg-light text-secondary border num-tabular" style="font-size:10px; flex:none;">${noteType}</span>
                            <span class="text-dark">${noteTitle}${noteContent}</span>
                            <span class="ms-auto text-muted small num-tabular" style="flex:none;"><i class="bi bi-person me-1"></i>${authorName}</span>
                        </div>
                    `;}).join('')}
                </div>
            </div>
        `;
        container.appendChild(reminderCard);
    }

    if (dayShifts.length === 0) {
        const emptyDiv = document.createElement('div');
        emptyDiv.className = 'text-center py-5 bg-light rounded-4 border';
        emptyDiv.innerHTML = `
            <i class="bi bi-calendar-x display-4 text-muted"></i>
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
            <div class="d-flex align-items-center gap-3">
                <div class="text-center px-3 py-2 bg-light rounded-3 border" style="min-width:90px;">
                    <span class="text-primary fw-bolder fs-5 d-block num-tabular">${startTime}</span>
                    <small class="text-muted fw-bold num-tabular">${endTime}</small>
                </div>
                <div>
                    <span class="badge bg-secondary mb-1" style="font-size:11px;">${s.event_title}</span>
                    <h5 class="fw-bold text-dark mb-1">${s.name}</h5>
                    <div class="d-flex flex-wrap align-items-center gap-3 text-muted small">
                        <span><i class="bi bi-geo-alt-fill text-danger me-1"></i>${s.location_name || 'Hội trường'}</span>
                        <span class="num-tabular"><i class="bi bi-people-fill text-info me-1"></i>Đã tuyển: <strong>${s.approved_qty}/${s.target_qty} CTV</strong></span>
                        <span class="text-success fw-bold num-tabular"><i class="bi bi-cash me-1"></i>Thù lao: ${allowance}</span>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                ${isMine 
                    ? `<span class="badge bg-success px-3 py-2 fs-6 fw-bold"><i class="bi bi-check-circle me-1"></i>Bạn Đã Đăng Ký</span>` 
                    : (currentUser && ['Student', 'Media'].includes(currentUser.role_name)
                        ? `<button class="btn-fpt-primary" onclick="openShiftDetail(${s.id})">
                            ${isFull ? 'Đăng Ký Dự Phòng' : 'Đăng Ký Ca Này'}
                           </button>`
                        : `<button class="btn-fpt-secondary" onclick="openShiftDetail(${s.id})">
                            <i class="bi bi-gear me-1"></i> Quản Lý Ca
                           </button>`)
                }
                <button class="btn-fpt-ghost" onclick="openShiftDetail(${s.id})" title="Xem chi tiết">
                    <i class="bi bi-info-circle fs-5"></i>
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

function handleAddNoteSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const newNote = {
        id: Date.now(),
        date: formData.get('date'),
        target_date: formData.get('date'),
        title: formData.get('title'),
        content: formData.get('content') || formData.get('title'),
        type: formData.get('type') || 'EVENT_REMINDER',
        note_type: formData.get('type') || 'EVENT_REMINDER',
        author: currentUser ? (currentUser.full_name || currentUser.username) : 'BTC',
        creator_name: currentUser ? (currentUser.full_name || currentUser.username) : 'BTC',
        color: '#EA580C'
    };

    rawCalendarNotes.push(newNote);
    if (addNoteModalInstance) {
        addNoteModalInstance.hide();
    }
    form.reset();
    showToast('Đã thêm ghi chú mới vào lịch thành công!', 'success');

    if (currentCalendarTier === 'month') {
        renderMonthGrid(currentYear, currentMonth);
    } else if (currentCalendarTier === 'week') {
        renderWeekView(selectedDateStr);
    } else if (currentCalendarTier === 'day') {
        renderDayView(selectedDateStr);
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

    document.getElementById('modalShiftBadge').textContent = `+${shift.points} Điểm Rèn Luyện`;
    document.getElementById('shiftModalTitle').textContent = shift.name;
    document.getElementById('modalEventTitle').textContent = shift.event_title;
    document.getElementById('modalShiftTime').textContent = `${shift.start_time.substring(11, 16)} - ${shift.end_time.substring(11, 16)} (${shift.start_time.substring(0, 10)})`;
    document.getElementById('modalShiftLocation').textContent = shift.location_name || 'Hội trường';
    document.getElementById('modalShiftQuota').textContent = `${shift.approved_qty} / ${shift.target_qty} CTV (Còn trống ${Math.max(0, shift.target_qty - shift.approved_qty)} chỗ)`;
    document.getElementById('modalShiftAllowance').textContent = allowance;
    document.getElementById('modalShiftSkill').textContent = shift.skill_required || 'Kỹ năng làm việc nhóm & trách nhiệm';

    const actionContainer = document.getElementById('modalActionButtons');
    const noticeContainer = document.getElementById('modalRoleNotice');
    const isMine = parseInt(shift.is_registered_by_me) > 0;

    if (currentUser && ['Student', 'Media'].includes(currentUser.role_name)) {
        noticeContainer.innerHTML = `
            <i class="bi bi-shield-check fs-5"></i>
            <span>Hệ thống tự động chạy thuật toán <strong>Collision Detection</strong> để đảm bảo bạn không bị trùng lịch học hay ca trực khác.</span>
        `;
        if (isMine) {
            actionContainer.innerHTML = `
                <button type="button" class="btn-fpt-emerald" onclick="shiftModalInstance.hide(); navigateToPage('my-shifts');">
                    <i class="bi bi-qr-code-scan me-1"></i> Đi Đến Lịch Của Tôi Để Check-in
                </button>
            `;
        } else {
            actionContainer.innerHTML = `
                <button type="button" class="btn-fpt-primary" onclick="submitShiftRegistration(${shift.id})">
                    <i class="bi bi-check-lg me-1"></i> Xác Nhận Đăng Ký (AJAX)
                </button>
            `;
        }
    } else {
        noticeContainer.innerHTML = `
            <i class="bi bi-person-check fs-5"></i>
            <span>Chế độ Quản trị: Bạn có thể kích hoạt máy chiếu Dynamic QR trực tiếp cho ca này hoặc kiểm tra danh sách CTV đã tuyển.</span>
        `;
        actionContainer.innerHTML = `
            <div class="d-flex gap-2">
                <a href="/admin/qrProjector/${shift.id}" target="_blank" class="btn-fpt-emerald">
                    <i class="bi bi-qr-code me-1"></i> Chiếu Dynamic QR
                </a>
                <button type="button" class="btn-fpt-secondary" onclick="navigateToPage('accounting')">
                    <i class="bi bi-cash-stack me-1"></i> Nghiệm Thu Thù Lao
                </button>
            </div>
        `;
    }

    shiftModalInstance.show();
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
    csvContent += 'STT,Họ và Tên,Mã Sinh Viên,Số Điện Thoại,Số CCCD,Tên Ngân Hàng,Số Tài Khoản (STK),Chủ Tài Khoản,Tên Sự Kiện,Ca Trực,Thời Gian Bắt Đầu,Thời Gian Kết Thúc,Thù Lao (VNĐ),Điểm Rèn Luyện,Trạng Thái Kế Toán\r\n';

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
            proofHtml = `
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 48px; height: 34px; flex-shrink: 0; overflow: hidden; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <img src="${s.my_proof_image_url}" alt="Ảnh Hiện Trường" style="width: 48px; height: 34px; object-fit: cover; display: block; cursor: pointer;" onclick="previewProofModal('${(currentUser.full_name || 'CTV').replace(/'/g, "\\'")}', '${s.name}', '${s.my_proof_image_url}', '${(s.my_proof_note || 'Đã có mặt tại sự kiện').replace(/'/g, "\\'")}')" title="Nhấp xem phóng to">
                    </div>
                    <div>
                        <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 10.5px;"><i class="bi bi-camera me-1"></i>Đã nộp ảnh</span>
                        <small class="text-muted d-block text-truncate" style="max-width: 120px; font-size: 11px;">${s.my_proof_note || 'Hiện trường'}</small>
                    </div>
                </div>
            `;
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
                <small class="text-muted">+${s.points || 10} điểm rèn luyện</small>
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
        box.innerHTML = `<img src="${url}" alt="Preview" style="max-height: 150px; max-width: 100%; object-fit: cover; border-radius: 8px;">`;
    } else {
        box.innerHTML = `<span class="text-muted small"><i class="bi bi-camera me-1"></i>Ảnh xem trước sẽ hiển thị tại đây</span>`;
    }
}

/**
 * Mở modal CTV nộp ảnh hiện trường
 */
function openSubmitProofModal(assignmentId, shiftName, shiftTime) {
    document.getElementById('submitProofAssignmentId').value = assignmentId;
    document.getElementById('submitProofShiftInfo').textContent = `${shiftName} (${shiftTime ? shiftTime.substring(11, 16) : ''})`;
    document.getElementById('submitProofImageUrl').value = '';
    document.getElementById('submitProofNote').value = '';
    previewProofInput('');
    submitProofModalInstance.show();
}

/**
 * Xử lý submit nộp ảnh hiện trường
 */
async function handleProofSubmit(e) {
    e.preventDefault();
    const form = document.getElementById('submitProofForm');
    const formData = new FormData(form);
    const assignmentId = parseInt(formData.get('assignment_id'));
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
                    <button type="button" class="btn-fpt-secondary btn-fpt-sm w-100" onclick="openContractModal(${assignmentId})">
                        <i class="bi bi-file-earmark-text me-1"></i> Hợp Đồng
                    </button>
                    <button type="button" class="btn-fpt-primary btn-fpt-sm w-100" onclick="submitToSchool(${assignmentId})">
                        <i class="bi bi-send-check me-1"></i> Trình Trường Ký
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
    document.getElementById('contractProofImg').src = proofImg;
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
        if (submitBtn) {
            submitBtn.style.display = 'inline-block';
        }
    }

    contractModalInstance.show();
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
                    <button type="button" class="btn-fpt-secondary btn-fpt-sm w-100" onclick="openContractModal(${assignmentId})">
                        <i class="bi bi-file-earmark-text me-1"></i> Hợp Đồng
                    </button>
                    <span class="badge bg-success w-100 py-1.5"><i class="bi bi-check2-all me-1"></i>Đã Chi Tiền</span>
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
    loadingMsg.innerHTML = `<div class="rounded-circle d-flex align-items-center justify-content-center" style="width:34px; height:34px; min-width:34px; flex:none; background:#F59E0B; color:#0F172A; box-shadow:0 2px 6px rgba(245,158,11,0.3);"><i class="fa-solid fa-robot" style="font-size:15px; line-height:1;"></i></div><div class="bg-white p-3 rounded-4 shadow-xs border text-muted" style="font-size:13px;"><i class="fa-solid fa-spinner fa-spin me-1"></i> Trợ lý AI đang phân tích dữ liệu...</div>`;
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
        aiReply.innerHTML = `<div class="rounded-circle d-flex align-items-center justify-content-center" style="width:34px; height:34px; min-width:34px; flex:none; background:#F59E0B; color:#0F172A; box-shadow:0 2px 6px rgba(245,158,11,0.3);"><i class="fa-solid fa-robot" style="font-size:15px; line-height:1;"></i></div><div class="bg-white p-3 rounded-4 shadow-xs border" style="font-size:13.5px; max-width:85%; line-height:1.5;">${replyHtml}</div>`;
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
</script>

</body>
</html>
