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



    public function update(User $user, Request $request){
    
        $user = Auth::user();
       
        $this->validateRequest($request);

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->phone = $request->input('phone');
        $user->dob = $request->input('dob');
        $user->image = 'user.jpg';

        $interests = $request->input('interests');
        $interests_length = count($interests);

        for ($i=0; $i < $interests_length; $i++) { 
           $userinterest = new Userinterest;
           $userinterest->owner_id = $user->id;
           $userinterest->interest_id = $interests[$i];
           $userinterest->save();
        }
         
        $user->save();      
	      return response()->json(['data' =>['success' => true, 'user' => $user, 'message' => 'Registration Successful']], 200);
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
