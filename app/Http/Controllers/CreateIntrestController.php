<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interest;

class CreateIntrestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin.auth');
    }

    
    public function index(Request $request) {

       $interest = Interest::all();    
       return response()->json(['data' =>['success' => true, 'interest' => $interest]], 200); 
    }

    public function store(Request $request, Interest $interest) {

        $this->validate($request, [
            'interest'  => 'required',
        ]); 

        $interest->interest = $request->input('interest');
        $interest->save();   
        return response()->json(['data' =>['success' => true, 'message' => 'New Interest Added']], 201); 
    }

    public function update(Request $request, $interest_id) {
        $this->validate($request, [
            'interest'  => 'required',
        ]);

        $data = Interest::findOrfail($interest_id);

        $data->interest = $request->input('interest');
        $data->save();
        return response()->json(['data' =>['success' => true, 'data' => $data, 'message' => 'Interest Updated']], 200);
    }


    public function destroy(Request $request, $interest_id) {

        $data = Interest::findOrfail($interest_id);
        $data->delete();
        return response()->json(['data' =>['success' => true, 'message' => 'Interest Deleted']], 200);
    }


}