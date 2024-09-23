<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index')->name('products.index');
    Route::get('/products/create', 'create')->name('products.create');
    Route::post('/products', 'store')->name('products.store');
    Route::get('/products/{products}/edit', 'edit')->name('products.edit');
    Route::put('/products/{products}', 'update')->name('products.update');
    Route::delete('/products/{products}', 'destroy')->name('products.destroy');

});