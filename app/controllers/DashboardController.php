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

        // 1. Thống kê tổng quan & Phân quyền chuẩn RBAC
        $stats = $this->eventModel->getDashboardStats();
        $permissions = RolePermissionHelper::getPermissions($this->currentUser);

        // 2. Lấy toàn bộ ca trực kèm thông tin sự kiện, bộ môn & giảng viên đặt lịch
        $sql = "SELECT s.*, 
                       e.title as event_title, 
                       e.banner_url, 
                       e.department as event_department,
                       e.created_by as event_created_by,
                       COALESCE(u_creator.full_name, 'Giảng viên FPT Poly') as lecturer_name,
                       u_creator.student_code as lecturer_code,
                       COALESCE(u_creator.department, e.department, 'Công nghệ Thông tin') as lecturer_department,
                       l.name as location_name, 
                       l.address as location_address,
                       s.shift_type,
                       sa.id as my_assignment_id,
                       COALESCE(sa.ctv_type, s.shift_type, 'FACILITY') as my_ctv_type,
                       sa.proof_image_url as my_proof_image_url,
                       sa.proof_drive_url as my_proof_drive_url,
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
                LEFT JOIN users u_creator ON e.created_by = u_creator.id
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

        // 4. Lấy BẢNG NGHIỆM THU KẾ TOÁN (Tuân thủ RBAC: Kế toán, Trưởng HC, Admin, Giảng viên xem toàn bộ để nghiệm thu & trình ký; CTV chỉ xem ca của mình)
        $whereAccounting = "";
        $paramsAccounting = [];
        if (!$permissions['canViewAccounting'] && $permissions['role'] !== 'LECTURER') {
            $whereAccounting = " WHERE sa.user_id = :current_user_id ";
            $paramsAccounting[':current_user_id'] = $this->currentUser['id'] ?? 0;
        }

        $sqlAccounting = "SELECT sa.id as assignment_id, sa.status as assignment_status, sa.assigned_at,
                                 sa.accounting_status, sa.accounting_note,
                                 COALESCE(sa.ctv_type, s.shift_type, 'FACILITY') as ctv_type,
                                 sa.proof_image_url, sa.proof_drive_url, sa.proof_note, sa.proof_submitted_at,
                                 sa.lecturer_verified, sa.lecturer_name, sa.lecturer_verified_at,
                                 sa.contract_code, sa.school_status,
                                 u.id as user_id, u.student_code, u.full_name, u.email, u.phone,
                                 COALESCE(u.department, 'Công nghệ Thông tin') as user_department,
                                 u.id_card_number, u.vneid_image_url, u.bank_name, u.bank_account_number, u.bank_account_name,
                                 s.id as shift_id, s.name as shift_name, s.shift_type, s.start_time, s.end_time,
                                 COALESCE(s.remuneration_amount, 150000) as remuneration_amount,
                                 s.points,
                                 e.id as event_id,
                                 e.title as event_title,
                                 e.created_by as event_created_by,
                                 e.department as event_department,
                                 COALESCE(u_creator.full_name, 'Giảng viên FPT Poly') as event_lecturer_name,
                                 COALESCE(u_creator.department, e.department, 'Công nghệ Thông tin') as event_lecturer_department,
                                 l.name as location_name
                          FROM shift_assignments sa
                          INNER JOIN users u ON sa.user_id = u.id
                          INNER JOIN shifts s ON sa.shift_id = s.id
                          INNER JOIN events e ON s.event_id = e.id
                          LEFT JOIN users u_creator ON e.created_by = u_creator.id
                          LEFT JOIN locations l ON e.location_id = l.id
                          {$whereAccounting}
                          ORDER BY sa.assigned_at DESC";
        $stmtAcc = $db->prepare($sqlAccounting);
        $stmtAcc->execute($paramsAccounting);
        $accountingList = $stmtAcc->fetchAll(PDO::FETCH_ASSOC);

        // 5. Lấy danh sách Ghi chú / Lịch hẹn toàn trường (Calendar Notes & Memos theo BA Baseline)
        $calendarNotes = $db->query("SELECT * FROM calendar_notes ORDER BY date ASC")->fetchAll(PDO::FETCH_ASSOC) ?: [];

        // 6. Lấy thông tin tài khoản CTV hiện tại (cho form cập nhật hồ sơ ngân hàng / VNeID)
        $stmtUser = $db->prepare("SELECT u.*, r.name as role_name, r.description as role_desc FROM users u INNER JOIN roles r ON u.role_id = r.id WHERE u.id = :uid");
        $stmtUser->execute([':uid' => $this->currentUser['id'] ?? 0]);
        $userProfile = $stmtUser->fetch(PDO::FETCH_ASSOC) ?: $this->currentUser;

        // 7. Lấy danh sách toàn bộ người dùng & vai trò (phục vụ Quản Trị Cấp Quyền)
        $stmtAllUsers = $db->query("SELECT u.*, r.name as role_name, r.description as role_desc FROM users u INNER JOIN roles r ON u.role_id = r.id ORDER BY u.id ASC");
        $allUsers = $stmtAllUsers->fetchAll(PDO::FETCH_ASSOC) ?: [];

        // 8. Lấy danh mục tất cả vai trò hệ thống
        $stmtRoles = $db->query("SELECT * FROM roles ORDER BY id ASC");
        $roles = $stmtRoles->fetchAll(PDO::FETCH_ASSOC) ?: [];

        // 9. Lấy nhật ký lịch sử thao tác hệ thống (Audit Trail)
        $stmtLogs = $db->query("SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 60");
        $activityLogs = $stmtLogs->fetchAll(PDO::FETCH_ASSOC) ?: [];

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
            'allUsers' => $allUsers,
            'roles' => $roles,
            'activityLogs' => $activityLogs,
            'currentUser' => $this->currentUser,
            'permissions' => $permissions,
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

        // Kiểm tra quyền RBAC: Giảng viên/BTC, Trưởng HC, Admin
        $permissions = RolePermissionHelper::getPermissions($this->currentUser);
        if (!$permissions['canCreateShift']) {
            $this->json(['success' => false, 'message' => 'Bạn không có quyền tạo sự kiện / đặt lịch ca trực. Chức năng dành cho Giảng viên/Ban tổ chức và Quản trị viên.'], 403);
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
        $shiftTypeInput = strtoupper(trim($_POST['shift_type'] ?? ''));
        $shiftType = in_array($shiftTypeInput, ['MEDIA', 'FACILITY', 'NONE'], true) 
                     ? $shiftTypeInput 
                     : (stripos($name, 'media') !== false || stripos($name, 'quay') !== false || stripos($name, 'chụp') !== false || stripos($name, 'ảnh') !== false ? 'MEDIA' : 'FACILITY');

        if ($shiftType === 'NONE') {
            $targetQty = 0;
            $remunerationAmount = 0;
        }

        if (empty($name) || empty($startTime) || empty($endTime) || $eventId <= 0) {
            $this->json(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin sự kiện, tên ca và thời gian.'], 400);
            return;
        }

        try {
            $db = Database::getInstance()->getConnection();
            $sql = "INSERT INTO shifts (event_id, name, shift_type, start_time, end_time, target_qty, approved_qty, points, remuneration_amount, skill_required)
                    VALUES (:event_id, :name, :shift_type, :start_time, :end_time, :target_qty, 0, :points, :remuneration_amount, :skill_required)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':event_id' => $eventId,
                ':name' => $name,
                ':shift_type' => $shiftType,
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

        // Kiểm tra quyền RBAC: Kế toán, Trưởng HC, Admin
        $permissions = RolePermissionHelper::getPermissions($this->currentUser);
        if (!$permissions['canApproveAccounting']) {
            $this->json(['success' => false, 'message' => 'Bạn không có quyền phê duyệt quyết toán thù lao. Quyền này chỉ dành cho Kế toán, Trưởng hành chính và Quản trị viên.'], 403);
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
     * API CTV nộp minh chứng nghiệm thu sau sự kiện:
     * - CTV Bàn ghế, máy móc: Upload/dán ảnh hiện trường bàn giao thiết bị
     * - CTV Media quay chụp: Dán Link Google Drive sản phẩm
     */
    public function submitOnSiteProof(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Phương thức không được hỗ trợ.'], 405);
            return;
        }

        $assignmentId = (int)($_POST['assignment_id'] ?? 0);
        $proofImageUrl = trim($_POST['proof_image_url'] ?? '');
        $proofDriveUrl = trim($_POST['proof_drive_url'] ?? '');
        $proofNote = trim($_POST['proof_note'] ?? 'Đã hoàn thành nhiệm vụ ca trực sự kiện');
        $ctvType = strtoupper(trim($_POST['ctv_type'] ?? ''));

        // Tự động phân loại nếu người dùng gửi link Google Drive
        if (!empty($proofDriveUrl) || (stripos($proofImageUrl, 'drive.google.com') !== false)) {
            if (empty($proofDriveUrl)) {
                $proofDriveUrl = $proofImageUrl;
            }
            $ctvType = 'MEDIA';
        }

        if (empty($ctvType)) {
            $ctvType = 'FACILITY';
        }

        if ($assignmentId <= 0 || (empty($proofImageUrl) && empty($proofDriveUrl))) {
            $this->json(['success' => false, 'message' => 'Vui lòng cung cấp minh chứng nghiệm thu (Ảnh hiện trường hoặc Link Google Drive).'], 400);
            return;
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE shift_assignments SET 
                                proof_image_url = :img, 
                                proof_drive_url = :drive,
                                ctv_type = :ctv_type,
                                proof_note = :note, 
                                proof_submitted_at = datetime('now', 'localtime') 
                              WHERE id = :id");
        $stmt->execute([
            ':img' => $proofImageUrl,
            ':drive' => $proofDriveUrl,
            ':ctv_type' => $ctvType,
            ':note' => $proofNote,
            ':id' => $assignmentId
        ]);

        $this->logActivity(
            'SUBMIT_PROOF',
            'shift_assignment',
            $assignmentId,
            ($ctvType === 'MEDIA' ? 'CTV Media nộp link Google Drive: ' . $proofDriveUrl : 'CTV Bàn ghế máy móc nộp ảnh hiện trường: ' . $proofImageUrl)
        );

        $this->json([
            'success' => true,
            'message' => ($ctvType === 'MEDIA' ? 'Đã gửi link Google Drive sản phẩm thành công!' : 'Đã gửi ảnh hiện trường minh chứng thành công!') . ' Đang chờ Giảng viên bộ môn xác nhận nghiệm thu.',
            'proof_image_url' => $proofImageUrl,
            'proof_drive_url' => $proofDriveUrl,
            'ctv_type' => $ctvType
        ]);
    }

    /**
     * API Giảng viên xác nhận nghiệm thu (Ảnh hiện trường / Link Drive) & Lập hợp đồng điện tử
     */
    public function verifyLecturerAcceptance(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Phương thức không được hỗ trợ.'], 405);
            return;
        }

        // Kiểm tra quyền RBAC: Giảng viên/BTC, Trưởng HC, Admin
        $permissions = RolePermissionHelper::getPermissions($this->currentUser);
        if (!$permissions['canVerifyLecturer']) {
            $this->json(['success' => false, 'message' => 'Bạn không có quyền xác nhận nghiệm thu ca trực. Quyền này thuộc về Giảng viên / Ban tổ chức sự kiện.'], 403);
            return;
        }

        $assignmentId = (int)($_POST['assignment_id'] ?? 0);
        $lecturerName = trim($_POST['lecturer_name'] ?? ($this->currentUser['full_name'] ?? 'Giảng viên phụ trách'));
        $decision = trim($_POST['decision'] ?? 'approve');
        $note = trim($_POST['note'] ?? '');

        if ($assignmentId <= 0) {
            $this->json(['success' => false, 'message' => 'Hồ sơ không hợp lệ.'], 400);
            return;
        }

        $db = Database::getInstance()->getConnection();

        // Xử lý khi Giảng viên chọn "Yêu cầu bổ sung"
        if ($decision === 'request_more') {
            $stmt = $db->prepare("UPDATE shift_assignments SET 
                                    lecturer_verified = 0, 
                                    lecturer_name = :lname, 
                                    accounting_status = 'Revision_Required',
                                    accounting_note = :note,
                                    school_status = 'Pending'
                                  WHERE id = :id");
            $stmt->execute([
                ':lname' => $lecturerName,
                ':note' => $note ?: 'Cần bổ sung thêm minh chứng rõ nét hơn',
                ':id' => $assignmentId
            ]);

            $this->logActivity(
                'REQUEST_PROOF_REVISION',
                'shift_assignment',
                $assignmentId,
                "Giảng viên {$lecturerName} đã gửi yêu cầu bổ sung minh chứng: " . ($note ?: 'Minh chứng chưa đủ')
            );

            $this->json([
                'success' => true,
                'message' => 'Đã gửi yêu cầu bổ sung minh chứng thành công! CTV sẽ nhận được thông báo để cập nhật.',
                'decision' => 'request_more'
            ]);
            return;
        }

        $contractCode = 'HDNT-2026-FPLY-' . str_pad((string)$assignmentId, 3, '0', STR_PAD_LEFT);

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE shift_assignments SET 
                                lecturer_verified = 1, 
                                lecturer_name = :lname, 
                                lecturer_verified_at = datetime('now', 'localtime'),
                                contract_code = :ccode,
                                accounting_status = 'Approved',
                                school_status = 'Pending'
                              WHERE id = :id");
        $stmt->execute([
            ':lname' => $lecturerName,
            ':ccode' => $contractCode,
            ':id' => $assignmentId
        ]);

        $this->logActivity(
            'VERIFY_PROOF',
            'shift_assignment',
            $assignmentId,
            "Giảng viên {$lecturerName} đã nghiệm thu minh chứng và khởi tạo hợp đồng {$contractCode}"
        );

        $this->json([
            'success' => true,
            'message' => 'Giảng viên đã xác nhận nghiệm thu và khởi tạo Hợp đồng điện tử ' . $contractCode . '! Đã chuyển tiếp đến Nhà trường / Hành chính ký duyệt.',
            'contract_code' => $contractCode,
            'lecturer_name' => $lecturerName
        ]);
    }

    /**
     * API Nhân viên Hành chính / Nhà trường ký xác nhận hợp đồng & hoàn tất chuyển khoản
     */
    public function submitToSchoolForSigning(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Phương thức không được hỗ trợ.'], 405);
            return;
        }

        // Kiểm tra quyền RBAC: Nhân viên hành chính, Trưởng HC, Admin
        $permissions = RolePermissionHelper::getPermissions($this->currentUser);
        if (!$permissions['canSubmitSchoolSigning']) {
            $this->json(['success' => false, 'message' => 'Bạn không có quyền ký duyệt. Quyền này dành cho Cán bộ Hành chính và Ban Giám hiệu Nhà trường.'], 403);
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

        $signerName = $this->currentUser['full_name'] ?? 'Phòng Hành Chính FPT';
        $this->logActivity(
            'APPROVE_PAYMENT',
            'shift_assignment',
            $assignmentId,
            "Cán bộ {$signerName} đã ký duyệt hợp đồng nghiệm thu và phê duyệt chuyển khoản thù lao thành công"
        );

        $this->json([
            'success' => true,
            'message' => 'Hồ sơ nghiệm thu đã được Nhà trường ký duyệt và xác nhận chuyển khoản thù lao thành công!'
        ]);
    }

    /**
     * API Giảng viên gửi email nhắc nhở CTV nộp minh chứng nghiệm thu sau sự kiện
     */
    public function remindProofSubmission(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Phương thức không được hỗ trợ.'], 405);
            return;
        }

        // Kiểm tra quyền RBAC: Giảng viên / Ban tổ chức hoặc Admin
        $permissions = RolePermissionHelper::getPermissions($this->currentUser);
        if (empty($permissions['canVerifyLecturer']) && empty($permissions['canApproveAccounting'])) {
            $this->json(['success' => false, 'message' => 'Bạn không có quyền gửi email nhắc nhở. Quyền này thuộc về Giảng viên / Ban tổ chức sự kiện.'], 403);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $assignmentId = (int)($input['assignment_id'] ?? 0);

        if ($assignmentId <= 0) {
            $this->json(['success' => false, 'message' => 'Hồ sơ ca trực không hợp lệ.'], 400);
            return;
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT sa.id as assignment_id, sa.proof_image_url,
                                     u.id as user_id, u.student_code, u.full_name, u.email,
                                     s.name as shift_name, s.start_time,
                                     e.title as event_title
                              FROM shift_assignments sa
                              INNER JOIN users u ON sa.user_id = u.id
                              INNER JOIN shifts s ON sa.shift_id = s.id
                              INNER JOIN events e ON s.event_id = e.id
                              WHERE sa.id = :id");
        $stmt->execute([':id' => $assignmentId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            $this->json(['success' => false, 'message' => 'Không tìm thấy thông tin ca trực của sinh viên.'], 404);
            return;
        }

        $studentName = $row['full_name'];
        $studentEmail = $row['email'] ?: ($row['student_code'] . '@fpt.edu.vn');
        $shiftName = $row['shift_name'];
        $eventTitle = $row['event_title'];
        $lecturerName = $this->currentUser['full_name'] ?? 'Giảng viên phụ trách';
        $lecturerRole = $this->currentUser['role_name'] ?? 'Organizer';

        // Ghi nhật ký vào activity_logs (Audit Trail)
        try {
            $stmtLog = $db->prepare("INSERT INTO activity_logs (user_id, user_name, user_role, action, target_type, target_id, details, ip_address, created_at)
                                     VALUES (:user_id, :user_name, :user_role, 'REMIND_PROOF', 'ASSIGNMENT', :target_id, :details, :ip_address, datetime('now', 'localtime'))");
            $stmtLog->execute([
                ':user_id' => $this->currentUser['id'] ?? 1,
                ':user_name' => $lecturerName,
                ':user_role' => $lecturerRole,
                ':target_id' => $assignmentId,
                ':details' => "Giảng viên {$lecturerName} đã gửi email tự động nhắc nhở CTV {$studentName} ({$studentEmail}) nộp minh chứng ca {$shiftName} - Sự kiện: {$eventTitle}",
                ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
            ]);
        } catch (\Throwable $e) {
            // Ignore logging exception
        }

        $this->json([
            'success' => true,
            'message' => "Đã gửi email nhắc nhở nộp minh chứng đến sinh viên {$studentName} ({$studentEmail}) thành công!",
            'student_name' => $studentName,
            'student_email' => $studentEmail,
            'shift_name' => $shiftName,
            'event_title' => $eventTitle
        ]);
    }

    /**
     * Thêm ghi chú / lịch hẹn mới vào CSDL
     */
    public function addNote(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Yêu cầu không hợp lệ'], 400);
            return;
        }

        // Kiểm tra quyền RBAC: CTV KHÔNG có quyền thêm ghi chú
        $permissions = RolePermissionHelper::getPermissions($this->currentUser);
        if (empty($permissions['canCreateNote'])) {
            $this->json(['success' => false, 'message' => 'Cộng tác viên (CTV) không có quyền thêm ghi chú / lịch hẹn. Chức năng chỉ dành cho Ban tổ chức và Giảng viên.'], 403);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $title = trim($input['title'] ?? '');
        $date = trim($input['date'] ?? '');
        $content = trim($input['content'] ?? '') ?: $title;
        $type = trim($input['type'] ?? 'EVENT_REMINDER');
        $color = trim($input['color'] ?? '#EA580C');
        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
            $color = '#EA580C';
        }
        $author = $this->currentUser['full_name'] ?? 'BTC';

        if (!$title || !$date) {
            $this->json(['success' => false, 'message' => 'Vui lòng nhập đầy đủ tiêu đề và ngày áp dụng'], 422);
            return;
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO calendar_notes (date, title, content, type, author, color) VALUES (:date, :title, :content, :type, :author, :color)");
        $stmt->execute([
            ':date' => $date,
            ':title' => $title,
            ':content' => $content,
            ':type' => $type,
            ':author' => $author,
            ':color' => $color
        ]);

        $newId = (int)$db->lastInsertId();

        $this->json([
            'success' => true,
            'message' => 'Đã lưu lịch hẹn / ghi chú thành công!',
            'note' => [
                'id' => $newId,
                'date' => $date,
                'target_date' => $date,
                'title' => $title,
                'content' => $content,
                'type' => $type,
                'note_type' => $type,
                'author' => $author,
                'creator_name' => $author,
                'color' => $color
            ]
        ]);
    }

    /**
     * Xóa lịch hẹn / ghi chú theo ID
     */
    public function deleteNote(int|string $id): void
    {
        $permissions = RolePermissionHelper::getPermissions($this->currentUser);
        if (empty($permissions['canCreateNote'])) {
            $this->json(['success' => false, 'message' => 'Cộng tác viên (CTV) không có quyền xóa ghi chú / lịch hẹn.'], 403);
            return;
        }

        $noteId = (int)$id;
        if ($noteId <= 0) {
            $this->json(['success' => false, 'message' => 'ID lịch hẹn không hợp lệ'], 400);
            return;
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM calendar_notes WHERE id = :id");
        $stmt->execute([':id' => $noteId]);

        $this->json([
            'success' => true,
            'message' => 'Đã xóa lịch hẹn thành công khỏi hệ thống!'
        ]);
    }

    /**
     * Cập nhật thông tin tài khoản cá nhân theo từng vai trò
     */
    public function updateProfile(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Phương thức không hợp lệ'], 405);
            return;
        }

        if (!$this->currentUser) {
            $this->json(['success' => false, 'message' => 'Vui lòng đăng nhập để cập nhật hồ sơ'], 401);
            return;
        }

        $userId = (int)($this->currentUser['id'] ?? 0);
        $roleName = $this->currentUser['role_name'] ?? '';
        $roleId = (int)($this->currentUser['role_id'] ?? 0);
        
        // CHỈ CTV (Sinh viên, Media, CTV) mới có hồ sơ định danh nhận thù lao (VNeID, CCCD, STK)
        // Các role khác (Admin, Giảng viên, Hành chính, Kế toán...) bỏ hết các trường này
        $isCtv = in_array($roleId, [4, 6], true) || in_array($roleName, ['Student', 'Media', 'CTV', 'Sinh viên'], true);

        $fullName = trim($_POST['full_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $skills = trim($_POST['skills'] ?? '');
        $newPassword = $_POST['new_password'] ?? '';

        if (empty($fullName)) {
            $this->json(['success' => false, 'message' => 'Họ và tên không được để trống'], 422);
            return;
        }

        $db = Database::getInstance()->getConnection();

        $params = [
            ':id' => $userId,
            ':full_name' => $fullName,
            ':phone' => $phone,
            ':skills' => $skills
        ];

        $setSql = "full_name = :full_name, phone = :phone, skills = :skills";

        // CHỈ CTV (Sinh viên, Media) mới cập nhật CCCD, ngân hàng, STK, VNeID
        if ($isCtv) {
            $idCardNumber = trim($_POST['id_card_number'] ?? '');
            $bankName = trim($_POST['bank_name'] ?? '');
            $bankAccountNumber = trim($_POST['bank_account_number'] ?? '');
            $bankAccountName = trim($_POST['bank_account_name'] ?? '');
            $vneidImageUrl = trim($_POST['vneid_image_url'] ?? '');

            $setSql .= ", id_card_number = :id_card_number, bank_name = :bank_name, bank_account_number = :bank_account_number, bank_account_name = :bank_account_name, vneid_image_url = :vneid_image_url";
            $params[':id_card_number'] = $idCardNumber;
            $params[':bank_name'] = $bankName;
            $params[':bank_account_number'] = $bankAccountNumber;
            $params[':bank_account_name'] = $bankAccountName;
            $params[':vneid_image_url'] = $vneidImageUrl;
        }

        if (!empty($newPassword)) {
            if (strlen($newPassword) < 6) {
                $this->json(['success' => false, 'message' => 'Mật khẩu mới phải từ 6 ký tự trở lên'], 422);
                return;
            }
            $setSql .= ", password_hash = :password_hash";
            $params[':password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $sql = "UPDATE users SET {$setSql} WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        // Làm mới thông tin session
        $stmtRefresh = $db->prepare("SELECT u.*, r.name as role_name, r.description as role_desc FROM users u INNER JOIN roles r ON u.role_id = r.id WHERE u.id = :id");
        $stmtRefresh->execute([':id' => $userId]);
        $refreshed = $stmtRefresh->fetch(PDO::FETCH_ASSOC);
        if ($refreshed) {
            unset($refreshed['password_hash']);
            $_SESSION['current_user'] = $refreshed;
            $this->currentUser = $refreshed;
        }

        $this->logActivity('UPDATE_PROFILE', 'USER', $userId, "Người dùng [{$fullName}] đã cập nhật thông tin hồ sơ tài khoản cá nhân.");

        $this->json([
            'success' => true,
            'message' => 'Cập nhật thông tin tài khoản cá nhân thành công!',
            'user' => $refreshed
        ]);
    }

    /**
     * Cấp quyền người dùng (Dành riêng cho Quản trị viên hệ thống)
     */
    public function updateUserRole(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Phương thức không hợp lệ'], 405);
            return;
        }

        // Kiểm tra quyền RBAC: CHỈ Admin
        $permissions = RolePermissionHelper::getPermissions($this->currentUser);
        if (!$permissions['canManageRoles']) {
            $this->json(['success' => false, 'message' => 'Bạn không có quyền cấp và quản lý vai trò người dùng. Chỉ Quản trị viên hệ thống (Admin) mới có quyền!'], 403);
            return;
        }

        $targetUserId = (int)($_POST['user_id'] ?? 0);
        $newRoleId = (int)($_POST['role_id'] ?? 0);

        if ($targetUserId <= 0 || $newRoleId <= 0) {
            $this->json(['success' => false, 'message' => 'Dữ liệu phân quyền không hợp lệ'], 422);
            return;
        }

        $db = Database::getInstance()->getConnection();

        // Kiểm tra vai trò đích
        $stmtRole = $db->prepare("SELECT * FROM roles WHERE id = :id");
        $stmtRole->execute([':id' => $newRoleId]);
        $targetRole = $stmtRole->fetch(PDO::FETCH_ASSOC);
        if (!$targetRole) {
            $this->json(['success' => false, 'message' => 'Vai trò được chọn không tồn tại trên hệ thống'], 404);
            return;
        }

        // Kiểm tra người dùng đích
        $stmtUser = $db->prepare("SELECT * FROM users WHERE id = :id");
        $stmtUser->execute([':id' => $targetUserId]);
        $targetUser = $stmtUser->fetch(PDO::FETCH_ASSOC);
        if (!$targetUser) {
            $this->json(['success' => false, 'message' => 'Người dùng không tồn tại'], 404);
            return;
        }

        // Cập nhật vai trò
        $stmtUpdate = $db->prepare("UPDATE users SET role_id = :role_id WHERE id = :id");
        $stmtUpdate->execute([
            ':role_id' => $newRoleId,
            ':id' => $targetUserId
        ]);

        $adminName = $this->currentUser['full_name'] ?? 'Admin';
        $details = "Quản trị viên [{$adminName}] đã cấp quyền mới [{$targetRole['name']} - {$targetRole['description']}] cho tài khoản [{$targetUser['full_name']}] (MSSV/Mã: {$targetUser['student_code']}).";
        
        $this->logActivity('GRANT_ROLE', 'USER', $targetUserId, $details);

        $this->json([
            'success' => true,
            'message' => "Đã cấp quyền '{$targetRole['name']}' thành công cho người dùng {$targetUser['full_name']}!",
            'role_name' => $targetRole['name'],
            'role_desc' => $targetRole['description']
        ]);
    }

    /**
     * API Tạo tài khoản người dùng mới (Dành cho Quản trị viên Admin)
     */
    public function createUser(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Phương thức không hợp lệ'], 405);
            return;
        }

        $permissions = RolePermissionHelper::getPermissions($this->currentUser);
        if (!$permissions['canManageRoles']) {
            $this->json(['success' => false, 'message' => 'Bạn không có quyền tạo tài khoản mới. Chỉ Quản trị viên (Admin) mới có quyền!'], 403);
            return;
        }

        $fullName = trim($_POST['full_name'] ?? '');
        $studentCode = strtoupper(trim($_POST['student_code'] ?? ''));
        $email = strtolower(trim($_POST['email'] ?? ''));
        $roleId = (int)($_POST['role_id'] ?? 4);
        $phone = trim($_POST['phone'] ?? '');
        $department = trim($_POST['department'] ?? 'Công nghệ Thông tin');
        $skills = trim($_POST['skills'] ?? 'Cộng tác viên hỗ trợ sự kiện');
        $rawPassword = $_POST['password'] ?? '123456';

        if (empty($fullName) || empty($studentCode) || empty($email) || $roleId <= 0) {
            $this->json(['success' => false, 'message' => 'Vui lòng điền đầy đủ: Họ tên, Mã số (MSSV), Email và Vai trò.'], 422);
            return;
        }

        $db = Database::getInstance()->getConnection();

        // Kiểm tra trùng lặp email hoặc mã số
        $stmtCheck = $db->prepare("SELECT id FROM users WHERE student_code = :code OR email = :email");
        $stmtCheck->execute([':code' => $studentCode, ':email' => $email]);
        if ($stmtCheck->fetch()) {
            $this->json(['success' => false, 'message' => 'Mã sinh viên/cán bộ hoặc Email này đã tồn tại trong hệ thống!'], 409);
            return;
        }

        $passwordHash = password_hash($rawPassword, PASSWORD_DEFAULT);

        $stmtInsert = $db->prepare("INSERT INTO users (student_code, full_name, email, password_hash, phone, role_id, department, skills, is_active, created_at)
                                     VALUES (:student_code, :full_name, :email, :password_hash, :phone, :role_id, :department, :skills, 1, datetime('now', 'localtime'))");
        $stmtInsert->execute([
            ':student_code' => $studentCode,
            ':full_name' => $fullName,
            ':email' => $email,
            ':password_hash' => $passwordHash,
            ':phone' => $phone,
            ':role_id' => $roleId,
            ':department' => $department,
            ':skills' => $skills
        ]);

        $newUserId = (int)$db->lastInsertId();

        $stmtRole = $db->prepare("SELECT name FROM roles WHERE id = :id");
        $stmtRole->execute([':id' => $roleId]);
        $roleName = $stmtRole->fetchColumn() ?: 'Thành viên';

        $this->logActivity('CREATE_USER', 'USER', $newUserId, "Admin [{$this->currentUser['full_name']}] tạo tài khoản mới [{$fullName}] ({$studentCode}) với vai trò [{$roleName}].");

        $this->json([
            'success' => true,
            'message' => "Tạo tài khoản {$fullName} ({$studentCode}) thành công!",
            'user_id' => $newUserId,
            'user' => [
                'id' => $newUserId,
                'full_name' => $fullName,
                'student_code' => $studentCode,
                'email' => $email,
                'phone' => $phone,
                'role_id' => $roleId,
                'role_name' => $roleName,
                'department' => $department,
                'skills' => $skills,
                'is_active' => 1
            ]
        ]);
    }

    /**
     * API Khóa / Kích hoạt tài khoản người dùng (Dành cho Quản trị viên Admin)
     */
    public function toggleUserStatus(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Phương thức không hợp lệ'], 405);
            return;
        }

        $permissions = RolePermissionHelper::getPermissions($this->currentUser);
        if (!$permissions['canManageRoles']) {
            $this->json(['success' => false, 'message' => 'Bạn không có quyền quản lý trạng thái tài khoản. Chỉ Quản trị viên (Admin) mới có quyền!'], 403);
            return;
        }

        $userId = (int)($_POST['user_id'] ?? 0);
        $newStatus = (int)($_POST['is_active'] ?? 1);

        if ($userId <= 0) {
            $this->json(['success' => false, 'message' => 'Mã tài khoản không hợp lệ'], 422);
            return;
        }

        if ($userId === (int)($this->currentUser['id'] ?? 0)) {
            $this->json(['success' => false, 'message' => 'Bạn không thể tự khóa tài khoản quản trị của chính mình!'], 400);
            return;
        }

        $db = Database::getInstance()->getConnection();
        $stmtUser = $db->prepare("SELECT full_name, student_code FROM users WHERE id = :id");
        $stmtUser->execute([':id' => $userId]);
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $this->json(['success' => false, 'message' => 'Không tìm thấy người dùng'], 404);
            return;
        }

        $stmtUpdate = $db->prepare("UPDATE users SET is_active = :status WHERE id = :id");
        $stmtUpdate->execute([':status' => $newStatus, ':id' => $userId]);

        $statusText = ($newStatus === 1) ? 'Kích hoạt / Mở khóa' : 'Khóa tài khoản';
        $this->logActivity('TOGGLE_USER_STATUS', 'USER', $userId, "Admin [{$this->currentUser['full_name']}] đã {$statusText} cho tài khoản [{$user['full_name']}] ({$user['student_code']}).");

        $this->json([
            'success' => true,
            'message' => "Đã {$statusText} thành công cho người dùng {$user['full_name']}!",
            'is_active' => $newStatus
        ]);
    }

    /**
     * Ghi nhận lịch sử thao tác hệ thống (Audit Trail)
     */
    private function logActivity(string $action, string $targetType, int|string $targetId, string $details): void
    {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("INSERT INTO activity_logs (user_id, user_name, user_role, action, target_type, target_id, details, ip_address, created_at) VALUES (:user_id, :user_name, :user_role, :action, :target_type, :target_id, :details, :ip_address, datetime('now', 'localtime'))");
            $stmt->execute([
                ':user_id' => $this->currentUser['id'] ?? null,
                ':user_name' => $this->currentUser['full_name'] ?? 'Hệ thống',
                ':user_role' => $this->currentUser['role_name'] ?? 'Guest',
                ':action' => $action,
                ':target_type' => $targetType,
                ':target_id' => (string)$targetId,
                ':details' => $details,
                ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
            ]);
        } catch (\Throwable $e) {
            error_log("Failed to log activity: " . $e->getMessage());
        }
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

    /**
     * API / Action Xóa tài khoản cá nhân (Dành cho CTV / Sinh viên theo yêu cầu)
     */
    public function deleteAccount(): void
    {
        $isAjax = !empty($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) === "xmlhttprequest";
        
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            if ($isAjax) {
                $this->json(["success" => false, "message" => "Phương thức không hợp lệ."], 405);
            } else {
                $this->redirect("/dashboard");
            }
            return;
        }

        $userId = (int)($this->currentUser["id"] ?? 0);
        if ($userId <= 0) {
            if ($isAjax) {
                $this->json(["success" => false, "message" => "Vui lòng đăng nhập để thực hiện."], 401);
            } else {
                $this->redirect("/auth/login");
            }
            return;
        }

        $db = Database::getInstance()->getConnection();

        // 1. Ghi nhật ký kiểm toán hệ thống
        try {
            $stmtLog = $db->prepare("INSERT INTO activity_logs (user_id, user_name, user_role, action, target_type, target_id, details, ip_address, created_at) VALUES (:uid, :uname, :urole, 'DELETE_ACCOUNT', 'USER', :tid, :details, :ip, datetime('now', 'localtime'))");
            $stmtLog->execute([
                ":uid" => $userId,
                ":uname" => $this->currentUser["full_name"] ?? "User",
                ":urole" => $this->currentUser["role_name"] ?? "Student",
                ":tid" => $userId,
                ":details" => "Người dùng tự hủy/xóa tài khoản cá nhân khỏi hệ thống PolyMee",
                ":ip" => $_SERVER["REMOTE_ADDR"] ?? "127.0.0.1"
            ]);
        } catch (Exception $e) {
            // Không chặn tiến trình xóa nếu ghi log lỗi
        }

        // 2. Vô hiệu hóa tài khoản (Soft delete bảo toàn dữ liệu đối soát kế toán)
        $stmtDel = $db->prepare("UPDATE users SET is_active = 0, updated_at = datetime('now', 'localtime') WHERE id = :uid");
        $stmtDel->execute([":uid" => $userId]);

        // 3. Xóa session
        SessionHelper::destroy();

        if ($isAjax) {
            $this->json([
                "success" => true,
                "message" => "Tài khoản của bạn đã được xóa thành công.",
                "redirect" => "/auth/login?deleted=1"
            ]);
        } else {
            SessionHelper::flash("info", "Tài khoản của bạn đã được xóa thành công khỏi hệ thống PolyMee.");
            $this->redirect("/auth/login?deleted=1");
        }
    }

    /**
     * API Xóa Sự Kiện & Toàn Bộ Ca Trực Thuộc Sự Kiện
     * Phân quyền: Cấp bậc từ Giảng viên trở lên (LECTURER, HEAD_ADMIN, ADMIN).
     * Giảng viên: CHỈ được xóa sự kiện do chính mình tạo (e.created_by == currentUser.id).
     * Cấp cao hơn (HEAD_ADMIN, ADMIN): Được xóa MỌI sự kiện.
     */
    public function deleteEvent(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Phương thức không được hỗ trợ.'], 405);
            return;
        }

        if (!$this->currentUser) {
            $this->json(['success' => false, 'message' => 'Vui lòng đăng nhập để thực hiện.'], 401);
            return;
        }

        $permissions = RolePermissionHelper::getPermissions($this->currentUser);
        $role = $permissions['role'];

        if (!in_array($role, ['LECTURER', 'HEAD_ADMIN', 'ADMIN'], true)) {
            $this->json(['success' => false, 'message' => 'Bạn không có quyền xóa sự kiện. Chức năng này chỉ dành cho cấp bậc từ Giảng viên trở lên.'], 403);
            return;
        }

        $eventId = (int)($_POST['event_id'] ?? 0);
        if ($eventId <= 0) {
            $this->json(['success' => false, 'message' => 'Mã sự kiện không hợp lệ.'], 400);
            return;
        }

        $db = Database::getInstance()->getConnection();
        $stmtEvent = $db->prepare("SELECT * FROM events WHERE id = :id");
        $stmtEvent->execute([':id' => $eventId]);
        $event = $stmtEvent->fetch(PDO::FETCH_ASSOC);

        if (!$event) {
            $this->json(['success' => false, 'message' => 'Không tìm thấy sự kiện cần xóa.'], 404);
            return;
        }

        $currentUserId = (int)($this->currentUser['id'] ?? 0);
        $eventCreatorId = (int)($event['created_by'] ?? 0);

        // Giảng viên chỉ xóa được lịch mình đặt
        if ($role === 'LECTURER' && $eventCreatorId !== $currentUserId) {
            $this->json([
                'success' => false, 
                'message' => 'Giảng viên chỉ có quyền xóa lịch sự kiện do chính mình đặt!'
            ], 403);
            return;
        }

        try {
            // 1. Xóa shift_assignments thuộc các ca của event này
            $stmtDelAssign = $db->prepare("DELETE FROM shift_assignments WHERE shift_id IN (SELECT id FROM shifts WHERE event_id = :eid)");
            $stmtDelAssign->execute([':eid' => $eventId]);

            // 2. Xóa các ca trực của event này
            $stmtDelShifts = $db->prepare("DELETE FROM shifts WHERE event_id = :eid");
            $stmtDelShifts->execute([':eid' => $eventId]);

            // 3. Xóa chính event
            $stmtDelEvent = $db->prepare("DELETE FROM events WHERE id = :eid");
            $stmtDelEvent->execute([':eid' => $eventId]);

            // 4. Ghi Audit Trail
            $this->logActivity('DELETE_EVENT', 'EVENT', $eventId, "Người dùng [{$this->currentUser['full_name']}] ({$role}) đã xóa sự kiện [{$event['title']}] (ID: {$eventId}) và các ca trực liên quan.");

            $this->json([
                'success' => true,
                'message' => "Đã xóa sự kiện '{$event['title']}' thành công khỏi hệ thống!",
                'event_id' => $eventId
            ]);
        } catch (\Throwable $e) {
            $this->json(['success' => false, 'message' => 'Lỗi khi xóa sự kiện: ' . $e->getMessage()], 500);
        }
    }

    /**
     * API Xóa Ca Trực Đơn Lẻ
     */
    public function deleteShift(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Phương thức không được hỗ trợ.'], 405);
            return;
        }

        if (!$this->currentUser) {
            $this->json(['success' => false, 'message' => 'Vui lòng đăng nhập để thực hiện.'], 401);
            return;
        }

        $permissions = RolePermissionHelper::getPermissions($this->currentUser);
        $role = $permissions['role'];

        if (!in_array($role, ['LECTURER', 'HEAD_ADMIN', 'ADMIN'], true)) {
            $this->json(['success' => false, 'message' => 'Bạn không có quyền xóa ca trực. Chức năng này chỉ dành cho cấp bậc từ Giảng viên trở lên.'], 403);
            return;
        }

        $shiftId = (int)($_POST['shift_id'] ?? 0);
        if ($shiftId <= 0) {
            $this->json(['success' => false, 'message' => 'Mã ca trực không hợp lệ.'], 400);
            return;
        }

        $db = Database::getInstance()->getConnection();
        $stmtShift = $db->prepare("SELECT s.*, e.created_by as event_creator_id, e.title as event_title FROM shifts s INNER JOIN events e ON s.event_id = e.id WHERE s.id = :id");
        $stmtShift->execute([':id' => $shiftId]);
        $shift = $stmtShift->fetch(PDO::FETCH_ASSOC);

        if (!$shift) {
            $this->json(['success' => false, 'message' => 'Không tìm thấy ca trực cần xóa.'], 404);
            return;
        }

        $currentUserId = (int)($this->currentUser['id'] ?? 0);
        $eventCreatorId = (int)($shift['event_creator_id'] ?? 0);

        if ($role === 'LECTURER' && $eventCreatorId !== $currentUserId) {
            $this->json([
                'success' => false, 
                'message' => 'Giảng viên chỉ có quyền xóa ca trực thuộc lịch sự kiện do chính mình đặt!'
            ], 403);
            return;
        }

        try {
            $stmtDelAssign = $db->prepare("DELETE FROM shift_assignments WHERE shift_id = :sid");
            $stmtDelAssign->execute([':sid' => $shiftId]);

            $stmtDelShift = $db->prepare("DELETE FROM shifts WHERE id = :sid");
            $stmtDelShift->execute([':sid' => $shiftId]);

            $this->logActivity('DELETE_SHIFT', 'SHIFT', $shiftId, "Người dùng [{$this->currentUser['full_name']}] ({$role}) đã xóa ca trực [{$shift['name']}] của sự kiện [{$shift['event_title']}].");

            $this->json([
                'success' => true,
                'message' => "Đã xóa ca trực '{$shift['name']}' thành công!",
                'shift_id' => $shiftId
            ]);
        } catch (\Throwable $e) {
            $this->json(['success' => false, 'message' => 'Lỗi khi xóa ca trực: ' . $e->getMessage()], 500);
        }
    }

    /**
     * API Hủy phân công ca trực CTV (Xác nhận qua Modal)
     */
    public function cancelAssignment(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Phương thức không được hỗ trợ.'], 405);
            return;
        }

        if (!$this->currentUser) {
            $this->json(['success' => false, 'message' => 'Vui lòng đăng nhập để thực hiện.'], 401);
            return;
        }

        $assignmentId = (int)($_POST['assignment_id'] ?? 0);
        if ($assignmentId <= 0) {
            $this->json(['success' => false, 'message' => 'Mã phân công không hợp lệ.'], 400);
            return;
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT sa.*, s.name as shift_name, u.full_name as user_name FROM shift_assignments sa INNER JOIN shifts s ON sa.shift_id = s.id INNER JOIN users u ON sa.user_id = u.id WHERE sa.id = :id");
        $stmt->execute([':id' => $assignmentId]);
        $assignment = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$assignment) {
            $this->json(['success' => false, 'message' => 'Không tìm thấy phân công cần hủy.'], 404);
            return;
        }

        $permissions = RolePermissionHelper::getPermissions($this->currentUser);
        $role = $permissions['role'];
        $currentUserId = (int)($this->currentUser['id'] ?? 0);

        if (!in_array($role, ['ADMIN', 'HEAD_ADMIN', 'LECTURER'], true) && (int)$assignment['user_id'] !== $currentUserId) {
            $this->json(['success' => false, 'message' => 'Bạn không có quyền hủy phân công này.'], 403);
            return;
        }

        try {
            $stmtDel = $db->prepare("DELETE FROM shift_assignments WHERE id = :id");
            $stmtDel->execute([':id' => $assignmentId]);

            // Cập nhật số lượng đã tuyển trong shifts nếu có cột quota_assigned
            try {
                $stmtCount = $db->prepare("SELECT COUNT(*) FROM shift_assignments WHERE shift_id = :sid");
                $stmtCount->execute([':sid' => $assignment['shift_id']]);
                $newCount = (int)$stmtCount->fetchColumn();

                $stmtUpdateShift = $db->prepare("UPDATE shifts SET quota_assigned = :cnt WHERE id = :sid");
                $stmtUpdateShift->execute([':cnt' => $newCount, ':sid' => $assignment['shift_id']]);
            } catch (\Throwable $e2) {
                // column might not exist in sqlite, ignore
            }

            $this->logActivity('CANCEL_ASSIGNMENT', 'SHIFT_ASSIGNMENT', $assignmentId, "Đã hủy phân công ca [{$assignment['shift_name']}] của CTV [{$assignment['user_name']}].");

            $this->json([
                'success' => true,
                'message' => "Đã hủy phân công ca trực thành công!",
                'assignment_id' => $assignmentId
            ]);
        } catch (\Throwable $e) {
            $this->json(['success' => false, 'message' => 'Lỗi khi hủy phân công: ' . $e->getMessage()], 500);
        }
    }
}
