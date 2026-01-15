# 📧 Laravel Queue + Mail System - Advanced Setup

> Một hệ thống hoàn chỉnh để gửi email không đồng bộ (asynchronous) với retry logic và monitoring.

## 🎯 Mục Tiêu Dự Án

- ✅ **Gửi email không bị timeout** - Sử dụng Queue (asynchronous)
- ✅ **Retry logic thông minh** - Retry 3 lần với backoff exponential
- ✅ **Logging chi tiết** - Track mọi job (pending → success/failed)
- ✅ **Dashboard monitoring** - Xem stats real-time
- ✅ **Error handling** - Xử lý lỗi, log stack trace
- ✅ **User management** - Có thể xóa user + logs

---

## 🏗️ Architecture

```
┌─────────────────────────────────────────────────────────┐
│                    Web Server                            │
│  ┌──────────────────────────────────────────────────┐  │
│  │ http://localhost:8000/register                  │  │
│  │  ↓                                               │  │
│  │  RegistrationController::register()             │  │
│  │  ├─ Validate input                             │  │
│  │  ├─ Create User                                │  │
│  │  └─ SendWelcomeEmailJob::dispatch()            │  │
│  └──────────────────────────┬───────────────────────┘  │
└─────────────────────────────┼──────────────────────────┘
                              │
                    ┌─────────▼─────────┐
                    │  Queue (Database) │
                    │  jobs table       │
                    └─────────┬─────────┘
                              │
                    ┌─────────▼──────────────────┐
                    │  Queue Worker              │
                    │  php artisan queue:work    │
                    │  ├─ Get job from queue     │
                    │  ├─ Execute handle()       │
                    │  ├─ Send email             │
                    │  └─ Update job_logs table  │
                    └─────────┬──────────────────┘
                              │
                    ┌─────────▼──────────────────┐
                    │  Results Storage           │
                    │  ├─ job_logs (DB)          │
                    │  ├─ laravel.log            │
                    │  └─ failed_jobs (DB)       │
                    └────────────────────────────┘
```

---

## 📁 Project Structure

```
app/
├── Console/
│   └── Commands/
│       └── TestQueue.php           # Command test queue
├── Http/
│   └── Controllers/
│       ├── RegistrationController.php
│       └── DashboardController.php
├── Jobs/
│   └── SendWelcomeEmailJob.php     # ⭐ Job chính
├── Mail/
│   └── WelcomeEmail.php            # Email template
└── Models/
    ├── User.php
    ├── JobLog.php                  # ⭐ Tracking model
    └── ...

config/
├── queue.php                        # ⭐ Queue config
├── mail.php                         # ⭐ Mail config
└── ...

database/
├── migrations/
│   ├── create_jobs_table.php        # Queue storage
│   └── create_job_logs_table.php    # ⭐ Tracking table
└── ...

resources/views/
├── dashboard.blade.php
├── job-logs.blade.php
├── auth/
│   └── register.blade.php
└── emails/
    └── welcome.blade.php            # Email content

storage/
└── logs/
    └── laravel.log                  # Logs

.env                                 # ⭐ Environment config
.env.example.queue                   # Config template
```

---

## 🚀 Quick Start

### 1️⃣ Setup Database
```bash
php artisan migrate
```

### 2️⃣ Chạy Worker (Terminal 1)
```bash
php artisan queue:work
```

### 3️⃣ Chạy Server (Terminal 2)
```bash
php artisan serve
```

### 4️⃣ Tạo User Test
- Vào: http://localhost:8000/register
- Điền form + Submit
- Sẽ redirect ngay (job được push)

### 5️⃣ Kiểm Tra Kết Quả
- Dashboard: http://localhost:8000/dashboard
- Logs: http://localhost:8000/job-logs

---

## ⚙️ Configuration

### Queue Driver
**File**: `.env`
```env
QUEUE_CONNECTION=database  # Database (default)
# QUEUE_CONNECTION=redis   # Redis (production)
```

**File**: `config/queue.php`
```php
'database' => [
    'driver' => 'database',
    'table' => 'jobs',
    'queue' => 'default',
    'retry_after' => 90,
],
```

### Mail Configuration
**File**: `.env`
```env
MAIL_MAILER=log              # Log only (dev)
# MAIL_MAILER=smtp           # SMTP (production)
# MAIL_HOST=smtp.gmail.com
# MAIL_PORT=587
```

**File**: `config/mail.php`
```php
'default' => env('MAIL_MAILER', 'log'),
'mailers' => [
    'smtp' => [...],
    'log' => [...],
]
```

### Job Configuration
**File**: `app/Jobs/SendWelcomeEmailJob.php`
```php
class SendWelcomeEmailJob implements ShouldQueue
{
    public $tries = 3;        // Thử 3 lần
    public $timeout = 120;    // Timeout 120 giây
    
    public function backoff(): int
    {
        // 10s, 20s, 40s
        return 10 * (2 ** ($this->attempts() - 1));
    }
}
```

---

## 📊 Database Tables

### `jobs` (Queue Storage)
```
id          int PK
queue       varchar - tên queue (default)
payload     longtext - job serialized data
attempts    int - số lần thử hiện tại
reserved_at timestamp - khi worker đang xử lý
available_at timestamp - sẵn sàng xử lý
created_at  timestamp
```

**Vòng đời**:
```
available_at = now() 
    ↓ (worker lấy)
reserved_at = now() (worker xử lý)
    ↓ (success)
DELETE (xóa từ bảng)
    ↓ (fail)
Move to failed_jobs
```

### `job_logs` (Tracking)
```
id              int PK
job_name        varchar - tên job class
email           varchar - email người nhận
status          enum (pending/processing/success/failed)
payload         longtext - job data (JSON)
error_message   text - lỗi nếu có
retry_count     int - số lần thử hiện tại
max_retries     int - tối đa
started_at      timestamp
completed_at    timestamp
created_at      timestamp
updated_at      timestamp

Indexes:
- email (để tìm logs theo email)
- status (để filter theo trạng thái)
- created_at (để sort time-series)
```

---

## 🔄 Job Lifecycle

```
1. User submits form
   ↓
2. Controller validates + creates User
   ↓
3. SendWelcomeEmailJob::dispatch($email, $name)
   ↓
4. Job serialized → stored in 'jobs' table
   ↓
5. Response sent to user (không chờ email)
   ↓
6. Worker lấy job từ 'jobs' table
   ↓
7. Create entry in 'job_logs' (status=processing)
   ↓
8. Try send email (attempt 1)
   ├─ Success → job_logs.status=success, DELETE from jobs
   └─ Failed → Retry after 10s
   ↓
9. Attempt 2 (after 20s backoff)
   ├─ Success → job_logs.status=success, DELETE from jobs
   └─ Failed → Retry after 20s
   ↓
10. Attempt 3 (after 40s backoff)
    ├─ Success → job_logs.status=success, DELETE from jobs
    └─ Failed → job_logs.status=failed, MOVE to failed_jobs
    ↓
11. Dashboard shows results
```

---

## 🧪 Testing

### Test 1: Tạo User via UI
```
1. http://localhost:8000/register
2. Fill form + Submit
3. Redirect ngay
4. http://localhost:8000/job-logs → See log
```

### Test 2: Tạo Multiple Users
```bash
php artisan app:test-queue --count=5
```

### Test 3: Manual via Tinker
```bash
php artisan tinker

use App\Models\User;
use App\Jobs\SendWelcomeEmailJob;

$user = User::create(['name'=>'X', 'email'=>'x@test.com', 'password'=>bcrypt('p')]);
SendWelcomeEmailJob::dispatch($user->email, $user->name);
```

---

## 🐛 Debugging

### Check Queue
```bash
php artisan queue:monitor      # Real-time queue stats
php artisan queue:failed       # Failed jobs
php artisan queue:flush        # Clear all failed
```

### Retry Failed Jobs
```bash
php artisan queue:retry all    # Retry semua
php artisan queue:retry 1      # Retry specific ID
```

### Logs
```bash
# Real-time
tail -f storage/logs/laravel.log

# View in dashboard
http://localhost:8000/job-logs

# View in DB
SELECT * FROM job_logs WHERE status='failed';
```

---

## 📈 Scaling to Production

### 1. Use Redis Queue
```env
QUEUE_CONNECTION=redis
REDIS_HOST=redis.production.com
REDIS_PORT=6379
```

### 2. Setup Supervisor (Auto-restart)
```ini
[program:laravel-queue]
command=php /app/artisan queue:work redis --tries=3
autostart=true
autorestart=true
numprocs=2
```

### 3. Setup SMTP Email
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=xxx@gmail.com
MAIL_PASSWORD=xxxx-xxxx-xxxx-xxxx
```

### 4. Monitoring
```bash
# Supervisor monitor
supervisorctl status laravel-queue

# Horizon (Advanced monitoring - Laravel package)
php artisan horizon
```

---

## 🎓 Key Concepts

| Concept | Meaning |
|---------|---------|
| **Queue** | Hàng đợi chứa jobs chờ xử lý |
| **Job** | Công việc cần làm (SendWelcomeEmailJob) |
| **Worker** | Process lắng nghe + xử lý jobs |
| **Dispatch** | Thêm job vào queue |
| **Attempt** | Lần thử, bắt đầu từ 1 |
| **Retry** | Thử lại sau delay |
| **Backoff** | Delay trước retry (exponential: 10, 20, 40) |
| **Timeout** | Thời gian tối đa job chạy |
| **Payload** | Dữ liệu được pass vào job |
| **Failed** | Job fail sau tất cả retries |

---

## 📚 Important Files

| File | Purpose |
|------|---------|
| `.env` | Environment config |
| `config/queue.php` | Queue driver config |
| `config/mail.php` | Mail driver config |
| `app/Jobs/SendWelcomeEmailJob.php` | Job implementation |
| `app/Mail/WelcomeEmail.php` | Email template |
| `app/Models/JobLog.php` | Tracking model |
| `app/Http/Controllers/RegistrationController.php` | Form handler |
| `resources/views/job-logs.blade.php` | View logs UI |
| `database/migrations/create_job_logs_table.php` | Tracking table |

---

## 🚨 Troubleshooting

| Problem | Solution |
|---------|----------|
| Jobs không process | Chạy `php artisan queue:work` |
| Email không gửi | Check MAIL_MAILER config |
| Timeout | Tăng `$timeout` |
| Memory leak | Restart worker định kỳ |
| Duplicate email | Check `unique:users` validation |

---

## ✅ Checklist

- [x] Queue driver: Database
- [x] Job: SendWelcomeEmailJob với retry
- [x] Mail: WelcomeEmail template
- [x] Logging: job_logs table
- [x] Dashboard: View stats
- [x] Error handling: Try-catch + failed()
- [x] User deletion: Delete user + logs
- [x] Command: app:test-queue

---

## 📞 References

- [Laravel Queue Docs](https://laravel.com/docs/queue)
- [Laravel Mail Docs](https://laravel.com/docs/mail)
- [Job Middleware](https://laravel.com/docs/queues#job-middleware)
- [Queue Timeout](https://laravel.com/docs/queues#timeout)

---

## 🎯 What's Next?

- [ ] Batch processing (send 1000 emails)
- [ ] Scheduled jobs (cron)
- [ ] Webhook notifications
- [ ] Real-time dashboard (WebSocket)
- [ ] Rate limiting
- [ ] Email templates variation

---

**Created**: 2026-01-14  
**Version**: 1.0  
**Status**: Production Ready
