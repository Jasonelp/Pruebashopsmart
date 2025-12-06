<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\AIController;
use App\Http\Controllers\Api\ChatbotController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rutas API RESTful con versionado y rate limiting
|
*/

// Ruta de información del usuario autenticado
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ====================================
// 🔵 API PÚBLICA (con rate limiting)
// ====================================
Route::prefix('v1')->middleware('throttle:60,1')->group(function () {

    // Productos públicos
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);

    // Categorías públicas
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::get('/categories/{id}/products', [CategoryController::class, 'products']);

    // IA endpoints públicos (rate limit más restrictivo)
    Route::middleware('throttle:20,1')->group(function () {
        Route::post('/ai/chat', [AIController::class, 'chat']);
        Route::get('/ai/product/{id}', [AIController::class, 'productAnalysis']);
        Route::post('/ai/vision', [AIController::class, 'vision']);
    });
});

// ====================================
// 🔐 API AUTENTICADA
// ====================================
Route::prefix('v1')->middleware(['auth:sanctum', 'throttle:100,1'])->group(function () {

    // Carrito
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/add/{id}', [CartController::class, 'add']);
        Route::patch('/update/{id}', [CartController::class, 'update']);
        Route::delete('/remove/{id}', [CartController::class, 'remove']);
        Route::delete('/clear', [CartController::class, 'clear']);
    });

    // Órdenes del usuario
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{id}', [OrderController::class, 'show']);
        Route::patch('/{id}/cancel', [OrderController::class, 'cancel']);
    });

    // Reviews
    Route::prefix('reviews')->group(function () {
        Route::post('/products/{productId}', [ReviewController::class, 'store']);
        Route::put('/{id}', [ReviewController::class, 'update']);
        Route::delete('/{id}', [ReviewController::class, 'destroy']);
    });

    // Chatbot IA
    Route::prefix('chat')->group(function () {
        Route::post('/', [ChatbotController::class, 'chat']);
        Route::get('/conversations', [ChatbotController::class, 'conversations']);
        Route::get('/conversations/{conversationId}', [ChatbotController::class, 'history']);
    });
});

// ====================================
// 🟠 API VENDEDOR
// ====================================
Route::prefix('v1/vendor')->middleware(['auth:sanctum', 'vendor', 'throttle:100,1'])->group(function () {

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'vendorIndex']);
        Route::post('/', [ProductController::class, 'store']);
        Route::get('/{id}', [ProductController::class, 'vendorShow']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'vendorIndex']);
        Route::patch('/{id}/status', [OrderController::class, 'updateStatus']);
    });
});

// ====================================
// 🔴 API ADMINISTRADOR
// ====================================
Route::prefix('v1/admin')->middleware(['auth:sanctum', 'admin', 'throttle:150,1'])->group(function () {

    // Productos (control total)
    Route::apiResource('products', ProductController::class);

    // Categorías (control total)
    Route::apiResource('categories', CategoryController::class);

    // Órdenes (todas)
    Route::get('/orders', [OrderController::class, 'adminIndex']);
    Route::get('/orders/{id}', [OrderController::class, 'adminShow']);
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus']);

    // Usuarios
    Route::prefix('users')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\UserController::class, 'index']);
        Route::get('/{id}', [\App\Http\Controllers\Api\UserController::class, 'show']);
        Route::patch('/{id}/role', [\App\Http\Controllers\Api\UserController::class, 'updateRole']);
        Route::post('/{id}/suspend', [\App\Http\Controllers\Api\UserController::class, 'suspend']);
        Route::post('/{id}/unsuspend', [\App\Http\Controllers\Api\UserController::class, 'unsuspend']);
    });

    // Analytics
    Route::get('/analytics', [\App\Http\Controllers\Api\AnalyticsController::class, 'index']);
    Route::get('/analytics/sales', [\App\Http\Controllers\Api\AnalyticsController::class, 'sales']);
    Route::get('/analytics/products', [\App\Http\Controllers\Api\AnalyticsController::class, 'products']);
});
