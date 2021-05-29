<?php

use App\Http\Controller\MainController;
use QuickRoute\Route;

Route::get('/', [MainController::class, 'index']);
Route::get('/chat', [MainController::class, 'chat']);
Route::get('/middleware', [MainController::class, 'index'])->middleware('test');