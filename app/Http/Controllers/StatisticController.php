<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Exports\PaymentsExport;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Payment;
use App\Access;
use App\Event;
use App\EventDate;
use App\Verified;
use App\Spectator;

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
            $expired = Access::whereDate('created_at', '=', $date->format('Y-m-d'))->whereHas('payment', function($query) use($event_id) {
                return $query->where('status', 'expired')->where('event_id', $event_id)->whereHas('event', function($query2) {
                    return $query2->where('user_id', auth()->user()->id);
                });
            })->get()->count();
            $array_expired[$date->format('Y-m-d')] = $expired;
        }

        $totalSales = Access::whereHas('payment', function($query) use ($event_id) {
            return $query->where('event_id', $event_id)->where('status', 'payed');
        })->get()->count();
        $totalPending = Access::whereHas('payment', function($query) use ($event_id) {
            return $query->where('event_id', $event_id)->where('status', 'pending');
        })->get()->count();
        $totalExpired = Access::whereHas('payment', function($query) use ($event_id) {
            return $query->where('event_id', $event_id)->where('status', 'expired');
        })->get()->count();

        return response()->json([
            'status' => true,
            'sales' => $array_sales,
            'pending' => $array_pending,
            'expired' => $array_expired,
            'totalSales' => $totalSales,
            'totalPending' => $totalPending,
            'totalExpired' => $totalExpired
        ]);
    }

    public function getUltimoDiaMes($year, $month) {
        return date("d", (mktime(0,0,0,$month+1,1,$year)-1));
    }

    public function extractSales(Request $request) {
        return datatables()->of(
            Payment::where('event_id', $request->input('event_id'))->whereHas('event', function($query) {
                return $query->where('user_id', auth()->user()->id);
            })->get()
            )
        ->editColumn('created_at', function ($user) {
            return [
                'display' => Carbon::parse($user->created_at)->format('d/m/Y'),
            ];
        })
        ->toJson();
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

    public function turns($id) {
        
        $event = Event::with(['eventDates.turns.access'])->where('id', $id)->first();
        // dd($event);
        return view('customers.turns')->with(['event_url' => $event->url, 'event_id' => $id, 'event' => $event]);
    }

    public function downloadPayments($id) {
        return Excel::download(new PaymentsExport($id), 'Clientes.xlsx');
    }

    public function scan($id) {
        $event = Event::where('id', $id)->first();
        return view('customers.scan')->with(['event_url' => $event->url, 'event_id' => $id]);
    }

    public function searchAccess(Request $request) {
        $access = Access::with(['payment', 'turns.eventDate'])->where('folio', $request->input('folio'))->whereHas('payment', function($query) {
            $payment = $query->where('status', 'payed');
        })->first();
        $date = date('Y-m-d');
        // $date = '2021-05-15';
        $time = date('H:i:s');
        // $time = '12:50:00';
        $dataTurn = null;
        if (!empty($access)) {
            $idEvent = $access->payment->event_id;
            $dateEvent = EventDate::where('date', $date)->whereHas('event', function($query) use($idEvent) {
                return $query->where('user_id', auth()->user()->id)->where('id', $idEvent);
            })->first();
            if (!empty($dateEvent)) {
                foreach ($access->turns as $key => $value) {
                    if ($value->eventDate->date == $date) {
                        $dataTurn = $value;
                    }
                }
                if (!empty($dataTurn)) {
                    $initial_hour = strtotime($dataTurn->initial_hour);
                    $final_hour = strtotime($dataTurn->final_hour);
                    $time =  strtotime($time);
                    if ($initial_hour <= $time && $time <= $final_hour) {
                        $verified = Verified::where('access_id', $access->id)->where('date', $date)->first();
                        if (empty($verified)) {
                            return response()->json([
                                'status' => true,
                                'access' => $access,
                                'turns' => true
                            ]);
                        } else {
                            return response()->json([
                                'status' => false,
                                'error' => 'verified'
                            ]); 
                        }
                    } else {
                        return response()->json([
                            'status' => false,
                            'error' => 'horary_incorrect'
                        ]); 
                    }
                } else {
                    $verified = Verified::where('access_id', $access->id)->where('date', $date)->first();
                    if (empty($verified)) {
                        return response()->json([
                            'status' => true,
                            'access' => $access,
                            'turns' => true
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'error' => 'verified'
                        ]); 
                    }
                }
            } else {
                return response()->json([
                    'status' => false,
                    'error' => 'date_not_found'
                ]);
            }
        } else {
            if ($request->input('folio') == '609EF3F81CD90' || $request->input('folio') == '609EF3F6EAE6D') {
                $access['folio'] = $request->input('folio');
                return response()->json([
                    'status' => true,
                    'access' => $access,
                    'turns' => true
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'error' => 'access_not_found'
                ]);
            }
        }
    }
    
    public function validateFolio(Request $request) {
        if ($request->input('folio') == '609EF3F81CD90' || $request->input('folio') == '609EF3F6EAE6D') {
            if ($request->input('folio') == '609EF3F81CD90') {
                $cortesias = DB::table('cortesias')->where('type', 'empresarios')->where('date', date('Y-m-d'))->first();
                if (empty($cortesias)) {
                    DB::table('cortesias')->insert([
                        ['type' => 'empresarios', 'quantity' => 1, 'date' => date('Y-m-d')]
                    ]);
                } else {
                    DB::table('cortesias')->where('type', 'empresarios')->where('date', date('Y-m-d'))->update(['quantity' => $cortesias->quantity + 1]);
                }
            } else if ($request->input('folio') == '609EF3F6EAE6D') {
                $cortesias = DB::table('cortesias')->where('type', 'cortesias')->where('date', date('Y-m-d'))->first();
                if (empty($cortesias)) {
                    DB::table('cortesias')->insert([
                        ['type' => 'cortesias', 'quantity' => 1, 'date' => date('Y-m-d')]
                    ]);
                } else {
                    DB::table('cortesias')->where('type', 'cortesias')->where('date', date('Y-m-d'))->update(['quantity' => $cortesias->quantity + 1]);
                }
            }
        } else {
            $access = Access::with(['ticket'])->where('folio', $request->input('folio'))->first();
            $access->quantity = $access->quantity - 1;
            $access->save();
            Verified::create([
                'access_id' => $access->id,
                'date' => date('Y-m-d'),
                'time' => date('H')
            ]);
            $spectators = Spectator::where('event_id', $access->ticket->event_id)->whereBetween('created_at', [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')])->first();
            if (empty($spectators)) {
                Spectator::create([
                    'event_id' => $access->ticket->event_id,
                    'entry' => 1,
                    'exit' => 0,
                ]);
            } else {
                $spectators->entry = $spectators->entry + 1;
                $spectators->save();
            }
        }
        return response()->json([
           'status' => true
        ]);
    }

    public function assistance($id) {
        $event = Event::with(['eventDates'])->where('id', $id)->first();
        return view('customers.assistance')->with([
            'event_id' => $id,
            'event_url' => $event->url
        ]);
    }

    public function extractAssistence(Request $request) {
        $id = $request->input('event_id');
        $spectators = Spectator::where('event_id', $id)->whereBetween('created_at', [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')])->first();
        $cortesiasEmpresarios = DB::table('cortesias')->where('type', 'empresarios')->where('date', date('Y-m-d'))->first();
        $cortesiasCortesias = DB::table('cortesias')->where('type', 'cortesias')->where('date', date('Y-m-d'))->first();
        if (!empty($spectators)) {
            $countSpectators = $spectators->entry - $spectators->exit;
        } else {
            $countSpectators = 0;
        }
        $eventDate = EventDate::where('event_id', $id)->where('date', date('Y-m-d'))->first();
        $initial_hour = substr($eventDate->initial_time, 0, 2);
        $final_hour = substr($eventDate->final_time, 0, 2);
        $count = intval($final_hour) - intval($initial_hour);
        for ($i = 0; $i < $count; $i++) { 
            $verifieds = Verified::select(DB::raw("SUM(quantity) as quantity"), 'time')->where('time', intval($initial_hour))->where('date', date('Y-m-d'))->whereHas('access.ticket', function($query) use($id) {
                return $query->where('event_id', $id);
            })->groupBy('time')->first();
            $taquilla = Verified::select(DB::raw("SUM(quantity) as quantity"), 'time')->whereNull('access_id')->where('time', intval($initial_hour))->where('date', date('Y-m-d'))->groupBy('time')->first();
            $assistence[$i] = (empty($verifieds->quantity)) ? 0 : intval($verifieds->quantity);
            $array_taquilla[$i] = (empty($taquilla->quantity)) ? 0 : intval($taquilla->quantity);
            $initial_hour++;
        }
        return response()->json([
            'eventDate' => $eventDate,
            'assistence' => $assistence,
            'taquilla' => $array_taquilla,
            'spectators' => $countSpectators,
            'exhibitors' => (empty($cortesiasEmpresarios)) ? 0 : $cortesiasEmpresarios,
            'courtesies' => (empty($cortesiasCortesias)) ? 0 : $cortesiasCortesias
        ]);
    }

    public function addAssistenceExternal(Request $request) {
        $spectators = Spectator::where('event_id', 1)->whereBetween('created_at', [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')])->first();
        Verified::create([
            'quantity' => $request->input('quantity'),
            'date' => date('y-m-d'),
            'time' => date('H'),
        ]);
        if (empty($spectators)) {
            Spectator::create([
                'event_id' => 1,
                'entry' => $request->input('quantity'),
                'exit' => 0,
            ]);
        } else {
            $spectators->entry = $spectators->entry + $request->input('quantity');
            $spectators->save();
        }
        return response()->json([
            'status' => true
        ]);
    }

    public function discount(Request $request) {
        $spectators = Spectator::where('event_id', 1)->whereBetween('created_at', [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')])->first();
        if (!empty($spectators)) {
            if (($spectators->entry - $spectators->exit) > 0) {
                $spectators->exit = $spectators->exit + $request->input('quantity');
                $spectators->save();
            }
        }
        return response()->json([
            'status' => true
        ]);
    }
}
