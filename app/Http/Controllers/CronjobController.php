<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Event;
use App\Payment;
use App\Code;

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
}
