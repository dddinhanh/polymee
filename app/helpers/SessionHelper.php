<?php
/**
 * PolyMee - SessionHelper
 * Quản lý phiên đăng nhập an toàn, phân quyền Role, CSRF Token và Flash Message
 */

declare(strict_types=1);

class SessionHelper
{
    /**
     * Lấy thông tin người dùng hiện tại đang đăng nhập
     * Nếu chưa đăng nhập, tự động giả lập tài khoản Sinh viên mẫu để tiện demo
     */
    public static function getCurrentUser(): array
    {
        if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
            return $_SESSION['user'];
        }

        if (!empty($_SESSION['is_logged_out'])) {
            return [
                'id' => 0,
                'student_code' => 'GUEST',
                'full_name' => 'Khách Vãng Lai',
                'email' => '',
                'role_id' => 0,
                'role_name' => 'Guest',
                'skills' => ''
            ];
        }

        // Mặc định nạp Sinh viên demo (Đinh Nguyễn Phương Anh - PI03084)
        $defaultUser = [
            'id' => 3,
            'student_code' => 'PI03084',
            'full_name' => 'Đinh Nguyễn Phương Anh',
            'email' => 'phuonganh.pi03084@fpt.edu.vn',
            'role_id' => 4,
            'role_name' => 'Student',
            'skills' => 'Media, Chụp ảnh, Quay phim'
        ];
        $_SESSION['user'] = $defaultUser;
        return $defaultUser;
    }

    public static function setCurrentUser(array $user): void
    {
        unset($_SESSION['is_logged_out']);
        $_SESSION['user'] = $user;
        if (session_status() === PHP_SESSION_ACTIVE) {
            @session_regenerate_id(true); // Phòng chống Session Fixation
        }
    }

    public static function logout(): void
    {
        unset($_SESSION['user']);
        $_SESSION['is_logged_out'] = true;
    }

    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user']) && empty($_SESSION['is_logged_out']) && (($_SESSION['user']['id'] ?? 0) > 0);
    }

    public static function isAdmin(): bool
    {
        $user = self::getCurrentUser();
        return in_array((int)($user['role_id'] ?? 4), [1, 2], true);
    }

    public static function switchUser(int $userId): void
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT u.*, r.name as role_name FROM users u LEFT JOIN roles r ON u.role_id = r.id WHERE u.id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            unset($user['password_hash']);
            self::setCurrentUser($user);
        }
    }

    /**
     * Sinh mã CSRF Token bảo mật
     */
    public static function getCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Thẩm định mã CSRF Token
     */
    public static function verifyCsrfToken(?string $token): bool
    {
        if (empty($token) || empty($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Flash Message (Thông báo tức thì sau khi redirect)
     */
    public static function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'] = [
            'type' => $type, // 'success', 'error', 'warning', 'info'
            'message' => $message
        ];
    }

    public static function getFlash(): ?array
    {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }
}
