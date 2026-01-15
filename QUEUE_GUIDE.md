# Bài Tập 8 - Laravel Queue + Mail Nâng Cao

## Mục Tiêu
- ✅ Sử dụng queue driver database / redis
- ✅ Gửi email hàng loạt không bị timeout
- ✅ Hiểu job retry, timeout
- ✅ Log lại trạng thái thành công/thất bại vào DB

## Cấu Trúc Dự Án

### 1. Các File Được Tạo

#### Job
- **`app/Jobs/SendWelcomeEmailJob.php`** - Job xử lý gửi email chào mừng
  - Implements `ShouldQueue` để chạy bất đồng bộ
  - Hỗ trợ retry tự động (max 3 lần)
  - Exponential backoff: 10s, 20s, 40s
  - Timeout: 120 giây (2 phút)
  - Log trạng thái thành công/thất bại vào DB

#### Mail
- **`app/Mail/WelcomeEmail.php`** - Mailable class cho email chào mừng
  - HTML email template
  - Sử dụng queue để gửi bất đồng bộ

#### Controller
- **`app/Http/Controllers/RegistrationController.php`** - Controller xử lý đăng ký
  - `showForm()` - Hiển thị form đăng ký
  - `register()` - Xử lý đăng ký user và push job vào queue
  - `showLogs()` - Hiển thị danh sách nhật ký job

#### Model
- **`app/Models/JobLog.php`** - Model để lưu trữ nhật ký job
  - Ghi lại job_name, email, status, error, retry count, timestamps

#### Migration
- **`database/migrations/2026_01_14_000003_create_job_logs_table.php`**
  - Tạo bảng `job_logs` để lưu trữ lịch sử gửi email

#### Views
- **`resources/views/auth/register.blade.php`** - Form đăng ký
- **`resources/views/emails/welcome.blade.php`** - Email template HTML
- **`resources/views/job-logs.blade.php`** - Trang hiển thị nhật ký job

#### Routes
- **`routes/web.php`** - Đã cập nhật routes

## Hướng Dẫn Sử Dụng

### 1. Chạy Migration
```bash
php artisan migrate
```

Lệnh này sẽ tạo bảng `job_logs` để lưu trữ lịch sử gửi email.

### 2. Queue Driver Configuration

#### Option A: Database Driver (Mặc định)
```
QUEUE_CONNECTION=database
```
- Sử dụng bảng `jobs` để lưu trữ job
- Không cần cài đặt Redis
- Phù hợp cho ứng dụng nhỏ đến trung bình

#### Option B: Redis Driver
```
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```
- Nhanh hơn database driver
- Cần Redis server chạy
- Phù hợp cho ứng dụng lớn, high traffic

### 3. Mail Configuration

Cập nhật các thông số mail trong `.env`:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hello@example.com
MAIL_FROM_NAME="Laravel"
```

Hoặc sử dụng log driver để test (không gửi email thực):
```
MAIL_MAILER=log
```

### 4. Chạy Queue Worker

**Start queue worker:**
```bash
php artisan queue:work
```

**Chạy queue worker và restart khi file thay đổi:**
```bash
php artisan queue:work --watch
```

**Chạy queue worker với timeout 60 giây:**
```bash
php artisan queue:work --timeout=60
```

**Chạy queue worker với sleep 5 giây:**
```bash
php artisan queue:work --sleep=5
```

**Chạy queue worker daemon (chạy trong background):**
```bash
php artisan queue:work --daemon
```

### 5. Sử Dụng Ứng Dụng

1. Truy cập `/register` để đăng ký tài khoản
2. Điền form đăng ký (name, email, password)
3. Sau khi submit, job sẽ được push vào queue
4. Khi `queue:work` đang chạy, job sẽ được xử lý
5. Xem kết quả tại `/job-logs`

## Các Tính Năng

### 1. Job Retry
- Job tự động retry tối đa 3 lần nếu thất bại
- Exponential backoff: delay tăng theo mũ (10s, 20s, 40s)

### 2. Job Timeout
- Timeout 120 giây (2 phút)
- Job sẽ fail nếu vượt quá timeout

### 3. Logging
Bảng `job_logs` ghi lại:
- `job_name` - Tên job
- `email` - Email được gửi
- `status` - Trạng thái (pending, processing, success, failed)
- `payload` - Dữ liệu job
- `error_message` - Thông báo lỗi (nếu có)
- `retry_count` - Số lần đã thử
- `max_retries` - Số lần thử tối đa
- `started_at` - Thời gian bắt đầu
- `completed_at` - Thời gian kết thúc
- `created_at` - Thời gian tạo log
- `updated_at` - Thời gian cập nhật

## File Cấu Hình Quan Trọng

### `config/queue.php`
```php
'default' => env('QUEUE_CONNECTION', 'database'),
'connections' => [
    'database' => [
        'driver' => 'database',
        'table' => 'jobs',
        'retry_after' => 90,  // Retry sau 90 giây
    ],
    'redis' => [
        'driver' => 'redis',
        'queue' => 'default',
        'retry_after' => 90,
    ]
]
```

### `config/mail.php`
```php
'default' => env('MAIL_MAILER', 'log'),
'mailers' => [
    'log' => [
        'driver' => 'log',
        'channel' => null,
    ],
    'smtp' => [
        'transport' => 'smtp',
        'host' => env('MAIL_HOST'),
        'port' => env('MAIL_PORT'),
        // ...
    ]
]
```

## Lệnh Hữu Ích

### Quản lý Queue
```bash
# Xem danh sách job trong queue
php artisan queue:failed

# Retry job thất bại
php artisan queue:retry {id}

# Xóa job thất bại
php artisan queue:forget {id}

# Xóa tất cả job thất bại
php artisan queue:flush

# Flush job table
php artisan queue:clear
```

### Testing
```bash
# Test send email
php artisan tinker
> Mail::to('test@example.com')->send(new App\Mail\WelcomeEmail('Test User'));

# Dispatch job directly
> SendWelcomeEmailJob::dispatch('test@example.com', 'Test User');
```

## Cấu Trúc Email

Email gửi bao gồm:
- Tiêu đề: "Chào mừng đến với ứng dụng của chúng tôi!"
- Nội dung HTML beautifully styled
- Thông tin về queue system
- Liên hệ support

## Xử Lý Lỗi

Job sẽ retry tự động nếu:
- Network error
- Database error
- Mail server error

Nếu retry 3 lần vẫn thất bại, job sẽ chuyển sang status `failed` với error message được ghi lại.

## Monitoring

Theo dõi trạng thái job:
1. Truy cập `/job-logs` để xem dashboard
2. Xem số lượng job thành công, thất bại
3. Xem chi tiết error message

## Performance Tips

1. **Batch Processing**: Gửi email hàng loạt bằng `Bus::batch()`
2. **Connection Pooling**: Sử dụng connection pooling cho Redis
3. **Queue Driver**: Chọn Redis cho high-traffic
4. **Worker Configuration**: Chạy multiple workers
5. **Timeout**: Điều chỉnh timeout phù hợp

## Troubleshooting

### Job không được xử lý
- Kiểm tra `queue:work` đang chạy
- Kiểm tra `QUEUE_CONNECTION` trong .env
- Kiểm tra bảng `jobs` có job hay không

### Email không gửi được
- Kiểm tra `MAIL_MAILER` configuration
- Kiểm tra SMTP credentials
- Kiểm tra error_message trong job_logs

### Queue database không tạo job
- Chạy `php artisan migrate`
- Kiểm tra DB connection

## Tài Liệu Tham Khảo
- Laravel Queue: https://laravel.com/docs/queue
- Laravel Mail: https://laravel.com/docs/mail
- Laravel Jobs: https://laravel.com/docs/queues#creating-jobs
