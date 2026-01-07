<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::prefix('/contact')->group(function () {
   Route::get('/', function () {
    return view('contact');
   })->name('contact.form');
   Route::post('/', function (Request $request) {
    $name=$request->input('name');
    $email=$request->input('email');
    return "
    <h2>Thông tin người dùng</h2>
    <p><strong>Tên: </strong> $name </p>
    <p><strong>Email: </strong> $email  </p>
    ";
    
   })->name('contact.submit');
});
