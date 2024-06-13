<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\Register;
use App\Http\Controllers\Api\Auth\Login;
use App\Http\Controllers\Api\emailvarification;

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
Route::get('/post',function(){
    return "Post_Management_Application";
});

Route::post('/register', [Register::class, 'register']);
Route::post('/login', [Login::class, 'login']);

Route::get('email/verify/{token}', [emailvarification::class, 'verify'])->name('emailvarification');
