<?php

namespace App\Http\Controllers;

use App\Poll;
use App\Interest;
use Illuminate\Http\Request;

class InterestController extends Controller
{    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function index()
    {
        $interest = Interest::all();

        return response()->json($interest, 200);
    }

    public function show($id)
    {   
        $check = Interest::where('id', $id)->exists();

        if($check)
        {
            $interest = Interest::where('id', $id)->with('poll')->get();
            return response()->json($interest, 200);
        
        } return response()->json("Interest Does Not Exist", 200);
    }
}