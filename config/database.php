<?php
/**
 * PolyMee - Cấu hình kết nối Cơ sở dữ liệu (PDO)
 * Môn học: WEB212 - Phát triển dự án website với AI (FPT Polytechnic)
 */

declare(strict_types=1);

// Chế độ CSDL: 'sqlite' (chạy ngay không cần cài đặt MySQL), 'mysql', hoặc 'sqlsrv'
define('DB_CONNECTION', getenv('DB_CONNECTION') ?: 'sqlsrv');

// Cấu hình SQLite (tệp lưu trong thư mục database)
define('DB_SQLITE_PATH', APP_ROOT . '/database/polymee.sqlite');

// Cấu hình MySQL (cho Docker hoặc XAMPP / MAMP)
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_DATABASE', getenv('DB_DATABASE') ?: 'polymee_db');
define('DB_USERNAME', getenv('DB_USERNAME') ?: 'root');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');

// Cấu hình Microsoft SQL Server (khi dùng sqlsrv)
define('DB_SQLSRV_HOST', getenv('DB_SQLSRV_HOST') ?: '127.0.0.1');
define('DB_SQLSRV_PORT', getenv('DB_SQLSRV_PORT') ?: '14331');
define('DB_SQLSRV_DB', getenv('DB_SQLSRV_DB') ?: 'Polymeet');
define('DB_SQLSRV_USER', getenv('DB_SQLSRV_USER') ?: 'sa');
define('DB_SQLSRV_PASS', getenv('DB_SQLSRV_PASS') ?: 'ThayBangMatKhauManh123!');
