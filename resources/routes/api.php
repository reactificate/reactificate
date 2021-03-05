<?php

use App\Http\Controller\MainController;
use QuickRoute\Route;

Route::get('test', [MainController::class, 'api']);