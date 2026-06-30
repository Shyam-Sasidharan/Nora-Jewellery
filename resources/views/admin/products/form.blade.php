@extends('layouts.admin')

@section('title', $product->exists ? 'Edit Product' : 'Add Product')

@section('content')
<form class="admin-form" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    @if($product->exists) @method('put') @endif
    <div class="form-grid">
        <label>Name
            <input name="name" value="{{ old('name', $product->name) }}" required>
        </label>
        <label>Category
            <select name="category_id" required>
                <option value="">Select category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </label>
        <label>SKU
            <input name="sku" value="{{ old('sku', $product->sku) }}">
        </label>
        <label>Price
            <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price) }}">
        </label>
        <label>Old Price
            <input type="number" step="0.01" min="0" name="compare_at_price" value="{{ old('compare_at_price', $product->compare_at_price) }}">
        </label>
        <label>Stock Quantity
            <input type="number" min="0" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" required>
        </label>
    </div>
    <label>Short Description
        <input name="short_description" value="{{ old('short_description', $product->short_description) }}">
    </label>
    <label>Description
        <textarea name="description" rows="6">{{ old('description', $product->description) }}</textarea>
    </label>
    <div class="form-grid">
        <label>SEO Title
            <input name="meta_title" value="{{ old('meta_title', $product->meta_title) }}">
        </label>
        <label>SEO Description
            <input name="meta_description" value="{{ old('meta_description', $product->meta_description) }}">
        </label>
    </div>
    <label>Product Images
        <input type="file" name="images[]" accept="image/*" multiple>
    </label>
    @if($product->exists && $product->images->isNotEmpty())
        <div class="admin-image-grid">
            @foreach($product->images as $image)
                @php $url = str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/'.$image->image_path); @endphp
                <div>
                    <img src="{{ $url }}" alt="{{ $image->alt_text }}">
                    <div class="table-actions">
                        @if(! $image->is_primary)
                            <form action="{{ route('admin.product-images.primary', $image) }}" method="post">
                                @csrf @method('patch')
                                <button type="submit">Primary</button>
                            </form>
                        @else
                            <span>Primary</span>
                        @endif
                        <form action="{{ route('admin.product-images.destroy', $image) }}" method="post">
                            @csrf @method('delete')
                            <button type="submit">Remove</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <div class="check-grid">
        <label><input type="checkbox" name="price_on_request" value="1" @checked(old('price_on_request', $product->price_on_request))> Price on request</label>
        <label><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))> Featured</label>
        <label><input type="checkbox" name="is_new_arrival" value="1" @checked(old('is_new_arrival', $product->is_new_arrival))> New arrival</label>
        <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))> Active</label>
    </div>
    <div class="form-actions">
        <button class="admin-button" type="submit">Save Product</button>
        <a href="{{ route('admin.products.index') }}">Cancel</a>
    </div>
</form>
@endsection
