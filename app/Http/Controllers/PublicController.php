<?php

namespace App\Http\Controllers;

require_once('bin/conekta-php-master/lib/Conekta.php');
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Event;
use App\Ticket;
use PDF;

class PublicController extends Controller
{

    private $ApiKey = 'key_rwDCz9zcDKjyrHcyVTvk6g';
    private $ApiVersion = '2.0.0';

    public function __construct() {
        date_default_timezone_set('America/Mexico_City');
        setlocale(LC_ALL, 'es_ES');
    }

    public function index($event) {
        $data = Event::with(['profile', 'eventDates', 'location', 'tickets'])->where(DB::raw('BINARY url'), $event)->first();
        if (!empty($data)) {
            $data->initial_date = Carbon::parse($data->eventDates[0]->date)->locale('es')->isoFormat('D MMM Y').' - '.substr($data->eventDates[0]->initial_time, 0, 5);
            $pos = sizeof($data->eventDates) - 1;
            $data->final_date = Carbon::parse($data->eventDates[$pos]->date)->locale('es')->isoFormat('D MMM Y').' - '.substr($data->eventDates[$pos]->final_time, 0, 5);
            return view('public.event')->with(['event' => $data]);
        } else {
            dd('not found');
        }
    }

    public function makePayment(Request $request) {
        // dd($request->input());
        $event = Event::with(['profile'])->where('id', $request->input('event_id'))->first();
        $tickets = Array();
        $total = 0;
        $pos = 0;
        for ($i = 0; $i < sizeof($request->input('quantities')); $i++) {
            if ($request->input('quantities')[$i] > 0) {
                $ticket = Ticket::select('name', 'description', 'price')->where('id', $request->input('tickets')[$i])->first();
                // $tickets[$i]['name'] = $ticket->name;
                // $tickets[$i]['description'] = $ticket->description;
                // $tickets[$i]['price'] = $ticket->price;
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
                        $folios[$pos] = $folio;
                        $pos++;
                    }
                } catch(\Exception $e) {
                    // $this->deleteFiles($folios);
                    return response()->json([
                        'status' => false,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }
        \Conekta\Conekta::setApiKey($this->ApiKey);
        \Conekta\Conekta::setApiVersion($this->ApiVersion);
        if ($request->input('payment_method') == 'card') {
            $customer = $this->createCustomer($request->input('name'), $request->input('email'), $request->input('conektaTokenId'));
            if ($customer['status'] == true) {
                $order = $this->createOrder($total, 'Compra de boletos para '.$event->name, 1);
                if ($order['status'] == true) {
                    // $payment = $this->registerPayment($event->id, substr($request->input('card'), -4), $request->input('email'), $coupon, 'pagado');
                    // $this->saveBuyers($payment->id, $names, $folios, $nameBride, $emailBride, $phoneBride, $dateWedding);
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

        }
        
    }

    public function createCustomer($name, $email, $token) {
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
        $data['status'] = true;
        return $data;
    }

    public function createOrder($price, $description, $quantity) {
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
        $data['status'] = true;
    }
}
