<?php
/**
 * PolyMee - Event Detail & Shift Breakdown View
 * Phân rã ca trực, hiển thị trạng thái tuyển dụng và đăng ký trực tiếp
 */
declare(strict_types=1);

$startDate = date('d/m/Y', strtotime($event['start_time']));
$startTime = date('H:i', strtotime($event['start_time']));
$endTime = date('H:i', strtotime($event['end_time']));
?>

<!-- Breadcrumb -->
<div class="bg-slate-100 border-b border-slate-200 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center text-xs text-slate-500 gap-2">
            <a href="/" class="hover:text-fpoly-primary transition">Trang chủ</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <span class="hover:text-slate-700">Sự kiện</span>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <span class="text-slate-900 font-semibold truncate max-w-md"><?= htmlspecialchars($event['title']) ?></span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Main Event Information (Left 8 Cols) -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Event Hero Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="relative h-64 sm:h-80 bg-[#F5F5F7]">
                    <?php if (!empty($event['banner_url']) && str_starts_with($event['banner_url'], 'http')): ?>
                        <img src="<?= htmlspecialchars($event['banner_url']) ?>" alt="<?= htmlspecialchars($event['title']) ?>" class="w-full h-full object-cover opacity-90">
                    <?php else: ?>
                        <div class="w-full h-full flex flex-col items-center justify-center text-[#86868B] bg-gradient-to-tr from-[#F5F5F7] via-orange-50/50 to-[#F5F5F7] p-8 text-center">
                            <i class="fa-solid fa-bullhorn text-5xl text-orange-500/60 mb-3"></i>
                            <h2 class="text-xl font-bold text-white"><?= htmlspecialchars($event['title']) ?></h2>
                            <p class="text-xs text-slate-400 mt-1">Sự kiện chính thức FPT Polytechnic</p>
                        </div>
                    <?php endif; ?>

                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1 rounded-lg text-xs font-bold bg-emerald-500 text-white shadow-md">
                            <i class="fa-solid fa-circle-check mr-1"></i><?= htmlspecialchars($event['status'] ?? 'Published') ?>
                        </span>
                    </div>

                    <div class="absolute bottom-4 left-4 right-4 bg-white/90 backdrop-blur-md rounded-xl p-4 border border-[#E5E5EA] text-[#1D1D1F] shadow-sm">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-orange-500/20 text-orange-400 flex items-center justify-center">
                                    <i class="fa-regular fa-calendar"></i>
                                </div>
                                <div>
                                    <div class="text-[10px] text-slate-400 uppercase font-semibold">Ngày diễn ra</div>
                                    <div class="font-bold text-white"><?= $startDate ?> (<?= $startTime ?> - <?= $endTime ?>)</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <div>
                                    <div class="text-[10px] text-slate-400 uppercase font-semibold">Địa điểm / Hội trường</div>
                                    <div class="font-bold text-white truncate"><?= htmlspecialchars($event['location_name'] ?? 'Chưa xác định') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description & Details -->
                <div class="p-6 sm:p-8 space-y-6">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight mb-3">
                            <?= htmlspecialchars($event['title']) ?>
                        </h1>
                        <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 pb-4 border-b border-slate-100">
                            <span><i class="fa-solid fa-user-tie text-orange-500 mr-1.5"></i>Người tổ chức: <strong><?= htmlspecialchars($event['creator_name'] ?? 'Ban Đào Tạo') ?></strong></span>
                            <span>•</span>
                            <span><i class="fa-solid fa-users text-blue-500 mr-1.5"></i>Sức chứa hội trường: <strong><?= $event['capacity'] ?? 200 ?> người</strong></span>
                        </div>
                    </div>

                    <!-- Event Content Description -->
                    <div class="prose prose-slate max-w-none text-sm text-slate-700 leading-relaxed space-y-3">
                        <h4 class="text-base font-bold text-slate-900 mb-1">Mục Đích & Kế Hoạch Sự Kiện</h4>
                        <p><?= nl2br(htmlspecialchars($event['description'] ?? 'Không có mô tả chi tiết.')) ?></p>
                    </div>

                    <!-- CTV Benefits Banner -->
                    <div class="p-4 rounded-xl bg-orange-50/70 border border-orange-200/80 text-orange-950 flex items-start gap-3">
                        <i class="fa-solid fa-gift text-fpoly-primary text-lg mt-0.5"></i>
                        <div class="text-xs space-y-1">
                            <span class="font-bold block text-sm text-orange-900">Quyền Lợi Dành Cho Cộng Tác Viên (CTV):</span>
                            <ul class="list-disc list-inside space-y-0.5 text-orange-900/80">
                                <li>Cộng từ <strong>10 đến 15 Điểm Tích Cực (Uy tín)</strong> vào hệ thống quản lý CTV FPoly.</li>
                                <li>Được cấp giấy chứng nhận tham gia ban tổ chức đối với các sự kiện cấp trường.</li>
                                <li>Hỗ trợ nước uống, ăn nhẹ và đồng phục CTV theo từng ca trực.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shifts Section -->
            <div id="shiftsContainer" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Phân Rã Các Ca Trực (Shifts)</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Chọn ca trực phù hợp với thời gian biểu của bạn để tham gia hỗ trợ</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-orange-100 text-orange-700">
                        <?= count($shifts) ?> Ca trực mở
                    </span>
                </div>

                <!-- Shift Cards -->
                <div class="space-y-4">
                    <?php foreach ($shifts as $shift): ?>
                        <?php
                        $shiftStart = date('H:i - d/m/Y', strtotime($shift['start_time']));
                        $shiftEnd = date('H:i - d/m/Y', strtotime($shift['end_time']));
                        $targetQty = (int)$shift['target_qty'];
                        $approvedQty = (int)$shift['approved_qty'];
                        $isRegistered = (bool)$shift['is_registered'];
                        $isFull = ($approvedQty >= $targetQty);
                        $remaining = max(0, $targetQty - $approvedQty);
                        ?>
                        <div id="shiftCard-<?= $shift['id'] ?>" class="p-5 rounded-xl border <?= $isRegistered ? 'border-emerald-300 bg-emerald-50/30' : ($isFull ? 'border-slate-200 bg-slate-50/50' : 'border-slate-200 bg-white hover:border-orange-400') ?> transition-all shadow-sm">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                
                                <!-- Shift Info -->
                                <div class="space-y-1.5 flex-grow">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3 class="font-bold text-slate-900 text-base"><?= htmlspecialchars($shift['name']) ?></h3>
                                        
                                        <!-- Points Badge -->
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                            <i class="fa-solid fa-star text-amber-500 mr-1 text-[10px]"></i>+<?= $shift['points'] ?> Điểm Tích Cực
                                        </span>

                                        <?php if ($isRegistered): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                <i class="fa-solid fa-check mr-1"></i>Bạn đã đăng ký
                                            </span>
                                        <?php elseif ($isFull): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-200 text-slate-600">
                                                Đã đủ quân số
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-100 text-blue-800">
                                                Còn <?= $remaining ?> chỗ
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="text-xs text-slate-500 flex flex-wrap items-center gap-x-4 gap-y-1">
                                        <span class="flex items-center gap-1 font-medium text-slate-700">
                                            <i class="fa-regular fa-clock text-orange-500"></i>
                                            <span><?= $shiftStart ?> → <?= $shiftEnd ?></span>
                                        </span>
                                        <?php if (!empty($shift['skill_required'])): ?>
                                            <span class="flex items-center gap-1 text-slate-600">
                                                <i class="fa-solid fa-tag text-slate-400"></i>
                                                <span>Kỹ năng: <strong><?= htmlspecialchars($shift['skill_required']) ?></strong></span>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Progress indicator -->
                                    <div class="flex items-center gap-2 pt-1 text-[11px] text-slate-500">
                                        <span>Tuyển: <strong id="shiftCount-<?= $shift['id'] ?>"><?= $approvedQty ?></strong>/<?= $targetQty ?></span>
                                        <div class="w-32 bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                            <div id="shiftBar-<?= $shift['id'] ?>" class="bg-orange-500 h-1.5 rounded-full" style="width: <?= min(100, round(($approvedQty / $targetQty) * 100)) ?>%"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="flex-shrink-0">
                                    <?php if ($isRegistered): ?>
                                        <button type="button" disabled class="px-5 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-bold cursor-default shadow-sm opacity-90 flex items-center gap-1.5">
                                            <i class="fa-solid fa-check"></i>
                                            <span>Đã Tham Gia</span>
                                        </button>
                                    <?php elseif ($isFull): ?>
                                        <button type="button" disabled class="px-5 py-2.5 rounded-xl bg-slate-200 text-slate-400 text-xs font-bold cursor-not-allowed">
                                            Hết Chỗ
                                        </button>
                                    <?php else: ?>
                                        <button type="button" onclick="registerShift(<?= $shift['id'] ?>, '<?= htmlspecialchars(addslashes($shift['name'])) ?>')" id="btnRegister-<?= $shift['id'] ?>" class="px-5 py-2.5 rounded-xl bg-fpoly-primary hover:bg-fpoly-hover text-white text-xs font-bold shadow-md shadow-orange-500/20 transition-all hover:scale-105 flex items-center gap-1.5">
                                            <i class="fa-solid fa-user-plus"></i>
                                            <span>Đăng Ký Ca</span>
                                        </button>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>

        </div>

        <!-- Sidebar (Right 4 Cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Student Profile Card in Current Session -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-fpoly-primary to-orange-400 text-white flex items-center justify-center font-bold text-lg shadow-md shadow-orange-500/20">
                        <?= mb_substr($currentUser['full_name'] ?? 'S', 0, 1) ?>
                    </div>
                    <div>
                        <div class="font-extrabold text-slate-900 text-sm"><?= htmlspecialchars($currentUser['full_name'] ?? 'Sinh viên') ?></div>
                        <div class="text-xs text-slate-500"><?= htmlspecialchars($currentUser['student_code'] ?? 'N/A') ?> • <?= htmlspecialchars($currentUser['role_name'] ?? 'Student') ?></div>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-600 space-y-1">
                    <div><strong>Email:</strong> <?= htmlspecialchars($currentUser['email'] ?? '') ?></div>
                    <div><strong>Kỹ năng:</strong> <?= htmlspecialchars($currentUser['skills'] ?? 'Chưa cập nhật') ?></div>
                </div>

                <a href="/shift/myShifts" class="w-full py-2.5 px-4 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-semibold text-center block transition">
                    <i class="fa-regular fa-calendar-check mr-1 text-orange-500"></i>Xem Lịch Làm Việc Đã Đăng Ký
                </a>
            </div>

            <!-- Algorithm Information Card -->
            <div class="bg-white border border-[#E5E5EA] text-[#1D1D1F] rounded-2xl p-5 space-y-3 shadow-xs">
                <div class="flex items-center gap-2 text-orange-400 font-bold text-xs uppercase tracking-wide">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>Bảo Vệ Xung Đột Thời Gian Thực</span>
                </div>
                <h4 class="text-sm font-bold text-white">Thuật toán Interval Collision</h4>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Hệ thống tự động kiểm tra giao thoa thời gian trước khi xác nhận ghi nhận ca trực:
                </p>
                <div class="p-2.5 rounded-lg bg-black/40 font-mono text-[11px] text-emerald-400 border border-white/10">
                    Start_New &lt; End_Existing AND<br>
                    End_New &gt; Start_Existing
                </div>
                <p class="text-[11px] text-slate-400">
                    Ngăn chặn hoàn toàn tình trạng một sinh viên nhận 2 ca trùng giờ hoặc phân công trùng địa điểm hội trường.
                </p>
            </div>

            <!-- Need Help AI Banner -->
            <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5 text-center space-y-3">
                <div class="w-10 h-10 rounded-full bg-orange-500 text-white flex items-center justify-center mx-auto text-base shadow-md shadow-orange-500/20">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
                <h4 class="text-sm font-bold text-slate-900">Bạn Cần Trợ Giúp Phân Ca?</h4>
                <p class="text-xs text-slate-600">Trợ lý AI PolyMee luôn sẵn sàng giải đáp thắc mắc và kiểm tra lịch rảnh cho bạn.</p>
                <button type="button" onclick="toggleAiWidget()" class="px-4 py-2 rounded-xl bg-[#1D1D1F] text-white text-xs font-bold hover:bg-black transition shadow-sm">
                    Mở Trợ Lý AI
                </button>
            </div>

        </div>

    </div>
</div>

<!-- Modal Thông Báo Xung Đột Lịch Trực (Interval Collision Alert) -->
<div id="conflictModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/30 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-rose-100 animate-in fade-in zoom-in-95 duration-200">
        
        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-xl mx-auto mb-4">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>

        <h3 class="text-lg font-bold text-slate-900 text-center mb-1">
            Phát Hiện Xung Đột Lịch Trực!
        </h3>
        
        <p class="text-xs text-rose-600 font-semibold text-center uppercase tracking-wide mb-4">
            Khoảng thời gian bị giao thoa (Collision Detected)
        </p>

        <div class="bg-rose-50/80 rounded-xl p-4 border border-rose-100 text-xs text-slate-700 space-y-2 mb-5">
            <div id="conflictMessage" class="leading-relaxed font-medium"></div>
            <div class="pt-2 border-t border-rose-200/60 text-[11px] text-slate-500">
                <strong>Thuật toán:</strong> Điều kiện xung đột giao thoa thời gian đã ngăn chặn việc đăng ký để bảo đảm quyền lợi và tính khả thi ca trực của bạn.
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" onclick="closeConflictModal()" class="w-full py-2.5 px-4 rounded-xl bg-[#1D1D1F] hover:bg-black text-white text-xs font-bold transition">
                Đã Hiểu & Chọn Ca Khác
            </button>
        </div>
    </div>
</div>
