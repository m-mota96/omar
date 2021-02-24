<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Document;

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
        $users = User::with(['taxData'])->whereHas('taxData', function($query) {
            $info = $query->where('legal_representative', '!=', null);
        })->get();
        return datatables()->of($users)->toJson();
    }
}