<?php

namespace App\Http\Controllers;

use App\User;
use App\Poll;
use App\Vote;
use App\Option;
use App\Interest;
use App\Userinterest;
use Faker\Factory as Faker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserVotesController extends Controller
{    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function create(Request $request, $id)
    {
        $poll = Poll::findOrFail($id);
        // $option = Option::where('id', = %);

        $this->validateVote($request);

            $vote = new Vote;
                if(!Vote::where('owner_id', Auth::user()->id)->where('poll_id', $poll->id)->exists())
                {
                    $vote->owner_id = Auth::user()->id;
                    $vote->option_id = $request->input('option_id');

                    $vote->poll_id = $poll->id;
                    $vote->save();
    
                    return response()->json('WELLDONE', 200);
    
                } return response()->json('WHAT MAKES YOU THINK YOU CAN VOTE TWICE?');
    }

    public function validateVote(Request $request){

		$rules = [
            'option_id' => 'required',
        ];
		$this->validate($request, $rules);
    }
}
