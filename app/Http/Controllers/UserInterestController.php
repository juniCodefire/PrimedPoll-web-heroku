<?php

namespace App\Http\Controllers;

use App\Poll;
use App\User;
use App\Interest;
use App\Userinterest;
use Faker\Factory as Faker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserInterestController extends Controller
{    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function index()
    {
        $interest = Userinterest::where('owner_id', Auth::user()->id)->with('interest')->get();

        return response()->json($interest, 200);

    }

    public function show($id)
    {   
        $interest = Userinterest::findOrFail($id);

        if(Auth::user()->id == $interest->owner_id)
            {
                return response()->json($interest, 200);
            }
            return response()->json('Unauthorized Access!', 400);
    }

    public function showAllIntrerestPoll($interest_id)
    {   
        $interest = Interest::where('id', $interest_id)->exists();

            if ($interest){

                $poll = Poll::where('interest_id', $interest_id)
                    ->with('options')
                    ->withCount('votes')
                    ->latest()->get();
                
                if ($poll){
                    
                    return response()->json($poll, 200);
                } return response()->json('Poll Does not Exist', 401);
            
            } return response()->json('Interest Does not Exist', 401);
        
    }

     
    public function destroy(Request $request, $id)
    {
        $interest = Userinterest::findOrFail($id);

        if(Auth::user()->id == $interest->owner_id)
        {
                $interest->delete();

                $res['status'] = "Deleted Successfully!";
                return response()->json($res, 201);
            
        } return response()->json('Unauthorized Acess', 400);
    }


}