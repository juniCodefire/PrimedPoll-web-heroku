<?php

namespace App\Http\Controllers;

use App\User;
use App\Follow;
use App\Interest;
use App\Userinterest;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

/**
 *
 */
class UserFollowController extends Controller
{

  /**
   * create a new controller instance.
   *
   * @return void
   */
  public $id = [];

  public function follow($id)
  {
    $to_follow_user = User::find($id);
    if ($to_follow_user) {

      //start temporay transaction
      DB::beginTransaction();
      try {
        $follow = new Follow;

        if (!$this->checkFollowUser($to_follow_user->id)) {
          $follow->follower_id = Auth::user()->id;
          $follow->following_id = $to_follow_user->id;
          $follow->save();

          //if operation was successful save changes to database
          DB::commit();
          return response()->json(['success' => true, 'check' => 1, 'message' => 'Following Successfull', 'follow' => $follow], 201);
        }

        $unFollow = Follow::where('follower_id', Auth::user()->id)->where('following_id', $to_follow_user->id)->first();
        $unFollow->delete();

        //if operation was successful save changes to database
        DB::commit();
        return response()->json(['success' => true, 'check' => 0, 'message' => 'Unfollowing Successful', 'follow' => $unFollow], 201);
      } catch (\Exception $e) {
        //if any operation fails, Thanos snaps finger - user was not created
        DB::rollBack();
        return response()->json(['error' => false, 'message' => "Opps! Something went wrong!", 'errorType' => $e], 400);
      }
    }
    return response()->json(['error' => false, 'message' => "Opps! Something thing wrong!"], 404);
  }

  public function show()
  {
    //get limit of 20 user in relation to the auth user interest at a ramdom order
    //Needed Model [User, UserInterest, Interest, ]
    $user = Auth::user();
    $interests = $user->interest()->get();

    foreach ($interests as $interest) {
      array_push($this->id, $interest->id);
    }
    // Get the id's of the members to follow
    $users_id = $this->permit($this->id);
    //This gets all user id this user has followed
    $following = Follow::where('follower_id', Auth::user()->id)->pluck('following_id')->toArray();
    //This is use to filter and get the member this user has not followed
    $value = array_diff($users_id, $following);

    $to_follow = User::whereIn('id', $value)->get();

    return response()->json(['success' => true, 'message' => 'Successful',   'image_link' => 'https://res.cloudinary.com/getfiledata/image/upload/w_200,c_thumb,ar_4:4,g_face/',  'to_follow' => $to_follow]);
  }

  public function followers($helper= false, $id) {
    //This api get the followers from the followers table
    $get_the_followers_id = Follow::where('following_id', $id)->latest()->pluck('follower_id');
    $get_followers_count = User::whereIn('id', $get_the_followers_id)->count();
    if($helper) {
      return $get_followers_count;
    }
    $get_followers_data = User::whereIn('id', $get_the_followers_id)->get();
    return response()->json(['success' => true, 'message' => 'Successful all followers!', 'image_link' => 'https://res.cloudinary.com/getfiledata/image/upload/w_200,c_thumb,ar_4:4,g_face/',
                             'followers' => $get_followers_data, 'count' =>  $get_followers_count]);
  }

  public function following($helper= false, $id) {
    //This api get the followers from the following table
    $get_the_following_id = Follow::where('follower_id', $id)->latest()->pluck('following_id');
    $get_following_count = User::whereIn('id', $get_the_following_id)->count();
    if($helper) {
      return $get_following_count;
    }
    $get_following_data = User::whereIn('id', $get_the_following_id)->get();
    return response()->json(['success' => true, 'message' => 'Successful all followers!', 'image_link' => 'https://res.cloudinary.com/getfiledata/image/upload/w_200,c_thumb,ar_4:4,g_face/', 
                             'following' => $get_following_data, 'count' =>  $get_following_count]);
  }

  public function permit($id)
  {
    $get_members = Userinterest::where('owner_id', '!=', Auth::user()->id)->whereIn('interest_id', $id)
      ->inRandomOrder()->distinct()->take(50)->pluck('owner_id')->toArray();
    return $get_members;
  }
  public function checkFollowUser($id)
  {
    return Follow::where('follower_id', Auth::user()->id)->where('following_id', $id)->exists();
  }
}
