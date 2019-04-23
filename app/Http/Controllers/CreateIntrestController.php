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

       $intrest = Intrest::all();    
       return response()->json(['data' =>['success' => true, 'intrest' => $intrest]], 200); 
    }

    public function store(Request $request, Intrest $intrest) {

        $this->validate($request, [
            'intrest'  => 'required',
        ]); 

        $intrest->intrest = $request->input('intrest');
        $intrest->save();   
        return response()->json(['data' =>['success' => true, 'messsage' => 'New Intrest Added']], 201); 
    }

    public function update(Request $request, $intrest_id) {
        $this->validate($request, [
            'intrest'  => 'required',
        ]);

        $data = Intrest::findOrfail($intrest_id);

        $data->intrest = $request->input('intrest');
        $data->save();
        return response()->json(['data' =>['success' => true, 'messsage' => 'Intrest Updated']], 200);
    }


    public function destroy(Request $request, $intrest_id) {

        $data = Intrest::findOrfail($intrest_id);
        $data->delete();
        return response()->json(['data' =>['success' => true, 'messsage' => 'Intrest Deleted']], 200);
    }


}