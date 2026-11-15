-- =====================================================================
-- DỰ ÁN MÔN HỌC: PHÁT TRIỂN DỰ ÁN WEBSITE VỚI AI (WEB212)
-- ĐỀ TÀI: HỆ THỐNG QUẢN TRỊ SỰ KIỆN VÀ ĐIỀU PHỐI CTV TẠI FPOLY (POLYMEE)
-- SCRIPT CƠ SỞ DỮ LIỆU MYSQL / MARIADB CHUẨN 3NF (CHO BÀI NỘP LMS)
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `polymee_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `polymee_db`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `activity_logs`;
DROP TABLE IF EXISTS `calendar_notes`;
DROP TABLE IF EXISTS `drive_submissions`;
DROP TABLE IF EXISTS `check_ins`;
DROP TABLE IF EXISTS `shift_assignments`;
DROP TABLE IF EXISTS `shifts`;
DROP TABLE IF EXISTS `events`;
DROP TABLE IF EXISTS `locations`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `roles`;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. BẢNG ROLES (Vai trò phân quyền RBAC)
CREATE TABLE `roles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `description` VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. BẢNG USERS (Tài khoản người dùng & Hồ sơ định danh)
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_code` VARCHAR(20) NOT NULL UNIQUE,
    `full_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(15) NULL,
    `role_id` INT NOT NULL,
    `skills` TEXT NULL,
    `id_card_number` VARCHAR(20) NULL,
    `bank_account_number` VARCHAR(50) NULL,
    `bank_name` VARCHAR(100) NULL,
    `bank_account_name` VARCHAR(100) NULL,
    `vneid_image_url` VARCHAR(500) NULL,
    `department` VARCHAR(100) NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_users_roles` FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. BẢNG LOCATIONS (Địa điểm & Hội trường FPoly)
CREATE TABLE `locations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `address` VARCHAR(255) NOT NULL,
    `capacity` INT NOT NULL DEFAULT 50,
    `status` VARCHAR(20) DEFAULT 'Available' -- Available, Maintenance, Booked
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. BẢNG EVENTS (Sự kiện FPT Polytechnic)
CREATE TABLE `events` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,
    `location_id` INT NOT NULL,
    `start_time` DATETIME NOT NULL,
    `end_time` DATETIME NOT NULL,
    `status` VARCHAR(20) DEFAULT 'Published', -- Draft, Published, Ongoing, Completed, Cancelled
    `created_by` INT NOT NULL,
    `banner_url` VARCHAR(255) NULL,
    `department` VARCHAR(100) DEFAULT 'Công nghệ Thông tin',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_events_location` FOREIGN KEY (`location_id`) REFERENCES `locations`(`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_events_user` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. BẢNG SHIFTS (Phân rã Ca trực của sự kiện)
CREATE TABLE `shifts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `event_id` INT NOT NULL,
    `name` VARCHAR(100) NOT NULL, -- Setup, Lễ tân & Đón khách, Media & Chụp ảnh, Hậu cần
    `start_time` DATETIME NOT NULL,
    `end_time` DATETIME NOT NULL,
    `target_qty` INT NOT NULL DEFAULT 5,
    `approved_qty` INT NOT NULL DEFAULT 0,
    `skill_required` VARCHAR(100) NULL,
    `points` INT NOT NULL DEFAULT 10,
    `remuneration_amount` INT DEFAULT 150000,
    `shift_type` VARCHAR(30) DEFAULT 'FACILITY', -- FACILITY, MEDIA
    CONSTRAINT `fk_shifts_event` FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. BẢNG SHIFT_ASSIGNMENTS (Đăng ký & Phân công CTV vào ca trực)
CREATE TABLE `shift_assignments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `shift_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `status` VARCHAR(20) DEFAULT 'Approved', -- Pending, Approved, Waitlist, Cancelled
    `assigned_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `accounting_status` VARCHAR(20) DEFAULT 'Pending', -- Pending, Approved, Paid
    `accounting_note` TEXT NULL,
    `proof_image_url` VARCHAR(500) NULL, -- Ảnh chụp hiện trường có mặt của CTV
    `proof_note` TEXT NULL, -- Ghi chú vị trí & công việc hiện trường
    `proof_submitted_at` DATETIME NULL,
    `lecturer_verified` TINYINT(1) DEFAULT 0, -- Giảng viên thẩm định nghiệm thu (0/1)
    `lecturer_name` VARCHAR(100) NULL, -- Tên Giảng viên xác nhận
    `lecturer_verified_at` DATETIME NULL,
    `contract_code` VARCHAR(50) NULL, -- Số Hợp đồng / Biên bản nghiệm thu (HDNT-...)
    `school_status` VARCHAR(30) DEFAULT 'Pending', -- Pending, Approved, Signed_Paid
    `ctv_type` VARCHAR(30) DEFAULT 'FACILITY', -- FACILITY, MEDIA
    `proof_drive_url` VARCHAR(500) NULL,
    CONSTRAINT `fk_assignments_shift` FOREIGN KEY (`shift_id`) REFERENCES `shifts`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_assignments_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_shift_user` (`shift_id`, `user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. BẢNG CHECK_INS (Nhật ký điểm danh Dynamic QR Code)
CREATE TABLE `check_ins` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `assignment_id` INT NOT NULL,
    `check_in_time` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `check_out_time` DATETIME NULL,
    `method` VARCHAR(20) DEFAULT 'DynamicQR', -- DynamicQR, Manual
    `status` VARCHAR(20) DEFAULT 'Present', -- Present, Absent, Late
    CONSTRAINT `fk_checkins_assignment` FOREIGN KEY (`assignment_id`) REFERENCES `shift_assignments`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. BẢNG DRIVE_SUBMISSIONS (Nghiệm thu minh chứng Google Drive của CTV Media)
CREATE TABLE `drive_submissions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `assignment_id` INT NOT NULL,
    `drive_url` VARCHAR(500) NOT NULL,
    `summary` TEXT NULL,
    `status` VARCHAR(20) DEFAULT 'Pending', -- Pending, Accepted, Rejected
    `submitted_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `reviewed_at` DATETIME NULL,
    CONSTRAINT `fk_submissions_assignment` FOREIGN KEY (`assignment_id`) REFERENCES `shift_assignments`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. BẢNG CALENDAR_NOTES (Ghi chú & Dặn dò Lịch toàn trường Apple Style)
CREATE TABLE `calendar_notes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `event_id` INT NULL,
    `date` VARCHAR(10) NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `content` TEXT NULL,
    `author` VARCHAR(100) NOT NULL,
    `color` VARCHAR(20) DEFAULT '#EA580C',
    `type` VARCHAR(30) DEFAULT 'EVENT_REMINDER', -- EVENT_REMINDER, DEADLINE, FACILITY_NOTE, MEETING
    `is_public` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. BẢNG ACTIVITY_LOGS (Nhật ký thao tác hệ thống - Audit Trail)
CREATE TABLE `activity_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NULL,
    `user_name` VARCHAR(100) NOT NULL,
    `user_role` VARCHAR(50) NOT NULL,
    `action` VARCHAR(100) NOT NULL,
    `target_type` VARCHAR(50) NULL,
    `target_id` INT NULL,
    `details` TEXT NOT NULL,
    `ip_address` VARCHAR(50) DEFAULT '127.0.0.1',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- NẠP DỮ LIỆU MẪU CHUẨN SẠCH & THỰC TẾ (AUTHENTIC SEED DATA)
-- =====================================================================

-- 1. Roles
INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'Admin', 'Quản trị viên toàn quyền hệ thống'),
(2, 'Organizer', 'Giảng viên và Ban tổ chức sự kiện'),
(3, 'Staff', 'Cán bộ giám sát và điều phối hiện trường'),
(4, 'Student', 'Sinh viên FPoly / Cộng tác viên sự kiện'),
(5, 'Accountant', 'Kế toán & Quyết toán thù lao CTV'),
(6, 'Media', 'Cộng tác viên Media & Nhiếp ảnh bàn giao Drive'),
(7, 'Head_Admin', 'Trưởng hành chính & Ban giám hiệu phê duyệt');

-- 2. Users (Mật khẩu mặc định: '123456')
INSERT INTO `users` (`id`, `student_code`, `full_name`, `email`, `password_hash`, `phone`, `role_id`, `skills`, `id_card_number`, `vneid_image_url`, `bank_name`, `bank_account_number`, `bank_account_name`, `department`, `is_active`, `created_at`) VALUES
(1, 'ADMIN001', 'Quản Trị Viên Hệ Thống', 'admin@fpoly.edu.vn', '$2y$10$h9RfZ18k19b91pEreGzHmejOcbGz4Z2u9y.2zVqZtq9Vz5a4F0Lte', '0901234567', 1, 'Quản trị hệ thống, Phân quyền RBAC', NULL, NULL, NULL, NULL, NULL, 'Công tác Sinh viên', 1, '2026-11-01 08:00:00'),
(2, 'GV0102', 'Thầy Phan Hoàng Khải', 'khai.phan@fpt.edu.vn', '$2y$10$h9RfZ18k19b91pEreGzHmejOcbGz4Z2u9y.2zVqZtq9Vz5a4F0Lte', '0912345678', 2, 'Trưởng bộ môn phần mềm, Điều phối sự kiện TechDay', NULL, NULL, NULL, NULL, NULL, 'Công nghệ Thông tin', 1, '2026-11-01 08:00:00'),
(3, 'PI03084', 'Đinh Nguyễn Phương Anh', 'phuonganh.pi03084@fpt.edu.vn', '$2y$10$h9RfZ18k19b91pEreGzHmejOcbGz4Z2u9y.2zVqZtq9Vz5a4F0Lte', '0923456789', 6, 'Media phóng sự, Chụp ảnh sự kiện, Hậu kỳ Premiere/Lightroom', '079203014589', 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=600&auto=format&fit=crop&q=80', 'MB Bank (Ngân hàng Quân Đội)', '098765432199', 'DINH NGUYEN PHUONG ANH', 'Thiết kế Đồ họa', 1, '2026-11-02 09:00:00'),
(4, 'PI03067', 'Lê Đức Mạnh', 'ducmanh.pi03067@fpt.edu.vn', '$2y$10$h9RfZ18k19b91pEreGzHmejOcbGz4Z2u9y.2zVqZtq9Vz5a4F0Lte', '0934567890', 4, 'Lễ tân đón tiếp, Hỗ trợ kỹ thuật Lab, Giao tiếp doanh nghiệp', '079204018721', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&auto=format&fit=crop&q=80', 'Vietcombank (VCB)', '102345678901', 'LE DUC MANH', 'Công nghệ Thông tin', 1, '2026-11-02 09:15:00'),
(5, 'PI03015', 'Hoàng Minh Luận', 'minhluan.pi03015@fpt.edu.vn', '$2y$10$h9RfZ18k19b91pEreGzHmejOcbGz4Z2u9y.2zVqZtq9Vz5a4F0Lte', '0945678901', 4, 'Setup âm thanh ánh sáng, Bàn ghế hội trường, Kỹ thuật mạng LAN', '079203019842', 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=600&auto=format&fit=crop&q=80', 'Techcombank (TCB)', '190345678912', 'HOANG MINH LUAN', 'Công nghệ Thông tin', 1, '2026-11-02 09:30:00'),
(6, 'PI02895', 'Dương Ngọc Hân', 'ngochan.pi02895@fpt.edu.vn', '$2y$10$h9RfZ18k19b91pEreGzHmejOcbGz4Z2u9y.2zVqZtq9Vz5a4F0Lte', '0956789012', 4, 'Hậu cần sự kiện, Check-in khách mời VIPs, Điều phối quà tặng', '079205012376', 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=600&auto=format&fit=crop&q=80', 'TPBank (Tiên Phong)', '03456789001', 'DUONG NGOC HAN', 'Digital Marketing', 1, '2026-11-02 09:45:00'),
(7, 'KT0105', 'Đặng Thu Thảo', 'thao.dt@fpt.edu.vn', '$2y$10$h9RfZ18k19b91pEreGzHmejOcbGz4Z2u9y.2zVqZtq9Vz5a4F0Lte', '0912888999', 5, 'Thẩm định hợp đồng nghiệm thu, Đối soát tài chính & Chi trả thù lao', NULL, NULL, NULL, NULL, NULL, 'Phòng Kế toán & Tài chính', 1, '2026-11-01 08:00:00'),
(8, 'HC0201', 'Lê Đình Tuấn', 'tuan.ld@fpt.edu.vn', '$2y$10$h9RfZ18k19b91pEreGzHmejOcbGz4Z2u9y.2zVqZtq9Vz5a4F0Lte', '0988777666', 3, 'Quản lý hội trường, Bàn giao máy chiếu âm thanh, Giám sát an ninh', NULL, NULL, NULL, NULL, NULL, 'Phòng Hành chính & Quản trị CSVC', 1, '2026-11-01 08:00:00'),
(13, 'HC0001', 'ThS. Phạm Hồng Sơn', 'son.ph@fpt.edu.vn', '$2y$10$h9RfZ18k19b91pEreGzHmejOcbGz4Z2u9y.2zVqZtq9Vz5a4F0Lte', '0908889999', 7, 'Phê duyệt quyết toán thù lao, Ký duyệt văn bản nhà trường', NULL, NULL, NULL, NULL, NULL, 'Ban Giám Hiệu & Hành Chính', 1, '2026-11-01 08:00:00'),
(16, 'GV0205', 'Cô Trần Thị Thùy Linh', 'linh.ttt@fpt.edu.vn', '$2y$10$h9RfZ18k19b91pEreGzHmejOcbGz4Z2u9y.2zVqZtq9Vz5a4F0Lte', '0912345588', 2, 'Chủ nhiệm bộ môn Đồ họa, Trưởng BTC Triển lãm FPoly Creative', NULL, NULL, NULL, NULL, NULL, 'Thiết kế Đồ họa', 1, '2026-11-01 08:00:00'),
(17, 'GV0912', 'ThS. Nguyễn Văn An', 'an.nv@fpt.edu.vn', '$2y$10$h9RfZ18k19b91pEreGzHmejOcbGz4Z2u9y.2zVqZtq9Vz5a4F0Lte', '0912345366', 2, 'Chủ nhiệm bộ môn Kinh tế, Trưởng BTC Diễn đàn Khởi nghiệp FPoly Biz', NULL, NULL, NULL, NULL, NULL, 'Quản trị Kinh doanh', 1, '2026-11-01 08:00:00'),
(18, 'GV0308', 'ThS. Vũ Hoàng Yến', 'yen.vh@fpt.edu.vn', '$2y$10$h9RfZ18k19b91pEreGzHmejOcbGz4Z2u9y.2zVqZtq9Vz5a4F0Lte', '0912345771', 2, 'Điều phối lễ tân ngoại giao, Tổ chức Hội thảo quốc tế', NULL, NULL, NULL, NULL, NULL, 'Du lịch - Khách sạn', 1, '2026-11-01 08:00:00'),
(37, 'PI03112', 'Trần Quang Khải', 'khaitq.pi03112@fpt.edu.vn', '$2y$10$h9RfZ18k19b91pEreGzHmejOcbGz4Z2u9y.2zVqZtq9Vz5a4F0Lte', '0981122334', 4, 'Bàn ghế, Thiết bị máy chiếu, Hậu cần sân khấu', '079203011223', 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=600&auto=format&fit=crop&q=80', 'Techcombank', '190367891234', 'TRAN QUANG KHAI', 'Công nghệ Thông tin', 1, '2026-11-03 10:00:00'),
(38, 'PI03225', 'Nguyễn Thảo My', 'mynt.pi03225@fpt.edu.vn', '$2y$10$h9RfZ18k19b91pEreGzHmejOcbGz4Z2u9y.2zVqZtq9Vz5a4F0Lte', '0982233445', 6, 'Quay video recap, Chụp ảnh chân dung đại biểu, Chỉnh màu Lightroom', '079203019876', 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=600&auto=format&fit=crop&q=80', 'Vietcombank', '1012345678', 'NGUYEN THAO MY', 'Thiết kế Đồ họa', 1, '2026-11-03 10:30:00');

-- 3. Locations
INSERT INTO `locations` (`id`, `name`, `address`, `capacity`, `status`) VALUES
(1, 'Hội trường Trịnh Văn Bô (Tòa T)', 'Cơ sở Công viên phần mềm Quang Trung, Q.12, TP.HCM', 500, 'Available'),
(2, 'Phòng Hội thảo Quốc tế (Phòng 301)', 'Tầng 3, Tòa nhà Innovation, CVPM Quang Trung, Q.12, TP.HCM', 120, 'Available'),
(3, 'Sân khấu Ngoài trời Central Yard', 'Khuôn viên trung tâm FPT Polytechnic, CVPM Quang Trung', 800, 'Available'),
(4, 'Phòng Lab Chuyên đề AI (Phòng 405)', 'Tầng 4, Tòa F, CVPM Quang Trung, Q.12, TP.HCM', 60, 'Available'),
(5, 'Phòng Studio & Multimedia Lab (Phòng 208)', 'Tầng 2, Tòa T, CVPM Quang Trung, Q.12, TP.HCM', 40, 'Available');

-- 4. Events
INSERT INTO `events` (`id`, `title`, `description`, `location_id`, `start_time`, `end_time`, `status`, `created_by`, `banner_url`, `created_at`, `department`) VALUES
(1, 'FPoly TechDay 2026: Trí Tuệ Nhân Tạo & Chuyển Đổi Số', 'Ngày hội công nghệ thường niên quy mô lớn nhất học kỳ, quy tụ hơn 20 doanh nghiệp công nghệ hàng đầu và chuỗi workshop AI Agent dành cho sinh viên IT.', 1, '2026-11-15 07:30:00', '2026-11-15 17:30:00', 'Published', 2, 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&auto=format&fit=crop&q=60', '2026-11-01 08:30:00', 'Công nghệ Thông tin'),
(2, 'Workshop: Làm Chủ PHP MVC & Kỷ Nguyên AI Vibe Coding', 'Buổi chia sẻ kỹ thuật chuyên sâu về kiến trúc PHP 8.2 OOP, kết nối an toàn PDO Prepared Statements và phương pháp lập trình AI có kiểm soát theo chuẩn môn WEB212.', 4, '2026-11-18 13:30:00', '2026-11-18 17:00:00', 'Published', 2, 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=800&auto=format&fit=crop&q=60', '2026-11-02 09:00:00', 'Công nghệ Thông tin'),
(3, 'Lễ Khai Mạc Giải Thể Thao Poly Champion 2026', 'Chuỗi giải đấu thể thao sinh viên Poly Champion với các môn bóng đá, bóng bàn và cầu lông tạo sân chơi gắn kết rèn luyện thể chất.', 3, '2026-11-20 07:00:00', '2026-11-20 11:30:00', 'Published', 1, 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=800&auto=format&fit=crop&q=60', '2026-11-03 10:00:00', 'Công tác Sinh viên'),
(4, 'Triển Lãm Đồ Họa & Thiết Kế Đa Phương Tiện FPoly Creative', 'Không gian triển lãm đồ án tốt nghiệp xuất sắc ngành Thiết kế Đồ họa, Trải nghiệm VR/AR và kết nối tuyển dụng doanh nghiệp sáng tạo.', 1, '2026-11-22 08:00:00', '2026-11-22 17:00:00', 'Published', 16, 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=800&auto=format&fit=crop&q=60', '2026-11-04 14:00:00', 'Thiết kế Đồ họa'),
(5, 'Diễn Đàn Khởi Nghiệp & Marketing Số FPoly Biz 2026', 'Diễn đàn giao lưu cùng các Founder Startup thành công, chia sẻ kỹ năng gọi vốn đầu tư và ứng dụng AI Marketing tối ưu doanh thu.', 2, '2026-11-25 08:00:00', '2026-11-25 16:30:00', 'Published', 17, 'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=800&auto=format&fit=crop&q=60', '2026-11-05 11:00:00', 'Quản trị Kinh doanh'),
(6, 'Ngày Hội Văn Hóa Du Lịch & Ẩm Thực FPoly Hospitality', 'Lễ hội ẩm thực ba miền và trình diễn nghệ thuật pha chế Bartender, quảng bá văn hóa du lịch do sinh viên ngành Nhà hàng Khách sạn thực hiện.', 3, '2026-11-28 08:00:00', '2026-11-28 15:30:00', 'Published', 18, 'https://images.unsplash.com/photo-1555244162-803834f70033?w=800&auto=format&fit=crop&q=60', '2026-11-06 15:00:00', 'Du lịch - Khách sạn');

-- 5. Shifts
INSERT INTO `shifts` (`id`, `event_id`, `name`, `start_time`, `end_time`, `target_qty`, `approved_qty`, `skill_required`, `points`, `remuneration_amount`, `shift_type`) VALUES
(1, 1, 'Ca 1: Setup Sân Khấu & Âm Thanh Ánh Sáng', '2026-11-15 06:30:00', '2026-11-15 09:30:00', 4, 3, 'Kỹ thuật, Thể lực', 15, 200000, 'FACILITY'),
(2, 1, 'Ca 2: Lễ Tân Đón Khách & Check-in Doanh Nghiệp', '2026-11-15 07:30:00', '2026-11-15 11:30:00', 6, 5, 'Giao tiếp, Lễ tân', 20, 180000, 'FACILITY'),
(3, 1, 'Ca 3: Media Phóng Sự Ảnh & Quay Phim Recap', '2026-11-15 08:00:00', '2026-11-15 12:00:00', 3, 2, 'Media, Chụp ảnh', 25, 250000, 'MEDIA'),
(4, 1, 'Ca 4: Điều Phối Workshop Chiều & Hậu Cần Bế Mạc', '2026-11-15 13:00:00', '2026-11-15 17:30:00', 5, 4, 'Hậu cần, Hỗ trợ', 20, 180000, 'FACILITY'),
(5, 2, 'Ca 1: Đón Tiếp Khách Mời & Hỗ Trợ Kỹ Thuật Lab', '2026-11-18 13:00:00', '2026-11-18 17:00:00', 3, 2, 'Kỹ thuật, Giao tiếp', 15, 150000, 'FACILITY'),
(6, 3, 'Ca 1: Lễ Tân Khai Mạc & Trọng Tài Bàn Thể Thao', '2026-11-20 06:30:00', '2026-11-20 11:30:00', 6, 4, 'Thể lực, Lễ tân', 20, 150000, 'FACILITY'),
(7, 3, 'Ca 2: Media Chụp Ảnh Khoảnh Khắc & Phóng Sự', '2026-11-20 07:30:00', '2026-11-20 11:30:00', 2, 1, 'Media, Thể thao', 20, 200000, 'MEDIA'),
(8, 4, 'Ca 1: Điều Phối Gian Trưng Bày & Hướng Dẫn Khách', '2026-11-22 07:30:00', '2026-11-22 12:00:00', 4, 2, 'Giao tiếp, Mỹ thuật', 20, 160000, 'FACILITY'),
(9, 4, 'Ca 2: Media Livestream & Phỏng Vấn Doanh Nghiệp', '2026-11-22 08:00:00', '2026-11-22 12:00:00', 2, 1, 'Media, Livestream', 25, 250000, 'MEDIA'),
(10, 4, 'Ca 3: Hậu Cần Bế Mạc & Trao Giải Thiết Kế', '2026-11-22 13:30:00', '2026-11-22 17:30:00', 4, 2, 'Hậu cần, Bàn ghế', 20, 160000, 'FACILITY'),
(11, 5, 'Ca 1: Check-in Doanh Nghiệp & Hậu Cần Teabreak', '2026-11-25 07:30:00', '2026-11-25 11:30:00', 4, 2, 'Lễ tân, Giao tiếp', 15, 150000, 'FACILITY'),
(12, 5, 'Ca 2: Hỗ Trợ Diễn Giả & Kỹ Thuật Máy Chiếu Lab', '2026-11-25 13:00:00', '2026-11-25 17:00:00', 3, 1, 'Kỹ thuật, Máy tính', 15, 150000, 'FACILITY'),
(13, 6, 'Ca 1: Chuẩn Bị Gian Hàng Ẩm Thực & Tiếp Đón', '2026-11-28 07:00:00', '2026-11-28 12:00:00', 6, 3, 'Ẩm thực, Lễ tân', 20, 160000, 'FACILITY'),
(14, 6, 'Ca 2: Media Ghi Hình Trình Diễn Bartender', '2026-11-28 08:00:00', '2026-11-28 12:00:00', 2, 1, 'Media, Quay phim', 20, 200000, 'MEDIA');

-- 6. Shift Assignments
INSERT INTO `shift_assignments` (`id`, `shift_id`, `user_id`, `status`, `assigned_at`, `accounting_status`, `accounting_note`, `proof_image_url`, `proof_note`, `proof_submitted_at`, `lecturer_verified`, `lecturer_name`, `lecturer_verified_at`, `contract_code`, `school_status`, `ctv_type`, `proof_drive_url`) VALUES
(1, 3, 3, 'Approved', '2026-11-08 14:00:00', 'Paid', NULL, 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=800&auto=format&fit=crop&q=80', 'Bộ ảnh phóng sự 120 ảnh gốc high-res và 01 video clip recap 3 phút đã chỉnh màu FPoly và chèn logo chuẩn nhận diện.', '2026-11-15 12:10:00', 1, 'Thầy Phan Hoàng Khải (CNBM Ứng dụng PM)', '2026-11-15 12:30:00', 'HDNT-2026-FPLY-001', 'Signed_Paid', 'MEDIA', 'https://drive.google.com/drive/folders/1FpolyRecapMedia2026_TechDay_Highlight'),
(2, 2, 4, 'Approved', '2026-11-08 14:15:00', 'Paid', NULL, 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&auto=format&fit=crop&q=80', 'Có mặt lúc 07:15, đón tiếp hơn 300 sinh viên và đại biểu doanh nghiệp tại sảnh chính Tòa T.', '2026-11-15 11:45:00', 1, 'Thầy Phan Hoàng Khải (CNBM Ứng dụng PM)', '2026-11-15 12:15:00', 'HDNT-2026-FPLY-002', 'Signed_Paid', 'FACILITY', NULL),
(3, 1, 5, 'Approved', '2026-11-08 14:30:00', 'Paid', NULL, 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=800&auto=format&fit=crop&q=80', 'Hoàn tất setup 500 ghế hội trường, căn chỉnh 4 micro sân khấu và máy chiếu trung tâm trước giờ khai mạc.', '2026-11-15 09:35:00', 1, 'Thầy Phan Hoàng Khải (CNBM Ứng dụng PM)', '2026-11-15 10:00:00', 'HDNT-2026-FPLY-003', 'Signed_Paid', 'FACILITY', NULL),
(4, 4, 6, 'Approved', '2026-11-08 15:00:00', 'Paid', NULL, 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=800&auto=format&fit=crop&q=80', 'Hỗ trợ phát tài liệu workshop, điều phối teabreak 50 đại biểu và dọn dẹp hội trường sau bế mạc.', '2026-11-15 17:35:00', 1, 'Cô Trần Thị Thùy Linh (Bộ môn Đồ họa)', '2026-11-15 17:45:00', 'HDNT-2026-FPLY-004', 'Signed_Paid', 'FACILITY', NULL),
(5, 6, 37, 'Approved', '2026-11-10 09:00:00', 'Paid', NULL, 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=800&auto=format&fit=crop&q=80', 'Ghi nhận tỷ số các trận bóng bàn và điều phối lịch thi đấu vòng loại sáng 20/11 đúng kế hoạch.', '2026-11-20 11:35:00', 1, 'Quản Trị Viên Hệ Thống', '2026-11-20 12:00:00', 'HDNT-2026-FPLY-005', 'Signed_Paid', 'FACILITY', NULL),
(6, 7, 38, 'Approved', '2026-11-10 10:00:00', 'Paid', NULL, 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=800&auto=format&fit=crop&q=80', 'Bộ ảnh khoảnh khắc thi đấu bóng đá và trao giải bóng bàn, đã lọc 150 ảnh đẹp gắn logo FPoly.', '2026-11-20 11:40:00', 1, 'Quản Trị Viên Hệ Thống', '2026-11-20 12:15:00', 'HDNT-2026-FPLY-006', 'Signed_Paid', 'MEDIA', 'https://drive.google.com/drive/folders/fpoly-sports-champion-2026'),
(7, 9, 3, 'Approved', '2026-11-12 11:00:00', 'Approved', NULL, 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=800&auto=format&fit=crop&q=80', 'Đã hoàn thành livestream phiên sáng và tổng hợp 80 ảnh tác phẩm triển lãm vào thư mục Drive chất lượng cao.', '2026-11-22 12:15:00', 1, 'Cô Trần Thị Thùy Linh (Bộ môn Đồ họa)', '2026-11-22 13:00:00', 'HDNT-2026-FPLY-007', 'Approved', 'MEDIA', 'https://drive.google.com/drive/folders/fpoly-creative-2026-media-raw'),
(8, 5, 5, 'Approved', '2026-11-12 14:00:00', 'Pending', NULL, 'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=800&auto=format&fit=crop&q=80', 'Đã cài đặt sẵn Composer, PHP 8.2 và SQLite trên 30 máy trạm phòng Lab 405 trước giờ diễn ra.', '2026-11-18 17:05:00', 0, NULL, NULL, NULL, 'Pending', 'FACILITY', NULL);

-- 7. Check-ins
INSERT INTO `check_ins` (`id`, `assignment_id`, `check_in_time`, `method`, `status`) VALUES
(1, 1, '2026-11-15 07:55:00', 'DynamicQR', 'Present'),
(2, 2, '2026-11-15 07:12:00', 'DynamicQR', 'Present'),
(3, 3, '2026-11-15 06:25:00', 'DynamicQR', 'Present');

-- 8. Drive Submissions
INSERT INTO `drive_submissions` (`id`, `assignment_id`, `drive_url`, `summary`, `status`, `submitted_at`, `reviewed_at`) VALUES
(1, 1, 'https://drive.google.com/drive/folders/1FpolyRecapMedia2026_TechDay_Highlight', 'Thư mục ảnh phóng sự 120 ảnh đã chỉnh sáng và 01 video clip recap 3 phút FPoly TechDay 2026', 'Accepted', '2026-11-15 12:10:00', '2026-11-15 12:30:00'),
(2, 6, 'https://drive.google.com/drive/folders/fpoly-sports-champion-2026', 'Thư mục ảnh thi đấu thể thao Poly Champion 2026 gồm 150 ảnh đã gắn logo FPoly', 'Accepted', '2026-11-20 11:40:00', '2026-11-20 12:15:00'),
(3, 7, 'https://drive.google.com/drive/folders/fpoly-creative-2026-media-raw', 'Thư mục Drive tác phẩm triển lãm và file ghi hình livestream phiên sáng FPoly Creative', 'Accepted', '2026-11-22 12:15:00', '2026-11-22 13:00:00');

-- 9. Calendar Notes
INSERT INTO `calendar_notes` (`id`, `event_id`, `date`, `title`, `content`, `author`, `color`, `type`, `is_public`, `created_at`) VALUES
(1, NULL, '2026-11-15', 'Nhắc nhở: Mang thẻ CTV & Áo cam FPoly', 'Có mặt lúc 06:45 tại Hội trường T để tổng duyệt âm thanh và kiểm tra backdrop sự kiện TechDay.', 'Thầy Phan Hoàng Khải', '#EA580C', 'EVENT_REMINDER', 1, '2026-11-13 07:30:00'),
(2, NULL, '2026-11-15', 'Hạn chót nộp ảnh Google Drive phóng sự', 'Đội Media nộp thư mục ảnh gốc và video highlight trong vòng 24 giờ sau khi kết thúc sự kiện.', 'Đinh Nguyễn Phương Anh', '#0284C7', 'DEADLINE', 1, '2026-11-13 07:30:00'),
(3, NULL, '2026-11-18', 'Kiểm tra đường truyền cáp LAN phòng Lab 405', 'Cán bộ CSVC bàn giao switch mạng Gigabit tại phòng Lab 405 trước 13:00 chiều phục vụ workshop.', 'Lê Đình Tuấn (Hành chính)', '#059669', 'FACILITY_NOTE', 1, '2026-11-13 07:30:00'),
(4, NULL, '2026-11-20', 'Phổ biến điều lệ Giải thể thao Poly Champion', 'Các trọng tài bàn và CTV phụ trách sân bãi có mặt lúc 06:15 nhận áo bib và biên bản thi đấu.', 'Ban Tổ Chức', '#D97706', 'MEETING', 1, '2026-11-13 07:30:00'),
(5, NULL, '2026-11-25', 'Quyết toán thù lao đợt 1 tháng 11/2026', 'Phòng Kế toán hoàn tất đối soát hồ sơ và chuyển khoản thù lao các ca trực TechDay và Workshop.', 'Đặng Thu Thảo (Kế toán)', '#DC2626', 'DEADLINE', 1, '2026-11-13 07:30:00');

-- 10. Activity Logs
INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `user_role`, `action`, `target_type`, `target_id`, `details`, `ip_address`, `created_at`) VALUES
(1, 13, 'ThS. Phạm Hồng Sơn', 'Head_Admin', 'APPROVE_PAYMENT', 'shift_assignment', 6, 'Cán bộ ThS. Phạm Hồng Sơn đã ký duyệt hợp đồng nghiệm thu HDNT-2026-FPLY-006 và phê duyệt chuyển khoản thù lao 200,000 đ cho CTV Nguyễn Thảo My.', '127.0.0.1', '2026-11-20 14:30:00'),
(2, 7, 'Đặng Thu Thảo', 'Accountant', 'APPROVE_PAYMENT', 'shift_assignment', 5, 'Kế toán Đặng Thu Thảo đối soát thông tin VNeID và trình Ban Giám Hiệu phê duyệt hợp đồng HDNT-2026-FPLY-005 cho CTV Trần Quang Khải.', '127.0.0.1', '2026-11-20 12:30:00'),
(3, 16, 'Cô Trần Thị Thùy Linh', 'Organizer', 'VERIFY_PROOF', 'shift_assignment', 7, 'Giảng viên Cô Trần Thị Thùy Linh đã thẩm định minh chứng Drive và khởi tạo hợp đồng HDNT-2026-FPLY-007 cho CTV Đinh Nguyễn Phương Anh.', '127.0.0.1', '2026-11-22 13:00:00'),
(4, 2, 'Thầy Phan Hoàng Khải', 'Organizer', 'VERIFY_PROOF', 'shift_assignment', 1, 'Giảng viên Thầy Phan Hoàng Khải đã nghiệm thu minh chứng Media ca TechDay và khởi tạo hợp đồng HDNT-2026-FPLY-001.', '127.0.0.1', '2026-11-15 12:30:00'),
(5, 13, 'ThS. Phạm Hồng Sơn', 'Head_Admin', 'APPROVE_PAYMENT', 'shift_assignment', 1, 'Nhà trường ký duyệt thanh toán thù lao ca Media sự kiện TechDay qua tài khoản MB Bank số 098765432199.', '127.0.0.1', '2026-11-15 15:00:00'),
(6, 1, 'Quản Trị Viên Hệ Thống', 'Admin', 'GRANT_ROLE', 'USER', 3, 'Quản trị viên đã cấp quyền vai trò [Media - CTV Nhiếp ảnh & Sản xuất nội dung] cho tài khoản Đinh Nguyễn Phương Anh (MSSV: PI03084).', '127.0.0.1', '2026-11-14 09:30:00'),
(7, 3, 'Đinh Nguyễn Phương Anh', 'Media', 'UPDATE_PROFILE', 'USER', 3, 'Sinh viên Đinh Nguyễn Phương Anh cập nhật số tài khoản ngân hàng MB Bank và tải ảnh xác thực VNeID Mức 2.', '127.0.0.1', '2026-11-14 10:15:00'),
(8, 2, 'Thầy Phan Hoàng Khải', 'Organizer', 'CREATE_EVENT', 'EVENT', 1, 'Đăng tải sự kiện FPoly TechDay 2026: Trí Tuệ Nhân Tạo & Chuyển Đổi Số lên hệ thống và phân rã 4 ca trực.', '127.0.0.1', '2026-11-01 08:30:00'),
(9, 8, 'Lê Đình Tuấn', 'Staff', 'CREATE_NOTE', 'CALENDAR_NOTE', 3, 'Tạo ghi chú cơ sở vật chất: Bàn giao hệ thống switch cáp mạng LAN phòng Lab 405 phục vụ workshop.', '127.0.0.1', '2026-11-13 07:30:00'),
(10, 1, 'Quản Trị Viên Hệ Thống', 'Admin', 'CREATE_NOTE', 'CALENDAR_NOTE', 1, 'Tạo thông báo lịch hẹn toàn trường: Nhắc nhở trang phục áo cam và tác phong tham gia ca trực TechDay.', '127.0.0.1', '2026-11-13 07:30:00');
