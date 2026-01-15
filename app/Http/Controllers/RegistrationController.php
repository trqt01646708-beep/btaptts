<?php

namespace App\Http\Controllers;

use App\Jobs\SendWelcomeEmailJob;
use App\Models\User;
use App\Models\EmailLog;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showForm()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Tạo user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // Tạo log entry đầu tiên (pending state)
        EmailLog::create([
            'email' => $user->email,
            'subject' => 'Welcome to our platform',
            'status' => 'pending',
        ]);

        // Push job gửi email vào queue
        SendWelcomeEmailJob::dispatch($user->email, $user->name);

        return redirect('/dashboard')->with('success', 'Đăng ký thành công! Email chào mừng đã được gửi.');
    }

    /**
     * Show job logs.
     */
    public function showLogs()
    {
        $logs = EmailLog::orderBy('created_at', 'desc')->paginate(20);
        return view('job-logs', ['logs' => $logs]);
    }
}
