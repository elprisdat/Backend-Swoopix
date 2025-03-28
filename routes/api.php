<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\MenuController;
use App\Http\Controllers\Api\V1\StoreController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\VoucherController;
use App\Http\Controllers\Api\V1\OrderItemController;
use App\Http\Controllers\Api\V1\LocationController;
use App\Http\Controllers\Api\V1\WeatherController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// API V1 Routes
Route::prefix('v1')->group(function () {
    // Auth Routes
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/verify-otp', [UserController::class, 'verifyOtp']);
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/logout', [UserController::class, 'logout']);

    // Location & Weather Routes
    Route::post('/location', [LocationController::class, 'updateLocation']);
    Route::get('/weather', [WeatherController::class, 'getCurrentWeather']);
    Route::get('/recommendations/weather', [WeatherController::class, 'getWeatherBasedRecommendations']);

    // Category Routes
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/active', [CategoryController::class, 'active']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::post('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
    Route::post('/categories/{id}/toggle-active', [CategoryController::class, 'toggleActive']);

    // Menu Routes
    Route::get('/menus/weather-recommendations', [MenuController::class, 'weatherRecommendations']);
    Route::get('/menus', [MenuController::class, 'index']);
    Route::get('/menus/available', [MenuController::class, 'available']);
    Route::get('/menus/search', [MenuController::class, 'search']);
    Route::get('/menus/price-range', [MenuController::class, 'priceRange']);
    Route::get('/menus/category/{categoryId}', [MenuController::class, 'byCategory']);
    Route::get('/menus/category/{categoryId}/available', [MenuController::class, 'availableByCategory']);
    Route::get('/menus/{id}', [MenuController::class, 'show']);
    Route::post('/menus', [MenuController::class, 'store']);
    Route::post('/menus/{id}', [MenuController::class, 'update']);
    Route::delete('/menus/{id}', [MenuController::class, 'destroy']);
    Route::post('/menus/{id}/toggle-available', [MenuController::class, 'toggleAvailable']);

    // Store Routes
    Route::prefix('stores')->group(function () {
        Route::get('/', [StoreController::class, 'index']);
        Route::get('/nearby', [StoreController::class, 'nearby']);
        Route::get('/{id}', [StoreController::class, 'show']);
        Route::post('/', [StoreController::class, 'store']);
        Route::post('/{id}', [StoreController::class, 'update']);
        Route::delete('/{id}', [StoreController::class, 'destroy']);
        Route::post('/{id}/toggle-open', [StoreController::class, 'toggleOpen']);
    });

    // Order Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::post('/orders/{id}/status', [OrderController::class, 'updateStatus']);
        Route::post('/orders/{id}/payment-status', [OrderController::class, 'updatePaymentStatus']);
        Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel']);
    });

    // Payment Routes
    Route::get('/payment-channels', [PaymentController::class, 'getPaymentChannels']);
    Route::post('/orders/{order}/pay', [PaymentController::class, 'createPayment']);
    Route::post('/payment/callback', [PaymentController::class, 'handleCallback']);

    // Voucher Routes
    Route::get('/vouchers', [VoucherController::class, 'index']);
    Route::get('/vouchers/active', [VoucherController::class, 'active']);
    Route::get('/vouchers/{id}', [VoucherController::class, 'show']);
    Route::post('/vouchers', [VoucherController::class, 'store']);
    Route::post('/vouchers/{id}', [VoucherController::class, 'update']);
    Route::delete('/vouchers/{id}', [VoucherController::class, 'destroy']);
    Route::post('/vouchers/{id}/toggle-active', [VoucherController::class, 'toggleActive']);
    Route::post('/vouchers/validate', [VoucherController::class, 'validate']);

    // Order Item Routes
    Route::post('/orders/{orderId}/items', [OrderItemController::class, 'store']);
    Route::post('/orders/{orderId}/items/{itemId}', [OrderItemController::class, 'update']);
    Route::delete('/orders/{orderId}/items/{itemId}', [OrderItemController::class, 'destroy']);
}); 