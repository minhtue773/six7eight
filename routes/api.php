<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckJWTRefreshable;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => 'api'
], function ($router) {

    Route::get('auth/profile', [AuthController::class, 'profile']);
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::patch('auth/user/{user}', [AuthController::class, 'update']);
    Route::delete('auth/user/{user}', [AuthController::class, 'delete']);
    Route::post('auth/refresh', [AuthController::class, 'refresh']);

    // Users
    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::get('users', [UserController::class, 'index']);
        Route::post('users', [UserController::class, 'store']);
        Route::delete('users/destroy', [UserController::class, 'destroy']);
        Route::delete('users/forcedelete', [UserController::class, 'forceDelete']);
        Route::patch('users/restore', [UserController::class, 'restore']);

        // Route model binding
        Route::get('users/{user}', [UserController::class, 'show']);
        Route::patch('users/{user}', [UserController::class, 'update']);
        Route::delete('users/{user}', [UserController::class, 'delete']);
    });

    // Product_Categories
    Route::get('product-categories', [ProductCategoryController::class, 'index']);
    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::post('product-categories', [ProductCategoryController::class, 'store']);
        Route::get('product-categories/{category}', [ProductCategoryController::class, 'show']);
        Route::patch('product-categories/{category}', [ProductCategoryController::class, 'update']);
        Route::delete('product-categories/{category}', [ProductCategoryController::class, 'delete']);
    });

    // Product Brands
    Route::get('brands', [BrandController::class, 'index']);
    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::post('brands', [BrandController::class, 'store']);
        Route::get('brands/{brand}', [BrandController::class, 'show']);
        Route::patch('brands/{brand}', [BrandController::class, 'update']);
        Route::delete('brands/{brand}', [BrandController::class, 'delete']);
    });

    // Products
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{product}', [ProductController::class, 'show']);

    Route::middleware(['auth:api', AdminMiddleware::class])->group(function () {
        Route::post('products', [ProductController::class, 'store']);


        Route::delete('products', [ProductController::class, 'destroy']); // Xoá mềm nhiều
        Route::delete('products/force-delete', [ProductController::class, 'forceDelete']); // Xoá vĩnh viễn
        Route::patch('products/restore', [ProductController::class, 'restore']); // Khôi phục

        Route::patch('products/{product}', [ProductController::class, 'update']);
        Route::delete('products/{product}', [ProductController::class, 'delete']);
    });


    // Posts
    Route::get('posts', [PostController::class, 'index']);
    Route::get('posts/{post}', [PostController::class, 'show']);
    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::post('posts', [PostController::class, 'store']);
        Route::patch('posts/restore', [PostController::class, 'restore']);
        Route::delete('posts/forcedelete', [PostController::class, 'forceDelete']);

        // Route model binding
        Route::post('posts/{post}', [PostController::class, 'update']);
        Route::delete('posts/{post}', [PostController::class, 'delete']);
    });
});