<?php

namespace App\Http\Controllers;

require_once('bin/conekta-php-master/lib/Conekta.php');
require_once('bin/messagebird/autoload.php');
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Mail\SendReference;
use App\Mail\SendTickets;
use Carbon\Carbon;
use Hashids\Hashids;
use App\Event;
use App\Ticket;
use App\Payment;
use App\Access;
use App\Turn;
use App\Response;
use App\Code;
use DateTime;
use DateInterval;
use PDF;

class PublicController extends Controller
{
    //Produccion
    // private $ApiKey = 'key_qQq2oPx6Dvq7KmXTqgQLsQ';
    //Pruebas
    private $ApiKey = 'key_qm551MUqq4Ra5WasNCxJAw';
    private $ApiVersion = '2.0.0';

    public function __construct() {
        date_default_timezone_set('America/Mexico_City');
        setlocale(LC_ALL, 'es_ES');
    }

    public function index($event, $ticket = null) {
        
        $data = Event::with(['profile', 'eventDates.turns', 'location', 'tickets.questions'])->where(DB::raw('BINARY url'), $event)->first();
        // dd($data);
        if (!empty($data)) {
            $data->initial_date = Carbon::parse($data->eventDates[0]->date)->locale('es')->isoFormat('D MMM Y').' - '.substr($data->eventDates[0]->initial_time, 0, 5);
            $pos = sizeof($data->eventDates) - 1;
            $data->final_date = Carbon::parse($data->eventDates[$pos]->date)->locale('es')->isoFormat('D MMM Y').' - '.substr($data->eventDates[$pos]->final_time, 0, 5);
            return view('public.event')->with(['event' => $data, 'ticket' => $ticket]);

        } else {
            return redirect('/');
        }
    }

    public function makePayment(Request $request) {

        //$request->quantities= explode(",",$request->quantities);
        //dd($request->quantities);
        //$request->input('turns')=$aux;
        // dd($request->all());
        // dd(json_decode($request->input('tickets')));
        $event = Event::with(['profile', 'eventDates', 'location'])->where('id', $request->input('event_id'))->first();
        $initial_date = Carbon::parse($event->eventDates[0]->date)->locale('es')->isoFormat('D-MM-Y');
        $pos = sizeof($event->eventDates) - 1;
        $final_date = Carbon::parse($event->eventDates[$pos]->date)->locale('es')->isoFormat('D-MM-Y');
        if (isset($event->location->name)) {
            $address = $event->location->name;
        } else {
            $address = '';
        }

        $tickets = Array();
        $folios = Array();
        $total = 0;
        $discountCodesVal = 0;
        $pos = 0;
        $pos2 = 0;
        if (!file_exists('media/pdf/events/'.$event->id)) {
            mkdir('media/pdf/events/'.$event->id, 0777, true);
        }
        $msj = null;
        if ($event->cost_type == 'paid' && $request->input('payment_method') == 'free') {
            $msj = 'Debe elegir un método de pago válido';
        }
        // for ($i = 0; $i < sizeof($request->input('turns')); $i++) {
            if (!empty($request->input('turns'))) {
                for ($j = 0; $j < sizeof($request->input('turns')); $j++) { 
                    $turn = Turn::with(['eventDate'])->where('id', $request->input('turns')[$j])->first();
                    $available = $turn->quantity - $turn->used;
                    if ($request->input('quantities')[$j] <= $available) {

                    } else {
                        if ($available == 0) {
                            $text = 'El turno "'.$turn->name.'" se agotó<br>';
                        } else {
                            $text = 'Solo quedan '.$available.' espacios en el turno "'.$turn->name.'"<br>';
                        }
                        $msj .= '<div class="text-left">
                                    <b>'.str_replace('.', '', Carbon::parse($turn->eventDate->date)->locale('es')->isoFormat('D-MMM-Y')).'</b><br>
                                    '.$text.'
                                </div>';
                    }
                }
            }
        // }
        if (!empty($msj)) {
            return response()->json([
                'status' => false,
                'error' => $msj
            ]);
        }
        // dd('STOP');
        
        $globlaDataOrder = $request->input('globlaDataOrder');
        $infoTickets= $globlaDataOrder['infoTickets'];
        if ($request->input('indicatorCodes') == 'true') {
            for ($i = 0; $i < sizeof($request->input('codes')); $i++) {
                $codeAux = $request->input('codes')[$i];
                $code = Code::with(['tickets'])
                ->where('code', strtoupper($request->input('codes')[$i]['code']))
                ->where('expiration', '>=', date('Y-m-d'))
                ->whereHas('tickets', function($query) use($codeAux, $event) {
                    $query->where('ticket_id', $codeAux['ticket_id'])->where('event_id', $event->id);
                })->first();
                if (!empty($code)) {
                    $sales = 0;
                    for ($j = 0; $j < sizeof($code->tickets); $j++) { 
                        $sales = $sales + ($code->tickets[$j]->pivot->used + $code->tickets[$j]->pivot->reserved);
                        if ($codeAux['ticket_id'] == $code->tickets[$j]->id) {
                            $code->ticket = $code->tickets[$j];
                        }
                    }
                    if ($request->input('codes')[$i]['quantity'] <= ($code->quantity - $sales)) {
                        $discountCodesVal = $discountCodesVal + (($request->input('codes')[$i]['quantity'] * $code->ticket->price) * ($code->discount / 100));
                    } else if (($request->input('codes')[$i]['quantity'] > ($code->quantity - $sales)) && (($code->quantity - $sales) > 0)) {
                        return response()->json([
                            'status' => false,
                            'error' => 'codesIncomplete',
                            'msj' => $msj
                        ]);
                    } else if ($code->quantity - $sales == 0 && $request->input('codes')[$i]['quantity'] != 0) {
                        return response()->json([
                            'status' => false,
                            'error' => 'codesAgoted',
                            'msj' => $msj
                        ]);
                    }
                }
            }
        }
        
        for ($i = 0; $i < sizeof($request->input('quantities')); $i++) {
            if ($request->input('quantities')[$i] > 0) {
                $ticket = Ticket::select('id', 'name', 'description', 'price', 'quantity', 'sales', 'valid', 'promotion', 'date_promotion', 'status')->where('id', $request->input('tickets')[$i])->first();
                                
                if ($ticket->quantity == $ticket->sales) {
                    return response()->json([
                        'status' => false,
                        'error' => 'expired',
                        'msj' => 'Lo sentimos se agotaron los bolteos '.$ticket->name
                    ]);
                } else if (($ticket->quantity - $ticket->sales) < $request->input('quantities')[$i]) {
                    return response()->json([
                        'status' => false,
                        'error' => 'exceeded',
                        'msj' => 'No es posible comprar '.$request->input('quantities')[$i].' '.$ticket->name.'<br>solo quedan '.($ticket->quantity - $ticket->sales).' disponibles'
                    ]);
                }
                $ticket->sales = $ticket->sales + $request->input('quantities')[$i];
                if ($ticket->quantity == $ticket->sales) {
                    $ticket->status = 0;
                }
                $ticket->save();
                if (!empty($ticket->promotion) && !empty($ticket->date_promotion)) {
                    if (date('Y-m-d') <= $ticket->date_promotion) {
                        $ticket->price = $ticket->price - ($ticket->price * ($ticket->promotion / 100));
                    }
                }
                
                $tickets[$i]['name'] = $ticket->name;
                
                // $tickets[$i]['description'] = $ticket->description;
                $tickets[$i]['price'] = $ticket->price;
                $total = $total + ($ticket->price * $request->input('quantities')[$i]);
                if (isset($request->input('turns')[$i])) {
                    for ($j = 0; $j < sizeof($request->input('turns')[$i]); $j++) { 
                        $folios[$pos2]['turn'] = $request->input('turns')[$i];
                        $pos2++;
                    }
                }
                
                try {
                    
                    $ticket->img_event = asset('media/events/'.$event->id.'/'.$event->profile->name);
                    
                    for ($j = 0; $j < $request->input('quantities')[$i]; $j++) {
                        $folio = strtoupper(uniqid());
                        $code_QR = QrCode::backgroundColor(255, 125, 0, 0.5)->size(550)->format('svg')->generate($folio);
                        $ticket->qr = base64_encode($code_QR);
                        $ticket->initial_date = $initial_date;
                        $ticket->final_date = $final_date;
                        $ticket->address = $address;
                        $ticket->eventName = $event->name;
                        $pdf = PDF::loadView('pdfTicket', $ticket);
                        $pdf->save('media/pdf/events/'.$event->id.'/'.$folio.'.pdf');
                        $folios[$pos]['folio'] = $folio;
                        $folios[$pos]['ticket_id'] = $ticket->id;
                        $folios[$pos]['ticket_price'] = $ticket->price;
                        $folios[$pos]['valid'] = $ticket->valid;
                        $pos++;
                    }
                } catch(\Exception $e) {
                    // $this->deleteFiles($folios);
                    return response()->json([
                        'status' => false,
                        'error' => 'Error al generar pdf: '.$e->getMessage()
                    ]);
                }
            } else {
                $tickets[$i]['name'] = null;
                $tickets[$i]['price'] = null;
            }
        }
        // dd('STOP');
        
        \Conekta\Conekta::setApiKey($this->ApiKey);
        \Conekta\Conekta::setApiVersion($this->ApiVersion);
        

        // Registra por metodo de pago los boletos 
        $commission=0;
        $total = $total - $discountCodesVal;
        if ($event->model_payment == 'separated') {
            $commission = ($total * 0.12);
            $total = $total + $commission;
        }else{
            $commission=0;
            $total = $total + $commission;
        }

        if ($request->input('payment_method') == 'card') {
            
            //Proceso de pago
            $customer = $this->createCustomer($request->input('name'), $request->input('email'), $request->input('conektaTokenId'));
            if ($customer['status'] == true) {
                $order = $this->createOrder($total, 'Compra de boletos para '.$event->name, 1);
                if ($order['status'] == true) {
                    if ($request->input('indicatorCodes') == 'true') {
                        $this->discountCodes($request->input('codes'), 'card', $event->id);
                    }
                    // Se registra en nuestra BDD la información de los pagos
                    $payment = $this->registerPayment($event->id, $request->input('name'), substr($request->input('card'), -4), 'card', $request->input('email'), 'payed', $total, $request->input('phone'));
                    $this->saveAccesses($payment, $folios,$infoTickets);
                } else {
                    // $this->deleteFiles($folios);
                    return response()->json([
                        'status' => false,
                        'error' => $order['msj']
                    ]);
                }
            } else {
                // $this->deleteFiles($folios);
                return response()->json([
                    'status' => false,
                    'error' => $customer['msj']
                ]);
            }
        } else if ($request->input('payment_method') == 'oxxo') {
            
            $order = $this->createOrderOxxo($total, 'Compra de boletos para '.$event->name, $request->input('name'), $request->input('email'), $request->input('phone'), 1);
            if($order['status'] == true) {
                if ($request->input('indicatorCodes') == 'true') {
                    $this->discountCodes($request->input('codes'), 'oxxo', $event->id);
                }
                $payment = $this->registerPayment($event->id, $request->input('name'), $order['reference'], 'oxxo', $request->input('email'), 'pending', $total, $request->input('phone'));
                $date = Carbon::now();
                $expiration = Carbon::parse($date->addDays(2)->format('Y-m-d'))->locale('es')->isoFormat('D MMMM Y');
                $dataReference = array(
                    'reference' => $order['reference'],
                    'monto' => $total,
                    'name_event' => $event->name,
                    'expiration' => $expiration
                );
                $pdf = PDF::loadView('pdfOxxo', $dataReference);
                if (!file_exists('media/pdf/events/'.$event->id)) {
                    mkdir('media/pdf/events/'.$event->id, 0777, true);
                }
                $pdf->save('media/pdf/events/'.$event->id.'/reference'.$payment->id.'.pdf');
                $this->saveAccesses($payment, $folios,$infoTickets);
            } else {
                // $this->deleteFiles($folios);
                return response()->json([
                    'status' => false,
                    'error' => $order['msj'] 
                    // 'error' => $e->getMessage()
                ]);
            }
        }else if($request->input('payment_method') == 'free'){
            $payment = $this->registerPayment($event->id, $request->input('name'),'Gratis', 'free', $request->input('email'), 'payed', $total, $request->input('phone'));
            $this->saveAccesses($payment, $folios,$infoTickets);
            
        }

        switch ($request->input('payment_method')) {
            case 'card':
                $this->sendWhatsapp($request, $event, $payment);
                // $this->sendSms($request, $event, $payment);
                Mail::to($request->input('email'))->send(new SendTickets($event, $folios, $tickets, $request->input('name'), $request->input('quantities'), $total, $commission, $discountCodesVal));
                break;
            case 'oxxo':
                $this->sendWhatsAppReference($request, $event, $order['reference']);
                Mail::to($request->input('email'))->send(new SendReference($event, $order['reference'], $request->input('name'), $payment));
                break;
            case 'free':
                Mail::to($request->input('email'))->send(new SendTickets($event, $folios, $tickets, $request->input('name'), $request->input('quantities'), $total, $commission));
                break;
            
        }
        return response()->json([
            'status' => true
        ]);
        
        
        
    }

    private function discountCodes($codes, $payment_method, $event_id) {
        for ($i = 0; $i < sizeof($codes); $i++) {
            $codeAux = $codes[$i];
            $code = Code::with(['tickets' => function($query) use($codeAux, $event_id) {
                $query->where('ticket_id', $codeAux['ticket_id'])->where('event_id', $event_id);
            }])
            ->whereHas('tickets', function($query) use($codeAux) {
                $query->where('ticket_id', $codeAux['ticket_id']);
            })
            ->where('code', strtoupper($codes[$i]['code']))
            ->where('expiration', '>=', date('Y-m-d'))
            ->first();
            if (!empty($code)) {
                if ($payment_method == 'card') {
                    $code->tickets[0]->pivot->used = $code->tickets[0]->pivot->used + $codes[$i]['quantity'];
                } else {
                    $code->tickets[0]->pivot->reserved = $code->tickets[0]->pivot->reserved + $codes[$i]['quantity'];
                }
                $code->tickets()->detach([$code->id, $code->tickets[0]->id]);
                $code->tickets()->attach($code->id, [
                    'ticket_id' => $code->tickets[0]->id,
                    'used' => $code->tickets[0]->pivot->used,
                    'reserved' => $code->tickets[0]->pivot->reserved
                ]);
            }
        }
        return true;
    }

    public function registerPayment($event_id, $name, $reference, $type, $email, $status, $total, $phone) {
        $payment = Payment::create([
            'event_id' => $event_id,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'type' => $type,
            'reference' => $reference,
            'amount' => $total,
            'status' => $status
        ]);
        return $payment;
    }

    public function saveAccesses($payment, $folios,$infoTickets) {

        /* $questions=array_filter($infoTickets, function($ticket,$key) use($request,$i) {
            echo "| ".$ticket['idTicket']." == ".$request->input('tickets')[$i];
            return $ticket['idTicket'] == $request->input('tickets')[$i];
        }, ARRAY_FILTER_USE_BOTH);*/

        for ($i = 0; $i < sizeof($folios); $i++) { 
            //dd($infoTickets[$i]);
            // $code = Code::where('code', $infoTickets[$i]['code'])->where('ticket_id', $folios[$i]['ticket_id'])->first();
            $code = Code::where('code', $infoTickets[$i]['code'])
            ->where('expiration', '>=', date('Y-m-d'))
            ->whereHas('tickets', function($query) use($folios, $i, $payment) {
                $query->where('ticket_id', $folios[$i]['ticket_id'])->where('event_id', $payment->event_id);
            })
            ->first();
            $access = Access::create([
                'payment_id' => $payment->id,
                'ticket_id' => $folios[$i]['ticket_id'],
                // 'code_id' => (isset($code->id)) ? $code->id : null,
                'folio' => $folios[$i]['folio'],
                'quantity' => $folios[$i]['valid'],
                'name' => $infoTickets[$i]['name'],
                'email' => $infoTickets[$i]['email'],
                'phone' => $infoTickets[$i]['phone'],
            ]);
            if (!empty($code)) {
                $access->codes()->attach([
                    $code->id => ['discount' => $code->discount, 'ticket_price' => $folios[$i]['ticket_price']]
                ]);
            }
            
            if (isset($infoTickets[$i]['requestQuestion'])) {
                $response = '';
                $responses = $infoTickets[$i]['requestQuestion'];
                
                for ($k=0; $k <sizeof($responses); $k++) { 
                    if($responses[$k]['type']== 'file'){
                        //registrar agregar un archivo a la storage
                        //$response = 'Name archivo=>'.$responses[$k]['title'];
                        $file="".$responses[$k]['value'];
                        echo "".pathinfo($file, PATHINFO_FILENAME);
                        dd();
                    } else {
                        $response=$responses[$k]['value'];
                    }
                    $question = Response::create([
                        'question_id' => $responses[$k]['question_id'],
                        'access_id' => $access->id,
                        'response' => $response,
                    ]);
                    $question->save();
                }
            }

            if (isset($folios[$i]['turn'])) {
                for ($j = 0; $j < sizeof($folios[$i]['turn']); $j++) { 
                    $access->turns()->attach($folios[$i]['turn'][$j]);
                    $turn = Turn::where('id', $folios[$i]['turn'][$j])->first();
                    $turn->used = $turn->used + 1;
                    $turn->save();
                }
            }
        }
        return true;
    }

    public function createCustomer($name, $email, $token) {
        $data['status'] = true;
        try {
            $this->customer = \Conekta\Customer::create(
                array(
                    "name" => $name,
                    "email" => $email,
                    "payment_sources" => array(
                        array(
                            "type" => "card",
                            "token_id" => $token
                        )
                    )
                )
            );
        } catch (\Conekta\ProcessingError $error){
            $data['msj'] = $error->getMessage();
            $data['status'] = false;
        } catch (\Conekta\ParameterValidationError $error){
            $data['msj'] = $error->getMessage();
            $data['status'] = false;
        } catch (\Conekta\Handler $error){
            $data['msj'] = $error->getMessage();
            $data['status'] = false;
        }
        return $data;
    }

    public function createOrder($price, $description, $quantity) {
        $data['status'] = true;
        $price = intval($price);
        try {
            $this->order = \Conekta\Order::create(
                array(
                    "amount"=>$price,
                    "line_items" => array(
                    array(
                        "name" => $description,
                        "unit_price" => $price * 100, //se multiplica por 100 conekta
                        "quantity" => $quantity
                    )//first line_item
                    ), //line_items
                    "currency" => "MXN",
                    "customer_info" => array(
                    "customer_id" => $this->customer->id 
                    ), //customer_info
                    "charges" => array(
                        array(
                            "payment_method" => array(
                                    "type" => "default"
                            ) 
                        ) //first charge
                    ) //charges
                )//order
            );
        } catch (\Conekta\ProcessingError $error){
            $data['msj'] = $error->getMessage();
            $data['status'] = false;
        } catch (\Conekta\ParameterValidationError $error){
            $data['msj'] = $error->getMessage();
            $data['status'] = false;
        } catch (\Conekta\Handler $error){
            $data['msj'] = $error->getMessage();
            $data['status'] = false;
        }
        return $data;
    }

    public function createOrderOxxo($price, $description, $name, $email, $phone, $quantity) {
        $data['status'] = true;
        $date = Carbon::now();
        $expiration = (new DateTime($date->addDays(2)->format('Y-m-d')))->getTimestamp();
        $price = intval($price);
        try{
            $this->order = \Conekta\Order::create(
                array(
                "line_items" => array(
                    array(
                    "name" => $description,
                    "unit_price" => $price*100,
                    "quantity" => $quantity
                    )//first line_item
                ), //line_items
                "currency" => "MXN",
                "customer_info" => array(
                    "name" => $name,
                    "email" => $email,
                    "phone" => $phone
                ), //customer_info
                "charges" => array(
                    array(
                        "payment_method" => array(
                            "type" => "oxxo_cash",
                            "expires_at" => $expiration
                        )//payment_method
                    ) //first charge
                ) //charges
                )//order
            );
        } catch (\Conekta\ParameterValidationError $error) {
            $data['msj'] = $error->getMessage();
            $data['status'] = false;
        } catch (\Conekta\Handler $error) {
            $data['msj'] = $error->getMessage();
            $data['status'] = false;
        }
        $data['reference'] = $this->order->charges[0]->payment_method->reference;
        return $data;
    }

    public function sendEmailContact(Request $request) {
        if (!empty($request->input('g-recaptcha-response'))) {
            $keySecret = '6Lfb88sbAAAAAIfZ0riQLeAFqnOzMqWypLxPPeRR';
            $token = $request->input('g-recaptcha-response');
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $response = file_get_contents($url.'?secret='.$keySecret.'&response='.$token);
            $response = json_decode($response);
            if ($response->success == true) {
                
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

    public function sendWhatsapp($client, $event, $payment) {
        $hashids = new Hashids('', 25); // pad to length 10
        $paymentId = $hashids->encode($payment->id);
        // print_r($id);
        // $idDecode = $hashids->decode($id);
        // dd($idDecode[0]);
        $messageBird = new \MessageBird\Client('F4JCaJDSSNBJPUkzcLBQScb7i'); // Set your own API access key here.

        $hsmParam1 = new \MessageBird\Objects\Conversation\HSM\Params();
        $hsmParam1->default = $client->name;

        $hsmParam2 = new \MessageBird\Objects\Conversation\HSM\Params();
        $hsmParam2->default = $event->name;

        $hsmParam3 = new \MessageBird\Objects\Conversation\HSM\Params();
        $hsmParam3->default = 'http://015e-187-247-139-61.ngrok.io/download/tickets/'.$paymentId;

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
        $message->to = '521'.$client->phone;
        $message->type = 'hsm';

        try {
            $conversation = $messageBird->conversations->start($message);

            // dd($conversation);
        } catch (\Exception $e) {
            // dd('error: '.$e->getMessage());
        }
        return true;
    }

    public function sendSms($client, $event, $payment) {
        $hashids = new Hashids('', 25); // pad to length 10
        $paymentId = $hashids->encode($payment->id);
        
        $MessageBird = new \MessageBird\Client('6t5V0jHDlkOpEZXUfc5f8PDwD');
        $Message = new \MessageBird\Objects\Message();
        $Message->originator = '524371041976';
        // $Message->recipients = array('52'.$value->telefono);
        $Message->recipients = array('524371041976'); // Miguel
        $Message->body = 'Hola '.$client->name.' agradecemos tu compra para asistir a '.$event->name.', puedes ver o descargar tus boletos en el siguiente enlace '.asset('').'download/tickets/'.$paymentId;
        try {
            $message = $MessageBird->messages->create($Message);
            // $m['success'] = "Los SMS se enviaron correctamente";
            // $m['sms'] = $message;
            // $m['error'] = "";
        } catch (\Exception $e) {
            dd("Los SMS no pudieron ser enviados: ".$e->getMessage());
            // echo sprintf("%s: %s", get_class($e), $e->getMessage());
        }
        return true;
    }

    public function sendSmsReference($client, $event, $reference) {
        $hashids = new Hashids('', 25); // pad to length 10
        $reference = $hashids->encode($reference);
        
        $MessageBird = new \MessageBird\Client('6t5V0jHDlkOpEZXUfc5f8PDwD');
        $Message = new \MessageBird\Objects\Message();
        $Message->originator = '524371041976';
        // $Message->recipients = array('52'.$value->telefono);
        $Message->recipients = array('524371041976'); // Miguel
        $Message->body = 'Hola '.$client->name.' agradecemos tu registro, puedes ver o descargar tu referencia de pago en el siguiente enlace '.asset('').'download/reference/'.$reference;
        try {
            $message = $MessageBird->messages->create($Message);
            // $m['success'] = "Los SMS se enviaron correctamente";
            // $m['sms'] = $message;
            // $m['error'] = "";
        } catch (\Exception $e) {
            dd("Los SMS no pudieron ser enviados: ".$e->getMessage());
            // echo sprintf("%s: %s", get_class($e), $e->getMessage());
        }
        return true;
    }

    public function sendWhatsAppReference($client, $event, $reference) {
        $hashids = new Hashids('', 25); // pad to length 10
        $reference = $hashids->encode($reference);
        // print_r($id);
        // $idDecode = $hashids->decode($id);
        // dd($idDecode[0]);
        $messageBird = new \MessageBird\Client('F4JCaJDSSNBJPUkzcLBQScb7i'); // Set your own API access key here.

        $hsmParam1 = new \MessageBird\Objects\Conversation\HSM\Params();
        $hsmParam1->default = $client->name;

        $hsmParam2 = new \MessageBird\Objects\Conversation\HSM\Params();
        $hsmParam2->default = $event->name;

        $hsmParam3 = new \MessageBird\Objects\Conversation\HSM\Params();
        $hsmParam3->default = asset('').'download/reference/'.$reference;

        $hsmLanguage = new \MessageBird\Objects\Conversation\HSM\Language();
        $hsmLanguage->policy = \MessageBird\Objects\Conversation\HSM\Language::DETERMINISTIC_POLICY;
        $hsmLanguage->code = 'es_MX';

        $hsm = new \MessageBird\Objects\Conversation\HSM\Message();
        $hsm->templateName = 'send_reference2';
        $hsm->namespace = '761b9b95_04f0_4ea9_9fa2_547e0efdb21c';
        $hsm->params = [$hsmParam1, $hsmParam2, $hsmParam3];
        $hsm->language = $hsmLanguage;

        $content = new \MessageBird\Objects\Conversation\Content();
        $content->hsm = $hsm;

        $message = new \MessageBird\Objects\Conversation\Message();
        $message->channelId = 'af4120f7-4c93-4aec-b4fa-d5e3335470c1';
        $message->content = $content;
        $message->to = '521'.$client->phone;
        $message->type = 'hsm';

        try {
            $conversation = $messageBird->conversations->start($message);

            // dd($conversation);
        } catch (\Exception $e) {
            // dd('error: '.$e->getMessage());
        }
        return true;
    }

    public function downloadTickets($paymentId) {
        $hashids = new Hashids('', 25);
        $paymentId = $hashids->decode($paymentId);
        $access = Access::with(['payment', 'ticket'])->where('payment_id', $paymentId[0])->whereHas('payment', function($query) {
            $query->where('status', 'payed');
        })->get();
        if (sizeof($access) > 0) {
            return view('access')->with(['access' => $access, 'type' => 'tickets']);
        } else {
            return redirect('/');
        }
    }

    public function downloadReference($reference) {
        $hashids = new Hashids('', 25);
        $reference = $hashids->decode($reference);
        $payment = Payment::with(['event'])->where('reference', $reference[0])->first();
        if (!empty($payment)) {
            return view('access')->with(['payment' => $payment, 'type' => 'reference']);
        } else {
            return redirect('/');
        }
    }

    public function validateCodes(Request $request) {
        foreach ($request->codes as $key => $cod) {
            $codeExist = Code::where('code', strtoupper($cod['code']))->whereHas('tickets', function($query) use($request) {
                $query->where('event_id', $request->event_id);
            })->first();
            if (!empty($codeExist)) {
                $code = Code::with(['tickets'])->where('code', strtoupper($cod['code']))->whereHas('tickets', function($query) use($cod) {
                    $query->where('ticket_id', $cod['ticket_id']);
                })->first();
                if (!empty($code)) {
                    $sales = 0;
                    for ($i = 0; $i < sizeof($code->tickets); $i++) { 
                        $sales = $sales + ($code->tickets[$i]->pivot->used + $code->tickets[$i]->pivot->reserved);
                        if ($cod['ticket_id'] == $code->tickets[$i]->id) {
                            $code->ticket = $code->tickets[$i];
                        }
                    }
                    if (date('Y-m-d') <= $code->expiration) {
                        if ($cod['quantity'] <= ($code->quantity - $sales)) {
                            $data[$key]['status'] = true;
                            $data[$key]['type'] = 'success';
                            $data[$key]['discount'] = $code->discount;
                            $data[$key]['quantity'] = $cod['quantity'];
                            $data[$key]['ticket_id'] = $code->ticket->id;
                            $data[$key]['total'] = $code->ticket->price;
                            $data[$key]['code'] = strtoupper($cod['code']);
                            $data[$key]['error'] = '';
                        } else if(($cod['quantity'] > ($code->quantity - $sales)) && ($code->quantity - $sales) > 0) {
                            $data[$key]['status'] = true;
                            $data[$key]['type'] = 'warning';
                            $data[$key]['discount'] = $code->discount;
                            $data[$key]['quantity'] = ($code->quantity - $sales);
                            $data[$key]['ticket_id'] = $code->ticket->id;
                            $data[$key]['total'] = $code->ticket->price;
                            $data[$key]['code'] = strtoupper($cod['code']);
                            $data[$key]['error'] = 'El código <b>'.strtoupper($cod['code']).'</b> solo sera aplicado '.($code->quantity - $sales).' veces ya que no hay más disponibles';
                        } else {
                            $data[$key]['status'] = false;
                            $data[$key]['type'] = 'danger';
                            $data[$key]['ticket_id'] = $code->ticket->id;
                            $data[$key]['code'] = strtoupper($cod['code']);
                            $data[$key]['quantity'] = 0;
                            $data[$key]['error'] = 'El código <b>'.strtoupper($cod['code']).'</b> se encuentra agotado';
                        }
                    } else {
                        $data[$key]['status'] = false;
                        $data[$key]['type'] = 'danger';
                        $data[$key]['ticket_id'] = $code->ticket->id;
                        $data[$key]['code'] = strtoupper($cod['code']);
                        $data[$key]['quantity'] = $cod['quantity'];
                        $data[$key]['error'] = 'El código <b>'.strtoupper($cod['code']).'</b> ha expirado';
                    }
                } else {
                    $ticket = Ticket::where('id', $cod['ticket_id'])->first();
                    $data[$key]['status'] = false;
                    $data[$key]['type'] = 'danger';
                    $data[$key]['ticket_id'] = $cod['ticket_id'];
                    $data[$key]['code'] = strtoupper($cod['code']);
                    $data[$key]['error'] = 'El código <b>'.strtoupper($cod['code']).'</b> no es aplicable para el boleto <b>'.$ticket->name.'</b>';
                }
            } else {
                $data[$key]['status'] = false;
                $data[$key]['type'] = 'danger';
                $data[$key]['ticket_id'] = $cod['ticket_id'];
                $data[$key]['code'] = strtoupper($cod['code']);
                $data[$key]['error'] = 'El código '.strtoupper($cod['code']).' no existe';
            }
        }
        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }
}
