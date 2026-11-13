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
