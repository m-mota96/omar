<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendTickets extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($event, $folios, $tickets, $buyer, $quantities, $total)
    {
        $this->event = $event;
        $this->folios = $folios;
        $this->tickets = $tickets;
        $this->buyer = $buyer;
        $this->quantities = $quantities;
        $this->total = $total;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->markdown('emails.public.sendTickets')->subject('Boletos para el evento '.$this->event->name)
        ->with([
            'event' => $this->event,
            'buyer' => $this->buyer,
            'folios' => $this->folios,
            'tickets' => $this->tickets,
            'quantities' => $this->quantities,
            'total' => $this->total
        ]);
        // $email = $this->markdown('emails.customer.paymentstore')->subject('Pago completado')->with([
        //     'name' => $this->data['name'],
        //     'img' => $this->data['qr'],
        //     'price' => $this->data['price'],
        //     'addresses' => '<span>Su cita fue generada para asistir a la sucursal ubicada en:<br>'.$this->data['address'].'</span>'
        // ]);
        for ($i = 0; $i < sizeof($this->folios); $i++) {
            $email->attach('media/pdf/events/'.$this->event->id.'/'.$this->folios[$i]['folio'].'.pdf');
        }
        return $email;
    }
}
