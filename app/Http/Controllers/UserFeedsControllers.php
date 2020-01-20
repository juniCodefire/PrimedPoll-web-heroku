<?php

namespace App\Http\Controllers;

use App\Poll;
use App\User;
use App\Interest;
use App\Option;
use App\Vote;
use Illuminate\Http\Request;
use App\Userinterest;
use App\Http\Controllers\UserFollowController;
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
    public  $male_count = 0;
    public  $female_count = 0;

    public function index( Request $request, $id = null )
 {
        $query = $request->query( 'search_poll' );

        if ( $query ) {

            $fetch_polls = $this->searchPoll( $query, $id );

        } else if ( $id == null ) {

            $fetch_polls =  Poll::whereIn( 'interest_id', $this->feedspermit() )
                                ->with('users')
                                ->with('interest')
                                ->with(['options' => function($query){
                                    $query->withCount('votes');
                                }])
                                ->with(['vote_status' => function($query) {
                                    $query->where('voter_id', Auth::user()->id);
                                }])
                                ->withCount( 'votes' )
                                ->orderBy( 'id', 'desc' )
                                ->limit( 10 )
                                ->get();           

        } else {
            $this->Interestpermit( $id );
            $fetch_polls = Poll::where( 'interest_id', $id )
                                ->with('users')
                                ->with('interest')
                                ->with(['options' => function($query){
                                    $query->withCount('votes');
                                }])
                                ->with(['vote_status' => function($query) {
                                    $query->where('voter_id', Auth::user()->id);
                                }])
                                ->withCount( 'votes' )
                                ->orderBy( 'id', 'desc' )
                                ->limit( 10 )
                                ->get();
        }
        //This is the triggerQuery
        $this->triggerQuery( $fetch_polls );
        return response()->json( ['data' =>['success' => true, 'feeds' =>  $this->feeds ]], 200 );

    }

    public function scrolledfeeds( Request $request, $offset , $id = null )
    {
           $query = $request->query( 'search_poll' );

        if ( empty( $offset ) ) {
            return response()->json( ['data' =>['error' => false, 'message' => 'offset cannot be empty']], 400 );
        }
        if ( $query ) {

            $fetch_polls = $this->searchPoll( $query, $id );

        } else if (  $id == null ) {
            $fetch_polls = Poll::whereIn( 'interest_id', $this->feedspermit() )
                                ->offset( ( int )$offset )
                                ->with('users')
                                ->with('interest')
                                ->with(['options' => function($query){
                                    $query->withCount('votes');
                                }])
                                ->with(['vote_status' => function($query) {
                                    $query->where('voter_id', Auth::user()->id);
                                }])
                                ->withCount( 'votes' )
                                ->orderBy( 'id', 'desc' )
                                ->limit( 10 )
                                ->get();
        } else {
            $this->Interestpermit( $id );
            $fetch_polls = Poll::where( 'interest_id', $id )
                                ->offset( $offset )
                                ->with('users')
                                ->with('interest')
                                ->with(['options' => function($query){
                                    $query->withCount('votes');
                                }])
                                ->with(['vote_status' => function($query) {
                                    $query->where('voter_id', Auth::user()->id);
                                }])
                                ->withCount( 'votes' )
                                ->orderBy( 'id', 'desc' )
                                ->limit( 10 )
                                ->get();
        }
        //This is the triggerQuery
        $this->triggerQuery( $fetch_polls );

        $offset += 10;
        return response()->json( ['data' =>['success' => true, 'scrolled_feeds' => $this->feeds, 'new_offset' => $offset]], 200 );

    }

    public function usersFeeds( $id ) {

        $this->Interestpermit( $id );

        $fetch_polls = Poll::where( 'interest_id', $id )
                            ->with('users')
                            ->with('interest')
                            ->with(['options' => function($query){
                                $query->withCount('votes');
                            }])
                            ->with(['votes' => function($query) {
                                $query->where('voter_id', Auth::user()->id);
                            }])
                            ->withCount( 'votes' )
                            ->orderBy( 'id', 'desc' )
                            ->limit( 50 )
                            ->get();

        //This is the triggerQuery
        $this->triggerQuery( $fetch_polls );

        return response()->json( ['data' =>['success' => true, 'usersfeeds' => $this->feeds]], 200 );

    }

    //Search a specific poll

    public function searchPoll( $query , $id) {

        //Get the user in connection to the seacrh name
        $query_user = User::where( 'first_name', 'LIKE',  "%{$query}%" )->orWhere( 'last_name', 'LIKE', "%{$query}%" )
                            ->orWhere( 'username', 'LIKE', "%{$query}%" )->pluck( 'id' );

        //Get the Interest in connection to the seacrh query
        $query_interest = Interest::where('title', 'LIKE', "%{$query}%")->pluck('id');
          
        //get the option in connection to the seacrh query
        $query_option = Option::where('option', 'LIKE', "%{$query}%")->pluck('poll_id');

        if($id == null){
            $query_poll = Poll::whereIn( 'interest_id', $query_interest)->orwhere( 'question', 'LIKE',  "%{$query}%" )->orWhereIn( 'owner_id', $query_user)
                                ->orWhereIn( 'id', $query_option)
                                ->orwhere('option_type', $query)
                                ->with('users')
                                ->with('interest')
                                ->with(['options' => function($query){
                                    $query->withCount('votes');
                                }])
                                ->with(['vote_status' => function($query) {
                                    $query->where('voter_id', Auth::user()->id);
                                }])
                                ->withCount( 'votes' )
                                ->orderBy( 'id', 'desc' )->limit( 10 )->get();
        }else {
            $query_poll = Poll::where( 'interest_id', $id)->orwhere( 'question', 'LIKE',  "%{$query}%" )->orWhereIn( 'owner_id', $query_user)
                                ->orWhereIn( 'id', $query_option)
                                ->orwhere('option_type', $query)
                                ->with('users')
                                ->with('interest')
                                ->with(['options' => function($query){
                                    $query->withCount('votes');
                                }])
                                ->with(['vote_status' => function($query) {
                                    $query->where('voter_id', Auth::user()->id);
                                }])
                                ->withCount( 'votes' )
                                ->orderBy( 'id', 'desc' )->limit( 10 )->get();
        }
        
        return $query_poll;
    }
    public function triggerQuery($fetch_polls) {
        $user_follow_controller = new UserFollowController;
        foreach ( $fetch_polls as $fetch_poll ) {

        //Get the male and female vote count
           $gender_count = $this->total_gender_count($fetch_poll);
        //Get the owner followers and following members(We import the follow Controller as our helper class)
    
           $followers_count = $user_follow_controller->followers($helper = true, $fetch_poll->users->id);
           $following_count = $user_follow_controller->following($helper = true, $fetch_poll->users->id);

        //Get the total polls created by owner
            $total_owner_poll_count = $this->total_owner_poll_count($fetch_poll->users->id);
        //Get the total polls voted by owner
            $total_poll_votes_count = $this->total_owner_votes_count($fetch_poll->users->id);

            $data = [
                'poll'      => $fetch_poll,
                'image_link'=> env( 'CLOUDINARY_IMAGE_LINK' ).'/w_200,c_thumb,ar_4:4,g_face/',
                'vote_count_male' => $gender_count[0],
                'vote_count_female' => $gender_count[1],
                'owner_followers_count' => $followers_count,
                'owner_following_count' => $following_count,
                'owner_created_poll_count' => $total_owner_poll_count,
                'owner_voted_poll_count' => $total_poll_votes_count,
                'owner_comment_count' => 0

            ];
            array_push( $this->feeds, $data );
            $this->male_count = 0; $this->female_count = 0;
            
        }
    }

    // Handles the count  
    public function total_owner_poll_count($owner_id) {
        $result = Poll::where('owner_id', $owner_id)->count();
        return $result;
    }
    public function total_owner_votes_count($owner_id) {
        $result = Vote::where('voter_id', $owner_id)->count();
        return $result;
    }

    public function total_gender_count($fetch_poll) {

        $data= DB::table('votes')
                        ->select('voter_id', DB::raw('count(*) as totalVote'))
                        ->where('poll_id', '=', $fetch_poll->id)
                        ->groupBy('voter_id')
                        ->orderBy('totalVote', 'desc')
                        ->get(); 

        foreach ($data as $x) {
            $user = User::where('id', $x->voter_id)->pluck('gender');

            if($user[0] == 'Male') {
                $this->male_count+=1;
            }elseif ($user[0] == 'Female') {
                $this->female_count+=1;
            }

        }
        return [$this->male_count, $this->female_count];
    }
    
    public function feedspermit() {
        $check_interest = Userinterest::where( 'owner_id', Auth::user()->id )->pluck( 'interest_id' );
        return $check_interest;
    }

    public function Interestpermit( $id ) {
        $id_interest = Interest::where( 'id', $id )->exists();
        if ( !$id_interest ) {
            return response()->json( ['data' =>['error' => false, 'message' => 'Interest id']], 404 );
        }
    }


}
