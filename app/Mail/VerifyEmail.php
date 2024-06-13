<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message | View email blade file | Expiration time.
     */
    public function build()
    {
        $verificationUrl = URL::temporarySignedRoute(
            'emailvarification', now()->addMinutes(5), ['token' => $this->user->email_verification_token]
        );

        return $this->view('Mail.email')
            ->with([
                'user' => $this->user,
                'verificationUrl' => $verificationUrl,
            ]);
    }
}
