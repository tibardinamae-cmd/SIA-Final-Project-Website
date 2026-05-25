<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        $categories = Category::all();
        return view('inventory.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        // 1. Basic Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'expiration_date' => 'required|date',
        ]);

        // 2. Manual Image Validation only if a file is present
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120', 
            ]);
        }

        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->expiration_date = $request->expiration_date;

        if ($request->hasFile('image')) {
            $imageName = 'prod_' . time() . '_' . rand(100, 999) . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);
            $product->image = 'uploads/products/' . $imageName;
        } else {
            $product->image = 'https://images.unsplash.com/photo-1593560708920-61dd98c46a4e?w=400';
        }

        $product->save();
        return redirect()->back()->with('success', 'Product created successfully.');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'expiration_date' => 'required|date',
        ]);

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            ]);
        }

        $product->name = $request->name;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->expiration_date = $request->expiration_date;

        if ($request->hasFile('image')) {
            // Delete old file if it exists
            if ($product->image && !str_starts_with($product->image, 'http')) {
                $oldPath = public_path($product->image);
                if (file_exists($oldPath)) @unlink($oldPath);
            }
            
            $imageName = 'prod_' . time() . '_' . rand(100, 999) . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);
            $product->image = 'uploads/products/' . $imageName;
        }

        $product->save();
        return redirect()->back()->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        TransactionDetail::where('product_id', $id)->delete();

        if ($product->image && !str_starts_with($product->image, 'http')) {
            $imagePath = public_path($product->image);
            if (file_exists($imagePath)) @unlink($imagePath);
        }

        $product->delete();
        return redirect()->back()->with('success', 'Product deleted successfully.');
    }
}
