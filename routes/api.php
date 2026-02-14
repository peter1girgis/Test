<?php

use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\PaymentsController;
use App\Http\Controllers\Api\StripeWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SuccessController;
use App\Http\Controllers\Api\ProductsController;
use App\Http\Controllers\Api\ProductDetailController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderDetailController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\CategoryController;



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

Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/home', [HomeController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/checkout', [CheckoutController::class, 'placeOrder']);
    // Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('api.checkout.success');
    Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('api.checkout.cancel');
});

// add cart table before add apis cart cantrol
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::delete('/cart/{product_id}', [CartController::class, 'destroy']);
    Route::patch('/cart/{product_id}/increase', [CartController::class, 'increaseQty']);
    Route::patch('/cart/{product_id}/decrease', [CartController::class, 'decreaseQty']);
    Route::delete('/cart-clear', [CartController::class, 'clear']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index']);
    Route::post('/checkout', [CheckoutController::class, 'placeOrder']);
    Route::get('/Payment/{payment_id}/{orderId}',[PaymentsController::class,'store'])->name('api.payment');
});
Route::group(['prefix' => 'orders', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::get('/{order_id}/details', [OrderDetailController::class, 'show']);
});


Route::get('/products/{slug}', [ProductDetailController::class, 'show']);
Route::post('/products/{id}/add-to-cart', [ProductDetailController::class, 'addToCart']);


Route::get('/products', [ProductsController::class, 'index']);

Route::middleware('auth:sanctum')->get('/checkout/success', [SuccessController::class, 'success'])->name('api.checkout.success');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

