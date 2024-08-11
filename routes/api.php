<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SearchProductController;


route::get('/', fn () => response()->json(['message' => 'Hello World!']));
route::post('register', [AuthController::class, 'register']);
route::post('login', [AuthController::class, 'login']);

route::get('search-products', SearchProductController::class);
route::apiResource('products', ProductController::class);
route::apiResource('categories', CategoryController::class);

route::middleware('auth:sanctum')->group(function () {
    route::get('user', [AuthController::class, 'currentUser']);
    route::post('logout', [AuthController::class, 'logout']);
});
