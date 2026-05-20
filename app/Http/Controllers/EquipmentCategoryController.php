<?php

namespace App\Http\Controllers;

use App\Models\EquipmentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EquipmentCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $categories = EquipmentCategory::withCount('items')->get();

        return view('admin.equipment.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        EquipmentCategory::create($validated);

        return back()->with('success', 'Category created.');
    }

    public function update(Request $request, EquipmentCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'sort_order' => 'integer',
        ]);

        $category->update($validated);

        return back()->with('success', 'Category updated.');
    }

    public function destroy(EquipmentCategory $category)
    {
        if ($category->items()->count() > 0) {
            return back()->with('error', 'Cannot delete category with items.');
        }

        $category->delete();

        return back()->with('success', 'Category deleted.');
    }
}
