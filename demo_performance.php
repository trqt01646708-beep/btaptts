<?php

/**
 * Demo Performance Comparison: Synchronous vs Queue
 * 
 * Chạy file này để thấy sự khác biệt giữa:
 * - Phương pháp đồng bộ (send email trực tiếp)
 * - Phương pháp bất đồng bộ (dùng queue)
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Jobs\SendWelcomeEmailJob;

echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║  DEMO: SYNCHRONOUS vs ASYNCHRONOUS (QUEUE) PROCESSING    ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n\n";

// ============================================================
// SCENARIO 1: GỬI 1 EMAIL
// ============================================================

echo "📊 SCENARIO 1: Đăng ký 1 user\n";
echo str_repeat("─", 60) . "\n\n";

echo "⚠️  CÁCH 1: Đồng bộ (Synchronous) - KHÔNG DÙNG QUEUE\n";
echo "└─ Giả lập: Gửi email trực tiếp trong request\n\n";

$start = microtime(true);
// Giả lập gửi email đồng bộ (mất 2 giây)
echo "   ├─ Validate user data... (50ms)\n";
usleep(50000);
echo "   ├─ Create user in database... (100ms)\n";
usleep(100000);
echo "   ├─ Send email SYNCHRONOUSLY... (2000ms) ⏳ CHẬM!\n";
usleep(2000000); // 2 seconds = 2,000,000 microseconds
echo "   └─ Return response to user\n\n";
$end = microtime(true);
$syncTime = round(($end - $start) * 1000, 2);

echo "   ⏱️  Total Response Time: {$syncTime}ms\n";
echo "   😤 User phải chờ {$syncTime}ms để thấy kết quả!\n\n";

echo str_repeat("─", 60) . "\n\n";

echo "✅ CÁCH 2: Bất đồng bộ (Asynchronous) - DÙNG QUEUE\n";
echo "└─ Push job vào queue, worker xử lý sau\n\n";

$start = microtime(true);
echo "   ├─ Validate user data... (50ms)\n";
usleep(50000);
echo "   ├─ Create user in database... (100ms)\n";
usleep(100000);
echo "   ├─ Dispatch job to queue... (10ms) ⚡ NHANH!\n";
usleep(10000);

// Thực tế dispatch job
SendWelcomeEmailJob::dispatch('demo@example.com', 'Demo User');

echo "   └─ Return response to user\n\n";
$end = microtime(true);
$asyncTime = round(($end - $start) * 1000, 2);

echo "   ⏱️  Total Response Time: {$asyncTime}ms\n";
echo "   😊 User chỉ chờ {$asyncTime}ms để thấy kết quả!\n";
echo "   📨 Email sẽ được gửi ở background bởi queue worker\n\n";

// So sánh
$improvement = round($syncTime / $asyncTime, 1);
$timeSaved = $syncTime - $asyncTime;

echo str_repeat("─", 60) . "\n\n";
echo "📈 COMPARISON:\n";
echo "   Synchronous:  {$syncTime}ms 😤\n";
echo "   Asynchronous: {$asyncTime}ms 😊\n";
echo "   ────────────────────────────\n";
echo "   Improvement:  {$improvement}x FASTER! 🚀\n";
echo "   Time Saved:   {$timeSaved}ms\n\n";

// ============================================================
// SCENARIO 2: GỬI 10 EMAILS
// ============================================================

echo str_repeat("═", 60) . "\n\n";
echo "📊 SCENARIO 2: Đăng ký 10 users cùng lúc\n";
echo str_repeat("─", 60) . "\n\n";

echo "⚠️  CÁCH 1: Đồng bộ - 10 requests\n\n";

$totalSyncTime = 0;
$timeoutCount = 0;

for ($i = 1; $i <= 10; $i++) {
    $requestTime = 150 + (2000) + ($i * 50); // Base + email + overhead

    if ($requestTime > 10000) {
        echo "   User {$i}: {$requestTime}ms ⚠️  TIMEOUT!\n";
        $timeoutCount++;
    } else {
        echo "   User {$i}: {$requestTime}ms ✅\n";
        $totalSyncTime += $requestTime;
    }
}

$avgSyncTime = $timeoutCount < 10 ? round($totalSyncTime / (10 - $timeoutCount), 2) : 0;
$successRate = ((10 - $timeoutCount) / 10) * 100;

echo "\n   Average time: {$avgSyncTime}ms\n";
echo "   Success rate: {$successRate}%\n";
echo "   Timeouts: {$timeoutCount}/10 ❌\n\n";

echo str_repeat("─", 60) . "\n\n";

echo "✅ CÁCH 2: Bất đồng bộ - 10 requests + queue\n\n";

$totalAsyncTime = 0;

for ($i = 1; $i <= 10; $i++) {
    $requestTime = 150 + 10 + ($i * 10); // Base + dispatch + overhead
    echo "   User {$i}: {$requestTime}ms ✅ (job queued)\n";
    $totalAsyncTime += $requestTime;

    // Dispatch job
    SendWelcomeEmailJob::dispatch("user{$i}@example.com", "User {$i}");
}

$avgAsyncTime = round($totalAsyncTime / 10, 2);

echo "\n   Average time: {$avgAsyncTime}ms\n";
echo "   Success rate: 100% ✅\n";
echo "   Timeouts: 0/10 🎉\n";
echo "   📨 10 jobs đã được push vào queue\n";
echo "   ⚙️  Queue worker sẽ xử lý tuần tự trong background\n\n";

// So sánh
$improvement2 = round($avgSyncTime / $avgAsyncTime, 1);

echo str_repeat("─", 60) . "\n\n";
echo "📈 COMPARISON (10 USERS):\n";
echo "   Synchronous:  {$avgSyncTime}ms avg, {$successRate}% success 😤\n";
echo "   Asynchronous: {$avgAsyncTime}ms avg, 100% success 😊\n";
echo "   ────────────────────────────\n";
echo "   Improvement:  {$improvement2}x FASTER! 🚀\n";
echo "   No timeouts with Queue! 🎊\n\n";

// ============================================================
// SUMMARY
// ============================================================

echo str_repeat("═", 60) . "\n\n";
echo "✅ SUMMARY: TẠI SAO QUEUE GIẢI QUYẾT TIMEOUT?\n\n";

echo "1. ⚡ Response Time:\n";
echo "   - Sync: User chờ email gửi xong (~2s)\n";
echo "   - Queue: User chỉ chờ job được push (~10ms)\n";
echo "   → {$improvement}x nhanh hơn!\n\n";

echo "2. 🔄 Scalability:\n";
echo "   - Sync: Nhiều requests = nhiều timeouts\n";
echo "   - Queue: Tất cả requests response nhanh\n";
echo "   → 100% success rate!\n\n";

echo "3. 📝 Reliability:\n";
echo "   - Sync: Lỗi = request failed\n";
echo "   - Queue: Job có thể retry 3 lần\n";
echo "   → Đảm bảo email được gửi!\n\n";

echo "4. 📊 Monitoring:\n";
echo "   - Sync: Không track được\n";
echo "   - Queue: Log đầy đủ vào database\n";
echo "   → Dễ debug và optimize!\n\n";

echo str_repeat("═", 60) . "\n\n";

echo "🎉 KẾT LUẬN:\n";
echo "Queue cho phép:\n";
echo "   ✅ Response nhanh (không chờ email)\n";
echo "   ✅ Không bị timeout (dù gửi hàng loạt)\n";
echo "   ✅ Retry khi lỗi (reliable)\n";
echo "   ✅ Log và monitor đầy đủ\n\n";

echo "💡 TIP: Kiểm tra queue worker đang chạy:\n";
echo "   php artisan queue:work\n\n";

echo "💡 TIP: Xem logs vừa tạo:\n";
echo "   php artisan tinker\n";
echo "   >>> App\\Models\\JobLog::latest()->take(5)->get()\n\n";

echo str_repeat("═", 60) . "\n";
