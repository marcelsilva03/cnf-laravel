<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ContatoMail extends Mailable
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject($this->data['assunto'])
                    ->view('emails.contato')
                    ->with('dados', $this->data);
    }
}
