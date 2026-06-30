@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="panel-heading">
    <h2>Product Categories</h2>
    <a class="admin-button" href="{{ route('admin.categories.create') }}">Add Category</a>
</div>
<div class="admin-panel">
    <table>
        <thead><tr><th>Name</th><th>Products</th><th>Sort</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->products_count }}</td>
                    <td>{{ $category->sort_order }}</td>
                    <td>{{ $category->is_active ? 'Active' : 'Inactive' }}</td>
                    <td class="table-actions">
                        <a href="{{ route('admin.categories.edit', $category) }}">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="post" onsubmit="return confirm('Delete this category?')">
                            @csrf @method('delete')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $categories->links() }}
</div>
@endsection
