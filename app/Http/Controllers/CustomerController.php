<?php

namespace App\Http\Controllers;

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
use DateTime;

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
        $payment = Payment::with(['accesses.ticket', 'event'])->where('id', $request->input('payment_id'))->first();
        $payment->email = $request->input('email');
        $payment->save();
        $tickets = Array();
        $folios = Array();
        $quantities = Array();
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
        }
        Mail::to($request->input('email'))->send(new SendTickets($payment->event, $folios, $tickets, $payment->name, $quantities, $total, $commission));
        return response()->json([
            'status' => true
        ]);
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

    public function getCategories(){
        $categories = Category::all();
        return ['categories'=>$categories];
    }
}