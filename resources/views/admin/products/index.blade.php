@extends('layouts.admin')

@section('title', 'Products')

@section('styles')
<style>
    .seo-badges { display: flex; flex-wrap: wrap; gap: 4px; }
    .seo-badge {
        display: inline-flex; align-items: center; gap: 3px;
        padding: 2px 8px; border-radius: 50px; font-size: 0.65rem;
        font-weight: 600; letter-spacing: 0.3px; text-transform: uppercase;
        white-space: nowrap;
    }
    .seo-badge.ok  { background: rgba(76,175,80,0.1);  color: #2e7d32; border: 1px solid rgba(76,175,80,0.25); }
    .seo-badge.warn { background: rgba(255,152,0,0.1); color: #e65100; border: 1px solid rgba(255,152,0,0.25); }
    .seo-badge.danger { background: rgba(244,67,54,0.1); color: #c62828; border: 1px solid rgba(244,67,54,0.25); }
    .seo-score {
        display: inline-flex; align-items: center; justify-content: center;
        width: 32px; height: 32px; border-radius: 50%; font-size: 0.75rem;
        font-weight: 800; color: #fff;
    }
    .seo-score.high   { background: linear-gradient(135deg, #43a047, #66bb6a); }
    .seo-score.medium { background: linear-gradient(135deg, #ef6c00, #ffa726); }
    .seo-score.low    { background: linear-gradient(135deg, #c62828, #ef5350); }
    .seo-summary-bar {
        display: flex; gap: 1.5rem; padding: 1rem 1.5rem;
        margin-bottom: 1.5rem; border-radius: 12px;
        background: var(--color-card); border: 1px solid var(--color-border);
    }
    .seo-summary-item { text-align: center; }
    .seo-summary-item .num { font-size: 1.5rem; font-weight: 800; color: var(--color-text); }
    .seo-summary-item .lbl { font-size: 0.7rem; color: var(--color-text-light); text-transform: uppercase; letter-spacing: 0.5px; }
</style>
@endsection

@section('content')
<div class="page-header flex-between">
    <div>
        <h1 class="page-title">Product Management</h1>
        <p style="color: var(--color-text-light); font-size: 0.875rem;">Manage your store's product catalog, SEO metadata, and inventory.</p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <a href="{{ route('admin.seo') }}" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 6px;"><i data-lucide="globe" style="width:16px;height:16px;"></i> Global SEO</a>
        <a href="{{ route('products.create') }}" class="btn btn-primary">+ Add Product</a>
    </div>
</div>

{{-- SEO Health Summary --}}
@php
    $allProducts = $products;
    $totalCount = $allProducts->count();
    $seoComplete = $allProducts->filter(fn($p) => $p->slug && $p->meta_title && $p->meta_description)->count();
    $noindexCount = $allProducts->filter(fn($p) => $p->is_noindex)->count();
    $missingMeta  = $totalCount - $seoComplete;
@endphp
<div class="seo-summary-bar">
    <div class="seo-summary-item">
        <div class="num">{{ $totalCount }}</div>
        <div class="lbl">Total Products</div>
    </div>
    <div class="seo-summary-item">
        <div class="num" style="color: #2e7d32;">{{ $seoComplete }}</div>
        <div class="lbl">SEO Complete</div>
    </div>
    <div class="seo-summary-item">
        <div class="num" style="color: #e65100;">{{ $missingMeta }}</div>
        <div class="lbl">Missing Meta</div>
    </div>
    <div class="seo-summary-item">
        <div class="num" style="color: #c62828;">{{ $noindexCount }}</div>
        <div class="lbl">NoIndex</div>
    </div>
</div>

<div class="card">
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>SEO Score</th>
                    <th>SEO Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                @php
                    $score = 0;
                    if ($product->slug) $score += 25;
                    if ($product->meta_title) $score += 25;
                    if ($product->meta_description) $score += 25;
                    if (!$product->is_noindex) $score += 25;
                    $scoreClass = $score >= 75 ? 'high' : ($score >= 50 ? 'medium' : 'low');
                @endphp
                <tr>
                    <td>
                        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" style="width: 45px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid var(--color-border);">
                    </td>
                    <td style="font-weight: 600; color: var(--color-text);">{{ $product['name'] }}</td>
                    <td>{{ $product['category'] }}</td>
                    <td style="color: var(--color-accent); font-weight: 700;">₹{{ number_format($product['price']) }}</td>
                    <td>
                        <span class="seo-score {{ $scoreClass }}">{{ $score }}</span>
                    </td>
                    <td>
                        <div class="seo-badges">
                            @if($product->slug)
                                <span class="seo-badge ok">Slug ✓</span>
                            @else
                                <span class="seo-badge danger">No Slug</span>
                            @endif

                            @if($product->meta_title)
                                <span class="seo-badge ok">Title ✓</span>
                            @else
                                <span class="seo-badge warn">No Title</span>
                            @endif

                            @if($product->meta_description)
                                <span class="seo-badge ok">Desc ✓</span>
                            @else
                                <span class="seo-badge warn">No Desc</span>
                            @endif

                            @if($product->is_noindex)
                                <span class="seo-badge danger">NoIndex</span>
                            @else
                                <span class="seo-badge ok">Indexed</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                            <a href="{{ route('products.edit', $product['id']) }}" style="color: var(--color-text-light);" title="Edit"><i data-lucide="edit-2" style="width:18px;"></i></a>
                            <form action="{{ route('products.destroy', $product['id']) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none; border:none; color: var(--color-error); cursor:pointer;" title="Delete"><i data-lucide="trash-2" style="width:18px;"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if($products->hasPages())
    <div style="margin-top: 2rem;">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
