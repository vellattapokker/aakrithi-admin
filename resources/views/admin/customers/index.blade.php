@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
<div class="page-header">
    <div class="flex-between">
        <div>
            <h1 class="page-title">Customer Management</h1>
            <p style="color: var(--color-text-light); font-size: 0.875rem;">Manage and view your user base.</p>
        </div>
        <div class="flex-between" style="gap: 1rem;">
             <form action="{{ route('customers.index') }}" method="GET" style="display: flex; gap: 0.5rem;">
                <input type="text" name="search" class="form-control" placeholder="Search name or email..." value="{{ request('search') }}" style="width: 250px;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem;"><i data-lucide="search"></i></button>
             </form>
        </div>
    </div>
</div>

<div class="card">
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Email Address</th>
                    <th>Joined Date</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr>
                    <td style="font-weight: 700;">{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->created_at->format('M d, Y') }}</td>
                    <td style="text-align: right;">
                        <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.75rem;">View Details</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 3rem; color: var(--color-text-light);">
                        No customers found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 1.5rem;">
        {{ $customers->links() }}
    </div>
</div>
@endsection
