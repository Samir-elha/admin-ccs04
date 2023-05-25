<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AnnouncementMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $subject;
    public $data;
    public function __construct($student,$subject)
    {
        $this->subject = $subject;
        $this->data = [
          'student' => $student->toArray(),
        ];
    }

    public function build()
    {
        return $this->subject($this->subject)
            ->view('emails.AnnouncementMail',$this->data);
    }
}
