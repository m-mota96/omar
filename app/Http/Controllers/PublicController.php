<?php

namespace App\Http\Controllers;

require_once('bin/conekta-php-master/lib/Conekta.php');
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Mail\SendReference;
use App\Mail\SendTickets;
use Carbon\Carbon;
use App\Event;
use App\Ticket;
use App\Payment;
use App\Access;
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
        $data = Event::with(['profile', 'eventDates', 'location', 'tickets'])->where(DB::raw('BINARY url'), $event)->first();
        if (!empty($data)) {
            $data->initial_date = Carbon::parse($data->eventDates[0]->date)->locale('es')->isoFormat('D MMM Y').' - '.substr($data->eventDates[0]->initial_time, 0, 5);
            $pos = sizeof($data->eventDates) - 1;
            $data->final_date = Carbon::parse($data->eventDates[$pos]->date)->locale('es')->isoFormat('D MMM Y').' - '.substr($data->eventDates[$pos]->final_time, 0, 5);
            return view('public.event')->with(['event' => $data, 'ticket' => $ticket]);
        } else {
            dd('not found');
        }
    }

    public function makePayment(Request $request) {
        // dd($request->input());
        $event = Event::with(['profile'])->where('id', $request->input('event_id'))->first();
        $tickets = Array();
        $folios = Array();
        $total = 0;
        $pos = 0;
        for ($i = 0; $i < sizeof($request->input('quantities')); $i++) {
            if ($request->input('quantities')[$i] > 0) {
                $ticket = Ticket::select('id', 'name', 'description', 'price', 'quantity', 'sales')->where('id', $request->input('tickets')[$i])->first();
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
                $tickets[$i]['name'] = $ticket->name;
                // $tickets[$i]['description'] = $ticket->description;
                $tickets[$i]['price'] = $ticket->price;
                $total = $total + ($ticket->price * $request->input('quantities')[$i]);
                try {
                    $ticket->img_event = 'media/events/'.$event->id.'/'.$event->profile->name;
                    for ($j = 0; $j < $request->input('quantities')[$i]; $j++) {
                        $folio = strtoupper(uniqid());
                        $code_QR = QrCode::generate($folio);
                        $ticket->qr = base64_encode($code_QR);
                        $pdf = PDF::loadView('pdfTicket', $ticket);
                        if (!file_exists('media/pdf/events/'.$event->id)) {
                            mkdir('media/pdf/events/'.$event->id, 0777, true);
                        }
                        $pdf->save('media/pdf/events/'.$event->id.'/'.$folio.'.pdf');
                        $folios[$pos]['folio'] = $folio;
                        $folios[$pos]['ticket_id'] = $ticket->id;
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
        \Conekta\Conekta::setApiKey($this->ApiKey);
        \Conekta\Conekta::setApiVersion($this->ApiVersion);
        if ($request->input('payment_method') == 'card') {
            $customer = $this->createCustomer($request->input('name'), $request->input('email'), $request->input('conektaTokenId'));
            if ($customer['status'] == true) {
                $order = $this->createOrder($total, 'Compra de boletos para '.$event->name, 1);
                if ($order['status'] == true) {
                    $payment = $this->registerPayment($event->id, substr($request->input('card'), -4), $request->input('email'), 'payed', $total);
                    $this->saveAccesses($payment->id, $folios);
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
                $payment = $this->registerPayment($event->id, $order['reference'], $request->input('email'), 'pending', $total);
                $dataReference = array(
                    'reference' => $order['reference'],
                    'monto' => $total,
                    'name_event' => $event->name
                );
                $pdf = PDF::loadView('pdfOxxo', $dataReference);
                if (!file_exists('media/pdf/events/'.$event->id)) {
                    mkdir('media/pdf/events/'.$event->id, 0777, true);
                }
                $pdf->save('media/pdf/events/'.$event->id.'/reference'.$payment->id.'.pdf');
                $this->saveAccesses($payment->id, $folios);
            } else {
                // $this->deleteFiles($folios);
                return response()->json([
                    'status' => false,
                    'error' => $order['msj']
                    // 'error' => $e->getMessage()
                ]);
            }
        }
        switch ($request->input('payment_method')) {
            case 'card':
                Mail::to($request->input('email'))->send(new SendTickets($event, $folios, $tickets, $request->input('name'), $request->input('quantities'), $total));
                break;
            case 'oxxo':
                Mail::to($request->input('email'))->send(new SendReference($event, $order['reference'], $request->input('name'), $payment));
                break;
        }
        return response()->json([
            'status' => true
        ]);
    }

    public function registerPayment($event_id, $reference, $email, $status, $total) {
        $payment = Payment::create([
            'event_id' => $event_id,
            'email' => $email,
            'reference' => $reference,
            'amount' => $total,
            'status' => $status
        ]);
        return $payment;
    }

    public function saveAccesses($payment_id, $folios) {
        for ($i = 0; $i < sizeof($folios); $i++) { 
            Access::create([
                'payment_id' => $payment_id,
                'ticket_id' => $folios[$i]['ticket_id'],
                'folio' => $folios[$i]['folio']
            ]);
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
}
