<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewUser extends Mailable
{
    use Queueable, SerializesModels;
    private $data;
    private $passw;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $passw)
    {
        $this->data = $data;
        $this->passw = $passw;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('robot@letsfly.ru', 'Robot LetsFly')
            ->subject('Регистрация на сайте letsfly.ru')
            ->view('emails.register', [
                'name' => $this->data['name'],
                'email' => $this->data['email'],
                'passw' => $this->passw
            ]);
    }
}
