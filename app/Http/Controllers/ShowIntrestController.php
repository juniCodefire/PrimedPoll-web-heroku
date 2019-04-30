<?php

namespace App\Http\Controllers;

use App\Intrest;

class ShowIntrestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function index() {

       $intrest = Intrest::all();    
       return response()->json(['data' =>['success' => true, 'intrest' => $intrest]], 200); 
    }

}