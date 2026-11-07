<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CheckoutController;
use App\Http\Controllers\Api\V1\CouponController;
use App\Http\Controllers\Api\V1\ModuleController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\StoreController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('throttle:api')->group(function (): void {
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('auth/user', [AuthController::class, 'user']);

        Route::middleware('tenant')->group(function (): void {
            Route::get('stores', [StoreController::class, 'index']);
            Route::get('stores/{store}', [StoreController::class, 'show']);

            Route::middleware('module:catalog')->group(function (): void {
                Route::get('categories', [CategoryController::class, 'index']);
                Route::get('categories/{category}', [CategoryController::class, 'show']);
                Route::get('products', [ProductController::class, 'index']);
                Route::get('products/{product}', [ProductController::class, 'show']);
            });

            Route::middleware('module:orders')->group(function (): void {
                Route::get('orders', [OrderController::class, 'index']);
                Route::get('orders/{order}', [OrderController::class, 'show']);
            });

            Route::middleware('module:cart')->prefix('stores/{store}')->group(function (): void {
                Route::get('cart', [CartController::class, 'show']);
                Route::post('cart/items', [CartController::class, 'addItem']);
            });

            Route::middleware('module:checkout')->prefix('stores/{store}')->group(function (): void {
                Route::post('checkout', [CheckoutController::class, 'store']);
            });

            Route::middleware('module:coupons')->group(function (): void {
                Route::get('coupons', [CouponController::class, 'index']);
            });

            Route::get('modules', [ModuleController::class, 'index']);
            Route::get('modules/status', [ModuleController::class, 'status']);
        });
    });
});
