@extends('layouts.admin')

@section('title', 'Manage Orders')

@section('content')
<div class="page-header">
    <div class="flex-between">
        <div>
            <h1 class="page-title">Orders Management</h1>
            <p style="color: var(--color-text-light); font-size: 0.875rem;">View and handle customer orders.</p>
        </div>
        <div class="flex-between" style="gap: 1rem;">
             <form action="{{ route('orders.index') }}" method="GET" style="display: flex; gap: 0.5rem;">
                <select name="status" class="form-control" style="width: 150px;" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
             </form>
        </div>
    </div>
</div>

<div class="card">
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td style="font-weight: 700;">#{{ $order->order_number }}</td>
                    <td>
                        <div style="font-weight: 600;">{{ $order->customer_name }}</div>
                        <div style="font-size: 0.75rem; color: var(--color-text-light);">{{ $order->customer_email }}</div>
                    </td>
                    <td style="color: var(--color-accent); font-weight: 700;">₹{{ number_format($order->total) }}</td>
                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                    <td>
                        <span class="status-badge {{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.75rem;">View Details</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 3rem; color: var(--color-text-light);">
                        No orders found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 1.5rem;">
        {{ $orders->links() }}
    </div>
</div>
@endsection
