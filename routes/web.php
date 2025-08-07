<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::view('/register-page', 'auth.register');
Route::view('/login-page', 'auth.login');

