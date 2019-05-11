<?php

namespace App\Http\Controllers;

use App\User;
use App\Userinterest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use libphonenumber\PhoneNumberType;



class CompleteRegistrationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       $this->middleware('auth');
    }



    public function update(User $user, Userinterest $userinterest, Request $request){
    
        $user = Auth::user();
       
        $this->validateRequest($request);

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->phone = $request->input('phone');
        $user->dob = $request->input('dob');
        $user->image = 'user.jpg';

        $interests = $request->input('interests');
        
        // for($interests as $interest) {
        //   $userinterest->owner_id = $user->id;
        //   $userinterest->interest_id = $interest;
        //   $userinterest->save();

        // }
        // $user->save();      
	      return response()->json(['data' =>['success' => true, 'user' => $user, 'interest' => $interests, 'message' => 'Registration Successful']], 200);
    }

    public function validateRequest($request)
    {
    
       $rules = [
        'first_name' => 'string|required',
        'last_name' => 'string|required',
        'phone' => 'phone:NG,US,mobile|required',
        'dob' => 'date|required',
        'interests' => 'array|required',
        ];

        $messages = [
            'required' => ':attribute is required',
            'phone' => ':attribute number is invalid'
        ];

        $this->validate($request, $rules);

    }
}
