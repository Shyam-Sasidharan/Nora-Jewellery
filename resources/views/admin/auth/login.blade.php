<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | Nora Jewellery</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="login-shell">
    <form class="login-card" action="{{ route('admin.login.submit') }}" method="post">
        @csrf
        <div class="brand-mark login-logo">
            <img src="{{ asset('images/nora-jewels-logo.webp') }}" alt="Nora Jewels Admin">
            <span class="sr-only">Nora Admin</span>
        </div>
        <h1>Secure CMS Login</h1>
        @if(session('error'))
            <div class="admin-alert error">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="admin-alert error">{{ $errors->first() }}</div>
        @endif
        <label>Email
            <input type="email" name="email" value="{{ old('email') }}" required autofocus>
        </label>
        <label>Password
            <input type="password" name="password" required>
        </label>
        <label class="check-row">
            <input type="checkbox" name="remember" value="1"> Remember me
        </label>
        <button class="gold-button" type="submit">Login</button>
    </form>
</body>
</html>
