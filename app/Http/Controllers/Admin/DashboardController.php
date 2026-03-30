<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products_count' => Product::count(),
            'orders_count' => Order::where('status', '!=', 'cancelled')->count(),
            'customers_count' => User::count(),
            'revenue' => '₹' . number_format(Order::where('status', 'delivered')->sum('total'), 0),
        ];

        $recentProducts = Product::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentProducts'));
    }
}
