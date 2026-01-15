#!/usr/bin/env php
<?php

/**
 * Testing Queue and Mail System
 * 
 * Run: php artisan tinker < tests/Queue/test_queue.php
 * Or manually copy-paste commands into tinker
 */

// Test 1: Send single welcome email via queue
echo "=== Test 1: Single Job ===\n";
App\Jobs\SendWelcomeEmailJob::dispatch('test@example.com', 'Test User');
echo "✓ Job dispatched to queue\n\n";

// Test 2: Send bulk emails
echo "=== Test 2: Bulk Jobs ===\n";
for ($i = 1; $i <= 5; $i++) {
    App\Jobs\SendWelcomeEmailJob::dispatch("bulk{$i}@example.com", "Bulk User {$i}");
}
echo "✓ 5 jobs dispatched to queue\n\n";

// Test 3: Check job logs
echo "=== Test 3: Job Logs ===\n";
$logs = App\Models\JobLog::latest()->limit(5)->get();
echo "Total logs: " . App\Models\JobLog::count() . "\n";
echo "Success: " . App\Models\JobLog::where('status', 'success')->count() . "\n";
echo "Failed: " . App\Models\JobLog::where('status', 'failed')->count() . "\n";
echo "Pending: " . App\Models\JobLog::where('status', 'pending')->count() . "\n\n";

// Test 4: View pending jobs
echo "=== Test 4: Pending Jobs ===\n";
$pending = App\Models\JobLog::where('status', 'pending')->get();
$pending->each(function($log) {
    echo "- {$log->email} (created: {$log->created_at})\n";
});
echo "\n";

// Test 5: Direct mail send (test)
echo "=== Test 5: Direct Mail Send (test) ===\n";
try {
    Mail::to('direct@example.com')->send(new App\Mail\WelcomeEmail('Direct Test'));
    echo "✓ Direct mail sent\n";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
