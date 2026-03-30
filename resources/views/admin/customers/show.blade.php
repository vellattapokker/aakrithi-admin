@extends('layouts.admin')

@section('title', 'Customer Details: ' . $customer->name)

@section('content')
<div class="page-header">
    <div class="flex-between">
        <div>
            <h1 class="page-title">{{ $customer->name }}</h1>
            <p style="color: var(--color-text-light); font-size: 0.875rem;">Member since {{ $customer->created_at->format('M d, Y') }}</p>
        </div>
        <div class="flex-between" style="gap: 1rem;">
             <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back to Customers</a>
        </div>
    </div>
</div>

<div class="grid-2-1">
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="card">
            <h3 style="font-size: 1.125rem; margin-bottom: 1.5rem;">Purchase History</h3>
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Total Amount</th>
                            <th>Date</th>
                            <th style="text-align: right;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td style="font-weight: 700;">#{{ $order->order_number }}</td>
                            <td style="color: var(--color-accent); font-weight: 700;">₹{{ number_format($order->total) }}</td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td style="text-align: right;">
                                <span class="status-badge {{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem; color: var(--color-text-light);">
                                No orders yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <h3 style="font-size: 1.125rem; margin-bottom: 1.5rem;">Saved Addresses</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem;">
                @forelse($customer->addresses as $address)
                <div style="padding: 1rem; border: 1px solid var(--color-border); border-radius: 12px; background: rgba(0,0,0,0.02);">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                         <div style="font-weight: 700; color: var(--color-text);">{{ $address->name }}</div>
                         @if($address->is_default)
                            <span style="font-size: 0.65rem; background: var(--color-accent); color: white; padding: 2px 6px; border-radius: 4px; text-transform: uppercase;">Default</span>
                         @endif
                    </div>
                    <div style="font-size: 0.825rem; color: var(--color-text-light); line-height: 1.4;">
                        {{ $address->address }}<br>
                        {{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}<br>
                        Phone: {{ $address->phone }}
                    </div>
                </div>
                @empty
                <div style="grid-column: span 2; padding: 1rem; color: var(--color-text-light); font-size: 0.875rem;">
                    No addresses saved.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="card">
            <h3 style="font-size: 1.125rem; margin-bottom: 1.5rem;">Customer Summary</h3>
            <div style="font-size: 0.875rem;">
                <div class="flex-between" style="margin-bottom: 1rem;">
                    <span style="color: var(--color-text-light);">Email</span>
                    <span style="font-weight: 600;">{{ $customer->email }}</span>
                </div>
                <div class="flex-between" style="margin-bottom: 1rem;">
                    <span style="color: var(--color-text-light);">Total Orders</span>
                    <span style="font-weight: 600;">{{ $orders->count() }}</span>
                </div>
                <div class="flex-between">
                    <span style="color: var(--color-text-light);">Total Spent</span>
                    <span style="font-weight: 800; color: var(--color-accent);">₹{{ number_format($orders->sum('total')) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
