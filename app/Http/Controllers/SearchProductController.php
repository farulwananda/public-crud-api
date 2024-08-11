<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;

class SearchProductController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $find = $request->find;

        $products = Product::where('name', 'like', "%{$find}%")->paginate(25);
        return response()->json([
            'message' => 'Product search result',
            'data' => ProductResource::collection($products),
        ]);
    }
}
