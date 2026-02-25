<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where(fn($q) => $q->where('store_id', session('active_store_id')))
            ],
            'active' => 'boolean'
        ]);

        Category::create([
            'name' => $request->name,
            'active' => $request->has('active') ? $request->active : true,
        ]);

        return redirect()->route('categories.index')->with('success', 'Categoría creada correctamente.');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where(fn($q) => $q->where('store_id', session('active_store_id')))->ignore($category->id)
            ],
            'active' => 'boolean'
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'No se puede eliminar la categoría porque tiene productos asociados.');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Categoría eliminada correctamente.');
    }
}
