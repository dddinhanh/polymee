<?php
/**
 * PolyMee - Cấu hình tổng quan ứng dụng
 * Môn học: WEB212 - Phát triển dự án website với AI (FPT Polytechnic)
 */

declare(strict_types=1);

// Đường dẫn hệ thống
define('APP_ROOT', dirname(__DIR__));
define('URL_ROOT', ''); // Tự động phát hiện hoặc để rỗng cho relative URL
define('SITE_NAME', 'PolyMee - Hệ Thống Quản Trị Sự Kiện & Điều Phối CTV');

// Thông tin liên hệ FPoly
define('FPOLY_CAMPUS', 'FPT Polytechnic TP. Hồ Chí Minh');
define('FPOLY_HOTLINE', '1900 2640');

// Khóa bí mật phục vụ sinh mã Dynamic QR HMAC-SHA256
define('QR_SECRET_KEY', 'FPOLY_POLYMEE_SECRET_HMAC_KEY_2026_CDIO');
define('QR_TTL_SECONDS', 30);

// Khóa bí mật CSRF
define('CSRF_SECRET_KEY', 'FPOLY_CSRF_TOKEN_SECRET_2026');

// Cấu hình AI Assistant (Google Gemini 1.5 Flash)
define('GEMINI_API_KEY', getenv('GEMINI_API_KEY') ?: 'DEMO_KEY_OR_AI_FALLBACK');
define('GEMINI_MODEL', 'gemini-1.5-flash');

// Thiết lập múi giờ Việt Nam
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Khởi tạo phiên làm việc an toàn (chỉ khi không chạy từ PHP CLI)
if (session_status() === PHP_SESSION_NONE && php_sapi_name() !== 'cli') {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    session_start();
}
