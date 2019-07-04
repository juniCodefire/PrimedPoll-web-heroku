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

     public  $feeds   = [];
     public  $options = [];

    public function index($id = null)
    {
       if ($id == null) {
         $fetch_polls = DB::table('polls')
                         ->whereIn('interest_id', $this->feedspermit())
                         ->orderBy('id', 'desc')
                         ->limit(5)
                         ->get();
        }else {
          $this->Interestpermit($id);
          $fetch_polls = DB::table('polls')
                          ->where('interest_id', $id)
                          ->orderBy('id', 'desc')
                          ->limit(5)
                          ->get();
        }

        //This is the triggerQuery
        $this->triggerQuery($fetch_polls);
        return response()->json(['data' =>['success' => true, 'feeds' => $this->feeds]], 200);

    }


    public function scrolledfeeds(Request $request,$id = null, $offset) {

        if (empty($offset)) {
            return response()->json(['data' =>['error' => false, 'message' => 'offset cannot be empty']], 404);
        }
        if ($id == null) {
          $fetch_polls = DB::table('polls')
                          ->whereIn('interest_id', $this->feedspermit())
                          ->offset($offset)
                          ->orderBy('id', 'desc')
                          ->limit(5)
                          ->get();
         }else {
           $this->Interestpermit($id);
           $fetch_polls = DB::table('polls')
                           ->where('interest_id', $id)
                           ->offset($offset)
                           ->orderBy('id', 'desc')
                           ->limit(5)
                           ->get();
         }
        //This is the triggerQuery
        $this->triggerQuery($fetch_polls);

        $offset += 5;
        return response()->json(['data' =>['success' => true, 'scrolled_feeds' => $this->feeds, 'new_offset' => $offset]], 200);

  }

  public function usersFeeds()
  {
     $fetch_polls = DB::table('polls')
                    ->where('interest_id', $id)
                    ->orderBy('id', 'desc')
                    ->limit(20)
                    ->get();

      //This is the triggerQuery
      $this->triggerQuery($fetch_polls);
      return response()->json(['data' =>['success' => true, 'usersfeeds' => $this->feeds]], 200);

  }



  public function feedspermit() {
    $check_interest = Userinterest::where('owner_id', Auth::user()->id)->pluck('interest_id');
    return $check_interest;
  }
  public function Interestpermit($id) {
    $id_interest = Interest::where('id', $id)->exists();
    if (!$id_interest) {
      return response()->json(['data' =>['error' => false, 'message' => 'Interest id']], 404);
    }
  }

  public function triggerQuery($fetch_polls) {
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
                array_push($this->options, $values);
            }


            $data = [
                'interest_id' => $fetch_poll->interest_id,
                'poll_id'   => $fetch_poll->id,
                'poll'      => $fetch_poll->question,
                'interest'  => $fetch_interest->title,
                'poll_owner_id' => $fetch_poll->owner_id,
                'firstname' => $fetch_user->first_name,
                'lastname'  => $fetch_user->last_name,
                'image_link'=> 'https://res.cloudinary.com/getfiledata/image/upload/w_200,c_thumb,ar_4:4,g_face/',
                'image'     => $fetch_user->image,
                'option'    => $this->options,

            ];
             array_push($this->feeds, $data);
             $this->options = [];
    }
  }
}
