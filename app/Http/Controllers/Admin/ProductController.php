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
        $products = Product::with('brand', 'category', 'images')->get();
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

        // Validate images
        $request->validate([
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048']
        ]);

        // Create product
        $productData['created_by'] = Auth::user()->id;
        $product = Product::create($productData);

        // Upload images
        if ($request->hasFile('images')) {
            $productImage = $request->file('images');
            foreach ($productImage as $image) {
                $image = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $image
                ]);
            }
        }

        // Redirect to index
        return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
    }

    /**
     * Change Product Published.
     */
    public function changePublished(string $id)
    {
        $product = Product::findOrFail($id);
        $product->published = !$product->published;
        $product->save();
        if ($product->published) {
            return redirect()->route('admin.products.index')->with('success', 'Product published successfully');
        } else {
            return redirect()->route('admin.products.index')->with('success', 'Product unpublished successfully');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        $productData = $request->validated();
        $product = Product::findOrFail($id);

        // Validate images
        $request->validate([
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048']
        ]);

        // Upload images
        if ($request->hasFile('images')) {

            // Delete old images
            $productImageOld = ProductImage::where('product_id', $id)->get();
            if (count($productImageOld) > 0) {
                foreach ($productImageOld as $image) {
                    File::delete(storage_path('app/public/' . $image->image));
                    $image->delete();
                }
            }

            // Upload new images
            $productImage = $request->file('images');
            foreach ($productImage as $image) {
                $image = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $image
                ]);
            }
        }

        // Update product
        $productData['updated_by'] = Auth::user()->id;
        $product->update($productData);

        // Redirect to index
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
