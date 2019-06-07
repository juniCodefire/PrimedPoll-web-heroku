<?php

namespace App\Http\Controllers;

use App\Poll;
use App\User;
use App\Option;
use App\Interest;
use App\Userinterest;
use Faker\Factory as Faker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPollController extends Controller
{    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function index()
    {
        $poll = Poll::where('owner_id', Auth::user()->id)
                ->withCount('votes')
                ->with(['options' => function($query){
                    $query->withCount('votes');
                 }])
                ->get();
        return response()->json($poll, 200);
    }

    public function show($id)
    {   
        $pollCheck = Poll::findOrFail($id);
        if(Auth::user()->id == $pollCheck->owner_id)
            {
                $poll = Poll::where('id', $id)
                    ->withCount('votes')
                    ->with(['options' => function($query){
                        $query->withCount('votes');
                     }])
                    ->get();
                return response()->json($poll, 200);
            }
            return response()->json('Unauthorized Access!', 400);
    }

    public function create(Request $request, $id)
    {
        $user = Auth::user();
        
        $interest = $user->interest()->where('interest_id', $id)->first();

        if($interest)
        {
            // return $request;
            $this->validatePoll($request);
            $poll = new Poll;
            if(!Poll::where('question', $request->input('question'))->where('interest_id', $id)->exists())
                {
                    $poll->question = $request->input('question');
                    $poll->startdate = $request->input('startdate');
                    $poll->expirydate = $request->input('expirydate');
                    $poll->interest_id = $interest->id;
                    $poll->owner_id = Auth::user()->id;
                    $poll->save();
                    
                    $items = $request->input('options');

                    foreach($items as $item) {
                        $option = new Option;
                        $option->option = $item['option'];
                        $option->owner_id = Auth::user()->id;
                        $option->poll_id = $poll->id;
                        $option->save();
                    }
                    $res['status'] = "{$poll->question} Created Successfully!";
                    $res['poll'] = $poll;
                    $res['options'] = Option::where('poll_id', $poll->id)->get();
                    return response()->json($res, 201);

                 } return response()->json('Poll exist for Interest', 422);

            } return response()->json('Please Select Interest Before Creating Poll', 422);
    }

    public function update(Request $request, $id)
    {
        $pollCheck = Poll::where('id', $id)->exists();
        $poll = Poll::findOrFail($id);
        $option = Option::where('poll_id', $poll)->get();

        if(Auth::user()->id == $poll->owner_id)
        {
            if($pollCheck)
            {
                $this->validatePoll($request);

                $poll->question = $request->input('question');
                $poll->startdate = $request->input('startdate');
                $poll->expirydate = $request->input('expirydate');
                $poll->save();

                $items = $request->input('options');

                foreach($items as $item) {
                    $option = new Option;
                    $option->option = $item['option'];
                    $option->owner_id = Auth::user()->id;
                    $option->poll_id = $poll->id;
                    $option->save();
                }

                $res['status'] = "{$poll->question} Updated Successfully!";
                $res['poll'] = $poll;
                $res['options'] = Option::where('poll_id', $poll->id)->get();
                return response()->json($res, 201);

            } return response()->json('Poll Does not Exist', 400);

        } return response()->json('Unauthorized Acess', 400);

    }
    
    public function destroy(Request $request, $id)
    {
        $pollCheck = Poll::where('id', $id)->exists();
        $poll = Poll::findOrFail($id);

        if(Auth::user()->id == $poll->owner_id)
        {
            if($pollCheck)
            {
                $poll->delete();
            
                $res['status'] = "Deleted Successfully!";
                return response()->json($res, 201);

            } return response()->json('Poll Does not Exist', 400);

        } return response()->json('Unauthorized Acess', 400);
                        
    }

    public function validatePoll(Request $request){

		$rules = [
            'question' => 'required|min:3',
            'options.*.option' => 'required',
            'startdate' => 'required|date|before:expirydate',
            'expirydate' => 'required|date|after:startdate',
        ];
         
		$this->validate($request, $rules);
    }

}
