<?php

use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\User\UserFavoriteController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Order\OrderItems\OrderItemController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;







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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Categories
Route::resource('categories', CategoryController::class)->except(['create', 'edit']);

// Products
Route::resource('products', ProductController::class)->except(['create', 'edit']);


// Orders
Route::resource('orders', OrderController::class)->except(['create', 'edit']);

// Order Items
Route::resource('orders.orderItems', OrderItemController::class)->except(['create', 'edit', 'show']);

// Favorites
Route::resource('users.favorites', UserFavoriteController::class)->except(['create', 'edit', 'show', 'update']);

// Users
Route::resource('users', UserController::class)->except(['create', 'edit']);
Route::get('users/verify/{token}', [UserController::class, 'verify'])->name('users.verify');
Route::get('users/{user}/resend', [UserController::class, 'resendEmail'])->name('users.resend');
