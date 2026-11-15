#!/usr/bin/env python3
"""
Generate professional UML Use Case Diagram SVG for PolyMee Project
FPT Polytechnic Dong Nai Branch.
5 Canonical Roles: Student, Media, Organizer, Staff (Hành chính kiêm CSVC & Ký duyệt), Admin.
Zero QR Code - 100% Minh Chứng Thực Tế (Ảnh Hiện Trường & Link Drive).
"""

def generate_svg():
    svg_content = """<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1340 930" width="100%" height="100%" style="background:#FFFFFF; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
  <defs>
    <!-- Gradients -->
    <linearGradient id="headerGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#EA580C" />
      <stop offset="100%" stop-color="#C2410C" />
    </linearGradient>
    <linearGradient id="subsysGrad1" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#EFF6FF" />
      <stop offset="100%" stop-color="#F8FAFC" />
    </linearGradient>
    <linearGradient id="subsysGrad2" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#F0FDF4" />
      <stop offset="100%" stop-color="#F8FAFC" />
    </linearGradient>
    <linearGradient id="subsysGrad3" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#FAF5FF" />
      <stop offset="100%" stop-color="#F8FAFC" />
    </linearGradient>
    <linearGradient id="actorGrad" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#FFFFFF" />
      <stop offset="100%" stop-color="#F1F5F9" />
    </linearGradient>
    <linearGradient id="ucGrad" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#FFFFFF" />
      <stop offset="100%" stop-color="#F8FAFC" />
    </linearGradient>
    
    <!-- Filter Drop Shadows -->
    <filter id="shadow" x="-5%" y="-5%" width="110%" height="115%" filterUnits="userSpaceOnUse">
      <feDropShadow dx="0" dy="2" stdDeviation="3" flood-color="#000000" flood-opacity="0.08" />
    </filter>
    <filter id="cardShadow" x="-3%" y="-3%" width="106%" height="110%" filterUnits="userSpaceOnUse">
      <feDropShadow dx="0" dy="4" stdDeviation="6" flood-color="#0F172A" flood-opacity="0.06" />
    </filter>

    <!-- Marker Arrow -->
    <marker id="arrow" viewBox="0 0 10 10" refX="9" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
      <path d="M 0 1 L 10 5 L 0 9 z" fill="#64748B" />
    </marker>
    <marker id="arrowInc" viewBox="0 0 10 10" refX="9" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
      <path d="M 0 1 L 10 5 L 0 9 z" fill="#0284C7" />
    </marker>
  </defs>

  <!-- Canvas Outer Border -->
  <rect x="10" y="10" width="1320" height="910" rx="16" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" />

  <!-- Top Title Header Banner -->
  <rect x="25" y="25" width="1290" height="60" rx="10" fill="url(#headerGrad)" />
  <text x="670" y="52" fill="#FFFFFF" font-size="19" font-weight="700" text-anchor="middle" letter-spacing="0.5">SƠ ĐỒ USE CASE TỔNG THỂ HỆ THỐNG POLYMEE</text>
  <text x="670" y="73" fill="#FED7AA" font-size="12" font-weight="500" text-anchor="middle">MÔ HÌNH HÓA THEO CHUẨN UML 2.5 • CƠ SỞ ĐỒNG NAI (FPT POLYTECHNIC) • 5 ROLES CHUẨN</text>

  <!-- ========================================================
       ACTORS COLUMN (LEFT SIDE) - 5 CANONICAL ACTORS
       ======================================================== -->
  <rect x="30" y="105" width="280" height="795" rx="14" fill="#F8FAFC" stroke="#CBD5E1" stroke-width="1.5" />
  <rect x="30" y="105" width="280" height="38" rx="14" fill="#E2E8F0" />
  <text x="170" y="130" fill="#334155" font-size="13.5" font-weight="700" text-anchor="middle">TÁC NHÂN HỆ THỐNG (5 ACTORS)</text>

  <!-- A1: Student -->
  <g transform="translate(45, 155)">
    <rect x="0" y="0" width="250" height="120" rx="10" fill="url(#actorGrad)" stroke="#3B82F6" stroke-width="1.5" filter="url(#shadow)" />
    <!-- Stick Figure -->
    <circle cx="36" cy="45" r="14" fill="#DBEAFE" stroke="#2563EB" stroke-width="2" />
    <path d="M 36 59 L 36 90 M 20 73 L 52 73 M 36 90 L 22 110 M 36 90 L 50 110" stroke="#2563EB" stroke-width="2" stroke-linecap="round" />
    <!-- Texts -->
    <text x="75" y="38" fill="#1E293B" font-size="13" font-weight="700">1. Sinh Viên CTV Sự Kiện</text>
    <text x="75" y="58" fill="#2563EB" font-size="11" font-weight="600">Role: Student (Lê Đức Mạnh)</text>
    <text x="75" y="78" fill="#64748B" font-size="10.5">Đăng ký ca, check collision,</text>
    <text x="75" y="96" fill="#059669" font-size="10.5" font-weight="600">Nộp ảnh minh chứng hiện trường</text>
  </g>

  <!-- A2: Media -->
  <g transform="translate(45, 295)">
    <rect x="0" y="0" width="250" height="120" rx="10" fill="url(#actorGrad)" stroke="#F59E0B" stroke-width="1.5" filter="url(#shadow)" />
    <circle cx="36" cy="45" r="14" fill="#FEF3C7" stroke="#D97706" stroke-width="2" />
    <path d="M 36 59 L 36 90 M 20 73 L 52 73 M 36 90 L 22 110 M 36 90 L 50 110" stroke="#D97706" stroke-width="2" stroke-linecap="round" />
    <text x="75" y="38" fill="#1E293B" font-size="13" font-weight="700">2. CTV Media &amp; Quay Chụp</text>
    <text x="75" y="58" fill="#D97706" font-size="11" font-weight="600">Role: Media (Đinh N. P. Anh)</text>
    <text x="75" y="78" fill="#64748B" font-size="10.5">Tác nghiệp ảnh/video RAW,</text>
    <text x="75" y="96" fill="#D97706" font-size="10.5" font-weight="600">Nộp link Google Drive recap</text>
  </g>

  <!-- A3: Organizer -->
  <g transform="translate(45, 435)">
    <rect x="0" y="0" width="250" height="125" rx="10" fill="url(#actorGrad)" stroke="#10B981" stroke-width="1.5" filter="url(#shadow)" />
    <circle cx="36" cy="48" r="14" fill="#D1FAE5" stroke="#059669" stroke-width="2" />
    <path d="M 36 62 L 36 95 M 20 76 L 52 76 M 36 95 L 22 115 M 36 95 L 50 115" stroke="#059669" stroke-width="2" stroke-linecap="round" />
    <text x="75" y="38" fill="#1E293B" font-size="13" font-weight="700">3. Giảng Viên / Ban Tổ Chức</text>
    <text x="75" y="58" fill="#059669" font-size="11" font-weight="600">Role: Organizer (Thầy Khải, Cô Trang)</text>
    <text x="75" y="78" fill="#64748B" font-size="10.5">Tạo sự kiện, rã ca trực,</text>
    <text x="75" y="96" fill="#047857" font-size="10.5" font-weight="600">Duyệt nghiệm thu minh chứng</text>
    <text x="75" y="114" fill="#64748B" font-size="10">Tự động sinh HĐ điện tử HDNT</text>
  </g>

  <!-- A4: Staff (HÀNH CHÍNH GỘP CSVC + KÝ DUYỆT CHI TRẢ) -->
  <g transform="translate(45, 580)">
    <rect x="0" y="0" width="250" height="135" rx="10" fill="url(#actorGrad)" stroke="#0284C7" stroke-width="2" filter="url(#shadow)" />
    <circle cx="36" cy="50" r="14" fill="#E0F2FE" stroke="#0284C7" stroke-width="2" />
    <path d="M 36 64 L 36 98 M 20 78 L 52 78 M 36 98 L 22 120 M 36 98 L 50 120" stroke="#0284C7" stroke-width="2" stroke-linecap="round" />
    <text x="75" y="36" fill="#1E293B" font-size="13" font-weight="700">4. Cán Bộ Hành Chính</text>
    <text x="75" y="56" fill="#0284C7" font-size="11" font-weight="600">Role: Staff (ThS. Hồng Sơn, Tuấn LD)</text>
    <text x="75" y="76" fill="#64748B" font-size="10.5">Quản lý CSVC &amp; Ghi chú lịch,</text>
    <text x="75" y="94" fill="#0369A1" font-size="10.5" font-weight="600">Đối soát CCCD/VNeID/STK,</text>
    <text x="75" y="112" fill="#0284C7" font-size="10.5" font-weight="700">Ký duyệt số HĐ &amp; Chi trả thù lao</text>
  </g>

  <!-- A5: Admin -->
  <g transform="translate(45, 735)">
    <rect x="0" y="0" width="250" height="120" rx="10" fill="url(#actorGrad)" stroke="#DC2626" stroke-width="1.5" filter="url(#shadow)" />
    <circle cx="36" cy="45" r="14" fill="#FEE2E2" stroke="#DC2626" stroke-width="2" />
    <path d="M 36 59 L 36 90 M 20 73 L 52 73 M 36 90 L 22 110 M 36 90 L 50 110" stroke="#DC2626" stroke-width="2" stroke-linecap="round" />
    <text x="75" y="38" fill="#1E293B" font-size="13" font-weight="700">5. Quản Trị Viên (Admin)</text>
    <text x="75" y="58" fill="#DC2626" font-size="11" font-weight="600">Role: Admin (ADMIN001)</text>
    <text x="75" y="78" fill="#64748B" font-size="10.5">Phân quyền vai trò RBAC 5 Roles,</text>
    <text x="75" y="96" fill="#64748B" font-size="10.5">Giám sát Audit Trail toàn diện</text>
  </g>

  <!-- ========================================================
       SYSTEM BOUNDARY BOX (RIGHT SIDE)
       ======================================================== -->
  <rect x="330" y="105" width="985" height="795" rx="14" fill="#FFFFFF" stroke="#0F172A" stroke-width="2" filter="url(#cardShadow)" />
  <rect x="330" y="105" width="985" height="38" rx="14" fill="#0F172A" />
  <text x="822" y="130" fill="#FFFFFF" font-size="14" font-weight="700" text-anchor="middle" letter-spacing="0.5">RANH GIỚI HỆ THỐNG: NỀN TẢNG WEB ĐIỀU PHỐI SỰ KIỆN POLYMEE</text>

  <!-- ========================================================
       SUBSYSTEM 1: QUẢN TRỊ LỊCH & CA TRỰC
       ======================================================== -->
  <g transform="translate(350, 155)">
    <rect x="0" y="0" width="465" height="385" rx="12" fill="url(#subsysGrad1)" stroke="#BFDBFE" stroke-width="1.2" />
    <rect x="0" y="0" width="465" height="30" rx="12" fill="#DBEAFE" />
    <text x="232" y="21" fill="#1E40AF" font-size="12" font-weight="700" text-anchor="middle">1. PHÂN HỆ QUẢN TRỊ LỊCH &amp; CA TRỰC</text>

    <!-- UC1 -->
    <g transform="translate(232, 60)">
      <ellipse cx="0" cy="0" rx="125" ry="20" fill="url(#ucGrad)" stroke="#2563EB" stroke-width="1.5" filter="url(#shadow)" />
      <text x="0" y="5" fill="#1E293B" font-size="11" font-weight="600" text-anchor="middle">UC01: Xem Lịch Trung Tâm (Tháng/Tuần/Ngày)</text>
    </g>

    <!-- UC2 -->
    <g transform="translate(125, 135)">
      <ellipse cx="0" cy="0" rx="105" ry="20" fill="url(#ucGrad)" stroke="#2563EB" stroke-width="1.5" filter="url(#shadow)" />
      <text x="0" y="5" fill="#1E293B" font-size="11" font-weight="600" text-anchor="middle">UC02: Đăng Ký Ca Trực</text>
    </g>

    <!-- UC3 -->
    <g transform="translate(345, 135)">
      <ellipse cx="0" cy="0" rx="105" ry="20" fill="url(#ucGrad)" stroke="#0284C7" stroke-width="1.5" stroke-dasharray="4,3" filter="url(#shadow)" />
      <text x="0" y="5" fill="#0369A1" font-size="10.5" font-weight="600" text-anchor="middle">UC03: Check Trùng Giờ (Collision)</text>
    </g>

    <!-- <<include>> line between UC2 and UC3 -->
    <path d="M 230 135 L 240 135" stroke="#0284C7" stroke-width="1.5" stroke-dasharray="4,3" marker-end="url(#arrowInc)" />
    <text x="235" y="126" fill="#0284C7" font-size="9" font-weight="700" text-anchor="middle">&lt;&lt;include&gt;&gt;</text>

    <!-- UC4: Ghi chú lịch CSVC / sự kiện (THAY CHO QR) -->
    <g transform="translate(125, 220)">
      <ellipse cx="0" cy="0" rx="110" ry="20" fill="url(#ucGrad)" stroke="#2563EB" stroke-width="1.5" filter="url(#shadow)" />
      <text x="0" y="5" fill="#1E293B" font-size="10.5" font-weight="600" text-anchor="middle">UC04: Ghi Chú Lịch CSVC / Sự Kiện</text>
    </g>

    <!-- UC5: Tạo sự kiện -->
    <g transform="translate(345, 220)">
      <ellipse cx="0" cy="0" rx="105" ry="20" fill="url(#ucGrad)" stroke="#EA580C" stroke-width="1.5" filter="url(#shadow)" />
      <text x="0" y="5" fill="#9A3412" font-size="10.5" font-weight="600" text-anchor="middle">UC05: Tạo Sự Kiện &amp; Đặt Phòng</text>
    </g>

    <!-- UC6: Phân rã ca -->
    <g transform="translate(232, 310)">
      <ellipse cx="0" cy="0" rx="125" ry="20" fill="url(#ucGrad)" stroke="#EA580C" stroke-width="1.5" filter="url(#shadow)" />
      <text x="0" y="5" fill="#9A3412" font-size="10.5" font-weight="600" text-anchor="middle">UC06: Phân Rã Ca Trực &amp; Định Mức Thù Lao</text>
    </g>

    <!-- UC6 extend UC5 relation -->
    <path d="M 270 292 L 320 240" stroke="#EA580C" stroke-width="1.5" stroke-dasharray="4,3" marker-end="url(#arrowInc)" />
    <text x="310" y="275" fill="#EA580C" font-size="9" font-weight="700" text-anchor="middle">&lt;&lt;extend&gt;&gt;</text>
  </g>

  <!-- ========================================================
       SUBSYSTEM 2: NGHIỆM THU & QUYẾT TOÁN THÙ LAO (ZERO QR)
       ======================================================== -->
  <g transform="translate(830, 155)">
    <rect x="0" y="0" width="465" height="385" rx="12" fill="url(#subsysGrad2)" stroke="#A7F3D0" stroke-width="1.2" />
    <rect x="0" y="0" width="465" height="30" rx="12" fill="#D1FAE5" />
    <text x="232" y="21" fill="#065F46" font-size="12" font-weight="700" text-anchor="middle">2. PHÂN HỆ NGHIỆM THU MINH CHỨNG &amp; QUYẾT TOÁN THÙ LAO</text>

    <!-- UC7 -->
    <g transform="translate(125, 60)">
      <ellipse cx="0" cy="0" rx="105" ry="20" fill="url(#ucGrad)" stroke="#059669" stroke-width="1.5" filter="url(#shadow)" />
      <text x="0" y="5" fill="#1E293B" font-size="10" font-weight="600" text-anchor="middle">UC07: Nộp Ảnh Minh Chứng Hiện Trường</text>
    </g>

    <!-- UC8 -->
    <g transform="translate(345, 60)">
      <ellipse cx="0" cy="0" rx="105" ry="20" fill="url(#ucGrad)" stroke="#D97706" stroke-width="1.5" filter="url(#shadow)" />
      <text x="0" y="5" fill="#1E293B" font-size="10" font-weight="600" text-anchor="middle">UC08: Nộp Link Drive Bàn Giao</text>
    </g>

    <!-- UC9 -->
    <g transform="translate(125, 145)">
      <ellipse cx="0" cy="0" rx="105" ry="20" fill="url(#ucGrad)" stroke="#059669" stroke-width="1.5" filter="url(#shadow)" />
      <text x="0" y="5" fill="#1E293B" font-size="10.5" font-weight="600" text-anchor="middle">UC09: Giảng Viên Nghiệm Thu Ca</text>
    </g>

    <!-- UC10 -->
    <g transform="translate(345, 145)">
      <ellipse cx="0" cy="0" rx="105" ry="20" fill="url(#ucGrad)" stroke="#059669" stroke-width="1.5" stroke-dasharray="4,3" filter="url(#shadow)" />
      <text x="0" y="5" fill="#047857" font-size="10" font-weight="600" text-anchor="middle">UC10: Tự Động Khởi Tạo HĐ Điện Tử</text>
    </g>

    <path d="M 230 145 L 240 145" stroke="#059669" stroke-width="1.5" stroke-dasharray="4,3" marker-end="url(#arrowInc)" />
    <text x="235" y="136" fill="#059669" font-size="9" font-weight="700" text-anchor="middle">&lt;&lt;include&gt;&gt;</text>

    <!-- UC11 -->
    <g transform="translate(125, 235)">
      <ellipse cx="0" cy="0" rx="105" ry="20" fill="url(#ucGrad)" stroke="#2563EB" stroke-width="1.5" filter="url(#shadow)" />
      <text x="0" y="5" fill="#1E293B" font-size="10" font-weight="600" text-anchor="middle">UC11: Kê Khai CCCD, VNeID, STK</text>
    </g>

    <!-- UC12 -->
    <g transform="translate(345, 235)">
      <ellipse cx="0" cy="0" rx="105" ry="20" fill="url(#ucGrad)" stroke="#0284C7" stroke-width="1.5" stroke-dasharray="4,3" filter="url(#shadow)" />
      <text x="0" y="5" fill="#0284C7" font-size="10" font-weight="600" text-anchor="middle">UC12: Hành Chính Ký Duyệt &amp; Chi Trả</text>
    </g>

    <path d="M 240 235 L 230 235" stroke="#0284C7" stroke-width="1.5" stroke-dasharray="4,3" marker-end="url(#arrowInc)" />
    <text x="235" y="226" fill="#0284C7" font-size="9" font-weight="700" text-anchor="middle">&lt;&lt;include&gt;&gt;</text>

    <!-- UC13 -->
    <g transform="translate(232, 320)">
      <ellipse cx="0" cy="0" rx="125" ry="20" fill="url(#ucGrad)" stroke="#0284C7" stroke-width="1.5" filter="url(#shadow)" />
      <text x="0" y="5" fill="#1E293B" font-size="10.5" font-weight="600" text-anchor="middle">UC13: Xuất Báo Cáo Quyết Toán CSV &amp; In A4</text>
    </g>
  </g>

  <!-- ========================================================
       SUBSYSTEM 3: TRỢ LÝ AI & QUẢN TRỊ HỆ THỐNG
       ======================================================== -->
  <g transform="translate(350, 560)">
    <rect x="0" y="0" width="945" height="320" rx="12" fill="url(#subsysGrad3)" stroke="#DDD6FE" stroke-width="1.2" />
    <rect x="0" y="0" width="945" height="30" rx="12" fill="#EDE9FE" />
    <text x="472" y="21" fill="#5B21B6" font-size="12" font-weight="700" text-anchor="middle">3. PHÂN HỆ TRỢ LÝ AI &amp; QUẢN TRỊ HỆ THỐNG (RBAC 5 ROLES &amp; AUDIT)</text>

    <!-- UC14 -->
    <g transform="translate(260, 90)">
      <ellipse cx="0" cy="0" rx="135" ry="22" fill="url(#ucGrad)" stroke="#8B5CF6" stroke-width="1.5" filter="url(#shadow)" />
      <text x="0" y="5" fill="#1E293B" font-size="11.5" font-weight="600" text-anchor="middle">UC14: Trợ Lý AI Theo Vai Trò (Context-Aware)</text>
    </g>

    <!-- UC15 -->
    <g transform="translate(680, 90)">
      <ellipse cx="0" cy="0" rx="135" ry="22" fill="url(#ucGrad)" stroke="#7C3AED" stroke-width="1.5" stroke-dasharray="4,3" filter="url(#shadow)" />
      <text x="0" y="5" fill="#6D28D9" font-size="11" font-weight="600" text-anchor="middle">UC15: AI Function Calling (Tra Cứu Phòng Trống)</text>
    </g>

    <path d="M 395 90 L 545 90" stroke="#7C3AED" stroke-width="1.5" stroke-dasharray="4,3" marker-end="url(#arrowInc)" />
    <text x="470" y="81" fill="#7C3AED" font-size="9.5" font-weight="700" text-anchor="middle">&lt;&lt;extend&gt;&gt;</text>

    <!-- UC16 -->
    <g transform="translate(260, 205)">
      <ellipse cx="0" cy="0" rx="135" ry="22" fill="url(#ucGrad)" stroke="#DC2626" stroke-width="1.5" filter="url(#shadow)" />
      <text x="0" y="5" fill="#1E293B" font-size="11.5" font-weight="600" text-anchor="middle">UC16: Cấp Quyền Vai Trò RBAC (Matrix 5 Roles)</text>
    </g>

    <!-- UC17 -->
    <g transform="translate(680, 205)">
      <ellipse cx="0" cy="0" rx="135" ry="22" fill="url(#ucGrad)" stroke="#475569" stroke-width="1.5" filter="url(#shadow)" />
      <text x="0" y="5" fill="#1E293B" font-size="11.5" font-weight="600" text-anchor="middle">UC17: Nhật Ký Kiểm Toán Toàn Diện (Audit Trail)</text>
    </g>
  </g>

  <!-- ========================================================
       ASSOCIATION LINES (ACTORS -> USE CASES)
       ======================================================== -->
  <g stroke="#64748B" stroke-width="1.3" opacity="0.85">
    <!-- From Student (A1: cy=215) -->
    <line x1="295" y1="215" x2="470" y2="215" marker-end="url(#arrow)" />
    <line x1="295" y1="215" x2="475" y2="290" marker-end="url(#arrow)" />
    <line x1="295" y1="215" x2="955" y2="215" marker-end="url(#arrow)" stroke="#059669" stroke-width="1.6" />
    <line x1="295" y1="215" x2="955" y2="390" marker-end="url(#arrow)" stroke="#2563EB" />
    <line x1="295" y1="215" x2="500" y2="650" marker-end="url(#arrow)" stroke="#8B5CF6" />

    <!-- From Media (A2: cy=355) -->
    <line x1="295" y1="355" x2="470" y2="215" marker-end="url(#arrow)" />
    <line x1="295" y1="355" x2="475" y2="290" marker-end="url(#arrow)" />
    <line x1="295" y1="355" x2="1175" y2="215" marker-end="url(#arrow)" stroke="#D97706" stroke-width="1.6" />
    <line x1="295" y1="355" x2="955" y2="390" marker-end="url(#arrow)" stroke="#2563EB" />
    <line x1="295" y1="355" x2="500" y2="650" marker-end="url(#arrow)" stroke="#8B5CF6" />

    <!-- From Organizer (A3: cy=495) -->
    <line x1="295" y1="495" x2="470" y2="215" marker-end="url(#arrow)" />
    <line x1="295" y1="495" x2="695" y2="375" marker-end="url(#arrow)" stroke="#EA580C" stroke-width="1.6" />
    <line x1="295" y1="495" x2="475" y2="375" marker-end="url(#arrow)" />
    <line x1="295" y1="495" x2="955" y2="300" marker-end="url(#arrow)" stroke="#059669" stroke-width="1.6" />
    <line x1="295" y1="495" x2="1062" y2="475" marker-end="url(#arrow)" />
    <line x1="295" y1="495" x2="500" y2="650" marker-end="url(#arrow)" stroke="#8B5CF6" />

    <!-- From Staff (A4: cy=645) - HÀNH CHÍNH GỘP TOÀN BỘ CSVC & KÝ DUYỆT -->
    <line x1="295" y1="645" x2="470" y2="215" marker-end="url(#arrow)" />
    <line x1="295" y1="645" x2="475" y2="375" marker-end="url(#arrow)" stroke="#0284C7" />
    <line x1="295" y1="645" x2="1175" y2="390" marker-end="url(#arrow)" stroke="#0284C7" stroke-width="1.8" />
    <line x1="295" y1="645" x2="1062" y2="475" marker-end="url(#arrow)" stroke="#0284C7" stroke-width="1.6" />
    <line x1="295" y1="645" x2="500" y2="650" marker-end="url(#arrow)" stroke="#8B5CF6" />
    <line x1="295" y1="645" x2="1030" y2="765" marker-end="url(#arrow)" stroke="#475569" />

    <!-- From Admin (A5: cy=795) -->
    <line x1="295" y1="795" x2="500" y2="650" marker-end="url(#arrow)" stroke="#8B5CF6" />
    <line x1="295" y1="795" x2="610" y2="765" marker-end="url(#arrow)" stroke="#DC2626" stroke-width="1.8" />
    <line x1="295" y1="795" x2="1030" y2="765" marker-end="url(#arrow)" stroke="#DC2626" />
  </g>

</svg>"""

    with open('docs/assets/use_case_diagram.svg', 'w', encoding='utf-8') as f:
        f.write(svg_content)
    print("✓ Successfully generated docs/assets/use_case_diagram.svg (5 Roles & Zero QR)")

if __name__ == '__main__':
    generate_svg()
