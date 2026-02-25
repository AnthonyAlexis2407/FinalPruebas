<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Size;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::with('category')->orderBy('category_id')->orderBy('number')->get();
        $categories = \App\Models\Category::all(); // Pass categories for the dropdown
        return view('sizes.index', compact('sizes', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|string|max:50',
            'category_id' => [
                'nullable',
                \Illuminate\Validation\Rule::exists('categories', 'id')->where(function ($query) {
                    return $query->where('store_id', session('active_store_id'));
                })
            ],
        ]);

        Size::create($request->all());
        return back()->with('success', 'Presentación creada correctamente.');
    }

    public function update(Request $request, Size $size)
    {
        $request->validate([
            'number' => 'required|string|max:50',
            'category_id' => [
                'nullable',
                \Illuminate\Validation\Rule::exists('categories', 'id')->where(function ($query) {
                    return $query->where('store_id', session('active_store_id'));
                })
            ],
        ]);

        $size->update($request->all());
        return back()->with('success', 'Presentación actualizada correctamente.');
    }

    public function destroy(Size $size)
    {
        if ($size->productSizes()->exists()) {
            return back()->with('error', 'No se puede eliminar esta presentación porque está siendo usada por productos.');
        }

        $size->delete();
        return back()->with('success', 'Presentación eliminada correctamente.');
    }
}
