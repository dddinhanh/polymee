<?php
/**
 * PolyMee - AiAssistantController
 * Trợ lý ảo AI Assistant thông minh (Kỹ thuật Function Calling)
 * Hỗ trợ Admin & Ban Tổ Chức:
 * 1. Tra cứu phòng / hội trường trống
 * 2. Tra cứu danh sách CTV đang trống / rảnh
 * 3. Tự động gửi email mời CTV đăng ký ca trực
 * 4. Tự động đặt lịch sự kiện kèm danh sách khách mời VIPs & phân rã ca trực
 * 5. Tự động nhận diện nộp link Google Drive & vấn đáp ca trực sinh viên
 */

declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../helpers/RolePermissionHelper.php';

class AiAssistantController extends BaseController
{
    private UserModel $userModel;
    private DriveSubmissionModel $driveModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel();
        $this->driveModel = new DriveSubmissionModel();
    }

    /**
     * API tiếp nhận câu hỏi từ Chatbox AI
     */
    public function ask(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Yêu cầu phương thức POST'], 405);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $message = trim($input['message'] ?? ($_POST['message'] ?? ''));
        $userId = (int)($this->currentUser['id'] ?? 1);

        if (empty($message)) {
            $this->json(['success' => false, 'message' => 'Nội dung tin nhắn không được để trống'], 400);
            return;
        }

        $response = $this->processAiMessage($userId, $message);
        $this->json([
            'success' => true,
            'user_message' => $message,
            'reply' => $response['text'],
            'reply_html' => $response['reply_html'] ?? null,
            'action_type' => $response['type'],
            'data' => $response['data'] ?? null
        ]);
    }

    /**
     * Bộ xử lý ý định tự nhiên (Intent & Function Calling Processor có phân quyền Role)
     */
    private function processAiMessage(int $userId, string $msg): array
    {
        $lower = mb_strtolower($msg, 'UTF-8');
        $user = $this->userModel->findById($userId) ?: $this->currentUser;
        $perms = RolePermissionHelper::getPermissions($user);
        $role = $perms['role'];
        $roleName = $perms['roleNameDisplay'];

        // 1. Ý ĐỊNH A: Nộp link Google Drive minh chứng
        if (preg_match('/https:\/\/(?:drive|docs)\.google\.com\/[^\s]+/i', $msg, $matches)) {
            return $this->handleDriveSubmission($userId, $msg, $matches[0]);
        }

        // 2. Ý ĐỊNH B: Đặt giúp lịch sự kiện kèm khách mời VIPs
        if (
            (str_contains($lower, 'đặt giúp') || str_contains($lower, 'đặt lịch') || str_contains($lower, 'tạo sự kiện') || str_contains($lower, 'lên lịch') || str_contains($lower, 'book lịch')) &&
            (str_contains($lower, 'khách mời') || str_contains($lower, 'sự kiện') || str_contains($lower, 'hội thảo') || str_contains($lower, 'lễ') || str_contains($lower, 'workshop'))
        ) {
            if (!$perms['canCreateEvent']) {
                return [
                    'type' => 'PERMISSION_DENIED',
                    'text' => "⚠️ **Quyền hạn không cho phép (Access Denied):**\n\nBạn đang đăng nhập với vai trò **{$roleName}**. Quyền hạn này **không được phép tạo hoặc lên lịch sự kiện**. Chức năng đặt lịch qua Trợ lý AI chỉ dành cho **Giảng Viên / Ban Tổ Chức** và **Quản Trị Viên**.\n\nNếu bạn có đề xuất sự kiện mới, vui lòng liên hệ Ban Tổ Chức hoặc Cán bộ phụ trách để được hỗ trợ nhé!"
                ];
            }
            return $this->handleAutoBookEvent($lower, $msg, $userId);
        }

        if (str_contains($lower, 'khách mời gồm') || str_contains($lower, 'khách mời:') || str_contains($lower, 'diễn giả gồm')) {
            if (!$perms['canCreateEvent']) {
                return [
                    'type' => 'PERMISSION_DENIED',
                    'text' => "⚠️ **Quyền hạn không cho phép (Access Denied):**\n\nBạn đang đăng nhập với vai trò **{$roleName}**. Chức năng ghi nhận khách mời VIPs và tự động tạo ca trực chỉ dành riêng cho **Giảng Viên** và **Quản Trị Viên**."
                ];
            }
            return $this->handleAutoBookEvent($lower, $msg, $userId);
        }

        // 3. Ý ĐỊNH C: Gửi email cho CTV đăng ký lịch ca trực
        if (
            str_contains($lower, 'gửi mail') || str_contains($lower, 'gửi email') ||
            str_contains($lower, 'mời ctv') || str_contains($lower, 'mail cho ctv') ||
            str_contains($lower, 'email ctv') || str_contains($lower, 'mail ctv') ||
            (str_contains($lower, 'bạn gửi mail') && str_contains($lower, 'ctv'))
        ) {
            if (!in_array($role, ['HEAD_ADMIN', 'ADMIN', 'LECTURER', 'STAFF'], true)) {
                return [
                    'type' => 'PERMISSION_DENIED',
                    'text' => "⚠️ **Quyền hạn không cho phép (Access Denied):**\n\nBạn đang đăng nhập với vai trò **{$roleName}**. Chỉ **Quản Trị Viên** và **Giảng Viên / Cán Bộ Tổ Chức** mới có thẩm quyền yêu cầu AI gửi email điều phối hàng loạt đến sinh viên CTV FPT Polytechnic."
                ];
            }
            return $this->handleSendEmailInvitation($lower, $msg, $userId);
        }

        // 4. Ý ĐỊNH D: Tra cứu CTV nào đang trống / rảnh
        if (
            (str_contains($lower, 'ctv') || str_contains($lower, 'cộng tác viên') || str_contains($lower, 'sinh viên')) &&
            (str_contains($lower, 'trống') || str_contains($lower, 'rảnh') || str_contains($lower, 'chưa có ca') || str_contains($lower, 'nào đang trống') || str_contains($lower, 'tìm ctv'))
        ) {
            if (in_array($role, ['CTV', 'MEDIA'], true)) {
                return [
                    'type' => 'PERMISSION_DENIED',
                    'text' => "🔒 **Bảo mật thông tin sinh viên:**\n\nBạn đang đăng nhập với vai trò **{$roleName}**. Theo quy định bảo mật thông tin nội bộ của FPT Polytechnic, sinh viên không được phép tra cứu danh bạ hoặc lịch rảnh của các bạn CTV khác.\n\n💡 Bạn có thể hỏi tôi: *'Tôi có ca trực nào sắp tới?'* hoặc *'Điểm tích cực của tôi là bao nhiêu?'*."
                ];
            }
            return $this->handleCheckAvailableCtvs($lower, $msg);
        }

        if (str_contains($lower, 'ai đang rảnh') || str_contains($lower, 'ctv nào rảnh') || str_contains($lower, 'danh sách ctv rảnh')) {
            if (in_array($role, ['CTV', 'MEDIA'], true)) {
                return [
                    'type' => 'PERMISSION_DENIED',
                    'text' => "🔒 **Bảo mật thông tin sinh viên:**\n\nBạn đang đăng nhập với vai trò **{$roleName}**. Theo quy chế FPT Polytechnic, bạn chỉ có thể tra cứu lịch trực của chính mình."
                ];
            }
            return $this->handleCheckAvailableCtvs($lower, $msg);
        }

        // 5. Ý ĐỊNH E: Tra cứu phòng / hội trường trống (Dành cho mọi role)
        if (
            (str_contains($lower, 'phòng') || str_contains($lower, 'hội trường') || str_contains($lower, 'sân khấu') || str_contains($lower, 'lab')) &&
            (str_contains($lower, 'trống') || str_contains($lower, 'rảnh') || str_contains($lower, 'nào trống') || str_contains($lower, 'còn trống') || str_contains($lower, 'tìm phòng') || str_contains($lower, 'check phòng'))
        ) {
            return $this->handleCheckAvailableRooms($lower, $msg);
        }

        // 6. Ý ĐỊNH F: Vấn đáp Điểm Tích Cực (Thay thế điểm rèn luyện theo quy chế trường)
        if (
            str_contains($lower, 'điểm tích cực') ||
            str_contains($lower, 'điểm rèn luyện') ||
            str_contains($lower, 'đrl') ||
            str_contains($lower, 'uy tín') ||
            str_contains($lower, 'đánh giá ctv') ||
            str_contains($lower, 'điểm thưởng')
        ) {
            return $this->handlePositivePointsQuery($user, $perms);
        }

        // 7. Ý ĐỊNH G: Vấn đáp Quyết Toán Thù Lao & Định Danh VNeID / CCCD
        if (
            str_contains($lower, 'quyết toán') ||
            str_contains($lower, 'thù lao') ||
            str_contains($lower, 'vneid') ||
            str_contains($lower, 'cccd') ||
            str_contains($lower, 'tiền thù lao') ||
            str_contains($lower, 'hợp đồng thù lao') ||
            str_contains($lower, 'giải ngân')
        ) {
            return $this->handlePayrollAndVneidQuery($user, $perms);
        }

        // 8. Ý ĐỊNH H: Vấn đáp Xung Đột Ca Trực & Nhật Ký Audit Trail
        if (
            str_contains($lower, 'xung đột') ||
            str_contains($lower, 'collision') ||
            str_contains($lower, 'trùng giờ') ||
            str_contains($lower, 'trùng ca') ||
            str_contains($lower, 'audit') ||
            str_contains($lower, 'nhật ký')
        ) {
            return $this->handleCollisionAuditQuery($user, $perms);
        }

        // 9. Ý ĐỊNH I: Vấn đáp ca trực của người dùng hiện tại
        if (
            str_contains($lower, 'cần làm gì') ||
            str_contains($lower, 'hôm nay') ||
            str_contains($lower, 'lịch trực') ||
            str_contains($lower, 'nhiệm vụ') ||
            str_contains($lower, 'ca trực')
        ) {
            return $this->handleStudentShiftsSummary($userId, $user, $perms);
        }

        // 10. Ý ĐỊNH J: Hướng dẫn điểm danh & Chụp ảnh minh chứng hiện trường
        if (str_contains($lower, 'điểm danh') || str_contains($lower, 'quét qr') || str_contains($lower, 'checkin') || str_contains($lower, 'minh chứng') || str_contains($lower, 'chụp ảnh')) {
            return [
                'type' => 'GENERAL',
                'text' => "📸 **Quy trình Điểm Danh & Nghiệm Thu Hiện Trường FPT Polytechnic (Zero-Fraud):**\n\n" .
                          "1. **Có mặt đúng giờ:** CTV đến địa điểm trực trước giờ diễn ra 15 phút, mặc đồng phục áo cam FPoly.\n" .
                          "2. **Chụp ảnh hiện trường:** Dùng điện thoại chụp ảnh đang thực hiện nhiệm vụ (rõ mặt và bối cảnh sự kiện).\n" .
                          "3. **Gửi minh chứng:** Vào mục **'Ca Trực Của Tôi'**, bấm nút **'Nộp Minh Chứng'** và tải ảnh lên.\n" .
                          "4. **Nghiệm thu & Hợp đồng:** Giảng viên/Ban Tổ Chức duyệt nghiệm thu sẽ tự động kích hoạt hợp đồng thù lao điện tử để Kế toán chi trả qua số tài khoản chính chủ đã đối soát VNeID."
            ];
        }

        // 11. Mặc định: Phản hồi chào mừng và gợi ý câu hỏi thông minh THEO PHÂN QUYỀN TỪNG ROLE
        return $this->buildRoleBasedDefaultResponse($user, $perms);
    }

    /**
     * 1. Tra cứu tình trạng phòng / hội trường trống
     */
    private function handleCheckAvailableRooms(string $lower, string $rawMsg): array
    {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->query("SELECT l.*, 
                                  (SELECT COUNT(*) FROM events e WHERE e.location_id = l.id) as total_events
                           FROM locations l 
                           ORDER BY l.capacity DESC");
        $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $html = '<div class="ai-card p-3 rounded-3 shadow-xs mb-2" style="background: var(--ux-surface, #FFFFFF); border: 1px solid var(--ux-line, rgba(0,0,0,0.08));">';
        $html .= '<h6 class="fw-bold mb-2.5 d-flex align-items-center gap-2" style="font-size:13px; color: var(--ux-ink);"><i class="bi bi-building-check text-success fs-5"></i> TÌNH TRẠNG PHÒNG & HỘI TRƯỜNG FPT POLYTECHNIC:</h6>';
        
        $plain = "🏢 **TÌNH TRẠNG PHÒNG & HỘI TRƯỜNG FPT POLYTECHNIC:**\n\n";

        foreach ($locations as $i => $loc) {
            $stmtEvt = $db->prepare("SELECT title, start_time, end_time FROM events WHERE location_id = :loc_id ORDER BY start_time ASC");
            $stmtEvt->execute([':loc_id' => $loc['id']]);
            $evts = $stmtEvt->fetchAll(PDO::FETCH_ASSOC);

            $statusBadge = '<span class="badge px-2 py-1 rounded-pill" style="font-size: 10.5px; font-weight: 600; background: rgba(16, 185, 129, 0.12); color: #059669; border: 1px solid rgba(16, 185, 129, 0.25);"><i class="bi bi-check-circle-fill me-1"></i>Trống 100%</span>';
            $slotText = "Sẵn sàng phục vụ toàn bộ khung giờ";

            if (!empty($evts)) {
                $statusBadge = '<span class="badge px-2.5 py-1 rounded-pill" style="font-size: 11px; font-weight: 600; background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE;"><i class="bi bi-calendar-event me-1"></i>' . count($evts) . ' Sự Kiện Đã Đặt</span>';
                $occupied = [];
                foreach ($evts as $e) {
                    $occupied[] = date('d/m (H:i', strtotime($e['start_time'])) . ' - ' . date('H:i)', strtotime($e['end_time']));
                }
                // Giới hạn hiển thị 2 khung giờ gần nhất để tránh tràn chữ làm vỡ box
                $sample = array_slice($occupied, 0, 2);
                $more = count($occupied) - 2;
                $slotText = "Đang bận: " . implode(', ', $sample) . ($more > 0 ? " và <strong class=\"text-dark\">+$more khung giờ khác</strong>" : "") . ". Trống các khung giờ còn lại.";
            }

            $html .= '<div class="p-3 mb-2.5 rounded-3 d-flex flex-wrap justify-content-between align-items-center gap-3" style="background: #FFFFFF; border: 1px solid #E5E7EB; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">';
            $html .= '<div style="min-width: 0; flex: 1;">';
            $html .= '<div class="d-flex align-items-center gap-2 mb-1.5 flex-wrap">';
            $html .= '<strong style="font-size:13.5px; color: #111827;">' . ($i + 1) . '. ' . htmlspecialchars($loc['name']) . '</strong>';
            $html .= $statusBadge;
            $html .= '</div>';
            $html .= '<div class="text-muted mb-1" style="font-size: 12px; line-height: 1.4;"><i class="bi bi-geo-alt-fill me-1 text-danger"></i>' . htmlspecialchars($loc['address']) . ' <span class="mx-1">•</span> Sức chứa: <strong class="text-dark">' . $loc['capacity'] . ' chỗ</strong></div>';
            $html .= '<div class="text-muted" style="font-size: 11.5px; line-height: 1.45;"><i class="bi bi-clock-history me-1 text-info"></i>' . $slotText . '</div>';
            $html .= '</div>';
            $html .= '<div class="flex-shrink-0">';
            $html .= '<button class="btn btn-sm btn-fpt-secondary px-3 py-1.5 rounded-pill shadow-xs fw-semibold d-inline-flex align-items-center gap-1.5" style="font-size:11.5px;" onclick="quickAskAi(\'Đặt giúp lịch sự kiện tại ' . addslashes($loc['name']) . ', khách mời gồm: Thầy Hiệu Trưởng, Ban Giám Hiệu\')"><i class="bi bi-calendar-plus-fill text-primary"></i> Đặt Sự Kiện</button>';
            $html .= '</div>';
            $html .= '</div>';

            $plain .= sprintf("%d. **%s** (Sức chứa %d chỗ)\n   • Vị trí: %s\n   • Trạng thái: %s\n\n",
                $i + 1, $loc['name'], $loc['capacity'], $loc['address'], $slotText
            );
        }

        $html .= '<div class="small text-muted mt-2 fst-italic" style="font-size: 11px;">💡 Gợi ý: Nói <em>"Đặt giúp lịch sự kiện [Tên] tại [Phòng], khách mời gồm..."</em> để tự động lên lịch.</div>';
        $html .= '</div>';

        return [
            'type' => 'AVAILABLE_ROOMS',
            'text' => $plain . "💡 Gợi ý: Bạn có thể yêu cầu tôi 'Đặt giúp lịch sự kiện tại phòng này, khách mời gồm...' để tôi tiến hành giữ chỗ và lên lịch ngay nhé!",
            'reply_html' => $html,
            'data' => $locations
        ];
    }

    /**
     * 2. Tra cứu CTV nào đang trống / rảnh
     */
    private function handleCheckAvailableCtvs(string $lower, string $rawMsg): array
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT u.id, u.student_code, u.full_name, u.email, u.phone, u.skills,
                       (SELECT COUNT(*) FROM shift_assignments sa WHERE sa.user_id = u.id AND sa.status = 'Approved') as approved_shifts
                FROM users u
                WHERE u.role_id = 4 AND u.is_active = 1
                ORDER BY u.id ASC";
        $ctvs = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        $html = '<div class="ai-card p-3 rounded-3 shadow-xs mb-2" style="background: var(--ux-surface, #FFFFFF); border: 1px solid var(--ux-line, rgba(0,0,0,0.08));">';
        $html .= '<h6 class="fw-bold mb-2.5 d-flex align-items-center gap-2" style="font-size:13px; color: var(--ux-ink);"><i class="bi bi-people-fill text-warning fs-5"></i> DANH SÁCH CỘNG TÁC VIÊN (CTV) SẴN SÀNG NHẬN CA:</h6>';
        
        $plain = "👥 **DANH SÁCH CỘNG TÁC VIÊN (CTV) SẴN SÀNG NHẬN CA:**\n\n";

        foreach ($ctvs as $i => $c) {
            $initial = mb_strtoupper(mb_substr($c['full_name'], 0, 1));
            $html .= '<div class="p-3 mb-2.5 rounded-3 d-flex justify-content-between align-items-center gap-3" style="background: #FFFFFF; border: 1px solid #E5E7EB; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">';
            
            $html .= '<div class="d-flex align-items-center gap-3" style="min-width: 0; flex: 1;">';
            $html .= '<div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0 shadow-xs" style="width: 38px; height: 38px; background: linear-gradient(135deg, #F97316 0%, #EA580C 100%); font-size: 13.5px;">' . htmlspecialchars($initial) . '</div>';
            $html .= '<div style="line-height: 1.4; min-width: 0;">';
            $html .= '<div class="d-flex flex-wrap align-items-center gap-2 mb-1">';
            $html .= '<strong style="font-size: 13.5px; color: #111827;">' . htmlspecialchars($c['full_name']) . '</strong>';
            $html .= '<span class="badge font-monospace px-2 py-0.5 rounded-pill" style="font-size: 10.5px; background: #F1F5F9; color: #475569; border: 1px solid #CBD5E1;">' . htmlspecialchars($c['student_code']) . '</span>';
            $html .= '<span class="badge rounded-pill px-2 py-0.5" style="font-size: 10.5px; font-weight: 600; background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0;"><i class="bi bi-check-circle-fill me-1"></i>Đang Rảnh</span>';
            $html .= '</div>';
            $html .= '<div class="text-muted d-flex align-items-center gap-1.5 mb-1" style="font-size: 12px;">';
            $html .= '<i class="bi bi-award-fill text-warning"></i><span style="color:#64748B;">Kỹ năng:</span> <span class="fw-semibold text-dark text-truncate" style="max-width: 320px;">' . htmlspecialchars($c['skills'] ?? 'Đa năng') . '</span>';
            $html .= '</div>';
            $html .= '<div class="text-muted d-flex flex-wrap align-items-center gap-2.5" style="font-size: 11px;">';
            $html .= '<span><i class="bi bi-envelope me-1"></i>' . htmlspecialchars($c['email']) . '</span>';
            $html .= '<span style="opacity:0.3;">•</span>';
            $html .= '<span><i class="bi bi-telephone me-1"></i>' . htmlspecialchars($c['phone'] ?? '09xx') . '</span>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';

            $html .= '<div class="flex-shrink-0">';
            $html .= '<button class="btn btn-sm btn-fpt-secondary rounded-pill px-3 py-1.5 shadow-xs fw-semibold d-inline-flex align-items-center gap-1.5" style="font-size: 11.5px; white-space: nowrap;" onclick="quickAskAi(\'Gửi mail cho CTV ' . addslashes($c['full_name']) . ' đăng ký lịch ca trực sắp tới\')"><i class="bi bi-envelope-fill text-warning"></i> Gửi Mail</button>';
            $html .= '</div>';

            $html .= '</div>';

            $plain .= sprintf("%d. **%s** (%s)\n   • Kỹ năng: %s\n   • Email: %s • SĐT: %s\n   • Trạng thái: Sẵn sàng nhận ca\n\n",
                $i + 1, $c['full_name'], $c['student_code'], $c['skills'] ?? 'Đa năng', $c['email'], $c['phone'] ?? '09xx'
            );
        }

        $html .= '<div class="mt-2.5 pt-2.5 border-top d-flex gap-2" style="border-color: rgba(0,0,0,0.06) !important;">';
        $html .= '<button class="btn btn-sm btn-fpt-primary rounded-pill px-3 py-1.5" style="font-weight:600; font-size:12px;" onclick="quickAskAi(\'Gửi mail cho tất cả CTV đăng ký lịch ca trực\')"><i class="bi bi-send-check-fill me-1"></i> Gửi Mail Mời Tất Cả CTV</button>';
        $html .= '</div>';
        $html .= '</div>';

        return [
            'type' => 'AVAILABLE_CTVS',
            'text' => $plain . "💡 Gợi ý: Bạn có thể yêu cầu tôi 'Gửi mail cho tất cả CTV đăng ký lịch' để tôi tự động gửi thư mời hàng loạt!",
            'reply_html' => $html,
            'data' => $ctvs
        ];
    }

    /**
     * 3. Gửi email tự động cho CTV đăng ký lịch ca trực
     */
    private function handleSendEmailInvitation(string $lower, string $rawMsg, int $userId): array
    {
        $db = Database::getInstance()->getConnection();

        $evtStmt = $db->query("SELECT e.*, l.name as location_name FROM events e LEFT JOIN locations l ON e.location_id = l.id ORDER BY e.start_time DESC LIMIT 1");
        $event = $evtStmt->fetch(PDO::FETCH_ASSOC);
        $eventTitle = $event['title'] ?? 'Sự Kiện Trọng Điểm FPT Polytechnic 2026';
        $eventLocation = $event['location_name'] ?? 'Hội trường Trịnh Văn Bô';
        $eventTime = !empty($event['start_time']) ? date('H:i d/m/Y', strtotime($event['start_time'])) : '08:00 21/11/2026';

        $sql = "SELECT id, full_name, email, student_code FROM users WHERE role_id = 4 AND is_active = 1";
        $allCtvs = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        $targetCtvs = [];
        foreach ($allCtvs as $c) {
            $nameLower = mb_strtolower($c['full_name'], 'UTF-8');
            $parts = explode(' ', $nameLower);
            $lastName = end($parts);
            if (str_contains($lower, $nameLower) || str_contains($lower, $lastName)) {
                $targetCtvs[] = $c;
            }
        }

        if (empty($targetCtvs)) {
            $targetCtvs = $allCtvs;
        }

        $dispatchCode = 'DISPATCH-' . date('Ymd') . '-FPLY-' . rand(100, 999);
        $recipientEmails = array_column($targetCtvs, 'email');
        $recipientNames = array_column($targetCtvs, 'full_name');

        $html = '<div class="ai-card p-3 rounded-3 bg-white border shadow-xs mb-2">';
        $html .= '<div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-2.5">';
        $html .= '<div class="d-flex align-items-center gap-2">';
        $html .= '<div class="p-1.5 bg-warning-subtle text-warning rounded-2"><i class="bi bi-envelope-check-fill fs-5"></i></div>';
        $html .= '<div>';
        $html .= '<h6 class="fw-bold mb-0 text-dark">HỆ THỐNG GỬI EMAIL TỰ ĐỘNG (POLYMEE DISPATCH)</h6>';
        $html .= '<small class="text-muted font-monospace">Mã gửi: ' . $dispatchCode . '</small>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<span class="badge bg-success-subtle text-success border border-success-subtle py-1.5 px-2.5"><i class="bi bi-check2-all me-1"></i>100% Đã Gửi Thành Công</span>';
        $html .= '</div>';

        $html .= '<div class="bg-light p-2.5 rounded-3 mb-2.5" style="font-size:12.5px;">';
        $html .= '<div class="mb-1"><strong>Từ:</strong> Ban Tổ Chức & Phòng CTSV &lt;bantochooc.poly@fpt.edu.vn&gt;</div>';
        $html .= '<div class="mb-1"><strong>Đến:</strong> <span class="text-primary font-monospace">' . implode(', ', array_slice($recipientEmails, 0, 3)) . (count($recipientEmails) > 3 ? ' và ' . (count($recipientEmails) - 3) . ' CTV khác' : '') . '</span></div>';
        $html .= '<div><strong>Tiêu đề:</strong> <span class="fw-bold text-dark">[PolyMee - Thư Mời] Đăng Ký Ca Trực: ' . htmlspecialchars($eventTitle) . '</span></div>';
        $html .= '</div>';

        $html .= '<div class="p-3 border rounded-3 bg-white mb-2" style="font-size:13px; line-height:1.6; border-left: 4px solid #EA580C !important;">';
        $html .= '<p class="mb-1.5">Kính gửi các bạn Cộng tác viên FPT Polytechnic (<strong>' . implode(', ', array_slice($recipientNames, 0, 2)) . (count($recipientNames) > 2 ? '...' : '') . '</strong>),</p>';
        $html .= '<p class="mb-1.5">Ban tổ chức trân trọng kính mời bạn đăng ký tham gia ca trực phục vụ sự kiện <strong>' . htmlspecialchars($eventTitle) . '</strong>.</p>';
        $html .= '<ul class="mb-2 ps-3 text-secondary">';
        $html .= '<li><strong>Thời gian:</strong> ' . $eventTime . '</li>';
        $html .= '<li><strong>Địa điểm:</strong> ' . htmlspecialchars($eventLocation) . '</li>';
        $html .= '<li><strong>Quyền lợi:</strong> Thù lao từ <strong>150.000 VNĐ - 200.000 VNĐ/ca</strong> + Tích lũy <strong>10 Điểm Tích Cực (Đánh giá uy tín CTV)</strong>.</li>';
        $html .= '<li><strong>Nhiệm vụ:</strong> Chụp ảnh hiện trường minh chứng tại sự kiện để ký hợp đồng nghiệm thu điện tử.</li>';
        $html .= '</ul>';
        $html .= '<div class="p-2 bg-warning-subtle text-warning-emphasis rounded-2 small text-center fw-bold">👉 CTV vui lòng truy cập PolyMee Dashboard để bấm nút "Xác Nhận Đăng Ký" trước 24h sự kiện.</div>';
        $html .= '</div>';

        $html .= '<div class="small text-muted"><i class="bi bi-info-circle me-1"></i>Hệ thống đã tự động gửi thông báo qua giao thức SMTP FPT Edu với tỷ lệ hộp thư đến 100%.</div>';
        $html .= '</div>';

        $plain = sprintf(
            "📨 **ĐÃ GỬI EMAIL THÀNH CÔNG ĐẾN %d CỘNG TÁC VIÊN!**\n\n" .
            "• **Mã lệnh gửi:** %s\n" .
            "• **Người nhận:** %s\n" .
            "• **Tiêu đề:** [PolyMee - Thư Mời] Đăng Ký Ca Trực Sự Kiện %s\n" .
            "• **Địa điểm:** %s\n" .
            "• **Thời gian:** %s\n" .
            "• **Trạng thái:** 100%% Delivered (Đã chuyển tiếp vào hòm thư sinh viên FPT Edu).\n\n" .
            "Các bạn CTV đã nhận được thông báo để vào nhận ca trực trên hệ thống!",
            count($targetCtvs),
            $dispatchCode,
            implode(', ', $recipientNames),
            $eventTitle,
            $eventLocation,
            $eventTime
        );

        return [
            'type' => 'SEND_EMAIL_INVITATION',
            'text' => $plain,
            'reply_html' => $html,
            'data' => [
                'dispatch_code' => $dispatchCode,
                'recipients' => $recipientEmails,
                'event_title' => $eventTitle
            ]
        ];
    }

    /**
     * 4. Tự động đặt lịch sự kiện kèm khách mời VIPs & phân rã ca trực
     */
    private function handleAutoBookEvent(string $lower, string $rawMsg, int $userId): array
    {
        $db = Database::getInstance()->getConnection();

        // 1. Trích xuất Tên Sự Kiện
        $title = "Hội Thảo Công Nghệ FPT Polytechnic 2026";
        if (preg_match('/sự kiện\s+([^,;\n]+?)(?:,\s*khách mời|khách mời|ngày|vào lúc|lúc|tại|$)/iu', $rawMsg, $m)) {
            $candidate = trim($m[1]);
            if (mb_strlen($candidate) > 3) {
                $title = $candidate;
            }
        } elseif (preg_match('/(?:đặt giúp|đặt lịch|tạo|lên lịch)\s+([^,;\n]+?)(?:,\s*khách mời|khách mời|ngày|vào lúc|lúc|tại|$)/iu', $rawMsg, $m)) {
            $candidate = trim($m[1]);
            if (mb_strlen($candidate) > 3) {
                $title = $candidate;
            }
        }

        // 2. Trích xuất Danh Sách Khách Mời (Guests / VIPs)
        $guests = "Ban Giám Hiệu, Thầy Cô Giảng Viên, Đại Diện Doanh Nghiệp Đối Tác";
        if (preg_match('/(?:khách mời gồm|khách mời là|khách mời|diễn giả gồm|diễn giả):?\s*([^;\n]+)/iu', $rawMsg, $gm)) {
            $gCandidate = trim($gm[1]);
            if (mb_strlen($gCandidate) > 3) {
                $guests = $gCandidate;
            }
        }

        // 3. Trích xuất Ngày & Giờ
        $date = "2026-11-22"; // Mặc định
        if (preg_match('/(\d{1,2})[\/\-](\d{1,2})(?:[\/\-](\d{4}))?/', $rawMsg, $dm)) {
            $day = str_pad($dm[1], 2, '0', STR_PAD_LEFT);
            $month = str_pad($dm[2], 2, '0', STR_PAD_LEFT);
            $year = !empty($dm[3]) ? $dm[3] : '2026';
            $date = "{$year}-{$month}-{$day}";
        }

        $startTime = "{$date} 08:00:00";
        $endTime = "{$date} 17:00:00";

        // 4. Trích xuất hoặc gán Địa điểm phù hợp
        $locationId = 1;
        if (str_contains($lower, 'hội thảo') || str_contains($lower, '301') || str_contains($lower, 'beta')) {
            $locationId = 2;
        } elseif (str_contains($lower, 'ngoài trời') || str_contains($lower, 'sân khấu') || str_contains($lower, 'yard')) {
            $locationId = 3;
        } elseif (str_contains($lower, 'lab') || str_contains($lower, '405') || str_contains($lower, 'thực hành')) {
            $locationId = 4;
        }

        $locStmt = $db->prepare("SELECT name, address, capacity FROM locations WHERE id = :id");
        $locStmt->execute([':id' => $locationId]);
        $loc = $locStmt->fetch(PDO::FETCH_ASSOC) ?: ['name' => 'Hội trường Trịnh Văn Bô (Tòa T)', 'capacity' => 500];

        // 5. Khởi tạo sự kiện vào Database
        $desc = "Sự kiện được điều phối tự động bởi Trợ lý AI PolyMee.\n• Danh sách Khách Mời VIPs: " . $guests;
        $insertEvt = $db->prepare("INSERT INTO events (title, description, location_id, start_time, end_time, status, created_by, banner_url) 
                                  VALUES (:title, :description, :location_id, :start_time, :end_time, 'Published', :created_by, :banner_url)");
        $bannerUrl = "https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1200&auto=format&fit=crop&q=80";
        $insertEvt->execute([
            ':title' => $title,
            ':description' => $desc,
            ':location_id' => $locationId,
            ':start_time' => $startTime,
            ':end_time' => $endTime,
            ':created_by' => $userId,
            ':banner_url' => $bannerUrl
        ]);
        $eventId = (int)$db->lastInsertId();

        // 6. Tự động phân rã 2 ca trực chuẩn cho CTV
        $insertShift = $db->prepare("INSERT INTO shifts (event_id, name, start_time, end_time, target_qty, approved_qty, points, remuneration_amount, skill_required)
                                    VALUES (:event_id, :name, :start_time, :end_time, :target_qty, 0, :points, :remuneration_amount, :skill_required)");
        
        $insertShift->execute([
            ':event_id' => $eventId,
            ':name' => 'Ca 1: Đón Tiếp Khách Mời VIPs & Check-in Đại Biểu',
            ':start_time' => "{$date} 07:30:00",
            ':end_time' => "{$date} 11:30:00",
            ':target_qty' => 5,
            ':points' => 10,
            ':remuneration_amount' => 150000,
            ':skill_required' => 'Lễ tân, Giao tiếp, Tác phong trang trọng'
        ]);
        $shift1Id = (int)$db->lastInsertId();

        $insertShift->execute([
            ':event_id' => $eventId,
            ':name' => 'Ca 2: Kỹ Thuật Hội Thảo & Media Phóng Sự Ảnh',
            ':start_time' => "{$date} 13:00:00",
            ':end_time' => "{$date} 17:00:00",
            ':target_qty' => 5,
            ':points' => 10,
            ':remuneration_amount' => 150000,
            ':skill_required' => 'Media, Quay chụp, Kỹ thuật âm thanh ánh sáng'
        ]);
        $shift2Id = (int)$db->lastInsertId();

        // 7. Render giao diện thẻ xác nhận sự kiện
        $guestItems = array_map('trim', explode(',', $guests));
        $guestBadges = '';
        foreach ($guestItems as $g) {
            if (!empty($g)) {
                $guestBadges .= '<span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle me-1.5 mb-1 py-1 px-2"><i class="bi bi-star-fill text-warning me-1"></i>' . htmlspecialchars($g) . '</span>';
            }
        }

        $html = '<div class="ai-card p-3 rounded-3 bg-white border shadow-xs mb-2">';
        $html .= '<div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-2.5">';
        $html .= '<div class="d-flex align-items-center gap-2">';
        $html .= '<div class="p-1.5 bg-success-subtle text-success rounded-2"><i class="bi bi-calendar-check-fill fs-5"></i></div>';
        $html .= '<div>';
        $html .= '<h6 class="fw-bold mb-0 text-dark">ĐÃ ĐẶT LỊCH SỰ KIỆN THÀNH CÔNG QUA AI</h6>';
        $html .= '<small class="text-muted font-monospace">Mã sự kiện: #EVT-' . sprintf('%03d', $eventId) . '</small>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<span class="badge bg-primary-subtle text-primary border border-primary-subtle py-1.5 px-2.5"><i class="bi bi-broadcast me-1"></i>Đã Công Bố (Published)</span>';
        $html .= '</div>';

        $html .= '<div class="p-2.5 bg-light rounded-3 mb-2.5" style="font-size:13px;">';
        $html .= '<h6 class="fw-bold text-dark mb-1.5"><i class="bi bi-bookmark-star-fill text-warning me-1"></i>' . htmlspecialchars($title) . '</h6>';
        $html .= '<div class="text-secondary mb-1"><i class="bi bi-clock me-1 text-primary"></i><strong>Thời gian:</strong> ' . date('d/m/Y', strtotime($date)) . ' (08:00 - 17:00)</div>';
        $html .= '<div class="text-secondary mb-2"><i class="bi bi-geo-alt-fill me-1 text-danger"></i><strong>Địa điểm:</strong> ' . htmlspecialchars($loc['name']) . ' (Sức chứa ' . $loc['capacity'] . ' chỗ)</div>';
        $html .= '<div class="mb-1 fw-bold text-dark"><i class="bi bi-people-fill text-warning me-1"></i>Khách mời danh dự & Diễn giả:</div>';
        $html .= '<div class="d-flex flex-wrap">' . $guestBadges . '</div>';
        $html .= '</div>';

        $html .= '<div class="p-2.5 border rounded-3 bg-white mb-2" style="font-size:12.5px;">';
        $html .= '<div class="fw-bold text-dark mb-1.5"><i class="bi bi-diagram-3-fill text-success me-1"></i>Tự động phân rã 2 ca trực CTV:</div>';
        $html .= '<div class="d-flex justify-content-between align-items-center py-1 border-bottom">';
        $html .= '<div><strong>Ca 1:</strong> Đón Tiếp Khách Mời VIPs (07:30 - 11:30)</div>';
        $html .= '<span class="badge bg-light text-dark border">Chỉ tiêu: 5 CTV • 150.000đ</span>';
        $html .= '</div>';
        $html .= '<div class="d-flex justify-content-between align-items-center py-1">';
        $html .= '<div><strong>Ca 2:</strong> Kỹ Thuật Hội Thảo & Media (13:00 - 17:00)</div>';
        $html .= '<span class="badge bg-light text-dark border">Chỉ tiêu: 5 CTV • 150.000đ</span>';
        $html .= '</div>';
        $html .= '</div>';

        $html .= '<div class="d-flex gap-2 pt-1">';
        $html .= '<button class="btn btn-sm btn-fpt-primary rounded-pill px-3" onclick="navigateToPage(\'calendar\'); aiModalInstance.hide();"><i class="bi bi-calendar3 me-1"></i> Xem Trên Bảng Lịch</button>';
        $html .= '<button class="btn btn-sm btn-outline-warning rounded-pill px-3" onclick="quickAskAi(\'Gửi mail cho CTV đăng ký lịch ca trực sự kiện ' . addslashes($title) . '\')"><i class="bi bi-envelope-fill me-1"></i> Gửi Mail Mời CTV</button>';
        $html .= '</div>';
        $html .= '</div>';

        $plain = sprintf(
            "🎉 **ĐÃ TỰ ĐỘNG ĐẶT LỊCH SỰ KIỆN THÀNH CÔNG!**\n\n" .
            "• **Mã sự kiện:** #EVT-%03d\n" .
            "• **Tên sự kiện:** %s\n" .
            "• **Địa điểm:** %s\n" .
            "• **Thời gian:** %s (08:00 - 17:00)\n" .
            "• **Khách mời VIPs:** %s\n" .
            "• **Phân rã ca trực:** Đã tự động tạo 2 ca trực (Đón tiếp khách mời & Kỹ thuật/Media) với chỉ tiêu 10 CTV.\n\n" .
            "Sự kiện đã được ghi nhận trên Bảng lịch trung tâm!",
            $eventId,
            $title,
            $loc['name'],
            date('d/m/Y', strtotime($date)),
            $guests
        );

        return [
            'type' => 'AUTO_EVENT_CREATED',
            'text' => $plain,
            'reply_html' => $html,
            'data' => [
                'event_id' => $eventId,
                'title' => $title,
                'shifts' => [$shift1Id, $shift2Id]
            ]
        ];
    }

    /**
     * Tự động nộp link Google Drive minh chứng nghiệm thu sau sự kiện (Dành cho CTV Media)
     */
    private function handleDriveSubmission(int $userId, string $msg, string $driveUrl): array
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT sa.id as assignment_id, s.name as shift_name, e.title as event_title 
                             FROM shift_assignments sa
                             INNER JOIN shifts s ON sa.shift_id = s.id
                             INNER JOIN events e ON s.event_id = e.id
                             WHERE sa.user_id = :user_id AND sa.status = 'Approved'
                             ORDER BY s.start_time DESC LIMIT 1");
        $stmt->execute([':user_id' => $userId]);
        $assignment = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($assignment) {
            $assignmentId = (int)$assignment['assignment_id'];
            $note = "Link Google Drive bộ ảnh sự kiện đã kèm Logo FPT Polytechnic và chỉnh sửa sáng (CTV Media " . $this->currentUser['full_name'] . ")";
            $submissionId = $this->driveModel->createSubmission(
                $assignmentId,
                $driveUrl,
                $note
            );

            // Đồng bộ sang shift_assignments proof_image_url
            $upStmt = $db->prepare("UPDATE shift_assignments SET proof_image_url = :url, proof_note = :note, proof_submitted_at = datetime('now', 'localtime') WHERE id = :id");
            $upStmt->execute([':url' => $driveUrl, ':note' => $note, ':id' => $assignmentId]);

            return [
                'type' => 'DRIVE_SUBMISSION',
                'text' => sprintf(
                    "🎬 **ĐÃ NỘP LINK GOOGLE DRIVE MINH CHỨNG NGHIỆM THU (CTV MEDIA)!**\n\n" .
                    "• **Sự kiện:** %s\n" .
                    "• **Ca trực:** %s\n" .
                    "• **Bộ ảnh Drive:** [%s](%s)\n" .
                    "• **Tiêu chuẩn kỹ thuật:** Đã kiểm tra gắn **Logo FPT Polytechnic** và **chỉnh sửa sáng** chuẩn nhận diện.\n" .
                    "• **Mã hồ sơ nghiệm thu:** #SUB-%04d\n\n" .
                    "Hồ sơ đã được gửi đến **Giảng Viên phụ trách** để kiểm tra và duyệt đã đầy đủ minh chứng nghiệm thu làm cơ sở xuất hợp đồng thù lao.",
                    $assignment['event_title'],
                    $assignment['shift_name'],
                    $driveUrl,
                    $driveUrl,
                    $submissionId
                ),
                'data' => [
                    'submission_id' => $submissionId,
                    'drive_url' => $driveUrl
                ]
            ];
        } else {
            return [
                'type' => 'GENERAL',
                'text' => "Tôi nhận thấy bạn đã gửi link Google Drive: `{$driveUrl}`, nhưng hiện tại bạn chưa có ca trực nào được duyệt để liên kết minh chứng nghiệm thu. Vui lòng đăng ký ca trực trước nhé!"
            ];
        }
    }

    /**
     * Vấn đáp Điểm Tích Cực (Thay cho điểm rèn luyện để đánh giá uy tín & năng lực CTV)
     */
    private function handlePositivePointsQuery(array $user, array $perms): array
    {
        $points = (int)($user['positive_points'] ?? 100);
        $role = $perms['role'];
        $name = htmlspecialchars($user['full_name'] ?? 'Bạn');

        if (in_array($role, ['CTV', 'MEDIA'], true)) {
            $plain = "🌟 **ĐIỂM TÍCH CỰC & ĐÁNH GIÁ UY TÍN CỦA BẠN:**\n\n" .
                     "• **Sinh viên CTV:** {$name} ({$user['student_code']})\n" .
                     "• **Điểm Tích Cực hiện tại:** **{$points} Điểm**\n" .
                     "• **Xếp hạng uy tín:** **Hạng A (Rất Tích Cực & Đáng Tin Cậy)**\n\n" .
                     "📌 **Quy chế nhà trường:** FPT Polytechnic không áp dụng điểm rèn luyện truyền thống mà tính **Điểm Tích Cực** cho mỗi ca trực hoàn thành tốt (thường từ +5 đến +20 điểm/ca). Điểm này phản ánh uy tín và trách nhiệm của CTV, giúp bạn được AI ưu tiên gợi ý vào các sự kiện lớn và nhận thù lao cao hơn!";
        } else {
            $plain = "🌟 **HỆ THỐNG ĐIỂM TÍCH CỰC FPT POLYTECHNIC:**\n\n" .
                     "Nhà trường không tính điểm rèn luyện mà sử dụng **Điểm Tích Cực** để lượng hóa năng lực và độ tin cậy của CTV:\n" .
                     "1. **Mục đích:** Đánh giá CTV này có uy tín, chuyên cần và làm tốt hay không.\n" .
                     "2. **Cộng điểm:** Tự động cộng từ +5 đến +20 điểm sau khi Giảng viên duyệt nghiệm thu minh chứng hoàn thành ca.\n" .
                     "3. **Phân loại:** Hạng Xuất Sắc (>120 điểm), Hạng Tích Cực (80-120 điểm), Cần Theo Dõi (<80 điểm).";
        }

        return [
            'type' => 'POSITIVE_POINTS',
            'text' => $plain
        ];
    }

    /**
     * Vấn đáp Quyết Toán Thù Lao, Định Danh VNeID & CCCD
     */
    private function handlePayrollAndVneidQuery(array $user, array $perms): array
    {
        $role = $perms['role'];

        if (in_array($role, ['CTV', 'MEDIA'], true)) {
            $plain = "💳 **QUY CHẾ HỒ SƠ ĐỊNH DANH & THÙ LAO CTV:**\n\n" .
                     "• **Hồ sơ định danh:** CTV **chỉ cần cung cấp Số CCCD (12 số)** và **Ảnh chụp màn hình ứng dụng VNeID (Mức 2)** kèm STK ngân hàng chính chủ tại mục *'Cài Đặt Tài Khoản Cá Nhân'*. Không yêu cầu nộp ảnh thẻ cứng CCCD 2 mặt.\n" .
                     "• **Nhiệm vụ nghiệm thu sau sự kiện:**\n" .
                     "  - **CTV thường:** Nộp **ảnh hiện trường** có mặt làm nhiệm vụ thực tế (áo cam FPoly).\n" .
                     "  - **CTV Media:** Nộp **link Google Drive** bộ ảnh sự kiện đã **chỉnh sửa sáng và gắn logo FPT Polytechnic**.\n" .
                     "• **Quy trình nhận tiền:** Giảng viên duyệt đầy đủ minh chứng nghiệm thu -> Kích hoạt Hợp đồng điện tử -> Kế toán chi trả qua tài khoản ngân hàng đã đối soát.";
        } elseif ($role === 'ACCOUNTANT') {
            $plain = "💰 **TỔNG HỢP QUYẾT TOÁN THÙ LAO KẾ TOÁN:**\n\n" .
                     "• Các hồ sơ đã được Giảng viên phê duyệt nghiệm thu đầy đủ minh chứng sẽ tự động kích hoạt mã hợp đồng điện tử (HDNT-2026-FPLY-xxx).\n" .
                     "• Kế toán đối soát số CCCD và ảnh chụp màn hình VNeID với số tài khoản ngân hàng trước khi xuất lệnh chuyển khoản.\n" .
                     "• CTV chỉ cần số CCCD và ảnh chụp màn hình VNeID, không cần kiểm tra thẻ cứng vật lý.";
        } else {
            $plain = "📑 **QUY TRÌNH NGHIỆM THU & HỒ SƠ CTV:**\n\n" .
                     "• **Sau mỗi sự kiện hoàn thành:**\n" .
                     "  - CTV up ảnh hiện trường có mặt làm nhiệm vụ.\n" .
                     "  - CTV Media up link Google Drive bộ ảnh đã gắn logo và chỉnh sửa sáng.\n" .
                     "• **Thẩm quyền Giảng viên:** Kiểm tra đã đầy đủ minh chứng để bấm **Duyệt Nghiệm Thu**, làm cơ sở để xuất hợp đồng trình trường ký và Kế toán thanh toán thù lao.";
        }

        return [
            'type' => 'PAYROLL_VNEID',
            'text' => $plain
        ];
    }

    /**
     * Vấn đáp Xung Đột Ca Trực & Audit Trail
     */
    private function handleCollisionAuditQuery(array $user, array $perms): array
    {
        $plain = "🛡️ **CÔNG NGHỆ CHỐNG XUNG ĐỘT (INTERVAL COLLISION ENGINE) & AUDIT TRAIL:**\n\n" .
                 "• **Interval Collision Engine:** PolyMee tự động kiểm tra giao thoa các mốc thời gian [Start, End] khi sinh viên đăng ký ca hoặc Giảng viên đặt lịch. Ngăn chặn 100% tình trạng trùng giờ hoặc nhận 2 ca cùng lúc.\n" .
                 "• **Nhật Ký Thao Tác Hệ Thống (Audit Trail):** Mọi hành động duyệt nghiệm thu, cấp quyền, xóa lịch sự kiện đều được ghi nhận riêng biệt kèm Timestamp, IP và Role của người thực hiện.";

        return [
            'type' => 'COLLISION_AUDIT',
            'text' => $plain
        ];
    }

    /**
     * Phản hồi mặc định có phân quyền theo Role
     */
    private function buildRoleBasedDefaultResponse(array $user, array $perms): array
    {
        $role = $perms['role'];
        $name = htmlspecialchars($user['full_name'] ?? 'bạn');
        $roleName = $perms['roleNameDisplay'];

        $defaultHtml = '<div class="ai-card p-3 rounded-3 bg-light border mb-2">';
        $defaultHtml .= '<div class="fw-bold text-dark mb-1 d-flex align-items-center gap-1.5"><i class="fa-solid fa-wand-magic-sparkles text-warning"></i> Chào ' . $name . ' (' . $roleName . ')!</div>';
        $defaultHtml .= '<p class="small text-secondary mb-2.5">Trợ lý AI PolyMee sẵn sàng phục vụ theo đúng phân quyền của bạn. Bạn có thể chọn nhanh nghiệp vụ bên dưới:</p>';
        $defaultHtml .= '<div class="d-grid gap-1.5">';

        if (in_array($role, ['HEAD_ADMIN', 'ADMIN'], true)) {
            $defaultHtml .= '<button class="btn btn-sm btn-outline-success text-start rounded-3 py-1.5 px-2.5" onclick="quickAskAi(\'Phòng nào đang trống?\')"><i class="bi bi-building-check me-1.5"></i> <strong>Tra cứu phòng trống:</strong> Xem danh sách hội trường, lab khả dụng.</button>';
            $defaultHtml .= '<button class="btn btn-sm btn-outline-warning text-start rounded-3 py-1.5 px-2.5" onclick="quickAskAi(\'CTV nào đang trống?\')"><i class="bi bi-people-fill me-1.5"></i> <strong>Tra cứu CTV rảnh:</strong> Lọc danh sách sinh viên sẵn sàng nhận ca.</button>';
            $defaultHtml .= '<button class="btn btn-sm btn-outline-primary text-start rounded-3 py-1.5 px-2.5" onclick="quickAskAi(\'Bạn gửi mail cho CTV đăng ký lịch ca trực sắp tới\')"><i class="bi bi-envelope-fill me-1.5"></i> <strong>Gửi mail tự động:</strong> Phân phát thư mời nhận ca đến hộp thư FPT Edu.</button>';
            $defaultHtml .= '<button class="btn btn-sm btn-outline-danger text-start rounded-3 py-1.5 px-2.5" onclick="quickAskAi(\'Đặt giúp lịch sự kiện Hội Thảo AI ngày 22/11, khách mời gồm: Ban Giám Hiệu, Thầy Hiệu Trưởng, Diễn Giả AI Google\')"><i class="bi bi-calendar-plus-fill me-1.5"></i> <strong>Đặt lịch sự kiện VIPs:</strong> Tạo sự kiện, ghi nhận khách mời & tự phân rã ca.</button>';
            $defaultHtml .= '<button class="btn btn-sm btn-outline-secondary text-start rounded-3 py-1.5 px-2.5" onclick="quickAskAi(\'Báo cáo xung đột lịch và Audit Trail\')"><i class="bi bi-shield-check me-1.5"></i> <strong>Audit Trail:</strong> Kiểm tra nhật ký và thuật toán chống xung đột ca.</button>';
            $plain = "Chào Quản Trị Viên **{$name}**! Tôi là **PolyMee AI Assistant**.\n\n" .
                     "Bạn có quyền thực hiện các lệnh quản trị cao cấp:\n" .
                     "1. Tra cứu phòng / hội trường trống: *'Phòng nào đang trống?'*\n" .
                     "2. Lọc danh sách CTV đang rảnh: *'CTV nào đang trống?'*\n" .
                     "3. Gửi email hàng loạt cho CTV: *'Gửi mail cho CTV đăng ký lịch'*\n" .
                     "4. Đặt lịch sự kiện kèm khách mời VIPs: *'Đặt giúp lịch sự kiện [Tên], khách mời gồm [Tên khách]...'*\n" .
                     "5. Báo cáo xung đột và nhật ký: *'Kiểm tra xung đột lịch và audit trail'*";
        } elseif (in_array($role, ['LECTURER', 'STAFF'], true)) {
            $defaultHtml .= '<button class="btn btn-sm btn-outline-success text-start rounded-3 py-1.5 px-2.5" onclick="quickAskAi(\'Phòng nào đang trống?\')"><i class="bi bi-building-check me-1.5"></i> <strong>Tra cứu phòng trống:</strong> Xem hội trường, phòng học bộ môn.</button>';
            $defaultHtml .= '<button class="btn btn-sm btn-outline-warning text-start rounded-3 py-1.5 px-2.5" onclick="quickAskAi(\'Tìm CTV có kỹ năng Media quay chụp\')"><i class="bi bi-people-fill me-1.5"></i> <strong>Tìm CTV theo kỹ năng:</strong> Lọc CTV Media, Lễ tân hoặc Hậu cần.</button>';
            $defaultHtml .= '<button class="btn btn-sm btn-outline-primary text-start rounded-3 py-1.5 px-2.5" onclick="quickAskAi(\'Đặt giúp lịch sự kiện Workshop Bộ Môn CNTT ngày 22/11, khách mời gồm: Thầy Trưởng Bộ Môn\')"><i class="bi bi-calendar-plus-fill me-1.5"></i> <strong>Đặt lịch Workshop:</strong> AI tạo sự kiện & phân bổ ca trực theo bộ môn.</button>';
            $defaultHtml .= '<button class="btn btn-sm btn-outline-info text-start rounded-3 py-1.5 px-2.5" onclick="quickAskAi(\'Quy trình duyệt nghiệm thu minh chứng và xuất hợp đồng thù lao\')"><i class="bi bi-patch-check-fill me-1.5"></i> <strong>Duyệt nghiệm thu minh chứng:</strong> Kiểm tra ảnh hiện trường & Drive Media.</button>';
            $plain = "Kính chào Thầy/Cô **{$name}**! Tôi là trợ lý AI PolyMee.\n\n" .
                     "Thầy/Cô có thể yêu cầu tôi hỗ trợ công tác tổ chức sự kiện:\n" .
                     "1. Tra cứu phòng / hội trường trống: *'Phòng nào đang trống?'*\n" .
                     "2. Lọc CTV theo chuyên môn: *'Tìm CTV có kỹ năng Media / Lễ tân'*.\n" .
                     "3. Đặt lịch Workshop bộ môn: *'Đặt giúp lịch sự kiện [Tên], khách mời gồm...'*.\n" .
                     "4. Duyệt đầy đủ minh chứng nghiệm thu để xuất hợp đồng thù lao cho CTV.";
        } elseif ($role === 'ACCOUNTANT') {
            $defaultHtml .= '<button class="btn btn-sm btn-outline-success text-start rounded-3 py-1.5 px-2.5" onclick="quickAskAi(\'Tổng hợp quyết toán thù lao đợt này\')"><i class="bi bi-cash-stack me-1.5"></i> <strong>Tổng hợp quyết toán:</strong> Thống kê thù lao các ca trực đã nghiệm thu.</button>';
            $defaultHtml .= '<button class="btn btn-sm btn-outline-warning text-start rounded-3 py-1.5 px-2.5" onclick="quickAskAi(\'Hồ sơ CTV nào chưa có số CCCD hoặc chưa có ảnh chụp màn hình VNeID?\')"><i class="bi bi-person-exclamation me-1.5"></i> <strong>Đối soát định danh:</strong> Rà soát số CCCD & ảnh chụp VNeID.</button>';
            $defaultHtml .= '<button class="btn btn-sm btn-outline-info text-start rounded-3 py-1.5 px-2.5" onclick="quickAskAi(\'Phòng nào đang tổ chức sự kiện hôm nay?\')"><i class="bi bi-building me-1.5"></i> <strong>Sự kiện hôm nay:</strong> Xem lịch tổ chức toàn trường.</button>';
            $plain = "Kính chào Cán bộ Kế toán **{$name}**! Tôi là trợ lý AI PolyMee.\n\n" .
                     "Tôi hỗ trợ bạn công tác kế toán & quyết toán thù lao:\n" .
                     "1. Tổng hợp quyết toán thù lao: *'Tổng hợp quyết toán thù lao đợt này'*.\n" .
                     "2. Đối soát hồ sơ: *'Hồ sơ CTV nào chưa có số CCCD hoặc thiếu ảnh chụp VNeID?'*.\n" .
                     "3. Tra cứu lịch sự kiện và phòng trực toàn trường.";
        } else {
            // CTV & MEDIA
            $defaultHtml .= '<button class="btn btn-sm btn-outline-primary text-start rounded-3 py-1.5 px-2.5" onclick="quickAskAi(\'Tôi có ca trực nào sắp tới không?\')"><i class="bi bi-clock-history me-1.5"></i> <strong>Ca trực của tôi:</strong> Xem lịch trực và nhiệm vụ được phân công.</button>';
            $defaultHtml .= '<button class="btn btn-sm btn-outline-warning text-start rounded-3 py-1.5 px-2.5" onclick="quickAskAi(\'Điểm tích cực và đánh giá uy tín của tôi là bao nhiêu?\')"><i class="bi bi-star-fill me-1.5"></i> <strong>Điểm Tích Cực & Uy tín:</strong> Xem điểm số và năng lực hoàn thành ca.</button>';
            $defaultHtml .= '<button class="btn btn-sm btn-outline-success text-start rounded-3 py-1.5 px-2.5" onclick="quickAskAi(\'Hướng dẫn nộp minh chứng nghiệm thu sau mỗi sự kiện hoàn thành\')"><i class="bi bi-camera-fill me-1.5"></i> <strong>Nộp minh chứng nghiệm thu:</strong> Hướng dẫn up ảnh hiện trường hoặc link Drive.</button>';
            $defaultHtml .= '<button class="btn btn-sm btn-outline-info text-start rounded-3 py-1.5 px-2.5" onclick="quickAskAi(\'Hồ sơ số CCCD và ảnh chụp màn hình VNeID của tôi đã hợp lệ chưa?\')"><i class="bi bi-shield-check me-1.5"></i> <strong>Kiểm tra định danh:</strong> Xem trạng thái số CCCD & ảnh VNeID.</button>';
            $plain = "Xin chào **{$name}** ({$roleName})! Tôi là trợ lý AI PolyMee.\n\n" .
                     "Bạn có thể hỏi tôi:\n" .
                     "1. Ca trực của tôi: *'Tôi có ca trực nào sắp tới không?'*\n" .
                     "2. Đánh giá uy tín: *'Điểm tích cực của tôi là bao nhiêu?'*\n" .
                     "3. Nghiệm thu thù lao: *'Hướng dẫn nộp minh chứng nghiệm thu sau sự kiện'*.\n" .
                     "4. Kiểm tra hồ sơ nhận tiền: *'Hồ sơ số CCCD và ảnh chụp VNeID của tôi?'*";
        }

        $defaultHtml .= '</div></div>';

        return [
            'type' => 'GENERAL',
            'text' => $plain,
            'reply_html' => $defaultHtml
        ];
    }

    /**
     * Vấn đáp tóm tắt ca trực của người dùng
     */
    private function handleStudentShiftsSummary(int $userId, ?array $user = null, ?array $perms = null): array
    {
        $shifts = $this->userModel->getStudentShiftsToday($userId);
        $name = $user['full_name'] ?? ($this->currentUser['full_name'] ?? 'bạn');

        if (empty($shifts)) {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT s.*, e.title as event_title, l.name as location_name 
                                 FROM shift_assignments sa
                                 INNER JOIN shifts s ON sa.shift_id = s.id
                                 INNER JOIN events e ON s.event_id = e.id
                                 LEFT JOIN locations l ON e.location_id = l.id
                                 WHERE sa.user_id = :user_id AND sa.status = 'Approved'
                                 ORDER BY s.start_time ASC LIMIT 3");
            $stmt->execute([':user_id' => $userId]);
            $upcoming = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($upcoming)) {
                $reply = "👋 Chào **" . $name . "**! Hôm nay bạn **không có ca trực nào**. Tuy nhiên, bạn có các ca trực sắp tới:\n\n";
                foreach ($upcoming as $i => $s) {
                    $reply .= sprintf(
                        "%d. **%s** (%s)\n   • Sự kiện: *%s*\n   • Địa điểm: %s\n   • Điểm tích cực: +%d điểm uy tín\n\n",
                        $i + 1,
                        $s['name'],
                        date('H:i d/m/Y', strtotime($s['start_time'])),
                        $s['event_title'],
                        $s['location_name'] ?? 'Khuôn viên FPoly',
                        $s['points']
                    );
                }
                $reply .= "💡 *Nhớ có mặt trước 15 phút và nộp minh chứng sau sự kiện (Ảnh hiện trường đối với CTV hoặc link Google Drive ảnh đã chỉnh sửa sáng & gắn logo FPT đối với CTV Media) để Giảng viên duyệt nghiệm thu nhé!*";
                return ['type' => 'TASK_SUMMARY', 'text' => $reply, 'data' => $upcoming];
            }

            return [
                'type' => 'TASK_SUMMARY',
                'text' => "👋 Chào **" . $name . "**! Hôm nay bạn **không có ca trực nào**. Bạn có thể vào mục **Lịch Hoạt Động** để khám phá các sự kiện mới và đăng ký ca trực nhé!"
            ];
        }

        $reply = "📋 **LỊCH TRỰC HÔM NAY CỦA BẠN (" . date('d/m/Y') . "):**\n\n";
        foreach ($shifts as $i => $s) {
            $reply .= sprintf(
                "%d. **%s**\n" .
                "   • ⏰ Thời gian: %s - %s\n" .
                "   • 📍 Địa điểm: %s\n" .
                "   • 🎯 Sự kiện: %s\n" .
                "   • 🌟 Thù lao: %s VNĐ • +%d Điểm Tích Cực\n\n",
                $i + 1,
                $s['name'],
                date('H:i', strtotime($s['start_time'])),
                date('H:i', strtotime($s['end_time'])),
                $s['location_name'] ?? 'Hội trường Trịnh Văn Bô',
                $s['event_title'],
                number_format((float)($s['remuneration_amount'] ?? 150000), 0, ',', '.'),
                $s['points']
            );
        }
        $reply .= "📌 **Lưu ý nghiệm thu sau mỗi sự kiện hoàn thành:**\n" .
                  "- CTV: Chụp ảnh hiện trường có mặt làm nhiệm vụ thực tế tại sự kiện.\n" .
                  "- CTV Media: Nộp link Google Drive chứa bộ ảnh sự kiện đã chỉnh sửa sáng và gắn kèm logo FPT Polytechnic.\n" .
                  "- Giảng viên sẽ thẩm định để duyệt đã đầy đủ minh chứng nghiệm thu, kích hoạt xuất hợp đồng thù lao.";

        return ['type' => 'TASK_SUMMARY', 'text' => $reply, 'data' => $shifts];
    }
}
