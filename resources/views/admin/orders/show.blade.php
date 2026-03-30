@extends('layouts.admin')

@section('title', 'Order Details #' . $order->order_number)

@section('content')
<div class="page-header">
    <div class="flex-between">
        <div>
            <h1 class="page-title">Order #{{ $order->order_number }}</h1>
            <p style="color: var(--color-text-light); font-size: 0.875rem;">Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
        </div>
        <div class="flex-between" style="gap: 1rem;">
             <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back to Orders</a>
        </div>
    </div>
</div>

<div class="grid-2-1">
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="card">
            <h3 style="font-size: 1.125rem; margin-bottom: 1.5rem;">Order Items</h3>
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th style="text-align: right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td style="font-weight: 600;">{{ $item['name'] }}</td>
                            <td>₹{{ number_format($item['price']) }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td style="text-align: right; color: var(--color-accent); font-weight: 700;">₹{{ number_format($item['price'] * $item['quantity']) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right; padding: 1.25rem 1rem; font-weight: 800;">Grand Total</td>
                            <td style="text-align: right; padding: 1.25rem 1rem; color: var(--color-accent); font-weight: 800; font-size: 1.25rem;">₹{{ number_format($order->total) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="card">
            <h3 style="font-size: 1.125rem; margin-bottom: 1.5rem;">Shipping Information</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; font-size: 0.875rem;">
                <div>
                    <label class="form-label">Customer Name</label>
                    <div style="color: var(--color-text); font-weight: 600;">{{ $order->customer_name }}</div>
                </div>
                <div>
                    <label class="form-label">Phone</label>
                    <div style="color: var(--color-text); font-weight: 600;">{{ $order->customer_phone }}</div>
                </div>
                <div style="grid-column: span 2;">
                    <label class="form-label">Address</label>
                    <div style="color: var(--color-text); font-weight: 600;">{{ $order->shipping_address }}, {{ $order->city }}, {{ $order->state }}, {{ $order->pincode }}</div>
                </div>
            </div>
        </div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="card">
            <h3 style="font-size: 1.125rem; margin-bottom: 1.5rem;">Manage Order</h3>
            <form action="{{ route('orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div style="margin-bottom: 1.5rem;">
                    <label class="form-label">Update Status</label>
                    <select name="status" class="form-control">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">Update Status</button>
            </form>
        </div>

        <div class="card">
            <h3 style="font-size: 1.125rem; margin-bottom: 1.5rem;">Payment Info</h3>
             <div style="font-size: 0.875rem;">
                <div class="flex-between" style="margin-bottom: 0.5rem;">
                    <span style="color: var(--color-text-light);">Method</span>
                    <span style="font-weight: 600; text-transform: uppercase;">{{ $order->payment_method }}</span>
                </div>
                <div class="flex-between" style="margin-bottom: 0.5rem;">
                    <span style="color: var(--color-text-light);">Payment ID</span>
                    <span style="font-weight: 600;">{{ $order->payment_id ?: 'N/A' }}</span>
                </div>
                <div class="flex-between">
                    <span style="color: var(--color-text-light);">Date</span>
                    <span style="font-weight: 600;">{{ $order->created_at->format('M d, Y') }}</span>
                </div>
             </div>
        </div>
    </div>
</div>
@endsection
