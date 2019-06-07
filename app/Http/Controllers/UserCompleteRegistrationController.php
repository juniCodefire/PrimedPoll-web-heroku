<?php

namespace App\Http\Controllers;

use App\User;
use App\Userinterest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use libphonenumber\PhoneNumberType;

class UserCompleteRegistrationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function update(Request $request)
    {
        $user = Auth::guard('api')->user();

        $this->validateRequest($request);
        try{
        $default_image = 'https://res.cloudinary.com/iro/image/upload/v1552487696/Backtick/noimage.png';

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->phone = $request->input('phone');
        $user->dob = $request->input('dob');
        $user->image = $default_image;

        $interest_ids = $request->input('interest_ids');

        $interest = $user->interest()->syncWithoutDetaching($interest_ids);
 
        $user->save();      

        $msg['success'] = "Registration Completed";
        $msg['user'] = $user;
        $msg['interests'] = $user->interest()->get();
        $msg['interest'] = $interest;
        return response()->json($msg, 201);
        }catch (\Exception $e) {
            return response()->json(['message'=> "Opps! Something went wrong!"], 400);
        }

    }

    public function validateRequest($request)
    {
       $rules = [
        'first_name' => '|required',
        'last_name' => 'string|required',
        'phone' => 'phone:NG,US,mobile|required',
        'dob' => 'date|required',
        'interest_ids' => 'required|array|min:5',
        'interest_ids.*' => 'required|integer',
        ];

        $messages = [
            'required' => ':attribute is required',
            'phone' => ':attribute number is invalid'
        ];

        $this->validate($request, $rules);

    }
}
