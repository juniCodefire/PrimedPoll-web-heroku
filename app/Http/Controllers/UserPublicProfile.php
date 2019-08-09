<?php

namespace App\Http\Controllers;

use App\User;
use App\Poll;
use App\Follow;
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
  public function showData(User $user, $username, $permission = 0, $onSession = false)
  {
    $userData  = $user->usernameCheck($username);
    $interest =  $userData->interest()->get();
    $polls = Poll::where('owner_id', $userData->id)
      ->latest()
      ->with('interest')
      ->withCount('votes')
      ->with(['options' => function ($query) {
        $query->withCount('votes');
      }])
      ->limit(20)
      ->get();
    $pollsCount = Poll::where('owner_id', $userData->id)->count();

    if ($permission === 1) {
      $follow_check = Follow::where('follower_id', $onSession)->where('following_id', $userData->id)->exists();
      if ($follow_check) {
        $following = true;
      } else {
        $following = false;
      }
      $onSession = true;
    } else if ($permission === 0) {
      $onSession = false;
    } else {
      return response()->json(['data' => ['error' => false, 'message' => 'Unauthorize process observe']], 401);
    }
    return response()->json(['data' => [
      'success' => true, 'user' => $userData, 'interest' => $interest, 'polls' => $polls,
      'pollCount' =>  $pollsCount, 'imageLink' => 'https://res.cloudinary.com/getfiledata/image/upload/',
      'imageProp' => [
        'cropType1' => 'c_fit',
        'cropType2' => 'g_face',
        'imageStyle' => 'c_thumb',
        'heigth' => 'h_577',
        'width' =>  '433',
        'widthThumb' => 'w_200',
        'aspectRatio' => 'ar_4:4'
      ],
      'following' => $following,
      'status' => $status
    ]], 200);
  }
}
