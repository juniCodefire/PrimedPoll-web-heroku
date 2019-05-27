<?php

namespace App\Http\Controllers;

use App\User;
use App\Poll;
use App\Option;
use App\Interest;
use App\Userinterest;
use Faker\Factory as Faker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserOptionsController extends Controller
{    
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function show($id)
    {   
        $optionCheck = Option::findOrFail($id);

        if(Auth::user()->id == $optionCheck->owner_id)
            {
                $option = Option::where('id', $id)
                    ->withCount('votes')
                    ->get();
                return response()->json($option, 200);
            }
            return response()->json('Unauthorized Access!', 400);
    }

    public function update(Request $request, $id)
    {
        $option = Option::findOrFail($id);

        if(Auth::user()->id == $option->owner_id)
        {
            $this->validatePoll($request);

                $option->options = $request->input('option');
                $option->save();

                $res['status'] = "Updated Successfully!";
                $res['option'] = $option;
                return response()->json($res, 201);
            
        } return response()->json('Unauthorized Acess', 400);

    }
    
    public function destroy(Request $request, $id)
    {
        $option = Option::findOrFail($id);

        if(Auth::user()->id == $option->owner_id)
        {
                $option->delete();

                $res['status'] = "Deleted Successfully!";
                return response()->json($res, 201);
            
        } return response()->json('Unauthorized Acess', 400);
    }

    public function validatePoll(Request $request){

		$rules = [
            'option' => 'required|min:3',
        ];
		$this->validate($request, $rules);
    }

}
