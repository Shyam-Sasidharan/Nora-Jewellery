@extends('layouts.frontend')

@section('content')
@php
    $image = $about?->image_path
        ? (str_starts_with($about->image_path, 'http') ? $about->image_path : asset('storage/'.$about->image_path))
        : 'https://images.unsplash.com/photo-1617038260897-41a1f14a8ca0?auto=format&fit=crop&w=1400&q=85';
    $data = $about?->data ?? [];
@endphp

<section class="page-hero warm">
    <div class="reveal">
        <span>About Nora</span>
        <h1>{{ $about?->title ?? 'Jewellery With Poise, Precision, And Presence' }}</h1>
    </div>
</section>

<section class="section-wrap about-panel">
    <div class="about-image reveal" data-tilt>
        <img src="{{ $image }}" alt="Nora Jewellery craftsmanship">
    </div>
    <div class="about-text reveal">
        <p>{{ $about?->content ?? 'Nora Jewellery is a premium fine jewellery house creating refined designs for everyday elegance, ceremony, and heirloom moments.' }}</p>
        <div class="metric-row">
            <div><strong>{{ $data['heritage'] ?? '18K' }}</strong><span>Gold-focused craft</span></div>
            <div><strong>{{ $data['craft'] ?? 'Hand' }}</strong><span>Finished details</span></div>
            <div><strong>{{ $data['promise'] ?? 'Bespoke' }}</strong><span>Private consultations</span></div>
        </div>
    </div>
</section>

<section class="section-wrap values-grid">
    <article class="reveal">
        <span>01</span>
        <h2>Material Integrity</h2>
        <p>Every collection is planned around graceful wear, balanced proportions, and lasting finish.</p>
    </article>
    <article class="reveal">
        <span>02</span>
        <h2>Modern Heirlooms</h2>
        <p>Our silhouettes are contemporary without losing the softness and permanence of traditional jewellery.</p>
    </article>
    <article class="reveal">
        <span>03</span>
        <h2>Personal Service</h2>
        <p>From bridal styling to custom pieces, appointments are guided, private, and detail rich.</p>
    </article>
</section>
@endsection
