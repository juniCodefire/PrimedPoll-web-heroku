<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Intrest;

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

       $interest = Intrest::all();    
       return response()->json(['data' =>['success' => true, 'interest' => $interest]], 200); 
    }

    public function store(Request $request, Intrest $interest) {

        $this->validate($request, [
            'interest'  => 'required',
        ]); 

        $interest->intrest = $request->input('interest');
        $interest->save();   
        return response()->json(['data' =>['success' => true, 'messsage' => 'New Interest Added']], 201); 
    }

    public function update(Request $request, $interest_id) {
        $this->validate($request, [
            'interest'  => 'required',
        ]);

        $data = Intrest::findOrfail($interest_id);

        $data->intrest = $request->input('interest');
        $data->save();
        return response()->json(['data' =>['success' => true, 'messsage' => 'Interest Updated']], 200);
    }


    public function destroy(Request $request, $interest_id) {

        $data = Intrest::findOrfail($interest_id);
        $data->delete();
        return response()->json(['data' =>['success' => true, 'messsage' => 'Interest Deleted']], 200);
    }


}