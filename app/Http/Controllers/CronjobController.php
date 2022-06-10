<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Event;
use App\Payment;

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
            $payments = Payment::where('event_id', $e->id)->where('type', 'oxxo')->where('status', 'pending')->where('created_at', '<=', DB::raw('CURDATE() - INTERVAL 2 DAY'))->get();
            foreach ($payments as $key2 => $p) {
                $p->status = 'expired';
                $p->save();
            }
        }
    }
}
