<?php

namespace App\Jobs;

use App\Models\EmailLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use Throwable;

class SendWelcomeEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 60;

    protected $email;
    protected $userName;

    /**
     * Create a new job instance.
     */
    public function __construct($email, $userName)
    {
        $this->email = $email;
        $this->userName = $userName;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Cập nhật trạng thái processing
            $this->updateLog('processing');

            // Gửi email
            Mail::to($this->email)->send(new WelcomeEmail($this->userName));

            // Cập nhật log thành công
            $this->updateLog('success');

            \Log::info("✅ Email sent successfully to {$this->email}");

        } catch (Throwable $e) {
            // Cập nhật log thất bại
            $this->updateLog('failed', $e->getMessage());

            \Log::error("❌ Failed to send email to {$this->email}", [
                'error' => $e->getMessage(),
            ]);

            throw $e; // Re-throw để Laravel retry
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        $this->updateLog('failed', "Job failed after {$this->attempts()} attempts: " . $exception->getMessage());
    }

    protected function updateLog($status, $errorMessage = null)
    {
        $log = EmailLog::where('email', $this->email)
            ->latest()
            ->first();

        if ($log) {
            $updateData = ['status' => $status];
            if ($errorMessage) {
                $updateData['error_message'] = $errorMessage;
            }
            $log->update($updateData);
        }
    }
}
