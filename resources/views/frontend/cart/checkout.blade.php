@extends('layouts.frontend')

@section('content')
<section class="page-hero warm">
    <div class="reveal">
        <span>Checkout</span>
        <h1>Complete Your Order</h1>
    </div>
</section>

<section class="section-wrap checkout-grid">
    <form class="contact-form reveal" action="{{ route('checkout.place') }}" method="post">
        @csrf
        @if($errors->any())
            <div class="notice error">{{ $errors->first() }}</div>
        @endif
        <input name="customer_name" value="{{ old('customer_name') }}" placeholder="Full name" required>
        <input type="email" name="customer_email" value="{{ old('customer_email') }}" placeholder="Email" required>
        <input name="customer_phone" value="{{ old('customer_phone') }}" placeholder="Phone">
        <textarea name="shipping_address" rows="5" placeholder="Delivery address" required>{{ old('shipping_address') }}</textarea>
        <textarea name="notes" rows="4" placeholder="Order notes">{{ old('notes') }}</textarea>
        <button class="gold-button" type="submit">Place Order</button>
    </form>
    <aside class="cart-summary checkout-summary reveal">
        <h2>Order Summary</h2>
        @foreach($cartItems as $item)
            <div class="summary-line">
                <span>{{ $item['product']->name }} × {{ $item['quantity'] }}</span>
                <strong>₹{{ number_format($item['line_total'], 2) }}</strong>
            </div>
        @endforeach
        <div class="summary-line">
            <span>Subtotal</span>
            <strong>₹{{ number_format($subtotal, 2) }}</strong>
        </div>
        <div class="summary-line">
            <span>Delivery</span>
            <strong>{{ $deliveryCharge > 0 ? '₹'.number_format($deliveryCharge, 2) : 'Free' }}</strong>
        </div>
        <div class="summary-total">
            <span>Total</span>
            <strong>₹{{ number_format($total, 2) }}</strong>
        </div>
    </aside>
</section>
@endsection
