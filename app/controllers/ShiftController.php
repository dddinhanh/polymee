<?php
/**
 * PolyMee - ShiftController
 * Xử lý đăng ký ca trực và kiểm tra xung đột thời gian thực qua CollisionService
 */

declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';

class ShiftController extends BaseController
{
    private ShiftModel $shiftModel;
    private CollisionService $collisionService;

    public function __construct()
    {
        parent::__construct();
        $this->shiftModel = new ShiftModel();
        $this->collisionService = new CollisionService();
    }

    /**
     * API / Form xử lý đăng ký ca trực
     */
    public function register(): void
    {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($isAjax) {
                $this->json(['success' => false, 'message' => 'Phương thức không được hỗ trợ.'], 405);
            }
            $this->redirect(URL_ROOT . '/');
            return;
        }

        $shiftId = isset($_POST['shift_id']) ? (int)$_POST['shift_id'] : 0;
        $userId = (int)($this->currentUser['id'] ?? 0);

        if ($shiftId <= 0 || $userId <= 0) {
            $msg = 'Thông tin đăng ký không hợp lệ.';
            if ($isAjax) $this->json(['success' => false, 'message' => $msg], 400);
            SessionHelper::setFlash('error', $msg);
            $this->redirect(URL_ROOT . '/');
            return;
        }

        // 1. Lấy thông tin ca trực
        $shift = $this->shiftModel->findWithEvent($shiftId);
        if (!$shift) {
            $msg = 'Ca trực không tồn tại.';
            if ($isAjax) $this->json(['success' => false, 'message' => $msg], 404);
            SessionHelper::setFlash('error', $msg);
            $this->redirect(URL_ROOT . '/');
            return;
        }

        // 2. Chạy thuật toán phát hiện xung đột lịch trực của sinh viên
        $conflictResult = $this->collisionService->checkStudentShiftConflict(
            $userId,
            $shift['start_time'],
            $shift['end_time']
        );

        if ($conflictResult['has_conflict']) {
            if ($isAjax) {
                $this->json([
                    'success' => false,
                    'is_conflict' => true,
                    'message' => $conflictResult['message'],
                    'conflict_shift' => $conflictResult['conflict_shift']
                ], 409);
            }
            SessionHelper::setFlash('error', $conflictResult['message']);
            $this->redirect(URL_ROOT . '/event/detail/' . $shift['event_id']);
            return;
        }

        // 3. Thực thi lưu trữ phân công qua ShiftModel
        $result = $this->shiftModel->registerUserForShift($shiftId, $userId);

        if ($isAjax) {
            $this->json($result, $result['success'] ? 200 : 400);
        }

        SessionHelper::setFlash($result['success'] ? 'success' : 'error', $result['message']);
        $this->redirect(URL_ROOT . '/event/detail/' . $shift['event_id']);
    }

    /**
     * Trang xem Lịch làm việc cá nhân của sinh viên
     */
    public function myShifts(): void
    {
        $userId = (int)($this->currentUser['id'] ?? 0);
        $userModel = new UserModel();

        // Lấy toàn bộ ca trực đã được duyệt của sinh viên
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT s.*, e.title as event_title, l.name as location_name, sa.status as assignment_status, sa.id as assignment_id
                FROM shift_assignments sa
                INNER JOIN shifts s ON sa.shift_id = s.id
                INNER JOIN events e ON s.event_id = e.id
                LEFT JOIN locations l ON e.location_id = l.id
                WHERE sa.user_id = :user_id
                ORDER BY s.start_time ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $shifts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('events/my_shifts', [
            'pageTitle' => 'Lịch Làm Việc & Ca Trực Của Tôi',
            'shifts' => $shifts
        ]);
    }

    /**
     * API lấy danh sách CTV đã được duyệt vào ca trực (dành cho Admin / BTC)
     */
    public function assignments(?string $shiftIdParam = null): void
    {
        $shiftId = (int)($shiftIdParam ?? ($_GET['shift_id'] ?? 0));
        if ($shiftId <= 0) {
            $this->json(['success' => false, 'message' => 'Ca trực không hợp lệ.'], 400);
            return;
        }

        $assignments = $this->shiftModel->getAssignmentsByShift($shiftId);
        $this->json([
            'success' => true,
            'shift_id' => $shiftId,
            'assignments' => $assignments,
            'total' => count($assignments)
        ]);
    }
}
