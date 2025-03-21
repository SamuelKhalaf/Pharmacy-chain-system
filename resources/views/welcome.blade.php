<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
</head>
<body class="bg-light">
<div class="container text-center mt-5">
    <h1 class="text-primary">Welcome to Our Application</h1>
    <p class="lead">Please login to access the dashboard.</p>
    <a href="{{ route('admin.login') }}" class="btn btn-success">Login</a>
</div>
</body>
</html>
