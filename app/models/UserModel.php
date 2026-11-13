<?php
/**
 * PolyMee - UserModel
 * Quản lý thông tin tài khoản người dùng, phân quyền Role và tra cứu lịch cá nhân
 */

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class UserModel extends BaseModel
{
    protected string $table = 'users';

    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT u.*, r.name as role_name 
                FROM users u 
                LEFT JOIN roles r ON u.role_id = r.id 
                WHERE u.email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function findByStudentCode(string $code): ?array
    {
        $sql = "SELECT u.*, r.name as role_name 
                FROM users u 
                LEFT JOIN roles r ON u.role_id = r.id 
                WHERE u.student_code = :code LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':code', $code, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Xác thực đăng nhập bằng email hoặc mã số sinh viên
     */
    public function authenticate(string $account, string $password): ?array
    {
        $sql = "SELECT u.*, r.name as role_name 
                FROM users u 
                LEFT JOIN roles r ON u.role_id = r.id 
                WHERE u.email = :acc OR u.student_code = :acc LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':acc', $account, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            unset($user['password_hash']);
            return $user;
        }

        return null;
    }

    /**
     * Lấy danh sách ca trực của sinh viên trong ngày hôm nay phục vụ Trợ lý AI
     */
    public function getStudentShiftsToday(int $userId, ?string $date = null): array
    {
        $targetDate = $date ?? date('Y-m-d');
        // Tìm kiếm các ca trực có ngày trùng với targetDate
        $sql = "SELECT s.*, e.title as event_title, l.name as location_name, l.address as location_address, sa.status as assignment_status, sa.id as assignment_id
                FROM shift_assignments sa
                INNER JOIN shifts s ON sa.shift_id = s.id
                INNER JOIN events e ON s.event_id = e.id
                LEFT JOIN locations l ON e.location_id = l.id
                WHERE sa.user_id = :user_id 
                  AND sa.status = 'Approved'
                  AND s.start_time LIKE :target_date
                ORDER BY s.start_time ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':target_date', $targetDate . '%', PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Đăng ký tài khoản CTV mới
     */
    public function register(array $data): int|false
    {
        $sql = "INSERT INTO users (
                    student_code, full_name, email, password_hash, phone, role_id, skills,
                    id_card_number, vneid_image_url, bank_name, bank_account_number, bank_account_name, is_active
                ) VALUES (
                    :student_code, :full_name, :email, :password_hash, :phone, :role_id, :skills,
                    :id_card_number, :vneid_image_url, :bank_name, :bank_account_number, :bank_account_name, 1
                )";

        $stmt = $this->db->prepare($sql);
        $res = $stmt->execute([
            ':student_code' => $data['student_code'],
            ':full_name' => $data['full_name'],
            ':email' => $data['email'],
            ':password_hash' => $data['password_hash'],
            ':phone' => $data['phone'] ?? null,
            ':role_id' => $data['role_id'] ?? 4, // 4 = Student
            ':skills' => $data['skills'] ?? 'Cộng tác viên mới',
            ':id_card_number' => $data['id_card_number'] ?? null,
            ':vneid_image_url' => $data['vneid_image_url'] ?? null,
            ':bank_name' => $data['bank_name'] ?? null,
            ':bank_account_number' => $data['bank_account_number'] ?? null,
            ':bank_account_name' => !empty($data['bank_account_name']) ? strtoupper($data['bank_account_name']) : strtoupper($data['full_name'])
        ]);

        return $res ? (int)$this->db->lastInsertId() : false;
    }
}
