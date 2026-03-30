<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SEOController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
Route::resource('products', ProductController::class);
Route::resource('orders', OrderController::class)->only(['index', 'show', 'update']);
Route::resource('customers', CustomerController::class)->only(['index', 'show']);

Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
Route::post('/settings', [SettingController::class, 'update'])->name('admin.settings.update');

Route::get('/seo', [SEOController::class, 'index'])->name('admin.seo');
Route::post('/seo', [SEOController::class, 'update'])->name('admin.seo.update');

