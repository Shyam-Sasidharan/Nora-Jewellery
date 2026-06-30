@extends('layouts.admin')

@section('title', 'Gallery')

@section('content')
<div class="panel-heading">
    <h2>Gallery Images</h2>
    <a class="admin-button" href="{{ route('admin.gallery.create') }}">Add Image</a>
</div>
<div class="admin-image-grid">
    @foreach($images as $image)
        @php $url = str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/'.$image->image_path); @endphp
        <article>
            <img src="{{ $url }}" alt="{{ $image->alt_text ?: $image->title }}">
            <strong>{{ $image->title ?: 'Gallery image' }}</strong>
            <span>{{ $image->is_active ? 'Active' : 'Inactive' }}</span>
            <div class="table-actions">
                <a href="{{ route('admin.gallery.edit', $image) }}">Edit</a>
                <form action="{{ route('admin.gallery.destroy', $image) }}" method="post" onsubmit="return confirm('Delete this image?')">
                    @csrf @method('delete')
                    <button type="submit">Delete</button>
                </form>
            </div>
        </article>
    @endforeach
</div>
{{ $images->links() }}
@endsection
