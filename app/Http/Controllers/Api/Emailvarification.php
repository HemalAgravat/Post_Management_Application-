<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class Emailvarification extends Controller
{
    /**
     * Handle email verification.
     *
     * @param  Request  $request
     * @param  string  $token
     * @return JsonResponse
     */
    public function verify(Request $request, $token)
    {
        // Check if the request has a valid signature
        if (!$request->hasValidSignature()) {
            return $this->resendVerificationLink($token);
        }

        try {
            // Find the user with the given email verification token
            $user = User::where('email_verification_token', $token)->firstOrFail();
            $user->email_verified_at = now();
            $user->email_verification_token = null;
            $user->status = '1';
            $user->save();

            return response()->json(['success' => true, 'message' => 'Email verified successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Verification failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Resend the verification link.
     *
     * @param  string  $token
     * @return JsonResponse
     */
    private function resendVerificationLink($token)
    {
        try {
            // Find the user with the given email verification token
            $user = User::where('email_verification_token', $token)->firstOrFail();
            $user->email_verification_token = Str::random(10);
            $user->save();

            // Send a new verification email
            Mail::to($user->email)->send(new VerifyEmail($user));

            return response()->json(['success' => false, 'message' => 'Invalid or expired verification link. A new verification link has been sent to your email.'], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to resend verification link: ' . $e->getMessage()], 500);
        }
    }
}
