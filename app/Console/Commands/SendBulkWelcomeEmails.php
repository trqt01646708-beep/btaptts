<?php

namespace App\Console\Commands;

use App\Jobs\SendWelcomeEmailJob;
use App\Models\User;
use Illuminate\Console\Command;

class SendBulkWelcomeEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:bulk-welcome {count=10 : Number of emails to send}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send bulk welcome emails using queue';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $count = (int) $this->argument('count');
        $this->info("Preparing to send {$count} welcome emails...");

        $progressBar = $this->output->createProgressBar($count);
        $progressBar->start();

        for ($i = 0; $i < $count; $i++) {
            $email = "user{$i}@example.com";
            $name = "User {$i}";

            SendWelcomeEmailJob::dispatch($email, $name);

            $progressBar->advance();
        }

        $progressBar->finish();

        $this->newLine();
        $this->info("✓ {$count} jobs have been queued successfully!");
        $this->info("Run 'php artisan queue:work' to process the jobs.");

        return Command::SUCCESS;
    }
}
