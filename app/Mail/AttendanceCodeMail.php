<?php

namespace App\Mail;

use App\Models\AttendanceCode;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AttendanceCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public AttendanceCode $attendanceCode
    ) {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Attendance Code: '.$this->attendanceCode->code.' - '.$this->attendanceCode->classSession->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.attendance-code',
            with: [
                'code' => $this->attendanceCode->code,
                'sessionName' => $this->attendanceCode->classSession->name,
                'sessionTime' => $this->attendanceCode->classSession->time_range,
                'date' => $this->attendanceCode->date->format('F d, Y'),
                'expiresAt' => $this->attendanceCode->expires_at->format('g:i A'),
            ],
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
