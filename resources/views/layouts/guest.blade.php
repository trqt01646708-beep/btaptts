<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <!-- AdminLTE 4 CSS (Bootstrap 5) -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta2/dist/css/adminlte.min.css">
    </head>
    <body class="login-page bg-light">
        <div class="login-box">
            <div class="card card-outline card-primary shadow">
                <div class="card-header text-center">
                    <a href="/" class="h1"><b>Hệ</b> THỐNG</a>
                </div>
                <div class="card-body">
                    {{ $slot }}
                </div>
            </div>
        </div>
        <!-- AdminLTE 4 JS -->
        <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta2/dist/js/adminlte.min.js"></script>
    </body>
</html>
