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

        foreach ($interests as $interest) {

          $userinterest->owner_id = $user->id;
          $userinterest->interest_id = $interest;
          $userinterest->save();

        }
        $user->save();      
		$res['message'] = "{$user->first_name} Updated Successfully!";        
        return response()->json($res, 200); 
    }

    public function validateRequest($request)
    {

       $rules = [
        'first_name' => 'users,first_name,string|required',
        'last_name' => 'unique:users,last_name,string|required',
        'phone' => 'users,phone,required|phone:NG,US,mobile',
        'dob' => 'date',
        'interests' => 'required',
        ];

        $messages = [
            'required' => ':attribute is required',
            'phone' => ':attribute number is invalid'
        ];

        $this->validate($request, $rules);

    }
}
