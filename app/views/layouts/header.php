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
<div class="bg-[#FAFAFC] text-[#1D1D1F] text-xs py-2 px-4 border-b border-[#E5E5EA]">
    <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-2">
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-orange-500/20 text-orange-400 border border-orange-500/30">
                <i class="fa-solid fa-graduation-cap mr-1"></i> WEB212
            </span>
            <span class="hidden sm:inline text-[#6E6E73]">Phát triển dự án Website với AI • <?= FPOLY_CAMPUS ?></span>
        </div>
        <div class="flex items-center gap-4 text-[#6E6E73]">
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-[11px]">Nghiệm thu minh chứng số</span>
            </div>
            <span class="text-[#C7C7CC]">|</span>
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
            <div class="flex items-center gap-6">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="h-10 px-2.5 py-1 flex items-center justify-center transition-all" style="border: none !important; box-shadow: none !important;">
                        <img src="/assets/images/fpt_polytechnic_logo.png" alt="FPT Polytechnic Logo" class="h-7 w-auto object-contain">
                    </div>
                    <div class="hidden sm:block">
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
                </nav>
            </div>

            <!-- User Profile & Auth Controls (Chỉ Login / Logout bên trong) -->
            <div class="flex items-center gap-3">
                <?php if (!empty($currentUser['id']) && (int)$currentUser['id'] > 0 && ($currentUser['role_name'] ?? '') !== 'Guest'): ?>
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

                    <!-- Dropdown Content: Clean Account & Logout -->
                    <div id="demoUserMenu" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-50 animate-in fade-in duration-150">
                        <div class="px-4 py-2 border-b border-slate-100">
                            <p class="text-xs font-bold text-slate-800 leading-tight block"><?= htmlspecialchars($currentUser['full_name']) ?></p>
                            <p class="text-[10px] text-slate-500 mt-0.5"><?= htmlspecialchars($currentUser['email'] ?? '') ?></p>
                            <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-orange-100 text-orange-700">
                                <?= htmlspecialchars($currentUser['role_name'] ?? 'CTV') ?>
                            </span>
                        </div>
                        
                        <div class="py-1">
                            <a href="/dashboard" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 hover:bg-orange-50 hover:text-fpoly-primary transition-colors">
                                <i class="fa-solid fa-gauge text-slate-400"></i>
                                <span>Bảng Điều Khiển</span>
                            </a>
                            <a href="/dashboard" onclick="localStorage.setItem('polymee_last_page', 'profile-settings')" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 hover:bg-orange-50 hover:text-fpoly-primary transition-colors">
                                <i class="fa-solid fa-user-gear text-slate-400"></i>
                                <span>Cài Đặt Tài Khoản</span>
                            </a>
                        </div>
                        
                        <div class="border-t border-slate-100 py-1">
                            <a href="/auth/logout" class="flex items-center gap-2.5 px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 font-bold transition-colors">
                                <i class="fa-solid fa-power-off text-rose-500"></i>
                                <span>Đăng Xuất</span>
                            </a>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <a href="/auth/login" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold transition shadow-sm">
                    <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                    <span>Đăng Nhập</span>
                </a>
                <?php endif; ?>

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
        <button type="button" onclick="this.parentElement.remove()" class="text-slate-400 hover:text-[#C7C7CC] p-1">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
</div>
<?php endif; ?>

<!-- Main Content Area -->
<main class="flex-grow">
