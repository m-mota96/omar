<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Payment;
use App\Access;
use App\Event;

class StatisticController extends Controller
{

    public function __construct() {
        date_default_timezone_set('America/Mexico_City');
    }

    public function stats($id) {
        $payments = Payment::where('event_id', $id)->where('status', 'payed')->whereHas('event', function($query) {
            return $query->where('user_id', auth()->user()->id);
        })
        ->groupBy('type')->select('type', DB::raw('SUM(amount) as total'))->get();
        $event = Event::where('id', $id)->where('user_id', auth()->user()->id)->first();
        // dd($payments);
        return view('customers.stats')->with([
            'event_id' => $id,
            'event_url' => $event->url,
            // 'final_day' => $final_day,
            // 'sales' => $array_sales,
            // 'pending' => $array_pending,
            // 'total_sales' => $totalSales,
            // 'total_pending' => $totalPending,
            // 'moneySales' => $moneySales,
            // 'moneyPending' => $moneyPending,
            'payments' => $payments
        ]);
    }

    public function chargingGraphic(Request $request) {
        $start_date = Carbon::parse($request->input('initial_date'));
        $end_date = Carbon::parse($request->input('final_date'));
        $array_sales = [];
        $array_pending = [];
        $event_id = $request->input('event_id');

        for($date = $start_date; $date->lte($end_date); $date->addDay()) {
            $payed = Access::whereDate('created_at', '=', $date->format('Y-m-d'))->whereHas('payment', function($query) use($event_id) {
                return $query->where('status', 'payed')->where('event_id', $event_id)->whereHas('event', function($query2) {
                    return $query2->where('user_id', auth()->user()->id);
                });
            })->get()->count();
            $array_sales[$date->format('Y-m-d')] = $payed;
            $pending = Access::whereDate('created_at', '=', $date->format('Y-m-d'))->whereHas('payment', function($query) use($event_id) {
                return $query->where('status', 'pending')->where('event_id', $event_id)->whereHas('event', function($query2) {
                    return $query2->where('user_id', auth()->user()->id);
                });
            })->get()->count();
            $array_pending[$date->format('Y-m-d')] = $pending;
        }
        return response()->json([
            'status' => true,
            'sales' => $array_sales,
            'pending' => $array_pending,
        ]);
    }

    public function getUltimoDiaMes($year, $month) {
        return date("d", (mktime(0,0,0,$month+1,1,$year)-1));
    }

    public function extractSales(Request $request) {
        $payments = Payment::where('event_id', $request->input('event_id'))->whereHas('event', function($query) {
            return $query->where('user_id', auth()->user()->id);
        })->get();
        return datatables()->of($payments)->toJson();
    }

    public function reservations($id) {
        $event = Event::where('id', $id)->where('user_id', auth()->user()->id)->first();
        return view('customers.reservations')->with(['event_id' => $event->id, 'event_url' => $event->url]);
    }

    public function detailsSale(Request $request) {
        $access = Access::with(['ticket'])->where('payment_id', $request->input('payment_id'))->get();
        return response()->json([
            'status' => true,
            'data' => $access
        ]);
    }
}
