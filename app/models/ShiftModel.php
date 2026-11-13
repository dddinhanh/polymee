<?php
/**
 * PolyMee - ShiftModel
 * Quản lý ca trực sự kiện và thực thi giao dịch đăng ký CTV
 */

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class ShiftModel extends BaseModel
{
    protected string $table = 'shifts';

    /**
     * Lấy thông tin ca trực kèm thông tin sự kiện
     */
    public function findWithEvent(int $shiftId): ?array
    {
        $sql = "SELECT s.*, e.title as event_title, e.location_id, l.name as location_name 
                FROM shifts s
                INNER JOIN events e ON s.event_id = e.id
                LEFT JOIN locations l ON e.location_id = l.id
                WHERE s.id = :id LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $shiftId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Đăng ký ca trực cho sinh viên với cơ chế giao dịch an toàn (Transaction)
     */
    public function registerUserForShift(int $shiftId, int $userId): array
    {
        try {
            $this->db->beginTransaction();

            // 1. Khóa và lấy thông tin ca trực
            $shift = $this->find($shiftId);
            if (!$shift) {
                $this->db->rollBack();
                return ['success' => false, 'message' => 'Ca trực không tồn tại.'];
            }

            // 2. Kiểm tra xem user đã đăng ký ca này chưa
            $sqlCheck = "SELECT id, status FROM shift_assignments WHERE shift_id = :shift_id AND user_id = :user_id";
            $stmtCheck = $this->db->prepare($sqlCheck);
            $stmtCheck->execute([':shift_id' => $shiftId, ':user_id' => $userId]);
            $existing = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                $this->db->rollBack();
                return ['success' => false, 'message' => 'Bạn đã đăng ký ca trực này rồi (Trạng thái: ' . $existing['status'] . ').'];
            }

            // 3. Đếm số lượng CTV hiện tại đã duyệt
            $sqlCount = "SELECT COUNT(*) FROM shift_assignments WHERE shift_id = :shift_id AND status = 'Approved'";
            $stmtCount = $this->db->prepare($sqlCount);
            $stmtCount->execute([':shift_id' => $shiftId]);
            $currentApproved = (int)$stmtCount->fetchColumn();

            // Quyết định trạng thái: Approved (nếu còn chỗ) hoặc Waitlist (nếu đủ chỉ tiêu)
            $newStatus = ($currentApproved < (int)$shift['target_qty']) ? 'Approved' : 'Waitlist';

            // 4. Lưu bản ghi phân công
            $sqlInsert = "INSERT INTO shift_assignments (shift_id, user_id, status, assigned_at) VALUES (:shift_id, :user_id, :status, :assigned_at)";
            $stmtInsert = $this->db->prepare($sqlInsert);
            $stmtInsert->execute([
                ':shift_id' => $shiftId,
                ':user_id' => $userId,
                ':status' => $newStatus,
                ':assigned_at' => date('Y-m-d H:i:s')
            ]);
            $assignmentId = (int)$this->db->lastInsertId();

            // 5. Cập nhật approved_qty trên bảng shifts nếu được Approved
            if ($newStatus === 'Approved') {
                $sqlUpdate = "UPDATE shifts SET approved_qty = approved_qty + 1 WHERE id = :shift_id";
                $stmtUpdate = $this->db->prepare($sqlUpdate);
                $stmtUpdate->execute([':shift_id' => $shiftId]);
            }

            $this->db->commit();

            return [
                'success' => true,
                'status' => $newStatus,
                'assignment_id' => $assignmentId,
                'message' => $newStatus === 'Approved' 
                    ? 'Đăng ký ca trực thành công! Bạn đã được duyệt vào danh sách chính thức.'
                    : 'Ca trực đã đủ chỉ tiêu. Bạn đã được tự động thêm vào Danh sách dự phòng (Waitlist).'
            ];

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log('[ShiftModel Error] ' . $e->getMessage());
            return ['success' => false, 'message' => 'Đã xảy ra lỗi trong quá trình lưu dữ liệu: ' . $e->getMessage()];
        }
    }

    /**
     * Lấy danh sách phân công của một ca trực
     */
    public function getAssignmentsByShift(int $shiftId): array
    {
        $sql = "SELECT sa.*, u.full_name, u.student_code, u.email, u.phone
                FROM shift_assignments sa
                INNER JOIN users u ON sa.user_id = u.id
                WHERE sa.shift_id = :shift_id
                ORDER BY sa.assigned_at ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':shift_id', $shiftId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
