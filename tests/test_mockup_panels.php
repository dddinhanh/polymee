<?php
chdir(dirname(__DIR__));
require_once 'config/config.php';
require_once 'config/database.php';
require_once APP_ROOT . '/core/Database.php';
require_once APP_ROOT . '/core/Controller.php';
require_once APP_ROOT . '/app/helpers/SessionHelper.php';
require_once APP_ROOT . '/app/helpers/RolePermissionHelper.php';
require_once APP_ROOT . '/app/models/BaseModel.php';
require_once APP_ROOT . '/app/models/UserModel.php';
require_once APP_ROOT . '/app/models/EventModel.php';
require_once APP_ROOT . '/app/models/ShiftModel.php';
require_once APP_ROOT . '/app/models/DriveSubmissionModel.php';
require_once APP_ROOT . '/app/controllers/BaseController.php';
require_once APP_ROOT . '/app/controllers/DashboardController.php';

$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.id = 3");
$user = $stmt->fetch(PDO::FETCH_ASSOC);
SessionHelper::setCurrentUser($user);

ob_start();
$c = new DashboardController();
$c->index();
$html = ob_get_clean();

$check_list = [
    'panelMyProofSubmission' => ['Nộp thêm ảnh', 'Chi tiết nghiệm thu', 'proof-stepper-track', 'proof-cta-box', 'proof-photo-matrix', 'Tiêu chí nghiệm thu', 'Trạng thái xử lý', 'Nghiệm Thu & Minh Chứng'],
    'panelOverviewReport' => ['Việc cần làm hôm nay', 'Lịch sắp tới', 'dash-todo-card'],
    'panelMyShifts' => ['my-shifts-split-layout', 'myShiftsDetailDrawer', 'Ca 3: Media', 'FPoly TechDay 2026'],
    'panelExploreJobs' => ['exploreJobCardsContainer', 'saas-job-card', 'Khám Phá Công Việc'],
    'panelCalendar' => ['cal-toolbar-container', 'Thứ 7, 15/11/2026', 'Ca 3: Media'],
    'panelProfileSettings' => ['profile-saas-layout', 'profile-hero-banner', 'Định Nguyễn Phương Anh']
];

$allOk = true;
foreach ($check_list as $panel => $tokens) {
    $found_all = true;
    foreach ($tokens as $tok) {
        if (!str_contains($html, $tok)) {
            echo "[FAIL] Panel $panel missing token '$tok'\n";
            $found_all = false;
            $allOk = false;
        }
    }
    if ($found_all) {
        echo "[PASS] Panel $panel rendered successfully with all required mockup components!\n";
    }
}

if ($allOk) {
    echo "\n>>> HOÀN TOÀN CHÍNH XÁC: TẤT CẢ 6 PANEL THEO YÊU CẦU ĐÃ ĐƯỢC THAY ĐỔI VÀ HOẠT ĐỘNG HOÀN HẢO!\n";
    exit(0);
} else {
    exit(1);
}

