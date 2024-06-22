<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return Inertia::render('Admin/Category/Index', [
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $categoryData = $request->validated();
        if ($request->hasFile('image')) {
            $categoryData['image'] = $request->file('image')->store('categories', 'public');
        }
        Category::create($categoryData);
        return redirect()->back()->with('success', 'Category created successfully');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        $categoryData = $request->validated();
        $category = Category::findOrFail($id);
        if ($request->hasFile('image')) {
            $categoryData['image'] = $request->file('image')->store('categories', 'public');
            File::delete(public_path('storage/' . $category->image));
        }
        $category->update($categoryData);
        return redirect()->back()->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->image ? File::delete(public_path('storage/' . $category->image)) : '';
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully');
    }

    /**
     * Delete Multiple Images
     */
    public function deleteMultiple(string $id)
    {
        $categories = explode(',', $id);
        foreach ($categories as $id) {
            $category = Category::findOrFail($id);
            $category->image ? File::delete(storage_path('app/public/' . $category->image)) : '';
            $category->delete();
        }

        return redirect()->back()->with('success', 'Products deleted successfully');
    }
}
