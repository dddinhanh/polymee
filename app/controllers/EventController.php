<?php
/**
 * PolyMee - EventController
 * Quản lý chi tiết sự kiện và phân rã các ca trực CTV
 */

declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';

class EventController extends BaseController
{
    private EventModel $eventModel;

    public function __construct()
    {
        parent::__construct();
        $this->eventModel = new EventModel();
    }

    /**
     * Trang chi tiết sự kiện
     */
    public function detail(?string $id = null): void
    {
        $eventId = (int)$id;
        if ($eventId <= 0) {
            SessionHelper::setFlash('error', 'Mã sự kiện không hợp lệ.');
            $this->redirect(URL_ROOT . '/');
            return;
        }

        $event = $this->eventModel->getEventByIdWithLocation($eventId);
        if (!$event) {
            SessionHelper::setFlash('error', 'Không tìm thấy sự kiện yêu cầu.');
            $this->redirect(URL_ROOT . '/');
            return;
        }

        $currentUserId = (int)($this->currentUser['id'] ?? 0);
        $shifts = $this->eventModel->getShiftsByEventId($eventId, $currentUserId);

        $this->render('events/detail', [
            'pageTitle' => $event['title'],
            'event' => $event,
            'shifts' => $shifts
        ]);
    }
}
