@extends('layouts.frontend')

@section('content')
<section class="page-hero">
    <div class="reveal">
        <span>Order Placed</span>
        <h1>Thank You</h1>
    </div>
</section>

<section class="section-wrap">
    <div class="empty-state reveal">
        <h2>{{ $order->order_number }}</h2>
        <p>Your order has been received. Our team will contact you shortly.</p>
        <a class="gold-button" href="{{ route('products.index') }}">Continue Shopping</a>
    </div>
</section>
@endsection
