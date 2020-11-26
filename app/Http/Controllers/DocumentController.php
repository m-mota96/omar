<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Document;
use App\User;

class DocumentController extends Controller
{
    public function documents() {
        $documents = Document::where('user_id', auth()->user()->id)->get();
        return view('customers.account')->with(['documents' => $documents]);
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
}
