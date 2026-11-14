<?php
/**
 * PolyMee - Home View
 * Danh sách sự kiện, bộ lọc tìm kiếm & chỉ số tổng quan
 */
declare(strict_types=1);
?>

<!-- Hero Banner Section -->
<section class="relative bg-gradient-to-b from-[#F5F5F7] via-white to-[#F5F5F7] text-[#1D1D1F] pt-12 pb-20 px-4 sm:px-6 lg:px-8 overflow-hidden border-b border-[#E5E5EA]">
    <!-- Background Glow Accent -->
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-orange-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-7 space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-orange-500/20 border border-orange-500/30 text-orange-400 text-xs font-bold tracking-wide uppercase">
                    <i class="fa-solid fa-sparkles"></i>
                    <span>Hệ Thống Số Hóa Hoạt Động Sự Kiện 4.0</span>
                </div>
                
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#1D1D1F] tracking-tight leading-tight">
                    Điều Phối Ca Trực & <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 via-orange-500 to-amber-300">
                        Cộng Tác Viên Thông Minh
                    </span>
                </h1>
                
                <p class="text-base text-[#6E6E73] max-w-2xl leading-relaxed">
                    Nền tảng chính thức hỗ trợ sinh viên FPT Polytechnic đăng ký ca trực sự kiện, tự động ngăn chặn trùng lặp thời gian thực bằng thuật toán phân đoạn thời gian (Interval Collision), điểm danh bảo mật Dynamic QR và Trợ lý AI PolyMee.
                </p>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <a href="#eventListSection" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-fpoly-primary hover:bg-fpoly-hover text-white text-sm font-bold shadow-lg shadow-orange-500/30 transition-all hover:scale-[1.02]">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>Khám Phá Ca Trực</span>
                    </a>
                    <button type="button" onclick="toggleAiWidget()" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-[#1D1D1F] hover:bg-black text-white text-sm font-semibold border border-[#1D1D1F] transition-all">
                        <i class="fa-solid fa-wand-magic-sparkles text-orange-400"></i>
                        <span>Hỏi Trợ Lý AI</span>
                    </button>
                </div>
            </div>

            <!-- Stats Metric Cards -->
            <div class="lg:col-span-5 grid grid-cols-2 gap-4">
                <div class="p-5 rounded-2xl bg-white border border-[#E5E5EA] shadow-xs hover:border-orange-500/40 transition">
                    <div class="flex items-center justify-between text-slate-500 mb-2">
                        <span class="text-xs font-semibold uppercase">Tổng Sự Kiện</span>
                        <div class="w-8 h-8 rounded-lg bg-orange-500/20 text-orange-500 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-calendar"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-extrabold text-[#1D1D1F]"><?= number_format($stats['total_events'] ?? 0) ?></div>
                    <div class="text-[11px] text-emerald-600 mt-1 flex items-center gap-1 font-medium">
                        <i class="fa-solid fa-arrow-trend-up"></i>
                        <span>Đang diễn ra học kỳ</span>
                    </div>
                </div>

                <div class="p-5 rounded-2xl bg-white border border-[#E5E5EA] shadow-xs hover:border-orange-500/40 transition">
                    <div class="flex items-center justify-between text-slate-500 mb-2">
                        <span class="text-xs font-semibold uppercase">Tổng Ca Trực</span>
                        <div class="w-8 h-8 rounded-lg bg-blue-500/20 text-blue-500 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-extrabold text-[#1D1D1F]"><?= number_format($stats['total_shifts'] ?? 0) ?></div>
                    <div class="text-[11px] text-slate-500 mt-1 font-medium">
                        <span>Đa dạng khung giờ</span>
                    </div>
                </div>

                <div class="p-5 rounded-2xl bg-white border border-[#E5E5EA] shadow-xs hover:border-orange-500/40 transition">
                    <div class="flex items-center justify-between text-slate-500 mb-2">
                        <span class="text-xs font-semibold uppercase">Tỷ Lệ Lấp Đầy</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-500 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-chart-pie"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-extrabold text-emerald-600"><?= $stats['fill_rate_percent'] ?? 0 ?>%</div>
                    <div class="text-[11px] text-slate-500 mt-1 font-medium">
                        <span>Chỉ tiêu tuyển CTV</span>
                    </div>
                </div>

                <div class="p-5 rounded-2xl bg-white border border-[#E5E5EA] shadow-xs hover:border-orange-500/40 transition">
                    <div class="flex items-center justify-between text-slate-500 mb-2">
                        <span class="text-xs font-semibold uppercase">CTV Tham Gia</span>
                        <div class="w-8 h-8 rounded-lg bg-purple-500/20 text-purple-500 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-extrabold text-[#1D1D1F]"><?= number_format($stats['active_collaborators'] ?? 0) ?></div>
                    <div class="text-[11px] text-purple-600 mt-1 font-medium">
                        <span>Sinh viên FPoly</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Search & Filter Container (Floated slightly over hero) -->
<section id="eventListSection" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200/80 p-5">
        <form method="GET" action="/" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            
            <!-- Search Keyword Input -->
            <div class="md:col-span-6 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </div>
                <input type="text" name="keyword" value="<?= htmlspecialchars($filterKeyword ?? '') ?>" placeholder="Tìm tên sự kiện, công nghệ, chủ đề workshop..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 placeholder-slate-400">
            </div>

            <!-- Location Dropdown -->
            <div class="md:col-span-4 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-location-dot text-sm"></i>
                </div>
                <select name="location_id" class="w-full pl-10 pr-8 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 bg-white text-slate-700">
                    <option value="">-- Tất cả địa điểm / Hội trường --</option>
                    <?php foreach ($locations as $loc): ?>
                        <option value="<?= $loc['id'] ?>" <?= (isset($filterLocation) && (int)$filterLocation === (int)$loc['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($loc['name']) ?> (Sức chứa: <?= $loc['capacity'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Submit Button & Reset -->
            <div class="md:col-span-2 flex items-center gap-2">
                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-fpoly-primary hover:bg-fpoly-hover text-white text-sm font-bold shadow-md shadow-orange-500/20 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Lọc</span>
                </button>
                <?php if (!empty($filterKeyword) || !empty($filterLocation)): ?>
                    <a href="/" class="p-2.5 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-100 transition" title="Đặt lại bộ lọc">
                        <i class="fa-solid fa-rotate-left text-sm"></i>
                    </a>
                <?php endif; ?>
            </div>

        </form>
    </div>
</section>

<!-- Event Grid List -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Sự Kiện Sắp Diễn Ra</h2>
            <p class="text-sm text-slate-500 mt-1">Chọn sự kiện để xem chi tiết các ca trực cần hỗ trợ và đăng ký ngay</p>
        </div>
        <div class="text-xs text-slate-500 font-medium bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">
            Tìm thấy: <strong class="text-slate-800"><?= count($events) ?></strong> sự kiện
        </div>
    </div>

    <?php if (empty($events)): ?>
        <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-12 text-center">
            <div class="w-16 h-16 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fa-solid fa-calendar-xmark"></i>
            </div>
            <h3 class="text-base font-bold text-slate-800 mb-1">Không tìm thấy sự kiện phù hợp</h3>
            <p class="text-xs text-slate-500 mb-4 max-w-sm mx-auto">Vui lòng thử từ khóa khác hoặc bỏ chọn bộ lọc để xem toàn bộ sự kiện hiện có.</p>
            <a href="/" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#1D1D1F] text-white text-xs font-semibold hover:bg-slate-800 transition">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Xem tất cả sự kiện</span>
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($events as $event): ?>
                <?php
                $target = (int)($event['total_target_qty'] ?? 1);
                $approved = (int)($event['total_approved_qty'] ?? 0);
                $percent = $target > 0 ? min(100, round(($approved / $target) * 100)) : 0;
                $startDate = date('d/m/Y', strtotime($event['start_time']));
                $startTime = date('H:i', strtotime($event['start_time']));
                $endTime = date('H:i', strtotime($event['end_time']));
                ?>
                <div class="group bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-orange-500/40 transition-all duration-300 flex flex-col overflow-hidden">
                    
                    <!-- Event Banner Image / Fallback Header -->
                    <div class="relative h-48 bg-gradient-to-tr from-slate-800 to-slate-900 overflow-hidden">
                        <?php if (!empty($event['banner_url']) && str_starts_with($event['banner_url'], 'http')): ?>
                            <img src="<?= htmlspecialchars($event['banner_url']) ?>" alt="<?= htmlspecialchars($event['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90">
                        <?php else: ?>
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-gradient-to-br from-slate-900 via-orange-950/20 to-slate-900 p-6 text-center">
                                <i class="fa-solid fa-champagne-glasses text-4xl text-orange-400/60 mb-2"></i>
                                <span class="text-xs font-semibold text-slate-300">FPT Polytechnic Event</span>
                            </div>
                        <?php endif; ?>

                        <!-- Floating Badges -->
                        <div class="absolute top-3 left-3 flex items-center gap-1.5">
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-white/90 backdrop-blur-md text-slate-900 shadow-sm">
                                <i class="fa-regular fa-calendar mr-1 text-orange-500"></i><?= $startDate ?>
                            </span>
                        </div>
                        <div class="absolute top-3 right-3">
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-emerald-500 text-white shadow-sm">
                                <?= htmlspecialchars($event['status'] ?? 'Published') ?>
                            </span>
                        </div>

                        <!-- Location overlay bar -->
                        <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-3 text-white">
                            <div class="flex items-center gap-1.5 text-xs">
                                <i class="fa-solid fa-location-dot text-orange-400"></i>
                                <span class="font-medium truncate"><?= htmlspecialchars($event['location_name'] ?? 'Khuôn viên FPoly') ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 flex-grow flex flex-col justify-between">
                        <div>
                            <!-- Time Range -->
                            <div class="text-xs font-semibold text-orange-600 mb-1 flex items-center gap-1.5">
                                <i class="fa-regular fa-clock"></i>
                                <span><?= $startTime ?> - <?= $endTime ?></span>
                            </div>

                            <!-- Title -->
                            <h3 class="text-lg font-bold text-slate-900 group-hover:text-fpoly-primary transition-colors line-clamp-2 leading-snug mb-2">
                                <a href="/event/detail/<?= $event['id'] ?>">
                                    <?= htmlspecialchars($event['title']) ?>
                                </a>
                            </h3>

                            <!-- Short Description -->
                            <p class="text-xs text-slate-500 line-clamp-2 mb-4 leading-relaxed">
                                <?= htmlspecialchars($event['description'] ?? 'Chương trình phối hợp cộng tác viên sinh viên FPT Polytechnic.') ?>
                            </p>
                        </div>

                        <!-- Progress & Shifts breakdown -->
                        <div class="pt-4 border-t border-slate-100">
                            <div class="flex items-center justify-between text-xs text-slate-600 mb-1.5">
                                <span class="font-medium flex items-center gap-1">
                                    <i class="fa-solid fa-users-gear text-slate-400"></i>
                                    <span>Đã tuyển: <strong><?= $approved ?></strong>/<?= $target ?> CTV</span>
                                </span>
                                <span class="font-bold <?= $percent >= 100 ? 'text-rose-500' : 'text-emerald-600' ?>">
                                    <?= $percent ?>%
                                </span>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden mb-4">
                                <div class="bg-gradient-to-r from-orange-500 to-amber-400 h-1.5 rounded-full transition-all duration-500" style="width: <?= $percent ?>%"></div>
                            </div>

                            <!-- CTA Button -->
                            <a href="/event/detail/<?= $event['id'] ?>" class="w-full py-2.5 px-4 rounded-xl bg-[#1D1D1F] group-hover:bg-fpoly-primary text-white text-xs font-bold text-center block transition shadow-sm flex items-center justify-center gap-2">
                                <span>Xem Chi Tiết & Đăng Ký Ca</span>
                                <i class="fa-solid fa-arrow-right text-[11px] group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
