<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;

Route::resource('posts', PostController::class);
Route::resource('categories', CategoryController::class);
