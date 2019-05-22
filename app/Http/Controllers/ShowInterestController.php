<?php

namespace App\Http\Controllers;

use App\Interest;

class ShowInterestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function index() {

       $interest = Interest::all();    
       return response()->json(['data' =>['success' => true, 'interest' => $interest]], 200); 
    }

}