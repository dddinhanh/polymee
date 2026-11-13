<?php
/**
 * PolyMee - Core App (Router / Request Dispatcher)
 * Định tuyến tập trung URL sang Controller và Action tương ứng theo chuẩn MVC
 */

declare(strict_types=1);

class App
{
    protected object|string $controller = 'HomeController';
    protected string $action = 'index';
    protected array $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        // 1. Tìm và nạp Controller
        if (!empty($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            $controllerPath = APP_ROOT . '/app/controllers/' . $controllerName . '.php';

            if (file_exists($controllerPath)) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }

        require_once APP_ROOT . '/app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller();

        // 2. Tìm và gọi Action (Method)
        if (isset($url[1])) {
            $actionName = $url[1];
            if (method_exists($this->controller, $actionName)) {
                $this->action = $actionName;
                unset($url[1]);
            }
        }

        // 3. Lấy danh sách tham số (Parameters)
        $this->params = $url ? array_values($url) : [];

        // 4. Kích hoạt Action kèm tham số
        call_user_func_array([$this->controller, $this->action], $this->params);
    }

    /**
     * Phân tích URL từ Request
     */
    private function parseUrl(): array
    {
        $uri = '';

        // Ưu tiên lấy từ query param 'url' do .htaccess chuyển qua
        if (isset($_GET['url'])) {
            $uri = $_GET['url'];
        } else {
            // Hoặc lấy trực tiếp từ REQUEST_URI (khi chạy php -S)
            $requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
            $uri = trim($requestUri, '/');
        }

        if (!empty($uri)) {
            $filtered = filter_var(rtrim($uri, '/'), FILTER_SANITIZE_URL);
            return explode('/', $filtered);
        }

        return [];
    }
}
