@extends('layouts.frontend')

@section('content')
<section class="page-hero">
    <div class="reveal">
        <span>{{ $category?->name ?? 'Collections' }}</span>
        <h1>{{ $category?->description ?: 'Fine Jewellery For The Moments That Stay' }}</h1>
    </div>
</section>

<section class="section-wrap collection-tools reveal">
    <form action="{{ $category ? route('products.category', $category) : route('products.index') }}" method="get" class="search-form">
        <input name="q" value="{{ request('q') }}" placeholder="Search jewellery">
        <button class="gold-button" type="submit">Search</button>
    </form>
    <div class="chip-row">
        <a class="{{ ! $category ? 'active' : '' }}" href="{{ route('products.index') }}">All</a>
        @foreach($categories as $item)
            <a class="{{ $category?->is($item) ? 'active' : '' }}" href="{{ route('products.category', $item) }}">{{ $item->name }}</a>
        @endforeach
    </div>
</section>

<section class="section-wrap">
    <div class="product-grid">
        @forelse($products as $product)
            @include('frontend.partials-product-card', ['product' => $product])
        @empty
            <div class="empty-state reveal">No pieces matched your search.</div>
        @endforelse
    </div>
    {{ $products->links() }}
</section>
@endsection
