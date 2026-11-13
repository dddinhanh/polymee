<?php
/**
 * PolyMee - CollisionService
 * Thuật toán phát hiện xung đột thời gian thực (Temporal Interval Collision Detection)
 * Ràng buộc toán học: (Start_A < End_B) AND (End_A > Start_B)
 */

declare(strict_types=1);

class CollisionService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Kiểm tra xem sinh viên có bị trùng lịch với ca trực nào khác đã nhận hay không
     * Trả về mảng ['has_conflict' => bool, 'conflict_shift' => ?array]
     */
    public function checkStudentShiftConflict(int $userId, string $newStart, string $newEnd, ?int $excludeShiftId = null): array
    {
        $sql = "SELECT s.*, e.title as event_title 
                FROM shift_assignments sa
                INNER JOIN shifts s ON sa.shift_id = s.id
                INNER JOIN events e ON s.event_id = e.id
                WHERE sa.user_id = :user_id 
                  AND sa.status IN ('Approved', 'Pending')
                  AND (:new_start < s.end_time AND :new_end > s.start_time)";

        if ($excludeShiftId !== null) {
            $sql .= " AND s.id != :exclude_id";
        }

        $sql .= " LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':new_start', $newStart, PDO::PARAM_STR);
        $stmt->bindValue(':new_end', $newEnd, PDO::PARAM_STR);
        if ($excludeShiftId !== null) {
            $stmt->bindValue(':exclude_id', $excludeShiftId, PDO::PARAM_INT);
        }
        $stmt->execute();
        $conflictingShift = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($conflictingShift) {
            return [
                'has_conflict' => true,
                'message' => sprintf(
                    'Xung đột lịch trực! Khung giờ (%s - %s) bị trùng với ca: "%s" thuộc sự kiện: "%s".',
                    date('H:i d/m', strtotime($newStart)),
                    date('H:i d/m', strtotime($newEnd)),
                    $conflictingShift['name'],
                    $conflictingShift['event_title']
                ),
                'conflict_shift' => $conflictingShift
            ];
        }

        return [
            'has_conflict' => false,
            'message' => 'Lịch trực an toàn, không có xung đột.',
            'conflict_shift' => null
        ];
    }

    /**
     * Kiểm tra xem địa điểm hội trường có bị trùng với sự kiện khác hay không
     */
    public function checkLocationConflict(int $locationId, string $start, string $end, ?int $excludeEventId = null): array
    {
        $sql = "SELECT e.*, l.name as location_name 
                FROM events e
                LEFT JOIN locations l ON e.location_id = l.id
                WHERE e.location_id = :loc_id 
                  AND e.status != 'Cancelled'
                  AND (:new_start < e.end_time AND :new_end > e.start_time)";

        if ($excludeEventId !== null) {
            $sql .= " AND e.id != :exclude_event";
        }

        $sql .= " LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':loc_id', $locationId, PDO::PARAM_INT);
        $stmt->bindValue(':new_start', $start, PDO::PARAM_STR);
        $stmt->bindValue(':new_end', $end, PDO::PARAM_STR);
        if ($excludeEventId !== null) {
            $stmt->bindValue(':exclude_event', $excludeEventId, PDO::PARAM_INT);
        }
        $stmt->execute();
        $conflictingEvent = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($conflictingEvent) {
            return [
                'has_conflict' => true,
                'message' => sprintf(
                    'Địa điểm "%s" đã có sự kiện: "%s" diễn ra từ %s đến %s.',
                    $conflictingEvent['location_name'],
                    $conflictingEvent['title'],
                    date('H:i d/m', strtotime($conflictingEvent['start_time'])),
                    date('H:i d/m', strtotime($conflictingEvent['end_time']))
                ),
                'conflict_event' => $conflictingEvent
            ];
        }

        return [
            'has_conflict' => false,
            'message' => 'Địa điểm còn trống trong khung giờ này.',
            'conflict_event' => null
        ];
    }
}
