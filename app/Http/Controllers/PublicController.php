<?php

namespace App\Http\Controllers;

require_once('bin/conekta-php-master/lib/Conekta.php');
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Event;

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

    }
}
