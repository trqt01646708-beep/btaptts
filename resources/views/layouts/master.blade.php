<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quản lý bài viết')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .container {
            display: flex;
            flex: 1;
        }
        .sidebar {
            width: 250px;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .sidebar ul {
            list-style: none;
        }
        .sidebar ul li {
            margin-bottom: 10px;
        }
        .sidebar ul li a {
            text-decoration: none;
            color: #333;
            display: block;
            padding: 10px;
            border-radius: 5px;
        }
        .sidebar ul li a:hover {
            background-color: #16dbc4;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 15px;
        }
    </style>
    @yield('styles')
</head>
<body>
    <header>
        <h1>Hệ thống quản lý bài viết</h1>
    </header>

    <div class="container">
        <aside class="sidebar">
            <h3>Menu</h3>
            <ul>
                <li><a href="{{ route('posts.index') }}">Quản lý bài viết</a></li>
                <li><a href="{{ route('categories.index') }}">Quản lý danh mục</a></li>
            </ul>
        </aside>

        <main class="content">
            @yield('content')
        </main>
    </div>

    <footer>
        <p>Hệ thống quản lý bài viết</p>
    </footer>

    @yield('scripts')
</body>
</html>
