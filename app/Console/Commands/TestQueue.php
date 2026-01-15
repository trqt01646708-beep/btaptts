<?php

namespace App\Console\Commands;

use App\Jobs\SendWelcomeEmailJob;
use App\Models\User;
use Illuminate\Console\Command;

class TestQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-queue {--count=5 : Number of test users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test queue by creating users and dispatching jobs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = $this->option('count');
        
        $this->info("🚀 Starting Queue Test...");
        $this->newLine();

        for ($i = 1; $i <= $count; $i++) {
            $timestamp = now()->timestamp;
            
            try {
                $user = User::create([
                    'name' => "Test User {$i} - {$timestamp}",
                    'email' => "test{$i}-{$timestamp}@example.com",
                    'password' => bcrypt('password123'),
                ]);

                SendWelcomeEmailJob::dispatch($user->email, $user->name);

                $this->info("✅ [{$i}/{$count}] User created & job dispatched: " . $user->email);

            } catch (\Exception $e) {
                $this->error("❌ [{$i}/{$count}] Error: " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info("✨ Test Complete!");
        $this->info("");
        $this->info("📊 Now run: php artisan queue:work");
        $this->info("📋 View logs: http://localhost:8000/job-logs");
        $this->info("📈 Dashboard: http://localhost:8000/dashboard");
    }
}
