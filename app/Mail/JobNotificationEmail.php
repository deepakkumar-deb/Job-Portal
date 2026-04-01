<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $maildata;

    /**
     * Create a new message instance.
     */
    public function __construct($maildata)
    {
        $this->maildata = $maildata;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Job Notification Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.job_notification_email', with: [
                'employer' => $this->maildata['employer'],
                'job' => $this->maildata['job'],
                'user' => $this->maildata['user'],
                'applicant_email' => $this->maildata['applicant_email'],
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
