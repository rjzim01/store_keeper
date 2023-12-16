<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;



Route::get('/', function () {
    return view('1_home');
})->name('home');

Route::get('/products', [ProductController::class, 'showAddProduct'])->name('products.show');

Route::get('/products/add', [ProductController::class, 'showAddProductForm'])->name('products.add');
Route::post('/products/add', [ProductController::class, 'addProduct'])->name('products.store');

Route::get('/products/{product}/update', [ProductController::class, 'updateProduct'])->name('products.update');
Route::put('/products/{product}/update', [ProductController::class, 'updateProductStore'])->name('products.updateStore');

Route::get('/products/{product}/sell', [ProductController::class, 'sellProduct'])->name('products.sell');
Route::post('/products/{product}/sell', [ProductController::class, 'sellProductStore'])->name('products.sellStore');

Route::get('/dashboard', [ProductController::class, 'showDashboard'])->name('dashboard');

Route::get('/transactions', [ProductController::class, 'showTransactionHistory'])->name('transactions');