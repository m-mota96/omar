<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Payment;
use App\Access;

class StatisticController extends Controller
{

    public function __construct() {
        date_default_timezone_set('America/Mexico_City');
    }

    public function stats($id) {
        // $payments = Access::with(['payment'])->whereHas('payment', function($query) use($id) {
        //     $query->where('status', 'payed')->where('event_id', $id);
        // })->get()->groupBy(function($date) {
        //     return Carbon::parse($date->created_at)->format('Y-m-d');
        // });
        // dd($payments);
        // foreach ($payments as $key => $p) {
        //     $diasel = intval(date("d", strtotime('2020-11-03')));
        //     dd($diasel);
        // }
        // $final_day = date("d", (mktime(0, 0, 0, 11+1, 1, 2020)-1));
        // for ($i=0; $i < $final_day; $i++) { 
        //     $diasel = intval(date("d", strtotime($venta->created_at)));
        //     // if($)
        //     // $sales[$i] = 
        // }
        // // dd($payments);
        $start_day = 1;
        $final_day = $this->getUltimoDiaMes(date('Y'), date('m'));
        $initial_date = date("Y-m-d H:i:s", strtotime(date('Y')."-".date('m')."-".$start_day));
        $final_date = date("Y-m-d H:i:s", strtotime(date('Y')."-".date('m')."-".$final_day));
        $ticket_payed = Payment::where('status', 'payed')->where('event_id', $id)->whereHas('event', function($query) {
            return $query->where('user_id', auth()->user()->id);
        })->get();
        $ticket_pending = Payment::where('status', 'pending')->where('event_id', $id)->whereHas('event', function($query) {
            return $query->where('user_id', auth()->user()->id);
        })->get();
        $array_sales = Array();
        $array_pending = Array();
        $totalSales = 0;
        $totalPending = 0;
        $moneySales = 0;
        $moneyPending= 0;
        for ($i=1; $i <= $final_day; $i++) { 
            $array_sales[$i] = 0;
            $array_pending[$i] = 0;
        } 
        for ($i=0; $i < sizeof($ticket_payed); $i++) { 
            $sales = Access::whereDate('created_at', '<=', $final_date)->whereDate('created_at', '>=', $initial_date)->where('payment_id', $ticket_payed[$i]->id)->get();
            foreach ($sales as $s) {
                $diasel = intval(date("d", strtotime($s->created_at)));
                $array_sales[$diasel]++;
                $totalSales++;
            }
            $moneySales = $moneySales + $ticket_payed[$i]->amount;
        }
        for ($i=0; $i < sizeof($ticket_pending); $i++) { 
            $sales = Access::whereDate('created_at', '<=', $final_date)->whereDate('created_at', '>=', $initial_date)->where('payment_id', $ticket_pending[$i]->id)->get();
            foreach ($sales as $s) {
                $diasel = intval(date("d", strtotime($s->created_at)));
                $array_pending[$diasel]++;
                $totalPending++;
            }
            $moneyPending = $moneyPending + $ticket_pending[$i]->amount;
        }
        return view('customers.stats')->with([
            'event_id' => $id,
            'final_day' => $final_day,
            'sales' => $array_sales,
            'pending' => $array_pending,
            'total_sales' => $totalSales,
            'total_pending' => $totalPending,
            'moneySales' => $moneySales,
            'moneyPending' => $moneyPending
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
        return view('customers.reservations')->with(['event_id' => $id]);
    }

    public function detailsSale(Request $request) {
        $access = Access::with(['ticket'])->where('payment_id', $request->input('payment_id'))->get();
        return response()->json([
            'status' => true,
            'data' => $access
        ]);
    }
}
