<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $urls = [];

        // Add static pages
        $urls[] = ['loc' => url('/'), 'priority' => '1.0'];
        $urls[] = ['loc' => url('/shop'), 'priority' => '0.8'];
        $urls[] = ['loc' => url('/about'), 'priority' => '0.5'];
        $urls[] = ['loc' => url('/contact'), 'priority' => '0.5'];

        // Add products
        foreach ($products as $product) {
            $urls[] = [
                'loc' => url('/product/' . $product->id),
                'priority' => '0.7',
                'lastmod' => $product->updated_at->toAtomString(),
            ];
        }

        $content = view('sitemap', compact('urls'));

        return Response::make($content, 200, ['Content-Type' => 'text/xml']);
    }
}
