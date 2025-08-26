<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('nour');
});


Route::view('/register-page', 'auth.register');
Route::view('/login-page', 'auth.login');


Route::get('/users', function () {
    $users = User::all();
    return view('users.index', compact('users'));
});
