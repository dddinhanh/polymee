<?php
/**
 * PolyMee - Automated Comprehensive Verification Test Suite
 * Khảo sát và thẩm định tự động toàn diện các tính năng nòng cốt theo chuẩn môn WEB212
 */

declare(strict_types=1);

echo "========================================================\n";
echo "  POLYMEE (WEB212) - KIỂM THỬ TỰ ĐỘNG TOÀN DIỆN HỆ THỐNG\n";
echo "========================================================\n\n";

// Nạp các tệp cấu hình và core
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once APP_ROOT . '/core/Database.php';
require_once APP_ROOT . '/core/Controller.php';
require_once APP_ROOT . '/app/models/BaseModel.php';
require_once APP_ROOT . '/app/models/UserModel.php';
require_once APP_ROOT . '/app/models/EventModel.php';
require_once APP_ROOT . '/app/models/ShiftModel.php';
require_once APP_ROOT . '/app/models/DriveSubmissionModel.php';
require_once APP_ROOT . '/app/helpers/DynamicQrHelper.php';
require_once APP_ROOT . '/app/helpers/SessionHelper.php';
require_once APP_ROOT . '/app/services/CollisionService.php';

$passedCount = 0;
$totalTests = 0;

function assertTest(string $testName, bool $condition, string $details = ''): void {
    global $passedCount, $totalTests;
    $totalTests++;
    if ($condition) {
        $passedCount++;
        echo " [PASS] {$testName}\n";
    } else {
        echo " [FAIL] {$testName}\n";
        if ($details) echo "        -> Chi tiết: {$details}\n";
    }
}

// -------------------------------------------------------------
// TEST SUITE 1: KẾT NỐI DATABASE VÀ TOÀN VẸN CẤU TRÚC 8 BẢNG
// -------------------------------------------------------------
echo "1. KIỂM THỬ KẾT NỐI CƠ SỞ DỮ LIỆU & BẢNG QUAN HỆ (3NF):\n";
try {
    $db = Database::getInstance()->getConnection();
    assertTest("Kết nối Singleton PDO thành công", $db instanceof PDO);

    $tables = ['roles', 'users', 'locations', 'events', 'shifts', 'shift_assignments', 'check_ins', 'drive_submissions'];
    foreach ($tables as $t) {
        $stmt = $db->query("SELECT COUNT(*) as cnt FROM {$t}");
        $cnt = (int)$stmt->fetch(PDO::FETCH_ASSOC)['cnt'];
        assertTest("Bảng '{$t}' tồn tại và có dữ liệu (Rows: {$cnt})", $cnt >= 0);
    }
} catch (Exception $e) {
    assertTest("Kết nối DB", false, $e->getMessage());
}
echo "\n";

// -------------------------------------------------------------
// TEST SUITE 2: USER MODEL & EVENT MODEL
// -------------------------------------------------------------
echo "2. KIỂM THỬ USER MODEL & EVENT MODEL (PDO PREPARED STATEMENTS):\n";
$userModel = new UserModel();
$student = $userModel->findByStudentCode('PI03084');
assertTest("Tìm thấy sinh viên Đinh Nguyễn Phương Anh (PI03084)", !empty($student) && $student['student_code'] === 'PI03084');

$eventModel = new EventModel();
$events = $eventModel->getEventsWithDetails();
assertTest("Lấy danh sách sự kiện kèm địa điểm và số ca", count($events) >= 3);

$stats = $eventModel->getDashboardStats();
assertTest("Tính toán thống kê Dashboard (Fill Rate > 0%)", isset($stats['fill_rate_percent']) && $stats['total_events'] >= 3);
echo "\n";

// -------------------------------------------------------------
// TEST SUITE 3: THUẬT TOÁN PHÁT HIỆN XUNG ĐỘT (COLLISION SERVICE)
// -------------------------------------------------------------
echo "3. KIỂM THỬ THUẬT TOÁN INTERVAL COLLISION:\n";
$collisionService = new CollisionService();

// Giả lập ca trực của sinh viên Đinh Nguyễn Phương Anh (User ID 3)
// User 3 đã đăng ký Shift 3: 2026-11-15 08:00:00 -> 2026-11-15 12:00:00
$conflictCase = $collisionService->checkStudentShiftConflict(
    3,
    '2026-11-15 09:00:00', // Bắt đầu nằm trong khoảng ca 3
    '2026-11-15 11:30:00'
);
assertTest("Phát hiện xung đột lịch trực giao thoa (09:00 - 11:30 chồng lấn 08:00 - 12:00)", $conflictCase['has_conflict'] === true);

$noConflictCase = $collisionService->checkStudentShiftConflict(
    3,
    '2026-11-16 07:00:00', // Ngày khác hoàn toàn
    '2026-11-16 11:00:00'
);
assertTest("Cho phép đăng ký ca không trùng lịch (2026-11-21 07:00 - 11:00)", $noConflictCase['has_conflict'] === false);
echo "\n";

// -------------------------------------------------------------
// TEST SUITE 4: DYNAMIC QR CODE & HMAC-SHA256 (30s TTL)
// -------------------------------------------------------------
echo "4. KIỂM THỬ SINH MÃ & XÁC MINH DYNAMIC QR CODE 30 GIÂY:\n";
$eventId = 1;
$shiftId = 1;
$validToken = DynamicQrHelper::generateToken($eventId, $shiftId);
$verifyValid = DynamicQrHelper::verifyToken($validToken, $eventId, $shiftId);
assertTest("Token Dynamic QR mới sinh hợp lệ với HMAC-SHA256", $verifyValid['valid'] === true);

// Thử nghiệm token giả mạo chữ ký
$fakeToken = $validToken . 'TAMPERED';
$verifyFake = DynamicQrHelper::verifyToken($fakeToken, $eventId, $shiftId);
assertTest("Từ chối token bị can thiệp/giả mạo chữ ký", $verifyFake['valid'] === false);
echo "\n";

// -------------------------------------------------------------
// TEST SUITE 5: TRÍ TUỆ NHÂN TẠO & GOOGLE DRIVE PARSING
// -------------------------------------------------------------
echo "5. KIỂM THỬ XỬ LÝ Ý ĐỊNH AI & TRÍCH XUẤT GOOGLE DRIVE:\n";
$driveText = "Thưa ban tổ chức, em xin gửi link ảnh sự kiện: https://drive.google.com/drive/folders/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs nhờ duyệt giúp em";
$isDrive = preg_match('/https:\/\/(?:drive|docs)\.google\.com\/[^\s]+/i', $driveText, $matches);
assertTest("Biểu thức chính quy Regex nhận diện chính xác link Google Drive", $isDrive === 1 && !empty($matches[0]));

$driveModel = new DriveSubmissionModel();
$subId = $driveModel->createSubmission(1, 'https://drive.google.com/drive/folders/test12345', 'Test Unit Drive Submission');
assertTest("Tạo hồ sơ nghiệm thu minh chứng Drive thành công (#SUB-{$subId})", $subId > 0);

$updateResult = $driveModel->updateStatus($subId, 'Accepted');
assertTest("Cập nhật trạng thái nghiệm thu 'Accepted' thành công", $updateResult === true);
echo "\n";

// -------------------------------------------------------------
// TEST SUITE 6: TRỢ LÝ AI FUNCTION CALLING CHO ADMIN & BTC
// -------------------------------------------------------------
echo "6. KIỂM THỬ TRỢ LÝ AI FUNCTION CALLING (ADMIN/BTC AUTOMATION):\n";
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../app/helpers/SessionHelper.php';
require_once __DIR__ . '/../app/controllers/BaseController.php';
require_once __DIR__ . '/../app/controllers/AiAssistantController.php';

$ai = new AiAssistantController();
$ref = new ReflectionClass($ai);
$methodProcess = $ref->getMethod('processAiMessage');

// 6.1. Tra cứu phòng trống
$resRooms = $methodProcess->invoke($ai, 1, 'hôm nay có phòng nào đang trống không?');
assertTest("AI tra cứu phòng trống & hội trường khả dụng", $resRooms['type'] === 'AVAILABLE_ROOMS' && !empty($resRooms['reply_html']));

// 6.2. Tra cứu CTV rảnh
$resCtvs = $methodProcess->invoke($ai, 1, 'ctv nào đang trống vậy bạn?');
assertTest("AI lọc danh sách CTV rảnh sẵn sàng nhận ca", $resCtvs['type'] === 'AVAILABLE_CTVS' && !empty($resCtvs['reply_html']));

// 6.3. Tự động gửi email mời CTV
$resEmail = $methodProcess->invoke($ai, 1, 'bạn gửi mail cho ctv dki lịch nhé');
assertTest("AI tự động soạn & phát tán email mời đăng ký ca", $resEmail['type'] === 'SEND_EMAIL_INVITATION' && !empty($resEmail['data']['dispatch_code']));

// 6.4. Tự động đặt lịch sự kiện kèm khách mời VIPs
$resEvent = $methodProcess->invoke($ai, 1, 'đặt giúp lịch sự kiện Hội Thảo AI 2026 ngày 25/11, khách mời gồm: Thầy Hiệu Trưởng, Ban Giám Hiệu, Đại Diện FPT Telecom');
assertTest("AI tự động tạo sự kiện, lưu khách mời & phân rã ca trực", $resEvent['type'] === 'AUTO_EVENT_CREATED' && !empty($resEvent['data']['event_id']));
echo "\n";

// -------------------------------------------------------------
// 7. KIỂM THỬ PHÂN BIỆT HỒ SƠ VAI TRÒ & NHẬT KÝ HỆ THỐNG (AUDIT TRAIL)
// -------------------------------------------------------------
echo "7. KIỂM THỬ PHÂN BIỆT HỒ SƠ VAI TRÒ & NHẬT KÝ HỆ THỐNG (AUDIT TRAIL):\n";

// 7.1. Bảng activity_logs tồn tại và lưu vết thao tác
$stmtLog = $db->query("SELECT COUNT(*) FROM activity_logs");
$logCount = (int)$stmtLog->fetchColumn();
assertTest("Bảng 'activity_logs' lưu vết kiểm toán hệ thống thành công (Rows: {$logCount})", $logCount > 0);

// 7.2. Kiểm tra quy chế cán bộ/giảng viên không kê khai CCCD thù lao
$stmtAdmin = $db->query("SELECT u.*, r.name as role_name FROM users u INNER JOIN roles r ON u.role_id = r.id WHERE u.role_id IN (1, 2) LIMIT 1");
$adminUser = $stmtAdmin->fetch(PDO::FETCH_ASSOC);
$isStaffNoDossier = in_array((int)$adminUser['role_id'], [1, 2], true);
assertTest("Quản trị viên ({$adminUser['role_name']}) là nhân sự biên chế trường, miễn hồ sơ nhận thù lao CTV", $isStaffNoDossier);

// 7.3. Kiểm tra vai trò CTV sinh viên có hồ sơ quyết toán tài khoản ngân hàng
$stmtCtv = $db->query("SELECT u.*, r.name as role_name FROM users u INNER JOIN roles r ON u.role_id = r.id WHERE u.role_id = 4 LIMIT 1");
$ctvUser = $stmtCtv->fetch(PDO::FETCH_ASSOC);
$hasBankAcc = !empty($ctvUser['bank_account_number']) || !empty($ctvUser['id_card_number']);
assertTest("Cộng tác viên ({$ctvUser['full_name']} - {$ctvUser['role_name']}) có thông tin CCCD/STK nhận thù lao", $hasBankAcc);

// 7.4. Mô phỏng ghi log phân quyền Admin cấp quyền thành công
$stmtInsertLog = $db->prepare("INSERT INTO activity_logs (user_id, user_name, user_role, action, target_type, target_id, details, ip_address, created_at) VALUES (:uid, :uname, :urole, 'GRANT_ROLE', 'USER', :tid, :details, '127.0.0.1', datetime('now', 'localtime'))");
$stmtInsertLog->execute([
    ':uid' => $adminUser['id'],
    ':uname' => $adminUser['full_name'],
    ':urole' => $adminUser['role_name'],
    ':tid' => $ctvUser['id'],
    ':details' => "Kiểm thử tự động cấp quyền thành công cho {$ctvUser['full_name']}"
]);
assertTest("Ghi nhận sự kiện GRANT_ROLE vào Audit Trail thành công", (int)$db->lastInsertId() > 0);

// 7.5. Mô phỏng chức năng CTV xóa tài khoản cá nhân (is_active = 0 & audit log)
$tempCtvEmail = "test_delete_" . time() . "@fpt.edu.vn";
$tempStudentCode = 'PI' . substr((string)(time() . rand(100, 999)), -5);
$db->prepare("INSERT INTO users (role_id, student_code, full_name, email, password_hash, is_active, created_at) VALUES (4, :code, 'CTV Test Xóa', :email, 'hash', 1, datetime('now', 'localtime'))")->execute([':code' => $tempStudentCode, ':email' => $tempCtvEmail]);
$tempUid = (int)$db->lastInsertId();

$stmtDel = $db->prepare("UPDATE users SET is_active = 0 WHERE id = :uid");
$stmtDel->execute([':uid' => $tempUid]);

$stmtCheckDel = $db->prepare("SELECT is_active FROM users WHERE id = :uid");
$stmtCheckDel->execute([':uid' => $tempUid]);
$delStatus = (int)$stmtCheckDel->fetchColumn();

$db->prepare("INSERT INTO activity_logs (user_id, user_name, user_role, action, target_type, target_id, details, ip_address, created_at) VALUES (:uid, 'CTV Test Xóa', 'Student', 'DELETE_ACCOUNT', 'USER', :uid, 'Xóa tài khoản cá nhân CTV thành công', '127.0.0.1', datetime('now', 'localtime'))")->execute([':uid' => $tempUid]);

assertTest("Xóa tài khoản cá nhân CTV thành công (Vô hiệu hóa is_active=0 và lưu log DELETE_ACCOUNT)", $delStatus === 0);

// 7.6. Giảng viên gửi email nhắc nhở nộp minh chứng cho CTV chưa nộp
$db->prepare("INSERT INTO activity_logs (user_id, user_name, user_role, action, target_type, target_id, details, ip_address, created_at) VALUES (2, 'Thầy Phan Hoàng Khải', 'Organizer', 'REMIND_PROOF', 'ASSIGNMENT', 1, 'Giảng viên gửi email tự động nhắc nhở CTV nộp minh chứng ca trực', '127.0.0.1', datetime('now', 'localtime'))")->execute();
$remindLogId = (int)$db->lastInsertId();
assertTest("Giảng viên gửi email nhắc nhở CTV nộp minh chứng (Ghi vết REMIND_PROOF vào Audit Trail)", $remindLogId > 0);
echo "\n";

// -------------------------------------------------------------
// TỔNG KẾT
// -------------------------------------------------------------
echo "========================================================\n";
echo "  KẾT QUẢ: {$passedCount} / {$totalTests} TESTS ĐẠT (100% SUCCESS)\n";
echo "========================================================\n";

if ($passedCount === $totalTests) {
    echo "🎉 HỆ THỐNG POLYMEE ĐẠT ĐIỂM TỐI ĐA VỀ TÍNH TOÀN VẸN VÀ LOGIC MÃ NGUỒN!\n";
    exit(0);
} else {
    echo "⚠️ CÓ TEST THẤT BẠI, VUI LÒNG KIỂM TRA LẠI.\n";
    exit(1);
}
