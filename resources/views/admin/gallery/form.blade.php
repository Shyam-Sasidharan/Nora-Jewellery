@extends('layouts.admin')

@section('title', $image->exists ? 'Edit Gallery Image' : 'Add Gallery Image')

@section('content')
<form class="admin-form" action="{{ $image->exists ? route('admin.gallery.update', $image) : route('admin.gallery.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    @if($image->exists) @method('put') @endif
    <div class="form-grid">
        <label>Title <input name="title" value="{{ old('title', $image->title) }}"></label>
        <label>Alt Text <input name="alt_text" value="{{ old('alt_text', $image->alt_text) }}"></label>
        <label>Sort Order <input type="number" name="sort_order" value="{{ old('sort_order', $image->sort_order ?? 0) }}" min="0"></label>
    </div>
    <label>Image <input type="file" name="image" accept="image/*" @required(! $image->exists)></label>
    @if($image->image_path)
        @php $url = str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/'.$image->image_path); @endphp
        <img class="admin-preview" src="{{ $url }}" alt="{{ $image->title }}">
    @endif
    <label class="check-row"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $image->is_active ?? true))> Active</label>
    <div class="form-actions">
        <button class="admin-button" type="submit">Save Image</button>
        <a href="{{ route('admin.gallery.index') }}">Cancel</a>
    </div>
</form>
@endsection
