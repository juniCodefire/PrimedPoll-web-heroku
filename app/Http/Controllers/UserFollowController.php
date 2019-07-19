<?php

namespace App\Http\Controllers;

use App\User;
use App\Follow;
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
  public function create($id) {
    // Things to need Here
    //Auth_id -> this is the follower_id
    //request_id -> this is the following_id

    $to_follow_user = User::find($id);
    if ($to_follow_user) {

  		//start temporay transaction
  		DB::beginTransaction();
      try {
        $follow = new Follow;

        if(!Follow::where('follower_id', Auth::user()->id)->where('following_id', $to_follow_user->id)->exists()) {
          $follow->follower_id = Auth::user()->id;
          $follow->following_id = $to_follow_user->id;
          $follow->save();

          //if operation was successful save changes to database
          DB::commit();
          return response()->json(['success' => true, 'check' => 1, 'message' => 'Following Successfull', 'follow' => $follow], 201);
        }

        $unFollow = Follow::where('follower_id', Auth::user()->id)->where('following_id', $to_follow_user->id)->first();
        $removed = $unFollow->delete();
        
        //if operation was successful save changes to database
        DB::commit();
        return response()->json(['success' => true, 'check' => 0, 'message' => 'Unfollowing Successful', 'follow' => $unFollow], 201);

      } catch (\Exception $e) {
  			//if any operation fails, Thanos snaps finger - user was not created
  			DB::rollBack();
        return response()->json(['error' => false, 'message'=> "Opps! Something went wrong!", 'errorType' => $e], 400);
      }
    } return response()->json(['error' => false, 'message'=> "Opps! Something thing wrong!"], 404);

  }
}
