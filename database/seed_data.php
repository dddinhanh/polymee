<?php
/**
 * PolyMee - Database Seeder
 * Tự động khởi tạo cấu trúc 10 bảng chuẩn và nạp bộ dữ liệu mẫu sạch FPT Polytechnic
 * Hỗ trợ tự động chạy cả SQLite và MySQL/MariaDB qua PDO
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/core/Database.php';

echo "========================================================\n";
echo "       POLYMEE CLEAN DATABASE SEEDER (FPT POLYTECHNIC)   \n";
echo "========================================================\n";
echo "Đang kết nối cơ sở dữ liệu: " . DB_CONNECTION . "\n";

$db = Database::getInstance()->getConnection();
$isSqlite = (DB_CONNECTION === 'sqlite');

// 1. DDL Statements (10 bảng)
if ($isSqlite) {
    $db->exec("PRAGMA foreign_keys = OFF;");
    $statements = [
        "DROP TABLE IF EXISTS activity_logs;",
        "DROP TABLE IF EXISTS calendar_notes;",
        "DROP TABLE IF EXISTS drive_submissions;",
        "DROP TABLE IF EXISTS check_ins;",
        "DROP TABLE IF EXISTS shift_assignments;",
        "DROP TABLE IF EXISTS shifts;",
        "DROP TABLE IF EXISTS events;",
        "DROP TABLE IF EXISTS locations;",
        "DROP TABLE IF EXISTS users;",
        "DROP TABLE IF EXISTS roles;",

        "CREATE TABLE roles (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(50) NOT NULL UNIQUE,
            description VARCHAR(255) NULL
        );",

        "CREATE TABLE users (
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
            id_card_number VARCHAR(20) NULL,
            vneid_image_url VARCHAR(500) NULL,
            bank_name VARCHAR(100) NULL,
            bank_account_number VARCHAR(50) NULL,
            bank_account_name VARCHAR(100) NULL,
            department VARCHAR(100) NULL,
            FOREIGN KEY (role_id) REFERENCES roles(id)
        );",

        "CREATE TABLE locations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(100) NOT NULL,
            address VARCHAR(255) NOT NULL,
            capacity INTEGER NOT NULL DEFAULT 50,
            status VARCHAR(20) DEFAULT 'Available'
        );",

        "CREATE TABLE events (
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

        "CREATE TABLE shifts (
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

        "CREATE TABLE shift_assignments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            shift_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            status VARCHAR(20) DEFAULT 'Approved',
            assigned_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            accounting_status VARCHAR(20) DEFAULT 'Pending',
            lecturer_verified INTEGER DEFAULT 0,
            lecturer_verified_at DATETIME NULL,
            school_status VARCHAR(20) DEFAULT 'Pending',
            school_approved_at DATETIME NULL,
            school_signature_hash VARCHAR(64) NULL,
            contract_code VARCHAR(50) NULL,
            proof_image_url VARCHAR(500) NULL,
            proof_note TEXT NULL,
            FOREIGN KEY (shift_id) REFERENCES shifts(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            UNIQUE (shift_id, user_id)
        );",

        "CREATE TABLE check_ins (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            assignment_id INTEGER NOT NULL,
            check_in_time DATETIME DEFAULT CURRENT_TIMESTAMP,
            check_out_time DATETIME NULL,
            method VARCHAR(20) DEFAULT 'DynamicQR',
            status VARCHAR(20) DEFAULT 'Present',
            FOREIGN KEY (assignment_id) REFERENCES shift_assignments(id) ON DELETE CASCADE
        );",

        "CREATE TABLE drive_submissions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            assignment_id INTEGER NOT NULL,
            drive_url VARCHAR(500) NOT NULL,
            summary TEXT NULL,
            status VARCHAR(20) DEFAULT 'Pending',
            submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            reviewed_at DATETIME NULL,
            FOREIGN KEY (assignment_id) REFERENCES shift_assignments(id) ON DELETE CASCADE
        );",

        "CREATE TABLE calendar_notes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            date DATE NOT NULL,
            title VARCHAR(150) NOT NULL,
            content TEXT NULL,
            author VARCHAR(100) DEFAULT 'Phòng QHDN',
            color VARCHAR(20) DEFAULT '#0284C7',
            type VARCHAR(50) DEFAULT 'Học vụ',
            is_public INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );",

        "CREATE TABLE activity_logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NULL,
            user_name VARCHAR(100) NULL,
            user_role VARCHAR(50) NULL,
            action VARCHAR(100) NOT NULL,
            target_type VARCHAR(50) NULL,
            target_id INTEGER NULL,
            details TEXT NULL,
            ip_address VARCHAR(45) NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );"
    ];
} else {
    $db->exec("SET FOREIGN_KEY_CHECKS = 0;");
    $statements = [
        "DROP TABLE IF EXISTS activity_logs;",
        "DROP TABLE IF EXISTS calendar_notes;",
        "DROP TABLE IF EXISTS drive_submissions;",
        "DROP TABLE IF EXISTS check_ins;",
        "DROP TABLE IF EXISTS shift_assignments;",
        "DROP TABLE IF EXISTS shifts;",
        "DROP TABLE IF EXISTS events;",
        "DROP TABLE IF EXISTS locations;",
        "DROP TABLE IF EXISTS users;",
        "DROP TABLE IF EXISTS roles;",

        "CREATE TABLE roles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL UNIQUE,
            description VARCHAR(255) NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            student_code VARCHAR(20) NOT NULL UNIQUE,
            full_name VARCHAR(100) NOT NULL,
            email VARCHAR(150) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            phone VARCHAR(15) NULL,
            role_id INT NOT NULL,
            skills TEXT NULL,
            is_active TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            id_card_number VARCHAR(20) NULL,
            vneid_image_url VARCHAR(500) NULL,
            bank_name VARCHAR(100) NULL,
            bank_account_number VARCHAR(50) NULL,
            bank_account_name VARCHAR(100) NULL,
            department VARCHAR(100) NULL,
            CONSTRAINT fk_users_roles FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE locations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            address VARCHAR(255) NOT NULL,
            capacity INT NOT NULL DEFAULT 50,
            status VARCHAR(20) DEFAULT 'Available'
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE events (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(200) NOT NULL,
            description TEXT NULL,
            location_id INT NOT NULL,
            start_time DATETIME NOT NULL,
            end_time DATETIME NOT NULL,
            status VARCHAR(20) DEFAULT 'Published',
            created_by INT NOT NULL,
            banner_url VARCHAR(255) NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_events_locations FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE RESTRICT,
            CONSTRAINT fk_events_users FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE shifts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            event_id INT NOT NULL,
            name VARCHAR(100) NOT NULL,
            start_time DATETIME NOT NULL,
            end_time DATETIME NOT NULL,
            target_qty INT NOT NULL DEFAULT 5,
            approved_qty INT NOT NULL DEFAULT 0,
            skill_required VARCHAR(100) NULL,
            points INT NOT NULL DEFAULT 10,
            CONSTRAINT fk_shifts_events FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE shift_assignments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            shift_id INT NOT NULL,
            user_id INT NOT NULL,
            status VARCHAR(20) DEFAULT 'Approved',
            assigned_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            accounting_status VARCHAR(20) DEFAULT 'Pending',
            lecturer_verified TINYINT(1) DEFAULT 0,
            lecturer_verified_at DATETIME NULL,
            school_status VARCHAR(20) DEFAULT 'Pending',
            school_approved_at DATETIME NULL,
            school_signature_hash VARCHAR(64) NULL,
            contract_code VARCHAR(50) NULL,
            proof_image_url VARCHAR(500) NULL,
            proof_note TEXT NULL,
            UNIQUE KEY uk_shift_user (shift_id, user_id),
            CONSTRAINT fk_assignments_shifts FOREIGN KEY (shift_id) REFERENCES shifts(id) ON DELETE CASCADE,
            CONSTRAINT fk_assignments_users FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE check_ins (
            id INT AUTO_INCREMENT PRIMARY KEY,
            assignment_id INT NOT NULL,
            check_in_time DATETIME DEFAULT CURRENT_TIMESTAMP,
            check_out_time DATETIME NULL,
            method VARCHAR(20) DEFAULT 'DynamicQR',
            status VARCHAR(20) DEFAULT 'Present',
            CONSTRAINT fk_checkins_assignments FOREIGN KEY (assignment_id) REFERENCES shift_assignments(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE drive_submissions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            assignment_id INT NOT NULL,
            drive_url VARCHAR(500) NOT NULL,
            summary TEXT NULL,
            status VARCHAR(20) DEFAULT 'Pending',
            submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            reviewed_at DATETIME NULL,
            CONSTRAINT fk_drive_assignments FOREIGN KEY (assignment_id) REFERENCES shift_assignments(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE calendar_notes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            date DATE NOT NULL,
            title VARCHAR(150) NOT NULL,
            content TEXT NULL,
            author VARCHAR(100) DEFAULT 'Phòng QHDN',
            color VARCHAR(20) DEFAULT '#0284C7',
            type VARCHAR(50) DEFAULT 'Học vụ',
            is_public TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE activity_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NULL,
            user_name VARCHAR(100) NULL,
            user_role VARCHAR(50) NULL,
            action VARCHAR(100) NOT NULL,
            target_type VARCHAR(50) NULL,
            target_id INT NULL,
            details TEXT NULL,
            ip_address VARCHAR(45) NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
    ];
}

foreach ($statements as $sql) {
    $db->exec($sql);
}
echo "✓ Đã khởi tạo cấu trúc 10 bảng dữ liệu thành công.\n";

// 2. Nạp Roles (7 vai trò)
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

// 3. Nạp Users (14 tài khoản sạch)
$passHash = password_hash('123456', PASSWORD_BCRYPT);
$users = [
    [1, 'ADMIN001', 'Quản Trị Viên Hệ Thống', 'admin@fpoly.edu.vn', $passHash, '0901234567', 1, 'Quản trị hệ thống, Phân quyền RBAC', 1, '2026-11-01 08:00:00', null, null, null, null, null, 'Công tác Sinh viên'],
    [2, 'GV0102', 'Thầy Phan Hoàng Khải', 'khai.phan@fpt.edu.vn', $passHash, '0912345678', 2, 'Trưởng bộ môn phần mềm, Điều phối sự kiện TechDay', 1, '2026-11-01 08:00:00', null, null, null, null, null, 'Công nghệ Thông tin'],
    [3, 'PI03084', 'Đinh Nguyễn Phương Anh', 'phuonganh.pi03084@fpt.edu.vn', $passHash, '0923456789', 6, 'Media phóng sự, Chụp ảnh sự kiện, Hậu kỳ Premiere/Lightroom', 1, '2026-11-02 09:00:00', '079203014589', 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=600&auto=format&fit=crop&q=80', 'MB Bank (Ngân hàng Quân Đội)', '098765432199', 'DINH NGUYEN PHUONG ANH', 'Thiết kế Đồ họa'],
    [4, 'PI03067', 'Lê Đức Mạnh', 'ducmanh.pi03067@fpt.edu.vn', $passHash, '0934567890', 4, 'Hỗ trợ kỹ thuật Lab, Cài đặt phần mềm, Check-in đại biểu', 1, '2026-11-02 09:15:00', '079204018274', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&auto=format&fit=crop&q=80', 'Techcombank', '19036812891012', 'LE DUC MANH', 'Ứng dụng Phần mềm'],
    [5, 'PI03015', 'Hoàng Minh Luận', 'minhluan.pi03015@fpt.edu.vn', $passHash, '0945678901', 4, 'Âm thanh ánh sáng, Setup sân khấu hội trường, Điều phối thiết bị', 1, '2026-11-02 09:30:00', '079202009182', 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=600&auto=format&fit=crop&q=80', 'Vietcombank', '0071001298451', 'HOANG MINH LUAN', 'Lập trình Web'],
    [6, 'PI02895', 'Dương Ngọc Hân', 'ngochan.pi02895@fpt.edu.vn', $passHash, '0956789012', 4, 'Lễ tân ngoại giao, Đón tiếp khách VIPs, Quản lý tài liệu hội thảo', 1, '2026-11-02 10:00:00', '079301018273', 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=600&auto=format&fit=crop&q=80', 'TPBank (Ngân hàng Tiên Phong)', '04128918201', 'DUONG NGOC HAN', 'Digital Marketing'],
    [7, 'KT0105', 'Đặng Thu Thảo', 'thao.dt@fpt.edu.vn', $passHash, '0967890123', 5, 'Kế toán trưởng, Thẩm định hồ sơ VNeID, Duyệt lệnh chi thù lao', 1, '2026-11-01 08:30:00', null, null, null, null, null, 'Phòng Kế toán - Tài chính'],
    [8, 'HC0201', 'Lê Đình Tuấn', 'tuan.ld@fpt.edu.vn', $passHash, '0978901234', 3, 'Quản lý cơ sở vật chất, Giám sát mượn phòng & âm thanh ánh sáng', 1, '2026-11-01 08:30:00', null, null, null, null, null, 'Phòng Cơ sở Vật chất'],
    [9, 'GV0108', 'Cô Trần Thị Thùy Linh', 'linh.ttt@fpt.edu.vn', $passHash, '0912333444', 2, 'Giảng viên Thiết kế Đồ họa, Trưởng ban tổ chức Creative Expo', 1, '2026-11-01 09:00:00', null, null, null, null, null, 'Thiết kế Đồ họa'],
    [10, 'PI03120', 'Trần Quang Khải', 'quangkhai.pi03120@fpt.edu.vn', $passHash, '0922114455', 4, 'Hỗ trợ kỹ thuật mạng LAN/Wifi, Cài máy Lab, Setup máy chiếu', 1, '2026-11-03 08:00:00', '079201017721', 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=600&auto=format&fit=crop&q=80', 'ACB (Ngân hàng Á Châu)', '289182741', 'TRAN QUANG KHAI', 'Công nghệ Thông tin'],
    [11, 'PI03255', 'Nguyễn Thảo My', 'thaomy.pi03255@fpt.edu.vn', $passHash, '0933225566', 6, 'Nhiếp ảnh chân dung sự kiện, Quay clip TikTok/Reels truyền thông', 1, '2026-11-03 08:30:00', '079304008219', 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&auto=format&fit=crop&q=80', 'VPBank', '19827364501', 'NGUYEN THAO MY', 'Truyền thông & Đa phương tiện'],
    [12, 'GV0205', 'ThS. Nguyễn Văn An', 'an.nv@fpt.edu.vn', $passHash, '0903332211', 2, 'Phó chủ nhiệm Bộ môn Kinh tế, Điều phối Poly Biz Challenge', 1, '2026-11-01 09:00:00', null, null, null, null, null, 'Kinh tế & Quản trị Kinh doanh'],
    [13, 'HC0001', 'ThS. Phạm Hồng Sơn', 'son.ph@fpt.edu.vn', $passHash, '0908889999', 7, 'Trưởng ban Hành chính - Đào tạo, Đại diện Nhà trường ký duyệt số', 1, '2026-11-01 08:00:00', null, null, null, null, null, 'Ban Giám Hiệu'],
    [14, 'GV0301', 'ThS. Vũ Hoàng Yến', 'yen.vh@fpt.edu.vn', $passHash, '0907776655', 2, 'Giảng viên chuyên ngành Du lịch - Nhà hàng Khách sạn', 1, '2026-11-01 09:00:00', null, null, null, null, null, 'Du lịch - Nhà hàng - Khách sạn']
];
$stmt = $db->prepare("INSERT INTO users (id, student_code, full_name, email, password_hash, phone, role_id, skills, is_active, created_at, id_card_number, vneid_image_url, bank_name, bank_account_number, bank_account_name, department) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($users as $u) {
    $stmt->execute($u);
}
echo "✓ Đã nạp 14 tài khoản người dùng sạch đại diện 7 vai trò.\n";

// 4. Nạp Locations (5 địa điểm)
$locations = [
    [1, 'Hội trường Trịnh Văn Bô (Tòa T)', 'Cơ sở Công viên phần mềm Quang Trung, Q.12, TP.HCM', 500, 'Available'],
    [2, 'Phòng Hội thảo Quốc tế (Phòng 301)', 'Tầng 3, Tòa nhà Innovation, CVPM Quang Trung, Q.12, TP.HCM', 120, 'Available'],
    [3, 'Sân khấu Ngoài trời Central Yard', 'Khuôn viên trung tâm FPT Polytechnic CVPM Quang Trung', 800, 'Available'],
    [4, 'Phòng Lab Chuyên đề AI (Phòng 405)', 'Tầng 4, Tòa F, CVPM Quang Trung, Q.12, TP.HCM', 60, 'Available'],
    [5, 'Studio Đa phương tiện & Livestream (Phòng 208)', 'Tầng 2, Tòa nhà Innovation, CVPM Quang Trung, Q.12, TP.HCM', 40, 'Available']
];
$stmt = $db->prepare("INSERT INTO locations (id, name, address, capacity, status) VALUES (?, ?, ?, ?, ?)");
foreach ($locations as $l) {
    $stmt->execute($l);
}
echo "✓ Đã nạp 5 địa điểm hội trường và phòng lab chuẩn.\n";

// 5. Nạp Events (6 sự kiện chính thức)
$events = [
    [1, 'FPoly TechDay 2026: Trí Tuệ Nhân Tạo & Chuyển Đổi Số', 'Ngày hội công nghệ thường niên quy mô lớn nhất học kỳ, quy tụ hơn 20 doanh nghiệp CNTT hàng đầu và chuỗi workshop AI Agent dành cho sinh viên.', 1, '2026-11-15 07:30:00', '2026-11-15 17:30:00', 'Published', 2, 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&auto=format&fit=crop&q=60'],
    [2, 'Workshop: Làm Chủ PHP MVC & Kỷ Nguyên AI Vibe Coding', 'Buổi chia sẻ kỹ thuật chuyên sâu về kiến trúc PHP 8.2 OOP, kết nối an toàn PDO Prepared Statements và phương pháp lập trình AI có kiểm soát theo chuẩn môn WEB212.', 4, '2026-11-18 13:30:00', '2026-11-18 17:00:00', 'Published', 2, 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=800&auto=format&fit=crop&q=60'],
    [3, 'Lễ Khai Mạc Giải Thể Thao FPoly Vô Địch 2026', 'Chuỗi giải đấu thể thao sinh viên Poly Champion với các môn bóng đá, bóng bàn và cờ vua tạo sân chơi gắn kết rèn luyện thể chất.', 3, '2026-11-20 07:00:00', '2026-11-20 11:30:00', 'Published', 1, 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=800&auto=format&fit=crop&q=60'],
    [4, 'Triển Lãm Đồ Họa & Thiết Kế Đa Phương Tiện FPoly Creative', 'Không gian trưng bày hơn 100 tác phẩm đồ họa xuất sắc, sản phẩm bao bì thương hiệu và phim ngắn hoạt hình của sinh viên ngành Thiết kế đồ họa.', 2, '2026-11-22 08:00:00', '2026-11-22 17:00:00', 'Published', 9, 'https://images.unsplash.com/photo-1558655146-d09347e92766?w=800&auto=format&fit=crop&q=60'],
    [5, 'Chung Kết Cuộc Thi Khởi Nghiệp Trẻ FPoly Biz Challenge 2026', 'Đấu trường tranh tài các dự án Startup đổi mới sáng tạo, nhận vốn đầu tư thiên thần từ các quỹ đầu tư mạo hiểm và doanh nghiệp đối tác.', 1, '2026-11-25 13:00:00', '2026-11-25 18:00:00', 'Published', 12, 'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=800&auto=format&fit=crop&q=60'],
    [6, 'Ngày Hội Văn Hóa Ẩm Thực & Du Lịch Poly Hospitality Day', 'Không gian trải nghiệm văn hóa đa quốc gia, nghệ thuật pha chế Bartender và kỹ năng buồng phòng chuyên nghiệp ngành Nhà hàng - Khách sạn.', 3, '2026-11-28 08:00:00', '2026-11-28 15:30:00', 'Published', 14, 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=800&auto=format&fit=crop&q=60']
];
$stmt = $db->prepare("INSERT INTO events (id, title, description, location_id, start_time, end_time, status, created_by, banner_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($events as $ev) {
    $stmt->execute($ev);
}
echo "✓ Đã nạp 6 sự kiện chính thức học kỳ Fall 2026.\n";

// 6. Nạp Shifts (14 ca trực phân rã)
$shifts = [
    [1, 1, 'Ca 1: Setup Sân Khấu & Âm Thanh Ánh Sáng', '2026-11-15 06:30:00', '2026-11-15 09:30:00', 4, 3, 'Kỹ thuật, Thể lực', 15],
    [2, 1, 'Ca 2: Lễ Tân Đón Khách & Check-in Doanh Nghiệp', '2026-11-15 07:30:00', '2026-11-15 11:30:00', 6, 5, 'Giao tiếp, Lễ tân', 20],
    [3, 1, 'Ca 3: Media Phóng Sự Ảnh & Quay Phim Recap', '2026-11-15 08:00:00', '2026-11-15 12:00:00', 3, 2, 'Media, Chụp ảnh', 25],
    [4, 1, 'Ca 4: Điều Phối Workshop Chiều & Bế Mạc', '2026-11-15 13:00:00', '2026-11-15 17:30:00', 5, 2, 'Hậu cần, Hỗ trợ', 20],
    [5, 2, 'Ca 1: Đón Tiếp Khách Mời & Hỗ Trợ Kỹ Thuật Lab', '2026-11-18 13:00:00', '2026-11-18 17:00:00', 3, 1, 'Kỹ thuật, Giao tiếp', 15],
    [6, 3, 'Ca 1: Lễ Tân Khai Mạc & Trọng Tài Bàn Thể Thao', '2026-11-20 06:30:00', '2026-11-20 11:30:00', 8, 4, 'Thể lực, Lễ tân', 20],
    [7, 3, 'Ca 2: Media Thể Thao & Quay Phim Khoảnh Khắc', '2026-11-20 07:30:00', '2026-11-20 11:30:00', 2, 1, 'Media, Thể thao', 20],
    [8, 4, 'Ca 1: Hướng Dẫn Khách Tham Quan Gian Hàng', '2026-11-22 07:30:00', '2026-11-22 12:00:00', 4, 2, 'Lễ tân, Giao tiếp', 15],
    [9, 4, 'Ca 2: Chụp Ảnh Chi Tiết Tác Phẩm Đồ Họa', '2026-11-22 08:30:00', '2026-11-22 13:00:00', 2, 1, 'Media, Nhiếp ảnh', 20],
    [10, 4, 'Ca 3: Hậu Cần Thu Dọn & Đóng Gói Triển Lãm', '2026-11-22 16:30:00', '2026-11-22 18:30:00', 4, 0, 'Hậu cần, Thể lực', 10],
    [11, 5, 'Ca 1: Đón Tiếp Ban Giám Khảo & Quỹ Đầu Tư', '2026-11-25 12:30:00', '2026-11-25 18:00:00', 4, 2, 'Lễ tân, Giao tiếp', 20],
    [12, 5, 'Ca 2: Kỹ Thuật Trình Chiếu Pitching & Micro', '2026-11-25 12:30:00', '2026-11-25 18:00:00', 2, 1, 'Kỹ thuật, Âm thanh', 20],
    [13, 6, 'Ca 1: Hỗ Trợ Gian Hàng Ẩm Thực Vùng Miền', '2026-11-28 07:30:00', '2026-11-28 12:00:00', 6, 2, 'Hậu cần, Ẩm thực', 15],
    [14, 6, 'Ca 2: Media Ghi Hình Hội Thi Bartender', '2026-11-28 09:00:00', '2026-11-28 14:00:00', 2, 1, 'Media, Video', 20]
];
$stmt = $db->prepare("INSERT INTO shifts (id, event_id, name, start_time, end_time, target_qty, approved_qty, skill_required, points) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($shifts as $sh) {
    $stmt->execute($sh);
}
echo "✓ Đã nạp 14 ca trực phân rã hợp lý.\n";

// 7. Nạp Shift Assignments (8 phân công ca trực thực tế đa dạng CTV)
$assignments = [
    [1, 3, 3, 'Approved', '2026-11-10 09:00:00', 'Paid', 1, '2026-11-15 13:00:00', 'Signed_Paid', '2026-11-15 15:30:00', 'f9a8b7c6d5e4f3a2b1c0d9e8f7a6b5c4d3e2f1a0b9c8d7e6f5a4b3c2d1e0f9a8', 'HD-20261115-001', 'https://drive.google.com/drive/folders/1A2B3C4D5E6F_FPolyTechDayPhotos', 'Đã chụp xong 450 ảnh RAW + 120 ảnh đã chỉnh tone màu FPoly và video recap 60s'],
    [2, 2, 4, 'Approved', '2026-11-10 09:15:00', 'Paid', 1, '2026-11-15 12:30:00', 'Signed_Paid', '2026-11-15 15:30:00', 'a1b2c3d4e5f6a7b8c9d0e1f2a3b4c5d6e7f8a9b0c1d2e3f4a5b6c7d8e9f0a1b2', 'HD-20261115-002', 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=800&auto=format&fit=crop&q=80', 'Check-in đủ 185 khách mời VIPs và hướng dẫn vào Hội trường T đúng vị trí'],
    [3, 1, 5, 'Approved', '2026-11-10 09:30:00', 'Paid', 1, '2026-11-15 10:00:00', 'Signed_Paid', '2026-11-15 15:30:00', 'b2c3d4e5f6a7b8c9d0e1f2a3b4c5d6e7f8a9b0c1d2e3f4a5b6c7d8e9f0a1b2c3', 'HD-20261115-003', 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&auto=format&fit=crop&q=80', 'Setup hoàn tất hệ thống âm thanh, 4 micro không dây và 2 màn hình LED phụ'],
    [4, 2, 6, 'Approved', '2026-11-10 10:00:00', 'Paid', 1, '2026-11-15 12:30:00', 'Signed_Paid', '2026-11-15 15:30:00', 'c3d4e5f6a7b8c9d0e1f2a3b4c5d6e7f8a9b0c1d2e3f4a5b6c7d8e9f0a1b2c3d4', 'HD-20261115-004', 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=800&auto=format&fit=crop&q=80', 'Phát 200 bộ tài liệu hội thảo và quà lưu niệm doanh nghiệp chu đáo'],
    [5, 5, 10, 'Approved', '2026-11-12 14:00:00', 'Paid', 1, '2026-11-18 17:30:00', 'Signed_Paid', '2026-11-18 18:00:00', 'd4e5f6a7b8c9d0e1f2a3b4c5d6e7f8a9b0c1d2e3f4a5b6c7d8e9f0a1b2c3d4e5', 'HD-20261118-005', 'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=800&auto=format&fit=crop&q=80', 'Cài đặt xong PHP 8.2 và SQLite trên 40 máy trạm Lab 405 phục vụ thực hành'],
    [6, 6, 4, 'Approved', '2026-11-14 08:30:00', 'Paid', 1, '2026-11-20 12:00:00', 'Signed_Paid', '2026-11-20 14:00:00', 'e5f6a7b8c9d0e1f2a3b4c5d6e7f8a9b0c1d2e3f4a5b6c7d8e9f0a1b2c3d4e5f6', 'HD-20261120-006', 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=800&auto=format&fit=crop&q=80', 'Điều hành bàn trọng tài giải bóng đá, ghi nhận biên bản 8 trận đấu chính xác'],
    [7, 9, 11, 'Approved', '2026-11-15 11:00:00', 'Pending', 1, '2026-11-22 14:00:00', 'Pending', null, null, 'HD-20261122-007', 'https://drive.google.com/drive/folders/1B3C4D5E6F7A_FPolyCreativeArtworks', 'Đã chụp 120 ảnh macro cận cảnh các tác phẩm bao bì và tranh sơn dầu sinh viên'],
    [8, 12, 5, 'Approved', '2026-11-16 16:00:00', 'Pending', 0, null, 'Pending', null, null, null, 'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=800&auto=format&fit=crop&q=80', 'Trực kỹ thuật âm thanh và hỗ trợ slide trình chiếu cho 10 đội thi Khởi nghiệp']
];
$stmt = $db->prepare("INSERT INTO shift_assignments (id, shift_id, user_id, status, assigned_at, accounting_status, lecturer_verified, lecturer_verified_at, school_status, school_approved_at, school_signature_hash, contract_code, proof_image_url, proof_note) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($assignments as $as) {
    $stmt->execute($as);
}
echo "✓ Đã nạp 8 phân công ca trực thực tế đa dạng CTV.\n";

// 8. Nạp Drive Submissions (3 minh chứng Google Drive)
$driveSubs = [
    [1, 1, 'https://drive.google.com/drive/folders/1A2B3C4D5E6F_FPolyTechDayPhotos', 'Bộ ảnh TechDay 2026: 450 ảnh RAW + 120 ảnh đã chỉnh tone màu FPoly và video recap 60s', 'Accepted', '2026-11-15 12:30:00', '2026-11-15 13:00:00'],
    [2, 7, 'https://drive.google.com/drive/folders/1B3C4D5E6F7A_FPolyCreativeArtworks', 'Ảnh chụp triển lãm đồ họa: 120 ảnh macro chi tiết chất lượng cao', 'Pending', '2026-11-22 13:30:00', null],
    [3, 8, 'https://drive.google.com/drive/folders/1C4D5E6F7A8B_FPolyBizPitchingRecordings', 'File ghi âm và slide dự thi của 10 đội vào vòng chung kết Khởi nghiệp', 'Pending', '2026-11-25 18:30:00', null]
];
$stmt = $db->prepare("INSERT INTO drive_submissions (id, assignment_id, drive_url, summary, status, submitted_at, reviewed_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
foreach ($driveSubs as $ds) {
    $stmt->execute($ds);
}
echo "✓ Đã nạp 3 bài nộp Google Drive minh chứng sạch.\n";

// 9. Nạp Calendar Notes (5 ghi chú lịch FPoly)
$notes = [
    [1, '2026-11-15', 'Khai mạc TechDay 2026', 'Toàn bộ CTV Media và Kỹ thuật có mặt trước 06:30 tại Hội trường T để nhận thẻ đeo và bộ đàm.', 'Phòng QHDN', '#EA580C', 'Sự kiện lớn', 1],
    [2, '2026-11-18', 'Bảo trì phòng máy Lab AI', 'Phòng CSVC tiến hành kiểm tra đường truyền mạng và cài bổ sung Ollama AI models.', 'Phòng CSVC', '#0284C7', 'Cơ sở vật chất', 1],
    [3, '2026-11-20', 'Khai mạc Giải thể thao', 'Sân Central Yard mở cửa đón sinh viên và các đội bóng tham dự lễ khai mạc giải.', 'Phòng CTSV', '#10B981', 'Hoạt động phong trào', 1],
    [4, '2026-11-25', 'Chung kết Khởi nghiệp FPoly', 'Hội trường T đón tiếp Ban giám khảo là các Founder và Quỹ đầu tư mạo hiểm.', 'Vườn ươm FPoly', '#F59E0B', 'Học thuật', 1],
    [5, '2026-11-30', 'Hạn chót quyết toán thù lao', 'Phòng Kế toán tổng hợp danh sách nghiệm thu và chuyển khoản thù lao tháng 11 cho toàn bộ CTV.', 'Phòng Kế toán', '#8B5CF6', 'Tài chính', 1]
];
$stmt = $db->prepare("INSERT INTO calendar_notes (id, date, title, content, author, color, type, is_public) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($notes as $n) {
    $stmt->execute($n);
}
echo "✓ Đã nạp 5 ghi chú lịch FPoly thực tế.\n";

// 10. Nạp Activity Logs (10 bản ghi kiểm toán)
$logs = [
    [1, 1, 'Quản Trị Viên Hệ Thống', 'Admin', 'LOGIN', 'auth', 1, 'Đăng nhập hệ thống qua bảo mật session', '127.0.0.1'],
    [2, 2, 'Thầy Phan Hoàng Khải', 'Organizer', 'CREATE_EVENT', 'events', 1, 'Tạo mới sự kiện FPoly TechDay 2026', '127.0.0.1'],
    [3, 8, 'Lê Đình Tuấn', 'Staff', 'APPROVE_LOCATION', 'locations', 1, 'Xác nhận địa điểm Hội trường T không bị trùng lịch', '127.0.0.1'],
    [4, 2, 'Thầy Phan Hoàng Khải', 'Organizer', 'CREATE_SHIFT', 'shifts', 3, 'Khởi tạo ca Media phóng sự ảnh TechDay', '127.0.0.1'],
    [5, 3, 'Đinh Nguyễn Phương Anh', 'Media', 'ACCEPT_SHIFT', 'shift_assignments', 1, 'Đăng ký nhận ca Media TechDay 2026', '127.0.0.1'],
    [6, 3, 'Đinh Nguyễn Phương Anh', 'Media', 'SUBMIT_PROOF', 'drive_submissions', 1, 'Bàn giao link Drive chứa 450 ảnh RAW sự kiện', '127.0.0.1'],
    [7, 2, 'Thầy Phan Hoàng Khải', 'Organizer', 'VERIFY_ACCEPTANCE', 'shift_assignments', 1, 'Giảng viên nghiệm thu đạt chất lượng Media', '127.0.0.1'],
    [8, 13, 'ThS. Phạm Hồng Sơn', 'Head_Admin', 'SIGN_CONTRACT', 'shift_assignments', 1, 'Ký duyệt số hợp đồng chi trả thù lao điện tử', '127.0.0.1'],
    [9, 7, 'Đặng Thu Thảo', 'Accountant', 'DISBURSE_PAYROLL', 'shift_assignments', 1, 'Chuyển khoản thù lao 200,000đ tới STK MB Bank', '127.0.0.1'],
    [10, 1, 'Quản Trị Viên Hệ Thống', 'Admin', 'DATA_REFRESH', 'system', null, 'Nạp bộ dữ liệu sạch chuẩn FPT Polytechnic Fall 2026', '127.0.0.1']
];
$stmt = $db->prepare("INSERT INTO activity_logs (id, user_id, user_name, user_role, action, target_type, target_id, details, ip_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($logs as $lg) {
    $stmt->execute($lg);
}
echo "✓ Đã nạp 10 nhật ký kiểm toán (Audit Logs) chân thực.\n";

if ($isSqlite) {
    $db->exec("PRAGMA foreign_keys = ON;");
} else {
    $db->exec("SET FOREIGN_KEY_CHECKS = 1;");
}

echo "========================================================\n";
echo "✓ NẠP DỮ LIỆU SẠCH HOÀN TẤT 100% TRÊN " . strtoupper(DB_CONNECTION) . "!\n";
echo "========================================================\n";
