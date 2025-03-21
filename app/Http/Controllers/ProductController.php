<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('user_id', '=', Auth::id())
            ->with(['categories', 'images'])
            ->get();
        $categories = Category::all();

        return view('products.index', [
            "products" => $products,
            "categories" => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $productCount = Product::where('user_id', Auth::id())->count();
        if ($productCount >= 5) {
            return redirect()->back()
                ->with('error', 'Anda Sudah Mencapai Maksimum Input');
        }
        $validated = $request->validate([
            "name" => ["required", "string", "max:100"],
            "description" => ["string", "nullable"]
        ]);

        Product::create([
            "name" => $validated['name'],
            "description" => $validated['description'],
            "user_id" => Auth::id()
        ]);
        return redirect()->route('products.index');
    }

    public function addCategory(int $id, Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id']
        ]);

        $categoryExist = DB::table('category_product')
            ->where('category_id', $validated['category_id'])
            ->where('product_id', $id)
            ->exists();

        if ($categoryExist) {
            throw ValidationException::withMessages([
                'category' => 'Kategori yang dipilih sudah ditambahkan'
            ]);
        }

        $product = Product::where('id', '=', $id)->first();
        $product->categories()->attach($validated['category_id']);

        return redirect()->route('products.index');
    }

    public function deleteCategory(int $id, int $categoryId)
    {
        $product = Product::find($id);
        $product->categories()->detach($categoryId);

        $category = Category::find($categoryId);
        $category->products()->detach($id);

        return redirect()->route('products.index');
    }

    public function uploadImage(int $id, Request $request)
    {
        $request->validate([
            "image" => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:2048']
        ]);

        $path = $request->file('image')->store('products', 'public');

        ProductImage::create([
            "image_url" => $path,
            "product_id" => $id
        ]);

        return redirect()->route('products.index');
    }

    public function deleteImage(int $id)
    {
        ProductImage::find($id)->delete();
        return redirect()->route('products.index');
    }

    public function delete(int $id)
    {
        Product::where('id', '=', $id)->delete();

        return redirect()->route('products.index');
    }
}
