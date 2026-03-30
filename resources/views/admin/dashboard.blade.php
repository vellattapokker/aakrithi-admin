@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard Overview</h1>
    <p style="color: var(--color-text-light); font-size: 0.875rem;">Welcome back, here's what's happening today.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-info">
            <h4>Total Products</h4>
            <div class="stat-value">{{ $stats['products_count'] }}</div>
        </div>
        <div class="stat-icon"><i data-lucide="package"></i></div>
    </div>
    
    <div class="stat-card">
        <div class="stat-info">
            <h4>Total Orders</h4>
            <div class="stat-value">{{ $stats['orders_count'] }}</div>
        </div>
        <div class="stat-icon"><i data-lucide="shopping-cart"></i></div>
    </div>
    
    <div class="stat-card">
        <div class="stat-info">
            <h4>Total Customers</h4>
            <div class="stat-value">{{ $stats['customers_count'] }}</div>
        </div>
        <div class="stat-icon"><i data-lucide="users"></i></div>
    </div>
    
    <div class="stat-card">
        <div class="stat-info">
            <h4>Monthly Revenue</h4>
            <div class="stat-value">{{ $stats['revenue'] }}</div>
        </div>
        <div class="stat-icon"><i data-lucide="trending-up"></i></div>
    </div>
</div>

<div class="grid-2-1" style="margin-bottom: 1.5rem;">
    <div class="card">
        <h3 style="font-size: 1.125rem; margin-bottom: 2rem; color: var(--color-text);">Sales Performance</h3>
        <div style="height: 320px;">
            <canvas id="salesChart"></canvas>
        </div>
    </div>
    
    <div class="card">
        <h3 style="font-size: 1.125rem; margin-bottom: 1.5rem; color: var(--color-text);">Quick Actions</h3>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <a href="{{ route('products.create') }}" class="btn btn-primary" style="justify-content: center;">Add New Product</a>
            <a href="{{ route('admin.seo') }}" class="btn btn-secondary" style="justify-content: center;">Update SEO Tags</a>
            <a href="/sitemap.xml" target="_blank" class="btn btn-secondary" style="justify-content: center;">View Sitemap</a>
        </div>
    </div>
</div>

<div class="card">
    <div class="flex-between" style="margin-bottom: 1.5rem;">
        <h3 style="font-size: 1.125rem; color: var(--color-text);">Recent Products</h3>
        <a href="{{ route('products.index') }}" style="color: var(--color-accent); font-size: 0.75rem; font-weight: 600;">View All</a>
    </div>
    <div class="admin-table-container mt-1">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th style="text-align: right;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentProducts as $product)
                <tr>
                    <td style="font-weight: 600; color: var(--color-text);">{{ $product['name'] }}</td>
                    <td>{{ $product['category'] }}</td>
                    <td style="color: var(--color-accent); font-weight: 700;">₹{{ number_format($product['price']) }}</td>
                    <td style="text-align: right;"><span class="status-badge success">Active</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    // Create gradient
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(197, 160, 89, 0.2)');
    gradient.addColorStop(1, 'rgba(197, 160, 89, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Revenue',
                data: [12000, 19000, 15000, 25000, 22000, 30000, 45200],
                borderColor: '#C5A059',
                backgroundColor: gradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#C5A059',
                pointBorderColor: '#FEFEE3',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.05)' },
                    ticks: { color: '#5A5A5A', font: { size: 11 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#5A5A5A', font: { size: 11 } }
                }
            }
        }
    });
});
</script>
@endsection
