<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('index');

Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegister')->name('show.register');
    Route::get('/login', 'showLogin')->name('show.login');
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])
    ->controller(ProductController::class)
    ->group(function () {
        Route::get('/products', 'index')->name('products.index');
        Route::post('/products', 'store')->name('products.store');
        Route::put('/products/{id}/category', 'addCategory')->name('products.addCategory');
        Route::post('/products/{id}/image', 'uploadImage')->name('products.uploadImage');
        Route::delete('/products/image/{id}', 'deleteImage')->name('products.deleteImage');
        Route::delete('/products/{id}/category/{categoryId}', 'deleteCategory')->name('products.deleteCategory');
        Route::delete('/products/{id}', 'delete')->name('products.delete');
    });

Route::middleware(['auth'])
    ->controller(CategoryController::class)
    ->group(function () {
        Route::get('/categories', 'index')->name('categories.index');
        Route::post('/categories', 'store')->name('categories.store');
        Route::delete('/categories/{id}', 'delete')->name('categories.delete');
    });
