<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $metaTitle ?? 'Nora Jewellery' }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'Premium luxury jewellery by Nora Jewellery.' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="site-shell">
    <header class="site-header">
        <div class="nav-glass">
            <a class="brand-mark" href="{{ route('home') }}">
                <img src="{{ asset('images/nora-jewels-logo.webp') }}" alt="Nora Jewels">
                <span class="sr-only">Nora Jewellery</span>
            </a>
        <button class="nav-toggle" type="button" data-menu-toggle aria-label="Open menu">Menu</button>
            <nav class="site-nav" data-menu>
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('about') }}">About</a>
                <a href="{{ route('categories') }}">Categories</a>
                <a href="{{ route('products.index') }}">Collections</a>
                <a href="{{ route('gallery') }}">Gallery</a>
                <a href="{{ route('contact') }}">Contact</a>
            </nav>
            <div class="nav-actions">
                <a class="nav-icon" href="{{ route('products.index') }}" aria-label="Search products">⌕</a>
                <a class="nav-icon" href="{{ route('cart.index') }}" aria-label="Open cart">Bag</a>
                <a class="nav-cta" href="{{ route('contact') }}">Appointment</a>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="footer-main">
            <a class="brand-mark footer-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/nora-jewels-logo.webp') }}" alt="Nora Jewels">
                <span class="sr-only">Nora Jewellery</span>
            </a>
            <p>Fine jewellery crafted for modern heirlooms, private appointments, and luminous celebrations.</p>
            <div class="social-row">
                <a href="{{ route('gallery') }}">Instagram</a>
                <a href="{{ route('contact') }}">Concierge</a>
                <a href="{{ route('products.index') }}">Lookbook</a>
            </div>
        </div>
        <div class="footer-links">
            <strong>Explore</strong>
            <a href="{{ route('products.index') }}">Collections</a>
            <a href="{{ route('gallery') }}">Gallery</a>
            <a href="{{ route('cart.index') }}">Cart</a>
            <a href="{{ route('contact') }}">Book Appointment</a>
            <a href="{{ route('admin.login') }}">Admin</a>
        </div>
        <div class="footer-newsletter">
            <strong>Private Preview</strong>
            <p>Receive collection notes, bridal appointments, and first looks from Nora.</p>
            <div><span>norajewels0523@gmail.com</span></div>
        </div>
    </footer>
</body>
</html>
