<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/admin/register', [AuthController::class, 'registerAdmin']);