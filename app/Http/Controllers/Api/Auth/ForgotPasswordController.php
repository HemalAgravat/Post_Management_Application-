<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;

use \App\Mail\ForgotPasswordEmail;
use App\Http\Requests\ForgotPasswordRequest;
use App\Models\User;
use App\Traits\JsonResponseTrait;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;



/**
 * Summary of ForgotPasswordController Class
 *
 */
class ForgotPasswordController extends Controller
{
    use JsonResponseTrait;

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
                DB::table('password_reset_tokens')->updateOrInsert(
                    ['email' => $user->email],
                    [
                        'email' => $user->email,
                        'token' => $token,
                        'created_at' => now()
                    ]
                );

                Mail::to($user->email)->send(new ForgotPasswordEmail($token, $user->uuid_column));

                return $this->successResponse(null, 'messages.forgot_password.reset_link_sent');
            }
            return $this->errorResponse($errorMessage, $statusCode);
        } catch (\Exception $th) {
            return $this->errorResponse("error: " . $th->getMessage(), 500);
        }
    }
}
