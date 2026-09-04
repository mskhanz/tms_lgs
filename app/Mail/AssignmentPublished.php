<?php

namespace App\Mail;

use App\Models\Assignment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AssignmentPublished extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Assignment $assignment,
        public User $trainee
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Assignment: '.$this->assignment->title.' — '.config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.assignment-published',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
