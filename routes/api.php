<?php


use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Post\PostController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->delete('/logout', [AuthController::class, 'logout']);
Route::get('sendemail/verifyEmail/{token}', [AuthController::class, 'verifyEmail'])->name('emailvarification');
Route::resource('/posts',PostController::class)->middleware('auth:api');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::get('/reset-password', [AuthController::class, 'resetPasswordForm']);
Route::put('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');


