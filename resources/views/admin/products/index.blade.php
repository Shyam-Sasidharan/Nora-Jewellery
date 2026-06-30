@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<div class="panel-heading">
    <h2>Jewellery Products</h2>
    <a class="admin-button" href="{{ route('admin.products.create') }}">Add Product</a>
</div>
<div class="admin-panel">
    <table>
        <thead><tr><th>Name</th><th>Category</th><th>Price</th><th>Old Price</th><th>Stock</th><th>Flags</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category?->name }}</td>
                    <td>{{ $product->price_label }}</td>
                    <td>{{ $product->compare_at_price_label ?: '-' }}</td>
                    <td>{{ $product->stock_quantity }} {{ $product->is_in_stock ? 'in stock' : 'out of stock' }}</td>
                    <td>{{ $product->is_featured ? 'Featured ' : '' }}{{ $product->is_new_arrival ? 'New' : '' }}</td>
                    <td>{{ $product->is_active ? 'Active' : 'Inactive' }}</td>
                    <td class="table-actions">
                        <a href="{{ route('admin.products.edit', $product) }}">Edit</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="post" onsubmit="return confirm('Delete this product?')">
                            @csrf @method('delete')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $products->links() }}
</div>
@endsection
