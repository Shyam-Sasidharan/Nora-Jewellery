@extends('layouts.frontend')

@section('content')
<section class="page-hero warm">
    <div class="reveal">
        <span>Gallery</span>
        <h1>Light, Detail, And Occasion</h1>
    </div>
</section>

<section class="section-wrap masonry-gallery">
    @foreach($images as $image)
        @php $url = str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/'.$image->image_path); @endphp
        <figure class="reveal">
            <img src="{{ $url }}" alt="{{ $image->alt_text ?: $image->title ?: 'Nora Jewellery gallery' }}">
            @if($image->title)
                <figcaption>{{ $image->title }}</figcaption>
            @endif
        </figure>
    @endforeach
    {{ $images->links() }}
</section>
@endsection
