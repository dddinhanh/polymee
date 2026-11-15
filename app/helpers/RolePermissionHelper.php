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
        if (in_array($roleId, [3, 7], true) || in_array($name, ['STAFF', 'HEAD_ADMIN', 'HÀNH CHÍNH', 'HANH CHINH', 'HÀNH CHÁNH', 'HANH CHANH', 'CSVC', 'BGH', 'TRƯỞNG HÀNH CHÍNH', 'TRUONG HANH CHINH'], true)) {
            return 'STAFF'; // Gộp Cán bộ CSVC và Trưởng Hành chính / BGH thành 1 Role duy nhất: Hành chính
        }
        if ($roleId === 6 || in_array($name, ['MEDIA', 'CTV MEDIA', 'TRUYỀN THÔNG', 'TRUYEN THONG'], true)) {
            return 'MEDIA';
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
                'canDeleteAnyEvent' => false,
                'canDeleteOwnEvent' => false,
                'canManageCtvProfiles' => false,
                'canCreateNote' => false,
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
                'desc' => 'Tạo sự kiện, đặt phòng, tuyển CTV, duyệt nghiệm thu minh chứng'
            ],
            'STAFF' => [
                'name' => 'Cán bộ Hành chính',
                'badge' => 'bg-info text-dark',
                'desc' => 'Quản lý CSVC, đối soát hồ sơ CTV, ký duyệt số hợp đồng & phê duyệt lệnh chi trả thù lao'
            ],
            'MEDIA' => [
                'name' => 'CTV Media / Truyền thông',
                'badge' => 'bg-primary',
                'desc' => 'Tác nghiệp ảnh/video, nộp link Google Drive bàn giao sản phẩm'
            ],
            default => [
                'name' => 'Cộng tác viên (CTV)',
                'badge' => 'bg-success-subtle text-success border border-success-subtle',
                'desc' => 'Đăng ký ca trực, nộp ảnh minh chứng hiện trường, theo dõi hợp đồng & thù lao'
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
            // Ca trực của tôi: Chỉ dành cho CTV và Media (Admin và Giảng viên không có trang ca trực CTV)
            'canViewMyShifts' => in_array($role, ['CTV', 'MEDIA'], true),
            // Hồ sơ hợp đồng: CTV xem hợp đồng của mình; GV xem ca của mình; Cán bộ Hành chính & Admin xem toàn bộ
            'canViewContracts' => in_array($role, ['CTV', 'MEDIA', 'LECTURER', 'STAFF', 'ADMIN'], true),
            // Nghiệm thu & Quyết toán: Dành cho Cán bộ Hành chính và Admin
            'canViewAccounting' => in_array($role, ['STAFF', 'ADMIN'], true),
            // Quản lý hồ sơ CTV: Giảng viên, Cán bộ Hành chính, Admin đối soát thông tin toàn trường
            'canViewCtvProfiles' => in_array($role, ['LECTURER', 'STAFF', 'ADMIN'], true),
            // Cài đặt tài khoản cá nhân: Toàn bộ người dùng đã đăng nhập đều có quyền
            'canViewProfileSettings' => true,
            // Cập nhật hồ sơ VNeID & STK cá nhân: CTV và Media
            'canViewMyProfile' => in_array($role, ['CTV', 'MEDIA'], true),
            // Minh chứng Drive: Media, CTV nộp link; GV, Hành chính, Admin kiểm tra
            'canViewSubmissions' => in_array($role, ['MEDIA', 'CTV', 'LECTURER', 'STAFF', 'ADMIN'], true),
            // Quản lý sự kiện: GV, Staff, Admin, CTV, Media (xem danh sách tuyển dụng)
            'canViewEvents' => in_array($role, ['LECTURER', 'STAFF', 'ADMIN', 'CTV', 'MEDIA'], true),
            // Cấp quyền & Quản trị tài khoản: CHỈ Quản trị viên Admin
            'canViewAdminManagement' => ($role === 'ADMIN'),
            // Xem nhật ký Audit log: Admin và Cán bộ Hành chính
            'canViewAuditLogs' => in_array($role, ['ADMIN', 'STAFF'], true),

            // 2. Phân quyền Thao tác Nghiệp vụ (Action Permissions)
            // Đặt lịch ca / Tạo sự kiện: Giảng viên/BTC, Cán bộ Hành chính, Admin
            'canCreateShift' => in_array($role, ['LECTURER', 'STAFF', 'ADMIN'], true),
            'canCreateEvent' => in_array($role, ['LECTURER', 'STAFF', 'ADMIN'], true),
            // Đăng ký ca trực: Chỉ sinh viên CTV và Media (và Admin demo)
            'canRegisterShift' => in_array($role, ['CTV', 'MEDIA', 'ADMIN'], true),
            // Nộp ảnh check-in hiện trường & link Drive: CTV, Media
            'canSubmitProof' => in_array($role, ['CTV', 'MEDIA'], true),
            // Giảng viên nghiệm thu ca trực (Work Confirmation): Giảng viên/BTC, Hành chính, Admin
            'canVerifyLecturer' => in_array($role, ['LECTURER', 'STAFF', 'ADMIN'], true),
            // Kiểm tra CSVC / Ký duyệt hợp đồng: Cán bộ Hành chính, Admin
            'canSubmitSchoolSigning' => in_array($role, ['STAFF', 'ADMIN'], true),
            // Phê duyệt thanh toán / Quyết toán thù lao: Cán bộ Hành chính, Admin
            'canApproveAccounting' => in_array($role, ['STAFF', 'ADMIN'], true),
            // Quản lý hồ sơ CTV: Giảng viên, Cán bộ Hành chính, Admin
            'canManageCtvProfiles' => in_array($role, ['LECTURER', 'STAFF', 'ADMIN'], true),
            // Xóa sự kiện: Cấp bậc từ Giảng viên trở lên
            // - Cán bộ Hành chính và Admin: Xóa được mọi sự kiện
            // - Giảng viên: Chỉ xóa được sự kiện do mình đặt
            'canDeleteAnyEvent' => in_array($role, ['STAFF', 'ADMIN'], true),
            'canDeleteOwnEvent' => in_array($role, ['LECTURER', 'STAFF', 'ADMIN'], true),
            // Thêm/Xóa Ghi chú lịch hẹn: Cán bộ Hành chính, Giảng viên, Admin (CTV KHÔNG có quyền)
            'canCreateNote' => in_array($role, ['LECTURER', 'STAFF', 'ADMIN'], true),
            // Quản lý, thay đổi vai trò (cấp quyền): CHỈ Admin
            'canManageRoles' => ($role === 'ADMIN'),
        ];
    }
}

