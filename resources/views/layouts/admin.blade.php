<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Include Bootstrap or other CSS frameworks -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>

    <!-- Include JavaScript dependencies -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
