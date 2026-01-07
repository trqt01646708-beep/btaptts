<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Shop')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .navbar { box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
        .footer { background-color: #212529; color: white; padding: 40px 0; margin-top: 50px; }
        .card { border: none; transition: transform 0.2s; }
        .card:hover { transform: translateY(-5px); }
    </style>
    @yield('css')
</head>
<body>

    @include('layouts.user_nav')

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
        @endif
        @yield('content')
    </div>

    <footer class="footer mt-auto py-3 bg-dark">
      <div class="container text-center">
        <span class="text-muted">© 2026 My Shop. All rights reserved.</span>
      </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('js')
</body>
</html>
