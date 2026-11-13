<?php
/**
 * PolyMee - Fullscreen Dynamic QR Code Projector
 * Màn hình máy chiếu điểm danh thời gian thực (Cơ chế xoay vòng 30 giây HMAC-SHA256)
 */
declare(strict_types=1);
?>

<div class="min-h-screen bg-slate-950 text-white flex flex-col justify-between py-8 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    
    <!-- Background Animated Grid Effect -->
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_80%_at_50%_-20%,rgba(242,111,33,0.15),rgba(255,255,255,0))] pointer-events-none"></div>

    <!-- Top Bar: FPoly Brand & Event Title -->
    <div class="max-w-6xl mx-auto w-full flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10 border-b border-slate-800/80 pb-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-fpoly-primary flex items-center justify-center font-black text-white text-2xl shadow-lg shadow-orange-500/30">
                <i class="fa-solid fa-qrcode"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-orange-400">Máy Chiếu Điểm Danh Hiện Diện</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">LIVE • TTL 30S</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-white mt-0.5"><?= htmlspecialchars($shift['event_title'] ?? 'Sự Kiện FPoly') ?></h1>
            </div>
        </div>

        <div class="text-right hidden sm:block">
            <div class="text-xs text-slate-400 uppercase tracking-wider">Hội trường / Phòng họp</div>
            <div class="text-base font-bold text-white flex items-center justify-end gap-1.5 mt-0.5">
                <i class="fa-solid fa-location-dot text-orange-500"></i>
                <span><?= htmlspecialchars($shift['location_name'] ?? 'Hội trường Trịnh Văn Bô') ?></span>
            </div>
        </div>
    </div>

    <!-- Center Stage: Giant Dynamic QR & Countdown -->
    <div class="max-w-4xl mx-auto w-full my-auto py-8 flex flex-col items-center relative z-10 text-center">
        
        <!-- Shift Title Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-900 border border-slate-800 text-slate-200 text-xs font-semibold mb-6 shadow-sm">
            <i class="fa-solid fa-user-clock text-orange-400"></i>
            <span>Đang điểm danh: <strong class="text-white"><?= htmlspecialchars($shift['name']) ?></strong></span>
        </div>

        <!-- QR Canvas Card Container with Glow Frame -->
        <div class="relative group">
            <div class="absolute -inset-1.5 bg-gradient-to-r from-orange-600 via-amber-500 to-orange-500 rounded-3xl blur-xl opacity-70 group-hover:opacity-100 transition duration-1000 animate-pulse"></div>
            
            <div class="relative bg-white p-7 sm:p-9 rounded-3xl shadow-2xl flex flex-col items-center">
                <!-- QR Canvas Target Element -->
                <div id="qrcodeCanvas" class="w-64 h-64 sm:w-80 sm:h-80 flex items-center justify-center"></div>

                <!-- FPoly Brand watermark under QR -->
                <div class="mt-4 flex items-center gap-2 text-slate-800 text-xs font-bold tracking-tight">
                    <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                    <span>FPT POLYTECHNIC • POLYMEE QR</span>
                </div>
            </div>
        </div>

        <!-- 30-Second Dynamic Countdown Ring & Status -->
        <div class="mt-8 flex flex-col items-center space-y-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-slate-900 border border-slate-700 flex items-center justify-center font-mono font-extrabold text-orange-400 text-base shadow-inner" id="countdownSec">
                    <?= $ttl ?>
                </div>
                <div class="text-left">
                    <div class="text-xs font-bold text-white uppercase tracking-wide">Mã Sẽ Làm Mới Tự Động</div>
                    <div class="text-[11px] text-slate-400">Chống chụp màn hình gian lận điểm danh từ xa</div>
                </div>
            </div>

            <!-- Progress countdown bar -->
            <div class="w-72 bg-slate-800 rounded-full h-1.5 overflow-hidden">
                <div id="countdownProgressBar" class="bg-gradient-to-r from-orange-500 to-amber-400 h-1.5 rounded-full transition-all duration-1000" style="width: 100%"></div>
            </div>

            <!-- HMAC Signature Hash Badge -->
            <div class="pt-2">
                <span class="font-mono text-[10px] text-slate-500 bg-slate-900 px-3 py-1.5 rounded-lg border border-slate-800 flex items-center gap-1.5">
                    <i class="fa-solid fa-key text-orange-400"></i>
                    <span>HMAC-SHA256 Token: </span>
                    <strong id="tokenHashPreview" class="text-slate-300"><?= substr($token, 0, 24) ?>...</strong>
                </span>
            </div>
        </div>

    </div>

    <!-- Bottom Instructions Footer -->
    <div class="max-w-4xl mx-auto w-full relative z-10 border-t border-slate-800/80 pt-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center text-xs text-slate-400">
            <div class="p-3 rounded-xl bg-slate-900/60 border border-slate-800/60">
                <strong class="text-white block mb-0.5">Bước 1: Mở Camera</strong>
                Dùng điện thoại thông minh hoặc app PolyMee quét mã.
            </div>
            <div class="p-3 rounded-xl bg-slate-900/60 border border-slate-800/60">
                <strong class="text-white block mb-0.5">Bước 2: Xác Thực</strong>
                Hệ thống xác minh mã token hợp lệ trong 30 giây.
            </div>
            <div class="p-3 rounded-xl bg-slate-900/60 border border-slate-800/60">
                <strong class="text-white block mb-0.5">Bước 3: Ghi Nhận Công</strong>
                Tự động lưu lịch sử Check-in và tích lũy điểm rèn luyện.
            </div>
        </div>
    </div>

</div>

<!-- QR Auto-Refresh Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const eventId = <?= (int)$shift['event_id'] ?>;
    const shiftId = <?= (int)$shift['id'] ?>;
    let initialToken = "<?= htmlspecialchars($token) ?>";
    const totalTtl = <?= (int)$ttl ?>;
    let remainingSeconds = totalTtl;
    let qrcodeObj = null;

    // Khởi tạo QRCode ban đầu
    const qrTarget = document.getElementById('qrcodeCanvas');
    if (qrTarget && typeof QRCode !== 'undefined') {
        qrcodeObj = new QRCode(qrTarget, {
            text: initialToken,
            width: 280,
            height: 280,
            colorDark: "#0F172A",
            colorLight: "#FFFFFF",
            correctLevel: QRCode.CorrectLevel.H
        });
    }

    // Bộ đếm lùi thời gian thực 30 giây
    const countdownEl = document.getElementById('countdownSec');
    const progressBar = document.getElementById('countdownProgressBar');
    const hashPreview = document.getElementById('tokenHashPreview');

    setInterval(() => {
        remainingSeconds--;

        if (countdownEl) {
            countdownEl.textContent = remainingSeconds;
        }

        if (progressBar) {
            const pct = Math.max(0, (remainingSeconds / totalTtl) * 100);
            progressBar.style.width = pct + '%';
        }

        // Khi hết 30s: Kích hoạt API làm mới mã Token
        if (remainingSeconds <= 0) {
            fetchNewToken();
            remainingSeconds = totalTtl;
        }
    }, 1000);

    // Hàm gọi API làm mới mã Dynamic QR
    async function fetchNewToken() {
        try {
            const res = await fetch(`/admin/refreshQr?event_id=${eventId}&shift_id=${shiftId}`);
            if (!res.ok) throw new Error('Network error');
            const data = await res.json();
            
            if (data.success && data.token && qrcodeObj) {
                // Xóa và vẽ lại mã QR mới
                qrcodeObj.clear();
                qrcodeObj.makeCode(data.token);

                if (hashPreview) {
                    hashPreview.textContent = data.token.substring(0, 24) + '...';
                }
            }
        } catch (err) {
            console.error('Lỗi khi làm mới mã QR:', err);
        }
    }
});
</script>
