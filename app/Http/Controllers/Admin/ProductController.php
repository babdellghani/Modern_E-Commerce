<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        $brands = Brand::all();
        $categories = Category::all();
        return Inertia::render('Admin/Product/Index', [
            'products' => $products,
            'brands' => $brands,
            'categories' => $categories
        ]);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $productData = $request->validated();

        // Upload images
        $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048']
        ]);
        if ($request->hasFile('image')) {
            $productImage = $request->file('image');
            foreach ($productImage as $image) {
                $image = $productImage->store('products', 'public');
                ProductImage::create([
                    'product_id' => $productData['id'],
                    'image' => $image
                ]);
            }
        }

        $productData['created_by'] = Auth::user()->id;

        Product::create($productData);
        return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        $productData = $request->validated();
        $product = Product::findOrFail($id);

        // Upload images
        // Validate images
        $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048']
        ]);

        // Upload images
        if ($request->hasFile('image')) {

            // Delete old images
            $productImageOld = ProductImage::where('product_id', $id)->get();
            if (count($productImageOld) > 0) {
                foreach ($productImageOld as $image) {
                    File::delete(storage_path('app/public/' . $image->image));
                    $image->delete();
                }
            }

            // Upload new images
            $productImage = $request->file('image');
            foreach ($productImage as $image) {
                $image = $productImage->store('products', 'public');
                ProductImage::create([
                    'product_id' => $productData['id'],
                    'image' => $image
                ]);
            }
        }

        $productData['updated_by'] = Auth::user()->id;

        $product->update($productData);
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        // Delete images
        $productImage = ProductImage::where('product_id', $id)->get();
        if (count($productImage) > 0) {
            foreach ($productImage as $image) {
                File::delete(storage_path('app/public/' . $image->image));
                $image->delete();
            }
        }

        // Delete product
        $product->deleted_by = Auth::user()->id;
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');
    }
}
