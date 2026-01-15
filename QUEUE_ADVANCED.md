# Laravel Queue + Mail Nâng Cao - Hướng Dẫn Chi Tiết

## 📋 Mục Tiêu
- Sử dụng Queue Driver Database để lưu jobs
- Gửi email hàng loạt không bị timeout
- Hiểu retry, timeout, backoff strategy
- Log lại trạng thái thành công/thất bại

---

## 🏗️ Kiến Trúc Hệ Thống

```
┌─────────────────┐
│  Form Đăng Ký   │
└────────┬────────┘
         │
         ▼
┌─────────────────────────────┐
│ RegistrationController      │ ← Validate + Create User
└────────┬────────────────────┘
         │
         ▼
┌─────────────────────────────┐
│ SendWelcomeEmailJob::       │ ← Push vào Queue
│ dispatch($email, $name)     │
└────────┬────────────────────┘
         │
         ▼
┌─────────────────────────────┐
│  jobs table (Database)      │ ← Lưu trữ job
└────────┬────────────────────┘
         │
         ▼
┌─────────────────────────────┐
│  queue:work (Worker)        │ ← Process jobs
└────────┬────────────────────┘
         │
         ▼
┌─────────────────────────────┐
│ SendWelcomeEmailJob::handle │ ← Thực thi job
│ - Gửi email                 │
│ - Log trạng thái            │
└────────┬────────────────────┘
         │
         ▼
┌─────────────────────────────┐
│  job_logs table (DB)        │ ← Lưu kết quả
│  - status                   │
│  - error_message            │
│  - retry_count              │
└─────────────────────────────┘
```

---

## 🔧 Cấu Hình Hiện Tại

### Queue Driver: Database
```php
// config/queue.php
'default' => env('QUEUE_CONNECTION', 'database'),

'connections' => [
    'database' => [
        'driver' => 'database',
        'connection' => env('DB_QUEUE_CONNECTION'),
        'table' => env('DB_QUEUE_TABLE', 'jobs'),
        'queue' => env('DB_QUEUE', 'default'),
        'retry_after' => (int) env('DB_QUEUE_RETRY_AFTER', 90),
        'after_commit' => false,
    ],
]
```

### Mail Driver: Log (Development)
```php
// config/mail.php
'default' => env('MAIL_MAILER', 'log'),
```

Để dùng SMTP thực tế:
```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hello@example.com
```

---

## 📤 Jobs Table Schema

Bảng `jobs` (tạo tự động bởi Laravel):
```
id (PK)
queue (default)
payload (JSON - chứa class + data)
attempts (số lần thử)
reserved_at (khi worker đang xử lý)
available_at (khi sẵn sàng xử lý)
created_at
```

---

## 📋 Job Logs Table Schema

Bảng `job_logs` (tự tạo để tracking):
```
id (PK)
job_name (SendWelcomeEmailJob)
email (người nhận)
status (pending/processing/success/failed)
payload (JSON - data gốc)
error_message (nếu thất bại)
retry_count (số lần thử hiện tại)
max_retries (tối đa 3)
started_at (khi bắt đầu)
completed_at (khi kết thúc)
created_at
updated_at
```

---

## 🚀 Cách Sử Dụng

### 1️⃣ Setup Ban Đầu
```bash
# Tạo migration cho jobs table (Laravel tự tạo)
php artisan queue:table
php artisan migrate

# Hoặc nếu đã có database
php artisan migrate
```

### 2️⃣ Đăng Ký User (Push Job)
```php
// app/Http/Controllers/RegistrationController.php
$user = User::create([...]);
SendWelcomeEmailJob::dispatch($user->email, $user->name);
// ✅ Job được lưu vào database, chưa thực thi
```

### 3️⃣ Chạy Queue Worker
```bash
# Terminal 1: Chạy worker (lắng nghe jobs)
php artisan queue:work

# Hoặc chạy 1 job rồi dừng
php artisan queue:work --once

# Chạy với supervisor (production)
```

### 4️⃣ Kiểm Tra Trạng Thái
- Đăng ký tài khoản: `/register`
- Xem logs: `/job-logs`
- Dashboard: `/dashboard`

---

## ⚙️ Job Configuration

### Retry & Timeout
```php
// app/Jobs/SendWelcomeEmailJob.php

class SendWelcomeEmailJob implements ShouldQueue
{
    // Số lần thử tối đa
    public $tries = 3;
    
    // Thời gian timeout (giây)
    public $timeout = 120;
    
    // Backoff strategy (giây)
    public function backoff(): int
    {
        // Lần 1: 10s, Lần 2: 20s, Lần 3: 40s
        return 10 * (2 ** ($this->attempts() - 1));
    }
}
```

### Trạng Thái Job
- **pending**: Chưa xử lý (mới push vào queue)
- **processing**: Đang xử lý bởi worker
- **success**: Gửi email thành công
- **failed**: Gửi email thất bại (sau 3 lần thử)

---

## 🔍 Monitoring & Debugging

### Xem Jobs trong Queue
```bash
php artisan queue:monitor
```

### Xem Failed Jobs
```bash
php artisan queue:failed
php artisan queue:failed-table
php artisan migrate
```

### Retry Failed Jobs
```bash
# Retry 1 job thất bại
php artisan queue:retry 1

# Retry tất cả
php artisan queue:retry all

# Hoặc dùng button trong Dashboard
```

### Logs
- `storage/logs/laravel.log` - Mọi hành động
- `job_logs` table - Chi tiết từng job

---

## 📊 Production Deployment

### Supervisor Config (Ubuntu/Linux)
```ini
[program:laravel-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/app/artisan queue:work database --sleep=3 --tries=3
autostart=true
autorestart=true
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/app/storage/logs/queue.log
```

### Redis Queue (More Scalable)
```dotenv
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

---

## 🚨 Common Issues & Solutions

| Issue | Nguyên Nhân | Giải Pháp |
|-------|-----------|----------|
| Job không chạy | Worker không chạy | `php artisan queue:work` |
| Email không gửi | Mail driver sai | Kiểm tra `.env` MAIL_* |
| Job timeout | Timeout quá ngắn | Tăng `$timeout` hoặc số `$tries` |
| Memory leak | Không release resources | Restart worker định kỳ |
| Database lock | Job trùng lặp | Kiểm tra `unique:users` validation |

---

## 📝 File Cấu Trúc

```
app/
├── Http/Controllers/
│   ├── RegistrationController.php    ← Push job
│   └── DashboardController.php       ← Xem logs
├── Jobs/
│   └── SendWelcomeEmailJob.php       ← Job logic
├── Mail/
│   └── WelcomeEmail.php              ← Email template
└── Models/
    ├── User.php
    └── JobLog.php                    ← Log tracking

config/
├── queue.php                          ← Queue config
└── mail.php                           ← Mail config

database/
└── migrations/
    ├── create_jobs_table.php
    └── create_job_logs_table.php

resources/views/
├── job-logs.blade.php                 ← View logs
├── dashboard.blade.php                ← Dashboard
└── emails/
    └── welcome.blade.php              ← Email content

routes/web.php                         ← Routes
```

---

## ✅ Checklist Hoàn Thành

- [x] Queue driver: Database
- [x] Job: SendWelcomeEmailJob
- [x] Mail: WelcomeEmail
- [x] Logging: job_logs table
- [x] Retry logic: maxTries=3, backoff exponential
- [x] Timeout: 120 giây
- [x] Dashboard: Xem logs + delete user
- [x] Handling: Success/Failed tracking

---

## 🎓 Bài Học Chính

1. **Queue = Async Processing**: Tránh timeout, improve UX
2. **Retry Strategy**: Backoff exponential, maxTries limits
3. **Logging**: Chi tiết hóa mọi action cho debugging
4. **Error Handling**: Catch exception, update status, retry
5. **Worker Process**: Chạy liên tục, lắng nghe jobs

---

## 📞 Tiếp Theo

- [ ] Sử dụng Redis queue (production-ready)
- [ ] Implement Supervisor (auto-restart worker)
- [ ] Email notifications (notify admin on failure)
- [ ] Batch processing (gửi hàng loạt)
- [ ] Dashboard real-time (WebSocket)
