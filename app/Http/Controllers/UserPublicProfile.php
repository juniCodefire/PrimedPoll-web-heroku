<?php

namespace App\Http\Controllers;

use App\User;
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
     $interest =  $userData->interest()->get();
     return response()->json(['data' => [ 'success' => true, 'user' => $userData, 'interest' => $interest]], 200);
   }

}
