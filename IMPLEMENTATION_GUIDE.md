# Laravel Queue + Mail - Hướng dẫn Triển khai & Sử Dụng

## 🚀 Bước 1: Chuẩn Bị

### 1.1 Chạy Migration
```bash
php artisan migrate
```

**Kết quả:** Tạo các bảng, bao gồm `job_logs` để lưu trữ lịch sử gửi email.

### 1.2 Kiểm Tra Cấu Hình
```bash
# Kiểm tra .env
cat .env | grep -E "QUEUE_CONNECTION|MAIL_MAILER"
```

**Mặc định:**
- `QUEUE_CONNECTION=database` (sử dụng bảng `jobs`)
- `MAIL_MAILER=log` (ghi email vào log file, không gửi thực)

## 📧 Bước 2: Bắt Đầu - Test Đơn Giản

### 2.1 Mở 2 Terminal

**Terminal 1 - Queue Worker:**
```bash
php artisan queue:work
```

Sẽ in ra:
```
Processing jobs from the [default] queue.
```

**Terminal 2 - Test Commands:**
```bash
php artisan tinker
```

### 2.2 Dispatch Single Job
```php
App\Jobs\SendWelcomeEmailJob::dispatch('test@example.com', 'Test User');
```

Khi này:
- **Terminal 1**: Sẽ xử lý job và gửi email
- **job_logs**: Sẽ ghi lại status `success`

## 🔄 Bước 3: Test Hàng Loạt

### 3.1 Sử Dụng Command Tích Hợp
```bash
php artisan email:bulk-welcome 10
```

Sẽ gửi 10 emails via queue.

### 3.2 Monitor Trong Terminal 1 (queue:work)
```
Processing [57]  App\Jobs\SendWelcomeEmailJob
Processed  [57]  App\Jobs\SendWelcomeEmailJob
Processing [58]  App\Jobs\SendWelcomeEmailJob
Processed  [58]  App\Jobs\SendWelcomeEmailJob
...
```

## 🌐 Bước 4: Test Qua Web Interface

### 4.1 Đăng Ký Tài Khoản
1. Truy cập: `http://localhost:8000/register`
2. Điền form:
   - Name: Test User
   - Email: test@example.com
   - Password: password123
3. Click "Đăng Ký"

**Kết quả:**
- Job được push vào queue
- Nếu `queue:work` chạy → email được gửi
- `job_logs` ghi lại status

### 4.2 Xem Dashboard
```
http://localhost:8000/dashboard
```

Hiển thị:
- 📊 Tổng số job
- ✓ Số job thành công
- ✗ Số job thất bại
- ⚙ Đang xử lý
- ○ Đợi xử lý
- Tỷ lệ thành công
- 10 job gần đây
- Job thất bại (có nút Thử lại)

### 4.3 Xem Chi Tiết Nhật Ký
```
http://localhost:8000/job-logs
```

Hiển thị:
- Danh sách tất cả job
- Status, email, lần thử, thời gian bắt đầu/kết thúc
- Chi tiết error (nếu có)
- Pagination

## ⚙️ Bước 5: Cấu Hình Nâng Cao

### 5.1 Thay Đổi Queue Driver thành Redis
**Cài Redis:**
```bash
# Windows: Download từ https://github.com/microsoftarchive/redis/releases
# Hoặc dùng WSL: sudo apt install redis-server
```

**Cập nhật .env:**
```
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

**Clear queue cache:**
```bash
php artisan queue:clear
```

### 5.2 Cấu Hình Mail SMTP (Gửi Email Thực)
**Sử dụng Mailtrap:**
1. Đăng ký: https://mailtrap.io
2. Lấy SMTP credentials
3. Cập nhật .env:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=<your_username>
MAIL_PASSWORD=<your_password>
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="My App"
```

**Test:**
```php
Mail::to('test@example.com')->send(new App\Mail\WelcomeEmail('Test'));
```

## 📊 Bước 6: Monitoring & Debugging

### 6.1 Xem Job Logs
```php
# Tất cả job
\App\Models\JobLog::all();

# Job thành công
\App\Models\JobLog::where('status', 'success')->get();

# Job thất bại
\App\Models\JobLog::where('status', 'failed')->get();

# Job theo email
\App\Models\JobLog::where('email', 'test@example.com')->get();

# Job gần đây nhất
\App\Models\JobLog::latest()->first();
```

### 6.2 Xem Queue Jobs
```bash
# Hiển thị job failed
php artisan queue:failed

# Retry job failed
php artisan queue:retry <id>

# Xóa job failed
php artisan queue:forget <id>

# Clear queue
php artisan queue:clear
```

### 6.3 Xem Mail Log
```bash
tail -f storage/logs/laravel.log
```

## 🧪 Bước 7: Test Cases

### 7.1 Chạy Tests
```bash
php artisan test
# Hoặc chi tiết
php artisan test tests/Feature/QueueMailTest.php -v
```

### 7.2 Manual Test Script
```bash
php artisan tinker < tests/Queue/test_queue.php
```

## 🔧 Xử Lý Lỗi Thường Gặp

| Problem | Solution |
|---------|----------|
| Job không được xử lý | Kiểm tra `queue:work` đang chạy; Kiểm tra `QUEUE_CONNECTION` |
| Email không gửi | Kiểm tra `MAIL_MAILER` config; Xem error_message trong DB |
| Queue hang | Chạy `php artisan queue:clear` |
| Job timeout | Tăng `timeout` trong job (hiện là 120s) |
| Retry không hoạt động | Kiểm tra `maxTries` và `backoff` method |

## 📝 Các File Chính

| File | Mục đích |
|------|---------|
| `app/Jobs/SendWelcomeEmailJob.php` | Job xử lý gửi email |
| `app/Mail/WelcomeEmail.php` | Email Mailable class |
| `app/Models/JobLog.php` | Model lưu trữ nhật ký job |
| `app/Http/Controllers/RegistrationController.php` | Controller đăng ký |
| `app/Http/Controllers/DashboardController.php` | Controller dashboard |
| `app/Console/Commands/SendBulkWelcomeEmails.php` | Command gửi hàng loạt |
| `database/migrations/2026_01_14_000003_create_job_logs_table.php` | Migration job logs |
| `resources/views/auth/register.blade.php` | Form đăng ký |
| `resources/views/emails/welcome.blade.php` | Email template |
| `resources/views/dashboard.blade.php` | Dashboard |
| `resources/views/job-logs.blade.php` | Danh sách job logs |

## 🎯 Các Tính Năng Chính

### ✅ Thành Công
- ✓ Job dispatch đến queue
- ✓ Queue worker xử lý job
- ✓ Email được gửi
- ✓ Log status thành công

### 🔄 Retry Tự Động
- ✓ Retry tối đa 3 lần
- ✓ Exponential backoff (10s, 20s, 40s)
- ✓ Log retry count

### ⏱ Timeout
- ✓ Job timeout 120 giây
- ✓ Tự động fail nếu quá timeout
- ✓ Log error message

### 📊 Dashboard
- ✓ Thống kê job
- ✓ Tỷ lệ thành công
- ✓ Retry failed job
- ✓ Clear failed job

## 💡 Performance Tips

1. **Batch Processing:**
   ```php
   use Illuminate\Bus\Batch;
   Bus::batch([
       new SendWelcomeEmailJob('user1@example.com', 'User 1'),
       new SendWelcomeEmailJob('user2@example.com', 'User 2'),
   ])->dispatch();
   ```

2. **Multiple Workers:**
   ```bash
   # Terminal 1
   php artisan queue:work --queue=default
   
   # Terminal 2
   php artisan queue:work --queue=default
   ```

3. **Queue Timeout:**
   ```bash
   php artisan queue:work --timeout=180
   ```

4. **Redis Performance:**
   - Nhanh hơn database driver
   - Phù hợp high-traffic
   - Cần Redis server

## 🚀 Deployment Checklist

- [ ] Chạy migration: `php artisan migrate`
- [ ] Cấu hình mail: `.env` MAIL_* settings
- [ ] Cấu hình queue: `.env` QUEUE_CONNECTION
- [ ] Chạy queue worker: `php artisan queue:work` (background)
- [ ] Kiểm tra logs: `tail -f storage/logs/laravel.log`
- [ ] Monitor dashboard: `/dashboard`

## 🔗 Các Đường Dẫn Chính

- 📝 Register: `http://localhost:8000/register`
- 📊 Dashboard: `http://localhost:8000/dashboard`
- 📋 Job Logs: `http://localhost:8000/job-logs`
- 📈 API Stats: `http://localhost:8000/dashboard/stats`

## 📚 Tài Liệu Tham Khảo

- [Laravel Queue Docs](https://laravel.com/docs/11.x/queues)
- [Laravel Mail Docs](https://laravel.com/docs/11.x/mail)
- [Laravel Jobs Docs](https://laravel.com/docs/11.x/queues#creating-jobs)
- [Horizon - Queue Monitoring](https://laravel.com/docs/11.x/horizon)

---

**Created by:** Bài Tập 8 - Laravel Queue + Mail Nâng Cao
**Date:** 2026-01-14
