<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Mail\VerifyEmail;
use App\Models\User;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Class Register
 *
 * Controller for handling user registration.
 */
class Register extends Controller
{
    use JsonResponseTrait;

    /**
     * Handle a registration request.
     *
     * @param  RegisterRequest  $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        // Validate the incoming request
        $validator = validator($request->all());

        // If validation fails, return validation error response
        if ($validator->fails()) {
            return $this->validationError($validator, 'messages.validation', 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'profile_url' => $request->profile_url,
                'phone_no' => $request->phone_no,
                'password' => Hash::make($request->password),
                'email_verification_token' => Str::random(10),
            ]);

            // Send email verification mail to the user
            Mail::to($user->email)->send(new VerifyEmail($user));

            $userData = User::select('id', 'name', 'email', 'profile_url', 'phone_no', 'created_at', 'updated_at')
                ->where('id', $user->id)
                ->first();

            return $this->successResponse($userData, 'messages.success.register');
        } catch (\Exception $e) {
            return $this->errorResponse('messages.error.default', 500);
        }
    }
}
