<?php
/**
 * PolyMee - Thông Báo Chuyển Đổi Phương Thức Nghiệm Thu
 * Lưu ý: Cơ chế điểm danh bằng quét mã QR đã được lược bỏ.
 * Hệ thống chuyển đổi 100% sang:
 * 1. Nghiệm thu bằng Ảnh minh chứng hiện trường (Đối với CTV thông thường)
 * 2. Nghiệm thu bằng Thư mục Google Drive ảnh RAW & Video Recap (Đối với CTV Media)
 * 3. Giảng viên phụ trách kiểm tra & ký hợp đồng điện tử trực tiếp trên Dashboard
 */
declare(strict_types=1);
?>

<div class="min-h-screen bg-slate-950 text-white flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Background Animated Grid Effect -->
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_80%_at_50%_-20%,rgba(234,88,12,0.15),rgba(255,255,255,0))] pointer-events-none"></div>

    <div class="max-w-xl mx-auto w-full bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl relative z-10 text-center">
        <div class="w-16 h-16 rounded-2xl bg-orange-500/10 border border-orange-500/30 text-orange-500 flex items-center justify-center mx-auto mb-4 text-3xl">
            <i class="fa-solid fa-camera-retro"></i>
        </div>

        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-orange-500/10 text-orange-400 border border-orange-500/20 mb-3">
            CHUYỂN ĐỔI QUY TRÌNH NGHIỆM THU
        </span>

        <h1 class="text-2xl font-bold text-white mb-2">Nghiệm Thu Bằng Minh Chứng Thực Tế</h1>
        <p class="text-sm text-slate-400 mb-6 leading-relaxed">
            Hệ thống PolyMee đã loại bỏ hoàn toàn cơ chế điểm danh quét mã QR để chống gian lận điểm danh hộ. Toàn bộ ca trực hiện nay được nghiệm thu thông qua:
        </p>

        <div class="space-y-3 text-left mb-6">
            <div class="p-3.5 rounded-xl bg-slate-800/60 border border-slate-700/50 flex items-start gap-3">
                <i class="fa-solid fa-camera text-emerald-400 mt-1"></i>
                <div class="text-xs">
                    <strong class="text-white block font-semibold mb-0.5">Ảnh Minh Chứng Hiện Trường (CTV Sự Kiện)</strong>
                    <span class="text-slate-400">Sinh viên chụp ảnh thực tế tại ca trực, nộp trực tiếp trên Dashboard cá nhân.</span>
                </div>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-800/60 border border-slate-700/50 flex items-start gap-3">
                <i class="fa-brands fa-google-drive text-amber-400 mt-1"></i>
                <div class="text-xs">
                    <strong class="text-white block font-semibold mb-0.5">Link Google Drive Bàn Giao (CTV Media)</strong>
                    <span class="text-slate-400">Đội truyền thông nộp link Google Drive chứa ảnh RAW, ảnh chỉnh màu và video recap.</span>
                </div>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-800/60 border border-slate-700/50 flex items-start gap-3">
                <i class="fa-solid fa-file-signature text-blue-400 mt-1"></i>
                <div class="text-xs">
                    <strong class="text-white block font-semibold mb-0.5">Hợp Đồng Nghiệm Thu Điện Tử (HDNT-2026)</strong>
                    <span class="text-slate-400">Giảng viên thẩm định minh chứng -> Sinh mã hợp đồng -> Cán bộ Hành chính ký duyệt chi tiền.</span>
                </div>
            </div>
        </div>

        <a href="/dashboard" class="inline-flex items-center justify-center gap-2 w-full py-3 px-6 rounded-xl font-bold text-sm bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/25 hover:from-orange-600 hover:to-orange-700 transition">
            <i class="fa-solid fa-arrow-left"></i> Quay Về Bảng Điều Khiển PolyMee
        </a>
    </div>
</div>
