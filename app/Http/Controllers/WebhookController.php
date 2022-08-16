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
            // $payment = Payment::with(['accesses.ticket', 'accesses.code', 'event'])->where('reference', $request->input('reference'))->first();
            $payment = Payment::with(['accesses.ticket', 'accesses.code', 'event'])->where('reference', $reference)->first();
            $payment->status = 'payed';
            $payment->save();
            $tickets = Array();
            $folios = Array();
            $quantities = Array();
            $total = $payment->amount;
            $totalTickets = 0;
            $commission = 0;
            $aux = 0;
            $pos = -1;
            $discount = 0;
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
                if (!empty($payment->accesses[$i]->code_id)) {
                    $discount = $discount + ($payment->accesses[$i]->ticket->price * ($payment->accesses[$i]->code->discount / 100));
                }
                $totalTickets = $totalTickets + $payment->accesses[$i]->ticket->price;
                $aux = $payment->accesses[$i]->ticket_id;
            }
            $commission = ($totalTickets - $discount) * .12;
            Mail::to($payment->email)->send(new SendTickets($payment->event, $folios, $tickets, $payment->name, $quantities, $total, $commission, $discount));
        }
        // return return response()->json($data, 200, $headers);
        http_response_code(200); // Return 200 OK
    }

    public function contactanos() {
        return view('contactanos');
    }
    
    public function contact(Request $request) {
        
        if (!empty($request->input('g-recaptcha-response'))) {
            $keySecret = '6Lfb88sbAAAAAIfZ0riQLeAFqnOzMqWypLxPPeRR';
            $token = $request->input('g-recaptcha-response');
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $response = file_get_contents($url.'?secret='.$keySecret.'&response='.$token);
            $response = json_decode($response);
            if ($response->success == true) {
                Mail::to('omar.pulido@maxwellcorp.mx')->send(new SendContact($request->all()));
                //return back()->with(['success' => 'Correo enviado correctamente']);
                return response()->json([
                    'status' => true,
                    'msj' => 'Correo enviado correctamente'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'msj' => 'Error al validar casilla de verificación'
                ]); 
            }
        } else {
            return response()->json([
                'status' => false,
                'msj' => 'Debe marcar la casilla de verificación'
            ]);
        }
        
    }
}
