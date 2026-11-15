<?php
/**
 * PolyMee - Footer Layout
 */
declare(strict_types=1);
?>
</main>

<!-- Footer -->
<footer class="bg-[#FAFAFC] text-[#6E6E73] text-sm mt-16 border-t border-[#E5E5EA]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <!-- Brand Column -->
            <div class="md:col-span-1">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-fpoly-primary flex items-center justify-center text-[#1D1D1F] font-bold">
                        <i class="fa-solid fa-calendar-check text-sm"></i>
                    </div>
                    <span class="font-extrabold text-[#1D1D1F] text-lg tracking-tight">Poly<span class="text-fpoly-primary">Mee</span></span>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Hệ thống số hóa quản trị sự kiện & điều phối cộng tác viên sinh viên FPT Polytechnic tích hợp Trợ lý Trí tuệ Nhân tạo và Nghiệm thu minh chứng điện tử.
                </p>
                <div class="mt-4 flex items-center gap-2 text-xs text-slate-500">
                    <span class="px-2 py-0.5 rounded bg-[#E5E5EA] text-[#1D1D1F] font-mono">PHP 8.2 MVC</span>
                    <span class="px-2 py-0.5 rounded bg-[#E5E5EA] text-[#1D1D1F] font-mono">PDO Prepared</span>
                </div>
            </div>

            <!-- Group Info Column -->
            <div>
                <h4 class="text-[#1D1D1F] text-xs font-bold uppercase tracking-wider mb-3">Thành Viên Nhóm Dự Án</h4>
                <ul class="space-y-1.5 text-xs text-slate-400">
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-user-graduate text-orange-400 text-[10px]"></i>
                        <span>Đinh Nguyễn Phương Anh (PI03084)</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-user-graduate text-slate-500 text-[10px]"></i>
                        <span>Lê Đức Mạnh (PI03067)</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-user-graduate text-slate-500 text-[10px]"></i>
                        <span>Hoàng Minh Luận (PI03015)</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-user-graduate text-slate-500 text-[10px]"></i>
                        <span>Dương Ngọc Hân (PI02895)</span>
                    </li>
                </ul>
            </div>

            <!-- Academic Info Column -->
            <div>
                <h4 class="text-[#1D1D1F] text-xs font-bold uppercase tracking-wider mb-3">Thông Tin Học Phần</h4>
                <ul class="space-y-1.5 text-xs text-slate-400">
                    <li><strong>Môn học:</strong> WEB212 - Phát triển dự án website với AI</li>
                    <li><strong>Giai đoạn:</strong> Assignment Giai đoạn 1 (Buổi 1–6)</li>
                    <li><strong>Cơ sở:</strong> <?= FPOLY_CAMPUS ?></li>
                    <li><strong>Giảng viên:</strong> Thầy Phan Hoàng Khải</li>
                </ul>
            </div>

            <!-- Technology Features -->
            <div>
                <h4 class="text-[#1D1D1F] text-xs font-bold uppercase tracking-wider mb-3">Tính Năng Nổi Bật</h4>
                <div class="flex flex-wrap gap-1.5">
                    <span class="px-2.5 py-1 rounded-md text-[11px] bg-[#E5E5EA] text-[#1D1D1F] border border-slate-700">Interval Collision</span>
                    <span class="px-2.5 py-1 rounded-md text-[11px] bg-[#E5E5EA] text-[#1D1D1F] border border-slate-700">Hợp Đồng Điện Tử HDNT</span>
                    <span class="px-2.5 py-1 rounded-md text-[11px] bg-[#E5E5EA] text-[#1D1D1F] border border-slate-700">AI Function Calling</span>
                    <span class="px-2.5 py-1 rounded-md text-[11px] bg-[#E5E5EA] text-[#1D1D1F] border border-slate-700">Google Drive Parser</span>
                    <span class="px-2.5 py-1 rounded-md text-[11px] bg-[#E5E5EA] text-[#1D1D1F] border border-slate-700">Audit Trail 100%</span>
                </div>
            </div>
        </div>

        <div class="pt-8 border-t border-slate-800 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-500 gap-4">
            <p>© <?= date('Y') ?> PolyMee. Dự án thực hành FPT Polytechnic cơ sở Đồng Nai. Bản quyền thuộc về Nhóm sinh viên thực hiện.</p>
            <div class="flex items-center gap-4">
                <a href="/" class="hover:text-slate-300 transition">Trang chủ</a>
                <span>•</span>
                <a href="/shift/myShifts" class="hover:text-slate-300 transition">Lịch làm việc</a>
                <span>•</span>
                <a href="/admin/dashboard" class="hover:text-slate-300 transition">Quản trị</a>
            </div>
        </div>
    </div>
</footer>

<!-- PolyMee JavaScript App Bundle -->
<script src="/assets/js/app.js"></script>
</body>
</html>
