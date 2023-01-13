<?php

namespace App\Http\Controllers;

require_once('bin/messagebird/autoload.php');
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\SendTickets;
use Hashids\Hashids;
use App\Payment;
use App\Event;
use App\Ticket;
use App\Access;
use App\Code;

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
                    $ticketId = $payment->accesses[$i]->ticket->id;
                    $code = Code::with(['tickets' => function($query) use($ticketId) {
                        $query->where('ticket_id', $ticketId);
                    }])
                    ->whereHas('tickets', function($query) use($ticketId) {
                        $query->where('ticket_id', $ticketId);
                    })
                    ->where('id', $payment->accesses[$i]->code_id)->first();
                    $code->tickets()->detach([$code->id, $code->tickets[0]->id]);
                    $code->tickets()->attach($code->id, [
                        'ticket_id' => $code->tickets[0]->id,
                        'used' => $code->tickets[0]->pivot->used + 1,
                        'reserved' => $code->tickets[0]->pivot->reserved - 1
                    ]);
                    $discount = $discount + ($payment->accesses[$i]->ticket->price * ($payment->accesses[$i]->code->discount / 100));
                }
                $totalTickets = $totalTickets + $payment->accesses[$i]->ticket->price;
                $aux = $payment->accesses[$i]->ticket_id;
            }
            $commission = ($totalTickets - $discount) * .12;
            $this->sendWhatsapp($payment);
            Mail::to($payment->email)->send(new SendTickets($payment->event, $folios, $tickets, $payment->name, $quantities, $total, $commission, $discount));
        }
        // return response()->json([
        //     'data' => $payment
        // ]);
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

    public function sendWhatsapp($payment) {
        $hashids = new Hashids('', 25); // pad to length 10
        $paymentId = $hashids->encode($payment->id);
        // print_r($id);
        // $idDecode = $hashids->decode($id);
        // dd($idDecode[0]);
        $messageBird = new \MessageBird\Client('F4JCaJDSSNBJPUkzcLBQScb7i'); // Set your own API access key here.

        $hsmParam1 = new \MessageBird\Objects\Conversation\HSM\Params();
        $hsmParam1->default = $payment->name;

        $hsmParam2 = new \MessageBird\Objects\Conversation\HSM\Params();
        $hsmParam2->default = $payment->event->name;

        $hsmParam3 = new \MessageBird\Objects\Conversation\HSM\Params();
        $hsmParam3->default = asset('').'download/tickets/'.$paymentId;

        $hsmLanguage = new \MessageBird\Objects\Conversation\HSM\Language();
        $hsmLanguage->policy = \MessageBird\Objects\Conversation\HSM\Language::DETERMINISTIC_POLICY;
        $hsmLanguage->code = 'es_MX';

        $hsm = new \MessageBird\Objects\Conversation\HSM\Message();
        $hsm->templateName = 'payment_success2';
        $hsm->namespace = '761b9b95_04f0_4ea9_9fa2_547e0efdb21c';
        $hsm->params = [$hsmParam1, $hsmParam2, $hsmParam3];
        $hsm->language = $hsmLanguage;

        $content = new \MessageBird\Objects\Conversation\Content();
        $content->hsm = $hsm;

        $message = new \MessageBird\Objects\Conversation\Message();
        $message->channelId = 'af4120f7-4c93-4aec-b4fa-d5e3335470c1';
        $message->content = $content;
        $message->to = '521'.$payment->phone;
        $message->type = 'hsm';

        try {
            $conversation = $messageBird->conversations->start($message);

            // dd($conversation);
        } catch (\Exception $e) {
            // dd('error: '.$e->getMessage());
        }
        return true;
    }
}
