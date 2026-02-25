<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class InventoryController extends Controller
{
    public function index(Product $product)
    {
        // Filter sizes: Global (null category) OR specific to this product's category
        $sizes = \App\Models\Size::whereNull('category_id')
            ->orWhere('category_id', $product->category_id)
            ->orderBy('category_id') // Groups global first (or last depending on DB) and then specific
            ->orderBy('number')
            ->get();

        $product->load('sizes');
        return view('inventory.manage', compact('product', 'sizes'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'size_id' => [
                'required',
                \Illuminate\Validation\Rule::exists('sizes', 'id')->where(function ($query) {
                    return $query->where('store_id', session('active_store_id'));
                })
            ],
            'stock' => 'required|integer|min:0'
        ]);

        $size = \App\Models\Size::find($request->size_id);

        // Final safety check to prevent property access on null
        if (!$size) {
            return back()->with('error', 'La presentación seleccionada no es válida para esta tienda.');
        }

        $product->sizes()->updateOrCreate(
            ['size_id' => $request->size_id],
            ['stock' => $request->stock, 'name' => $size->number]
        );

        return redirect()->back()->with('success', 'Stock y presentación actualizados correctamente.');
    }
    public function destroy(Product $product, \App\Models\ProductSize $productSize)
    {
        // Ensure the ProductSize belongs to the Product
        if ($productSize->product_id !== $product->id) {
            return back()->with('error', 'Esta presentación no pertenece a este producto.');
        }

        try {
            // Check for sales dependencies (could prevent deletion if strict)
            // But let's handle recipes first as that's the immediate blocker for unsold items.

            // Delete recipes where this size is the parent (Recipe Definition)
            $productSize->recipes()->delete();

            // Delete recipes where this size is a component (Ingredient)
            \App\Models\ProductRecipe::where('component_product_size_id', $productSize->id)->delete();

            $productSize->delete();
            return back()->with('success', 'Presentación eliminada de este producto.');
        } catch (\Exception $e) {
            return back()->with('error', 'No se puede eliminar: ' . $e->getMessage());
        }
    }
}
