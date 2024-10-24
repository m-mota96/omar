<?php

namespace App\Http\Controllers;

require_once('bin/messagebird/autoload.php');
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Mail\SendTickets;
use App\Exclusivity;
use App\Event;
use App\User;
use App\GalleryEvent;
use App\EventDate;
use App\LocationEvent;
use App\Ticket;
use App\Payment;
use App\Turn;
use App\Category;
use App\Question;
use App\Access;
use App\Code;
use DateTime;
use File;
use ZipArchive;
use GuzzleHttp\Client;
use Hashids\Hashids;

class CustomerController extends Controller {

    public function __construct() {
        date_default_timezone_set('America/Mexico_City');
    }

    public function checkEvent(Request $request) {
        $name = Exclusivity::where(DB::raw('BINARY name'), $request->input('name_event'))->first();
        $website = Exclusivity::where(DB::raw('BINARY name'), $request->input('website'))->first();
        if(!empty($name)) {
            if($name->user_id == auth()->user()->id) {
                $name_available = true;
                $status_name_event = Event::where(DB::raw('BINARY name'), $request->input('name_event'))->where('user_id', auth()->user()->id)->get();
                foreach ($status_name_event as $key => $value) {
                    if ($value->status < 3) {
                        $name_available = false;
                    }
                }
            } else {
                $name_available = false;
            }
        } else {
            $name_available = true;
        }
        if(!empty($website)) {
            if($website->user_id == auth()->user()->id) {
                $website_available = true;
                $status_url_event = Event::where(DB::raw('BINARY url'), $request->input('website'))->where('user_id', auth()->user()->id)->get();
                foreach ($status_url_event as $key => $value) {
                    if ($value->status < 3) {
                        $website_available = false;
                    }
                }
            } else {
                $website_available = false;
            }
        } else {
            $website_available = true;
        }
        $event = Event::where('url', $request->input('website'))->where('status', 1)->get()->count();
        if($event > 0) {
            $website_available = false;
        }
        return response()->json([
            'name_available' => $name_available,
            'website_available' => $website_available
        ]);
    }

    public function createEvent(Request $request) {

        //dd($request);

        
        $name = Exclusivity::where(DB::raw('BINARY name'), $request->input('name'))->where('user_id', auth()->user()->id)->first();
        $website = Exclusivity::where(DB::raw('BINARY name'), $request->input('website'))->where('user_id', auth()->user()->id)->first();
        if(!empty($name)) {
            $name->name = $request->input('name');
            $name->save();
        } else {
            Exclusivity::create([
                'user_id' => auth()->user()->id,
                'name' => $request->input('name')
            ]);
        }
        if(!empty($website)) {
            $website->name = $request->input('website');
            $website->save();
        } else {
            Exclusivity::create([
                'user_id' => auth()->user()->id,
                'name' => $request->input('website')
            ]);
        }

        $event = Event::create([
            'user_id' => auth()->user()->id,
            'name' => $request->input('name'),
            'url' => $request->input('website'),
            'description' => $request->input('description'),
            'quantity' => $request->input('quantity'),
            'cost_type' => $request->input('cost_type'),
            'category_id' => $request->input('category_id'),
        ]);

        $valid = 0;
        for ($i = 0; $i < sizeof($request->input('dates')); $i++) {
            EventDate::create([
                'event_id' => $event->id,
                'date' => $request->input('dates')[$i],
                'initial_time' => $request->input('initial_times')[$i],
                'final_time' => $request->input('final_times')[$i]
            ]);
            $valid++;
        }

        $aux_price=0;
        if($request->input('cost_type') == 'paid'){
            $aux_price=100;
        }else{
            $aux_price=0;
        }

        Ticket::create([
            'event_id' => $event->id,
            'name' => 'Boleto 1',
            'price' => $aux_price,
            'quantity' => 50,
            'valid' => $valid,
            'start_sale' => date('Y-m-d'),
            'stop_sale' => $request->input('dates')[0],
        ]);
        
        if (strlen($event->description) > 80) {
            $event->description = substr($event->description, 0, 80).'...';
        }
        $event->initial_date = Carbon::parse($request->input('dates')[0])->locale('es')->isoFormat('D MMM Y').' - '.$request->input('initial_times')[0];
        $pos = sizeof($request->input('dates')) - 1;
        $event->final_date = Carbon::parse($request->input('dates')[$pos])->locale('es')->isoFormat('D MMM Y').' - '.$request->input('final_times')[$pos];
        return response()->json([
            'status' => true,
            'event' => $event
        ]);
        
    }

    public function uploadImage(Request $request) {
        if (!file_exists('media/events/'.$request->input('event_id'))) {
            mkdir('media/events/'.$request->input('event_id'), 0777, true);
        }
        if($request->input('type') == 'index') {
            $photo = GalleryEvent::where('event_id', $request->input('event_id'))->where('type', 'index')->first();
            $type = 'index';
        } else {
            $photo = GalleryEvent::where('event_id', $request->input('event_id'))->where('type', 'logo')->first();
            $type = 'logo';
        }
        if(empty($photo)) {
            $file = file_get_contents($_FILES['files']['tmp_name']);
            $extension = pathinfo($_FILES["files"]["name"])["extension"];
            $fileName = uniqid().".".$extension;
            file_put_contents('media/events/'.$request->input('event_id').'/'.$fileName, $file);
            $image = GalleryEvent::create([
                'event_id' => $request->input('event_id'),
                'name' => $fileName,
                'type' => $type
            ]);
        } else {
            if(file_exists('media/events/'.$request->input('event_id').'/'.$photo->name)) {
                unlink('media/events/'.$request->input('event_id').'/'.$photo->name);
            }
            $file = file_get_contents($_FILES['files']['tmp_name']);
            $extension = pathinfo($_FILES["files"]["name"])["extension"];
            $fileName = uniqid().".".$extension;
            file_put_contents('media/events/'.$request->input('event_id').'/'.$fileName, $file);
            $photo->name = $fileName;
            $photo->save();
        }
        return response()->json([
            'status' => true,
            'image' => $fileName,
            'event_id' => $request->input('event_id'),
            'type' => $type
        ]);
    }

    public function extractEvent(Request $request) {
        $event = Event::where('id', $request->input('event_id'))->first();
        return response()->json([
            'event' => $event
        ]);
    }
    
    public function editEvent($id) {
        $event = Event::with(['profile', 'logo', 'eventDates', 'location','category'])->where('id', $id)->first();
        $event->original_initial_date = $event->eventDates[0]->date;
        $event->initial_date = Carbon::parse($event->eventDates[0]->date)->locale('es')->isoFormat('D MMM Y').' - '.substr($event->eventDates[0]->initial_time, 0, 5);
        $pos = sizeof($event->eventDates) - 1;
        $event->original_final_date = $event->eventDates[$pos]->date;
        $event->final_date = Carbon::parse($event->eventDates[$pos]->date)->locale('es')->isoFormat('D MMM Y').' - '.substr($event->eventDates[$pos]->final_time, 0, 5);
        
        //dd($event);
        
        return view('customers.editEvent')->with(['event' => $event, 'event_id' => $event->id, 'event_url' => $event->url]);
    }

    public function updateNameWebsite(Request $request) {
        $name = Exclusivity::where(DB::raw('BINARY name'), $request->input('name_event'))->where('user_id', auth()->user()->id)->first();
        $website = Exclusivity::where(DB::raw('BINARY name'), $request->input('website'))->where('user_id', auth()->user()->id)->first();
        if(!empty($name)) {
            $name->name = $request->input('name_event');
            $name->save();
        } else {
            Exclusivity::create([
                'user_id' => auth()->user()->id,
                'name' => $request->input('name_event')
            ]);
        }
        if(!empty($website)) {
            $website->name = $request->input('website');
            $website->save();
        } else {
            Exclusivity::create([
                'user_id' => auth()->user()->id,
                'name' => $request->input('website')
            ]);
        }
        $event = Event::where('id', $request->input('event_id'))->first();
        $event->name = $request->input('name_event');
        $event->url = $request->input('website');
        $event->quantity = $request->input('quantity');
        $event->category_id = $request->input('category_id');
        $event->save();
        return response()->json([
            'status' => true,
            'name' => $request->input('name_event'),
            'website' => $request->input('website'),
        ]);
    }

    public function deleteLogo(Request $request) {
        $photo = GalleryEvent::where('event_id', $request->input('event_id'))->where('type', 'logo')->first();
        if(file_exists('media/events/'.$request->input('event_id').'/'.$photo->name)) {
            unlink('media/events/'.$request->input('event_id').'/'.$photo->name);
        }
        $photo->delete();
        return response()->json([
            'status' => true
        ]);
    }

    public function editDescription(Request $request) {
        $event = Event::where('id', $request->input('event_id'))->first();
        $event->description = $request->input('description');
        $event->save();
        return response()->json([
            'status' => true
        ]);
    }

    public function editCategory(Request $request) {
        $event = Event::where('id', $request->input('event_id'))->first();
        $event->category_id = $request->input('category_id');
        $event->save();
        return response()->json([
            'status' => true
        ]);
    }

    public function editDates(Request $request) {
        $dates = EventDate::where('event_id', $request->input('event_id'))->delete();
        foreach ($request->input('dates') as $key => $value) {
            EventDate::create([
                'event_id' => $request->input('event_id'),
                'date' => $value,
                'initial_time' => $request->input('initial_times')[$key],
                'final_time' => $request->input('final_times')[$key],
            ]);
        }
        $initial_date = Carbon::parse($request->input('dates')[0])->locale('es')->isoFormat('D MMM Y').' - '.$request->input('initial_times')[0];
        $pos = sizeof($request->input('dates')) - 1;
        $final_date = Carbon::parse($request->input('dates')[$pos])->locale('es')->isoFormat('D MMM Y').' - '.$request->input('final_times')[$pos];
        return response()->json([
            'status' => true,
            'initial_date' => $initial_date,
            'final_date' => $final_date
        ]);
    }

    public function addContact(Request $request) {
        $event = Event::where('id', $request->input('event_id'))->first();
        $event->email = $request->input('email');
        $event->phone = $request->input('phone');
        $event->twitter = $request->input('twitter');
        $event->facebook = $request->input('facebook');
        $event->instagram = $request->input('instagram');
        $event->website = $request->input('website');
        $event->save();
        return response()->json([
            'status' => true
        ]);
    }

    public function addLocation(Request $request) {
        $location = LocationEvent::where('event_id', $request->input('event_id'))->first();
        if (empty($location)) {
            LocationEvent::create([
                'event_id' => $request->input('event_id'),
                'name' => $request->input('location'),
                'address' => $request->input('address'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude')
            ]);
        } else {
            $location->name = $request->input('location');
            $location->address = $request->input('address');
            $location->latitude = $request->input('latitude');
            $location->longitude = $request->input('longitude');
            $location->save();
        }
        return response()->json([
            'status' => true
        ]);
    }

    public function tickets($id) {
        $tickets = Ticket::with(['event.eventDates', 'access' => function($query) {
            $query->with('payment')->whereHas('payment', function($query2) {
                $query2->where('status', 'payed');
            });
        }])->where('event_id', $id)->get();
        $event = Event::with(['eventDates.turns'])->where('id', $id)->where('user_id', auth()->user()->id)->first();
        $indicatorTurn = false;
        foreach ($event->eventDates as $key => $eventDate) {
            if (sizeof($eventDate->turns) > 0) {
                $indicatorTurn = true;
            }
        }
        if (!empty($event)) {
            $payments = Payment::where('event_id', $id)->get()->count();
            return view('customers.tickets')->with([
                'tickets' => $tickets,
                'event_id' => $id,
                'event' => $event,
                'event_url' => $event->url,
                'quantityPayments' => $payments,
                'indicatorTurns' => $indicatorTurn
            ]);
        } else {
            return redirect('/home');
        }
    }

    public function saveTicket(Request $request) {
        if (!empty($request->input('ticket_id'))) {
            $ticket = Ticket::where('id', $request->input('ticket_id'))->first();
            $ticket->name = $request->input('name');
            $ticket->description = $request->input('description');
            $ticket->price = $request->input('price');
            $ticket->quantity = $request->input('quantity');
            $ticket->valid = $request->input('daysValid');
            $ticket->use_turns = $request->input('turns');
            $ticket->promotion = $request->input('promotion');
            $ticket->date_promotion = $request->input('date_promotion');
            $ticket->start_sale = $request->input('start_sale');
            $ticket->stop_sale = $request->input('stop_sale');
            $ticket->min_reservation = $request->input('min_reservation');
            $ticket->max_reservation = $request->input('max_reservation');
            $ticket->save();
            $operation = 'edit';
        } else {
            $ticket = Ticket::create([
                'event_id' => $request->input('event_id'),
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
                'quantity' => $request->input('quantity'),
                'valid' => $request->input('daysValid'),
                'use_turns' => $request->input('turns'),
                'promotion' => $request->input('promotion'),
                'date_promotion' => $request->input('date_promotion'),
                'start_sale' => $request->input('start_sale'),
                'stop_sale' => $request->input('stop_sale'),
                'min_reservation' => $request->input('min_reservation'),
                'max_reservation' => $request->input('max_reservation')
            ]);
            $operation = 'save';
            $ticket = Ticket::with(['event', 'access' => function($query) {
                $query->with('payment')->whereHas('payment', function($query2) {
                    $query2->where('status', 'payed');
                });
            }])->where('event_id', $request->input('event_id'))->get();
        }
        return response()->json([
            'status' => true,
            'operation' => $operation,
            'ticket' => $ticket
        ]);
    }

    public function deleteTicket(Request $request) {
        $ticket = Ticket::where('id', $request->input('ticket_id'))->delete();
        return response()->json([
            'status' => true
        ]);
    }

    public function searchEvents(Request $request) {
        $events = Event::with(['profile', 'eventDates'])->addSelect(['quantity_tickets' => Ticket::selectRaw('SUM(quantity) as quantity')
            ->whereColumn('event_id', 'events.id')
            ->groupBy('event_id')
        ])
        ->where('user_id', auth()->user()->id)->where('name', 'LIKE', '%'.$request->input('name').'%')->get();
        foreach ($events as $key => $e) {
            $e->initial_date = Carbon::parse($e->eventDates[0]->date)->locale('es')->isoFormat('D MMM Y').' - '.substr($e->eventDates[0]->initial_time, 0, 5);
            $pos = sizeof($e->eventDates) - 1;
            $e->final_date = Carbon::parse($e->eventDates[$pos]->date)->locale('es')->isoFormat('D MMM Y').' - '.substr($e->eventDates[$pos]->final_time, 0, 5);
            if(empty($e->profile->name)) {
                $e->imageGeneral = 'general/not_image.png';
            } else {
                $e->profile->name = 'events/'.$e->id.'/'.$e->profile->name;
            }
        }
        return response()->json([
            'events' => $events
        ]);
    }

    public function changeStatusEvent(Request $request) {
        // $user = User::where('id', auth()->user()->id)->first();
        // if (empty($user->contract)) {
        //     return response()->json([
        //         'status' => false
        //     ]);
        // } else {
            $event = Event::where('user_id', auth()->user()->id)->where('id', $request->input('eventId'))->first();
            $event->status = $request->input('status');
            $event->save();
            return response()->json([
                'status' => true
            ]);
        // }
    }

    public function resendTickets(Request $request) {
        $payment = Payment::with(['accesses.ticket', 'accesses.code', 'event'])->where('id', $request->input('payment_id'))->first();
        $payment->email = $request->input('email');
        $payment->save();
        $tickets = Array();
        $folios = Array();
        $quantities = Array();
        $discount = 0;
        $total = $payment->amount;
        $aux = 0;
        $pos = -1;
        if ($payment->event->model_payment == 'included') {
            if ($payment->type == 'card') {
                $commission = ($total * 0.03) + 2.5;
                $total = $total + $commission;
            } elseif ($payment->type == 'card') {
                $commission = ($total * 0.04);
                $total = $total + $commission;
            }
        } else {
            $commission = null;
        }
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
            if (!empty($payment->accesses[$i]->code_id)) {
                $discount = $discount + ($payment->accesses[$i]->ticket->price * ($payment->accesses[$i]->code->discount / 100));
            }
        }
        $this->sendWhatsapp($payment, $payment->event, $payment);
        Mail::to($request->input('email'))->send(new SendTickets($payment->event, $folios, $tickets, $payment->name, $quantities, $total, $commission, $discount));
        return response()->json([
            'status' => true
        ]);
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

            dd($conversation);
        } catch (\Exception $e) {
            dd('error: '.$e->getMessage());
        }
        return true;
    }

    public function saveTurns(Request $request) {
        
        $idsTurnsNews = json_decode(json_decode(json_encode($request->turnsNews)),true);
        
        $contNewTurn=0;
        
        if($request->input('nameTurn') == null){
            
        }else{
            $pointerStarDate=array_keys($request->input('nameTurn'))[0];
            $resetRequest=(array_keys($request->input('nameTurn')));
            $numberDate=end($resetRequest);

            $i=$pointerStarDate;

            for ($date =$pointerStarDate; $date <=$numberDate; $date++) {
                //echo "conta Date > ".$date."\n";
                if(isset($request->input('nameTurn')[$i])){
                    for ($j = 0; $j < sizeof($request->input('nameTurn')[$i]); $j++) { 
                        if($request->input('turnStatus')[$i][$j]=="edit"){
                            
                            $turn = Turn::find($request->input('idTurn')[$i][$j]);
                            $turn->name=$request->input('nameTurn')[$i][$j];
                            $turn->initial_hour=$request->input('hourInitial')[$i][$j].':'.$request->input('minuteInitial')[$i][$j];
                            $turn->final_hour=$request->input('hourFinal')[$i][$j].':'.$request->input('minuteFinal')[$i][$j];
                            $turn->quantity=$request->input('quantity')[$i][$j];
                            $turn->save();
                            
        
                        }elseif($request->input('turnStatus')[$i][$j]=="new"){
                            
                            $idNewTurn=Turn::create([
                                'event_date_id' => $request->input('dateId')[$i],
                                'name' => $request->input('nameTurn')[$i][$j],
                                'initial_hour' => $request->input('hourInitial')[$i][$j].':'.$request->input('minuteInitial')[$i][$j],
                                'final_hour' => $request->input('hourFinal')[$i][$j].':'.$request->input('minuteFinal')[$i][$j],
                                'quantity' => $request->input('quantity')[$i][$j]
                            ]);
                            
                            $idsTurnsNews[$contNewTurn]['idNew']=$idNewTurn->id;
                            $contNewTurn++;
                            
                        }
                        
                    }
                    $i++;
                }else{
                    $i++;
                }
            

            }
            
        }
        
        if(strlen($request->turnsEliminated)>0){
            //Elimina los turnos que vienen en la variable de turnsEliminated
            $turnsEliminated=explode(",",$request->turnsEliminated);

            foreach($turnsEliminated as $idTurn){
                Turn::where('id', $idTurn)->delete();
            }
        }

        return response()->json([
            'status' => true,
            'idsTurnsNews'=>$idsTurnsNews
        ]);
        
        
        
        

        
        
    }

    public function model_payment(Request $request) {
        $payments = Payment::where('event_id', $request->input('event_id'))->get()->count();
        if ($payments == 0) {
            $event = Event::where('id', $request->input('event_id'))->first();
            $event->model_payment = $request->input('model_payment');
            $event->save();
            $status = true;
        } else {
            $status = false;
        }
        return response()->json([
            'status' => $status
        ]);
    }

    public function getCategories() {
        $categories = Category::all();
        return ['categories'=>$categories];
    }

    public function form_ticket($id) {
        $event = Event::where('id', $id)->where('user_id', auth()->user()->id)->first();
        if (!empty($event)) {
            $tickets = Ticket::where('event_id', $id)->get();
            $questions = Question::with(['tickets'])->where('event_id', $id)->get();
            foreach ($questions as $key => $value) {
                $arrayTickets=Array();
                $value->status = 'existing';
                $value->type = strval($value->type);
                $value->info = (!empty($value->information)) ? $value->information : '';
                $value->required = ($value->required == 1) ? true : false;
                foreach ($value->tickets as $key2 => $ticket) {
                    $arrayTickets[$key2] = $ticket->id;
                }
                unset($value->tickets);
                $value->tickets = $arrayTickets;
                if ($value->type == 2) {
                    $value->options = explode(',', $value->options);
                } else {
                    $value->options = null;
                }
            }
            //dd($questions);
            return view('customers.form_ticket')->with(['event' => $event, 'event_id' => $event->id, 'event_url' => $event->url, 'tickets' => $tickets, 'questions' => $questions]);
        } else {
            return redirect('/home');
        }
    }

    public function saveChangesQuesntions(Request $request) {
        // dd($request->input());
        if (!empty($request->input('dataDeleted'))) {
            for ($i = 0; $i < sizeof($request->input('dataDeleted')); $i++) { 
                $question = Question::findOrFail($request->input('dataDeleted')[$i]);
                $question->tickets()->detach();
                $question->delete();
            }
        }
        if(!empty($request->input('data'))) {
            for ($i = 0; $i < sizeof($request->input('data')); $i++) { 
                $options = null;
                if (intval($request->input('data')[$i]['type']) == 2) {
                    $options = implode(",", $request->input('data')[$i]['options']);
                }
                if ($request->input('data')[$i]['status'] == 'new') {
                    $question = Question::create([
                        'event_id' => $request->input('event_id'),
                        'title' => $request->input('data')[$i]['title'],
                        'required' => ($request->input('data')[$i]['required'] == 'true') ? true : false,
                        'type' => $request->input('data')[$i]['type'],
                        'information' => $request->input('data')[$i]['info'],
                        'options' => $options,
                    ]);
                    $question->tickets()->sync($request->input('data')[$i]['tickets']);
                } else {
                    $question = Question::with(['tickets'])->where('id', $request->input('data')[$i]['id'])->first();
                    $question->title = $request->input('data')[$i]['title'];
                    $question->required = ($request->input('data')[$i]['required'] == 'true') ? true : false;
                    $question->type = $request->input('data')[$i]['type'];
                    $question->information = $request->input('data')[$i]['info'];
                    $question->options = $options;
                    $question->tickets()->sync($request->input('data')[$i]['tickets']);
                    $question->save();
                }
            }
        }
        $questions = Question::with(['tickets'])->where('event_id', $request->input('event_id'))->get();
        foreach ($questions as $key => $value) {
            $value->status = 'existing';
            $value->type = strval($value->type);
            $value->info = (!empty($value->information)) ? $value->information : '';
            $value->required = ($value->required == 1) ? true : false;
            foreach ($value->tickets as $key2 => $ticket) {
                $arrayTickets[$key2] = $ticket->id;
            }
            unset($value->tickets);
            $value->tickets = $arrayTickets;
            if ($value->type == 2) {
                $value->options = explode(',', $value->options);
            } else {
                $value->options = null;
            }
        }
        return response()->json([
            'status' => true,
            'questions' => $questions
        ]);
    }

    public function downloadTickets(Request $request) {
        $tickets = Access::with(['payment'])->where('payment_id', $request->input('payment_id'))->get();
        // dd(sizeof($tickets));
        $zip = new ZipArchive();
        $filename = 'media/zips/'.$tickets[0]->payment->name.'.zip';
        
        if($zip->open($filename, ZIPARCHIVE::CREATE) === true) {
            foreach ($tickets as $key => $value) {
                $url = 'media/pdf/events/'.$tickets[0]->payment->event_id.'/'.$value->folio.'.pdf';
                $name = basename($url);
                $zip->addFile($url, $name);
            }
            $resultado = $zip->close();
            if ($resultado) {
                return response()->json([
                    'status' => true,
                    'nameZip' => $tickets[0]->payment->name.'.zip'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'msj' => 'Error creando pdf'
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'msj' => 'Error creando zip'
            ]);
        }
    }

    public function codes($id) {
        $event = Event::where('id', $id)->where('user_id', auth()->user()->id)->first();
        if (!empty($event)) {
            $tickets = Ticket::where('event_id', $id)->get();
            $codes = Code::with(['tickets'])->whereHas('tickets.event', function($query) {
                $query->where('user_id', auth()->user()->id);
            })->whereHas('tickets', function($query) use($event) {
                $query->where('event_id', $event->id);
            })->where('status', 1)->get();
            return view('customers.codes')->with(['event' => $event, 'event_id' => $event->id, 'event_url' => $event->url, 'tickets' => $tickets, 'codes' => $codes]);
        } else {
            return redirect('/home');
        }
    }

    public function saveCode(Request $request) {
        // dd($request->all());
        if (!empty($request->customer_name) && !empty($request->email) && !empty($request->password) && !empty($request->password_confirm)) {
            if ($request->password == $request->password_confirm) {
                $client = new \GuzzleHttp\Client();
                $client->request('POST', 'https://influencer.ticketland.mx/api/registro', [
                    'form_params' => [ 'name' => $request->customer_name, 'email' => $request->email, 'password' => $request->password ]
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'error' => 'passwords_incorrect'
                ]);
            }
        }
        if (empty($request->code_id)) {
            $code = Code::create([
                'ticket_id' => $request->ticket_id,
                'email' => $request->email,
                'customer_name' => $request->customer_name,
                'code' => strtoupper($request->code),
                'quantity' => $request->quantity,
                'expiration' => $request->expiration,
                'discount' => $request->discount,
            ]);
            $code->tickets()->sync($request->ticket_id);
            $code = Code::with(['tickets'])->where('id', $code->id)->first();
        } else {
            $code = Code::with(['tickets'])->where('id', $request->code_id)->first();
            $code->email = $request->email;
            $code->customer_name = $request->customer_name;
            $code->code = $request->code;
            $code->quantity = $request->quantity;
            $code->expiration = $request->expiration;
            $code->discount = $request->discount;
            $code->tickets()->sync($request->ticket_id);
            $code->save();
            $code = Code::with(['tickets'])->where('id', $request->code_id)->first();
        }
        return response()->json([
            'status' => true,
            'code' => $code
        ]);
    }

    public function deleteCode(Request $request) {
        $code = Code::where('id', $request->code_id)->delete();
        return response()->json([
            'status' => true
        ]);
    }
    public function generateCourtesies(Request $request) {
        $ticket = Ticket::where('name', 'Cortesías')->where('event_id', $request->event_id)->first();
        // dd($ticket);
        if (empty($ticket)) {
            $ticket = Ticket::create([
                'event_id' => $request->event_id,
                'name' => 'Cortesías',
                'price' => 0,
                'quantity' => $request->quantity,
                'valid' => 0,
                'start_sale' => date('Y-m-d'),
                'stop_sale' => date('Y-m-d'),
                'status' => 0
            ]);
        } else {
            $ticket->quantity = $ticket->quantity + intval($request->quantity);
            $ticket->save();
        }
        $paymentFree = Payment::where('name', 'cortesias')->where('status', 'pay_free')->where('event_id', $request->event_id)->first();
        if (empty($paymentFree)) {
            $paymentFree = Payment::create([
                'event_id' => $request->event_id,
                'name' => 'cortesias',
                'email' => 'cortesias@mail.com',
                'phone' => '0123456789',
                'reference' => '0000',
                'type' => 'card',
                'amount' => 0,
                'status' => 'pay_free'
            ]);
        }

        $zip = new ZipArchive();
        $zipName = uniqid();
        $filename = 'media/zips/'.$zipName.'.zip';
        
        if($zip->open($filename, ZIPARCHIVE::CREATE) === true) {
            for ($i = 0; $i < $request->quantity; $i++) { 
                $folio = strtoupper(uniqid());
                $code_QR = QrCode::backgroundColor(255, 125, 0, 0.5)->size(550)->format('png')->generate($folio, 'media/qr/'.$folio.'.png');
                $url = 'media/qr/'.$folio.'.png';
                $name = basename($url);
                $zip->addFile($url, $name);
                Access::create([
                    'payment_id' => $paymentFree->id,
                    'ticket_id' => $ticket->id,
                    'folio' => $folio,
                    'quantity' => 1
                ]);
            }
            $result = $zip->close();
            if ($result) {
                return response()->json([
                    'status' => true,
                    'nameZip' => asset('media/zips/'.$zipName.'.zip')
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'msj' => 'Error creando pdf'
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'msj' => 'Error creando zip'
            ]);
        }
    }
}