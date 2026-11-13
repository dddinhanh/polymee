<?php
/**
 * PolyMee - My Shifts View
 * Hiển thị toàn bộ ca trực đã được phân công/duyệt của sinh viên hiện tại
 */
declare(strict_types=1);

$totalPoints = 0;
foreach ($shifts as $s) {
    $totalPoints += (int)($s['points'] ?? 0);
}
?>

<div class="bg-slate-100 border-b border-slate-200 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center text-xs text-slate-500 gap-2">
            <a href="/" class="hover:text-fpoly-primary transition">Trang chủ</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <span class="text-slate-900 font-semibold">Lịch Trực Của Tôi</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    
    <!-- Profile & Metrics Banner -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-fpoly-primary to-orange-400 text-white flex items-center justify-center font-bold text-2xl shadow-lg shadow-orange-500/20">
                    <?= mb_substr($currentUser['full_name'] ?? 'U', 0, 1) ?>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900"><?= htmlspecialchars($currentUser['full_name']) ?></h1>
                        <span class="px-2 py-0.5 rounded text-xs font-bold bg-orange-100 text-orange-700">CTV FPoly</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">
                        Mã sinh viên: <strong class="text-slate-700"><?= htmlspecialchars($currentUser['student_code']) ?></strong> • 
                        Email: <strong class="text-slate-700"><?= htmlspecialchars($currentUser['email']) ?></strong>
                    </p>
                </div>
            </div>

            <!-- Total stats -->
            <div class="flex items-center gap-4 border-t sm:border-t-0 sm:border-l border-slate-100 pt-4 sm:pt-0 sm:pl-6">
                <div class="text-center sm:text-right">
                    <span class="text-xs text-slate-400 block font-semibold uppercase">Số Ca Trực</span>
                    <span class="text-2xl font-extrabold text-slate-900"><?= count($shifts) ?></span>
                </div>
                <div class="text-center sm:text-right">
                    <span class="text-xs text-slate-400 block font-semibold uppercase">Điểm Rèn Luyện</span>
                    <span class="text-2xl font-extrabold text-amber-500">+<?= $totalPoints ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Shifts List -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Danh Sách Ca Làm Việc Được Phân Công</h2>
                <p class="text-xs text-slate-500 mt-0.5">Vui lòng có mặt đúng giờ và quét mã Dynamic QR tại bàn ban tổ chức</p>
            </div>
            <a href="/" class="text-xs font-bold text-fpoly-primary hover:text-fpoly-hover flex items-center gap-1">
                <span>Khám phá thêm ca mới</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <?php if (empty($shifts)): ?>
            <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-2xl">
                    <i class="fa-regular fa-calendar-xmark"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800 mb-1">Bạn chưa đăng ký ca trực nào</h3>
                <p class="text-xs text-slate-500 mb-4 max-w-sm mx-auto">Hãy tham gia ngay các sự kiện của FPT Polytechnic để tích lũy kinh nghiệm thực tế và cộng điểm rèn luyện nhé!</p>
                <a href="/" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-fpoly-primary text-white text-xs font-bold hover:bg-fpoly-hover transition shadow-sm">
                    <i class="fa-solid fa-calendar-plus"></i>
                    <span>Xem danh sách sự kiện</span>
                </a>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($shifts as $s): ?>
                    <?php
                    $startFormatted = date('H:i - d/m/Y', strtotime($s['start_time']));
                    $endFormatted = date('H:i - d/m/Y', strtotime($s['end_time']));
                    ?>
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 hover:border-orange-300 transition">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            
                            <!-- Left: Shift Info -->
                            <div class="space-y-2">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wide">
                                        <?= htmlspecialchars($s['assignment_status']) ?>
                                    </span>
                                    <h3 class="text-base font-bold text-slate-900"><?= htmlspecialchars($s['name']) ?></h3>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                        <i class="fa-solid fa-star text-amber-500 mr-1 text-[10px]"></i>+<?= $s['points'] ?> Điểm RL
                                    </span>
                                </div>

                                <div class="text-xs text-slate-600 flex flex-wrap items-center gap-x-4 gap-y-1">
                                    <span class="flex items-center gap-1 font-semibold text-slate-800">
                                        <i class="fa-solid fa-flag text-orange-500"></i>
                                        <span><?= htmlspecialchars($s['event_title']) ?></span>
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i class="fa-solid fa-location-dot text-slate-400"></i>
                                        <span><?= htmlspecialchars($s['location_name'] ?? 'Hội trường FPoly') ?></span>
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i class="fa-regular fa-clock text-blue-500"></i>
                                        <span><?= $startFormatted ?> → <?= $endFormatted ?></span>
                                    </span>
                                </div>
                            </div>

                            <!-- Right: Actions -->
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <button type="button" onclick="openDriveSubmitModal(<?= $s['assignment_id'] ?>, '<?= htmlspecialchars(addslashes($s['event_title'] . ' - ' . $s['name'])) ?>')" class="px-4 py-2 rounded-xl bg-orange-50 hover:bg-orange-100 text-fpoly-primary text-xs font-bold border border-orange-200 transition flex items-center gap-1.5">
                                    <i class="fa-brands fa-google-drive"></i>
                                    <span>Nộp Minh Chứng Drive</span>
                                </button>
                                <a href="/admin/qrProjector/<?= $s['id'] ?>" target="_blank" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-qrcode"></i>
                                    <span>Xem Mã QR Điểm Danh</span>
                                </a>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>

<!-- Drive Submission Quick Modal -->
<div id="driveSubmitModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 animate-in fade-in duration-150">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center">
                    <i class="fa-brands fa-google-drive"></i>
                </div>
                <h3 class="font-bold text-slate-900 text-sm">Nộp Minh Chứng / Sản Phẩm Nghiệm Thu</h3>
            </div>
            <button type="button" onclick="closeDriveModal()" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Ca trực & Sự kiện:</label>
                <div id="modalShiftTitle" class="text-xs font-bold text-slate-800 bg-slate-50 p-2.5 rounded-xl border border-slate-200"></div>
            </div>

            <div>
                <label for="driveUrlInput" class="block text-xs font-semibold text-slate-700 mb-1">Đường dẫn Google Drive (Ảnh/Video recap/Biên bản):</label>
                <input type="url" id="driveUrlInput" placeholder="https://drive.google.com/drive/folders/..." class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none">
                <p class="text-[11px] text-slate-400 mt-1">Đảm bảo quyền xem link là "Bất kỳ ai có đường liên kết".</p>
            </div>

            <div>
                <label for="driveNotesInput" class="block text-xs font-semibold text-slate-700 mb-1">Ghi chú kèm theo:</label>
                <textarea id="driveNotesInput" rows="2" placeholder="Ví dụ: Link bao gồm 150 ảnh đã chỉnh màu và 1 video recap 60s..." class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none"></textarea>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2 pt-4 mt-4 border-t border-slate-100">
            <button type="button" onclick="closeDriveModal()" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">Hủy</button>
            <button type="button" onclick="submitDriveLinkAjax()" class="px-5 py-2 rounded-xl bg-fpoly-primary hover:bg-fpoly-hover text-white text-xs font-bold shadow-md shadow-orange-500/20 transition flex items-center gap-1.5">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <span>Gửi Nghiệm Thu</span>
            </button>
        </div>
    </div>
</div>
