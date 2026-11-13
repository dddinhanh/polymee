# Tài liệu Thiết Kế Kỹ Thuật (Design Specification)
## Dự án: PolyMee - Hệ thống Quản trị Sự kiện & Điều phối CTV tại FPOLY
**Môn học:** WEB212 – Phát triển dự án website với AI (FPT Polytechnic)  
**Ngày:** 09/11/2026  
**Trạng thái:** Đã phê duyệt (Approved)  

---

### 1. Mục tiêu và Phạm vi (Giai đoạn 1: Buổi 1–6)
* Xây dựng cấu trúc khung mã nguồn chuẩn PHP 8.2 Hướng đối tượng (OOP) theo mô hình MVC (Model - View - Controller), phân tách triệt để `app/models`, `app/views`, `app/controllers`, Single Entry Point `public/index.php` và định tuyến qua `.htaccess`.
* Thiết kế và khởi tạo cơ sở dữ liệu quan hệ 8 bảng chuẩn 3NF (hỗ trợ cả MySQL cho bài nộp LMS và SQLite tích hợp sẵn để demo tức thì).
* Xây dựng giao diện Client (End-User) responsive với Tailwind CSS: Danh sách sự kiện, bộ lọc tìm kiếm theo hội trường/thời gian, chi tiết sự kiện và modal đăng ký ca trực CTV có kiểm tra chống trùng lịch thời gian thực (`CollisionService`).
* Xây dựng Widget Trợ lý ảo AI "PolyMee" (Floating Chatbox): Hỗ trợ sinh viên hỏi ca trực trong ngày ("Tôi cần làm gì hôm nay?") và tự động tiếp nhận link Google Drive nghiệm thu minh chứng sản phẩm Media.
* Xây dựng phân hệ Quản trị Admin: Dashboard thống kê sự kiện/CTV, và màn hình chiếu mã Dynamic QR Code đếm ngược 30 giây (TTL 30s) có mã hóa chữ ký HMAC-SHA256 phục vụ điểm danh trực tiếp.

---

### 2. Kiến trúc Hệ thống (System Architecture)
```
Browser Client (Tailwind CSS, Vanilla JS, Fetch API, HTML5 Camera)
                           │
                           ▼ HTTP Request
              public/index.php (Single Entry Point)
                           │
                           ▼
                 core/App.php (Router)
                           │
              ┌────────────┴────────────┐
              ▼                         ▼
   app/controllers/          app/controllers/
   HomeController.php        ShiftController.php / AiAssistantController.php
              │                         │
              ▼                         ▼
      app/models/               app/services/
      EventModel.php            CollisionService.php / GeminiService.php
              │                         │
              └────────────┬────────────┘
                           ▼
                  core/Database.php (PDO Singleton)
                           │
                           ▼
          MySQL Server / SQLite Database (3NF Relational Tables)
```

---

### 3. CSDL Quan Hệ (Database Schema)
1. `roles`: `id`, `name`, `description`
2. `users`: `id`, `student_code`, `full_name`, `email`, `password_hash`, `phone`, `role_id`, `skills`
3. `locations`: `id`, `name`, `address`, `capacity`, `status`
4. `events`: `id`, `title`, `description`, `location_id`, `start_time`, `end_time`, `status`, `created_by`, `banner_url`
5. `shifts`: `id`, `event_id`, `name`, `start_time`, `end_time`, `target_qty`, `approved_qty`, `skill_required`, `points`
6. `shift_assignments`: `id`, `shift_id`, `user_id`, `status` ('Pending', 'Approved', 'Waitlist', 'Cancelled'), `assigned_at`
7. `check_ins`: `id`, `assignment_id`, `check_in_time`, `check_out_time`, `method` ('DynamicQR', 'Manual'), `status`
8. `drive_submissions`: `id`, `assignment_id`, `drive_url`, `summary`, `status` ('Pending', 'Accepted', 'Rejected'), `submitted_at`

---

### 4. Thuật toán Cốt lõi
* **Kiểm tra giao thoa khoảng thời gian (Temporal Interval Collision Detection)**:
  `(Start_New < End_Existing) AND (End_New > Start_Existing)`
* **Dynamic QR Code HMAC-SHA256**:
  `Payload = EventId | ShiftId | Timestamp`
  `Signature = hash_hmac('sha256', Payload, SecretKey)`
  `TTL = 30 giây`
* **AI Chat Assistant (Function Calling Logic)**:
  Phân tích intent câu hỏi người dùng, gọi method truy vấn ca trực hoặc lưu trữ link Google Drive vào CSDL.
