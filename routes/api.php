<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;

Route::prefix('auth')->group(function(){
    Route::post('register',[AuthController::class,'register']);
    Route::post('login',[AuthController::class,'login']);
    Route::middleware('auth:api')->group(function(){
        Route::post('logout',[AuthController::class,'logout']);
        Route::get('me',[AuthController::class,'me']);
    });
});

Route::middleware('auth:api')->group(function(){
    Route::apiResource('products', ProductController::class);
    // cart
    Route::get('cart',[CartController::class,'index']);
    Route::post('cart/add',[CartController::class,'add']);
    Route::delete('cart/item/{id}',[CartController::class,'remove']);
    Route::delete('cart/clear',[CartController::class,'clear']);
    // orders
    Route::get('orders',[OrderController::class,'index']);
    Route::post('orders',[OrderController::class,'store']);
    Route::get('orders/{id}',[OrderController::class,'show']);
});