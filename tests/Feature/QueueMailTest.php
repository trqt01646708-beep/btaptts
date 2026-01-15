<?php

namespace Tests\Feature;

use App\Jobs\SendWelcomeEmailJob;
use App\Mail\WelcomeEmail;
use App\Models\JobLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class QueueMailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test job can be dispatched to queue.
     */
    public function test_job_can_be_dispatched(): void
    {
        Queue::fake();

        SendWelcomeEmailJob::dispatch('test@example.com', 'Test User');

        Queue::assertPushed(SendWelcomeEmailJob::class);
    }

    /**
     * Test welcome email can be sent.
     */
    public function test_welcome_email_can_be_sent(): void
    {
        Mail::fake();

        Mail::to('test@example.com')->send(new WelcomeEmail('Test User'));

        Mail::assertSent(WelcomeEmail::class);
    }

    /**
     * Test job logs are created when job runs.
     */
    public function test_job_logs_are_created(): void
    {
        // Dispatch job
        SendWelcomeEmailJob::dispatch('test@example.com', 'Test User');

        // Job log should be created
        $this->assertDatabaseHas('job_logs', [
            'email' => 'test@example.com',
            'status' => 'pending',
        ]);
    }

    /**
     * Test job can handle success.
     */
    public function test_job_handles_success(): void
    {
        Mail::fake();

        $job = new SendWelcomeEmailJob('test@example.com', 'Test User');
        $job->handle();

        // Check log entry exists
        $log = JobLog::where('email', 'test@example.com')->first();
        $this->assertNotNull($log);
        $this->assertEquals('success', $log->status);
    }

    /**
     * Test registration form exists.
     */
    public function test_registration_form_exists(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    /**
     * Test user registration dispatches job.
     */
    public function test_user_registration_dispatches_job(): void
    {
        Queue::fake();

        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        Queue::assertPushed(SendWelcomeEmailJob::class);
    }

    /**
     * Test job logs page exists.
     */
    public function test_job_logs_page_exists(): void
    {
        $response = $this->get('/job-logs');
        $response->assertStatus(200);
    }

    /**
     * Test bulk email dispatch.
     */
    public function test_bulk_email_dispatch(): void
    {
        Queue::fake();

        // Dispatch 10 jobs
        for ($i = 0; $i < 10; $i++) {
            SendWelcomeEmailJob::dispatch("user{$i}@example.com", "User {$i}");
        }

        Queue::assertPushedTimes(SendWelcomeEmailJob::class, 10);
    }
}
