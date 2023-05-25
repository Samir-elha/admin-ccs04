<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $data;

    public function __construct($student, $subject, $password)
    {
        $this->subject = $subject;
        $this->data = [
            'student' => $student->toArray(),
            'password' => $password,
        ];
    }

    public function build()
    {
        return $this->subject($this->subject)
            ->view('emails.resetPassword', $this->data);
    }
}
