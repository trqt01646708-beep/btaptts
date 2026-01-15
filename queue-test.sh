#!/bin/bash

# Laravel Queue Testing Script
# Hướng dẫn kiểm tra hệ thống Queue + Mail

echo "=========================================="
echo "  Laravel Queue + Mail Testing"
echo "=========================================="
echo ""

# 1. Check if database is ready
echo "1️⃣  Checking database..."
php artisan migrate:status

echo ""
echo "2️⃣  Starting queue worker..."
echo "   Run this in a separate terminal:"
echo "   $ php artisan queue:work"
echo ""

# 2. Test by creating a user
echo "3️⃣  Creating test user..."
php artisan tinker << 'EOF'
use App\Models\User;
use App\Jobs\SendWelcomeEmailJob;

// Tạo user test
$user = User::create([
    'name' => 'Test User ' . now()->timestamp,
    'email' => 'test' . now()->timestamp . '@example.com',
    'password' => bcrypt('password123'),
]);

echo "✅ User created: " . $user->email . "\n";

// Push job vào queue
SendWelcomeEmailJob::dispatch($user->email, $user->name);
echo "✅ Job pushed to queue\n";

// Check job_logs
$logs = \App\Models\JobLog::where('email', $user->email)->get();
echo "📋 Job logs: " . $logs->count() . " records\n";
EOF

echo ""
echo "4️⃣  Monitoring Queue..."
echo "   Command: php artisan queue:monitor"
echo ""

echo "5️⃣  View failed jobs..."
echo "   Command: php artisan queue:failed"
echo ""

echo "6️⃣  Check Dashboard:"
echo "   📊 http://localhost:8000/dashboard"
echo "   📋 http://localhost:8000/job-logs"
echo ""

echo "=========================================="
echo "  Queue Testing Complete!"
echo "=========================================="
