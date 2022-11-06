<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $contactInfo)
    {
        //
    }

    public function build()
    {

        return $this->from($this->contactInfo['email'], $this->contactInfo['name'])
            ->subject('お問い合わせがありました')
            ->text('emails.contact.admin');
    }
}
