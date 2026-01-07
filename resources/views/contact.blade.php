<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
</head>
<body>
    <h1>Contact</h1>
    <form action="{{ route('contact.submit') }}" method="post">
        @csrf
        <label>Name</label><br>
        <input type="text" name="name" placeholder="Name">
        <label>Email</label><br>
        <input type="email" name="email" placeholder="Email">
        <button type="submit">Submit</button>
    </form>
    <h2>Thông tin người dùng</h2>
    <p><strong>Tên: </strong>{{ $name }} </p>
    <p><strong>Email: </strong>{{ $email }} </p>
</body>
</html>