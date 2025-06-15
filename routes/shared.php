<?php

use App\Http\Controllers\TriviaController;
use Illuminate\Support\Facades\Route;



Route::prefix('v1')->group(function () {
    Route::get('/',  [TriviaController::class,'index'])->name('index');
    Route::post('/', [TriviaController::class,'create'])->name('create');
   
});