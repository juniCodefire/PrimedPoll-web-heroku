<?php

namespace App\Http\Controllers;

use App\Poll;
use App\User;
use App\Interest;
use App\Option;
use Illuminate\Http\Request;
use App\Userinterest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserFeedsController extends Controller
{    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function index()
    {
        $fetch_polls = DB::table('polls')
                        ->whereIn('interest_id', $this->feedspermit())
                        ->orderBy('id', 'desc')
                        ->limit(5)
                        ->get();

        $feeds   = [];
        $options = [];

        foreach ($fetch_polls as $fetch_poll) {              
                //Fetch the user info 
                $fetch_user     = User::where('id', $fetch_poll->owner_id)->first();
                //Fetch the user interest 
                $fetch_interest = Interest::where('id', $fetch_poll->interest_id)->first();
                //Fetch the user options 
                $fetch_options  =  Option::where('poll_id', $fetch_poll->id)
                                    ->where('owner_id', $fetch_poll->owner_id)
                                    ->select('id', 'option')
                                    ->get();
                //Get the whole option related to the poll
                foreach ($fetch_options as $fetch_option) {
                    $values = [
                        'option_id' => $fetch_option->id,
                        'option'    => $fetch_option->option 
                    ];
                    array_push($options, $values);
                }
                $data = [
                    'poll_id'   => $fetch_poll->id,
                    'poll'      => $fetch_poll->question,
                    'interest'  => $fetch_interest->title,
                    'poll_owner_id' => $fetch_poll->owner_id,
                    'firstname' => $fetch_user->first_name,
                    'lastname'  => $fetch_user->last_name,
                    'image'     => $fetch_user->image,
                    'option'    => $options

                ];
                 array_push($feeds, $data);
                 $options = [];
            }
        return response()->json(['data' =>['success' => true, 'feeds' => $feeds]], 200);

    }


    public function scrolledfeeds(Request $request, $offset) {

        if (empty($offset)) {
            return response()->json(['data' =>['error' => false, 'message' => 'offset cannot be empty']], 404);
        }

        $fetch_polls = DB::table('polls')
                        ->whereIn('interest_id', $this->feedspermit())
                        ->offset($offset)
                        ->orderBy('id', 'desc')
                        ->limit(5)
                        ->get();

        $feeds   = [];
        $options = [];

        foreach ($fetch_polls as $fetch_poll) {            
                //Fetch the user info 
                $fetch_user     = User::where('id', $fetch_poll->owner_id)->first();
                //Fetch the user interest 
                $fetch_interest = Interest::where('id', $fetch_poll->interest_id)->first();
                //Fetch the user options 
                $fetch_options  = Option::where('poll_id', $fetch_poll->id)
                                    ->select('id', 'option')
                                    ->get();
                //Get the whole option related to the poll
                foreach ($fetch_options as $fetch_option) {
                    $values = [
                        'option_id' => $fetch_option->id,
                        'option'    => $fetch_option->option 
                    ];
                    array_push($options, $values);
                }


                $data = [
                    'poll_id'   => $fetch_poll->id,
                    'poll'      => $fetch_poll->name,
                    'interest'  => $fetch_interest->title,
                    'poll_owner_id' => $fetch_poll->owner_id,
                    'firstname' => $fetch_user->first_name,
                    'lastname'  => $fetch_user->last_name,
                    'image'     => $fetch_user->image,
                    'option'    => $options

                ];
                 array_push($feeds, $data);
        }
        $options = [];
        $offset += 5; 
        return response()->json(['data' =>['success' => true, 'scrolled_feeds' => $feeds, 'new_offset' => $offset]], 200);
     
  }

  public function feedspermit() {
    $check_interest = Userinterest::where('owner_id', Auth::user()->id)->pluck('interest_id');
    return $check_interest;
  }



}