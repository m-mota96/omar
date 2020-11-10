<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendReference extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($event, $reference, $name, $payment)
    {
        $this->event = $event;
        $this->reference = $reference;
        $this->name = $name;
        $this->payment = $payment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->markdown('emails.public.sendReference')->subject('Referencia de pago para el evento '.$this->event->name)
        ->with([
            'event' => $this->event,
            'name' => $this->name
        ]);
        $email->attach('media/pdf/events/'.$this->event->id.'/reference'.$this->payment->id.'.pdf');
        return $email;
    }
}
