@extends('layouts.frontend')

@section('content')
@php
    $hero = $banners->first();
    $heroImage = $hero?->image_path
        ? (str_starts_with($hero->image_path, 'http') ? $hero->image_path : asset('storage/'.$hero->image_path))
        : 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?auto=format&fit=crop&w=1800&q=85';
@endphp

<section class="hero-section luxury-hero" data-luxury-hero style="--hero-image: url('{{ $heroImage }}')">
    <div class="hero-aura"></div>
    <div class="gold-particles" aria-hidden="true"><i></i><i></i><i></i><i></i><i></i></div>
    <div class="hero-slider" data-slider>
        @forelse($banners as $banner)
            @php
                $image = $banner->image_path
                    ? (str_starts_with($banner->image_path, 'http') ? $banner->image_path : asset('storage/'.$banner->image_path))
                    : $heroImage;
            @endphp
            <article class="hero-slide {{ $loop->first ? 'is-active' : '' }}" style="background-image: linear-gradient(100deg, rgba(5, 4, 3, .92), rgba(12, 8, 4, .68) 42%, rgba(7, 5, 3, .12)), url('{{ $image }}')">
                <div class="hero-content reveal">
                    <span>{{ $banner->eyebrow ?: 'Nora Jewellery' }}</span>
                    <h1>{{ $banner->title }}</h1>
                    <p>{{ $banner->subtitle }}</p>
                    @if($banner->cta_label && $banner->cta_url)
                        <a class="gold-button" href="{{ $banner->cta_url }}">{{ $banner->cta_label }}</a>
                    @endif
                </div>
                <div class="hero-floating-card reveal" data-tilt>
                    <img src="{{ $image }}" alt="{{ $banner->title }}">
                    <strong>Hand Finished</strong>
                    <span>18K gold, diamond setting, bespoke styling</span>
                </div>
            </article>
        @empty
            <article class="hero-slide is-active">
                <div class="hero-content reveal">
                    <span>Nora Jewellery</span>
                    <h1>Luxury That Lives Beyond The Moment</h1>
                    <p>Hand-finished rings, necklaces, bridal sets, and bespoke pieces shaped with refined detail.</p>
                    <a class="gold-button" href="{{ route('products.index') }}">Explore Collections</a>
                </div>
            </article>
        @endforelse
    </div>
    <a class="scroll-indicator" href="#collections-preview" aria-label="Scroll to collections"><span></span></a>
</section>

<section class="section-wrap split-intro luxury-intro">
    <div class="section-kicker reveal">Signature Craft</div>
    <div class="split-copy reveal">
        <h2>{{ $about?->title ?? 'A House Of Quiet Radiance' }}</h2>
        <p>{{ $about?->content ?? 'Nora Jewellery creates premium pieces that feel intimate, sculptural, and enduring.' }}</p>
        <div class="luxury-metrics">
            <div><strong>18K</strong><span>Fine gold craft</span></div>
            <div><strong>900+</strong><span>Private designs</span></div>
            <div><strong>1:1</strong><span>Concierge styling</span></div>
        </div>
    </div>
</section>

<section class="section-wrap luxury-collections" id="collections-preview">
    <div class="section-heading reveal">
        <span>Curated Departments</span>
        <h2>Shop By Category</h2>
    </div>
    <div class="category-grid">
        @foreach($categories as $category)
            @php
                $categoryImage = $category->image_path
                    ? (str_starts_with($category->image_path, 'http') ? $category->image_path : asset('storage/'.$category->image_path))
                    : 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?auto=format&fit=crop&w=900&q=85';
            @endphp
            <a class="category-tile reveal" href="{{ route('products.category', $category) }}" style="background-image: linear-gradient(180deg, rgba(10,9,8,.05), rgba(10,9,8,.76)), url('{{ $categoryImage }}')">
                <span>{{ $category->products_count }} pieces</span>
                <strong>{{ $category->name }}</strong>
            </a>
        @endforeach
    </div>
</section>

<section class="section-wrap feature-showcase">
    <div class="section-heading reveal">
        <span>Featured Jewellery</span>
        <h2>Designed To Catch The Light</h2>
        <a href="{{ route('products.index') }}">View all</a>
    </div>
    <div class="product-grid">
        @foreach($featuredProducts as $product)
            @include('frontend.partials-product-card', ['product' => $product])
        @endforeach
    </div>
</section>

<section class="parallax-band">
    <div class="reveal">
        <span>Private Appointments</span>
        <h2>Bridal, bespoke, and celebration jewellery shaped around you.</h2>
        <a class="outline-button" href="{{ route('contact') }}">Book A Consultation</a>
    </div>
</section>

<section class="section-wrap editorial-band">
    <div class="editorial-copy reveal">
        <span>Nora Atelier</span>
        <h2>Jewellery styled like sculpture, finished like an heirloom.</h2>
        <p>Every Nora piece is presented with quiet drama: luminous surfaces, restrained silhouettes, and details that reward a closer look.</p>
    </div>
    <div class="editorial-stacks reveal">
        @foreach($featuredProducts->take(3) as $product)
            @php
                $image = $product->images->first();
                $url = $image ? (str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/'.$image->image_path)) : asset('images/product-placeholder.svg');
            @endphp
            <a href="{{ route('products.show', $product) }}"><img src="{{ $url }}" alt="{{ $product->name }}" loading="lazy"></a>
        @endforeach
    </div>
</section>

<section class="section-wrap">
    <div class="section-heading reveal">
        <span>New Arrivals</span>
        <h2>Fresh From The Atelier</h2>
    </div>
    <div class="product-grid compact">
        @foreach($newArrivals as $product)
            @include('frontend.partials-product-card', ['product' => $product])
        @endforeach
    </div>
</section>

<section class="section-wrap trust-section">
    <article class="reveal">
        <span>01</span>
        <h3>Certified Finish</h3>
        <p>Premium materials, careful setting, and polish standards built for long wear.</p>
    </article>
    <article class="reveal">
        <span>02</span>
        <h3>Private Concierge</h3>
        <p>Appointments for bridal, gifting, custom pieces, and collection guidance.</p>
    </article>
    <article class="reveal">
        <span>03</span>
        <h3>Luxury Delivery</h3>
        <p>Secure order handling with delivery settings managed directly from CMS.</p>
    </article>
</section>

<section class="section-wrap testimonial-section">
    <div class="reveal">
        <span>Client Notes</span>
        <h2>“The piece felt personal from the first appointment. Nora’s finish is genuinely premium.”</h2>
        <p>Bridal consultation client</p>
    </div>
</section>

<section class="section-wrap gallery-strip instagram-gallery">
    @foreach($galleryImages as $image)
        @php $url = str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/'.$image->image_path); @endphp
        <img class="reveal" src="{{ $url }}" alt="{{ $image->alt_text ?: $image->title ?: 'Nora Jewellery gallery' }}" loading="lazy">
    @endforeach
</section>

<section class="newsletter-band">
    <div class="reveal">
        <span>Nora Private List</span>
        <h2>Receive first looks, bridal edits, and appointment openings.</h2>
        <a class="gold-button" href="{{ route('contact') }}">Join The Preview</a>
    </div>
</section>
@endsection
