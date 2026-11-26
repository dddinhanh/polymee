<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/Database.php';

$pdo = Database::getInstance()->getConnection();
$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

echo "<h3>Kết nối thành công!</h3>";
echo "Driver đang dùng: <b>" . htmlspecialchars($driver) . "</b><br>";

if ($driver === 'sqlsrv') {
    $dbName = $pdo->query("SELECT DB_NAME()")->fetchColumn();
    echo "Database SQL Server đang trỏ tới: <b>" . htmlspecialchars($dbName) . "</b><br>";
} elseif ($driver === 'mysql') {
    $dbName = $pdo->query("SELECT DATABASE()")->fetchColumn();
    echo "Database MySQL đang trỏ tới: <b>" . htmlspecialchars($dbName) . "</b><br>";
} else {
    echo "Đang chạy tệp SQLite: <b>" . DB_SQLITE_PATH . "</b><br>";
}

$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
echo "Số lượng tài khoản trong bảng users: <b>" . $totalUsers . "</b>";