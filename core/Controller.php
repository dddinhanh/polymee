<?php
/**
 * PolyMee - Core Controller Base Class
 * Cung cấp các phương thức nạp Model, nạp View và trả về JSON chuẩn RESTful
 */

declare(strict_types=1);

abstract class Controller
{
    /**
     * Nạp Model tương ứng từ thư mục app/models
     */
    public function model(string $model): object
    {
        $modelFile = APP_ROOT . '/app/models/' . $model . '.php';
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        }
        throw new RuntimeException("Model {$model} không tồn tại tại đường dẫn: {$modelFile}");
    }

    /**
     * Nạp View tương ứng từ thư mục app/views
     */
    public function view(string $view, array $data = []): void
    {
        $viewFile = APP_ROOT . '/app/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            // Trích xuất các biến từ mảng dữ liệu
            extract($data);
            require_once $viewFile;
            return;
        }
        throw new RuntimeException("View {$view} không tồn tại tại đường dẫn: {$viewFile}");
    }

    /**
     * Trả về phản hồi dạng JSON chuẩn hóa cho các yêu cầu AJAX
     */
    public function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Điều hướng trang (Redirect)
     */
    public function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }
}
