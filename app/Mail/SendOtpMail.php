<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    // 1. Definisikan properti publik agar bisa dibaca oleh view email
    public $otp;
    public $user;

    /**
     * 2. Terima data OTP dan User lewat constructor
     */
    public function __construct($otp, $user)
    {
        $this->otp = $otp;
        $this->user = $user;
    }

    /**
     * 3. Atur Judul Email (Subject)
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode OTP Reset Password - RENT THE TOOLS',
        );
    }

    /**
     * 4. Hubungkan ke file view yang sudah di buat tadi
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.otp', // Pastikan filenya ada di resources/views/emails/otp.blade.php
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
