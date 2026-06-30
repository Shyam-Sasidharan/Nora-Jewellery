@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<section class="stat-grid">
    @foreach($stats as $label => $value)
        <article>
            <span>{{ str_replace('_', ' ', $label) }}</span>
            <strong>{{ $value }}</strong>
        </article>
    @endforeach
</section>

<section class="admin-grid">
    <div class="admin-panel">
        <div class="panel-heading">
            <h2>Latest Products</h2>
            <a href="{{ route('admin.products.create') }}">Add product</a>
        </div>
        <table>
            <thead><tr><th>Name</th><th>Category</th><th>Status</th></tr></thead>
            <tbody>
                @foreach($latestProducts as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category?->name }}</td>
                        <td>{{ $product->is_active ? 'Active' : 'Inactive' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="admin-panel">
        <div class="panel-heading">
            <h2>Latest Orders</h2>
            <a href="{{ route('admin.orders.index') }}">View orders</a>
        </div>
        @foreach($latestOrders as $order)
            <div class="message-row">
                <strong><a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a></strong>
                <span>{{ $order->customer_name }} · ₹{{ number_format((float) $order->total, 2) }} · {{ ucfirst($order->status) }}</span>
            </div>
        @endforeach
    </div>
    <div class="admin-panel">
        <div class="panel-heading">
            <h2>Recent Messages</h2>
            <a href="{{ route('admin.contact.edit') }}">Open inbox</a>
        </div>
        @foreach($messages as $message)
            <div class="message-row">
                <strong>{{ $message->name }}</strong>
                <span>{{ $message->subject ?: $message->email }}</span>
            </div>
        @endforeach
    </div>
</section>
@endsection
