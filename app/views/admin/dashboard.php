<?php
/**
 * PolyMee - Admin Dashboard View
 * Bảng điều khiển quản trị, thẩm định nghiệm thu Drive và điều phối sự kiện
 */
declare(strict_types=1);
?>

<div class="bg-[#FAFAFC] text-[#1D1D1F] py-8 border-b border-[#E5E5EA]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-orange-500/20 text-orange-400 border border-orange-500/30">
                        HỆ THỐNG QUẢN TRỊ VIÊN
                    </span>
                    <span class="text-xs text-slate-400">• FPT Polytechnic</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Bảng Điều Khiển Quản Trị & Nghiệm Thu</h1>
                <p class="text-xs text-slate-400 mt-1">Quản lý sự kiện, ca trực và xét duyệt hồ sơ minh chứng Google Drive</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="/dashboard" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-500 hover:to-amber-500 text-white text-xs font-bold shadow-lg shadow-orange-900/30 transition hover:scale-105">
                    <i class="fa-solid fa-calendar-days text-sm"></i>
                    <span>Xem Bảng Lịch Trung Tâm</span>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    
    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-semibold uppercase">Tổng Sự Kiện</span>
                <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-calendar"></i>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-slate-900"><?= number_format($stats['total_events'] ?? 0) ?></div>
            <div class="text-[11px] text-slate-500 mt-1">Đang hoạt động trong kỳ</div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-semibold uppercase">Tổng Ca Trực Phân Rã</span>
                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-slate-900"><?= number_format($stats['total_shifts'] ?? 0) ?></div>
            <div class="text-[11px] text-slate-500 mt-1">Các khung giờ CTV</div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-semibold uppercase">Tỷ Lệ Lấp Đầy Ca</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-emerald-600"><?= $stats['fill_rate_percent'] ?? 0 ?>%</div>
            <div class="text-[11px] text-slate-500 mt-1">Chỉ tiêu nhân sự tuyển dụng</div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-semibold uppercase">Cộng Tác Viên Đăng Ký</span>
                <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-purple-600"><?= number_format($stats['active_collaborators'] ?? 0) ?></div>
            <div class="text-[11px] text-slate-500 mt-1">Sinh viên tham gia điều phối</div>
        </div>
    </div>

    <!-- Section 1: Thẩm định bài nộp Google Drive -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-orange-100 text-fpoly-primary flex items-center justify-center text-sm font-bold">
                        <i class="fa-brands fa-google-drive"></i>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Thẩm Định & Nghiệm Thu Minh Chứng Google Drive</h2>
                </div>
                <p class="text-xs text-slate-500 mt-1">Các đường link minh chứng hình ảnh/video recap do CTV nộp qua Web hoặc Trợ lý AI PolyMee</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                <?= count($submissions) ?> Hồ sơ nghiệm thu
            </span>
        </div>

        <?php if (empty($submissions)): ?>
            <div class="p-10 text-center text-slate-400 text-xs">
                <i class="fa-solid fa-folder-open text-3xl mb-2 text-slate-300"></i>
                <p>Hiện chưa có hồ sơ minh chứng nào được nộp.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto max-h-[450px] overflow-y-auto relative rounded-xl border border-slate-200">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="sticky top-0 z-10 bg-slate-100 text-slate-700 uppercase tracking-wider font-bold border-b border-slate-200 shadow-xs">
                        <tr>
                            <th class="py-3 px-4 bg-slate-100">Mã HS</th>
                            <th class="py-3 px-4 bg-slate-100">Sinh Viên Nộp</th>
                            <th class="py-3 px-4 bg-slate-100">Sự Kiện & Ca Trực</th>
                            <th class="py-3 px-4 bg-slate-100">Liên Kết Drive</th>
                            <th class="py-3 px-4 bg-slate-100">Ghi Chú</th>
                            <th class="py-3 px-4 bg-slate-100">Trạng Thái</th>
                            <th class="py-3 px-4 text-right bg-slate-100">Thao Tác Duyệt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 bg-white">
                        <?php foreach ($submissions as $sub): ?>
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4 font-mono font-bold text-slate-500">
                                    #SUB-<?= sprintf('%04d', $sub['id']) ?>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900"><?= htmlspecialchars($sub['student_name'] ?? 'CTV') ?></div>
                                    <div class="text-[11px] text-slate-400"><?= htmlspecialchars($sub['student_code'] ?? '') ?></div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-semibold text-slate-800"><?= htmlspecialchars($sub['event_title'] ?? '') ?></div>
                                    <div class="text-[11px] text-orange-600 font-medium"><?= htmlspecialchars($sub['shift_name'] ?? '') ?></div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <a href="<?= htmlspecialchars($sub['drive_url']) ?>" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 font-medium transition">
                                        <i class="fa-brands fa-google-drive"></i>
                                        <span>Mở Drive</span>
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                    </a>
                                </td>
                                <td class="py-3.5 px-4 max-w-xs truncate text-slate-500" title="<?= htmlspecialchars($sub['notes'] ?? '') ?>">
                                    <?= htmlspecialchars($sub['notes'] ?? 'Không có ghi chú') ?>
                                </td>
                                <td class="py-3.5 px-4">
                                    <?php if ($sub['status'] === 'Accepted'): ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                            <i class="fa-solid fa-check mr-1"></i>Đã Nghiệm Thu
                                        </span>
                                    <?php elseif ($sub['status'] === 'Rejected'): ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800">
                                            <i class="fa-solid fa-xmark mr-1"></i>Từ Chối
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">
                                            <i class="fa-solid fa-hourglass-half mr-1"></i>Chờ Thẩm Định
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <form method="POST" action="/admin/reviewSubmission" class="inline-flex items-center gap-1.5">
                                        <input type="hidden" name="submission_id" value="<?= $sub['id'] ?>">
                                        <button type="submit" name="status" value="Accepted" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-[11px] shadow-xs transition" title="Chấp nhận nghiệm thu và cộng điểm tích cực">
                                            <i class="fa-solid fa-check mr-1"></i>Duyệt
                                        </button>
                                        <button type="submit" name="status" value="Rejected" class="px-2.5 py-1 rounded-lg bg-rose-600 hover:bg-rose-500 text-white font-bold text-[11px] shadow-xs transition" title="Từ chối bài nộp">
                                            <i class="fa-solid fa-xmark mr-1"></i>Từ chối
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Section 2: Quản lý Sự Kiện & Phân Rã Ca Trực -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Danh Sách Sự Kiện & Phân Rã Ca Trực</h2>
                <p class="text-xs text-slate-500">Theo dõi các sự kiện và ca trực đang diễn ra trong khuôn viên trường</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php foreach ($events as $evt): ?>
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 space-y-3">
                    <div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 text-slate-700">
                            <?= htmlspecialchars($evt['location_name'] ?? 'Khuôn viên') ?>
                        </span>
                        <h3 class="font-bold text-slate-900 text-sm mt-1.5 line-clamp-1"><?= htmlspecialchars($evt['title']) ?></h3>
                        <p class="text-[11px] text-slate-500"><?= date('H:i d/m/Y', strtotime($evt['start_time'])) ?></p>
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-slate-200/70 text-xs">
                        <span class="text-slate-600">Ca trực: <strong><?= $evt['shift_count'] ?? 1 ?> ca</strong></span>
                        <a href="/event/detail/<?= $evt['id'] ?>" class="px-3 py-1.5 rounded-lg bg-[#1D1D1F] hover:bg-fpoly-primary text-white text-[11px] font-bold transition flex items-center gap-1.5">
                            <i class="fa-solid fa-eye"></i>
                            <span>Xem Chi Tiết</span>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>
