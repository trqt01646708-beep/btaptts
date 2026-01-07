<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>@yield('title','Admin')</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f6fa;
            color: #333;
        }

        .header {
            background: #4f46e5;
            color: #fff;
            padding: 15px 20px;
        }

        .wrapper {
            display: flex;
            min-height: calc(100vh - 120px);
        }

        .sidebar {
            width: 220px;
            background: #ffffff;
            border-right: 1px solid #ddd;
            padding: 15px;
        }

        .sidebar a {
            display: block;
            padding: 10px;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        .sidebar a:hover {
            background: #eef2ff;
            color: #4f46e5;
        }

        .content {
            flex: 1;
            padding: 20px;
            background: #f9fafb;
        }

        .footer {
            background: #ffffff;
            border-top: 1px solid #ddd;
            text-align: center;
            padding: 10px;
            color: #777;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        table th {
            background: #eef2ff;
            padding: 10px;
            border: 1px solid #ddd;
        }

        table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        button {
            background: #4f46e5;
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #4338ca;
        }

        input, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        h3 {
            margin-top: 0;
        }
    </style>
</head>
<body>

@include('layouts.header')

<div class="wrapper">
    @include('layouts.sidebar')

    <div class="content">
        @yield('content')
    </div>
</div>

@include('layouts.footer')

</body>
</html>
