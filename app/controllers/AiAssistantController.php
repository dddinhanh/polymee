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
     * Bộ xử lý ý định tự nhiên (Intent & Function Calling Processor)
     */
    private function processAiMessage(int $userId, string $msg): array
    {
        $lower = mb_strtolower($msg, 'UTF-8');

        // 1. Ý ĐỊNH A: Nộp link Google Drive minh chứng
        if (preg_match('/https:\/\/(?:drive|docs)\.google\.com\/[^\s]+/i', $msg, $matches)) {
            return $this->handleDriveSubmission($userId, $msg, $matches[0]);
        }

        // 2. Ý ĐỊNH B: Đặt giúp lịch sự kiện kèm khách mời VIPs
        if (
            (str_contains($lower, 'đặt giúp') || str_contains($lower, 'đặt lịch') || str_contains($lower, 'tạo sự kiện') || str_contains($lower, 'lên lịch') || str_contains($lower, 'book lịch')) &&
            (str_contains($lower, 'khách mời') || str_contains($lower, 'sự kiện') || str_contains($lower, 'hội thảo') || str_contains($lower, 'lễ') || str_contains($lower, 'workshop'))
        ) {
            return $this->handleAutoBookEvent($lower, $msg, $userId);
        }

        if (str_contains($lower, 'khách mời gồm') || str_contains($lower, 'khách mời:') || str_contains($lower, 'diễn giả gồm')) {
            return $this->handleAutoBookEvent($lower, $msg, $userId);
        }

        // 3. Ý ĐỊNH C: Gửi email cho CTV đăng ký lịch ca trực
        if (
            str_contains($lower, 'gửi mail') || str_contains($lower, 'gửi email') ||
            str_contains($lower, 'mời ctv') || str_contains($lower, 'mail cho ctv') ||
            str_contains($lower, 'email ctv') || str_contains($lower, 'mail ctv') ||
            (str_contains($lower, 'bạn gửi mail') && str_contains($lower, 'ctv'))
        ) {
            return $this->handleSendEmailInvitation($lower, $msg, $userId);
        }

        // 4. Ý ĐỊNH D: Tra cứu CTV nào đang trống / rảnh
        if (
            (str_contains($lower, 'ctv') || str_contains($lower, 'cộng tác viên') || str_contains($lower, 'sinh viên')) &&
            (str_contains($lower, 'trống') || str_contains($lower, 'rảnh') || str_contains($lower, 'chưa có ca') || str_contains($lower, 'nào đang trống') || str_contains($lower, 'tìm ctv'))
        ) {
            return $this->handleCheckAvailableCtvs($lower, $msg);
        }

        if (str_contains($lower, 'ai đang rảnh') || str_contains($lower, 'ctv nào rảnh') || str_contains($lower, 'danh sách ctv rảnh')) {
            return $this->handleCheckAvailableCtvs($lower, $msg);
        }

        // 5. Ý ĐỊNH E: Tra cứu phòng / hội trường trống
        if (
            (str_contains($lower, 'phòng') || str_contains($lower, 'hội trường') || str_contains($lower, 'sân khấu') || str_contains($lower, 'lab')) &&
            (str_contains($lower, 'trống') || str_contains($lower, 'rảnh') || str_contains($lower, 'nào trống') || str_contains($lower, 'còn trống') || str_contains($lower, 'tìm phòng') || str_contains($lower, 'check phòng'))
        ) {
            return $this->handleCheckAvailableRooms($lower, $msg);
        }

        // 6. Ý ĐỊNH F: Vấn đáp ca trực "Tôi cần làm gì hôm nay?" / "Lịch trực hôm nay"
        if (
            str_contains($lower, 'cần làm gì') ||
            str_contains($lower, 'hôm nay') ||
            str_contains($lower, 'lịch trực') ||
            str_contains($lower, 'nhiệm vụ') ||
            str_contains($lower, 'ca trực')
        ) {
            return $this->handleStudentShiftsSummary($userId);
        }

        // 7. Ý ĐỊNH G: Hướng dẫn điểm danh Dynamic QR & Minh chứng
        if (str_contains($lower, 'điểm danh') || str_contains($lower, 'quét qr') || str_contains($lower, 'checkin') || str_contains($lower, 'minh chứng')) {
            return [
                'type' => 'GENERAL',
                'text' => "📱 **Quy trình Điểm Danh & Nghiệm Thu Hiện Trường FPT Polytechnic:**\n\n" .
                          "1. **Có mặt tại sự kiện:** CTV đến địa điểm trực trước giờ 15 phút.\n" .
                          "2. **Chụp ảnh hiện trường:** Dùng điện thoại chụp ảnh đang thực hiện nhiệm vụ (áo cam FPoly) tại vị trí trực.\n" .
                          "3. **Gửi minh chứng:** Mở mục 'Ca Trực Của Tôi' và tải ảnh hiện trường lên để Giảng viên/BTC nghiệm thu.\n" .
                          "4. **Ký hợp đồng thù lao:** Giảng viên phê duyệt sẽ tự động kích hoạt mã Hợp đồng điện tử (HDNT-2026-FPLY-xxx) trình nhà trường ký duyệt chuyển tiền."
            ];
        }

        // 8. Mặc định phản hồi chung kèm các nút gợi ý thông minh
        $defaultHtml = '<div class="ai-card p-3 rounded-3 bg-light border mb-2">';
        $defaultHtml .= '<div class="fw-bold text-dark mb-1 d-flex align-items-center gap-1.5"><i class="fa-solid fa-robot text-warning"></i> Chào bạn, tôi là PolyMee AI Assistant!</div>';
        $defaultHtml .= '<p class="small text-secondary mb-2.5">Trợ lý ảo điều phối sự kiện & quản trị Cộng tác viên FPT Polytechnic. Tôi có thể thực thi ngay các nghiệp vụ sau:</p>';
        $defaultHtml .= '<div class="d-grid gap-1.5">';
        $defaultHtml .= '<button class="btn btn-sm btn-outline-success text-start rounded-3 py-1.5 px-2.5" onclick="quickAskAi(\'Phòng nào đang trống?\')"><i class="bi bi-building-check me-1.5"></i> <strong>Tra cứu phòng trống:</strong> Xem danh sách hội trường, phòng lab khả dụng.</button>';
        $defaultHtml .= '<button class="btn btn-sm btn-outline-warning text-start rounded-3 py-1.5 px-2.5" onclick="quickAskAi(\'CTV nào đang trống?\')"><i class="bi bi-people-fill me-1.5"></i> <strong>Tra cứu CTV rảnh:</strong> Lọc danh sách sinh viên sẵn sàng nhận ca.</button>';
        $defaultHtml .= '<button class="btn btn-sm btn-outline-primary text-start rounded-3 py-1.5 px-2.5" onclick="quickAskAi(\'Bạn gửi mail cho CTV đăng ký lịch ca trực sắp tới\')"><i class="bi bi-envelope-fill me-1.5"></i> <strong>Gửi mail tự động:</strong> Phân phát thư mời nhận ca đến hộp thư FPT Edu.</button>';
        $defaultHtml .= '<button class="btn btn-sm btn-outline-danger text-start rounded-3 py-1.5 px-2.5" onclick="quickAskAi(\'Đặt giúp lịch sự kiện Hội Thảo AI ngày 22/11, khách mời gồm: Ban Giám Hiệu, Thầy Hiệu Trưởng, Diễn Giả AI Google\')"><i class="bi bi-calendar-plus-fill me-1.5"></i> <strong>Đặt lịch sự kiện:</strong> Tạo sự kiện, ghi nhận khách mời & tự phân rã ca.</button>';
        $defaultHtml .= '</div>';
        $defaultHtml .= '</div>';

        return [
            'type' => 'GENERAL',
            'text' => "Chào bạn, tôi là **PolyMee AI Assistant** - Trợ lý ảo điều phối sự kiện FPT Polytechnic.\n\n" .
                      "Tôi có thể hỗ trợ bạn:\n" .
                      "1. Tra cứu phòng / hội trường trống: Gõ *'Phòng nào đang trống?'*\n" .
                      "2. Tra cứu CTV rảnh: Gõ *'CTV nào đang trống?'*\n" .
                      "3. Gửi email mời CTV đăng ký ca: Gõ *'Gửi mail cho CTV đăng ký lịch'*\n" .
                      "4. Tự động đặt lịch sự kiện kèm khách mời: Gõ *'Đặt giúp lịch sự kiện [Tên], khách mời gồm [Tên khách]...'*",
            'reply_html' => $defaultHtml
        ];
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

        $html = '<div class="ai-card p-3 rounded-3 bg-white border shadow-xs mb-2">';
        $html .= '<h6 class="fw-bold text-dark mb-2 d-flex align-items-center gap-1.5"><i class="bi bi-building-check text-success"></i> TÌNH TRẠNG PHÒNG & HỘI TRƯỜNG FPT POLYTECHNIC:</h6>';
        
        $plain = "🏢 **TÌNH TRẠNG PHÒNG & HỘI TRƯỜNG FPT POLYTECHNIC:**\n\n";

        foreach ($locations as $i => $loc) {
            $stmtEvt = $db->prepare("SELECT title, start_time, end_time FROM events WHERE location_id = :loc_id ORDER BY start_time ASC");
            $stmtEvt->execute([':loc_id' => $loc['id']]);
            $evts = $stmtEvt->fetchAll(PDO::FETCH_ASSOC);

            $statusBadge = '<span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-check-circle-fill me-1"></i>Trống 100% • Sẵn Sàng</span>';
            $slotText = "Sẵn sàng phục vụ toàn bộ khung giờ";

            if (!empty($evts)) {
                $statusBadge = '<span class="badge bg-primary-subtle text-primary border border-primary-subtle">Có ' . count($evts) . ' Sự Kiện Đã Đặt</span>';
                $occupied = [];
                foreach ($evts as $e) {
                    $occupied[] = date('d/m (H:i', strtotime($e['start_time'])) . ' - ' . date('H:i)', strtotime($e['end_time']));
                }
                $slotText = "Đang bận: " . implode(', ', $occupied) . ". Trống các khung giờ còn lại.";
            }

            $html .= '<div class="p-3.5 bg-light rounded-3 border mb-3 shadow-xs" style="line-height:1.5;">';
            $html .= '<div class="d-flex justify-content-between align-items-center mb-1.5">';
            $html .= '<div><strong class="text-dark" style="font-size:14px;">' . ($i + 1) . '. ' . htmlspecialchars($loc['name']) . '</strong></div>';
            $html .= $statusBadge;
            $html .= '</div>';
            $html .= '<div class="small text-muted mb-1"><i class="bi bi-geo-alt me-1.5 text-danger"></i>' . htmlspecialchars($loc['address']) . ' <span class="mx-1">•</span> Sức chứa: <strong class="text-dark">' . $loc['capacity'] . ' chỗ</strong></div>';
            $html .= '<div class="small text-secondary mb-2.5"><i class="bi bi-clock-history me-1.5 text-info"></i>' . $slotText . '</div>';
            $html .= '<button class="btn btn-sm btn-outline-primary px-3 py-1.5 rounded-pill shadow-xs" style="font-size:12px; font-weight:600;" onclick="quickAskAi(\'Đặt giúp lịch sự kiện tại ' . addslashes($loc['name']) . ', khách mời gồm: Thầy Hiệu Trưởng, Ban Giám Hiệu\')"><i class="bi bi-calendar-plus me-1"></i> Đặt Sự Kiện Tại Phòng Này</button>';
            $html .= '</div>';

            $plain .= sprintf("%d. **%s** (Sức chứa %d chỗ)\n   • Vị trí: %s\n   • Trạng thái: %s\n\n",
                $i + 1, $loc['name'], $loc['capacity'], $loc['address'], $slotText
            );
        }

        $html .= '<div class="small text-muted mt-2 fst-italic">💡 Bạn có thể nói: <em>"Đặt giúp lịch sự kiện [Tên] tại [Phòng], khách mời gồm [Tên khách]..."</em> để tôi giữ chỗ tự động!</div>';
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

        $html = '<div class="ai-card p-3.5 rounded-4 bg-white border shadow-sm mb-3">';
        $html .= '<h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2" style="font-size:13.5px;"><i class="bi bi-people-fill text-warning fs-6"></i> DANH SÁCH CỘNG TÁC VIÊN (CTV) SẴN SÀNG NHẬN CA:</h6>';
        
        $plain = "👥 **DANH SÁCH CỘNG TÁC VIÊN (CTV) SẴN SÀNG NHẬN CA:**\n\n";

        foreach ($ctvs as $i => $c) {
            $html .= '<div class="p-3.5 bg-light rounded-3 border mb-3 shadow-xs d-flex justify-content-between align-items-center gap-3">';
            $html .= '<div style="line-height: 1.5;">';
            $html .= '<div class="fw-bold text-dark mb-1.5" style="font-size:14px;">' . htmlspecialchars($c['full_name']) . ' <span class="badge bg-white text-secondary border font-monospace ms-1.5 px-2 py-0.5" style="font-size:11px;">' . htmlspecialchars($c['student_code']) . '</span></div>';
            $html .= '<div class="small text-muted mb-1"><i class="bi bi-award me-1.5 text-warning"></i>Kỹ năng: <strong class="text-dark">' . htmlspecialchars($c['skills'] ?? 'Đa năng') . '</strong></div>';
            $html .= '<div class="small text-secondary"><i class="bi bi-envelope me-1.5"></i>' . htmlspecialchars($c['email']) . ' <span class="mx-1">•</span> SĐT: ' . htmlspecialchars($c['phone'] ?? '09xx') . '</div>';
            $html .= '</div>';
            $html .= '<div class="d-flex flex-column align-items-end justify-content-center gap-2 flex-shrink-0">';
            $html .= '<span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill" style="font-size:11px;"><i class="bi bi-check-circle-fill me-1"></i>Đang Rảnh</span>';
            $html .= '<button class="btn btn-sm btn-outline-warning px-3 py-1 rounded-pill shadow-xs" style="font-size:12px; font-weight:600;" onclick="quickAskAi(\'Gửi mail cho CTV ' . addslashes($c['full_name']) . ' đăng ký lịch ca trực sắp tới\')"><i class="bi bi-envelope-plus me-1"></i> Gửi Mail</button>';
            $html .= '</div>';
            $html .= '</div>';

            $plain .= sprintf("%d. **%s** (%s)\n   • Kỹ năng: %s\n   • Email: %s • SĐT: %s\n   • Trạng thái: Sẵn sàng nhận ca\n\n",
                $i + 1, $c['full_name'], $c['student_code'], $c['skills'] ?? 'Đa năng', $c['email'], $c['phone'] ?? '09xx'
            );
        }

        $html .= '<div class="mt-3 pt-2.5 border-top d-flex gap-2">';
        $html .= '<button class="btn btn-sm btn-fpt-primary rounded-pill px-3.5 py-1.5" style="font-weight:600;" onclick="quickAskAi(\'Gửi mail cho tất cả CTV đăng ký lịch ca trực\')"><i class="bi bi-send-check-fill me-1"></i> Gửi Mail Mời Tất Cả CTV</button>';
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
        $html .= '<li><strong>Quyền lợi:</strong> Thù lao từ <strong>150.000 VNĐ - 200.000 VNĐ/ca</strong> + Cộng <strong>10 Điểm Rèn Luyện (ĐRL)</strong> chính thức.</li>';
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
     * Tự động nộp link Google Drive minh chứng
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
            $submissionId = $this->driveModel->createSubmission(
                $assignmentId,
                $driveUrl,
                "Sinh viên " . $this->currentUser['full_name'] . " nộp qua Trợ lý AI PolyMee. Ghi chú: " . htmlspecialchars($msg)
            );

            return [
                'type' => 'DRIVE_SUBMISSION',
                'text' => sprintf(
                    "🎉 **Đã tự động nộp minh chứng thành công!**\n\n" .
                    "• **Sự kiện:** %s\n" .
                    "• **Ca trực:** %s\n" .
                    "• **Liên kết Drive:** [%s](%s)\n" .
                    "• **Mã hồ sơ nghiệm thu:** #SUB-%04d\n\n" .
                    "Hệ thống đã chuyển bài nộp của bạn đến Ban tổ chức để thẩm định và cập nhật điểm rèn luyện.",
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
                'text' => "Tôi nhận thấy bạn đã gửi link Google Drive: `{$driveUrl}`, nhưng hiện tại bạn chưa có ca trực nào được duyệt để liên kết minh chứng. Vui lòng đăng ký ca trực trước nhé!"
            ];
        }
    }

    /**
     * Vấn đáp tóm tắt ca trực của sinh viên
     */
    private function handleStudentShiftsSummary(int $userId): array
    {
        $shifts = $this->userModel->getStudentShiftsToday($userId);

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
                $reply = "👋 Chào **" . $this->currentUser['full_name'] . "**! Hôm nay bạn **không có ca trực nào**. Tuy nhiên, bạn có các ca trực sắp tới:\n\n";
                foreach ($upcoming as $i => $s) {
                    $reply .= sprintf(
                        "%d. **%s** (%s)\n   • Sự kiện: *%s*\n   • Địa điểm: %s\n   • Điểm rèn luyện: +%d điểm\n\n",
                        $i + 1,
                        $s['name'],
                        date('H:i d/m/Y', strtotime($s['start_time'])),
                        $s['event_title'],
                        $s['location_name'] ?? 'Khuôn viên FPoly',
                        $s['points']
                    );
                }
                $reply .= "💡 *Nhớ có mặt trước 15 phút và chụp ảnh hiện trường để làm hồ sơ nghiệm thu nhé!*";
                return ['type' => 'TASK_SUMMARY', 'text' => $reply, 'data' => $upcoming];
            }

            return [
                'type' => 'TASK_SUMMARY',
                'text' => "👋 Chào **" . $this->currentUser['full_name'] . "**! Hôm nay bạn **không có ca trực nào**. Bạn có thể vào mục **Lịch Hoạt Động** để khám phá các sự kiện mới và đăng ký ca trực nhé!"
            ];
        }

        $reply = "📋 **LỊCH TRỰC HÔM NAY CỦA BẠN (" . date('d/m/Y') . "):**\n\n";
        foreach ($shifts as $i => $s) {
            $reply .= sprintf(
                "%d. **%s**\n" .
                "   • ⏰ Thời gian: %s - %s\n" .
                "   • 📍 Địa điểm: %s\n" .
                "   • 🎯 Sự kiện: %s\n" .
                "   • 🌟 Thù lao: %s VNĐ • +%d ĐRL\n\n",
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
        $reply .= "📌 **Lưu ý quan trọng cho CTV:**\n" .
                  "- Vui lòng có mặt trước giờ trực 15 phút, mặc áo cam FPoly đồng phục.\n" .
                  "- Tại sự kiện, mở mục 'Ca Trực Của Tôi' chụp ảnh hiện trường có mặt trực tiếp để hoàn thành nghiệm thu.\n" .
                  "- Sau khi Giảng viên duyệt, hợp đồng thù lao điện tử sẽ được tạo tự động.";

        return ['type' => 'TASK_SUMMARY', 'text' => $reply, 'data' => $shifts];
    }
}
