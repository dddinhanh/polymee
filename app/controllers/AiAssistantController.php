<?php
/**
 * PolyMee - AiAssistantController
 * Trợ lý ảo AI Assistant thông minh (Kỹ thuật Function Calling)
 * Tự động xử lý vấn đáp tác vụ "Tôi cần làm gì hôm nay?" và tự động nhận diện nộp link Google Drive
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
        $userId = (int)($this->currentUser['id'] ?? 3);

        if (empty($message)) {
            $this->json(['success' => false, 'message' => 'Nội dung tin nhắn không được để trống'], 400);
            return;
        }

        $response = $this->processAiMessage($userId, $message);
        $this->json([
            'success' => true,
            'user_message' => $message,
            'reply' => $response['text'],
            'action_type' => $response['type'], // 'TASK_SUMMARY', 'DRIVE_SUBMISSION', 'GENERAL'
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
        // Nhận diện nếu câu chat chứa link 'drive.google.com'
        if (preg_match('/https:\/\/(?:drive|docs)\.google\.com\/[^\s]+/i', $msg, $matches)) {
            $driveUrl = $matches[0];
            
            // Tìm ca trực gần nhất của sinh viên để gán minh chứng
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

        // 2. Ý ĐỊNH B: Vấn đáp ca trực "Tôi cần làm gì hôm nay?" / "Lịch trực hôm nay"
        if (
            str_contains($lower, 'cần làm gì') ||
            str_contains($lower, 'hôm nay') ||
            str_contains($lower, 'lịch trực') ||
            str_contains($lower, 'nhiệm vụ') ||
            str_contains($lower, 'ca trực')
        ) {
            // Tra cứu ca trực của sinh viên
            $shifts = $this->userModel->getStudentShiftsToday($userId);

            if (empty($shifts)) {
                // Nếu hôm nay không có ca nào, lấy toàn bộ ca trực sắp tới
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
                    $reply .= "💡 *Nhớ mang thẻ sinh viên và quét Dynamic QR tại sảnh khi đến trực nhé!*";
                    return ['type' => 'TASK_SUMMARY', 'text' => $reply, 'data' => $upcoming];
                }

                return [
                    'type' => 'TASK_SUMMARY',
                    'text' => "👋 Chào **" . $this->currentUser['full_name'] . "**! Hôm nay bạn **không có ca trực nào**. Bạn có thể vào mục **Sự kiện** để khám phá các sự kiện mới và đăng ký ca trực tích lũy điểm rèn luyện nhé!"
                ];
            }

            // Tổng hợp danh sách ca trực hôm nay
            $reply = "📋 **LỊCH TRỰC HÔM NAY CỦA BẠN (" . date('d/m/Y') . "):**\n\n";
            foreach ($shifts as $i => $s) {
                $reply .= sprintf(
                    "%d. **%s**\n" .
                    "   • ⏰ Thời gian: %s - %s\n" .
                    "   • 📍 Địa điểm: %s\n" .
                    "   • 🎯 Sự kiện: %s\n" .
                    "   • 🌟 Điểm rèn luyện: +%d điểm\n\n",
                    $i + 1,
                    $s['name'],
                    date('H:i', strtotime($s['start_time'])),
                    date('H:i', strtotime($s['end_time'])),
                    $s['location_name'] ?? 'Hội trường Trịnh Văn Bô',
                    $s['event_title'],
                    $s['points']
                );
            }
            $reply .= "📌 **Lưu ý quan trọng cho CTV:**\n" .
                      "- Vui lòng có mặt trước giờ trực 15 phút, mặc áo cam FPoly đồng phục.\n" .
                      "- Quét mã **Dynamic QR** trên máy chiếu tại bàn ban tổ chức để hoàn thành Check-in.\n" .
                      "- Nếu phụ trách Media, sau sự kiện hãy dán link Google Drive vào đây để tôi nộp nghiệm thu giúp bạn nhé!";

            return ['type' => 'TASK_SUMMARY', 'text' => $reply, 'data' => $shifts];
        }

        // 3. Ý ĐỊNH C: Hướng dẫn điểm danh Dynamic QR
        if (str_contains($lower, 'điểm danh') || str_contains($lower, 'quét qr') || str_contains($lower, 'checkin')) {
            return [
                'type' => 'GENERAL',
                'text' => "📱 **Hướng dẫn Điểm danh Hiện diện Dynamic QR Code:**\n\n" .
                          "1. Đến đúng địa điểm hội trường nơi diễn ra ca trực.\n" .
                          "2. Nhìn lên màn hình máy chiếu tại bàn Ban tổ chức để thấy mã **Dynamic QR Code** (Mã sẽ tự động đổi mới mỗi 30 giây kèm chữ ký HMAC-SHA256).\n" .
                          "3. Dùng camera điện thoại hoặc chức năng Quét QR trong hệ thống để Check-in.\n" .
                          "4. Khi kết thúc ca trực, quét mã một lần nữa để ghi nhận Check-out và xác nhận giờ công thực tế."
            ];
        }

        // 4. Mặc định phản hồi chung
        return [
            'type' => 'GENERAL',
            'text' => "Chào bạn, tôi là **PolyMee AI Assistant** - Trợ lý ảo điều phối sự kiện FPT Polytechnic. Tôi có thể hỗ trợ bạn:\n\n" .
                      "1. Tra cứu ca trực trong ngày: Gõ *'Tôi cần làm gì hôm nay?'*\n" .
                      "2. Tự động nộp link Google Drive minh chứng: Chỉ cần *dán link Drive vào đây*\n" .
                      "3. Hướng dẫn điểm danh bảo mật: Gõ *'Điểm danh như thế nào?'*\n\n" .
                      "Bạn cần tôi hỗ trợ việc gì ngay lúc này?"
        ];
    }
}
