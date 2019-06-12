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
        $user = Auth::user();

        $interest = $user->interest()->get();

        return response()->json($interest, 200);
    }

    public function show($id)
    {   
        $user = Auth::user();

        $interest = $user->interest()->where('interest_id', $id)->first();

        if($interest)
            {
                return response()->json($interest, 200);
            }
        return response()->json('You are not subscribed to this interest!', 400);
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
                } return response()->json('Poll Does not Exist', 404);
            
            } return response()->json('Interest Does not Exist', 404);
        
    }
    
    public function showNotSubscribedInterest()
    {
        $not_subscribed_interest = Interest::whereNotIn('id',  $this->feedspermit())->get();

        $msg['not_subscribe_interest'] = $not_subscribed_interest;

        return response()->json($msg, 200);
    }

    public function create(Request $request) {
        $user = Auth::user();
    
        $this->validateInterests($request);
        try{
            $interest_ids = $request->input('interest_ids');

            $interest = $user->interest()->syncWithoutDetaching($interest_ids);
            
            $msg['success'] = "New Interest Added";
            $msg['interests'] = $user->interest()->get();
            $msg['interest'] = $interest;

            return response()->json($msg, 200);

        }catch (\Exception $e) {

            return response()->json(['message'=> "Opps! Something went wrong!"], 400);
        }
    }

    public function destroy(Request $request, $id)
    {
        $user = Auth::user();
        $interest = $user->interest()->detach($id);

        if($interest)
            {
                return response()->json(['message' => 'Interest removed successully'], 200);
            }
        return response()->json('You are not subscribed to this interest!', 400);
    }

    public function validateInterests(Request $request) 
    {
        $rules = [
           'interest_ids'   => 'required|array|min:5',
           'interest_ids.*' => 'required|integer'
        ];
        $messages = [
            'required' => ':attribute is required'
        ];
        $this->validate($request, $rules);
    }

    public function feedspermit() {
        $check_interest = Userinterest::where('owner_id', Auth::user()->id)->pluck('interest_id');
        return $check_interest;
    }


}