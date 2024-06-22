<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Requests\BrandRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::latest()->paginate(10);
        return Inertia::render('Admin/Brand/Index', [
            'brands' => $brands
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
    public function store(BrandRequest $request)
    {
        $brandData = $request->validated();
        if ($request->hasFile('image')) {
            $brandData['image'] = $request->file('image')->store('brands', 'public');
        }
        Brand::create($brandData);
        return redirect()->back()->with('success', 'Brand created successfully');
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
    public function update(BrandRequest $request, string $id)
    {
        $brandData = $request->validated();
        $brand = Brand::findOrFail($id);
        if ($request->hasFile('image')) {
            $brandData['image'] = $request->file('image')->store('brands', 'public');
            $brand->image ? File::delete(storage_path('app/public/' . $brand->image)) : '';
        }
        $brand->update($brandData);
        return redirect()->back()->with('success', 'Brand updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::findOrFail($id);
        $brand->image ? File::delete(storage_path('app/public/' . $brand->image)) : '';
        $brand->delete();
        return redirect()->back()->with('success', 'Brand deleted successfully');
    }

    /**
     * Delete Multiple Images
     */
    public function deleteMultiple(string $id)
    {
        $brands = explode(',', $id);
        foreach ($brands as $id) {
            $brand = Brand::findOrFail($id);
            $brand->image ? File::delete(storage_path('app/public/' . $brand->image)) : '';
            $brand->delete();
        }

        return redirect()->back()->with('success', 'Products deleted successfully');
    }
}
