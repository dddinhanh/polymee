<?php
/**
 * PolyMee - DriveSubmissionModel
 * Quản lý các liên kết Google Drive bàn giao minh chứng sản phẩm ảnh/video của CTV
 */

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class DriveSubmissionModel extends BaseModel
{
    protected string $table = 'drive_submissions';

    /**
     * Tạo mới bản ghi nộp link Google Drive
     */
    public function createSubmission(int $assignmentId, string $driveUrl, ?string $summary = null): int
    {
        $sql = "INSERT INTO drive_submissions (assignment_id, drive_url, summary, status, submitted_at)
                VALUES (:assignment_id, :drive_url, :summary, 'Pending', :submitted_at)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':assignment_id' => $assignmentId,
            ':drive_url' => $driveUrl,
            ':summary' => $summary ?? 'Bàn giao minh chứng qua Trợ lý AI PolyMee',
            ':submitted_at' => date('Y-m-d H:i:s')
        ]);

        return (int)$this->db->lastInsertId();
    }

    /**
     * Lấy danh sách nghiệm thu kèm thông tin sinh viên và ca trực phục vụ Admin duyệt
     */
    public function getSubmissionsWithDetails(int $limit = 20): array
    {
        $sql = "SELECT ds.*, sa.user_id, u.full_name as student_name, u.student_code,
                       s.name as shift_name, s.points, e.title as event_title
                FROM drive_submissions ds
                INNER JOIN shift_assignments sa ON ds.assignment_id = sa.id
                INNER JOIN users u ON sa.user_id = u.id
                INNER JOIN shifts s ON sa.shift_id = s.id
                INNER JOIN events e ON s.event_id = e.id
                ORDER BY ds.submitted_at DESC
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cập nhật trạng thái duyệt nghiệm thu (Accepted, Rejected)
     */
    public function updateStatus(int $id, string $status): bool
    {
        $sql = "UPDATE drive_submissions 
                SET status = :status, reviewed_at = :reviewed_at 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':status' => $status,
            ':reviewed_at' => date('Y-m-d H:i:s'),
            ':id' => $id
        ]);
    }
}
