#!/usr/bin/env python3
"""
Generate Role Hierarchy Diagram SVG for PolyMee Project
"""

def generate_roles_svg():
    svg_content = """<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1100 280" width="100%" height="100%" style="background:#FFFFFF; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
  <defs>
    <linearGradient id="rootGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#EA580C" />
      <stop offset="100%" stop-color="#C2410C" />
    </linearGradient>
    <filter id="roleShadow" x="-5%" y="-5%" width="110%" height="115%">
      <feDropShadow dx="0" dy="3" stdDeviation="4" flood-color="#0F172A" flood-opacity="0.08" />
    </filter>
    <marker id="roleArrow" viewBox="0 0 10 10" refX="9" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
      <path d="M 0 1 L 10 5 L 0 9 z" fill="#EA580C" />
    </marker>
  </defs>

  <!-- Root System Box -->
  <g transform="translate(425, 20)">
    <rect x="0" y="0" width="250" height="56" rx="12" fill="url(#rootGrad)" filter="url(#roleShadow)" />
    <text x="125" y="27" fill="#FFFFFF" font-size="13" font-weight="700" text-anchor="middle">HỆ THỐNG POLYMEE</text>
    <text x="125" y="44" fill="#FFEDD5" font-size="11" font-weight="500" text-anchor="middle">FPT Polytechnic Cơ Sở Đồng Nai</text>
  </g>

  <!-- Lines from Root to Roles -->
  <path d="M 550 76 L 550 115 M 115 115 L 985 115" stroke="#CBD5E1" stroke-width="2" fill="none" />
  
  <path d="M 115 115 L 115 140" stroke="#CBD5E1" stroke-width="2" marker-end="url(#roleArrow)" fill="none" />
  <path d="M 285 115 L 285 140" stroke="#CBD5E1" stroke-width="2" marker-end="url(#roleArrow)" fill="none" />
  <path d="M 460 115 L 460 140" stroke="#CBD5E1" stroke-width="2" marker-end="url(#roleArrow)" fill="none" />
  <path d="M 635 115 L 635 140" stroke="#CBD5E1" stroke-width="2" marker-end="url(#roleArrow)" fill="none" />
  <path d="M 810 115 L 810 140" stroke="#CBD5E1" stroke-width="2" marker-end="url(#roleArrow)" fill="none" />
  <path d="M 985 115 L 985 140" stroke="#CBD5E1" stroke-width="2" marker-end="url(#roleArrow)" fill="none" />

  <!-- Role 1: Student -->
  <g transform="translate(35, 140)">
    <rect x="0" y="0" width="160" height="110" rx="10" fill="#FFFFFF" stroke="#3B82F6" stroke-width="1.8" filter="url(#roleShadow)" />
    <rect x="0" y="0" width="160" height="28" rx="10" fill="#EFF6FF" />
    <text x="80" y="19" fill="#1D4ED8" font-size="11.5" font-weight="700" text-anchor="middle">1. Sinh Viên CTV</text>
    <text x="80" y="48" fill="#1E293B" font-size="11" font-weight="600" text-anchor="middle">Student</text>
    <text x="80" y="68" fill="#64748B" font-size="10" text-anchor="middle">Đăng ký ca, QR 30s</text>
    <text x="80" y="85" fill="#64748B" font-size="10" text-anchor="middle">Kê khai CCCD &amp; STK</text>
    <text x="80" y="100" fill="#2563EB" font-size="9.5" font-style="italic" text-anchor="middle">Lê Đức Mạnh</text>
  </g>

  <!-- Role 2: Media -->
  <g transform="translate(205, 140)">
    <rect x="0" y="0" width="160" height="110" rx="10" fill="#FFFFFF" stroke="#F59E0B" stroke-width="1.8" filter="url(#roleShadow)" />
    <rect x="0" y="0" width="160" height="28" rx="10" fill="#FFFBEB" />
    <text x="80" y="19" fill="#B45309" font-size="11.5" font-weight="700" text-anchor="middle">2. CTV Media</text>
    <text x="80" y="48" fill="#1E293B" font-size="11" font-weight="600" text-anchor="middle">Media</text>
    <text x="80" y="68" fill="#64748B" font-size="10" text-anchor="middle">Quay chụp RAW</text>
    <text x="80" y="85" fill="#64748B" font-size="10" text-anchor="middle">Nộp Google Drive</text>
    <text x="80" y="100" fill="#D97706" font-size="9.5" font-style="italic" text-anchor="middle">Đinh N. P. Anh</text>
  </g>

  <!-- Role 3: Organizer -->
  <g transform="translate(380, 140)">
    <rect x="0" y="0" width="160" height="110" rx="10" fill="#FFFFFF" stroke="#10B981" stroke-width="1.8" filter="url(#roleShadow)" />
    <rect x="0" y="0" width="160" height="28" rx="10" fill="#ECFDF5" />
    <text x="80" y="19" fill="#047857" font-size="11.5" font-weight="700" text-anchor="middle">3. Giảng Viên / BTC</text>
    <text x="80" y="48" fill="#1E293B" font-size="11" font-weight="600" text-anchor="middle">Organizer</text>
    <text x="80" y="68" fill="#64748B" font-size="10" text-anchor="middle">Tạo sự kiện, rã ca</text>
    <text x="80" y="85" fill="#64748B" font-size="10" text-anchor="middle">Duyệt nghiệm thu</text>
    <text x="80" y="100" fill="#059669" font-size="9.5" font-style="italic" text-anchor="middle">Thầy Khải, Cô Trang</text>
  </g>

  <!-- Role 4: Staff -->
  <g transform="translate(555, 140)">
    <rect x="0" y="0" width="160" height="110" rx="10" fill="#FFFFFF" stroke="#8B5CF6" stroke-width="1.8" filter="url(#roleShadow)" />
    <rect x="0" y="0" width="160" height="28" rx="10" fill="#F5F3FF" />
    <text x="80" y="19" fill="#6D28D9" font-size="11.5" font-weight="700" text-anchor="middle">4. Cán Bộ CSVC</text>
    <text x="80" y="48" fill="#1E293B" font-size="11" font-weight="600" text-anchor="middle">Staff</text>
    <text x="80" y="68" fill="#64748B" font-size="10" text-anchor="middle">Bàn giao thiết bị</text>
    <text x="80" y="85" fill="#64748B" font-size="10" text-anchor="middle">Ghi chú lịch hiện trường</text>
    <text x="80" y="100" fill="#7C3AED" font-size="9.5" font-style="italic" text-anchor="middle">Lê Đình Tuấn</text>
  </g>

  <!-- Role 5: Head_Admin -->
  <g transform="translate(730, 140)">
    <rect x="0" y="0" width="160" height="110" rx="10" fill="#FFFFFF" stroke="#0284C7" stroke-width="1.8" filter="url(#roleShadow)" />
    <rect x="0" y="0" width="160" height="28" rx="10" fill="#F0F9FF" />
    <text x="80" y="19" fill="#0369A1" font-size="11.5" font-weight="700" text-anchor="middle">5. Trưởng Ban HC / BGH</text>
    <text x="80" y="48" fill="#1E293B" font-size="11" font-weight="600" text-anchor="middle">Head_Admin</text>
    <text x="80" y="68" fill="#64748B" font-size="10" text-anchor="middle">Thẩm định hồ sơ</text>
    <text x="80" y="85" fill="#64748B" font-size="10" text-anchor="middle">Ký duyệt chi thù lao</text>
    <text x="80" y="100" fill="#0284C7" font-size="9.5" font-style="italic" text-anchor="middle">ThS. Phạm Hồng Sơn</text>
  </g>

  <!-- Role 6: Admin -->
  <g transform="translate(905, 140)">
    <rect x="0" y="0" width="160" height="110" rx="10" fill="#FFFFFF" stroke="#DC2626" stroke-width="1.8" filter="url(#roleShadow)" />
    <rect x="0" y="0" width="160" height="28" rx="10" fill="#FEF2F2" />
    <text x="80" y="19" fill="#B91C1C" font-size="11.5" font-weight="700" text-anchor="middle">6. Quản Trị Hệ Thống</text>
    <text x="80" y="48" fill="#1E293B" font-size="11" font-weight="600" text-anchor="middle">Admin</text>
    <text x="80" y="68" fill="#64748B" font-size="10" text-anchor="middle">Phân quyền RBAC</text>
    <text x="80" y="85" fill="#64748B" font-size="10" text-anchor="middle">Giám sát Audit Trail</text>
    <text x="80" y="100" fill="#DC2626" font-size="9.5" font-style="italic" text-anchor="middle">ADMIN001</text>
  </g>
</svg>"""

    with open('docs/assets/role_hierarchy.svg', 'w', encoding='utf-8') as f:
        f.write(svg_content)
    print("✓ Successfully generated docs/assets/role_hierarchy.svg")

if __name__ == '__main__':
    generate_roles_svg()
