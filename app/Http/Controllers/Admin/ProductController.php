<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->with('brand', 'category', 'images')->paginate(10);
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
        return redirect()->back()->with('success', 'Product created successfully');
    }

    /**
     * Change Product Published.
     */
    public function changePublished(string $id)
    {

        $product = Product::findOrFail($id);
        if (!$product->published && ($product->images->count() == 0 || !$product->images)) {
            return redirect()->back()->with('error', 'Please upload images first');
        } else if (!$product->published && ($product->category_id == null)) {
            return redirect()->back()->with('error', 'Please fill category first');
        } else if (!$product->published && ($product->brand_id == null)) {
            return redirect()->back()->with('error', 'Please fill brand first');
        }

        $product->updated_by = Auth::user()->id;
        $product->published = !$product->published;
        $product->save();
        if ($product->published) {
            return redirect()->back()->with('success', 'Product published successfully');
        } else {
            return redirect()->back()->with('success', 'Product unpublished successfully');
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
        return redirect()->back()->with('success', 'Product updated successfully');
    }

    /**
     * Delete Single Image
     */
    public function deleteImage(string $id)
    {
        $image = ProductImage::findOrFail($id);
        $image->image ? File::delete(storage_path('app/public/' . $image->image)) : '';
        $image->delete();
        return redirect()->back()->with('success', 'Image deleted successfully');
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
                $image->image ? File::delete(storage_path('app/public/' . $image->image)) : '';
                $image->delete();
            }
        }

        // Delete product
        $product->update(['deleted_by' => Auth::user()->id]);
        $product->delete();

        // Redirect to index
        return redirect()->back()->with('success', 'Product deleted successfully');
    }

    /**
     * Delete Multiple Images
     */
    public function deleteMultiple(string $id)
    {
        $products = explode(',', $id);
        foreach ($products as $id) {
            $product = Product::findOrFail($id);
            // Delete images
            $productImage = ProductImage::where('product_id', $id)->get();
            if (count($productImage) > 0) {
                foreach ($productImage as $image) {
                    $image->image ? File::delete(storage_path('app/public/' . $image->image)) : '';
                    $image->delete();
                }
            }

            // Delete product
            $product->update(['deleted_by' => Auth::user()->id]);
            $product->delete();
        }

        return redirect()->back()->with('success', 'Products deleted successfully');
    }
}
