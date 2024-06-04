<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Response;
use Hash;

class UserController extends Controller
{
    public function register(Request $request): Response
    {
        
        $request->all();

    $verificationToken = Str::random(32);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone_no' => $request->phone_no,
        'password' => Hash::make($request->password),
        'email_verification_token' => $verificationToken,
    ]);

    Mail::to($user->email)->send(new \App\Mail\VerifyEmail($user));

    return response(['message' => 'User registered successfully! Please check your email to verify your account.'],200);

}

public function verifyEmail($token): Response
{
    $user = User::where('email_verification_token', $token)->first();

    if (!$user) {
        return response(['message' => 'Invalid verification token.'], 400);
    }

    $user->status = 'active';
    $user->email_verification_token = null;
    $user->save();

    return response(['message' => 'Email verified successfully!'], 200);
}
}
