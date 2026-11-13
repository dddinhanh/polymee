<?php
/**
 * PolyMee - PDO Database Connection (Singleton Pattern)
 * Tuân thủ chuẩn OOP và môn học WEB212 FPT Polytechnic (Nghiêm cấm mysqli)
 */

declare(strict_types=1);

class Database
{
    private static ?Database $instance = null;
    private ?PDO $pdo = null;

    private function __construct()
    {
        $connection = DB_CONNECTION;

        try {
            if ($connection === 'sqlite') {
                $dbDir = dirname(DB_SQLITE_PATH);
                if (!is_dir($dbDir)) {
                    mkdir($dbDir, 0777, true);
                }
                $dsn = 'sqlite:' . DB_SQLITE_PATH;
                $this->pdo = new PDO($dsn);
                // Bật ràng buộc khóa ngoại cho SQLite
                $this->pdo->exec('PRAGMA foreign_keys = ON;');
            } elseif ($connection === 'mysql') {
                $dsn = sprintf(
                    'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                    DB_HOST,
                    DB_PORT,
                    DB_DATABASE
                );
                $this->pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
            } elseif ($connection === 'sqlsrv') {
                $dsn = sprintf(
                    'sqlsrv:Server=%s,%s;Database=%s;TrustServerCertificate=true',
                    DB_SQLSRV_HOST,
                    DB_SQLSRV_PORT,
                    DB_SQLSRV_DB
                );
                $this->pdo = new PDO($dsn, DB_SQLSRV_USER, DB_SQLSRV_PASS);
            } else {
                throw new RuntimeException("Cơ chế kết nối không được hỗ trợ: {$connection}");
            }

            // Thiết lập các thuộc tính chuẩn bảo mật cho PDO
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        } catch (PDOException $e) {
            // Khi lỗi, ghi log an toàn và đưa ra thông báo rõ ràng
            error_log('[PolyMee Database Error] ' . $e->getMessage());
            die('Lỗi kết nối Cơ sở dữ liệu: ' . htmlspecialchars($e->getMessage()));
        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    // Ngăn chặn clone và serialize theo Singleton Pattern
    private function __clone() {}
    public function __wakeup()
    {
        throw new RuntimeException("Không thể khôi phục thể hiện Singleton.");
    }
}
