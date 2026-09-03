<?php

namespace App\Mail;

use App\Models\TrainingEnrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnrollmentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $enrollment;

    public function __construct(TrainingEnrollment $enrollment)
    {
        $this->enrollment = $enrollment;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Training Enrollment Confirmation - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.enrollment-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
