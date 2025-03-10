<?php

use App\Http\Controllers\Products\ProductAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=> 'products'], function () {
    Route::get("/", [ProductAPIController::class,"index"]);

});