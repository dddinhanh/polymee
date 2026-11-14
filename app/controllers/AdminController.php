<?php
/**
 * PolyMee - AdminController
 * Bảng điều khiển quản trị, duyệt nghiệm thu minh chứng và màn hình chiếu Dynamic QR Code
 */

declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';

class AdminController extends BaseController
{
    private EventModel $eventModel;
    private DriveSubmissionModel $driveModel;
    private ShiftModel $shiftModel;

    public function __construct()
    {
        parent::__construct();
        $this->eventModel = new EventModel();
        $this->driveModel = new DriveSubmissionModel();
        $this->shiftModel = new ShiftModel();
    }

    /**
     * Bảng điều khiển Dashboard Admin
     */
    public function dashboard(): void
    {
        $stats = $this->eventModel->getDashboardStats();
        $events = $this->eventModel->getEventsWithDetails();
        $submissions = $this->driveModel->getSubmissionsWithDetails(10);

        $this->render('admin/dashboard', [
            'pageTitle' => 'Dashboard Quản Trị Hệ Thống PolyMee',
            'stats' => $stats,
            'events' => $events,
            'submissions' => $submissions
        ]);
    }

    /**
     * Chuyển hướng về Dashboard (Loại bỏ màn hình chiếu Dynamic QR theo yêu cầu người dùng)
     */
    public function qrProjector(?string $shiftIdParam = null): void
    {
        $this->redirect(URL_ROOT . '/dashboard');
    }

    /**
     * API làm mới mã Dynamic QR Code (gọi định kỳ mỗi 30s từ client)
     */
    public function refreshQr(): void
    {
        $eventId = (int)($_GET['event_id'] ?? 1);
        $shiftId = (int)($_GET['shift_id'] ?? 1);

        $token = DynamicQrHelper::generateToken($eventId, $shiftId);

        $this->json([
            'success' => true,
            'token' => $token,
            'ttl' => QR_TTL_SECONDS,
            'timestamp' => time(),
            'formatted_time' => date('H:i:s d/m/Y')
        ]);
    }

    /**
     * Duyệt bài nghiệm thu minh chứng Google Drive
     */
    public function reviewSubmission(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(URL_ROOT . '/admin/dashboard');
            return;
        }

        $id = (int)($_POST['submission_id'] ?? 0);
        $status = $_POST['status'] ?? 'Accepted'; // 'Accepted' hoặc 'Rejected'

        if ($id > 0 && in_array($status, ['Accepted', 'Rejected'], true)) {
            $this->driveModel->updateStatus($id, $status);
            SessionHelper::setFlash('success', 'Đã cập nhật trạng thái nghiệm thu: ' . $status);
        }

        $this->redirect(URL_ROOT . '/admin/dashboard');
    }
}
