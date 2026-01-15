<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Queue & Mail</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            color: #333;
        }
        
        .navbar {
            background: white;
            padding: 15px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .navbar h1 {
            font-size: 20px;
            color: #667eea;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-card .number {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            margin: 10px 0;
        }
        
        .stat-card .label {
            color: #666;
            font-size: 14px;
        }
        
        .stat-card .emoji {
            font-size: 40px;
            margin-bottom: 10px;
        }
        
        .progress-bar {
            width: 100%;
            height: 8px;
            background: #eee;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 10px;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            transition: width 0.3s;
        }
        
        .actions {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }
        
        .btn-secondary:hover {
            background: #e0e0e0;
        }
        
        .btn-danger {
            background: #e74c3c;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c0392b;
        }
        
        .section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .section h2 {
            margin-bottom: 15px;
            font-size: 18px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table th {
            background: #f5f7fa;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #eee;
        }
        
        table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        
        table tr:hover {
            background: #f9f9f9;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }
        
        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }
        
        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .empty {
            text-align: center;
            color: #999;
            padding: 30px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>📊 Queue & Mail Dashboard</h1>
    </div>
    
    <div class="container">
        <!-- Statistics Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="emoji">📊</div>
                <div class="label">Tổng Công Việc</div>
                <div class="number">{{ $totalJobs }}</div>
            </div>
            
            <div class="stat-card">
                <div class="emoji">✅</div>
                <div class="label">Thành Công</div>
                <div class="number" style="color: #27ae60;">{{ $successCount }}</div>
            </div>
            
            <div class="stat-card">
                <div class="emoji">❌</div>
                <div class="label">Thất Bại</div>
                <div class="number" style="color: #e74c3c;">{{ $failedCount }}</div>
            </div>
            
            <div class="stat-card">
                <div class="emoji">⚙️</div>
                <div class="label">Đang Xử Lý</div>
                <div class="number" style="color: #f39c12;">{{ $processingCount }}</div>
            </div>
            
            <div class="stat-card">
                <div class="emoji">⏳</div>
                <div class="label">Đợi Xử Lý</div>
                <div class="number" style="color: #9b59b6;">{{ $pendingCount }}</div>
            </div>
        </div>
        
        <!-- Success Rate -->
        <div class="section">
            <h2>📈 Tỷ Lệ Thành Công</h2>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $successRate }}%"></div>
            </div>
            <p style="margin-top: 10px; text-align: right;">
                <strong>{{ $successRate }}%</strong>
            </p>
        </div>
        
        <!-- Actions -->
        <div class="actions">
            <a href="/register" class="btn btn-primary">📝 Đăng Ký Mới</a>
            <a href="/job-logs" class="btn btn-secondary">📋 Xem Tất Cả Logs</a>
            @if($failedCount > 0)
                <form method="POST" action="/dashboard/clear-failed" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Xóa tất cả công việc thất bại?')">
                        🗑️ Xóa Thất Bại
                    </button>
                </form>
            @endif
        </div>
        
        <!-- Recent Jobs -->
        @if($recentLogs->count() > 0)
        <div class="section">
            <h2>🕐 10 Công Việc Gần Đây</h2>
            <table>
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Trạng Thái</th>
                        <th>Thời Gian Tạo</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentLogs as $log)
                    <tr>
                        <td>{{ $log->email }}</td>
                        <td>
                            @switch($log->status)
                                @case('success')
                                    <span class="badge badge-success">✓ Thành công</span>
                                    @break
                                @case('failed')
                                    <span class="badge badge-danger">✗ Thất bại</span>
                                    @break
                                @case('processing')
                                    <span class="badge badge-warning">⚙ Đang xử lý</span>
                                    @break
                                @default
                                    <span class="badge badge-info">○ Chờ</span>
                            @endswitch
                        </td>
                        <td>{{ $log->created_at->diffForHumans() }}</td>
                        <td>
                            <form method="POST" action="/dashboard/delete-user/{{ urlencode($log->email) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;" onclick="return confirm('Xóa user {{ $log->email }} và tất cả logs liên quan?')">
                                    🗑️ Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="section">
            <div class="empty">Chưa có công việc nào</div>
        </div>
        @endif
        
        <!-- Failed Jobs -->
        @if($failedLogs->count() > 0)
        <div class="section">
            <h2>⚠️ Công Việc Thất Bại</h2>
            <table>
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Lỗi</th>
                        <th>Lần Thử</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($failedLogs as $log)
                    <tr>
                        <td>{{ $log->email }}</td>
                        <td>{{ substr($log->error_message ?? '', 0, 50) }}...</td>
                        <td>{{ $log->retry_count }}/{{ $log->max_retries }}</td>
                        <td>
                            <form method="POST" action="/dashboard/retry/{{ $log->id }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">
                                    🔄 Thử Lại
                                </button>
                            </form>
                            <form method="POST" action="/dashboard/delete-user/{{ urlencode($log->email) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px; background: #e74c3c;" onclick="return confirm('Xóa user {{ $log->email }} và tất cả logs liên quan?')">
                                    🗑️ Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</body>
</html>
