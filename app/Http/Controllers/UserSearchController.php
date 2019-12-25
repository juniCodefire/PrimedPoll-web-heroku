<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\User;

class UserSearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function resetpassword(Request $request)
    {

    // Do a validation for the input
        $this->validate($request, [
        	'search_query' => 'required|string',
        ]);
    
        $query = ucwords($request->query('search_query'));
        
        $this->Interestpermit($id);
          $fetch_polls = Poll::where('interest_id', $id)
                                ->withCount('votes')
                                ->orderBy('id', 'desc')
                                ->limit(10)
                                ->get();
        }
        //This is the triggerQuery
        $this->triggerQuery($fetch_polls, $vote_status_key="on");
        return response()->json(['data' =>['success' => true, 'feeds' => $this->feeds]], 200);
    }

    public function feedspermit() {
       $check_interest = Userinterest::where('owner_id', Auth::user()->id)->pluck('interest_id');
        return $check_interest;
    }
}
