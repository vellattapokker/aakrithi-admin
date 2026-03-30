<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|max:255|unique:products,slug',
            'price' => 'required|numeric',
            'category' => 'required',
            'image' => 'required|url',
            'description' => 'required',
            'badge' => 'nullable|string',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable',
            'og_image' => 'nullable|url',
        ]);

        $validated['is_noindex'] = $request->has('is_noindex');

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|max:255|unique:products,slug,' . $id,
            'price' => 'required|numeric',
            'category' => 'required',
            'image' => 'required|url',
            'description' => 'required',
            'badge' => 'nullable|string',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable',
            'og_image' => 'nullable|url',
        ]);

        $validated['is_noindex'] = $request->has('is_noindex');

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
