@php
    $primary = $product->images->first();
    $imageUrl = $primary
        ? (str_starts_with($primary->image_path, 'http') ? $primary->image_path : asset('storage/'.$primary->image_path))
        : asset('images/product-placeholder.svg');
@endphp

<article class="product-card reveal" data-tilt>
    <a href="{{ route('products.show', $product) }}" class="product-media">
        <img src="{{ $imageUrl }}" alt="{{ $primary?->alt_text ?: $product->name }}" loading="lazy">
        @if($product->is_new_arrival)
            <span class="product-badge">New</span>
        @elseif($product->is_featured)
            <span class="product-badge">Featured</span>
        @endif
        <span class="product-shine"></span>
        <span class="product-actions-hover">
            <em>♡</em>
            <strong>Quick View</strong>
        </span>
    </a>
    <div class="product-copy">
        <span>{{ $product->category?->name }}</span>
        <h3><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h3>
        <p class="price-line">
            @if($product->compare_at_price_label)
                <del>{{ $product->compare_at_price_label }}</del>
            @endif
            <strong>{{ $product->price_label }}</strong>
        </p>
        <small class="{{ $product->is_in_stock ? 'stock-ok' : 'stock-out' }}">
            {{ $product->is_in_stock ? $product->stock_quantity.' in stock' : 'Out of stock' }}
        </small>
    </div>
</article>
