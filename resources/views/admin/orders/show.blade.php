@extends('layouts.admin')

@section('title', $order->order_number)

@section('content')
<section class="admin-grid">
    <div class="admin-panel">
        <div class="panel-heading">
            <h2>Order Items</h2>
            <form action="{{ route('admin.orders.status', $order) }}" method="post" class="status-form">
                @csrf @method('patch')
                <select name="status">
                    @foreach(['new', 'processing', 'completed', 'cancelled'] as $status)
                        <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <button class="admin-button" type="submit">Update</button>
            </form>
        </div>
        <table>
            <thead><tr><th>Product</th><th>SKU</th><th>Price</th><th>Qty</th><th>Total</th></tr></thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->product_sku ?: '-' }}</td>
                        <td>₹{{ number_format((float) $item->unit_price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>₹{{ number_format((float) $item->line_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="admin-total">
            <div class="summary-total">
                <span>Subtotal</span>
                <strong>₹{{ number_format((float) $order->subtotal, 2) }}</strong>
            </div>
            <div class="summary-total">
                <span>Delivery</span>
                <strong>{{ (float) $order->delivery_charge > 0 ? '₹'.number_format((float) $order->delivery_charge, 2) : 'Free' }}</strong>
            </div>
            <div class="summary-total">
                <span>Total</span>
                <strong>₹{{ number_format((float) $order->total, 2) }}</strong>
            </div>
        </div>
    </div>
    <div class="admin-panel">
        <h2>Customer</h2>
        <p><strong>{{ $order->customer_name }}</strong></p>
        <p>{{ $order->customer_email }}</p>
        <p>{{ $order->customer_phone }}</p>
        <h2>Address</h2>
        <p>{{ $order->shipping_address }}</p>
        @if($order->notes)
            <h2>Notes</h2>
            <p>{{ $order->notes }}</p>
        @endif
    </div>
</section>
@endsection
