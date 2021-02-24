<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Document;
use App\User;
use App\TaxData;
use App\BankData;

class DocumentController extends Controller
{

    public function __construct() {
        date_default_timezone_set('America/Mexico_City');
    }

    public function documents() {
        // $documents = Document::where('user_id', auth()->user()->id)->get();
        $user = User::with(['documents', 'taxData', 'bankData'])->where('id', auth()->user()->id)->first();
        $orderDocs = ['acta', 'comprobante_domicilio', 'comprobante_bancario', 'identificacion'];
        // dd($user->documents);
        $arrayDocs = Array();
        for ($i = 0; $i < sizeof($orderDocs); $i++) { 
            foreach ($user->documents as $key => $value) {
                if ($orderDocs[$i] == $value->type) {
                    $arrayDocs[$i] = $value;
                }
            }
        }
        // dd($arrayDocs);
        return view('customers.account')->with(['documents' => $arrayDocs, 'taxData' => $user->taxData, 'bankData' => $user->bankData]);
    }

    public function resetPassword(Request $request) {
        $user = User::where('id', auth()->user()->id)->first();
        $checkPassword = Hash::check($request->input('last_password'), $user->password);
        if ($checkPassword == true) {
            $user->password = Hash::make($request->input('password'));
            $user->save();
            return response()->json([
                'status' => true
            ]);
        } else {
            return response()->json([
                'msj' => 'password_incorrect',
                'status' => false
            ]);
        }
    }

    public function editAccount(Request $request) {
        $user = User::where('id', auth()->user()->id)->first();
        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->save();
        return response()->json([
            'status' => true
        ]);
    }

    public function saveTaxData(Request $request) {
        $taxData = TaxData::create([
            'user_id' => auth()->user()->id,
            'legal_representative' => $request->input('legal_representative'),
            'business_name' => $request->input('business_name'),
            'rfc' => $request->input('rfc'),
            'address' => $request->input('address'),
            'external_number' => $request->input('external_number'),
            'internal_number' => $request->input('internal_number'),
            'colony' => $request->input('colony'),
            'postal_code' => $request->input('postal_code'),
            'state' => $request->input('state'),
            'city' => $request->input('city'),
        ]);

        return response()->json([
            'status' => true,
            'taxData' => $taxData
        ]);
    }

    public function saveBankData(Request $request) {
        $bankData = BankData::create([
            'user_id' => auth()->user()->id,
            'name_propietary' => $request->input('name_propietary'),
            'bank' => $request->input('bank'),
            'key' => $request->input('key'),
            'number_account' => $request->input('number_account')
        ]);

        return response()->json([
            'status' => true,
            'bankData' => $bankData
        ]);
    }

    public function uploadDocuments(Request $request) {
        // dd($_FILES);
        $document = Document::where('user_id', auth()->user()->id)->where('type', $request->input('type'))->first();
        if (!empty($document)) {
            if(file_exists('media/pdf/documents/user_'.auth()->user()->id)) {
                unlink('media/pdf/documents/user_'.auth()->user()->id.'/'.$document->document);
            }
            $file = file_get_contents($_FILES['files']['tmp_name']);
            $extension = pathinfo($_FILES["files"]["name"])["extension"];
            $fileName = uniqid().".".$extension;
            file_put_contents('media/pdf/documents/user_'.auth()->user()->id.'/'.$fileName, $file);
            $document->document = $fileName;
            $document->status = 1;
            $document->save();
        } else {
            if (!file_exists('media/pdf/documents/user_'.auth()->user()->id)) {
                mkdir('media/pdf/documents/user_'.auth()->user()->id, 0777, true);
            }
            $file = file_get_contents($_FILES['files']['tmp_name']);
            $extension = pathinfo($_FILES["files"]["name"])["extension"];
            $fileName = uniqid().".".$extension;
            file_put_contents('media/pdf/documents/user_'.auth()->user()->id.'/'.$fileName, $file);
            $doc = Document::create([
                'user_id' => auth()->user()->id,
                'document' => $fileName,
                'type' => $request->input('type'),
                'status' => 1
            ]);
        }

        return response()->json([
            'status' => true,
            'nameDocument' => $fileName
        ]);
    }
}
