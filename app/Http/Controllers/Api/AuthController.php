<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Mail\SendMail;
use App\Models\User;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class AuthController
 *
 * Controller for handling user Request.
 */
class AuthController extends Controller
{

    use JsonResponseTrait;
    /**
     * Handle a registration request.
     *
     * @param  AuthRequest  $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $validator = Validator::make($request->all(), $request->rules(), $request->messages());

        if ($validator->fails()) {
            return $this->validationError($validator, 'validations.failed', 422);
        } else {
            try {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'profile_url' => $request->profile_url,
                    'phone_no' => $request->phone_no,
                    'password' => Hash::make($request->password),
                    'email_verification_token' => Str::random(10),
                ]);

                $data = [
                    'name' => $request->name,
                    'token' => $user->email_verification_token
                ];

                $this->sendVerificationEmail('Mail.email', $request->email, $data);

                $userData = User::select('id', 'name', 'email', 'profile_url', 'phone_no', 'created_at', 'updated_at')
                    ->where('id', $user->id)
                    ->first();

                return $this->successResponse($userData, 'messages.user.register');
            } catch (\Exception $e) {
                return $this->errorResponse('messages.error.default', 500);
            }
        }
    }

    /**
     * Send verification email to the user.
     *
     * @param  User  $user
     */
    public function sendVerificationEmail($view, $user, $data)
    {
        $subject = 'Please verify your email';
        // Queue the email
        Mail::to($user)->queue(new SendMail($view, $subject, $data));
    }

    /**
     * email verification.
     *
     * @param  Request  $request
     * @param  string  $token
     * @return JsonResponse
     */
    public function verifyEmail($token)
    {

        try {
            // Find the user with the given email verification token
            $user = User::where('email_verification_token', $token)->firstOrFail();

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Invalid verification token'], 500);
            } else {
                $user->email_verified_at = now();
                $user->email_verification_token = null;
                $user->status = '1';
                $user->save();

                return response()->json(['success' => true, 'message' => 'Email verified successfully.']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Verification failed: ' . $e->getMessage()], 500);
        }
    }

}
