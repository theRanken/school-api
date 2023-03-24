<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::group(['middleware'=>['auth:sanctum']], function(){
    Route::resource('students', UserController::class);
    Route::get('/payments', [StudentController::class, 'payments']);
    Route::patch('/payments/{id}/mark-paid', [StudentController::class, 'mark_paid']);
    Route::get('/payments/{id}/receipt', [StudentController::class, 'print_receipt']);
    Route::post('/payments/pay', [StudentController::class, 'pay']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
