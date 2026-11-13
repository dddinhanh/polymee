<?php
/**
 * PolyMee - BaseController
 * Lớp điều khiển cơ sở đóng gói layout và các tham số dùng chung cho mọi trang
 */

declare(strict_types=1);

abstract class BaseController extends Controller
{
    protected array $currentUser;

    public function __construct()
    {
        $this->currentUser = SessionHelper::getCurrentUser();
    }

    /**
     * Render view lồng trong layout chuẩn (header, view, footer)
     */
    protected function render(string $view, array $data = []): void
    {
        $data['currentUser'] = $this->currentUser;
        $data['csrfToken'] = SessionHelper::getCsrfToken();
        $data['flash'] = SessionHelper::getFlash();
        $data['siteName'] = SITE_NAME;

        // Trích xuất biến để dùng trong layout và view
        extract($data);

        // 1. Nạp Header
        require_once APP_ROOT . '/app/views/layouts/header.php';

        // 2. Nạp nội dung trang chính
        $contentPath = APP_ROOT . '/app/views/' . $view . '.php';
        if (file_exists($contentPath)) {
            require_once $contentPath;
        } else {
            throw new RuntimeException("Không tìm thấy tệp view: {$contentPath}");
        }

        // 3. Nạp Widget Trợ lý AI PolyMee (Floating chatbox)
        require_once APP_ROOT . '/app/views/ai/widget.php';

        // 4. Nạp Footer
        require_once APP_ROOT . '/app/views/layouts/footer.php';
    }
}
