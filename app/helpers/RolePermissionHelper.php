<?php
/**
 * PolyMee - RolePermissionHelper
 * Chuẩn hóa RBAC (Role-Based Access Control) theo BA Report v1.0 & Usecase specifications
 * Single Source of Truth (SSOT) cho Phân quyền Vai trò người dùng toàn hệ thống
 */

declare(strict_types=1);

class RolePermissionHelper
{
    /**
     * Chuẩn hóa mã vai trò chuẩn (Normalized Role Code)
     */
    public static function normalizeRole(string $roleName, int $roleId): string
    {
        $name = strtoupper(trim($roleName));
        if ($roleId === 1 || in_array($name, ['ADMIN', 'QUẢN TRỊ VIÊN', 'QUAN TRI VIEN'], true)) {
            return 'ADMIN';
        }
        if ($roleId === 2 || in_array($name, ['ORGANIZER', 'LECTURER', 'TEACHER', 'GIẢNG VIÊN', 'GIANG VIEN', 'BTC'], true)) {
            return 'LECTURER';
        }
        if ($roleId === 3 || in_array($name, ['STAFF', 'HÀNH CHÍNH', 'HANH CHINH', 'CSVC'], true)) {
            return 'STAFF';
        }
        if ($roleId === 5 || in_array($name, ['ACCOUNTANT', 'KẾ TOÁN', 'KE TOAN', 'TÀI CHÍNH'], true)) {
            return 'ACCOUNTANT';
        }
        if ($roleId === 6 || in_array($name, ['MEDIA', 'CTV MEDIA', 'TRUYỀN THÔNG', 'TRUYEN THONG'], true)) {
            return 'MEDIA';
        }
        if ($roleId === 7 || in_array($name, ['HEAD_ADMIN', 'TRƯỞNG HÀNH CHÍNH', 'TRUONG HANH CHINH', 'BGH'], true)) {
            return 'HEAD_ADMIN';
        }
        return 'CTV'; // Mặc định role_id = 4: Student / CTV
    }

    /**
     * Trích xuất thông tin hiển thị và toàn bộ quyền hạn của người dùng
     */
    public static function getPermissions(?array $user): array
    {
        if (empty($user) || empty($user['id']) || ($user['role_name'] ?? '') === 'Guest') {
            return [
                'role' => 'GUEST',
                'roleId' => 0,
                'roleNameDisplay' => 'Khách Vãng Lai',
                'roleBadgeClass' => 'bg-secondary',
                'roleDesc' => 'Xem lịch và thông tin công khai',
                'isLoggedIn' => false,
                // Sidebar
                'canViewCalendar' => true,
                'canViewMyShifts' => false,
                'canViewContracts' => false,
                'canViewAccounting' => false,
                'canViewCtvProfiles' => false,
                'canViewMyProfile' => false,
                'canViewSubmissions' => false,
                'canViewEvents' => true,
                'canViewAdminManagement' => false,
                'canViewAuditLogs' => false,
                // Actions
                'canCreateShift' => false,
                'canCreateEvent' => false,
                'canRegisterShift' => false,
                'canSubmitProof' => false,
                'canVerifyLecturer' => false,
                'canSubmitSchoolSigning' => false,
                'canApproveAccounting' => false,
                'canManageRoles' => false,
            ];
        }

        $roleId = (int)($user['role_id'] ?? 4);
        $roleName = $user['role_name'] ?? 'Student';
        $role = self::normalizeRole($roleName, $roleId);

        $roleMetadata = match($role) {
            'ADMIN' => [
                'name' => 'Quản trị viên Hệ thống',
                'badge' => 'bg-danger',
                'desc' => 'Quản trị tài khoản, phân quyền, xem audit log toàn hệ thống'
            ],
            'LECTURER' => [
                'name' => 'Giảng viên / Ban tổ chức',
                'badge' => 'bg-warning text-dark',
                'desc' => 'Tạo sự kiện, đặt phòng, tuyển CTV, duyệt hoàn thành'
            ],
            'STAFF' => [
                'name' => 'Nhân viên Hành chính',
                'badge' => 'bg-info text-dark',
                'desc' => 'Kiểm tra cơ sở vật chất, đối soát xung đột, chuyển duyệt'
            ],
            'HEAD_ADMIN' => [
                'name' => 'Trưởng hành chính / BGH',
                'badge' => 'bg-dark text-white',
                'desc' => 'Phê duyệt cuối đặt phòng/sự kiện, quản lý địa điểm, báo cáo'
            ],
            'ACCOUNTANT' => [
                'name' => 'Kế toán / Quyền lợi',
                'badge' => 'bg-success',
                'desc' => 'Tổng hợp công, lập đợt thanh toán, đối soát STK/VNeID, ghi mã giao dịch'
            ],
            'MEDIA' => [
                'name' => 'CTV Media / Truyền thông',
                'badge' => 'bg-primary',
                'desc' => 'Tác nghiệp ảnh/video, nộp link Google Drive bàn giao sản phẩm'
            ],
            default => [
                'name' => 'Cộng tác viên (CTV)',
                'badge' => 'bg-success-subtle text-success border border-success-subtle',
                'desc' => 'Đăng ký đầu việc, Check-in/out QR, theo dõi thù lao'
            ]
        };

        return [
            'role' => $role,
            'roleId' => $roleId,
            'roleNameDisplay' => $roleMetadata['name'],
            'roleBadgeClass' => $roleMetadata['badge'],
            'roleDesc' => $roleMetadata['desc'],
            'isLoggedIn' => true,

            // 1. Phân quyền hiển thị Menu Sidebar (Role-based Navigation)
            // Lịch sự kiện mở cho mọi vai trò
            'canViewCalendar' => true,
            // Ca trực của tôi: Chỉ dành cho CTV, Media (và Admin hỗ trợ)
            'canViewMyShifts' => in_array($role, ['CTV', 'MEDIA', 'ADMIN'], true),
            // Hồ sơ hợp đồng: CTV xem hợp đồng của mình; GV xem ca của mình; Kế toán/Trưởng HC/Admin xem toàn bộ
            'canViewContracts' => in_array($role, ['CTV', 'MEDIA', 'LECTURER', 'ACCOUNTANT', 'HEAD_ADMIN', 'ADMIN'], true),
            // Nghiệm thu kế toán: CHỈ dành cho Kế toán, Trưởng hành chính và Admin! Tuyệt đối ẩn với CTV và Staff
            'canViewAccounting' => in_array($role, ['ACCOUNTANT', 'HEAD_ADMIN', 'ADMIN'], true),
            // Quản lý hồ sơ CTV: Kế toán, Trưởng HC, Admin đối soát thông tin toàn trường
            'canViewCtvProfiles' => in_array($role, ['ACCOUNTANT', 'HEAD_ADMIN', 'ADMIN'], true),
            // Cập nhật hồ sơ VNeID & STK cá nhân: CTV và Media
            'canViewMyProfile' => in_array($role, ['CTV', 'MEDIA'], true),
            // Minh chứng Drive: Media, CTV nộp link; GV, Trưởng HC, Admin kiểm tra
            'canViewSubmissions' => in_array($role, ['MEDIA', 'CTV', 'LECTURER', 'HEAD_ADMIN', 'ADMIN'], true),
            // Quản lý sự kiện: GV, Staff, Trưởng HC, Admin, CTV, Media (xem danh sách tuyển dụng)
            'canViewEvents' => in_array($role, ['LECTURER', 'STAFF', 'HEAD_ADMIN', 'ADMIN', 'CTV', 'MEDIA'], true),
            // Cấp quyền & Quản trị tài khoản: CHỈ Quản trị viên Admin
            'canViewAdminManagement' => ($role === 'ADMIN'),
            // Xem nhật ký Audit log: Admin và Trưởng hành chính
            'canViewAuditLogs' => in_array($role, ['ADMIN', 'HEAD_ADMIN'], true),

            // 2. Phân quyền Thao tác Nghiệp vụ (Action Permissions)
            // Đặt lịch ca / Tạo sự kiện: Giảng viên/BTC, Trưởng HC, Admin
            'canCreateShift' => in_array($role, ['LECTURER', 'HEAD_ADMIN', 'ADMIN'], true),
            'canCreateEvent' => in_array($role, ['LECTURER', 'HEAD_ADMIN', 'ADMIN'], true),
            // Đăng ký ca trực: Chỉ sinh viên CTV và Media (và Admin demo)
            'canRegisterShift' => in_array($role, ['CTV', 'MEDIA', 'ADMIN'], true),
            // Nộp ảnh check-in hiện trường & link Drive: CTV, Media
            'canSubmitProof' => in_array($role, ['CTV', 'MEDIA'], true),
            // Giảng viên nghiệm thu ca trực (Work Confirmation): Giảng viên/BTC, Trưởng HC, Admin
            'canVerifyLecturer' => in_array($role, ['LECTURER', 'HEAD_ADMIN', 'ADMIN'], true),
            // Kiểm tra CSVC / Trình trường ký duyệt: Nhân viên hành chính, Trưởng HC, Admin
            'canSubmitSchoolSigning' => in_array($role, ['STAFF', 'HEAD_ADMIN', 'ADMIN'], true),
            // Phê duyệt thanh toán / Quyết toán thù lao: Kế toán, Trưởng HC, Admin
            'canApproveAccounting' => in_array($role, ['ACCOUNTANT', 'HEAD_ADMIN', 'ADMIN'], true),
            // Quản lý, thay đổi vai trò (cấp quyền): CHỈ Admin
            'canManageRoles' => ($role === 'ADMIN'),
        ];
    }
}
