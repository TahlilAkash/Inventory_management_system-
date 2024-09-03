<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\TokenVerificationMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::post('/user-registration',[UserController::class,'UserRegistration'])->name('user.registration');
Route::post('/user-login',[UserController::class,'UserLogin'])->name('user.login');

Route::post('/send-otp',[UserController::class,'SendOTPCode']);
Route::post('/verify-otp',[UserController::class,'VerifyOTP']);
Route::post('/reset-password',[UserController::class,'ResetPassword'])->middleware([TokenVerificationMiddleware::class]);

// Page Routes 
Route::get('/userLogin',[UserController::class,'LoginPage'] );
Route::get('/dashboard',[DashboardController::class,'DashboardPage'])->middleware([TokenVerificationMiddleware::class]);
