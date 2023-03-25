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
    // Route::resource('students', UserController::class);

    // Transaction Endpoints
    Route::prefix('payments')
        ->controller(StudentController::class)
        ->group(function(){
        Route::get('', 'payments');
        Route::patch('mark-paid/{id}','mark_paid');
        Route::get('receipt/{id}', 'print_receipt');
        Route::post('pay', 'pay');
    });
    

    // Logut Endpoints
    Route::any('/logout', [AuthController::class, 'logout']);
    Route::any('/logout-all', [AuthController::class, 'logout_all_sessions']);
});
