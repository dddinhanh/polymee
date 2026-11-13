<?php
/**
 * PolyMee - AuthController
 * Xử lý xác thực người dùng: Đăng nhập, Đăng ký CTV, Đăng xuất và Chuyển đổi tài khoản nhanh
 * Tuân thủ tiêu chuẩn bảo mật CSRF, Password Hashing BCRYPT, Session Fixation Prevention
 */

declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';

class AuthController extends BaseController
{
    private UserModel $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel();
    }

    /**
     * Màn hình đăng nhập (GET) & Xử lý đăng nhập (POST)
     */
    public function login(): void
    {
        // Nếu đã đăng nhập thì chuyển thẳng vào Dashboard
        if (SessionHelper::isLoggedIn() && ($this->currentUser['id'] ?? 0) > 0) {
            $this->redirect('/dashboard');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!SessionHelper::verifyCsrfToken($csrfToken)) {
                SessionHelper::setFlash('error', 'Phiên làm việc hết hạn hoặc yêu cầu không hợp lệ (CSRF Token).');
                $this->redirect('/auth/login');
                return;
            }

            $account = trim($_POST['account'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($account) || empty($password)) {
                SessionHelper::setFlash('error', 'Vui lòng điền đầy đủ Email/Mã sinh viên và Mật khẩu.');
                $this->renderAuthView('auth/login', [
                    'pageTitle' => 'Đăng Nhập PolyMee - FPT Polytechnic',
                    'oldAccount' => $account
                ]);
                return;
            }

            $user = $this->userModel->authenticate($account, $password);

            if ($user) {
                SessionHelper::setCurrentUser($user);
                SessionHelper::setFlash('success', 'Đăng nhập thành công! Chào mừng ' . $user['full_name']);
                $this->redirect('/dashboard');
                return;
            } else {
                SessionHelper::setFlash('error', 'Thông tin đăng nhập không chính xác. Mật khẩu mặc định demo là: 123456');
                $this->renderAuthView('auth/login', [
                    'pageTitle' => 'Đăng Nhập PolyMee - FPT Polytechnic',
                    'oldAccount' => $account
                ]);
                return;
            }
        }

        // Render màn hình đăng nhập
        $this->renderAuthView('auth/login', [
            'pageTitle' => 'Đăng Nhập PolyMee - FPT Polytechnic',
            'oldAccount' => ''
        ]);
    }

    /**
     * Màn hình đăng ký CTV (GET) & Xử lý đăng ký CTV mới (POST)
     */
    public function register(): void
    {
        if (SessionHelper::isLoggedIn() && ($this->currentUser['id'] ?? 0) > 0) {
            $this->redirect('/dashboard');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!SessionHelper::verifyCsrfToken($csrfToken)) {
                SessionHelper::setFlash('error', 'Mã xác thực CSRF không hợp lệ.');
                $this->redirect('/auth/register');
                return;
            }

            $fullName = trim($_POST['full_name'] ?? '');
            $studentCode = strtoupper(trim($_POST['student_code'] ?? ''));
            $email = strtolower(trim($_POST['email'] ?? ''));
            $phone = trim($_POST['phone'] ?? '');
            $idCardNumber = trim($_POST['id_card_number'] ?? '');
            $bankName = trim($_POST['bank_name'] ?? '');
            $bankAccountNumber = trim($_POST['bank_account_number'] ?? '');
            $bankAccountName = strtoupper(trim($_POST['bank_account_name'] ?? ''));
            $skills = trim($_POST['skills'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Validation kiểm tra hợp lệ
            $errors = [];
            if (empty($fullName) || mb_strlen($fullName) < 3) {
                $errors[] = 'Họ và tên phải có ít nhất 3 ký tự.';
            }
            if (empty($studentCode)) {
                $errors[] = 'Vui lòng nhập Mã số sinh viên (MSSV).';
            }
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Địa chỉ Email không đúng định dạng.';
            }
            if (strlen($password) < 6) {
                $errors[] = 'Mật khẩu phải có độ dài từ 6 ký tự trở lên.';
            }
            if ($password !== $confirmPassword) {
                $errors[] = 'Xác nhận mật khẩu không trùng khớp.';
            }

            // Kiểm tra trùng lặp email hoặc MSSV
            if (empty($errors)) {
                if ($this->userModel->findByEmail($email)) {
                    $errors[] = 'Email này đã được sử dụng bởi một tài khoản khác.';
                }
                if ($this->userModel->findByStudentCode($studentCode)) {
                    $errors[] = 'Mã sinh viên này đã đăng ký trên hệ thống.';
                }
            }

            if (!empty($errors)) {
                SessionHelper::setFlash('error', implode('<br>', $errors));
                $this->renderAuthView('auth/register', [
                    'pageTitle' => 'Đăng Ký Cộng Tác Viên - PolyMee',
                    'old' => $_POST
                ]);
                return;
            }

            // Mã hóa mật khẩu an toàn
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            $newUserId = $this->userModel->register([
                'student_code' => $studentCode,
                'full_name' => $fullName,
                'email' => $email,
                'password_hash' => $passwordHash,
                'phone' => $phone,
                'role_id' => 4, // Role Sinh viên CTV
                'skills' => !empty($skills) ? $skills : 'CTV Hỗ trợ sự kiện',
                'id_card_number' => !empty($idCardNumber) ? $idCardNumber : null,
                'vneid_image_url' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=600&auto=format&fit=crop&q=80',
                'bank_name' => $bankName,
                'bank_account_number' => $bankAccountNumber,
                'bank_account_name' => !empty($bankAccountName) ? $bankAccountName : $fullName
            ]);

            if ($newUserId) {
                // Tự động đăng nhập cho user mới
                SessionHelper::switchUser($newUserId);
                SessionHelper::setFlash('success', 'Đăng ký tài khoản CTV thành công! Chào mừng ' . $fullName . ' gia nhập PolyMee.');
                $this->redirect('/dashboard');
                return;
            } else {
                SessionHelper::setFlash('error', 'Có lỗi xảy ra khi tạo tài khoản. Vui lòng thử lại.');
                $this->renderAuthView('auth/register', [
                    'pageTitle' => 'Đăng Ký Cộng Tác Viên - PolyMee',
                    'old' => $_POST
                ]);
                return;
            }
        }

        $this->renderAuthView('auth/register', [
            'pageTitle' => 'Đăng Ký Cộng Tác Viên - PolyMee',
            'old' => []
        ]);
    }

    /**
     * Đăng xuất an toàn khỏi hệ thống (GET)
     */
    public function logout(): void
    {
        SessionHelper::logout();
        SessionHelper::setFlash('info', 'Bạn đã đăng xuất an toàn khỏi hệ thống PolyMee.');
        $this->redirect('/auth/login');
    }

    /**
     * Chức năng chuyển đổi nhanh tài khoản mẫu (Dành cho Giảng viên / Hội đồng chấm thi)
     */
    public function quickLogin(int $userId): void
    {
        SessionHelper::switchUser($userId);
        SessionHelper::setFlash('success', 'Đã chuyển đổi sang tài khoản ID #' . $userId . ' thành công.');
        $this->redirect('/dashboard');
    }

    /**
     * Render view Auth chuyên biệt (Bootstrap 5.3 + FPT Theme + Clean Layout)
     */
    private function renderAuthView(string $view, array $data = []): void
    {
        $data['csrfToken'] = SessionHelper::getCsrfToken();
        $data['flash'] = SessionHelper::getFlash();
        $data['currentUser'] = SessionHelper::getCurrentUser();
        $data['siteName'] = SITE_NAME;

        extract($data);
        $viewFile = APP_ROOT . '/app/views/' . $view . '.php';

        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            throw new RuntimeException("Không tìm thấy view Auth: {$viewFile}");
        }
    }
}

