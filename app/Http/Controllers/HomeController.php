<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\User;
use App\Event;
use App\Ticket;
use App\Access;
use App\Payment;
use App\EventDate;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        date_default_timezone_set('America/Mexico_City');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($search = null)
    {
        $user = Auth::user();
        if ($user->role_id == 1) {
            return view('admin.index');
        } elseif($user->role_id == 2) {
            $status = 1;
            $order = 'DESC';
            switch ($search) {
                case null:
                    $status = 1;
                    $order = 'ASC';
                    break;
                case 'inactive':
                    $status = 0;
                    break;
                case 'past':
                    $status = 2;
                    break;
                case 'all':
                    $status = 3;
                    break;
            }
            $quantities['actives'] = Event::where('user_id', auth()->user()->id)->where('status', 1)->get()->count();
            $quantities['inactives'] = Event::where('user_id', auth()->user()->id)->where('status', 0)->get()->count();
            $quantities['past'] = Event::where('user_id', auth()->user()->id)->where('status', 2)->get()->count();
            $quantities['all'] = Event::where('user_id', auth()->user()->id)->get()->count();
            if ($status != 3) {
                $events = Event::with(['profile', 'eventDates', 'payments' => function($query) {
                    $query->addSelect(['quantity' => Access::selectRaw('COUNT(id) as quantity')->whereColumn('payment_id', 'payments.id')->groupBy('payment_id')]);
                }])->addSelect(['quantity_tickets' => Ticket::selectRaw('SUM(quantity) as quantity')
                    ->whereColumn('event_id', 'events.id')
                    ->groupBy('event_id')
                ])->addSelect(['date' => EventDate::select('date')
                    ->whereColumn('event_id', 'events.id')
                    ->orderBy('date')->limit(1)
                ])->where('user_id', auth()->user()->id)->where('status', $status)->orderBy('date', $order)->paginate(10);
                // dd($events);
            } else {
                $events = Event::with(['profile', 'eventDates', 'payments' => function($query) {
                    $query->addSelect(['quantity' => Access::selectRaw('COUNT(id) as quantity')->whereColumn('payment_id', 'payments.id')->groupBy('payment_id')]);
                }])->addSelect(['quantity_tickets' => Ticket::selectRaw('SUM(quantity) as quantity')
                    ->whereColumn('event_id', 'events.id')
                    ->groupBy('event_id')
                ])->addSelect(['date' => EventDate::select('date')
                    ->whereColumn('event_id', 'events.id')
                    ->orderBy('date')->limit(1)
                ])->where('user_id', auth()->user()->id)->orderBy('date', $order)->paginate(10);
            }
            $total = 0;
            foreach ($events as $key => $e) {
                $e->initial_date = Carbon::parse($e->eventDates[0]->date)->locale('es')->isoFormat('D MMM Y').' - '.substr($e->eventDates[0]->initial_time, 0, 5);
                $pos = sizeof($e->eventDates) - 1;
                $e->final_date = Carbon::parse($e->eventDates[$pos]->date)->locale('es')->isoFormat('D MMM Y').' - '.substr($e->eventDates[$pos]->final_time, 0, 5);
                if(empty($e->profile->name)) {
                    $e->imageGeneral = 'general/not_image.png';
                } else {
                    $e->profile->name = 'events/'.$e->id.'/'.$e->profile->name;
                }
                foreach ($e->payments as $key2 => $p) {
                    $total = $total + $p->quantity;
                }
                $e->sales = $total;
                $total = 0;
            }
            return view('customers.index')->with(['events' => $events, 'quantities' => $quantities]);
        }
    }
}
