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

$css = file_get_contents('public/assets/css/style.css');

// 1. Kiểm tra không sử dụng zoom hoặc transform: scale() để ép nhỏ toàn màn hình
if (preg_match('/body\s*\{[^}]*zoom\s*:/i', $css) || preg_match('/\.app-shell\s*\{[^}]*zoom\s*:/i', $css)) {
    echo "[FAIL] Found illegal zoom property in CSS\n";
    exit(1);
}
if (preg_match('/body\s*\{[^}]*transform\s*:\s*scale/i', $css) || preg_match('/\.app-shell\s*\{[^}]*transform\s*:\s*scale/i', $css)) {
    echo "[FAIL] Found illegal transform: scale() on body or app-shell in CSS\n";
    exit(1);
}
echo "[PASS] Rule 1: Không sử dụng zoom hoặc transform: scale() để ép nhỏ giao diện\n";

// 2. Kiểm tra các class bố cục Fit-to-Viewport & Compact UI
$required_rules = [
    '.contract-bento-grid',
    '.contract-master-layout',
    '.my-shifts-split-layout',
    '.proof-admin-split-layout',
    '.table-responsive thead th',
    'overflow-y: auto',
    'min-height: 0',
    'min-width: 0'
];

foreach ($required_rules as $rule) {
    if (!str_contains($css, $rule)) {
        echo "[FAIL] Missing required layout rule '$rule' in style.css\n";
        exit(1);
    }
}
echo "[PASS] Rule 2: Các class Master-Detail, Split Layout, và Sticky Header có đầy đủ trong style.css\n";

// 3. Render Dashboard View và kiểm tra các thành phần giao diện
$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.id = 3");
$user = $stmt->fetch(PDO::FETCH_ASSOC);
SessionHelper::setCurrentUser($user);

ob_start();
$c = new DashboardController();
$c->index();
$html = ob_get_clean();

$dashboard_tokens = [
    'dashboard-topbar',
    'main-workspace',
    'spaBodyContent',
    'my-shifts-split-layout',
    'proof-admin-split-layout',
    'contract-master-layout',
    'contract-bento-grid'
];

foreach ($dashboard_tokens as $tok) {
    if (!str_contains($html, $tok)) {
        echo "[FAIL] Missing dashboard token '$tok'\n";
        exit(1);
    }
}
echo "[PASS] Rule 3: Dashboard View render đầy đủ các thành phần Fit-to-Viewport\n";

echo "\n>>> XÁC NHẬN: NGUYÊN TẮC RESPONSIVE LAYOUT + FIT-TO-VIEWPORT + COMPACT UI ĐÃ ĐƯỢC ÁP DỤNG THÀNH CÔNG!\n";
exit(0);
