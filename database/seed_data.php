<?php
/**
 * PolyMee - Database Seeder
 * Tự động khởi tạo cấu trúc 10 bảng chuẩn và nạp bộ dữ liệu mẫu sạch FPT Polytechnic Đồng Nai
 * Hỗ trợ tự động chạy cả SQLite và MySQL/MariaDB qua PDO
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/core/Database.php';

echo "========================================================\n";
echo "    POLYMEE CLEAN DATABASE SEEDER (FPT POLY ĐỒNG NAI)   \n";
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
            department VARCHAR(100) DEFAULT 'Công nghệ Thông tin',
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
            remuneration_amount INTEGER DEFAULT 150000,
            shift_type VARCHAR(30) DEFAULT 'FACILITY',
            FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
        );",

        "CREATE TABLE shift_assignments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            shift_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            status VARCHAR(20) DEFAULT 'Approved',
            assigned_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            accounting_status VARCHAR(20) DEFAULT 'Pending',
            accounting_note TEXT NULL,
            proof_image_url VARCHAR(500) NULL,
            proof_note TEXT NULL,
            proof_submitted_at DATETIME NULL,
            lecturer_verified INTEGER DEFAULT 0,
            lecturer_name VARCHAR(100) NULL,
            lecturer_verified_at DATETIME NULL,
            contract_code VARCHAR(50) NULL,
            school_status VARCHAR(30) DEFAULT 'Pending',
            ctv_type VARCHAR(30) DEFAULT 'FACILITY',
            proof_drive_url VARCHAR(500) NULL,
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
            event_id INTEGER NULL,
            date VARCHAR(10) NOT NULL,
            title VARCHAR(200) NOT NULL,
            content TEXT NULL,
            author VARCHAR(100) NOT NULL,
            color VARCHAR(20) DEFAULT '#EA580C',
            type VARCHAR(30) DEFAULT 'EVENT_REMINDER',
            is_public INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );",

        "CREATE TABLE activity_logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NULL,
            user_name VARCHAR(100) NOT NULL,
            user_role VARCHAR(50) NOT NULL,
            action VARCHAR(100) NOT NULL,
            target_type VARCHAR(50) NULL,
            target_id INTEGER NULL,
            details TEXT NOT NULL,
            ip_address VARCHAR(50) DEFAULT '127.0.0.1',
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
            department VARCHAR(100) DEFAULT 'Công nghệ Thông tin',
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
            remuneration_amount INT DEFAULT 150000,
            shift_type VARCHAR(30) DEFAULT 'FACILITY',
            CONSTRAINT fk_shifts_events FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE shift_assignments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            shift_id INT NOT NULL,
            user_id INT NOT NULL,
            status VARCHAR(20) DEFAULT 'Approved',
            assigned_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            accounting_status VARCHAR(20) DEFAULT 'Pending',
            accounting_note TEXT NULL,
            proof_image_url VARCHAR(500) NULL,
            proof_note TEXT NULL,
            proof_submitted_at DATETIME NULL,
            lecturer_verified TINYINT(1) DEFAULT 0,
            lecturer_name VARCHAR(100) NULL,
            lecturer_verified_at DATETIME NULL,
            contract_code VARCHAR(50) NULL,
            school_status VARCHAR(30) DEFAULT 'Pending',
            ctv_type VARCHAR(30) DEFAULT 'FACILITY',
            proof_drive_url VARCHAR(500) NULL,
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
            event_id INT NULL,
            date VARCHAR(10) NOT NULL,
            title VARCHAR(200) NOT NULL,
            content TEXT NULL,
            author VARCHAR(100) NOT NULL,
            color VARCHAR(20) DEFAULT '#EA580C',
            type VARCHAR(30) DEFAULT 'EVENT_REMINDER',
            is_public TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE activity_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NULL,
            user_name VARCHAR(100) NOT NULL,
            user_role VARCHAR(50) NOT NULL,
            action VARCHAR(100) NOT NULL,
            target_type VARCHAR(50) NULL,
            target_id INT NULL,
            details TEXT NOT NULL,
            ip_address VARCHAR(50) DEFAULT '127.0.0.1',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
    ];
}

foreach ($statements as $sql) {
    $db->exec($sql);
}
echo "✓ Đã khởi tạo cấu trúc 10 bảng dữ liệu thành công.\n";

// 2. Roles (5 Roles chuẩn - Gộp CSVC & Trưởng HC thành Hành chính)
$roles = [
    [1, 'Admin', 'Quản trị viên toàn quyền hệ thống'],
    [2, 'Organizer', 'Giảng viên và Ban tổ chức sự kiện'],
    [3, 'Staff', 'Cán bộ Hành chính, Quản lý CSVC và Ký duyệt chi thù lao'],
    [4, 'Student', 'Sinh viên FPoly / Cộng tác viên sự kiện'],
    [6, 'Media', 'Cộng tác viên Media & Nhiếp ảnh bàn giao Drive']
];
$stmt = $db->prepare("INSERT INTO roles (id, name, description) VALUES (?, ?, ?)");
foreach ($roles as $r) {
    $stmt->execute($r);
}
echo "✓ Đã nạp 5 vai trò (Roles) chuẩn.\n";

// 3. Users (13 Users)
$passHash = password_hash('123456', PASSWORD_BCRYPT);
$users = [
    [1, 'ADMIN001', 'Quản Trị Viên Hệ Thống', 'admin@fpoly.edu.vn', $passHash, '0901234567', 1, 'Quản trị hệ thống, Phân quyền RBAC', 1, '2026-11-01 08:00:00', null, null, null, null, null, 'Công tác Sinh viên Đồng Nai'],
    [2, 'GV0102', 'Thầy Phan Hoàng Khải', 'khai.phan@fpt.edu.vn', $passHash, '0912345678', 2, 'Trưởng bộ môn phần mềm, Điều phối sự kiện TechDay FPoly Đồng Nai', 1, '2026-11-01 08:00:00', null, null, null, null, null, 'Công nghệ Thông tin'],
    [3, 'PI03084', 'Đinh Nguyễn Phương Anh', 'phuonganh.pi03084@fpt.edu.vn', $passHash, '0923456789', 6, 'Media phóng sự, Chụp ảnh sự kiện, Hậu kỳ Premiere/Lightroom', 1, '2026-11-02 09:00:00', '079203014589', 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=600&auto=format&fit=crop&q=80', 'MB Bank (Ngân hàng Quân Đội)', '098765432199', 'DINH NGUYEN PHUONG ANH', 'Thiết kế Đồ họa'],
    [4, 'PI03067', 'Lê Đức Mạnh', 'ducmanh.pi03067@fpt.edu.vn', $passHash, '0934567890', 4, 'Hỗ trợ kỹ thuật Lab, Cài đặt phần mềm, Hậu cần sự kiện', 1, '2026-11-02 09:15:00', '079204018274', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&auto=format&fit=crop&q=80', 'Techcombank', '19036812891012', 'LE DUC MANH', 'Ứng dụng Phần mềm'],
    [5, 'PI03015', 'Hoàng Minh Luận', 'minhluan.pi03015@fpt.edu.vn', $passHash, '0945678901', 4, 'Âm thanh ánh sáng, Setup sân khấu hội trường, Điều phối thiết bị', 1, '2026-11-02 09:30:00', '079202009182', 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=600&auto=format&fit=crop&q=80', 'Vietcombank', '0071001298451', 'HOANG MINH LUAN', 'Lập trình Web'],
    [6, 'PI02895', 'Dương Ngọc Hân', 'ngochan.pi02895@fpt.edu.vn', $passHash, '0956789012', 4, 'Lễ tân ngoại giao, Đón tiếp khách VIPs, Quản lý tài liệu hội thảo', 1, '2026-11-02 10:00:00', '079301018273', 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=600&auto=format&fit=crop&q=80', 'TPBank (Ngân hàng Tiên Phong)', '04128918201', 'DUONG NGOC HAN', 'Digital Marketing'],
    [8, 'HC0201', 'Lê Đình Tuấn', 'tuan.ld@fpt.edu.vn', $passHash, '0978901234', 3, 'Cán bộ Hành chính, Quản lý cơ sở vật chất & thiết bị FPoly Đồng Nai', 1, '2026-11-01 08:30:00', null, null, null, null, null, 'Phòng Hành chính - CSVC'],
    [9, 'GV0108', 'Cô Hoàng Thị Mai Trang', 'trang.htm@fpt.edu.vn', $passHash, '0912333444', 2, 'Chủ nhiệm bộ môn Đồ họa, Trưởng BTC Triển lãm FPoly Creative Đồng Nai', 1, '2026-11-01 09:00:00', null, null, null, null, null, 'Thiết kế Đồ họa'],
    [10, 'PI03120', 'Trần Quang Khải', 'quangkhai.pi03120@fpt.edu.vn', $passHash, '0922114455', 4, 'Hỗ trợ kỹ thuật mạng LAN/Wifi, Cài máy Lab, Setup máy chiếu', 1, '2026-11-03 08:00:00', '079201017721', 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=600&auto=format&fit=crop&q=80', 'ACB (Ngân hàng Á Châu)', '289182741', 'TRAN QUANG KHAI', 'Công nghệ Thông tin'],
    [11, 'PI03255', 'Nguyễn Thảo My', 'thaomy.pi03255@fpt.edu.vn', $passHash, '0933225566', 6, 'Nhiếp ảnh chân dung sự kiện, Quay clip TikTok/Reels truyền thông', 1, '2026-11-03 08:30:00', '079304008219', 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&auto=format&fit=crop&q=80', 'VPBank', '19827364501', 'NGUYEN THAO MY', 'Truyền thông & Đa phương tiện'],
    [12, 'GV0205', 'ThS. Nguyễn Văn An', 'an.nv@fpt.edu.vn', $passHash, '0903332211', 2, 'Phó chủ nhiệm Bộ môn Kinh tế, Điều phối Poly Biz Challenge Đồng Nai', 1, '2026-11-01 09:00:00', null, null, null, null, null, 'Kinh tế & Quản trị Kinh doanh'],
    [13, 'HC0001', 'ThS. Phạm Hồng Sơn', 'son.ph@fpt.edu.vn', $passHash, '0908889999', 3, 'Trưởng phòng Hành chính, Quản lý CSVC và Ký duyệt chi trả thù lao FPoly Đồng Nai', 1, '2026-11-01 08:00:00', null, null, null, null, null, 'Phòng Hành chính'],
    [14, 'GV0301', 'ThS. Vũ Hoàng Yến', 'yen.vh@fpt.edu.vn', $passHash, '0907776655', 2, 'Giảng viên chuyên ngành Du lịch - Nhà hàng Khách sạn FPoly Đồng Nai', 1, '2026-11-01 09:00:00', null, null, null, null, null, 'Du lịch - Nhà hàng - Khách sạn']
];
$stmt = $db->prepare("INSERT INTO users (id, student_code, full_name, email, password_hash, phone, role_id, skills, is_active, created_at, id_card_number, vneid_image_url, bank_name, bank_account_number, bank_account_name, department) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($users as $u) {
    $stmt->execute($u);
}
echo "✓ Đã nạp 13 tài khoản người dùng sạch FPoly Đồng Nai.\n";

// 4. Locations (5 Locations)
$locations = [
    [1, 'Hội trường Trịnh Văn Bô (Tòa A)', 'Cơ sở FPT Polytechnic Đồng Nai, Số 120/1 Trương Định, TP. Biên Hòa, Tỉnh Đồng Nai', 500, 'Available'],
    [2, 'Phòng Hội thảo Quốc tế (Phòng 301)', 'Tầng 3, Tòa A, FPT Polytechnic Đồng Nai, TP. Biên Hòa', 120, 'Available'],
    [3, 'Sân khấu Ngoài trời Central Yard', 'Khuôn viên trung tâm FPT Polytechnic Đồng Nai, TP. Biên Hòa', 800, 'Available'],
    [4, 'Phòng Lab Chuyên đề AI (Phòng 405)', 'Tầng 4, Tòa B, FPT Polytechnic Đồng Nai, TP. Biên Hòa', 60, 'Available'],
    [5, 'Studio Đa phương tiện & Livestream (Phòng 208)', 'Tầng 2, Tòa B, FPT Polytechnic Đồng Nai, TP. Biên Hòa', 40, 'Available']
];
$stmt = $db->prepare("INSERT INTO locations (id, name, address, capacity, status) VALUES (?, ?, ?, ?, ?)");
foreach ($locations as $l) {
    $stmt->execute($l);
}
echo "✓ Đã nạp 5 địa điểm hội trường FPoly Đồng Nai.\n";

// 5. Events (6 Events)
$events = [
    [1, 'FPoly TechDay 2026: Trí Tuệ Nhân Tạo & Chuyển Đổi Số', 'Ngày hội công nghệ thường niên quy mô lớn nhất học kỳ tại FPT Polytechnic Đồng Nai, quy tụ hơn 20 doanh nghiệp CNTT hàng đầu và chuỗi workshop AI Agent dành cho sinh viên.', 1, '2026-11-15 07:30:00', '2026-11-15 17:30:00', 'Published', 2, 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&auto=format&fit=crop&q=60', '2026-11-01 08:00:00', 'Công nghệ Thông tin'],
    [2, 'Workshop: Làm Chủ PHP MVC & Kỷ Nguyên AI Vibe Coding', 'Buổi chia sẻ kỹ thuật chuyên sâu về kiến trúc PHP 8.2 OOP, kết nối an toàn PDO Prepared Statements và phương pháp lập trình AI có kiểm soát theo chuẩn môn WEB212 tại Lab AI FPoly Đồng Nai.', 4, '2026-11-18 13:30:00', '2026-11-18 17:00:00', 'Published', 2, 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=800&auto=format&fit=crop&q=60', '2026-11-02 09:00:00', 'Ứng dụng Phần mềm'],
    [3, 'Lễ Khai Mạc Giải Thể Thao FPoly Vô Địch 2026', 'Chuỗi giải đấu thể thao sinh viên Poly Champion với các môn bóng đá, bóng bàn và cờ vua tạo sân chơi gắn kết rèn luyện thể chất tại Sân Central Yard FPoly Đồng Nai.', 3, '2026-11-20 07:00:00', '2026-11-20 11:30:00', 'Published', 1, 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=800&auto=format&fit=crop&q=60', '2026-11-03 10:00:00', 'Công tác Sinh viên'],
    [4, 'Triển Lãm Đồ Họa & Thiết Kế Đa Phương Tiện FPoly Creative', 'Không gian trưng bày hơn 100 tác phẩm đồ họa xuất sắc, sản phẩm bao bì thương hiệu và phim ngắn hoạt hình của sinh viên ngành Thiết kế đồ họa FPT Polytechnic Đồng Nai.', 2, '2026-11-22 08:00:00', '2026-11-22 17:00:00', 'Published', 9, 'https://images.unsplash.com/photo-1558655146-d09347e92766?w=800&auto=format&fit=crop&q=60', '2026-11-04 11:00:00', 'Thiết kế Đồ họa'],
    [5, 'Chung Kết Cuộc Thi Khởi Nghiệp Trẻ FPoly Biz Challenge 2026', 'Đấu trường tranh tài các dự án Startup đổi mới sáng tạo, nhận vốn đầu tư thiên thần từ các quỹ đầu tư mạo hiểm và doanh nghiệp đối tác tại Hội trường FPoly Đồng Nai.', 1, '2026-11-25 13:00:00', '2026-11-25 18:00:00', 'Published', 12, 'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=800&auto=format&fit=crop&q=60', '2026-11-05 14:00:00', 'Kinh tế & Quản trị Kinh doanh'],
    [6, 'Ngày Hội Văn Hóa Ẩm Thực & Du Lịch Poly Hospitality Day', 'Không gian trải nghiệm văn hóa đa quốc gia, nghệ thuật pha chế Bartender và kỹ năng buồng phòng chuyên nghiệp ngành Nhà hàng - Khách sạn FPoly Đồng Nai.', 3, '2026-11-28 08:00:00', '2026-11-28 15:30:00', 'Published', 14, 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=800&auto=format&fit=crop&q=60', '2026-11-06 15:00:00', 'Du lịch - Nhà hàng - Khách sạn'],
    [7, 'Workshop Bộ môn CNTT: Đổi Mới Đào Tạo & AI 2026', 'Buổi tọa đàm chuyên môn giảng viên và sinh viên về ứng dụng AI trong giảng dạy và nghiên cứu thực chiến tại FPoly.', 1, '2026-11-22 13:30:00', '2026-11-22 17:30:00', 'Published', 2, 'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=800&auto=format&fit=crop&q=60', '2026-11-04 14:00:00', 'Công nghệ Thông tin']
];
$stmt = $db->prepare("INSERT INTO events (id, title, description, location_id, start_time, end_time, status, created_by, banner_url, created_at, department) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($events as $ev) {
    $stmt->execute($ev);
}
echo "✓ Đã nạp 7 sự kiện chính thức FPoly Đồng Nai.\n";

// 6. Shifts (16 Shifts)
$shifts = [
    [1, 1, 'Ca 1: Setup Sân Khấu & Âm Thanh Ánh Sáng', '2026-11-15 06:30:00', '2026-11-15 09:30:00', 4, 3, 'Kỹ thuật, Thể lực', 15, 150000, 'FACILITY'],
    [2, 1, 'Ca 2: Lễ Tân Đón Khách & Check-in Doanh Nghiệp', '2026-11-15 07:30:00', '2026-11-15 11:30:00', 6, 5, 'Giao tiếp, Lễ tân', 20, 150000, 'FACILITY'],
    [3, 1, 'Ca 3: Media Phóng Sự Ảnh & Quay Phim Recap', '2026-11-15 08:00:00', '2026-11-15 12:00:00', 3, 2, 'Media, Chụp ảnh', 25, 200000, 'MEDIA'],
    [4, 1, 'Ca 4: Điều Phối Workshop Chiều & Bế Mạc', '2026-11-15 13:00:00', '2026-11-15 17:30:00', 5, 2, 'Hậu cần, Hỗ trợ', 20, 150000, 'FACILITY'],
    [5, 2, 'Ca 1: Đón Tiếp Khách Mời & Hỗ Trợ Kỹ Thuật Lab', '2026-11-18 13:00:00', '2026-11-18 17:00:00', 3, 1, 'Kỹ thuật, Giao tiếp', 15, 150000, 'FACILITY'],
    [6, 3, 'Ca 1: Lễ Tân Khai Mạc & Trọng Tài Bàn Thể Thao', '2026-11-20 06:30:00', '2026-11-20 11:30:00', 8, 4, 'Thể lực, Lễ tân', 20, 150000, 'FACILITY'],
    [7, 3, 'Ca 2: Media Thể Thao & Quay Phim Khoảnh Khắc', '2026-11-20 07:30:00', '2026-11-20 11:30:00', 2, 1, 'Media, Thể thao', 20, 180000, 'MEDIA'],
    [8, 4, 'Ca 1: Hướng Dẫn Khách Tham Quan Gian Hàng', '2026-11-22 07:30:00', '2026-11-22 12:00:00', 4, 2, 'Lễ tân, Giao tiếp', 15, 150000, 'FACILITY'],
    [9, 4, 'Ca 2: Chụp Ảnh Chi Tiết Tác Phẩm Đồ Họa', '2026-11-22 08:30:00', '2026-11-22 13:00:00', 2, 1, 'Media, Nhiếp ảnh', 20, 200000, 'MEDIA'],
    [10, 4, 'Ca 3: Hậu Cần Thu Dọn & Đóng Gói Triển Lãm', '2026-11-22 16:30:00', '2026-11-22 18:30:00', 4, 0, 'Hậu cần, Thể lực', 10, 100000, 'FACILITY'],
    [11, 5, 'Ca 1: Đón Tiếp Ban Giám Khảo & Quỹ Đầu Tư', '2026-11-25 12:30:00', '2026-11-25 18:00:00', 4, 2, 'Lễ tân, Giao tiếp', 20, 180000, 'FACILITY'],
    [12, 5, 'Ca 2: Kỹ Thuật Trình Chiếu Pitching & Micro', '2026-11-25 12:30:00', '2026-11-25 18:00:00', 2, 1, 'Kỹ thuật, Âm thanh', 20, 180000, 'FACILITY'],
    [13, 6, 'Ca 1: Hỗ Trợ Gian Hàng Ẩm Thực Vùng Miền', '2026-11-28 07:30:00', '2026-11-28 12:00:00', 6, 2, 'Hậu cần, Ẩm thực', 15, 150000, 'FACILITY'],
    [14, 6, 'Ca 2: Media Ghi Hình Hội Thi Bartender', '2026-11-28 09:00:00', '2026-11-28 14:00:00', 2, 1, 'Media, Video', 20, 200000, 'MEDIA'],
    [15, 7, 'Ca 1: Setup Hội Trường & Hệ Thống Trình Chiếu', '2026-11-22 13:00:00', '2026-11-22 15:30:00', 4, 2, 'Kỹ thuật, Máy chiếu', 15, 150000, 'FACILITY'],
    [16, 7, 'Ca 2: Lễ Tân Đón Khách & Tổng Kết Hội Thảo', '2026-11-22 15:00:00', '2026-11-22 17:30:00', 3, 1, 'Lễ tân, Giao tiếp', 15, 150000, 'FACILITY']
];
$stmt = $db->prepare("INSERT INTO shifts (id, event_id, name, start_time, end_time, target_qty, approved_qty, skill_required, points, remuneration_amount, shift_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($shifts as $sh) {
    $stmt->execute($sh);
}
echo "✓ Đã nạp 16 ca trực phân rã.\n";

// 7. Shift Assignments (8 Assignments)
$assignments = [
    [1, 3, 3, 'Approved', '2026-11-10 09:00:00', 'Paid', 'Đã chuyển thù lao qua MB Bank', 'https://drive.google.com/drive/folders/1A2B3C4D5E6F_FPolyTechDayPhotos', 'Đã chụp xong 450 ảnh RAW + 120 ảnh đã chỉnh tone màu FPoly và video recap 60s', '2026-11-15 12:30:00', 1, 'Thầy Phan Hoàng Khải (CNTT)', '2026-11-15 13:00:00', 'HD-20261115-001', 'Signed_Paid', 'MEDIA', 'https://drive.google.com/drive/folders/1A2B3C4D5E6F_FPolyTechDayPhotos'],
    [2, 2, 4, 'Approved', '2026-11-10 09:15:00', 'Paid', 'Đã đối soát Techcombank', 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=800&auto=format&fit=crop&q=80', 'Check-in đủ 185 khách mời VIPs và hướng dẫn vào Hội trường T đúng vị trí', '2026-11-15 11:45:00', 1, 'Thầy Phan Hoàng Khải (CNTT)', '2026-11-15 12:30:00', 'HD-20261115-002', 'Signed_Paid', 'FACILITY', null],
    [3, 1, 5, 'Approved', '2026-11-10 09:30:00', 'Paid', 'Đã chuyển khoản Vietcombank', 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&auto=format&fit=crop&q=80', 'Setup hoàn tất hệ thống âm thanh, 4 micro không dây và 2 màn hình LED phụ', '2026-11-15 09:35:00', 1, 'Thầy Phan Hoàng Khải (CNTT)', '2026-11-15 10:00:00', 'HD-20261115-003', 'Signed_Paid', 'FACILITY', null],
    [4, 2, 6, 'Approved', '2026-11-10 10:00:00', 'Paid', 'Đã chuyển khoản TPBank', 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=800&auto=format&fit=crop&q=80', 'Phát 200 bộ tài liệu hội thảo và quà lưu niệm doanh nghiệp chu đáo', '2026-11-15 11:50:00', 1, 'Thầy Phan Hoàng Khải (CNTT)', '2026-11-15 12:30:00', 'HD-20261115-004', 'Signed_Paid', 'FACILITY', null],
    [5, 5, 10, 'Approved', '2026-11-12 14:00:00', 'Paid', 'Đã chuyển khoản ACB', 'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=800&auto=format&fit=crop&q=80', 'Cài đặt xong PHP 8.2 và SQLite trên 40 máy trạm Lab 405 phục vụ thực hành', '2026-11-18 17:15:00', 1, 'Thầy Phan Hoàng Khải (CNTT)', '2026-11-18 17:30:00', 'HD-20261118-005', 'Signed_Paid', 'FACILITY', null],
    [6, 6, 4, 'Approved', '2026-11-14 08:30:00', 'Paid', 'Đã duyệt thù lao giải thể thao', 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=800&auto=format&fit=crop&q=80', 'Điều hành bàn trọng tài giải bóng đá, ghi nhận biên bản 8 trận đấu chính xác', '2026-11-20 11:45:00', 1, 'Lê Đình Tuấn (CSVC)', '2026-11-20 12:00:00', 'HD-20261120-006', 'Signed_Paid', 'FACILITY', null],
    [7, 9, 11, 'Approved', '2026-11-15 11:00:00', 'Approved', 'Chờ BGH ký duyệt chi', 'https://drive.google.com/drive/folders/1B3C4D5E6F7A_FPolyCreativeArtworks', 'Đã chụp 120 ảnh macro cận cảnh các tác phẩm bao bì và tranh sơn dầu sinh viên', '2026-11-22 13:15:00', 1, 'Cô Hoàng Thị Mai Trang (Bộ môn Đồ họa)', '2026-11-22 14:00:00', 'HD-20261122-007', 'Pending', 'MEDIA', 'https://drive.google.com/drive/folders/1B3C4D5E6F7A_FPolyCreativeArtworks'],
    [8, 12, 5, 'Approved', '2026-11-16 16:00:00', 'Pending', null, 'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=800&auto=format&fit=crop&q=80', 'Trực kỹ thuật âm thanh và hỗ trợ slide trình chiếu cho 10 đội thi Khởi nghiệp FPoly Đồng Nai', '2026-11-25 18:15:00', 0, null, null, null, 'Pending', 'FACILITY', null]
];
$stmt = $db->prepare("INSERT INTO shift_assignments (id, shift_id, user_id, status, assigned_at, accounting_status, accounting_note, proof_image_url, proof_note, proof_submitted_at, lecturer_verified, lecturer_name, lecturer_verified_at, contract_code, school_status, ctv_type, proof_drive_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($assignments as $as) {
    $stmt->execute($as);
}
echo "✓ Đã nạp 8 phân công ca trực thực tế đa dạng CTV.\n";

// 8. Drive Submissions
$driveSubs = [
    [1, 1, 'https://drive.google.com/drive/folders/1A2B3C4D5E6F_FPolyTechDayPhotos', 'Bộ ảnh TechDay 2026 FPoly Đồng Nai: 450 ảnh RAW + 120 ảnh đã chỉnh tone màu FPoly và video recap 60s', 'Accepted', '2026-11-15 12:30:00', '2026-11-15 13:00:00'],
    [2, 7, 'https://drive.google.com/drive/folders/1B3C4D5E6F7A_FPolyCreativeArtworks', 'Ảnh chụp triển lãm đồ họa: 120 ảnh macro chi tiết do Cô Hoàng Thị Mai Trang nghiệm thu', 'Pending', '2026-11-22 13:30:00', null],
    [3, 8, 'https://drive.google.com/drive/folders/1C4D5E6F7A8B_FPolyBizPitchingRecordings', 'File ghi âm và slide dự thi của 10 đội vào vòng chung kết Khởi nghiệp FPoly Đồng Nai', 'Pending', '2026-11-25 18:30:00', null]
];
$stmt = $db->prepare("INSERT INTO drive_submissions (id, assignment_id, drive_url, summary, status, submitted_at, reviewed_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
foreach ($driveSubs as $ds) {
    $stmt->execute($ds);
}
echo "✓ Đã nạp 3 bài nộp Google Drive minh chứng sạch.\n";

// 9. Calendar Notes
$notes = [
    [1, 1, '2026-11-15', 'Khai mạc TechDay 2026 FPoly Đồng Nai', 'Toàn bộ CTV Media và Kỹ thuật có mặt trước 06:30 tại Hội trường Trịnh Văn Bô để nhận thẻ đeo và bộ đàm.', 'Phòng QHDN Đồng Nai', '#EA580C', 'EVENT_REMINDER', 1, '2026-11-01 08:00:00'],
    [2, 2, '2026-11-18', 'Bảo trì phòng máy Lab AI Tòa B', 'Phòng CSVC tiến hành kiểm tra đường truyền mạng cáp quang và cài bổ sung Ollama AI models.', 'Phòng CSVC', '#0284C7', 'FACILITY_NOTICE', 1, '2026-11-02 09:00:00'],
    [3, 3, '2026-11-20', 'Khai mạc Giải thể thao Poly Champion', 'Sân Central Yard mở cửa đón sinh viên và các đội bóng tham dự lễ khai mạc giải đấu thể thao FPoly Đồng Nai.', 'Phòng CTSV Đồng Nai', '#10B981', 'EVENT_REMINDER', 1, '2026-11-03 10:00:00'],
    [4, 5, '2026-11-25', 'Chung kết Khởi nghiệp FPoly Biz', 'Hội trường Trịnh Văn Bô đón tiếp Ban giám khảo là các Founder và Quỹ đầu tư mạo hiểm khu vực Đông Nam Bộ.', 'Vườn ươm FPoly Đồng Nai', '#F59E0B', 'EVENT_REMINDER', 1, '2026-11-05 11:00:00'],
    [5, null, '2026-11-30', 'Hạn chót quyết toán thù lao tháng 11', 'Ban Giám Hiệu & Phòng Hành chính tổng hợp danh sách nghiệm thu và chuyển khoản thù lao tháng 11 cho toàn bộ CTV.', 'Ban Giám Hiệu', '#8B5CF6', 'FINANCE_DEADLINE', 1, '2026-11-06 14:00:00']
];
$stmt = $db->prepare("INSERT INTO calendar_notes (id, event_id, date, title, content, author, color, type, is_public, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($notes as $n) {
    $stmt->execute($n);
}
echo "✓ Đã nạp 5 ghi chú lịch FPoly Đồng Nai.\n";

// 10. Activity Logs
$logs = [
    [1, 1, 'Quản Trị Viên Hệ Thống', 'Admin', 'LOGIN', 'auth', 1, 'Đăng nhập hệ thống qua bảo mật session', '127.0.0.1', '2026-11-15 06:00:00'],
    [2, 2, 'Thầy Phan Hoàng Khải', 'Organizer', 'CREATE_EVENT', 'events', 1, 'Tạo mới sự kiện FPoly TechDay 2026 tại FPT Polytechnic Đồng Nai', '127.0.0.1', '2026-11-15 06:15:00'],
    [3, 8, 'Lê Đình Tuấn', 'Staff', 'APPROVE_LOCATION', 'locations', 1, 'Xác nhận địa điểm Hội trường Trịnh Văn Bô không bị trùng lịch', '127.0.0.1', '2026-11-15 06:20:00'],
    [4, 2, 'Thầy Phan Hoàng Khải', 'Organizer', 'CREATE_SHIFT', 'shifts', 3, 'Khởi tạo ca Media phóng sự ảnh TechDay', '127.0.0.1', '2026-11-15 06:25:00'],
    [5, 3, 'Đinh Nguyễn Phương Anh', 'Media', 'ACCEPT_SHIFT', 'shift_assignments', 1, 'Đăng ký nhận ca Media TechDay 2026', '127.0.0.1', '2026-11-15 07:00:00'],
    [6, 3, 'Đinh Nguyễn Phương Anh', 'Media', 'SUBMIT_PROOF', 'drive_submissions', 1, 'Bàn giao link Drive chứa 450 ảnh RAW sự kiện', '127.0.0.1', '2026-11-15 12:30:00'],
    [7, 2, 'Thầy Phan Hoàng Khải', 'Organizer', 'VERIFY_ACCEPTANCE', 'shift_assignments', 1, 'Giảng viên nghiệm thu đạt chất lượng Media', '127.0.0.1', '2026-11-15 13:00:00'],
    [8, 13, 'ThS. Phạm Hồng Sơn', 'Staff', 'SIGN_CONTRACT', 'shift_assignments', 1, 'Ký duyệt số hợp đồng chi trả thù lao điện tử HD-20261115-001', '127.0.0.1', '2026-11-15 15:30:00'],
    [9, 13, 'ThS. Phạm Hồng Sơn', 'Staff', 'DISBURSE_PAYROLL', 'shift_assignments', 1, 'Đại diện BGH FPoly Đồng Nai phê duyệt lệnh chuyển khoản thù lao 200,000đ tới STK MB Bank', '127.0.0.1', '2026-11-15 15:35:00'],
    [10, 9, 'Cô Hoàng Thị Mai Trang', 'Organizer', 'CREATE_EVENT', 'events', 4, 'Tạo sự kiện Triển lãm FPoly Creative tại Phòng 301 Tòa A FPoly Đồng Nai', '127.0.0.1', '2026-11-16 08:00:00']
];
$stmt = $db->prepare("INSERT INTO activity_logs (id, user_id, user_name, user_role, action, target_type, target_id, details, ip_address, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
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
