# PolyMee Web Application - Giai Đoạn 1 Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Xây dựng hoàn chỉnh khung mã nguồn dự án PolyMee theo chuẩn kiến trúc PHP 8.2 OOP, Mô hình MVC, kết nối PDO, CSDL quan hệ 8 bảng, cùng giao diện Responsive Tailwind CSS, Đăng ký ca trực chống trùng lịch và Trợ lý ảo AI PolyMee phục vụ nghiệm thu Assignment Giai đoạn 1 (Buổi 1-6) môn WEB212.

**Architecture:** Áp dụng mô hình MVC với Single Entry Point (`public/index.php`) và bộ định tuyến tập trung (`core/App.php`). Tầng dữ liệu sử dụng PDO Singleton (`core/Database.php`) hỗ trợ linh hoạt cả SQLite tự động và MySQL. Tầng dịch vụ (`CollisionService`, `DynamicQrHelper`) xử lý logic thuật toán xếp lịch và chữ ký số HMAC-SHA256. Giao diện người dùng sử dụng Tailwind CSS kết hợp JavaScript tương tác Ajax thời gian thực.

**Tech Stack:** PHP 8.2+ OOP, PDO (MySQL & SQLite), Apache mod_rewrite, Tailwind CSS, Google Gemini API Function Calling Schema, HMAC-SHA256.

---

### Task 1: Core MVC Infrastructure & Configuration
**Files:**
- Create: `config/config.php`
- Create: `config/database.php`
- Create: `core/Database.php`
- Create: `core/Controller.php`
- Create: `core/App.php`
- Create: `public/index.php`
- Create: `public/.htaccess`

- [ ] **Step 1: Create config files (`config/config.php`, `config/database.php`)**
- [ ] **Step 2: Implement Database connection singleton with PDO (`core/Database.php`)**
- [ ] **Step 3: Implement Core Controller base class (`core/Controller.php`)**
- [ ] **Step 4: Implement Router dispatcher (`core/App.php`)**
- [ ] **Step 5: Implement Single Entry Point (`public/index.php`) and `.htaccess`**
- [ ] **Step 6: Verify routing works with a simple test controller**

---

### Task 2: Database Schemas & Seed Data Generator
**Files:**
- Create: `database/schema_mysql.sql`
- Create: `database/schema_sqlserver.sql`
- Create: `database/seed_data.php`

- [ ] **Step 1: Write MySQL DDL schema (`database/schema_mysql.sql`) with 8 relational tables and constraints**
- [ ] **Step 2: Write Microsoft SQL Server 2022 DDL schema (`database/schema_sqlserver.sql`)**
- [ ] **Step 3: Implement database seeder (`database/seed_data.php`) with rich realistic FPoly events, shifts, locations, and users**
- [ ] **Step 4: Execute seeder to generate `database/polymee.sqlite` for instant local execution**

---

### Task 3: Models & Business Services (OOP Backend)
**Files:**
- Create: `app/models/BaseModel.php`
- Create: `app/models/UserModel.php`
- Create: `app/models/EventModel.php`
- Create: `app/models/ShiftModel.php`
- Create: `app/models/DriveSubmissionModel.php`
- Create: `app/services/CollisionService.php`
- Create: `app/helpers/DynamicQrHelper.php`
- Create: `app/helpers/SessionHelper.php`

- [ ] **Step 1: Implement `BaseModel.php` with reusable PDO CRUD methods**
- [ ] **Step 2: Implement `UserModel.php`, `EventModel.php`, `ShiftModel.php`, `DriveSubmissionModel.php`**
- [ ] **Step 3: Implement `CollisionService.php` with Temporal Interval Collision detection logic**
- [ ] **Step 4: Implement `DynamicQrHelper.php` with HMAC-SHA256 signature and 30s TTL verification**
- [ ] **Step 5: Implement `SessionHelper.php` for user state and CSRF tokens**

---

### Task 4: Controllers & API Endpoints
**Files:**
- Create: `app/controllers/BaseController.php`
- Create: `app/controllers/HomeController.php`
- Create: `app/controllers/EventController.php`
- Create: `app/controllers/ShiftController.php`
- Create: `app/controllers/AiAssistantController.php`
- Create: `app/controllers/AdminController.php`

- [ ] **Step 1: Implement `BaseController.php` (view rendering, json responses)**
- [ ] **Step 2: Implement `HomeController.php` (homepage event listing, search, location filtering)**
- [ ] **Step 3: Implement `EventController.php` (event detail, shift breakdown)**
- [ ] **Step 4: Implement `ShiftController.php` (register shift API with collision validation)**
- [ ] **Step 5: Implement `AiAssistantController.php` (chat API, task query "Tôi cần làm gì hôm nay?", drive link handover)**
- [ ] **Step 6: Implement `AdminController.php` (dashboard stats, dynamic QR display API)**

---

### Task 5: Views & Responsive UI/UX (Tailwind CSS)
**Files:**
- Create: `app/views/layouts/header.php`
- Create: `app/views/layouts/footer.php`
- Create: `app/views/home/index.php`
- Create: `app/views/events/detail.php`
- Create: `app/views/ai/widget.php`
- Create: `app/views/admin/dashboard.php`
- Create: `app/views/admin/qr_projector.php`
- Create: `public/assets/css/style.css`
- Create: `public/assets/js/app.js`

- [ ] **Step 1: Create `header.php` and `footer.php` with FPoly branding and responsive navigation**
- [ ] **Step 2: Implement `home/index.php` with Hero banner, search bar, location filter, and event cards**
- [ ] **Step 3: Implement `events/detail.php` with shift breakdown, capacity progress bar, and registration modal**
- [ ] **Step 4: Implement `ai/widget.php` with floating chatbox, quick action pills, and Google Drive submission flow**
- [ ] **Step 5: Implement `admin/dashboard.php` and `admin/qr_projector.php` with 30s live countdown QR**
- [ ] **Step 6: Implement `public/assets/js/app.js` for seamless Ajax registration, AI chat, and QR live refresh**

---

### Task 6: Testing, Docker & Handover Documentation
**Files:**
- Create: `tests/test_flow.php`
- Create: `docker-compose.yml`
- Create: `Dockerfile`
- Create: `README.md`

- [ ] **Step 1: Implement and run automated test script `tests/test_flow.php` verifying all endpoints**
- [ ] **Step 2: Create `docker-compose.yml` and `Dockerfile` for multi-container deployment**
- [ ] **Step 3: Write comprehensive `README.md` with step-by-step instructions for LMS review and teacher demo**
- [ ] **Step 4: Launch local test server and verify complete application works cleanly**
