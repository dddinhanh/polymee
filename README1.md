# Polymee — Hướng dẫn Kết nối Database SQL Server (Docker)

Tài liệu này hướng dẫn các thành viên (cả **Windows** và **macOS**) thiết lập môi trường, kết nối cơ sở dữ liệu **SQL Server chạy trên Docker** với mã nguồn web PHP **Polymee**, và xử lý các lỗi thường gặp trong quá trình chạy dự án.

## 1. Yêu cầu môi trường

* **Docker Desktop**: Đã cài đặt và bật sẵn container SQL Server.
* **PHP 8.x**:
  * **Windows**: Cài qua Laragon hoặc PHP CLI, bật sẵn 2 extension `pdo_sqlsrv`, `sqlsrv` và cài **Microsoft ODBC Driver for SQL Server**.
  * **macOS**: Cài qua **Homebrew** kèm `msodbcsql18` và biên dịch `sqlsrv`, `pdo_sqlsrv` qua PECL (xem chi tiết tại **Mục 3**).
* **Công cụ quản trị Database**:
  * **Windows**: **SSMS** (SQL Server Management Studio) hoặc Azure Data Studio.
  * **macOS**: **VS Code** (cài extension **SQL Server (mssql)** của Microsoft) hoặc **DBeaver**.

## 2. Thông số kết nối SQL Server (Docker)

Cả Windows và macOS đều dùng chung một bộ thông số kết nối:

| Thông số | Giá trị | Ghi chú |
| :--- | :--- | :--- |
| **Server / Host** | `127.0.0.1,14331` | Dùng cổng `14331` để tránh trùng cổng `1433` của SQL Server cài sẵn trên máy |
| **Database** | `Polymeet` | Tên database chính của hệ thống |
| **Username** | `sa` | Tài khoản quản trị mặc định của SQL Server Docker |
| **Password** | `ThayBangMatKhauManh123!` | Mật khẩu đã thiết lập khi khởi tạo container Docker |
| **Driver PHP** | `sqlsrv` | Kết nối thông qua `pdo_sqlsrv` |

## 3. Hướng dẫn thiết lập môi trường trên macOS

> **Thành viên dùng Windows** có thể bỏ qua mục này và chuyển thẳng sang **Mục 4**.

### 3.1. Bật Rosetta trên Docker Desktop (Bắt buộc với Mac chip M1/M2/M3/M4)
Image SQL Server của Microsoft hiện chỉ hỗ trợ kiến trúc `amd64`. Nếu dùng Mac chip Apple Silicon:
1. Mở **Docker Desktop** $\rightarrow$ **Settings** $\rightarrow$ **General**.
2. Tích chọn **Use Rosetta for x86_64/amd64 emulation on Apple Silicon** rồi nhấn **Apply & Restart**.
3. Khi khởi tạo container SQL Server bằng lệnh, thêm cờ `--platform linux/amd64`:

```bash
docker run --platform linux/amd64 -e "ACCEPT_EULA=Y" -e "ThayBangMatKhauManh123!" \
  -p 14331:1433 --name polymee-sqlserver \
  -d mcr.microsoft.com/mssql/server:2022-latest
```

### 3.2. Cài đặt PHP và Driver `sqlsrv` / `pdo_sqlsrv` qua Homebrew
Mở **Terminal** trên macOS và chạy lần lượt các lệnh sau:

```bash
# 1. Cài đặt PHP và công cụ hỗ trợ biên dịch (nếu chưa có)
brew install php autoconf automake libtool

# 2. Cài đặt Microsoft ODBC Driver 18 cho SQL Server
brew tap microsoft/mssql-release https://github.com/Microsoft/homebrew-mssql-release
brew update
HOMEBREW_ACCEPT_EULA=Y brew install msodbcsql18 mssql-tools18

# 3. Cài đặt 2 extension sqlsrv và pdo_sqlsrv cho PHP qua PECL
pecl install sqlsrv
pecl install pdo_sqlsrv

# 4. Kiểm tra lại xem PHP đã nhận đủ 2 driver chưa
php -m | grep sqlsrv
```
*Nếu kết quả in ra cả `pdo_sqlsrv` và `sqlsrv` là đã cài đặt thành công.*

### 3.3. Công cụ chạy Script SQL thay thế SSMS
macOS không cài được SSMS. Bạn hãy chọn 1 trong 2 công cụ sau để kết nối tới `127.0.0.1,14331`:
* **VS Code**: Cài extension **SQL Server (mssql)** (của Microsoft) $\rightarrow$ thêm kết nối mới với Server `127.0.0.1,14331`, Authentication `SQL Login`, User `sa`, chọn **Trust Server Certificate = Yes**.
* **DBeaver Community**: Tạo kết nối mới loại **SQL Server**, nhập Host `127.0.0.1`, Port `14331`, User `sa`, và bật `trustServerCertificate = true` ở tab Driver Properties.

## 4. Khởi tạo Database

1. Mở **Docker Desktop**, đảm bảo container SQL Server đang ở trạng thái **Running** (màu xanh).
2. Mở **SSMS** (Windows) hoặc **VS Code / DBeaver** (macOS), kết nối đến Server **`127.0.0.1,14331`** bằng tài khoản `sa` / `<Mật_khẩu_SA_Docker>`.
3. Tạo database mới tên **`Polymeet`** (nếu chưa có).
4. Chọn đúng database **`Polymeet`** $\rightarrow$ mở **New Query** $\rightarrow$ chạy lần lượt:
   * **Script khởi tạo cấu trúc 10 bảng**: `roles`, `users`, `locations`, `events`, `shifts`, `shift_assignments`, `check_ins`, `drive_submissions`, `calendar_notes`, `activity_logs`.
   * **Script nạp dữ liệu mẫu (Seed data)** để kiểm thử lịch và phân quyền.

## 5. Cấu hình kết nối trong Source Code

Mở tệp `config/database.php` và cập nhật các thông số kết nối sang `sqlsrv`:

```php
// Bắt buộc đặt là 'sqlsrv' để kết nối SQL Server Docker
define('DB_CONNECTION', 'sqlsrv');

// Thông số SQL Server Docker
define('DB_HOST', '127.0.0.1,14331');
define('DB_NAME', 'Polymeet');
define('DB_USER', 'sa');
define('DB_PASS', 'ThayBangMatKhauManh123!');

// Đường dẫn SQLite dự phòng (giữ nguyên, không cần xóa)
define('DB_SQLITE_PATH', APP_ROOT . '/database/polymee.sqlite');
```

## 6. Chạy Web & Kiểm tra kết nối

1. Mở Terminal tại thư mục gốc của dự án (`polymee/`), khởi chạy máy chủ PHP trỏ thẳng vào thư mục `public/`:

```bash
php -S localhost:8080 -t public
```

2. Mở trình duyệt và truy cập đường dẫn kiểm tra (lưu ý **không** thêm `/public`):

```text
http://localhost:8080/test_db.php
```

3. Kiểm tra dòng **`Driver đang dùng:`** hiển thị trên màn hình:
   * Hiện **`sqlsrv`** $\rightarrow$ Kết nối Docker thành công. Truy cập `http://localhost:8080` để sử dụng.
   * Hiện **`sqlite`** $\rightarrow$ Web chưa kết nối được Docker, kiểm tra mục xử lý lỗi bên dưới.

## 7. Tài khoản mẫu dùng để kiểm thử phân quyền

Tất cả tài khoản mẫu sau khi nạp Seed data dùng chung mật khẩu: **`password`**

| Vai trò | Email đăng nhập | MSSV / Mã | Quyền kiểm thử chính |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@fpt.edu.vn` | `ADMIN001` | Toàn quyền hệ thống, quản lý user, Audit Trail |
| **Giảng viên (Organizer)** | `khaiph@fpt.edu.vn` | `GV0102` | Tạo lịch ca trực, thêm ghi chú, duyệt minh chứng |
| **Hành chính (Staff)** | `sonph@fpt.edu.vn` | `HC0001` | Ký duyệt hợp đồng, duyệt chi thù lao |
| **CTV Media** | `phuonganh@fpt.edu.vn` | `PI03084` | Đăng ký ca trực, nộp minh chứng link Google Drive |
| **CTV Hậu cần** | `manhld@fpt.edu.vn` | `PI03067` | Đăng ký ca trực, nộp ảnh hiện trường |
| **CTV Hậu cần** | `luanhm@fpt.edu.vn` | `PI03015` | Kiểm thử trạng thái hồ sơ đang chờ duyệt minh chứng |

> **Lưu ý:** Nên nhập trực tiếp **Email/MSSV** và mật khẩu vào form đăng nhập thay vì bấm các nút *Đăng nhập nhanh*, vì các nút đó gọi theo `id` cứng có thể bị lệch thứ tự nếu database đã có tài khoản tự đăng ký trước đó.

## 8. Các lỗi thường gặp & Cách xử lý nhanh

* **Lỗi chứng chỉ SSL trên macOS (`SSL Provider: certificate verify failed`):**
  * ODBC Driver 18 mặc định yêu cầu xác thực SSL khi nối vào SQL Server. Mở tệp `core/Database.php` và đảm bảo chuỗi kết nối PDO (DSN) có thêm tham số `TrustServerCertificate=1`:
    ```php
    $dsn = "sqlsrv:Server=" . DB_HOST . ";Database=" . DB_NAME . ";TrustServerCertificate=1";
    ```
* **`test_db.php` tự chuyển về `sqlite` dù đã cấu hình `sqlsrv`:**
  * Kiểm tra xem **Docker Desktop** và container SQL Server đã ở trạng thái **Running** chưa (nếu kết nối SQL Server thất bại, hệ thống sẽ tự lùi về tệp `database/polymee.sqlite`).
  * Kiểm tra xem lệnh `php -S localhost:8080 -t public` có đang chạy đúng thư mục chứa tệp `config/database.php` vừa chỉnh sửa hay không.
* **Báo lỗi `404 Not Found` khi vào `/public/test_db.php`:**
  * Do máy chủ cổng `8080` đã trỏ gốc vào thư mục `public/`, chỉ cần truy cập `http://localhost:8080/test_db.php`.
* **Chạy script SQL bị lỗi `Invalid object name` hoặc thiếu cột:**
  * Kiểm tra xem đã kết nối đúng cổng Docker `127.0.0.1,14331` (tránh nhầm với SQL Server cài sẵn trên cổng `1433`) và đúng database `Polymeet` (không phải `master`) hay chưa.
  * Nếu SSMS (Windows) hiện gạch chân đỏ ảo ngay sau khi tạo bảng, nhấn **`Ctrl + Shift + R`** để làm mới bộ nhớ đệm IntelliSense.