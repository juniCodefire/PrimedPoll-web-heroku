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
   public function showData($username) {
     $user  = User::where('username', $username)->get();
       dd($user->id);
     $value = User::find($user->id);

      foreach ($post->tags as $tag) {
          //
      }
     $interest = $user->interest()->get();
     dd($interest);
     return response()->json(['data' => [ 'success' => true, 'user' => $user, 'interest' => $interest]], 200);
   }

}
