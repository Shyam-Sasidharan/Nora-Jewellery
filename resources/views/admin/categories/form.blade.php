@extends('layouts.admin')

@section('title', $category->exists ? 'Edit Category' : 'Add Category')

@section('content')
<form class="admin-form" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    @if($category->exists) @method('put') @endif
    <div class="form-grid">
        <label>Name
            <input name="name" value="{{ old('name', $category->name) }}" required>
        </label>
        <label>Sort Order
            <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0">
        </label>
    </div>
    <label>Description
        <textarea name="description" rows="5">{{ old('description', $category->description) }}</textarea>
    </label>
    <label>Category Image
        <input type="file" name="image" accept="image/*">
    </label>
    @if($category->image_path)
        @php $url = str_starts_with($category->image_path, 'http') ? $category->image_path : asset('storage/'.$category->image_path); @endphp
        <img class="admin-preview" src="{{ $url }}" alt="{{ $category->name }}">
    @endif
    <label class="check-row">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active ?? true))> Active
    </label>
    <div class="form-actions">
        <button class="admin-button" type="submit">Save Category</button>
        <a href="{{ route('admin.categories.index') }}">Cancel</a>
    </div>
</form>
@endsection
