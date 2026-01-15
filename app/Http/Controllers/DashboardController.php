<?php

namespace App\Http\Controllers;

use App\Models\EmailLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the dashboard.
     */
    public function index()
    {
        $totalJobs = EmailLog::count();
        $successCount = EmailLog::where('status', 'success')->count();
        $failedCount = EmailLog::where('status', 'failed')->count();
        $processingCount = EmailLog::where('status', 'processing')->count();
        $pendingCount = EmailLog::where('status', 'pending')->count();

        $successRate = $totalJobs > 0 
            ? round(($successCount / $totalJobs) * 100, 2) 
            : 0;

        $recentLogs = EmailLog::orderBy('created_at', 'desc')->limit(10)->get();
        $failedLogs = EmailLog::where('status', 'failed')->orderBy('created_at', 'desc')->limit(5)->get();

        return view('dashboard', [
            'totalJobs' => $totalJobs,
            'successCount' => $successCount,
            'failedCount' => $failedCount,
            'processingCount' => $processingCount,
            'pendingCount' => $pendingCount,
            'successRate' => $successRate,
            'recentLogs' => $recentLogs,
            'failedLogs' => $failedLogs,
        ]);
    }

    /**
     * Get job statistics (for API).
     */
    public function stats()
    {
        return response()->json([
            'total' => EmailLog::count(),
            'success' => EmailLog::where('status', 'success')->count(),
            'failed' => EmailLog::where('status', 'failed')->count(),
            'processing' => EmailLog::where('status', 'processing')->count(),
            'pending' => EmailLog::where('status', 'pending')->count(),
        ]);
    }

    /**
     * Clear failed jobs.
     */
    public function clearFailed()
    {
        EmailLog::where('status', 'failed')->delete();
        return redirect()->back()->with('success', 'Cleared failed jobs');
    }

    /**
     * Retry failed job.
     */
    public function retryFailed($id)
    {
        $log = EmailLog::find($id);
        
        if (!$log || $log->status !== 'failed') {
            return redirect()->back()->with('error', 'Invalid job log');
        }

        // EmailLog không lưu payload, giả định lấy lại từ bảng users hoặc không thể retry nếu thiếu data
        // Trong trường hợp này code retry sẽ cần chỉnh lại hoặc disable
        // Tạm thời disable retry cho EmailLog vì cấu trúc đơn giản
        
        return redirect()->back()->with('error', 'Retry functionality is not available for EmailLog');
    }

    /**
     * Delete user and related job logs.
     */
    public function deleteUser($email)
    {
        // Xóa tất cả job logs của user này
        EmailLog::where('email', $email)->delete();
        
        // Xóa user
        \App\Models\User::where('email', $email)->delete();

        return redirect()->back()->with('success', 'User and related logs deleted successfully');
    }
}
