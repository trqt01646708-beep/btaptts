# 🚀 Quick Start - Laravel Queue + Mail

## ⚡ 5 Phút Để Bắt Đầu

### Terminal 1: Chạy Migration
```bash
php artisan migrate
```

### Terminal 2: Khởi động Queue Worker
```bash
php artisan queue:work
```

### Terminal 3: Mở Trình Duyệt
```bash
http://localhost:8000/register
```

### Điền Form & Đăng Ký
- Name: Your Name
- Email: your@email.com
- Password: password123

### Xem Kết Quả
- Dashboard: `http://localhost:8000/dashboard`
- Job Logs: `http://localhost:8000/job-logs`

---

## 📊 Dashboard Features

### Statistics
- Tổng số job
- Thành công / Thất bại
- Đang xử lý / Đợi xử lý
- Tỷ lệ thành công

### Actions
- Đăng ký mới
- Xem tất cả nhật ký
- Xóa công việc thất bại
- Thử lại công việc

---

## 🧪 Test Commands

```bash
# Gửi 10 emails hàng loạt
php artisan email:bulk-welcome 10

# Xem queue status
php artisan queue:failed

# Clear all queue
php artisan queue:clear

# Tinker mode
php artisan tinker
> App\Jobs\SendWelcomeEmailJob::dispatch('test@example.com', 'Test');
```

---

## 📁 Cấu Trúc Thư Mục

```
bai8/
├── app/
│   ├── Jobs/
│   │   └── SendWelcomeEmailJob.php       ✉️ Job gửi email
│   ├── Mail/
│   │   └── WelcomeEmail.php              📧 Mailable class
│   ├── Models/
│   │   ├── User.php
│   │   └── JobLog.php                    📝 Model nhật ký
│   ├── Http/Controllers/
│   │   ├── RegistrationController.php    📝 Đăng ký
│   │   └── DashboardController.php       📊 Dashboard
│   └── Console/Commands/
│       └── SendBulkWelcomeEmails.php     📤 Command hàng loạt
├── database/
│   ├── migrations/
│   │   └── 2026_01_14_000003_create_job_logs_table.php
│   └── seeders/
├── resources/views/
│   ├── auth/
│   │   └── register.blade.php            🔐 Form đăng ký
│   ├── emails/
│   │   └── welcome.blade.php             ✉️ Email template
│   ├── dashboard.blade.php               📊 Dashboard
│   └── job-logs.blade.php                📋 Danh sách job
├── routes/
│   └── web.php
├── config/
│   ├── queue.php
│   └── mail.php
├── .env                                   ⚙️ Environment config
├── QUEUE_GUIDE.md                         📚 Chi tiết hướng dẫn
└── IMPLEMENTATION_GUIDE.md                🚀 Hướng dẫn triển khai
```

---

## 🔄 Flow Diagram

```
User Registration
      ↓
POST /register
      ↓
RegistrationController::register()
      ↓
Create User
      ↓
SendWelcomeEmailJob::dispatch()
      ↓
Queue (Database/Redis)
      ↓
queue:work listening
      ↓
SendWelcomeEmailJob::handle()
      ↓
Mail::send(WelcomeEmail)
      ↓
JobLog::create(['status' => 'success'])
      ↓
Email gửi thành công ✓
```

---

## ⚙️ Configuration

### Queue Driver
```env
# Database (Default)
QUEUE_CONNECTION=database

# Or Redis
QUEUE_CONNECTION=redis
```

### Mail Driver
```env
# Log to file (Default)
MAIL_MAILER=log

# Or SMTP
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
```

---

## 🎯 Key Features

| Feature | Status | Detail |
|---------|--------|--------|
| Queue Job | ✅ | SendWelcomeEmailJob dispatched |
| Retry Logic | ✅ | Max 3 retries, exponential backoff |
| Timeout | ✅ | 120 seconds per job |
| Logging | ✅ | Full tracking in job_logs table |
| Dashboard | ✅ | Real-time statistics |
| Bulk Send | ✅ | `email:bulk-welcome` command |
| Failed Job | ✅ | Retry button, error tracking |

---

## 📞 Support

### Common Issues

**Q: Job không được xử lý?**
A: Chắc chắn `php artisan queue:work` đang chạy

**Q: Email không gửi?**
A: Kiểm tra MAIL_MAILER config trong .env

**Q: Job bị timeout?**
A: Tăng `timeout` trong SendWelcomeEmailJob

---

## 🎓 Learning Path

1. ✅ Setup & Migration
2. ✅ Create Job & Mailable
3. ✅ Create Controller & Form
4. ✅ Create Dashboard
5. ✅ Queue Monitoring
6. ✅ Error Handling & Logging
7. ✅ Bulk Operations

---

**Ready to go! Happy queuing! 🎉**
