<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\SendMail;
use App\Models\PasswordResetToken;
use App\Models\User;
use App\Traits\JsonResponseTrait;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    /**
     * Handle Login Request.
     *
     * @param  LoginRequest  $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        // Retrieve email and password from the request
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return $this->errorResponse('messages.error.login.invalid_credentials', 401);
        }

        // Retrieve authenticated user instance
        $user = Auth::user();

        if ($user->status == 0) {
            $errorMessage = 'messages.error.login.user_not_active';
            $statusCode = 403;
        } elseif (is_null($user->email_verified_at)) {
            $errorMessage = 'messages.error.login.email_not_verified';
            $statusCode = 403;
        } else {
            // Create a Login token for the user
            $token = $user->createToken('Login')->accessToken;

            $userData = User::select('id', 'name', 'email', 'created_at', 'updated_at')
                ->where('id', $user->id)
                ->first();

            return $this->successResponse([
                'token' => $token,
                'data' => $userData
            ], 'messages.user.login', 200);
        }

        return $this->errorResponse($errorMessage, $statusCode);
    }
    /**
     * Handle a logout request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if ($user) {
                // Revoke the user's token
                $user->token()->revoke();

                return $this->successResponse(null, 'messages.user.logout', 200);
            } else {
                return $this->errorResponse('messages.error.logout.unauthenticated', 401);
            }
        } catch (\Exception $e) {
            return $this->errorResponse('messages.error.default', 500);
        }
    }

    /**
     * Handle the request for password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        try {

            $request->validated();
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                $errorMessage = 'messages.forgot_password.user_not_found';
                $statusCode = 404;
            } elseif ($user->email_verified_at == null) {
                $errorMessage = 'messages.forgot_password.email_not_verified';
                $statusCode = 400;
            } else {
                $token = Str::random(20);
                PasswordResetToken::updateOrInsert([
                    'email' => $user->email,
                ], [
                    'token' => $token,
                    'created_at' => now(),
                ]);

                $data = [
                    'resetLink' => "http://127.0.0.1:8000/api/reset-password?token=$token&uuid=$user->uuid_column"
                ];
                $view = 'Mail.forgotpasswordemail';
                $subject = 'Reset Password';
                Mail::to($user)->queue(new SendMail($view, $subject, $data));

                return $this->successResponse(null, 'messages.forgot_password.reset_link_sent');
            }
            return $this->errorResponse($errorMessage, $statusCode);
        } catch (\Exception $th) {
            return $this->errorResponse("error:" . $th->getMessage(), 500);
        }
    }

    /**
     * Display the reset password form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function resetPasswordForm(Request $request)
    {
        try {
            $created = PasswordResetToken::where('token', $request->token)->first();
            if (!$created) {
                $errorMessage = 'messages.reset_password.invalid_token';
                $statusCode = 400;
            } else {
                $createdAt = new DateTime($created->created_at);
                $createdAt->modify('+2 hours');
                if (new DateTime() > $createdAt) {
                    $errorMessage = 'messages.reset_password.expired_token';
                    $statusCode = 400;
                } else {
                    $data = $request->all();
                    return view('resetpasswordform', compact('data'));
                }
            }
            return $this->errorResponse($errorMessage, $statusCode);
        } catch (\Throwable $th) {
            return $this->errorResponse('error: ' . $th->getMessage(), 500);
        }
    }

    /**
     * Reset the user's password.
     *
     * @param ResetPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse .
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $request->validated();

            $user = User::where('uuid_column', $request->uuid)->first();
            if (Hash::check($request->password, $user->password)) {
                return $this->errorResponse('messages.reset_password.password_same_as_old', 400);
            }
            $user->password = Hash::make($request->password);
            $user->save();
            PasswordResetToken::where('token', $request->token)->delete();
            return $this->successResponse(null, 'messages.reset_password.password_reset_success', 200);

        } catch (\Throwable $th) {
            return $this->errorResponse('error: ' . $th->getMessage(), 500);
        }
    }
}
