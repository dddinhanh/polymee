<?php
/**
 * PolyMee - Header Layout
 * FPT Polytechnic Brand Theme (Orange #F26F21 & Deep Navy #0F172A)
 */
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="vi" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'PolyMee') ?> - FPT Polytechnic</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- QRCode.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <!-- Tailwind CSS with Custom FPoly Theme -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        fpoly: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            500: '#f97316',
                            primary: '#F26F21',
                            hover: '#d95a12',
                            dark: '#c2410c',
                            navy: '#0F172A',
                            surface: '#1E293B'
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="flex flex-col min-h-screen text-slate-800 antialiased selection:bg-orange-500 selection:text-white">

<!-- Top Announcement Bar -->
<div class="bg-gradient-to-r from-fpoly-navy via-slate-900 to-fpoly-navy text-white text-xs py-2 px-4 border-b border-slate-800">
    <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-2">
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-orange-500/20 text-orange-400 border border-orange-500/30">
                <i class="fa-solid fa-graduation-cap mr-1"></i> WEB212
            </span>
            <span class="hidden sm:inline text-slate-300">Phát triển dự án Website với AI • <?= FPOLY_CAMPUS ?></span>
        </div>
        <div class="flex items-center gap-4 text-slate-300">
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-[11px]">Dynamic QR 30s Active</span>
            </div>
            <span class="text-slate-600">|</span>
            <div class="flex items-center gap-1 text-[11px]">
                <i class="fa-solid fa-phone text-orange-400"></i>
                <span>Hotline: <?= FPOLY_HOTLINE ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Main Navigation Bar -->
<header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            
            <!-- Logo & Brand -->
            <div class="flex items-center gap-8">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-fpoly-primary to-orange-400 flex items-center justify-center text-white font-black text-xl shadow-md shadow-orange-500/30 group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-calendar-check text-lg"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="font-extrabold text-xl tracking-tight text-slate-900 group-hover:text-fpoly-primary transition-colors">Poly<span class="text-fpoly-primary">Mee</span></span>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-orange-100 text-orange-700">AI</span>
                        </div>
                        <p class="text-[10px] text-slate-500 uppercase tracking-wider font-semibold">Event & CTV Hub</p>
                    </div>
                </a>

                <!-- Nav Links Desktop -->
                <nav class="hidden md:flex items-center gap-1">
                    <a href="/dashboard" class="px-3 py-2 rounded-lg text-sm font-bold text-fpoly-primary bg-orange-50 hover:bg-orange-100 transition-colors">
                        <i class="fa-solid fa-calendar-week mr-1.5 text-fpoly-primary"></i>Bảng Lịch (Dashboard)
                        <span class="ml-1 px-1.5 py-0.2 rounded text-[10px] bg-orange-500 text-white font-bold">Mới</span>
                    </a>
                    <a href="/" class="px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:text-fpoly-primary hover:bg-orange-50/60 transition-colors">
                        <i class="fa-solid fa-compass mr-1.5 text-slate-400"></i>Sự Kiện
                    </a>
                    <a href="/shift/myShifts" class="px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:text-fpoly-primary hover:bg-orange-50/60 transition-colors">
                        <i class="fa-solid fa-calendar-days mr-1.5 text-slate-400"></i>Lịch Trực Của Tôi
                    </a>
                    <a href="/admin/dashboard" class="px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:text-fpoly-primary hover:bg-orange-50/60 transition-colors">
                        <i class="fa-solid fa-chart-line mr-1.5 text-slate-400"></i>Thẩm Định & Quản Trị
                    </a>
                    <a href="/admin/qrProjector" target="_blank" class="px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:text-fpoly-primary hover:bg-orange-50/60 transition-colors">
                        <i class="fa-solid fa-qrcode mr-1.5 text-slate-400"></i>Máy Chiếu QR
                        <span class="ml-1 px-1.5 py-0.2 rounded text-[10px] bg-emerald-100 text-emerald-700 font-bold">30s</span>
                    </a>
                </nav>
            </div>

            <!-- User Info & Demo Switcher Dropdown -->
            <div class="flex items-center gap-3">
                
                <!-- Quick Demo Switcher -->
                <div class="relative" id="userSwitcherDropdown">
                    <button type="button" onclick="toggleDropdown('demoUserMenu')" class="flex items-center gap-2 pl-2 pr-3 py-1.5 bg-slate-100 hover:bg-slate-200/80 rounded-full text-xs font-semibold text-slate-700 transition border border-slate-200">
                        <span class="w-6 h-6 rounded-full bg-gradient-to-tr from-fpoly-primary to-orange-400 text-white flex items-center justify-center font-bold text-[11px]">
                            <?= mb_substr($currentUser['full_name'] ?? 'U', 0, 1) ?>
                        </span>
                        <div class="text-left hidden sm:block">
                            <span class="font-bold text-slate-800 leading-tight block"><?= htmlspecialchars($currentUser['full_name'] ?? 'Khách') ?></span>
                            <span class="text-[10px] text-slate-500 font-medium">Vai trò: <?= htmlspecialchars($currentUser['role_name'] ?? 'Student') ?></span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 ml-1"></i>
                    </button>

                    <!-- Dropdown Content -->
                    <div id="demoUserMenu" class="hidden absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-50 animate-in fade-in duration-150">
                        <div class="px-4 py-2 border-b border-slate-100">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Chuyển Tài Khoản Demo (Hội đồng)</p>
                            <p class="text-xs text-slate-600 mt-0.5">Click để hoán đổi role tức thì không cần mật khẩu:</p>
                        </div>
                        
                        <div class="py-1">
                            <a href="/home/switchUser/3" class="flex items-center gap-3 px-4 py-2 text-xs hover:bg-orange-50 text-slate-700 <?= ($currentUser['id'] ?? 0) === 3 ? 'bg-orange-50/80 font-bold text-fpoly-primary' : '' ?>">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">PA</div>
                                <div>
                                    <div class="font-bold">Đinh Nguyễn Phương Anh</div>
                                    <div class="text-[10px] text-slate-500">PI03084 • CTV Media / Sinh viên</div>
                                </div>
                            </a>
                            <a href="/home/switchUser/4" class="flex items-center gap-3 px-4 py-2 text-xs hover:bg-orange-50 text-slate-700 <?= ($currentUser['id'] ?? 0) === 4 ? 'bg-orange-50/80 font-bold text-fpoly-primary' : '' ?>">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xs">ĐM</div>
                                <div>
                                    <div class="font-bold">Lê Đức Mạnh</div>
                                    <div class="text-[10px] text-slate-500">PS20262 • CTV Lễ tân / Sinh viên</div>
                                </div>
                            </a>
                            <a href="/home/switchUser/1" class="flex items-center gap-3 px-4 py-2 text-xs hover:bg-orange-50 text-slate-700 <?= ($currentUser['id'] ?? 0) === 1 ? 'bg-orange-50/80 font-bold text-fpoly-primary' : '' ?>">
                                <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-xs">AD</div>
                                <div>
                                    <div class="font-bold">Quản Trị Viên (Admin)</div>
                                    <div class="text-[10px] text-slate-500">ADMIN001 • Toàn quyền duyệt & QR</div>
                                </div>
                            </a>
                            <a href="/home/switchUser/2" class="flex items-center gap-3 px-4 py-2 text-xs hover:bg-orange-50 text-slate-700 <?= ($currentUser['id'] ?? 0) === 2 ? 'bg-orange-50/80 font-bold text-fpoly-primary' : '' ?>">
                                <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-xs">GV</div>
                                <div>
                                    <div class="font-bold">Thầy Phan Hoàng Khải</div>
                                    <div class="text-[10px] text-slate-500">GV0102 • Ban tổ chức (Organizer)</div>
                                </div>
                            </a>
                        </div>
                        <div class="border-t border-slate-100 py-1">
                            <a href="/auth/login" class="flex items-center gap-2 px-4 py-1.5 text-xs text-slate-600 hover:text-fpoly-primary hover:bg-orange-50">
                                <i class="fa-solid fa-arrow-right-to-bracket text-slate-400"></i>
                                <span>Trang Đăng Nhập</span>
                            </a>
                            <a href="/auth/register" class="flex items-center gap-2 px-4 py-1.5 text-xs text-slate-600 hover:text-emerald-600 hover:bg-emerald-50">
                                <i class="fa-solid fa-user-plus text-slate-400"></i>
                                <span>Đăng Ký CTV Mới</span>
                            </a>
                            <a href="/auth/logout" class="flex items-center gap-2 px-4 py-1.5 text-xs text-rose-600 hover:bg-rose-50 font-semibold">
                                <i class="fa-solid fa-power-off text-rose-500"></i>
                                <span>Đăng Xuất</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Open AI Assistant Button in Nav -->
                <button type="button" onclick="toggleAiWidget()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-fpoly-hover text-white text-xs font-bold shadow-sm shadow-orange-500/20 transition-all">
                    <i class="fa-solid fa-wand-magic-sparkles text-xs"></i>
                    <span class="hidden sm:inline">Trợ lý AI</span>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Flash Message Notifications -->
<?php if (!empty($flash)): ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
    <?php 
    $isSuccess = ($flash['type'] === 'success');
    $bgColor = $isSuccess ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : 'bg-rose-50 border-rose-200 text-rose-800';
    $icon = $isSuccess ? 'fa-circle-check text-emerald-500' : 'fa-circle-exclamation text-rose-500';
    ?>
    <div class="flex items-center justify-between p-4 rounded-xl border <?= $bgColor ?> shadow-sm animate-in fade-in">
        <div class="flex items-center gap-3">
            <i class="fa-solid <?= $icon ?> text-lg"></i>
            <span class="text-sm font-semibold"><?= htmlspecialchars($flash['message']) ?></span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-600 p-1">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
</div>
<?php endif; ?>

<!-- Main Content Area -->
<main class="flex-grow">
