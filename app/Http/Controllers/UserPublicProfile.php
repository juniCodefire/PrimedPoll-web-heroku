<?php

namespace App\Http\Controllers;

use App\User;
use App\Poll;
use Cloudder;
use App\Interest;
use App\Userinterest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserPublicProfile extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   // public $pollsData = [];
   public function showData(User $user, $username) {
     $userData  = $user->usernameCheck($username);
     $interest =  $userData->interest()->get();
     $polls = Poll::where('owner_id', $userData->id)
                          ->orderBy('id', 'desc')
                          ->limit(10)
                          ->get();
      // foreach ($polls as $poll) {
      //   $interestInfo = Interest::where('id', $poll->interest_id)->first();
      //   $values = [
      //     'poll' => $poll,
      //     'interest_name' => $interestInfo->title
      //   ];
      //   array_push($this->pollsData, $values);
      //
      // }

     return response()->json(['data' => [ 'success' => true, 'user' => $userData, 'interest' => $interest, 'polls' => $this->polls]], 200);
   }

}
