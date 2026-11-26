-- =====================================================================
-- DỰ ÁN MÔN HỌC: PHÁT TRIỂN DỰ ÁN WEBSITE VỚI AI (WEB212)
-- SCRIPT CƠ SỞ DỮ LIỆU MICROSOFT SQL SERVER 2022 (T-SQL) CHUẨN 3NF
-- =====================================================================

IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = 'Polymeet')
BEGIN
    CREATE DATABASE Polymeet COLLATE Vietnamese_CI_AS;
END;
GO

USE Polymeet;
GO

-- XÓA BẢNG NẾU ĐÃ TỒN TẠI THEO THỨ TỰ RÀNG BUỘC
IF OBJECT_ID('drive_submissions', 'U') IS NOT NULL DROP TABLE drive_submissions;
IF OBJECT_ID('check_ins', 'U') IS NOT NULL DROP TABLE check_ins;
IF OBJECT_ID('shift_assignments', 'U') IS NOT NULL DROP TABLE shift_assignments;
IF OBJECT_ID('shifts', 'U') IS NOT NULL DROP TABLE shifts;
IF OBJECT_ID('events', 'U') IS NOT NULL DROP TABLE events;
IF OBJECT_ID('locations', 'U') IS NOT NULL DROP TABLE locations;
IF OBJECT_ID('users', 'U') IS NOT NULL DROP TABLE users;
IF OBJECT_ID('roles', 'U') IS NOT NULL DROP TABLE roles;
GO

-- 1. BẢNG ROLES
CREATE TABLE roles (
    id INT IDENTITY(1,1) PRIMARY KEY,
    name NVARCHAR(50) NOT NULL UNIQUE,
    description NVARCHAR(255) NULL
);

-- 2. BẢNG USERS
CREATE TABLE users (
    id INT IDENTITY(1,1) PRIMARY KEY,
    student_code NVARCHAR(20) NOT NULL UNIQUE,
    full_name NVARCHAR(100) NOT NULL,
    email NVARCHAR(150) NOT NULL UNIQUE,
    password_hash NVARCHAR(255) NOT NULL,
    phone NVARCHAR(15) NULL,
    role_id INT NOT NULL,
    skills NVARCHAR(MAX) NULL,
    is_active BIT DEFAULT 1,
    created_at DATETIME2 DEFAULT SYSUTCDATETIME(),
    CONSTRAINT fk_users_roles FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- 3. BẢNG LOCATIONS
CREATE TABLE locations (
    id INT IDENTITY(1,1) PRIMARY KEY,
    name NVARCHAR(100) NOT NULL,
    address NVARCHAR(255) NOT NULL,
    capacity INT NOT NULL DEFAULT 50,
    status NVARCHAR(20) DEFAULT 'Available'
);

-- 4. BẢNG EVENTS
CREATE TABLE events (
    id INT IDENTITY(1,1) PRIMARY KEY,
    title NVARCHAR(200) NOT NULL,
    description NVARCHAR(MAX) NULL,
    location_id INT NOT NULL,
    start_time DATETIME2 NOT NULL,
    end_time DATETIME2 NOT NULL,
    status NVARCHAR(20) DEFAULT 'Published',
    created_by INT NOT NULL,
    banner_url NVARCHAR(255) NULL,
    created_at DATETIME2 DEFAULT SYSUTCDATETIME(),
    CONSTRAINT fk_events_location FOREIGN KEY (location_id) REFERENCES locations(id),
    CONSTRAINT fk_events_user FOREIGN KEY (created_by) REFERENCES users(id)
);

-- 5. BẢNG SHIFTS
CREATE TABLE shifts (
    id INT IDENTITY(1,1) PRIMARY KEY,
    event_id INT NOT NULL,
    name NVARCHAR(100) NOT NULL,
    start_time DATETIME2 NOT NULL,
    end_time DATETIME2 NOT NULL,
    target_qty INT NOT NULL DEFAULT 5,
    approved_qty INT NOT NULL DEFAULT 0,
    skill_required NVARCHAR(100) NULL,
    points INT NOT NULL DEFAULT 10,
    CONSTRAINT fk_shifts_event FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

-- 6. BẢNG SHIFT_ASSIGNMENTS
CREATE TABLE shift_assignments (
    id INT IDENTITY(1,1) PRIMARY KEY,
    shift_id INT NOT NULL,
    user_id INT NOT NULL,
    status NVARCHAR(20) DEFAULT 'Approved',
    assigned_at DATETIME2 DEFAULT SYSUTCDATETIME(),
    CONSTRAINT fk_assignments_shift FOREIGN KEY (shift_id) REFERENCES shifts(id) ON DELETE CASCADE,
    CONSTRAINT fk_assignments_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT uq_shift_user UNIQUE (shift_id, user_id)
);

-- 7. BẢNG CHECK_INS
CREATE TABLE check_ins (
    id INT IDENTITY(1,1) PRIMARY KEY,
    assignment_id INT NOT NULL,
    check_in_time DATETIME2 DEFAULT SYSUTCDATETIME(),
    check_out_time DATETIME2 NULL,
    method NVARCHAR(20) DEFAULT 'DynamicQR',
    status NVARCHAR(20) DEFAULT 'Present',
    CONSTRAINT fk_checkins_assignment FOREIGN KEY (assignment_id) REFERENCES shift_assignments(id) ON DELETE CASCADE
);

-- 8. BẢNG DRIVE_SUBMISSIONS
CREATE TABLE drive_submissions (
    id INT IDENTITY(1,1) PRIMARY KEY,
    assignment_id INT NOT NULL,
    drive_url NVARCHAR(500) NOT NULL,
    summary NVARCHAR(MAX) NULL,
    status NVARCHAR(20) DEFAULT 'Pending',
    submitted_at DATETIME2 DEFAULT SYSUTCDATETIME(),
    reviewed_at DATETIME2 NULL,
    CONSTRAINT fk_submissions_assignment FOREIGN KEY (assignment_id) REFERENCES shift_assignments(id) ON DELETE CASCADE
);
GO
