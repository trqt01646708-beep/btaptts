# 🔧 Commands Reference - Bài Tập 8

## 🚀 Getting Started Commands

```bash
# 1. Run migration to create tables
php artisan migrate

# 2. Start queue worker (in Terminal 1)
php artisan queue:work

# 3. Start Laravel server (in Terminal 2)
php artisan serve

# 4. Open browser (Terminal 3 or browser)
http://localhost:8000/register
```

## 📧 Bulk Email Commands

```bash
# Send 10 welcome emails
php artisan email:bulk-welcome 10

# Send 50 welcome emails
php artisan email:bulk-welcome 50

# Send 100 welcome emails
php artisan email:bulk-welcome 100
```

## 🧪 Testing Commands

```bash
# Enter Tinker shell
php artisan tinker

# In Tinker:
# Send single email
> App\Jobs\SendWelcomeEmailJob::dispatch('test@example.com', 'Test User');

# Send bulk
> for ($i = 1; $i <= 5; $i++) App\Jobs\SendWelcomeEmailJob::dispatch("user{$i}@example.com", "User {$i}");

# Check logs
> App\Models\JobLog::all();

# Get success count
> App\Models\JobLog::where('status', 'success')->count();

# Get failed count
> App\Models\JobLog::where('status', 'failed')->count();

# Get specific email logs
> App\Models\JobLog::where('email', 'test@example.com')->get();

# Exit Tinker
> exit
```

## 🧪 Run Test Suite

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/QueueMailTest.php

# Run with verbose output
php artisan test tests/Feature/QueueMailTest.php -v

# Run with details
php artisan test tests/Feature/QueueMailTest.php --verbose
```

## 📊 Queue Management Commands

```bash
# Show failed jobs
php artisan queue:failed

# Retry specific failed job
php artisan queue:retry job-id

# Retry all failed jobs
php artisan queue:retry-all

# Forget (delete) specific failed job
php artisan queue:forget job-id

# Flush entire queue
php artisan queue:clear

# Monitor queue status
php artisan queue:monitor redis:default database:default

# Pause queue
php artisan queue:pause

# Continue queue
php artisan queue:continue
```

## 🔍 Monitoring & Debugging Commands

```bash
# View application logs
tail -f storage/logs/laravel.log

# Clear all logs
php artisan log:clear

# Check queue connections
php artisan queue:connections

# Monitor queue process (if Horizon installed)
php artisan horizon

# View scheduled jobs
php artisan schedule:list
```

## 🗄️ Database Commands

```bash
# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Rollback all migrations
php artisan migrate:reset

# Fresh migrate (clear and migrate)
php artisan migrate:fresh

# Check migration status
php artisan migrate:status

# Seed database
php artisan db:seed
```

## 💾 Cache & Config Commands

```bash
# Clear all cache
php artisan cache:clear

# Clear config cache
php artisan config:clear

# Clear view cache
php artisan view:clear

# Clear route cache
php artisan route:clear

# Optimize (build cache)
php artisan optimize

# Clear optimization
php artisan optimize:clear
```

## 🎨 Development Commands

```bash
# Generate application key
php artisan key:generate

# Create new controller
php artisan make:controller MyController

# Create new model
php artisan make:model MyModel

# Create new migration
php artisan make:migration create_table_name

# Create new job
php artisan make:job MyJob

# Create new command
php artisan make:command MyCommand

# Create new event
php artisan make:event MyEvent

# Create new listener
php artisan make:listener MyListener
```

## 🌍 Web URLs

```
# Registration
http://localhost:8000/register

# Dashboard
http://localhost:8000/dashboard

# Job Logs
http://localhost:8000/job-logs

# Dashboard API Stats
http://localhost:8000/dashboard/stats
```

## 🔄 Queue Worker Modes

```bash
# Basic queue worker
php artisan queue:work

# Worker with watch mode (restart on file change)
php artisan queue:work --watch

# Worker with specific sleep (seconds between jobs)
php artisan queue:work --sleep=5

# Worker with specific timeout
php artisan queue:work --timeout=120

# Worker with specific queue
php artisan queue:work --queue=default

# Worker with daemon mode
php artisan queue:work --daemon

# Worker with max jobs
php artisan queue:work --max-jobs=1000

# Worker with max attempts
php artisan queue:work --max-attempts=3

# Combined options
php artisan queue:work --watch --sleep=3 --timeout=60
```

## 📝 Useful Database Queries (Tinker)

```php
# In tinker:

# Total jobs
DB::table('job_logs')->count();

# Success rate
DB::table('job_logs')->where('status', 'success')->count() / DB::table('job_logs')->count() * 100;

# Failed jobs with error
DB::table('job_logs')->where('status', 'failed')->select('email', 'error_message')->get();

# Jobs by date
DB::table('job_logs')->where('created_at', '>', now()->subHours(1))->count();

# Average retry count
DB::table('job_logs')->avg('retry_count');

# Jobs still pending
DB::table('job_logs')->where('status', 'pending')->count();

# Clear all logs
DB::table('job_logs')->truncate();

# Delete failed logs
DB::table('job_logs')->where('status', 'failed')->delete();
```

## 🔧 Configuration Editing

```bash
# Edit .env file
nano .env
# or
vi .env
# or
code .env (VS Code)

# Key environment variables:
QUEUE_CONNECTION=database        # or redis
MAIL_MAILER=log                  # or smtp
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
DB_CONNECTION=sqlite
```

## 📦 Package Management

```bash
# Install/update packages
composer install
composer update

# Show outdated packages
composer outdated

# Show what packages are installed
composer show

# Dump autoloader
composer dump-autoload -o
```

## 🐛 Troubleshooting Commands

```bash
# Check PHP version
php -v

# Check installed extensions
php -m

# Check PHP ini
php -i

# Test Laravel installation
php artisan about

# Check Laravel version
php artisan --version

# List all available commands
php artisan list

# Get help for command
php artisan help queue:work
```

## ⚡ Performance Commands

```bash
# Optimize application
php artisan optimize

# Clear optimization
php artisan optimize:clear

# Cache routes
php artisan route:cache

# Cache config
php artisan config:cache

# Cache views
php artisan view:cache

# Clear all caches
php artisan cache:clear && php artisan config:clear && php artisan view:clear && php artisan route:clear
```

## 📋 Cheat Sheet - Common Workflows

### Workflow 1: Fresh Start
```bash
php artisan migrate:fresh
php artisan cache:clear
php artisan config:clear
php artisan serve
# In new terminal:
php artisan queue:work
```

### Workflow 2: Test Bulk Email
```bash
# Terminal 1
php artisan queue:work

# Terminal 2
php artisan tinker
> for ($i = 1; $i <= 10; $i++) \
    App\Jobs\SendWelcomeEmailJob::dispatch("user{$i}@example.com", "User {$i}");
```

### Workflow 3: Debug Failed Job
```bash
php artisan queue:failed
php artisan tinker
> $log = App\Models\JobLog::where('status', 'failed')->first();
> echo $log->error_message;
> php artisan queue:retry {job-id}
```

### Workflow 4: Monitor Dashboard
```bash
# Terminal 1: Queue worker
php artisan queue:work --watch

# Terminal 2: Laravel server
php artisan serve

# Browser: http://localhost:8000/dashboard
# Refresh to see real-time updates
```

---

**Pro Tips:**
- 💡 Use `--watch` flag for development auto-reload
- 💡 Use multiple terminals for better organization
- 💡 Check logs with `tail -f` for real-time monitoring
- 💡 Use Tinker for quick database queries
- 💡 Use `php artisan about` to check setup

---

**Last Updated:** 2026-01-14
