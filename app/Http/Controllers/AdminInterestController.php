<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interest;

class AdminInterestController extends Controller
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
    
    public function index() {

       $interest = Interest::all();    
       return response()->json(['data' =>['success' => true, 'interest' => $interest]], 200); 
    }

    public function store(Request $request, Interest $interest) {

        $this->validate($request, [
            'title'  => 'required',
        ]); 

        $interest->title = $request->input('title');
        $interest->save();   
        return response()->json(['data' =>['success' => true, 'messsage' => 'New Interest Added' . $interest->title ]], 201); 
    }

    public function update(Request $request, $interest_id) {
        $this->validate($request, [
            'title'  => 'required',
        ]);

        $data = Interest::findOrfail($interest_id);

        $data->title = $request->input('title');
        $data->save();
        return response()->json(['data' =>['success' => true, 'messsage' => 'Interest Updated']], 200);
    }


    public function destroy($interest_id) {

        $data = Interest::findOrfail($interest_id);
        $data->delete();
        return response()->json(['data' =>['success' => true, 'messsage' => 'Interest Deleted']], 200);
    }


}