# 🚀 Hướng Dẫn Chi Tiết: Laravel Queue + Mail

## 📌 Tóm Tắt
Hệ thống này cho phép gửi email **không đồng bộ** (asynchronous) để tránh timeout khi người dùng đăng ký.

**Flow:**
```
Người dùng đăng ký 
    ↓
Job được push vào Queue (database)
    ↓
Người dùng nhận phản hồi ngay (không chờ gửi email)
    ↓
Worker chạy nền xử lý jobs
    ↓
Email được gửi + Log được lưu vào database
```

---

## ⚙️ Setup Lần Đầu

### 1. Tạo Database Tables
```bash
php artisan migrate
```

Điều này sẽ tạo:
- `users` - Lưu tài khoản người dùng
- `jobs` - Lưu các jobs chờ xử lý (queue)
- `job_logs` - Lưu lịch sử gửi email

### 2. Kiểm Tra Cấu Hình
```bash
# File quan trọng:
cat .env | grep QUEUE
cat .env | grep MAIL
```

Bạn sẽ thấy:
```
QUEUE_CONNECTION=database    # ✅ Dùng database làm queue
MAIL_MAILER=log              # ✅ Log mail (dev mode)
```

---

## 🎯 Cách Sử Dụng

### Bước 1: Chạy Queue Worker
```bash
# Mở Terminal 1
php artisan queue:work
```

**Kết quả:**
```
[2026-01-14 10:30:00] Processing: App\Jobs\SendWelcomeEmailJob
[2026-01-14 10:30:00] ✅ Email sent successfully
```

⚠️ **Quan trọng**: Worker phải **chạy liên tục** ở nền. Nếu dừng → jobs không được xử lý.

### Bước 2: Đăng Ký User
```bash
# Mở Terminal 2
php artisan serve

# Hoặc truy cập: http://localhost:8000/register
```

1. Điền form đăng ký
2. Click "Đăng Ký"
3. **Bạn sẽ redirect ngay** (không chờ email gửi)
4. Job được lưu vào queue

### Bước 3: Kiểm Tra Queue
```bash
# Xem jobs đang chờ
php artisan queue:monitor

# Xem jobs đã fail
php artisan queue:failed

# Xem logs
cat storage/logs/laravel.log
```

### Bước 4: Xem Dashboard
```
http://localhost:8000/dashboard
```

Sẽ thấy:
- ✅ **Tổng Công Việc**: Số lượng jobs
- ✅ **Thành Công**: Jobs gửi email thành công
- ❌ **Thất Bại**: Jobs bị lỗi
- ⚙️ **Đang Xử Lý**: Jobs đang chạy
- ⏳ **Đợi Xử Lý**: Jobs chờ xử lý

---

## 📊 Database Tables

### 1. `jobs` table (Queue Storage)
```
id          | queue    | payload                        | attempts | reserved_at | available_at | created_at
1           | default  | {"displayName":"SendWelcome..."| 1        | NULL        | 2026-01-14   | 2026-01-14
```

Khi worker xử lý → dòng này bị xóa.

### 2. `job_logs` table (Tracking)
```
id | job_name                      | email           | status    | retry_count | error_message | created_at
1  | App\Jobs\SendWelcomeEmailJob  | user@gmail.com  | success   | 0           | NULL          | 2026-01-14
2  | App\Jobs\SendWelcomeEmailJob  | test@gmail.com  | failed    | 3           | Connection... | 2026-01-14
```

Đây là **công cụ tracking chính** để debug.

---

## 🔄 Retry Logic

### Cấu Hình Hiện Tại
```php
// app/Jobs/SendWelcomeEmailJob.php
public $tries = 3;          // Thử 3 lần
public $timeout = 120;      // Timeout sau 120 giây
public $backoff = [10,20,40]; // Delay: 10s, 20s, 40s
```

### Quy Trình Retry
```
Lần 1: Thử gửi email ngay
    ↓ (Nếu fail) → Chờ 10 giây
    
Lần 2: Thử lại lần 2
    ↓ (Nếu fail) → Chờ 20 giây
    
Lần 3: Thử lại lần 3
    ↓ (Nếu fail) → FAILED (ghi vào job_logs)
```

**Ví dụ: Email gửi fail vì server mail down**
- 10:00:00 - Thử 1 → Lỗi
- 10:00:10 - Thử 2 → Lỗi
- 10:00:30 - Thử 3 → Lỗi
- **Kết quả: FAILED** → Ghi vào database

---

## 🧪 Kiểm Tra Nhanh

### Cách 1: Tạo User Test (UI)
1. Vào `http://localhost:8000/register`
2. Điền form
3. Click "Đăng Ký"
4. **Chờ vài giây** (worker xử lý)
5. Vào `/job-logs` → Xem email

### Cách 2: Tạo User Test (Command)
```bash
php artisan app:test-queue --count=5
```

Sẽ tạo 5 user test + push 5 jobs vào queue.

### Cách 3: Tạo Job Thủ Công (Tinker)
```bash
php artisan tinker

# Copypaste:
use App\Models\User;
use App\Jobs\SendWelcomeEmailJob;

$user = User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password'),
]);

SendWelcomeEmailJob::dispatch($user->email, $user->name);

# Exit: exit (hoặc Ctrl+D)
```

---

## 🐛 Troubleshooting

### ❌ Problem: Jobs không được xử lý
**Nguyên nhân**: Worker không chạy

**Giải pháp**:
```bash
# Check xem worker có chạy không
ps aux | grep "queue:work"

# Nếu không, chạy:
php artisan queue:work
```

### ❌ Problem: Email không gửi được
**Nguyên nhân**: MAIL_MAILER=log (chỉ log, không gửi)

**Giải pháp** (để dùng Gmail):
1. Tạo App Password: https://myaccount.google.com/apppasswords
2. Cập nhật `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=xxxx-xxxx-xxxx-xxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
```
3. Chạy lại

### ❌ Problem: Job timeout
**Nguyên nhân**: Email mất quá lâu để gửi

**Giải pháp**:
```php
// app/Jobs/SendWelcomeEmailJob.php
public $timeout = 300;  // Tăng lên 5 phút
```

### ❌ Problem: Duplicate emails gửi
**Nguyên nhân**: User click "Đăng Ký" nhiều lần

**Giải pháp**: 
```php
// app/Http/Controllers/RegistrationController.php
// Đã có validation: 'email' => 'unique:users'
```

---

## 📈 Advanced: Production Setup

### Sử Dụng Redis (Nhanh hơn)
```env
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

### Sử Dụng Supervisor (Auto-restart)
**File: /etc/supervisor/conf.d/laravel-queue.conf**
```ini
[program:laravel-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/app/artisan queue:work database --tries=3 --timeout=120
autostart=true
autorestart=true
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/app/storage/logs/queue.log
```

**Chạy**:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-queue:*
```

---

## 📋 Tóm Tắt File Quan Trọng

| File | Mục Đích |
|------|---------|
| `app/Jobs/SendWelcomeEmailJob.php` | Logic gửi email |
| `app/Mail/WelcomeEmail.php` | Template email |
| `app/Http/Controllers/RegistrationController.php` | Xử lý form đăng ký |
| `app/Models/JobLog.php` | Model tracking |
| `config/queue.php` | Cấu hình queue |
| `config/mail.php` | Cấu hình email |
| `.env` | Biến môi trường |

---

## 🎓 Khái Niệm Chính

| Khái Niệm | Giải Thích |
|----------|-----------|
| **Queue** | Hàng đợi chứa jobs chờ xử lý |
| **Job** | Công việc/task cần làm (ví dụ: gửi email) |
| **Worker** | Chương trình chạy nền, lấy job từ queue và xử lý |
| **Dispatch** | Thêm job vào queue |
| **Retry** | Thử lại job nếu fail |
| **Backoff** | Chờ trước khi thử lại (tránh overwhelm server) |
| **Timeout** | Thời gian tối đa job được chạy |
| **Payload** | Dữ liệu được gửi kèm job |

---

## ✅ Checklist Hoàn Thành

- [ ] Chạy `php artisan migrate`
- [ ] Mở 2 terminal: 1 chạy worker, 1 chạy serve
- [ ] Tạo user test qua `/register`
- [ ] Kiểm tra `/job-logs`
- [ ] Kiểm tra `/dashboard`
- [ ] Xem `storage/logs/laravel.log`

---

## 🎯 Kết Quả Mong Đợi

✅ Người dùng đăng ký → Nhận phản hồi ngay
✅ Job được lưu vào `jobs` table
✅ Worker xử lý job → Gửi email
✅ Kết quả được log vào `job_logs` table
✅ Dashboard hiển thị stats đúng
✅ Có thể retry failed jobs
✅ Có thể xóa user + logs

---

## 📞 Hỏi & Đáp

**Q: Worker phải chạy mãi mãi à?**
A: Vâng, hoặc dùng Supervisor để auto-restart.

**Q: Tại sao phải dùng Queue?**
A: Nếu không dùng Queue, user phải chờ 5 giây để gửi email xong → Tệ UX.

**Q: Email đâu nếu dùng MAIL_MAILER=log?**
A: Email được viết vào `storage/logs/laravel.log`, không gửi thực sự.

**Q: Làm sao để production chạy được?**
A: Dùng Supervisor + Redis + Gmail/SendGrid.

---

## 🚀 Bước Tiếp Theo

- Cấu hình SMTP thực (Gmail/SendGrid)
- Setup Supervisor production
- Monitoring real-time với WebSocket
- Batch email (gửi nhiều cùng lúc)
- Cron jobs (scheduled tasks)

---

**Tạo bởi**: Laravel Queue System  
**Phiên bản**: 1.0  
**Ngày**: 2026-01-14
