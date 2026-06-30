@extends('layouts.frontend')

@section('content')
<section class="page-hero">
    <div class="reveal">
        <span>Categories</span>
        <h1>Every Collection, Beautifully Organized</h1>
    </div>
</section>

<section class="section-wrap">
    <div class="category-grid listing">
        @foreach($categories as $category)
            @php
                $image = $category->image_path
                    ? (str_starts_with($category->image_path, 'http') ? $category->image_path : asset('storage/'.$category->image_path))
                    : 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?auto=format&fit=crop&w=900&q=85';
            @endphp
            <a class="category-tile tall reveal" href="{{ route('products.category', $category) }}" style="background-image: linear-gradient(180deg, rgba(10,9,8,.08), rgba(10,9,8,.78)), url('{{ $image }}')">
                <span>{{ $category->products_count }} pieces</span>
                <strong>{{ $category->name }}</strong>
                <em>{{ $category->description }}</em>
            </a>
        @endforeach
    </div>
    {{ $categories->links() }}
</section>
@endsection
