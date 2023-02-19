<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Mail</title>
</head>

<body>
  <h2>Xin chào {{ $name }}</h2>
  <p>Để thay đổi mật khẩu mới vui lòng nhấn vào link bên dưới!</p>
  <a href="{{ 'http://localhost:3000/change-password/' . $id . '/' . $token }}">Nhấn vào đây để thay đổi mật khẩu!</a>
</body>

</html>
