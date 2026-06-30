@extends('layouts.frontend')

@section('content')
<section class="page-hero">
    <div class="reveal">
        <span>Cart</span>
        <h1>Your Jewellery Selection</h1>
    </div>
</section>

<section class="section-wrap cart-wrap">
    @if(session('success'))
        <div class="notice success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="notice error">{{ session('error') }}</div>
    @endif

    @if($cartItems->isEmpty())
        <div class="empty-state reveal">
            Your cart is empty.
            <div><a class="gold-button" href="{{ route('products.index') }}">Shop Collections</a></div>
        </div>
    @else
        <div class="cart-table reveal">
            @foreach($cartItems as $item)
                @php
                    $product = $item['product'];
                    $image = $product->images->first();
                    $imageUrl = $image ? (str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/'.$image->image_path)) : asset('images/product-placeholder.svg');
                @endphp
                <article class="cart-row">
                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}">
                    <div>
                        <h2>{{ $product->name }}</h2>
                        <span>{{ $product->price_label }}</span>
                        <small>{{ $product->stock_quantity }} in stock</small>
                    </div>
                    <form action="{{ route('cart.update', $product) }}" method="post">
                        @csrf @method('patch')
                        <input type="number" name="quantity" min="1" max="{{ $product->stock_quantity }}" value="{{ $item['quantity'] }}">
                        <button type="submit">Update</button>
                    </form>
                    <strong>₹{{ number_format($item['line_total'], 2) }}</strong>
                    <form action="{{ route('cart.remove', $product) }}" method="post">
                        @csrf @method('delete')
                        <button type="submit">Remove</button>
                    </form>
                </article>
            @endforeach
        </div>
        <div class="cart-summary reveal">
            <span>Subtotal</span>
            <strong>₹{{ number_format($cartItems->sum('line_total'), 2) }}</strong>
            <a class="gold-button" href="{{ route('checkout') }}">Checkout</a>
        </div>
    @endif
</section>
@endsection
