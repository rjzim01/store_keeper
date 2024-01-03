<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


Route::get('/dekho', function () {
    return view('dekho.index');
})->name('dekho');

Route::get('/home1', function () {
    return view('1_home');
})->name('homeone');

Route::get('/', [ProductController::class, 'showDashboard'])->name('home');

Route::get('/products', [ProductController::class, 'showAddProduct'])->name('products.show');

Route::get('/add', [ProductController::class, 'showAddProductForm'])->name('products.add');
Route::post('/add', [ProductController::class, 'addProduct'])->name('products.store');

Route::get('/products/{product}/update', [ProductController::class, 'updateProduct'])->name('products.update');
Route::put('/products/{product}/update', [ProductController::class, 'updateProductStore'])->name('products.updateStore');

Route::get('/products/{product}/sell', [ProductController::class, 'sellProduct'])->name('products.sell');
Route::post('/products/{product}/sell', [ProductController::class, 'sellProductStore'])->name('products.sellStore');

Route::get('/dashboard', [ProductController::class, 'showDashboard'])->name('dashboard');


Route::get('/transactions', [ProductController::class, 'showTransactionHistory'])->name('transactions');