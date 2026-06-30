@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="panel-heading">
    <h2>Checkout Orders</h2>
</div>
<div class="admin-panel">
    <table>
        <thead><tr><th>Order</th><th>Customer</th><th>Items</th><th>Total</th><th>Status</th><th>Date</th><th></th></tr></thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->customer_name }}<br><small>{{ $order->customer_phone ?: $order->customer_email }}</small></td>
                    <td>{{ $order->items_count }}</td>
                    <td>₹{{ number_format((float) $order->total, 2) }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                    <td><a href="{{ route('admin.orders.show', $order) }}">View</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $orders->links() }}
</div>
@endsection
