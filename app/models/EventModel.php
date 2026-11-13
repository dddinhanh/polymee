<?php
/**
 * PolyMee - EventModel
 * Quản lý danh mục sự kiện FPoly, lọc theo địa điểm và liên kết ca trực
 */

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class EventModel extends BaseModel
{
    protected string $table = 'events';

    /**
     * Lấy danh sách sự kiện kèm thông tin địa điểm và số lượng ca trực
     */
    public function getEventsWithDetails(?string $keyword = null, ?int $locationId = null): array
    {
        $sql = "SELECT e.*, l.name as location_name, l.address as location_address, l.capacity,
                       u.full_name as organizer_name,
                       (SELECT COUNT(*) FROM shifts s WHERE s.event_id = e.id) as total_shifts,
                       (SELECT COALESCE(SUM(s.target_qty), 0) FROM shifts s WHERE s.event_id = e.id) as total_ctv_target,
                       (SELECT COALESCE(SUM(s.approved_qty), 0) FROM shifts s WHERE s.event_id = e.id) as total_ctv_approved
                FROM events e
                LEFT JOIN locations l ON e.location_id = l.id
                LEFT JOIN users u ON e.created_by = u.id
                WHERE 1=1";

        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (e.title LIKE :kw OR e.description LIKE :kw)";
            $params[':kw'] = '%' . $keyword . '%';
        }

        if (!empty($locationId)) {
            $sql .= " AND e.location_id = :loc_id";
            $params[':loc_id'] = $locationId;
        }

        $sql .= " ORDER BY e.start_time ASC";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy chi tiết một sự kiện kèm địa điểm và ban tổ chức
     */
    public function getEventByIdWithLocation(int $id): ?array
    {
        $sql = "SELECT e.*, l.name as location_name, l.address as location_address, l.capacity,
                       u.full_name as organizer_name, u.email as organizer_email, u.phone as organizer_phone
                FROM events e
                LEFT JOIN locations l ON e.location_id = l.id
                LEFT JOIN users u ON e.created_by = u.id
                WHERE e.id = :id LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Lấy danh sách ca trực của sự kiện
     */
    public function getShiftsByEventId(int $eventId, ?int $currentUserId = null): array
    {
        $sql = "SELECT s.*,
                       (SELECT COUNT(*) FROM shift_assignments sa WHERE sa.shift_id = s.id AND sa.status = 'Approved') as current_approved
                FROM shifts s
                WHERE s.event_id = :event_id
                ORDER BY s.start_time ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->execute();
        $shifts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Nếu có userId, kiểm tra trạng thái đăng ký của user cho từng ca trực
        if ($currentUserId !== null) {
            $userAssignments = $this->getUserAssignmentsForEvent($eventId, $currentUserId);
            foreach ($shifts as &$shift) {
                $shift['user_status'] = $userAssignments[$shift['id']] ?? null;
            }
        }

        return $shifts;
    }

    private function getUserAssignmentsForEvent(int $eventId, int $userId): array
    {
        $sql = "SELECT sa.shift_id, sa.status 
                FROM shift_assignments sa 
                INNER JOIN shifts s ON sa.shift_id = s.id 
                WHERE s.event_id = :event_id AND sa.user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[$row['shift_id']] = $row['status'];
        }
        return $result;
    }

    public function getAllLocations(): array
    {
        $sql = "SELECT * FROM locations ORDER BY name ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thống kê tổng hợp số liệu cho Dashboard Admin
     */
    public function getDashboardStats(): array
    {
        $totalEvents = (int)$this->db->query("SELECT COUNT(*) FROM events")->fetchColumn();
        $totalShifts = (int)$this->db->query("SELECT COUNT(*) FROM shifts")->fetchColumn();
        $totalCtvTarget = (int)$this->db->query("SELECT COALESCE(SUM(target_qty), 0) FROM shifts")->fetchColumn();
        $totalCtvApproved = (int)$this->db->query("SELECT COUNT(*) FROM shift_assignments WHERE status = 'Approved'")->fetchColumn();
        $totalSubmissions = (int)$this->db->query("SELECT COUNT(*) FROM drive_submissions")->fetchColumn();

        $fillRate = $totalCtvTarget > 0 ? round(($totalCtvApproved / $totalCtvTarget) * 100, 1) : 0;

        return [
            'total_events' => $totalEvents,
            'total_shifts' => $totalShifts,
            'total_ctv_target' => $totalCtvTarget,
            'total_ctv_approved' => $totalCtvApproved,
            'active_collaborators' => $totalCtvApproved,
            'fill_rate' => $fillRate,
            'fill_rate_percent' => $fillRate,
            'total_submissions' => $totalSubmissions
        ];
    }
}
