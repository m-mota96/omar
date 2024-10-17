<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Event;
use App\Payment;
use App\Code;
use App\Access;

class CronjobController extends Controller {

    public function __construct() {
        date_default_timezone_set('America/Mexico_City');
    }

    public function cronjob($activitie) {
        switch ($activitie) {
            case 'updateStatusEvent':
                $this->updateStatusEvent();
                break;
            case 'checkPaymentsExpired':
                $this->checkPaymentsExpired();
                break;
        }
    }

    public function updateStatusEvent() {
        $currentDate = date('Y-m-d');
        $events = Event::with(['eventDates'])->where('status', 1)->get();
        foreach ($events as $key => $e) {
            $pos = sizeof($e->eventDates) - 1;
            if ($currentDate > $e->eventDates[$pos]->date) {
                $e->status = 2;
                $e->save();
            }
        }
    }
    
    public function checkPaymentsExpired() {
        $events = Event::where('status', 1)->get();
        foreach ($events as $key => $e) {
            $payments = Payment::
            with(['accesses.code'])
            ->where('event_id', $e->id)
            ->where('type', 'oxxo')
            ->where('status', 'pending')
            ->where('created_at', '<=', DB::raw('CURDATE() - INTERVAL 2 DAY'))
            ->get();
            foreach ($payments as $key2 => $p) {
                foreach ($p->accesses as $key3 => $a) {
                    if (!empty($a->code_id)) {
                        $ticketId = $a->ticket_id;
                        $code = Code::with(['tickets' => function($query) use($ticketId) {
                            $query->where('ticket_id', $ticketId);
                        }])
                        ->whereHas('tickets', function($query) use($ticketId) {
                            $query->where('ticket_id', $ticketId);
                        })
                        ->where('id', $a->code_id)->first();
                        $code->tickets()->detach([$code->id, $code->tickets[0]->id]);
                        $code->tickets()->attach($code->id, [
                            'ticket_id' => $code->tickets[0]->id,
                            'used' => $code->tickets[0]->pivot->used,
                            'reserved' => $code->tickets[0]->pivot->reserved - 1
                        ]);
                    }
                }
                $p->status = 'expired';
                $p->save();
            }
        }
    }

    public function searchCodes(Request $request) {
        $codes = Code::with(['accesses_payed.ticket'])->where('email', $request->email)->get();
        foreach($codes as $key => $c) {
            $total = 0;
            foreach ($c->accesses_payed as $key2 => $a) {
                $a->pivot->ticket_price_discount = $a->pivot->ticket_price - ($a->pivot->ticket_price * ($a->pivot->discount / 100));
                $a->pivot->profits = ($a->pivot->ticket_price - ($a->pivot->ticket_price * ($a->pivot->discount / 100))) * .10;
                $total = $total + $a->pivot->profits;
            }
            $c->total = $total;
            // $used = 0;
            // if (sizeof($c->tickets) > 0) {
            //     foreach($c->tickets as $key2 => $t) {
            //         $used = $used + $t->pivot->used;
            //     }
            // }
            // $c->used = $used;
        }
        return response()->json([
            'codes' => $codes
        ]);
    }
}
