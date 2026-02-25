<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category', 'sizes');

        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        $products = $query->get();
        $categories = Category::where('active', true)->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('active', true)->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'code' => [
                'required',
                \Illuminate\Validation\Rule::unique('products')->where(function ($query) {
                    return $query->where('store_id', session('active_store_id'));
                })
            ],
            'name' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image'
        ]);

        $data = $request->except('image');
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('active', true)->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required',
            'code' => [
                'required',
                \Illuminate\Validation\Rule::unique('products')->where(function ($query) {
                    return $query->where('store_id', session('active_store_id'));
                })->ignore($product->id)
            ],
            'name' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image'
        ]);

        $data = $request->except('image');
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
