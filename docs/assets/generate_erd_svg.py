#!/usr/bin/env python3
"""
Generate clean professional 3NF Database ERD Diagram SVG for PolyMee Project
"""

def generate_erd_svg():
    svg_content = """<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 680" width="100%" height="100%" style="background:#FFFFFF; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
  <defs>
    <linearGradient id="erdTitleGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#0F172A" />
      <stop offset="100%" stop-color="#334155" />
    </linearGradient>
    <filter id="erdShadow" x="-5%" y="-5%" width="110%" height="115%">
      <feDropShadow dx="0" dy="2" stdDeviation="4" flood-color="#0F172A" flood-opacity="0.08" />
    </filter>
  </defs>

  <!-- Title Banner -->
  <rect x="20" y="15" width="1160" height="50" rx="10" fill="url(#erdTitleGrad)" />
  <text x="600" y="38" fill="#FFFFFF" font-size="16" font-weight="700" text-anchor="middle" letter-spacing="0.5">SƠ ĐỒ QUAN HỆ THỰC THỂ (DATABASE ERD - CHUẨN HÓA 3NF)</text>
  <text x="600" y="55" fill="#94A3B8" font-size="11" font-weight="500" text-anchor="middle">HỆ CƠ SỞ DỮ LIỆU ĐIỀU PHỐI SỰ KIỆN POLYMEE (10 BẢNG QUAN HỆ KHÓA NGOẠI)</text>

  <!-- Table 1: ROLES (x=30, y=85) -->
  <g transform="translate(30, 85)" filter="url(#erdShadow)">
    <rect x="0" y="0" width="190" height="120" rx="8" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.5" />
    <rect x="0" y="0" width="190" height="26" rx="8" fill="#F1F5F9" />
    <text x="95" y="18" fill="#0F172A" font-size="12" font-weight="700" text-anchor="middle">ROLES (Vai Trò)</text>
    <text x="12" y="44" fill="#EA580C" font-size="11" font-weight="700">PK  id</text><text x="80" y="44" fill="#64748B" font-size="11">: INT AUTO</text>
    <text x="12" y="64" fill="#0F172A" font-size="11">name</text><text x="80" y="64" fill="#64748B" font-size="11">: VARCHAR(50)</text>
    <text x="12" y="84" fill="#0F172A" font-size="11">description</text><text x="80" y="84" fill="#64748B" font-size="11">: VARCHAR(255)</text>
    <text x="12" y="104" fill="#059669" font-size="10" font-style="italic">6 Roles (Admin...Student)</text>
  </g>

  <!-- Table 2: USERS (x=270, y=85) -->
  <g transform="translate(270, 85)" filter="url(#erdShadow)">
    <rect x="0" y="0" width="230" height="300" rx="8" fill="#FFFFFF" stroke="#3B82F6" stroke-width="1.5" />
    <rect x="0" y="0" width="230" height="26" rx="8" fill="#EFF6FF" />
    <text x="115" y="18" fill="#1E40AF" font-size="12" font-weight="700" text-anchor="middle">USERS (Tài Khoản)</text>
    <text x="12" y="44" fill="#EA580C" font-size="11" font-weight="700">PK  id</text><text x="95" y="44" fill="#64748B" font-size="11">: INT AUTO</text>
    <text x="12" y="64" fill="#0284C7" font-size="11" font-weight="600">UK  student_code</text><text x="115" y="64" fill="#64748B" font-size="11">: VARCHAR(20)</text>
    <text x="12" y="84" fill="#0F172A" font-size="11">full_name</text><text x="95" y="84" fill="#64748B" font-size="11">: VARCHAR(100)</text>
    <text x="12" y="104" fill="#0284C7" font-size="11" font-weight="600">UK  email</text><text x="95" y="104" fill="#64748B" font-size="11">: VARCHAR(150)</text>
    <text x="12" y="124" fill="#0F172A" font-size="11">password_hash</text><text x="95" y="124" fill="#64748B" font-size="11">: VARCHAR(255)</text>
    <text x="12" y="144" fill="#7C3AED" font-size="11" font-weight="700">FK  role_id</text><text x="95" y="144" fill="#64748B" font-size="11">: INT -&gt; roles(id)</text>
    <text x="12" y="164" fill="#0F172A" font-size="11">phone</text><text x="95" y="164" fill="#64748B" font-size="11">: VARCHAR(20)</text>
    <text x="12" y="184" fill="#0F172A" font-size="11">skills</text><text x="95" y="184" fill="#64748B" font-size="11">: TEXT</text>
    <text x="12" y="204" fill="#059669" font-size="11">id_card_number</text><text x="115" y="204" fill="#64748B" font-size="11">: VARCHAR(20) [CCCD]</text>
    <text x="12" y="224" fill="#059669" font-size="11">bank_name</text><text x="115" y="224" fill="#64748B" font-size="11">: VARCHAR(100)</text>
    <text x="12" y="244" fill="#059669" font-size="11">bank_account_number</text><text x="145" y="244" fill="#64748B" font-size="11">: VARCHAR(50)</text>
    <text x="12" y="264" fill="#059669" font-size="11">vneid_image_url</text><text x="115" y="264" fill="#64748B" font-size="11">: VARCHAR(255)</text>
    <text x="12" y="284" fill="#0F172A" font-size="11">is_active</text><text x="95" y="284" fill="#64748B" font-size="11">: TINYINT(1)</text>
  </g>

  <!-- Table 3: LOCATIONS (x=550, y=85) -->
  <g transform="translate(550, 85)" filter="url(#erdShadow)">
    <rect x="0" y="0" width="200" height="130" rx="8" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.5" />
    <rect x="0" y="0" width="200" height="26" rx="8" fill="#F1F5F9" />
    <text x="100" y="18" fill="#0F172A" font-size="12" font-weight="700" text-anchor="middle">LOCATIONS (Hội Trường)</text>
    <text x="12" y="44" fill="#EA580C" font-size="11" font-weight="700">PK  id</text><text x="80" y="44" fill="#64748B" font-size="11">: INT AUTO</text>
    <text x="12" y="64" fill="#0F172A" font-size="11">name</text><text x="80" y="64" fill="#64748B" font-size="11">: VARCHAR(150)</text>
    <text x="12" y="84" fill="#0F172A" font-size="11">address</text><text x="80" y="84" fill="#64748B" font-size="11">: VARCHAR(255)</text>
    <text x="12" y="104" fill="#0F172A" font-size="11">capacity</text><text x="80" y="104" fill="#64748B" font-size="11">: INT (Sức chứa)</text>
  </g>

  <!-- Table 4: EVENTS (x=800, y=85) -->
  <g transform="translate(800, 85)" filter="url(#erdShadow)">
    <rect x="0" y="0" width="230" height="210" rx="8" fill="#FFFFFF" stroke="#EA580C" stroke-width="1.5" />
    <rect x="0" y="0" width="230" height="26" rx="8" fill="#FFF7ED" />
    <text x="115" y="18" fill="#C2410C" font-size="12" font-weight="700" text-anchor="middle">EVENTS (Sự Kiện FPoly)</text>
    <text x="12" y="44" fill="#EA580C" font-size="11" font-weight="700">PK  id</text><text x="90" y="44" fill="#64748B" font-size="11">: INT AUTO</text>
    <text x="12" y="64" fill="#0F172A" font-size="11">title</text><text x="90" y="64" fill="#64748B" font-size="11">: VARCHAR(200)</text>
    <text x="12" y="84" fill="#7C3AED" font-size="11" font-weight="700">FK  location_id</text><text x="100" y="84" fill="#64748B" font-size="11">: INT -&gt; locations</text>
    <text x="12" y="104" fill="#7C3AED" font-size="11" font-weight="700">FK  created_by</text><text x="100" y="104" fill="#64748B" font-size="11">: INT -&gt; users</text>
    <text x="12" y="124" fill="#0F172A" font-size="11">start_date</text><text x="90" y="124" fill="#64748B" font-size="11">: DATETIME</text>
    <text x="12" y="144" fill="#0F172A" font-size="11">end_date</text><text x="90" y="144" fill="#64748B" font-size="11">: DATETIME</text>
    <text x="12" y="164" fill="#0F172A" font-size="11">status</text><text x="90" y="164" fill="#64748B" font-size="11">: VARCHAR(50)</text>
    <text x="12" y="184" fill="#0F172A" font-size="11">banner_url</text><text x="90" y="184" fill="#64748B" font-size="11">: VARCHAR(255)</text>
  </g>

  <!-- Table 5: SHIFTS (x=800, y=340) -->
  <g transform="translate(800, 340)" filter="url(#erdShadow)">
    <rect x="0" y="0" width="230" height="190" rx="8" fill="#FFFFFF" stroke="#059669" stroke-width="1.5" />
    <rect x="0" y="0" width="230" height="26" rx="8" fill="#ECFDF5" />
    <text x="115" y="18" fill="#047857" font-size="12" font-weight="700" text-anchor="middle">SHIFTS (Ca Trực)</text>
    <text x="12" y="44" fill="#EA580C" font-size="11" font-weight="700">PK  id</text><text x="90" y="44" fill="#64748B" font-size="11">: INT AUTO</text>
    <text x="12" y="64" fill="#7C3AED" font-size="11" font-weight="700">FK  event_id</text><text x="90" y="64" fill="#64748B" font-size="11">: INT -&gt; events</text>
    <text x="12" y="84" fill="#0F172A" font-size="11">name</text><text x="90" y="84" fill="#64748B" font-size="11">: VARCHAR(100)</text>
    <text x="12" y="104" fill="#0F172A" font-size="11">start_time</text><text x="90" y="104" fill="#64748B" font-size="11">: DATETIME</text>
    <text x="12" y="124" fill="#0F172A" font-size="11">end_time</text><text x="90" y="124" fill="#64748B" font-size="11">: DATETIME</text>
    <text x="12" y="144" fill="#0F172A" font-size="11">target_qty</text><text x="90" y="144" fill="#64748B" font-size="11">: INT (Chỉ tiêu)</text>
    <text x="12" y="164" fill="#059669" font-size="11">remuneration</text><text x="90" y="164" fill="#64748B" font-size="11">: INT (VNĐ thù lao)</text>
  </g>

  <!-- Table 6: SHIFT_ASSIGNMENTS (x=450, y=410) -->
  <g transform="translate(450, 410)" filter="url(#erdShadow)">
    <rect x="0" y="0" width="280" height="240" rx="8" fill="#FFFFFF" stroke="#0284C7" stroke-width="1.8" />
    <rect x="0" y="0" width="280" height="26" rx="8" fill="#E0F2FE" />
    <text x="140" y="18" fill="#0369A1" font-size="12" font-weight="700" text-anchor="middle">SHIFT_ASSIGNMENTS (Đăng Ký &amp; HĐ)</text>
    <text x="12" y="44" fill="#EA580C" font-size="11" font-weight="700">PK  id</text><text x="110" y="44" fill="#64748B" font-size="11">: INT AUTO</text>
    <text x="12" y="64" fill="#7C3AED" font-size="11" font-weight="700">FK  shift_id</text><text x="110" y="64" fill="#64748B" font-size="11">: INT -&gt; shifts(id)</text>
    <text x="12" y="84" fill="#7C3AED" font-size="11" font-weight="700">FK  user_id</text><text x="110" y="84" fill="#64748B" font-size="11">: INT -&gt; users(id)</text>
    <text x="12" y="104" fill="#0284C7" font-size="11" font-weight="600">UK  contract_code</text><text x="110" y="104" fill="#64748B" font-size="11">: VARCHAR(50) [HDNT]</text>
    <text x="12" y="124" fill="#0F172A" font-size="11">lecturer_verified</text><text x="110" y="124" fill="#64748B" font-size="11">: TINYINT [Nghiệm thu]</text>
    <text x="12" y="144" fill="#0F172A" font-size="11">lecturer_name</text><text x="110" y="144" fill="#64748B" font-size="11">: VARCHAR(100)</text>
    <text x="12" y="164" fill="#0F172A" font-size="11">school_status</text><text x="110" y="164" fill="#64748B" font-size="11">: VARCHAR(50) [Signed]</text>
    <text x="12" y="184" fill="#0F172A" font-size="11">proof_image_url</text><text x="110" y="184" fill="#64748B" font-size="11">: VARCHAR(255) [Hiện trường]</text>
    <text x="12" y="204" fill="#0F172A" font-size="11">proof_drive_url</text><text x="110" y="204" fill="#64748B" font-size="11">: VARCHAR(255) [Drive Media]</text>
    <text x="12" y="224" fill="#059669" font-size="11">amount_paid</text><text x="110" y="224" fill="#64748B" font-size="11">: INT (Quyết toán VNĐ)</text>
  </g>

  <!-- Table 7: CHECK_INS (x=150, y=430) -->
  <g transform="translate(150, 430)" filter="url(#erdShadow)">
    <rect x="0" y="0" width="240" height="130" rx="8" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.5" />
    <rect x="0" y="0" width="240" height="26" rx="8" fill="#F8FAFC" />
    <text x="120" y="18" fill="#0F172A" font-size="12" font-weight="700" text-anchor="middle">CHECK_INS (Điểm Danh QR)</text>
    <text x="12" y="44" fill="#EA580C" font-size="11" font-weight="700">PK  id</text><text x="110" y="44" fill="#64748B" font-size="11">: INT AUTO</text>
    <text x="12" y="64" fill="#7C3AED" font-size="11" font-weight="700">FK  assignment_id</text><text x="110" y="64" fill="#64748B" font-size="11">: INT -&gt; shift_assignments</text>
    <text x="12" y="84" fill="#0F172A" font-size="11">check_in_time</text><text x="110" y="84" fill="#64748B" font-size="11">: DATETIME</text>
    <text x="12" y="104" fill="#0F172A" font-size="11">qr_token_hash</text><text x="110" y="104" fill="#64748B" font-size="11">: VARCHAR(64) [HMAC-256]</text>
  </g>

  <!-- Table 8: ACTIVITY_LOGS (x=800, y=560) -->
  <g transform="translate(800, 560)" filter="url(#erdShadow)">
    <rect x="0" y="0" width="230" height="100" rx="8" fill="#FFFFFF" stroke="#DC2626" stroke-width="1.5" />
    <rect x="0" y="0" width="230" height="26" rx="8" fill="#FEF2F2" />
    <text x="115" y="18" fill="#991B1B" font-size="12" font-weight="700" text-anchor="middle">ACTIVITY_LOGS (Audit Trail)</text>
    <text x="12" y="44" fill="#EA580C" font-size="11" font-weight="700">PK  id</text><text x="75" y="44" fill="#64748B" font-size="11">: INT AUTO</text>
    <text x="12" y="64" fill="#7C3AED" font-size="11" font-weight="700">FK  user_id</text><text x="75" y="64" fill="#64748B" font-size="11">: INT -&gt; users</text>
    <text x="12" y="84" fill="#0F172A" font-size="11">action, entity, ip_address, created_at</text>
  </g>

  <!-- Connectors -->
  <g stroke="#94A3B8" stroke-width="1.5" fill="none">
    <!-- roles to users -->
    <path d="M 220 135 L 270 135" />
    <!-- users to events -->
    <path d="M 500 135 L 800 135" />
    <!-- locations to events -->
    <path d="M 750 135 L 800 135" />
    <!-- events to shifts -->
    <path d="M 915 295 L 915 340" />
    <!-- shifts to shift_assignments -->
    <path d="M 800 435 L 730 435" />
    <!-- users to shift_assignments -->
    <path d="M 385 385 L 385 450 L 450 450" />
    <!-- shift_assignments to check_ins -->
    <path d="M 450 500 L 390 500" />
  </g>
</svg>"""

    with open('docs/assets/erd_diagram.svg', 'w', encoding='utf-8') as f:
        f.write(svg_content)
    print("✓ Successfully generated docs/assets/erd_diagram.svg")

if __name__ == '__main__':
    generate_erd_svg()
