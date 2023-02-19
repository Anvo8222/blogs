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
  <p>Bạn đã đăng ký tài khoản thành công!</p>
  <p>Để sử dụng tài khoản, vui lòng nhấn vào link bên dưới để kích hoạt tài khoản!</p>
  <a href="{{ 'http://localhost:3000/active/' . $id . '/' . $token }}">Nhấn vào đây để kích hoạt tài khoản!</a>
</body>

</html>
