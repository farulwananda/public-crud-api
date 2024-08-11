<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProductSingleResource;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controllers\HasMiddleware;

class ProductController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(middleware: 'auth:sanctum', except: ['index', 'show']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return response()->json([
            'message' => 'Products fetched successfully',
            'data' => ProductResource::collection(Product::latest()->paginate(25))
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $image_name);
            $request['image_url'] = env('APP_URL') . '/storage/images/' . $image_name;
        }

        $product = Product::create($request->toArray());

        return response()->json([
            'message' => 'Product created successfully',
            'data' => new ProductSingleResource($product),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json([
            'message' => 'Product fetched successfully',
            'data' => new ProductSingleResource($product),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $image_name);
            $request['image_url'] = env('APP_URL') . '/storage/images/' . $image_name;

            if ($product->image_url) {
                $image_path = str_replace(env('APP_URL') . '/storage/', '', $product->image_url);
                Storage::delete('public/' . $image_path);
            }
        }

        $attributes = $request->toArray();
        $attributes['slug'] = Str::slug($request->name . '-' . time());
        $product->update($attributes);

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => new ProductSingleResource($product),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->image_url) {
            $image_path = str_replace(env('APP_URL') . '/storage/', '', $product->image_url);
            Storage::delete('public/' . $image_path);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully',
        ], 200);
    }
}
