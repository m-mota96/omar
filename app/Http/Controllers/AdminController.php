<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exclusivity;
use App\Event;
use App\GalleryEvent;
use App\EventDate;
use App\LocationEvent;
use App\Ticket;

class AdminController extends Controller {

    public function __construct() {
        date_default_timezone_set('America/Mexico_City');
    }

    public function checkEvent(Request $request) {
        $name = Exclusivity::where(DB::raw('BINARY name'), $request->input('name_event'))->first();
        $website = Exclusivity::where(DB::raw('BINARY name'), $request->input('website'))->first();
        if(!empty($name)) {
            if($name->user_id == auth()->user()->id) {
                $name_available = true;
            } else {
                $name_available = false;
            }
        } else {
            $name_available = true;
        }
        if(!empty($website)) {
            if($website->user_id == auth()->user()->id) {
                $website_available = true;
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
        ]);

        for ($i = 0; $i < sizeof($request->input('dates')); $i++) {
            EventDate::create([
                'event_id' => $event->id,
                'date' => $request->input('dates')[$i],
                'initial_time' => $request->input('initial_times')[$i],
                'final_time' => $request->input('final_times')[$i]
            ]);
        }

        Ticket::create([
            'event_id' => $event->id,
            'name' => 'Boleto 1',
            'price' => 100,
            'quantity' => 50,
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
        $event = Event::with(['profile', 'logo', 'eventDates', 'location'])->where('id', $id)->first();
        $event->initial_date = Carbon::parse($event->eventDates[0]->date)->locale('es')->isoFormat('D MMM Y').' - '.substr($event->eventDates[0]->initial_time, 0, 5);
        $pos = sizeof($event->eventDates) - 1;
        $event->final_date = Carbon::parse($event->eventDates[$pos]->date)->locale('es')->isoFormat('D MMM Y').' - '.substr($event->eventDates[$pos]->final_time, 0, 5);
        return view('customers.editEvent')->with(['event' => $event, 'event_id' => $event->id]);
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
        $event->save();
        return response()->json([
            'status' => true
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
        $tickets = Ticket::with(['event'])->where('event_id', $id)->get();
        return view('customers.tickets')->with(['tickets' => $tickets, 'event_id' => $id]);
    }

    public function saveTicket(Request $request) {
        if (!empty($request->input('ticket_id'))) {
            $ticket = Ticket::where('id', $request->input('ticket_id'))->first();
            $ticket->name = $request->input('name');
            $ticket->description = $request->input('description');
            $ticket->price = $request->input('price');
            $ticket->quantity = $request->input('quantity');
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
                'start_sale' => $request->input('start_sale'),
                'stop_sale' => $request->input('stop_sale'),
                'min_reservation' => $request->input('min_reservation'),
                'max_reservation' => $request->input('max_reservation')
            ]);
            $operation = 'save';
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
}