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
   public function showData(User $user, $username) {
     $userData  = $user->usernameCheck($username);
     if ($userData) {
       $interest =  $userData->interest()->get();
       $polls = Poll::where('owner_id', $userData->id)
                            ->orderBy('id', 'desc')
                            ->limit(10)
                            ->get();

       return response()->json(['data' => [ 'success' => true, 'user' => $userData, 'interest' => $interest, 'polls' => $polls]], 200);
     }else {
      return response()->json(['data' => [ 'success' => error, 'message' => 'User not found']], 404);
     }

   }

}
