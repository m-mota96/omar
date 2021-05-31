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
        $users = User::with(['taxData', 'bankData'])->whereHas('taxData', function($query) {
            return $query->where('legal_representative', '!=', null);
        })->whereHas('bankData', function($query) {
            return $query->where('bank', '!=', null);
        })->whereHas('documentsValidated')->get();
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
}