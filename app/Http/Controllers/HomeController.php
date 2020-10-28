<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\User;
use App\Event;
use App\Ticket;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
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
            return view('customers.index');
        } elseif($user->role_id == 2) {
            $status = 1;
            switch ($search) {
                case null:
                    $status = 1;
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
                $events = Event::with(['profile', 'eventDates'])->addSelect(['quantity_tickets' => Ticket::selectRaw('SUM(quantity) as quantity')
                    ->whereColumn('event_id', 'events.id')
                    ->groupBy('event_id')
                ])
                ->where('user_id', auth()->user()->id)->where('status', $status)->paginate(10);
            } else {
                $events = Event::with(['profile', 'eventDates'])->addSelect(['quantity_tickets' => Ticket::selectRaw('SUM(quantity) as quantity')
                    ->whereColumn('event_id', 'events.id')
                    ->groupBy('event_id')
                ])
                ->where('user_id', auth()->user()->id)->paginate(10);
            }
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
            return view('customers.index')->with(['events' => $events, 'quantities' => $quantities]);
        }
    }
}
