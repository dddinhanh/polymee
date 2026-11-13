<?php
/**
 * PolyMee - DynamicQrHelper
 * Sinh và thẩm định mã QR động với chu kỳ sống TTL = 30 giây
 * Chữ ký số HMAC-SHA256 chống gian lận điểm danh
 */

declare(strict_types=1);

class DynamicQrHelper
{
    private static string $secretKey = QR_SECRET_KEY;
    private const TTL_SECONDS = QR_TTL_SECONDS;

    /**
     * Sinh chuỗi Token mã hóa cho mã Dynamic QR
     * Cấu trúc: eventId|shiftId|timestamp|hmacSignature
     */
    public static function generateToken(int $eventId, int $shiftId): string
    {
        $timestamp = time(); // Giờ Unix UTC
        $payload = "{$eventId}|{$shiftId}|{$timestamp}";
        $signature = hash_hmac('sha256', $payload, self::$secretKey);
        return "{$payload}|{$signature}";
    }

    /**
     * Thẩm định mã Dynamic QR do sinh viên quét và gửi lên
     */
    public static function verifyToken(string $qrContent, int $expectedEventId, int $expectedShiftId): array
    {
        $parts = explode('|', trim($qrContent));
        if (count($parts) !== 4) {
            return [
                'valid' => false,
                'message' => 'Cấu trúc mã QR không hợp lệ (không phải mã do PolyMee phát hành).'
            ];
        }

        [$eventId, $shiftId, $timestamp, $clientSignature] = $parts;
        $eventId = (int)$eventId;
        $shiftId = (int)$shiftId;
        $timestamp = (int)$timestamp;

        // 1. Kiểm tra thời gian sống TTL 30 giây
        $currentTime = time();
        $elapsed = abs($currentTime - $timestamp);
        if ($elapsed > self::TTL_SECONDS) {
            return [
                'valid' => false,
                'message' => "Mã QR đã hết hạn ({$elapsed}s trôi qua, giới hạn 30s). Vui lòng quét mã mới đang chiếu tại hội trường."
            ];
        }

        // 2. Thẩm định chữ ký số HMAC-SHA256
        $payload = "{$eventId}|{$shiftId}|{$timestamp}";
        $expectedSignature = hash_hmac('sha256', $payload, self::$secretKey);

        if (!hash_equals($expectedSignature, $clientSignature)) {
            return [
                'valid' => false,
                'message' => 'Chữ ký số không khớp! Phát hiện hành vi can thiệp hoặc giả mạo mã QR.'
            ];
        }

        // 3. Khớp định danh sự kiện và ca trực
        if ($eventId !== $expectedEventId || $shiftId !== $expectedShiftId) {
            return [
                'valid' => false,
                'message' => 'Mã QR không khớp với ca trực mà bạn đang điểm danh.'
            ];
        }

        return [
            'valid' => true,
            'message' => 'Xác thực điểm danh Dynamic QR thành công!',
            'timestamp' => $timestamp
        ];
    }
}
