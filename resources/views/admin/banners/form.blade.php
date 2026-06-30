@extends('layouts.admin')

@section('title', $banner->exists ? 'Edit Banner' : 'Add Banner')

@section('content')
<form class="admin-form" action="{{ $banner->exists ? route('admin.banners.update', $banner) : route('admin.banners.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    @if($banner->exists) @method('put') @endif
    <div class="form-grid">
        <label>Eyebrow <input name="eyebrow" value="{{ old('eyebrow', $banner->eyebrow) }}"></label>
        <label>Title <input name="title" value="{{ old('title', $banner->title) }}" required></label>
    </div>
    <label>Subtitle <input name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}"></label>
    <div class="form-grid">
        <label>CTA Label <input name="cta_label" value="{{ old('cta_label', $banner->cta_label) }}"></label>
        <label>CTA URL <input name="cta_url" value="{{ old('cta_url', $banner->cta_url) }}"></label>
        <label>Sort Order <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}" min="0"></label>
    </div>
    <label>Banner Image <input type="file" name="image" accept="image/*"></label>
    @if($banner->image_path)
        @php $url = str_starts_with($banner->image_path, 'http') ? $banner->image_path : asset('storage/'.$banner->image_path); @endphp
        <img class="admin-preview wide" src="{{ $url }}" alt="{{ $banner->title }}">
    @endif
    <label class="check-row"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $banner->is_active ?? true))> Active</label>
    <div class="form-actions">
        <button class="admin-button" type="submit">Save Banner</button>
        <a href="{{ route('admin.banners.index') }}">Cancel</a>
    </div>
</form>
@endsection
