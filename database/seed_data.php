<?php
/**
 * PolyMee - Database Seeder
 * Tự động khởi tạo cấu trúc bảng và nạp dữ liệu mẫu ban đầu
 * Hỗ trợ tự động chạy cả SQLite và MySQL
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/core/Database.php';

echo "=== POLYMEE DATABASE SEEDER ===\n";
echo "Đang kết nối cơ sở dữ liệu: " . DB_CONNECTION . "\n";

$db = Database::getInstance()->getConnection();

// 1. Tạo bảng (tương thích SQLite và MySQL)
$statements = [
    // Roles
    "CREATE TABLE IF NOT EXISTS roles (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(50) NOT NULL UNIQUE,
        description VARCHAR(255) NULL
    );",

    // Users
    "CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        student_code VARCHAR(20) NOT NULL UNIQUE,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        phone VARCHAR(15) NULL,
        role_id INTEGER NOT NULL,
        skills TEXT NULL,
        is_active INTEGER DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (role_id) REFERENCES roles(id)
    );",

    // Locations
    "CREATE TABLE IF NOT EXISTS locations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(100) NOT NULL,
        address VARCHAR(255) NOT NULL,
        capacity INTEGER NOT NULL DEFAULT 50,
        status VARCHAR(20) DEFAULT 'Available'
    );",

    // Events
    "CREATE TABLE IF NOT EXISTS events (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title VARCHAR(200) NOT NULL,
        description TEXT NULL,
        location_id INTEGER NOT NULL,
        start_time DATETIME NOT NULL,
        end_time DATETIME NOT NULL,
        status VARCHAR(20) DEFAULT 'Published',
        created_by INTEGER NOT NULL,
        banner_url VARCHAR(255) NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (location_id) REFERENCES locations(id),
        FOREIGN KEY (created_by) REFERENCES users(id)
    );",

    // Shifts
    "CREATE TABLE IF NOT EXISTS shifts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        event_id INTEGER NOT NULL,
        name VARCHAR(100) NOT NULL,
        start_time DATETIME NOT NULL,
        end_time DATETIME NOT NULL,
        target_qty INTEGER NOT NULL DEFAULT 5,
        approved_qty INTEGER NOT NULL DEFAULT 0,
        skill_required VARCHAR(100) NULL,
        points INTEGER NOT NULL DEFAULT 10,
        FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
    );",

    // Shift Assignments
    "CREATE TABLE IF NOT EXISTS shift_assignments (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        shift_id INTEGER NOT NULL,
        user_id INTEGER NOT NULL,
        status VARCHAR(20) DEFAULT 'Approved',
        assigned_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (shift_id) REFERENCES shifts(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        UNIQUE (shift_id, user_id)
    );",

    // Check-ins
    "CREATE TABLE IF NOT EXISTS check_ins (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        assignment_id INTEGER NOT NULL,
        check_in_time DATETIME DEFAULT CURRENT_TIMESTAMP,
        check_out_time DATETIME NULL,
        method VARCHAR(20) DEFAULT 'DynamicQR',
        status VARCHAR(20) DEFAULT 'Present',
        FOREIGN KEY (assignment_id) REFERENCES shift_assignments(id) ON DELETE CASCADE
    );",

    // Drive Submissions
    "CREATE TABLE IF NOT EXISTS drive_submissions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        assignment_id INTEGER NOT NULL,
        drive_url VARCHAR(500) NOT NULL,
        summary TEXT NULL,
        status VARCHAR(20) DEFAULT 'Pending',
        submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        reviewed_at DATETIME NULL,
        FOREIGN KEY (assignment_id) REFERENCES shift_assignments(id) ON DELETE CASCADE
    );"
];

// Nếu là MySQL, chỉnh sửa AUTOINCREMENT thành AUTO_INCREMENT
if (DB_CONNECTION === 'mysql') {
    foreach ($statements as &$stmt) {
        $stmt = str_replace('INTEGER PRIMARY KEY AUTOINCREMENT', 'INT AUTO_INCREMENT PRIMARY KEY', $stmt);
    }
}

foreach ($statements as $sql) {
    $db->exec($sql);
}
echo "✓ Đã khởi tạo cấu trúc 8 bảng dữ liệu thành công.\n";

// 2. Kiểm tra nếu đã có dữ liệu thì không seed lại
$stmt = $db->query("SELECT COUNT(*) as cnt FROM users");
$row = $stmt->fetch();
if ((int)($row['cnt'] ?? 0) > 0) {
    echo "✓ Dữ liệu mẫu đã tồn tại sẵn, bỏ qua bước nạp lại.\n";
    return;
}

// 3. Nạp Roles (Chuẩn hóa 7 vai trò theo BA Report v1.0 & Usecase specifications)
$roles = [
    [1, 'Admin', 'Quản trị viên toàn quyền hệ thống'],
    [2, 'Organizer', 'Giảng viên và Ban tổ chức sự kiện'],
    [3, 'Staff', 'Cán bộ giám sát và điều phối hiện trường'],
    [4, 'Student', 'Sinh viên FPoly / Cộng tác viên sự kiện'],
    [5, 'Accountant', 'Kế toán & Quyết toán thù lao CTV'],
    [6, 'Media', 'Cộng tác viên Media & Nhiếp ảnh bàn giao Drive'],
    [7, 'Head_Admin', 'Trưởng hành chính & Ban giám hiệu phê duyệt']
];
$stmt = $db->prepare("INSERT INTO roles (id, name, description) VALUES (?, ?, ?)");
foreach ($roles as $r) {
    $stmt->execute($r);
}
echo "✓ Đã nạp 7 vai trò (Roles) chuẩn BA.\n";

// 4. Nạp Users
$passHash = password_hash('123456', PASSWORD_BCRYPT);
$users = [
    [1, 'ADMIN001', 'Quản Trị Viên Hệ Thống', 'admin@fpoly.edu.vn', $passHash, '0901234567', 1, 'Quản trị, Phân quyền'],
    [2, 'GV0102', 'Thầy Phan Hoàng Khải', 'khai.phan@fpt.edu.vn', $passHash, '0912345678', 2, 'Tổ chức sự kiện, Đào tạo'],
    [3, 'PI03084', 'Đinh Nguyễn Phương Anh', 'phuonganh.pi03084@fpt.edu.vn', $passHash, '0923456789', 6, 'Media, Chụp ảnh sự kiện, Quay video recap'],
    [4, 'PI03067', 'Lê Đức Mạnh', 'ducmanh.pi03067@fpt.edu.vn', $passHash, '0934567890', 4, 'Lễ tân, Đón tiếp đại biểu, Setup'],
    [5, 'PI03015', 'Hoàng Minh Luận', 'minhluan.pi03015@fpt.edu.vn', $passHash, '0945678901', 4, 'Kỹ thuật âm thanh ánh sáng, Sân khấu'],
    [6, 'PI02895', 'Dương Ngọc Hân', 'ngochan.pi02895@fpt.edu.vn', $passHash, '0956789012', 4, 'Hậu cần, Check-in, Hỗ trợ điều phối'],
    [7, 'KT0105', 'Đặng Thu Thảo', 'thao.dt@fpt.edu.vn', $passHash, '0967890123', 5, 'Kế toán, Thẩm định VNeID & Quyết toán chi trả'],
    [8, 'HC0201', 'Lê Đình Tuấn', 'tuan.ld@fpt.edu.vn', $passHash, '0978901234', 3, 'Hành chính, CSVC, Rà soát xung đột'],
    [13, 'HC0001', 'ThS. Phạm Hồng Sơn', 'son.ph@fpt.edu.vn', $passHash, '0908889999', 7, 'Trưởng hành chính, Phê duyệt cuối, Giám sát vận hành']
];
$stmt = $db->prepare("INSERT INTO users (id, student_code, full_name, email, password_hash, phone, role_id, skills) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($users as $u) {
    $stmt->execute($u);
}
echo "✓ Đã nạp 9 tài khoản người dùng mẫu đại diện 7 vai trò.\n";

// 5. Nạp Locations
$locations = [
    [1, 'Hội trường Trịnh Văn Bô (Tòa T)', 'Cơ sở Công viên phần mềm Quang Trung, Q.12, TP.HCM', 500, 'Available'],
    [2, 'Phòng Hội thảo Quốc tế (Phòng 301)', 'Tầng 3, Tòa nhà Innovation, Q.12, TP.HCM', 120, 'Available'],
    [3, 'Sân khấu Ngoài trời Central Yard', 'Khuôn viên trung tâm FPT Polytechnic', 800, 'Available'],
    [4, 'Phòng Lab Chuyên đề AI (Phòng 405)', 'Tầng 4, Tòa F, Q.12, TP.HCM', 60, 'Available']
];
$stmt = $db->prepare("INSERT INTO locations (id, name, address, capacity, status) VALUES (?, ?, ?, ?, ?)");
foreach ($locations as $l) {
    $stmt->execute($l);
}
echo "✓ Đã nạp 4 địa điểm hội trường FPoly.\n";

// 6. Nạp Events
$events = [
    [
        1,
        'FPoly TechDay 2026: Trí Tuệ Nhân Tạo & Chuyển Đổi Số',
        'Ngày hội công nghệ thường niên quy mô lớn nhất học kỳ, quy tụ hơn 20 doanh nghiệp công nghệ hàng đầu và chuỗi workshop AI Agent dành cho sinh viên IT.',
        1,
        '2026-11-15 07:30:00',
        '2026-11-15 17:30:00',
        'Published',
        2,
        'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&auto=format&fit=crop&q=60'
    ],
    [
        2,
        'Workshop: Làm Chủ PHP MVC & Kỷ Nguyên AI Vibe Coding',
        'Buổi chia sẻ kỹ thuật chuyên sâu về kiến trúc PHP 8.2 OOP, kết nối an toàn PDO Prepared Statements và phương pháp lập trình AI có kiểm soát theo chuẩn môn WEB212.',
        2,
        '2026-11-18 13:30:00',
        '2026-11-18 17:00:00',
        'Published',
        2,
        'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=800&auto=format&fit=crop&q=60'
    ],
    [
        3,
        'Lễ Khai Mạc Giải Thể Thao FPoly Vô Địch 2026',
        'Chuỗi giải đấu thể thao sinh viên Poly Champion với các môn bóng đá, bóng bàn và cầu lông tạo sân chơi gắn kết rèn luyện thể chất.',
        3,
        '2026-11-20 07:00:00',
        '2026-11-20 11:30:00',
        'Published',
        1,
        'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=800&auto=format&fit=crop&q=60'
    ]
];
$stmt = $db->prepare("INSERT INTO events (id, title, description, location_id, start_time, end_time, status, created_by, banner_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($events as $ev) {
    $stmt->execute($ev);
}
echo "✓ Đã nạp 3 sự kiện chính thức.\n";

// 7. Nạp Shifts
$shifts = [
    [1, 1, 'Ca 1: Setup Sân Khấu & Âm Thanh Ánh Sáng', '2026-11-15 06:30:00', '2026-11-15 09:30:00', 4, 3, 'Kỹ thuật, Thể lực', 15],
    [2, 1, 'Ca 2: Lễ Tân Đón Khách & Check-in Doanh Nghiệp', '2026-11-15 07:30:00', '2026-11-15 11:30:00', 6, 5, 'Giao tiếp, Lễ tân', 20],
    [3, 1, 'Ca 3: Media Phóng Sự Ảnh & Quay Phim Recap', '2026-11-15 08:00:00', '2026-11-15 12:00:00', 3, 2, 'Media, Chụp ảnh', 25],
    [4, 1, 'Ca 4: Điều Phối Workshop Chiều & Hậu Cần Bế Mạc', '2026-11-15 13:00:00', '2026-11-15 17:30:00', 5, 2, 'Hậu cần, Hỗ trợ', 20],
    [5, 2, 'Ca 1: Đón Tiếp Khách Mời & Hỗ Trợ Kỹ Thuật Lab', '2026-11-18 13:00:00', '2026-11-18 17:00:00', 3, 1, 'Kỹ thuật, Giao tiếp', 15],
    [6, 3, 'Ca 1: Lễ Tân Khai Mạc & Trọng Tài Bàn Thể Thao', '2026-11-20 06:30:00', '2026-11-20 11:30:00', 8, 4, 'Thể lực, Lễ tân', 20]
];
$stmt = $db->prepare("INSERT INTO shifts (id, event_id, name, start_time, end_time, target_qty, approved_qty, skill_required, points) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($shifts as $sh) {
    $stmt->execute($sh);
}
echo "✓ Đã nạp 6 ca trực phân rã.\n";

// 8. Nạp Shift Assignments
$assignments = [
    [1, 3, 3, 'Approved'], // Phương Anh nhận ca Media TechDay
    [2, 2, 4, 'Approved'], // Lê Đức Mạnh nhận ca Lễ tân TechDay
    [3, 1, 5, 'Approved'], // Hoàng Minh Luận nhận ca Setup TechDay
    [4, 5, 6, 'Approved']  // Dương Ngọc Hân nhận ca Workshop
];
$stmt = $db->prepare("INSERT INTO shift_assignments (id, shift_id, user_id, status) VALUES (?, ?, ?, ?)");
foreach ($assignments as $as) {
    $stmt->execute($as);
}
echo "✓ Đã nạp phân công ca trực.\n";

// 9. Nạp Drive Submissions
$submissions = [
    [1, 1, 'https://drive.google.com/drive/folders/1A2B3C4D5E6F_FPolyTechDayPhotos', 'Đã chụp xong 450 ảnh RAW + 120 ảnh đã chỉnh màu tone FPoly và 1 video recap 60s', 'Accepted']
];
$stmt = $db->prepare("INSERT INTO drive_submissions (id, assignment_id, drive_url, summary, status) VALUES (?, ?, ?, ?, ?)");
foreach ($submissions as $sub) {
    $stmt->execute($sub);
}
echo "✓ Đã nạp minh chứng nghiệm thu Google Drive.\n";

echo "=== KHỞI TẠO CƠ SỞ DỮ LIỆU HOÀN TẤT 100%! ===\n";
