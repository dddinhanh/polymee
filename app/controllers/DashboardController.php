<?php
/**
 * PolyMee - DashboardController
 * Giao diện Dashboard chuyên biệt mới sử dụng Bootstrap 5
 * Tích hợp Bảng lịch 3 tầng (Tháng -> Tuần -> Ngày), Đặt lịch trực tiếp & Nghiệm thu Kế toán
 */

declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';

class DashboardController extends BaseController
{
    private EventModel $eventModel;
    private ShiftModel $shiftModel;

    public function __construct()
    {
        parent::__construct();
        $this->eventModel = new EventModel();
        $this->shiftModel = new ShiftModel();
    }

    /**
     * Màn hình chính Dashboard Bảng Lịch & Nghiệm Thu Kế Toán
     */
    public function index(): void
    {
        $db = Database::getInstance()->getConnection();

        // 1. Thống kê tổng quan
        $stats = $this->eventModel->getDashboardStats();

        // 2. Lấy toàn bộ ca trực kèm thông tin sự kiện & địa điểm phục vụ hiển thị lên lịch
        $sql = "SELECT s.*, 
                       e.title as event_title, 
                       e.banner_url, 
                       l.name as location_name, 
                       l.address as location_address,
                       sa.id as my_assignment_id,
                       sa.proof_image_url as my_proof_image_url,
                       sa.proof_note as my_proof_note,
                       sa.proof_submitted_at as my_proof_submitted_at,
                       sa.lecturer_verified as my_lecturer_verified,
                       sa.lecturer_name as my_lecturer_name,
                       sa.contract_code as my_contract_code,
                       sa.school_status as my_school_status,
                       sa.accounting_status as my_accounting_status,
                       CASE WHEN sa.id IS NOT NULL THEN 1 ELSE 0 END as is_registered_by_me
                FROM shifts s
                INNER JOIN events e ON s.event_id = e.id
                LEFT JOIN locations l ON e.location_id = l.id
                LEFT JOIN shift_assignments sa ON sa.shift_id = s.id AND sa.user_id = :current_user_id
                ORDER BY s.start_time ASC";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':current_user_id', $this->currentUser['id'] ?? 0, PDO::PARAM_INT);
        $stmt->execute();
        $allShifts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 3. Lấy danh sách sự kiện và địa điểm
        $events = $this->eventModel->getEventsWithDetails();
        $locations = $this->eventModel->getAllLocations();

        // 4. Lấy BẢNG NGHIỆM THU KẾ TOÁN (Bao gồm Họ tên, SĐT, CCCD, Ảnh VNeID, Ảnh hiện trường, GV duyệt, Hợp đồng, Ngân hàng, STK, Thù lao)
        $sqlAccounting = "SELECT sa.id as assignment_id, sa.status as assignment_status, sa.assigned_at,
                                 sa.accounting_status, sa.accounting_note,
                                 sa.proof_image_url, sa.proof_note, sa.proof_submitted_at,
                                 sa.lecturer_verified, sa.lecturer_name, sa.lecturer_verified_at,
                                 sa.contract_code, sa.school_status,
                                 u.id as user_id, u.student_code, u.full_name, u.email, u.phone,
                                 u.id_card_number, u.vneid_image_url, u.bank_name, u.bank_account_number, u.bank_account_name,
                                 s.id as shift_id, s.name as shift_name, s.start_time, s.end_time,
                                 COALESCE(s.remuneration_amount, 150000) as remuneration_amount,
                                 s.points,
                                 e.title as event_title,
                                 l.name as location_name
                          FROM shift_assignments sa
                          INNER JOIN users u ON sa.user_id = u.id
                          INNER JOIN shifts s ON sa.shift_id = s.id
                          INNER JOIN events e ON s.event_id = e.id
                          LEFT JOIN locations l ON e.location_id = l.id
                          ORDER BY sa.assigned_at DESC";
        $accountingList = $db->query($sqlAccounting)->fetchAll(PDO::FETCH_ASSOC);

        // 5. Lấy danh sách Ghi chú / Lịch hẹn toàn trường (Calendar Notes & Memos theo BA Baseline)
        $calendarNotes = $db->query("SELECT * FROM calendar_notes ORDER BY date ASC")->fetchAll(PDO::FETCH_ASSOC) ?: [];

        // 6. Lấy thông tin tài khoản CTV hiện tại (cho form cập nhật hồ sơ ngân hàng / VNeID)
        $stmtUser = $db->prepare("SELECT * FROM users WHERE id = :uid");
        $stmtUser->execute([':uid' => $this->currentUser['id'] ?? 0]);
        $userProfile = $stmtUser->fetch(PDO::FETCH_ASSOC) ?: $this->currentUser;

        // Render view chuyên biệt Bootstrap 5 & Apple macOS Sonoma / iPadOS
        $this->renderBootstrapDashboard('dashboard/index', [
            'pageTitle' => 'PolyMee Operations Hub - Lịch & Nghiệm Thu Kế Toán',
            'stats' => $stats,
            'shifts' => $allShifts,
            'events' => $events,
            'locations' => $locations,
            'accountingList' => $accountingList,
            'calendarNotes' => $calendarNotes,
            'userProfile' => $userProfile,
            'currentUser' => $this->currentUser,
            'flash' => SessionHelper::getFlash(),
            'csrfToken' => SessionHelper::getCsrfToken()
        ]);
    }

    /**
     * API Tạo / Đặt lịch ca trực sự kiện mới ngay từ trang Dashboard
     */
    public function createShift(): void
    {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Phương thức không được hỗ trợ.'], 405);
            return;
        }

        // Kiểm tra quyền: Chỉ Admin hoặc Organizer mới được đặt lịch
        $roleName = $this->currentUser['role_name'] ?? '';
        if (!in_array($roleName, ['Admin', 'Organizer'], true)) {
            $this->json(['success' => false, 'message' => 'Bạn không có quyền tạo sự kiện / đặt lịch ca trực.'], 403);
            return;
        }

        $eventId = (int)($_POST['event_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $startTime = trim($_POST['start_time'] ?? '');
        $endTime = trim($_POST['end_time'] ?? '');
        $targetQty = (int)($_POST['target_qty'] ?? 4);
        $points = (int)($_POST['points'] ?? 10);
        $remunerationAmount = (int)($_POST['remuneration_amount'] ?? 200000);
        $skillRequired = trim($_POST['skill_required'] ?? 'Kỹ năng làm việc nhóm & trách nhiệm');

        if (empty($name) || empty($startTime) || empty($endTime) || $eventId <= 0) {
            $this->json(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin sự kiện, tên ca và thời gian.'], 400);
            return;
        }

        try {
            $db = Database::getInstance()->getConnection();
            $sql = "INSERT INTO shifts (event_id, name, start_time, end_time, target_qty, approved_qty, points, remuneration_amount, skill_required)
                    VALUES (:event_id, :name, :start_time, :end_time, :target_qty, 0, :points, :remuneration_amount, :skill_required)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':event_id' => $eventId,
                ':name' => $name,
                ':start_time' => $startTime,
                ':end_time' => $endTime,
                ':target_qty' => $targetQty,
                ':points' => $points,
                ':remuneration_amount' => $remunerationAmount,
                ':skill_required' => $skillRequired
            ]);

            $newShiftId = (int)$db->lastInsertId();

            // Lấy lại ca vừa tạo kèm tên sự kiện & địa điểm
            $shift = $this->shiftModel->findWithEvent($newShiftId);

            $this->json([
                'success' => true,
                'message' => 'Đặt lịch ca trực thành công! Ca mới đã xuất hiện ngay trên Bảng Lịch.',
                'shift' => $shift
            ]);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Lỗi lưu ca trực: ' . $e->getMessage()], 500);
        }
    }

    /**
     * API Kế toán cập nhật trạng thái duyệt chi thù lao
     */
    public function updateAccountingStatus(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Phương thức không được hỗ trợ.'], 405);
            return;
        }

        $assignmentId = (int)($_POST['assignment_id'] ?? 0);
        $status = trim($_POST['status'] ?? 'Approved'); // 'Pending', 'Approved', 'Paid'
        $note = trim($_POST['note'] ?? '');

        if ($assignmentId <= 0) {
            $this->json(['success' => false, 'message' => 'Hồ sơ nghiệm thu không hợp lệ.'], 400);
            return;
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE shift_assignments SET accounting_status = :st, accounting_note = :note WHERE id = :id");
        $stmt->execute([
            ':st' => $status,
            ':note' => $note,
            ':id' => $assignmentId
        ]);

        $this->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái nghiệm thu kế toán thành công!',
            'status' => $status
        ]);
    }

    /**
     * API Sinh viên / CTV tự cập nhật thông tin CCCD, Ảnh VNeID, Ngân hàng, STK
     */
    public function updateAccountingProfile(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Phương thức không được hỗ trợ.'], 405);
            return;
        }

        $userId = (int)($this->currentUser['id'] ?? 0);
        $idCardNumber = trim($_POST['id_card_number'] ?? '');
        $vneidImageUrl = trim($_POST['vneid_image_url'] ?? '');
        $bankName = trim($_POST['bank_name'] ?? '');
        $bankAccountNumber = trim($_POST['bank_account_number'] ?? '');
        $bankAccountName = strtoupper(trim($_POST['bank_account_name'] ?? ''));

        if ($userId <= 0 || empty($idCardNumber) || empty($bankAccountNumber)) {
            $this->json(['success' => false, 'message' => 'Vui lòng cung cấp đầy đủ số CCCD và Số tài khoản ngân hàng.'], 400);
            return;
        }

        $db = Database::getInstance()->getConnection();
        $sql = "UPDATE users SET 
                    id_card_number = :cccd,
                    vneid_image_url = :vneid,
                    bank_name = :bname,
                    bank_account_number = :stk,
                    bank_account_name = :owner
                WHERE id = :uid";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':cccd' => $idCardNumber,
            ':vneid' => $vneidImageUrl,
            ':bname' => $bankName,
            ':stk' => $bankAccountNumber,
            ':owner' => $bankAccountName,
            ':uid' => $userId
        ]);

        $this->json([
            'success' => true,
            'message' => 'Cập nhật hồ sơ nhận thù lao (VNeID & Ngân hàng) thành công!'
        ]);
    }

    /**
     * API CTV nộp ảnh chụp hiện trường chứng minh có mặt nghiệm thu
     */
    public function submitOnSiteProof(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Phương thức không được hỗ trợ.'], 405);
            return;
        }

        $assignmentId = (int)($_POST['assignment_id'] ?? 0);
        $proofImageUrl = trim($_POST['proof_image_url'] ?? '');
        $proofNote = trim($_POST['proof_note'] ?? 'Có mặt đúng giờ tại địa điểm trực sự kiện');

        if ($assignmentId <= 0 || empty($proofImageUrl)) {
            $this->json(['success' => false, 'message' => 'Vui lòng cung cấp đường dẫn ảnh hiện trường minh chứng.'], 400);
            return;
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE shift_assignments SET 
                                proof_image_url = :img, 
                                proof_note = :note, 
                                proof_submitted_at = datetime('now', 'localtime') 
                              WHERE id = :id");
        $stmt->execute([
            ':img' => $proofImageUrl,
            ':note' => $proofNote,
            ':id' => $assignmentId
        ]);

        $this->json([
            'success' => true,
            'message' => 'Đã gửi ảnh hiện trường minh chứng thành công! Đang chờ Giảng viên xác nhận nghiệm thu.',
            'proof_image_url' => $proofImageUrl
        ]);
    }

    /**
     * API Giảng viên xác nhận nghiệm thu ảnh hiện trường & lập hợp đồng
     */
    public function verifyLecturerAcceptance(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Phương thức không được hỗ trợ.'], 405);
            return;
        }

        $assignmentId = (int)($_POST['assignment_id'] ?? 0);
        $lecturerName = trim($_POST['lecturer_name'] ?? ($this->currentUser['full_name'] ?? 'Giảng viên phụ trách'));

        if ($assignmentId <= 0) {
            $this->json(['success' => false, 'message' => 'Hồ sơ không hợp lệ.'], 400);
            return;
        }

        $contractCode = 'HDNT-2026-FPLY-' . str_pad((string)$assignmentId, 3, '0', STR_PAD_LEFT);

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE shift_assignments SET 
                                lecturer_verified = 1, 
                                lecturer_name = :lname, 
                                lecturer_verified_at = datetime('now', 'localtime'),
                                contract_code = :ccode,
                                accounting_status = 'Approved'
                              WHERE id = :id");
        $stmt->execute([
            ':lname' => $lecturerName,
            ':ccode' => $contractCode,
            ':id' => $assignmentId
        ]);

        $this->json([
            'success' => true,
            'message' => 'Giảng viên đã xác nhận nghiệm thu hiện trường và khởi tạo Hợp đồng ' . $contractCode . '!',
            'contract_code' => $contractCode,
            'lecturer_name' => $lecturerName
        ]);
    }

    /**
     * API Trình hồ sơ hợp đồng lên Trường ký xác nhận chuyển tiền
     */
    public function submitToSchoolForSigning(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Phương thức không được hỗ trợ.'], 405);
            return;
        }

        $assignmentId = (int)($_POST['assignment_id'] ?? 0);

        if ($assignmentId <= 0) {
            $this->json(['success' => false, 'message' => 'Hồ sơ không hợp lệ.'], 400);
            return;
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE shift_assignments SET 
                                school_status = 'Signed_Paid',
                                accounting_status = 'Paid'
                              WHERE id = :id");
        $stmt->execute([':id' => $assignmentId]);

        $this->json([
            'success' => true,
            'message' => 'Hồ sơ nghiệm thu đã được Nhà trường ký duyệt và chuyển khoản thù lao thành công!'
        ]);
    }

    /**
     * Render view Bootstrap 5 độc lập
     */
    private function renderBootstrapDashboard(string $view, array $data = []): void
    {
        extract($data);
        $viewFile = APP_ROOT . '/app/views/' . $view . '.php';

        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            throw new RuntimeException("Không tìm thấy view: {$viewFile}");
        }
    }
}
