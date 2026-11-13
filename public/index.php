<?php
/**
 * PolyMee - Single Entry Point (index.php)
 * Mọi yêu cầu HTTP đều đi qua tệp này theo mô hình MVC chuẩn FPoly
 */

declare(strict_types=1);

// 1. Nạp tệp cấu hình
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';

// 2. Tự động khởi tạo dữ liệu mẫu nếu dùng SQLite và chưa có tệp DB
if (DB_CONNECTION === 'sqlite' && !file_exists(DB_SQLITE_PATH)) {
    require_once dirname(__DIR__) . '/database/seed_data.php';
}

// 3. Đăng ký Autoloader tự động nạp Models, Helpers, Services, Core
spl_autoload_register(function (string $class): void {
    $paths = [
        APP_ROOT . '/core/' . $class . '.php',
        APP_ROOT . '/app/models/' . $class . '.php',
        APP_ROOT . '/app/controllers/' . $class . '.php',
        APP_ROOT . '/app/helpers/' . $class . '.php',
        APP_ROOT . '/app/services/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// 4. Khởi chạy ứng dụng MVC
$app = new App();
