<?php

use App\Http\Controllers\Products\ProductController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['prefix'=> 'products', "middleware" => "auth"], function () {
    Route::get("/", [ProductController::class,"index"])->name("products");
    Route::delete("/{product}", [ProductController::class,"destroy"])->name("products.delete");

});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
