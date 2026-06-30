<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') | Nora Jewellery</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-shell">
    <aside class="admin-sidebar">
        <a class="brand-mark admin-brand-logo" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('images/nora-jewels-logo.webp') }}" alt="Nora Jewels Admin">
            <span class="sr-only">Nora Admin</span>
        </a>
        <nav>
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.products.index') }}">Products</a>
            <a href="{{ route('admin.orders.index') }}">Orders</a>
            <a href="{{ route('admin.categories.index') }}">Categories</a>
            <a href="{{ route('admin.banners.index') }}">Banners</a>
            <a href="{{ route('admin.gallery.index') }}">Gallery</a>
            <a href="{{ route('admin.about.edit') }}">About</a>
            <a href="{{ route('admin.contact.edit') }}">Contact</a>
            <a href="{{ route('admin.delivery.edit') }}">Delivery</a>
            <a href="{{ route('home') }}" target="_blank">View Site</a>
        </nav>
        <form action="{{ route('admin.logout') }}" method="post">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </aside>
    <main class="admin-main">
        <header class="admin-topbar">
            <div>
                <span>CMS</span>
                <h1>@yield('title', 'Dashboard')</h1>
            </div>
            <strong>{{ auth()->user()->name }}</strong>
        </header>
        @if(session('success'))
            <div class="admin-alert success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="admin-alert error">{{ $errors->first() }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>
