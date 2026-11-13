-- =====================================================================
-- DỰ ÁN MÔN HỌC: PHÁT TRIỂN DỰ ÁN WEBSITE VỚI AI (WEB212)
-- ĐỀ TÀI: HỆ THỐNG QUẢN TRỊ SỰ KIỆN VÀ ĐIỀU PHỐI CTV TẠI FPOLY (POLYMEE)
-- SCRIPT CƠ SỞ DỮ LIỆU MYSQL / MARIADB CHUẨN 3NF (CHO BÀI NỘP LMS)
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `polymee_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `polymee_db`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `drive_submissions`;
DROP TABLE IF EXISTS `check_ins`;
DROP TABLE IF EXISTS `shift_assignments`;
DROP TABLE IF EXISTS `shifts`;
DROP TABLE IF EXISTS `events`;
DROP TABLE IF EXISTS `locations`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `roles`;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. BẢNG ROLES (Vai trò phân quyền)
CREATE TABLE `roles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `description` VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. BẢNG USERS (Tài khoản người dùng)
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
    `bank_account_number` VARCHAR(30) NULL,
    `bank_name` VARCHAR(100) NULL,
    `bank_account_name` VARCHAR(100) NULL,
    `vneid_image_url` VARCHAR(500) NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_users_roles` FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. BẢNG LOCATIONS (Địa điểm & Hội trường)
CREATE TABLE `locations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `address` VARCHAR(255) NOT NULL,
    `capacity` INT NOT NULL DEFAULT 50,
    `status` VARCHAR(20) DEFAULT 'Available' -- Available, Maintenance, Booked
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. BẢNG EVENTS (Sự kiện FPoly)
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
    `remuneration_amount` DECIMAL(12,2) DEFAULT 150000.00,
    CONSTRAINT `fk_shifts_event` FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. BẢNG SHIFT_ASSIGNMENTS (Đăng ký & Phân công CTV vào ca trực)
CREATE TABLE `shift_assignments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `shift_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `status` VARCHAR(20) DEFAULT 'Approved', -- Pending, Approved, Waitlist, Cancelled
    `remuneration_amount` DECIMAL(12,2) DEFAULT 150000.00,
    `accounting_status` VARCHAR(20) DEFAULT 'Pending', -- Pending, Approved, Paid
    `assigned_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `proof_image_url` VARCHAR(500) NULL, -- Ảnh chụp hiện trường có mặt của CTV
    `proof_note` TEXT NULL, -- Ghi chú vị trí & công việc hiện trường
    `proof_submitted_at` DATETIME NULL,
    `lecturer_verified` TINYINT(1) DEFAULT 0, -- Giảng viên thẩm định nghiệm thu
    `lecturer_name` VARCHAR(100) NULL, -- Tên Giảng viên xác nhận
    `lecturer_verified_at` DATETIME NULL,
    `contract_code` VARCHAR(50) NULL, -- Số Hợp đồng / Biên bản nghiệm thu (HDNT-...)
    `school_status` VARCHAR(30) DEFAULT 'Pending', -- Pending, Approved, Signed_Paid
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

-- 8. BẢNG DRIVE_SUBMISSIONS (Nghiệm thu minh chứng Google Drive của CTV)
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
    `target_date` DATE NOT NULL,
    `note_type` VARCHAR(50) NOT NULL DEFAULT 'EVENT_REMINDER', -- EVENT_REMINDER, DEADLINE, FACILITY_NOTE, MEETING
    `content` TEXT NOT NULL,
    `creator_id` INT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_notes_user` FOREIGN KEY (`creator_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- NẠP DỮ LIỆU MẪU (SEED DATA KIỂM THỬ)
-- =====================================================================

-- 1. Roles
INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'Admin', 'Quản trị viên toàn quyền hệ thống'),
(2, 'Organizer', 'Giảng viên và Ban tổ chức sự kiện'),
(3, 'Staff', 'Cán bộ giám sát và điều phối hiện trường'),
(4, 'Student', 'Sinh viên FPoly / Cộng tác viên sự kiện'),
(5, 'Accountant', 'Kế toán & Quyết toán thù lao CTV'),
(6, 'Media', 'CTV Nhiếp ảnh & Sản xuất nội dung truyền thông');

-- 2. Users (Mật khẩu mặc định: '123456' băm BCrypt: $2y$10$wT0... hoặc băm chuẩn)
INSERT INTO `users` (`id`, `student_code`, `full_name`, `email`, `password_hash`, `phone`, `role_id`, `skills`, `id_card_number`, `bank_account_number`, `bank_name`, `bank_account_name`, `vneid_image_url`) VALUES
(1, 'ADMIN001', 'Quản Trị Viên Hệ Thống', 'admin@fpoly.edu.vn', '$2y$10$h9RfZ18k19b91pEreGzHmejOcbGz4Z2u9y.2zVqZtq9Vz5a4F0Lte', '0901234567', 1, 'Quản trị, Điều phối', '079201009999', '19036789012011', 'Techcombank', 'NGUYEN VAN QUAN TRI', NULL),
(2, 'GV0102', 'Thầy Phan Hoàng Khải', 'khai.phan@fpt.edu.vn', '$2y$10$h9RfZ18k19b91pEreGzHmejOcbGz4Z2u9y.2zVqZtq9Vz5a4F0Lte', '0912345678', 2, 'Tổ chức, Đào tạo', '079201008888', '1023889977', 'Vietcombank', 'PHAN HOANG KHAI', NULL),
(3, 'PI03084', 'Đinh Nguyễn Phương Anh', 'phuonganh.pi03084@fpt.edu.vn', '$2y$10$h9RfZ18k19b91pEreGzHmejOcbGz4Z2u9y.2zVqZtq9Vz5a4F0Lte', '0923456789', 4, 'Media, Chụp ảnh, Quay phim', '079203014589', '1029384756', 'Vietcombank', 'DINH NGUYEN PHUONG ANH', 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=600&auto=format&fit=crop&q=80'),
(4, 'PS20262', 'Lê Đức Mạnh', 'ducmanh.ps20262@fpt.edu.vn', '$2y$10$h9RfZ18k19b91pEreGzHmejOcbGz4Z2u9y.2zVqZtq9Vz5a4F0Lte', '0934567890', 4, 'Lễ tân, Giao tiếp, Setup', '079203018899', '1903847561', 'Techcombank', 'LE DUC MANH', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&auto=format&fit=crop&q=80'),
(5, 'PS20263', 'Hoàng Minh Luận', 'minhluan.ps20263@fpt.edu.vn', '$2y$10$h9RfZ18k19b91pEreGzHmejOcbGz4Z2u9y.2zVqZtq9Vz5a4F0Lte', '0945678901', 4, 'Kỹ thuật âm thanh, Ánh sáng', '079203017766', '450100056789', 'BIDV', 'HOANG MINH LUAN', 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=600&auto=format&fit=crop&q=80'),
(6, 'PS20264', 'Dương Ngọc Hân', 'ngochan.ps20264@fpt.edu.vn', '$2y$10$h9RfZ18k19b91pEreGzHmejOcbGz4Z2u9y.2zVqZtq9Vz5a4F0Lte', '0956789012', 4, 'Hậu cần, Check-in, Hỗ trợ', '079203016655', '038100056789', 'MB Bank', 'DUONG NGOC HAN', 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=600&auto=format&fit=crop&q=80'),
(7, 'KT0105', 'Đặng Thu Thảo', 'thao.dang@fpt.edu.vn', '$2y$10$h9RfZ18k19b91pEreGzHmejOcbGz4Z2u9y.2zVqZtq9Vz5a4F0Lte', '0967890123', 5, 'Kế toán & Quyết toán', '079201005555', '9999888877', 'TPBank', 'DANG THU THAO', NULL),
(8, 'HC0201', 'Lê Đình Tuấn', 'tuan.le@fpt.edu.vn', '$2y$10$h9RfZ18k19b91pEreGzHmejOcbGz4Z2u9y.2zVqZtq9Vz5a4F0Lte', '0978901234', 3, 'Quản trị cơ sở vật chất & Khảo sát', '079201004444', '8888777766', 'ACB', 'LE DINH TUAN', NULL);

-- 3. Locations
INSERT INTO `locations` (`id`, `name`, `address`, `capacity`, `status`) VALUES
(1, 'Hội trường Trịnh Văn Bô (Tòa T)', 'Cơ sở Công viên phần mềm Quang Trung, Q.12, TP.HCM', 500, 'Available'),
(2, 'Phòng Hội thảo Quốc tế (Phòng 301)', 'Tầng 3, Tòa nhà Innovation, Q.12, TP.HCM', 120, 'Available'),
(3, 'Sân khấu Ngoài trời Central Yard', 'Khuôn viên trung tâm FPT Polytechnic', 800, 'Available'),
(4, 'Phòng Lab Chuyên đề AI (Phòng 405)', 'Tầng 4, Tòa F, Q.12, TP.HCM', 60, 'Available');

-- 4. Events
INSERT INTO `events` (`id`, `title`, `description`, `location_id`, `start_time`, `end_time`, `status`, `created_by`, `banner_url`) VALUES
(1, 'FPoly TechDay 2026: Trí Tuệ Nhân Tạo & Chuyển Đổi Số', 'Ngày hội công nghệ thường niên quy mô lớn nhất học kỳ, quy tụ hơn 20 doanh nghiệp công nghệ hàng đầu và chuỗi workshop AI Agent.', 1, '2026-11-15 07:30:00', '2026-11-15 17:30:00', 'Published', 2, 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&auto=format&fit=crop&q=60'),
(2, 'Workshop: Làm Chủ PHP MVC & Kỷ Nguyên AI Vibe Coding', 'Buổi chia sẻ kỹ thuật chuyên sâu về kiến trúc PHP OOP, kết nối an toàn PDO Prepared Statements và phương pháp lập trình AI có kiểm soát.', 2, '2026-11-18 13:30:00', '2026-11-18 17:00:00', 'Published', 2, 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=800&auto=format&fit=crop&q=60'),
(3, 'Lễ Khai Mạc Giải Thể Thao FPoly Vô Địch 2026', 'Chuỗi giải đấu thể thao sinh viên Poly Champion với các môn bóng đá, bóng bàn và cầu lông.', 3, '2026-11-20 07:00:00', '2026-11-20 11:30:00', 'Published', 1, 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=800&auto=format&fit=crop&q=60');

-- 5. Shifts
INSERT INTO `shifts` (`id`, `event_id`, `name`, `start_time`, `end_time`, `target_qty`, `approved_qty`, `skill_required`, `points`, `remuneration_amount`) VALUES
(1, 1, 'Ca 1: Setup Sân Khấu & Âm Thanh Ánh Sáng', '2026-11-15 06:30:00', '2026-11-15 09:30:00', 4, 3, 'Kỹ thuật, Thể lực', 15, 200000.00),
(2, 1, 'Ca 2: Lễ Tân Đón Khách & Check-in Doanh Nghiệp', '2026-11-15 07:30:00', '2026-11-15 11:30:00', 6, 5, 'Giao tiếp, Lễ tân', 20, 180000.00),
(3, 1, 'Ca 3: Media Phóng Sự Ảnh & Quay Phim Sự Kiện', '2026-11-15 08:00:00', '2026-11-15 12:00:00', 3, 2, 'Media, Chụp ảnh', 25, 250000.00),
(4, 1, 'Ca 4: Điều Phối Workshop Chiều & Hậu Cần Bế Mạc', '2026-11-15 13:00:00', '2026-11-15 17:30:00', 5, 2, 'Hậu cần, Hỗ trợ', 20, 180000.00),
(5, 2, 'Ca 1: Đón Tiếp Khách Mời & Hỗ Trợ Kỹ Thuật Lab', '2026-11-18 13:00:00', '2026-11-18 17:00:00', 3, 1, 'Kỹ thuật, Giao tiếp', 15, 150000.00),
(6, 3, 'Ca 1: Lễ Tân Khai Mạc & Trọng Tài Bàn Thể Thao', '2026-11-20 06:30:00', '2026-11-20 11:30:00', 8, 4, 'Thể lực, Lễ tân', 20, 150000.00);

-- 6. Shift Assignments
INSERT INTO `shift_assignments` (`id`, `shift_id`, `user_id`, `status`, `remuneration_amount`, `accounting_status`) VALUES
(1, 3, 3, 'Approved', 250000.00, 'Pending'), -- Phương Anh nhận ca Media sự kiện TechDay
(2, 2, 4, 'Approved', 180000.00, 'Approved'), -- Lê Đức Mạnh nhận ca Lễ tân TechDay
(3, 1, 5, 'Approved', 200000.00, 'Paid'),     -- Hoàng Minh Luận nhận ca Setup TechDay
(4, 5, 6, 'Approved', 150000.00, 'Pending');  -- Dương Ngọc Hân nhận ca Workshop

-- 7. Check-ins
INSERT INTO `check_ins` (`id`, `assignment_id`, `check_in_time`, `method`, `status`) VALUES
(1, 1, '2026-11-15 07:55:00', 'DynamicQR', 'Present'),
(2, 2, '2026-11-15 07:28:00', 'DynamicQR', 'Present');

-- 8. Drive Submissions
INSERT INTO `drive_submissions` (`id`, `assignment_id`, `drive_url`, `summary`, `status`) VALUES
(1, 1, 'https://drive.google.com/drive/folders/1A2B3C4D5E6F_FPolyTechDayPhotos', 'Đã chụp xong 450 ảnh RAW + 120 ảnh đã chỉnh sửa màu tone FPoly và 1 video recap 60s', 'Accepted');

-- 9. Calendar Notes (Apple Memos)
INSERT INTO `calendar_notes` (`id`, `target_date`, `note_type`, `content`, `creator_id`) VALUES
(1, '2026-11-15', 'EVENT_REMINDER', 'Nhắc nhở toàn bộ CTV có mặt trước 15 phút tại sảnh Hội trường T. Đồng phục áo cam FPoly chuẩn chỉ.', 2),
(2, '2026-11-15', 'FACILITY_NOTE', 'Đã nghiệm thu và bàn giao 2 micro không dây + 1 máy chiếu Epson cho phòng Hội thảo Quốc tế 301.', 8),
(3, '2026-11-16', 'DEADLINE', 'Hạn chót các nhóm Media nộp thư mục Google Drive ảnh recap và video tổng kết sự kiện TechDay.', 2),
(4, '2026-11-17', 'DEADLINE', 'Kế toán đối soát và chốt danh sách quyết toán thù lao đợt 1 tháng 11/2026.', 7),
(5, '2026-11-18', 'MEETING', 'Họp rút kinh nghiệm tổ chức TechDay và phân công nhân sự chuẩn bị Giải vô địch thể thao FPoly.', 1);
