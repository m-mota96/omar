<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use App\Document;
use App\Event;
use App\Access;
use App\AdminPayment;
use App\GallerySlider;

class AdminController extends Controller {

    public function __construct() {
        date_default_timezone_set('America/Mexico_City');
    }

    public function documents() {
        return view('admin.documents');
    }

    public function extractUsersDocuments() {
        $users = User::with(['documents'])->where('role_id', 2)->whereHas('documents', function($query) {
            return $query->where('status', 1);
        })->get();
        return datatables()->of($users)->toJson();
    }

    public function statusDocument(Request $request) {
        $document = Document::where('id', $request->input('idDocument'))->first();
        $document->status = $request->input('status');
        $document->save();
        return response()->json([
            'status' => true
        ]);
    }

    public function contracts(Request $request) {
        return view('admin.contracts');
    }

    public function extractUsersInfo() {
        $users = User::with(['taxData', 'bankData'])->whereHas('taxData', function($query) {
            return $query->where('legal_representative', '!=', null);
        })->whereHas('bankData', function($query) {
            return $query->where('bank', '!=', null);
        })->has('documents')->get();
        return datatables()->of($users)->toJson();
    }

    public function uploadContract(Request $request) {
        $file = file_get_contents($_FILES['file']['tmp_name']);
        $extension = pathinfo($_FILES["file"]["name"])["extension"];
        $fileName = "contract".$request->input('user_id').".".$extension;
        file_put_contents('media/pdf/contracts/'.$fileName, $file);
        $user = User::where('id', $request->input('user_id'))->first();
        $user->contract = $fileName;
        $user->save();
        return response()->json([
            'status' => true
        ]);
    }

    public function deleteContract(Request $request) {
        $user = User::where('id', $request->input('user_id'))->first();
        unlink('media/pdf/contracts/'.$user->contract);
        $user->contract = null;
        $user->save();
        return response()->json([
            'status' => true
        ]);
    }

    public function events($type) {
        return view('admin.events')->with(['type' => $type]);
    }

    public function extractEvents(Request $request) {
        if ($request->input('status') != 3) {
            $events = Event::with(['eventDates', 'paymentsAgruped.accesses', 'payments' => function($query) {
                $query->addSelect(['quantity' => Access::selectRaw('COUNT(id) as quantity')->whereColumn('payment_id', 'payments.id')->groupBy('payment_id')]);
            }, 'assistance'])->where('status', $request->input('status'))->where('cost_type', $request->input('type'))->get();
        } else {
            $events = Event::with(['eventDates', 'paymentsAgruped.accesses', 'payments' => function($query) {
                $query->addSelect(['quantity' => Access::selectRaw('COUNT(id) as quantity')->whereColumn('payment_id', 'payments.id')->groupBy('payment_id')]);
            }, 'assistance'])->where('cost_type', $request->input('type'))->get();
        }
        $total = 0;
        foreach ($events as $key => $e) {
            foreach ($e->payments as $key2 => $p) {
                $total = $total + $p->quantity;
            }
            $e->sales = $total;
            $total = 0;
        }
        return datatables()->of($events)->toJson();
    }

    public function payments($status) {
        $status = ($status == 'pending') ? 0 : 1;
        return view('admin.payments')->with(['status' => $status]);
    }

    public function extractPayments(Request $request) {
        $payments = AdminPayment::with(['user', 'event'])->where('status', $request->input('status'))->get();
        return datatables()->of($payments)->editColumn('created_at', function ($payments) {
            return [
                'date' => Carbon::parse($payments->created_at)->format('d-M-Y'),
                'update' => Carbon::parse($payments->updated_at)->format('d-M-Y'),
            ];
        })->toJson();
    }

    public function changeStatusAdminPayment(Request $request) {
        $payment = AdminPayment::where('id', $request->input('payment_id'))->first();
        $payment->status = 1;
        $payment->save();
        return response()->json([
            'status' => true
        ]);
    }

    public function slider() {
        $gallery = GallerySlider::get();
        return view('admin.slider')->with(['gallery' => $gallery]);
    }

    public function saveInfoSlider(Request $request) {
        if (empty($request->input('id'))) {
            $file = file_get_contents($_FILES['file']['tmp_name']);
            $extension = pathinfo($_FILES["file"]["name"])["extension"];
            $fileName = uniqid().".".$extension;
            file_put_contents('media/sliderIndex/'.$fileName, $file);
            $gallery = GallerySlider::create([
                'title' => $request->input('title'),
                'date' => $request->input('initial_date'),
                'image' => $fileName,
            ]);
        } else {
            $gallery = GallerySlider::where('id', $request->input('id'))->first();
            $gallery->title = $request->input('title');
            $gallery->date = $request->input('initial_date');
            if (!empty($_FILES)) {
                $file = file_get_contents($_FILES['file']['tmp_name']);
                $extension = pathinfo($_FILES["file"]["name"])["extension"];
                $fileName = uniqid().".".$extension;
                file_put_contents('media/sliderIndex/'.$fileName, $file);
                unlink('media/sliderIndex/'.$gallery->image);
                $gallery->image = $fileName;
            }
            $gallery->save();
        }
        return response()->json([
            'status' => true,
            'data' => $gallery
        ]);
    }

    public function deleteInfoSlider(Request $request) {
        $slider = GallerySlider::where('id', $request->input('id'))->first();
        unlink('media/sliderIndex/'.$slider->image);
        $slider->delete();
        return response()->json([
            'status' => true
        ]);
    }

    public function categories() {
        return view('admin.categories');
    }
}