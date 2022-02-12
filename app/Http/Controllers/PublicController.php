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
use DateTime;
use DateInterval;
use PDF;

class PublicController extends Controller
{

    private $ApiKey = 'key_rwDCz9zcDKjyrHcyVTvk6g';
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
        //dd($request);
        // dd(json_decode($request->input('tickets')));
        $event = Event::with(['profile', 'eventDates', 'location'])->where('id', $request->input('event_id'))->first();
        $initial_date = Carbon::parse($event->eventDates[0]->date)->locale('es')->isoFormat('D-MM-Y');
        $pos = sizeof($event->eventDates) - 1;
        $final_date = Carbon::parse($event->eventDates[$pos]->date)->locale('es')->isoFormat('D-MM-Y');
        $address = $event->location->name;

        $tickets = Array();
        $folios = Array();
        $total = 0;
        $pos = 0;
        $pos2 = 0;
        if (!file_exists('media/pdf/events/'.$event->id)) {
            mkdir('media/pdf/events/'.$event->id, 0777, true);
        }
        $msj = null;
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
        /*
         $questions=array_filter($infoTickets, function($ticket,$key) {
            return $ticket['idTicket'] == 3;
        }, ARRAY_FILTER_USE_BOTH);
        
        dd($questions[0]); 
        */
        
        //dd($infoTickets);
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
                        $folios[$pos]['valid'] = $ticket->valid;
                        $pos++;
                    }
                } catch(\Exception $e) {
                    // $this->deleteFiles($folios);
                    return response()->json([
                        'status' => false,
                        'error' => $e->getMessage()
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
                    // Se registra en nuestra BDD la información de los pagos
                    $payment = $this->registerPayment($event->id, $request->input('name'), substr($request->input('card'), -4), 'card', $request->input('email'), 'payed', $total, $request->input('phone'));
                    $this->saveAccesses($payment->id, $folios,$infoTickets);
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
                $this->saveAccesses($payment->id, $folios,$infoTickets);
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
            $this->saveAccesses($payment->id, $folios,$infoTickets);
            
        }

        switch ($request->input('payment_method')) {
            case 'card':
                $this->sendSmsAndWhatsapp($request, $event, $payment);
                Mail::to($request->input('email'))->send(new SendTickets($event, $folios, $tickets, $request->input('name'), $request->input('quantities'), $total, $commission));
                break;
            case 'oxxo':
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

    public function saveAccesses($payment_id, $folios,$infoTickets) {

        /* $questions=array_filter($infoTickets, function($ticket,$key) use($request,$i) {
            echo "| ".$ticket['idTicket']." == ".$request->input('tickets')[$i];
            return $ticket['idTicket'] == $request->input('tickets')[$i];
        }, ARRAY_FILTER_USE_BOTH);*/

        for ($i = 0; $i < sizeof($folios); $i++) { 
            //dd($infoTickets[$i]);
            $access = Access::create([
                'payment_id' => $payment_id,
                'ticket_id' => $folios[$i]['ticket_id'],
                'folio' => $folios[$i]['folio'],
                'quantity' => $folios[$i]['valid'],
                'name' => $infoTickets[$i]['name'],
                'email' => $infoTickets[$i]['email'],
                'phone' => $infoTickets[$i]['phone'],
            ]);            
            
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

    public function sendSmsAndWhatsapp($client, $event, $payment) {
        $hashids = new Hashids('', 25); // pad to length 10
        $paymentId = $hashids->encode($payment->id);
        // print_r($id);
        // $idDecode = $hashids->decode($id);
        // dd($idDecode[0]);
        $messageBird = new \MessageBird\Client('PEEB1YEK2oMAZslARp7EAyxxw'); // Set your own API access key here.

        $hsmParam1 = new \MessageBird\Objects\Conversation\HSM\Params();
        $hsmParam1->default = $client->name;

        $hsmParam2 = new \MessageBird\Objects\Conversation\HSM\Params();
        $hsmParam2->default = $event->name;

        $hsmParam3 = new \MessageBird\Objects\Conversation\HSM\Params();
        $hsmParam3->default = asset('').'download/tickets/'.$paymentId;

        $hsmLanguage = new \MessageBird\Objects\Conversation\HSM\Language();
        $hsmLanguage->policy = \MessageBird\Objects\Conversation\HSM\Language::DETERMINISTIC_POLICY;
        $hsmLanguage->code = 'es_MX';

        $hsm = new \MessageBird\Objects\Conversation\HSM\Message();
        $hsm->templateName = 'prueba_2';
        $hsm->namespace = '4f323d59_c5fa_478d_8a3f_4eb145ca0b2f';
        $hsm->params = [$hsmParam1, $hsmParam2, $hsmParam3];
        $hsm->language = $hsmLanguage;

        $content = new \MessageBird\Objects\Conversation\Content();
        $content->hsm = $hsm;

        $message = new \MessageBird\Objects\Conversation\Message();
        $message->channelId = '397492bb-c325-4438-a20d-83fea267d8ac';
        $message->content = $content;
        $message->to = '521'.$client->phone;
        $message->type = 'hsm';

        try {
            $conversation = $messageBird->conversations->start($message);

            // dd($conversation);
        } catch (\Exception $e) {
            dd('error: '.$e->getMessage());
        }
        return true;
    }
}
