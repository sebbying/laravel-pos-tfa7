<?php
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
Route::get('/', fn () => redirect()->route('customers.index'));
Route::resource('customers', CustomerController::class)->only(['index', 'create', 'store', 'edit', 'update']);
Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'edit', 'update']);
