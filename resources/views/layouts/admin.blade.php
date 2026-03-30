<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') | Aakriti Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @yield('styles')
</head>
<body>
    <div class="admin-wrapper">
        {{-- Sidebar --}}
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="admin-logo" style="display: block; width: 100%;">
                    <img src="{{ asset('images/logo.png') }}" alt="Aakrithi Admin" style="max-width: 140px; height: auto;">
                </a>
            </div>
            
            <nav class="sidebar-nav">
                <p class="nav-category">Main</p>
                <div class="nav-item-group">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i data-lucide="layout-dashboard"></i> Dashboard
                    </a>
                    <div class="nav-sub-list">
                        <a href="{{ route('admin.dashboard') }}" class="nav-sub-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Overview</a>
                        <a href="#" class="nav-sub-link">Analytics</a>
                    </div>
                </div>
                
                <p class="nav-category">Management</p>
                <div class="nav-item-group">
                    <div class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <i data-lucide="package"></i> Catalog
                    </div>
                    <div class="nav-sub-list">
                        <a href="{{ route('products.index') }}" class="nav-sub-link {{ request()->url() == route('products.index') ? 'active' : '' }}">All Products</a>
                        <a href="{{ route('products.create') }}" class="nav-sub-link {{ request()->routeIs('products.create') ? 'active' : '' }}">Add Product</a>
                    </div>
                </div>

                <div class="nav-item-group">
                    <div class="nav-link {{ request()->routeIs('orders.*') || request()->routeIs('customers.*') ? 'active' : '' }}">
                        <i data-lucide="shopping-cart"></i> Sales
                    </div>
                    <div class="nav-sub-list">
                        <a href="{{ route('orders.index') }}" class="nav-sub-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">Orders</a>
                        <a href="{{ route('customers.index') }}" class="nav-sub-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">Customers</a>
                    </div>
                </div>
                
                <p class="nav-category">Configuration</p>
                <div class="nav-item-group">
                    <div class="nav-link {{ request()->routeIs('admin.seo') || request()->routeIs('admin.settings') ? 'active' : '' }}">
                        <i data-lucide="settings"></i> Site Config
                    </div>
                    <div class="nav-sub-list">
                        <a href="{{ route('admin.seo') }}" class="nav-sub-link {{ request()->routeIs('admin.seo') ? 'active' : '' }}">SEO Settings</a>
                        <a href="{{ route('admin.settings') }}" class="nav-sub-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">General Settings</a>
                    </div>
                </div>
            </nav>
            
            <div style="padding: 1rem; border-top: 1px solid var(--color-border); margin-top: auto;">
                <form action="#" method="POST">
                    @csrf
                    <button type="submit" class="nav-link" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                        <i data-lucide="log-out"></i> Logout
                    </button>
                </form>
            </div>

        </aside>

        {{-- Main Area --}}
        <div class="main-area">
            <header class="admin-header">
                <div class="header-search">
                    <i data-lucide="search" style="color: var(--color-text-light); width: 18px;"></i>
                </div>
                <div class="header-actions">
                    <button class="action-btn" title="Notifications"><i data-lucide="bell"></i></button>
                    <div class="header-profile">
                        <span class="admin-name">Admin User</span>
                        <div class="admin-avatar">AD</div>
                    </div>
                </div>

            </header>

            <main class="admin-content">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
    @yield('scripts')
</body>
</html>
