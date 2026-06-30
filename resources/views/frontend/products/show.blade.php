@extends('layouts.frontend')

@section('content')
@php
    $images = $product->images;
    $primary = $images->first();
    $primaryUrl = $primary
        ? (str_starts_with($primary->image_path, 'http') ? $primary->image_path : asset('storage/'.$primary->image_path))
        : asset('images/product-placeholder.svg');
@endphp

<section class="product-detail section-wrap">
    <div class="detail-gallery reveal">
        <div class="detail-primary" data-tilt>
            <img src="{{ $primaryUrl }}" alt="{{ $primary?->alt_text ?: $product->name }}">
        </div>
        <div class="detail-thumbs">
            @foreach($images as $image)
                @php $url = str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/'.$image->image_path); @endphp
                <img src="{{ $url }}" alt="{{ $image->alt_text ?: $product->name }}">
            @endforeach
        </div>
    </div>
    <div class="detail-copy reveal">
        <span>{{ $product->category->name }}</span>
        <h1>{{ $product->name }}</h1>
        <div class="detail-price">
            @if($product->compare_at_price_label)
                <del>{{ $product->compare_at_price_label }}</del>
            @endif
            <strong>{{ $product->price_label }}</strong>
        </div>
        <div class="{{ $product->is_in_stock ? 'stock-pill stock-ok' : 'stock-pill stock-out' }}">
            {{ $product->is_in_stock ? $product->stock_quantity.' in stock' : 'Out of stock' }}
        </div>
        <p>{{ $product->short_description }}</p>
        <div class="detail-description">{!! nl2br(e($product->description)) !!}</div>
        <div class="product-actions">
            @if($product->is_in_stock && ! $product->price_on_request && $product->price !== null)
                <form action="{{ route('cart.add', $product) }}" method="post">
                    @csrf
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}">
                    <button class="gold-button" type="submit">Add To Cart</button>
                </form>
            @else
                <button class="gold-button disabled" type="button" disabled>Out Of Stock</button>
            @endif
            <a class="outline-dark-button" href="{{ route('contact') }}?subject={{ urlencode('Enquiry for '.$product->name) }}">Request Appointment</a>
        </div>
    </div>
</section>

@if($relatedProducts->isNotEmpty())
<section class="section-wrap">
    <div class="section-heading reveal">
        <span>Related</span>
        <h2>You May Also Love</h2>
    </div>
    <div class="product-grid compact">
        @foreach($relatedProducts as $product)
            @include('frontend.partials-product-card', ['product' => $product])
        @endforeach
    </div>
</section>
@endif
@endsection
