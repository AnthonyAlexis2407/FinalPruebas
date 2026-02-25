<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ProductRecipe;
use App\Models\Category;

class RecipeController extends Controller
{
    public function index(Product $product)
    {
        // Restriction: Insumos category products cannot have recipes
        if ($product->category && $product->category->name === 'Insumos') {
            return redirect()->route('products.index')->with('error', 'Los productos de la categoría Insumos no pueden tener recetas.');
        }

        $product->load('sizes.size', 'sizes.recipes.componentProductSize.product', 'sizes.recipes.componentProductSize.size');

        // Get only supply products (Insumos) for the dropdown to make it "automatic/easy"
        $insumoCategory = Category::where('name', 'Insumos')->first();
        $supplies = [];

        if ($insumoCategory) {
            $supplies = Product::with(['sizes' => function ($q) {
                $q->where('stock', '>', 0)->orWhere('stock', 0); // Always show supplies
            }, 'sizes.size'])
                ->where('category_id', $insumoCategory->id)
                ->get();
        }

        return view('products.recipes.index', compact('product', 'supplies'));
    }

    public function store(Request $request, Product $product)
    {
        // Restriction: Insumos category products cannot have recipes
        if ($product->category && $product->category->name === 'Insumos') {
            return redirect()->route('products.index')->with('error', 'Los productos de la categoría Insumos no pueden tener recetas.');
        }

        $request->validate([
            'product_size_id' => 'required|exists:product_sizes,id',
            'component_product_size_id' => 'required|exists:product_sizes,id',
            'quantity' => 'required|numeric|min:0.1'
        ]);

        // Prevent self-reference or cycles if needed, but for now just simple link
        if ($request->product_size_id == $request->component_product_size_id) {
            return back()->withErrors(['msg' => 'No puedes asignar el mismo producto como insumo.']);
        }

        ProductRecipe::create([
            'product_size_id' => $request->product_size_id,
            'component_product_size_id' => $request->component_product_size_id,
            'quantity' => $request->quantity
        ]);

        return back()->with('success', 'Insumo agregado a la receta correctamente.');
    }

    public function destroy(Product $product, ProductRecipe $recipe)
    {
        $recipe->delete();
        return back()->with('success', 'Insumo eliminado de la receta.');
    }
}
