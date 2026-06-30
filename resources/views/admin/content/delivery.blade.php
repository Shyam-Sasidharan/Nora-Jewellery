@extends('layouts.admin')

@section('title', 'Delivery Settings')

@section('content')
@php
    $data = $delivery->data ?? [];
    $isFreeDelivery = old('data.is_free_delivery', $data['is_free_delivery'] ?? true);
@endphp

<form class="admin-form" action="{{ route('admin.delivery.update') }}" method="post">
    @csrf
    @method('put')

    <label class="check-row">
        <input type="checkbox" name="data[is_free_delivery]" value="1" @checked($isFreeDelivery)>
        Free delivery
    </label>

    <label>Delivery Charge
        <input type="number" step="0.01" min="0" name="data[delivery_charge]" value="{{ old('data.delivery_charge', $data['delivery_charge'] ?? 0) }}">
    </label>

    <p class="form-help">If free delivery is enabled, checkout will show delivery as Free. If disabled, the delivery charge will be added to the order total.</p>

    <button class="admin-button" type="submit">Save Delivery Settings</button>
</form>
@endsection
