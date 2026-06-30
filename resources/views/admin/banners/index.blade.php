@extends('layouts.admin')

@section('title', 'Banners')

@section('content')
<div class="panel-heading">
    <h2>Home Sliders</h2>
    <a class="admin-button" href="{{ route('admin.banners.create') }}">Add Banner</a>
</div>
<div class="admin-panel">
    <table>
        <thead><tr><th>Title</th><th>Sort</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @foreach($banners as $banner)
                <tr>
                    <td>{{ $banner->title }}</td>
                    <td>{{ $banner->sort_order }}</td>
                    <td>{{ $banner->is_active ? 'Active' : 'Inactive' }}</td>
                    <td class="table-actions">
                        <a href="{{ route('admin.banners.edit', $banner) }}">Edit</a>
                        <form action="{{ route('admin.banners.destroy', $banner) }}" method="post" onsubmit="return confirm('Delete this banner?')">
                            @csrf @method('delete')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $banners->links() }}
</div>
@endsection
