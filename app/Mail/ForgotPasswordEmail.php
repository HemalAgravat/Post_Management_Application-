<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Summary of ForgotPasswordEmail
 *
 */
class ForgotPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $token;
    public $uuid;
    public $resetLink;

    /**
     * Create a new message instance.
     */
    public function __construct($token, $uuid)
    {
        $this->token = $token;
        $this->uuid = $uuid;
        $this->resetLink = 'http://127.0.0.1:8000/api/reset-password?token=' . $this->token . '&uuid=' . $this->uuid;
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Forgot Password Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'Mail.forgotpasswordemail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
