<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserRegisterController;


Route::get('/auth/google/login', [UserRegisterController::class, 'googleRedirect']);

Route::get('/', function () {
    return view('welcome');
});
