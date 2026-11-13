<?php
/**
 * PolyMee - HomeController
 * Xử lý trang chủ danh sách sự kiện, bộ lọc tìm kiếm và chuyển đổi tài khoản demo
 */

declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';

class HomeController extends BaseController
{
    private EventModel $eventModel;

    public function __construct()
    {
        parent::__construct();
        $this->eventModel = new EventModel();
    }

    /**
     * Trang chủ hiển thị danh sách sự kiện hoặc dẫn thẳng vào Dashboard Bảng Lịch
     */
    public function index(): void
    {
        // Điều hướng thẳng vào Dashboard Bảng Lịch chuyên biệt mới theo yêu cầu
        $this->redirect(URL_ROOT . '/dashboard');
    }

    /**
     * Màn hình danh sách sự kiện dạng lưới truyền thống
     */
    public function events(): void
    {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : null;
        $locationId = isset($_GET['location_id']) && is_numeric($_GET['location_id']) ? (int)$_GET['location_id'] : null;

        $events = $this->eventModel->getEventsWithDetails($keyword, $locationId);
        $locations = $this->eventModel->getAllLocations();
        $stats = $this->eventModel->getDashboardStats();

        $this->render('home/index', [
            'pageTitle' => 'Sự Kiện & Ca Trực FPoly Mới Nhất',
            'events' => $events,
            'locations' => $locations,
            'stats' => $stats,
            'filterKeyword' => $keyword,
            'filterLocation' => $locationId
        ]);
    }

    /**
     * Chuyển đổi nhanh tài khoản người dùng để phục vụ demo hội đồng bảo vệ
     */
    public function switchUser(?string $userId = null): void
    {
        $id = (int)($userId ?? 3);
        SessionHelper::switchUser($id);
        SessionHelper::setFlash('success', 'Đã chuyển sang tài khoản: ' . SessionHelper::getCurrentUser()['full_name']);
        $this->redirect(URL_ROOT . '/');
    }
}
