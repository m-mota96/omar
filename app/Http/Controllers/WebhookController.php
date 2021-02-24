<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\SendTickets;
use App\Payment;
use App\Event;
use App\Ticket;
use App\Access;

class WebhookController extends Controller
{
    public function __construct() {
        date_default_timezone_set('America/Mexico_City');
        setlocale(LC_ALL, 'es_ES');
    }

    public function reference_paid(Request $request) {
        $body = @file_get_contents('php://input');
        $data = json_decode($body);
        if ($data->type == 'charge.paid') {
            $reference = $data->data->object->payment_method->reference;
            // $payment = Payment::with(['accesses.ticket', 'event'])->where('reference', $request->input('reference'))->first();
            $payment = Payment::with(['accesses.ticket', 'event'])->where('reference', $reference)->first();
            $payment->status = 'payed';
            $payment->save();
            $tickets = Array();
            $folios = Array();
            $quantities = Array();
            $total = $payment->amount;
            $aux = 0;
            $pos = -1;
            for ($i = 0; $i < sizeof($payment->accesses); $i++) {
                $folios[$i]['folio'] = $payment->accesses[$i]->folio;
                $folios[$i]['ticket_id'] = $payment->accesses[$i]->ticket_id;
                if ($payment->accesses[$i]->ticket_id != $aux) {
                    $pos++;
                    $tickets[$pos]['name'] = $payment->accesses[$i]->ticket->name;
                    $tickets[$pos]['price'] = $payment->accesses[$i]->ticket->price;
                    $quantities[$pos] = 1;
                } else {
                    $quantities[$pos] = $quantities[$pos] + 1;
                }
                $aux = $payment->accesses[$i]->ticket_id;
            }
            Mail::to($payment->email)->send(new SendTickets($payment->event, $folios, $tickets, $payment->name, $quantities, $total));
        }
        // return return response()->json($data, 200, $headers);
        http_response_code(200); // Return 200 OK
    }
}
