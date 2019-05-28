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

        $default_image = 'https://res.cloudinary.com/iro/image/upload/v1552487696/Backtick/noimage.png';

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->phone = $request->input('phone');
        $user->dob = $request->input('dob');
        $user->image = $default_image;

        $items = $request->input('interests');
        
        foreach($items as $item) {
            $userinterest = new Userinterest;
            $userinterest->owner_id = $user->id;
            $userinterest->interest_id = $item['interest_id'];
            $userinterest->save();
        }

        $user->save();      

        $msg['success'] = true;
        $msg['user'] = $user;
        $msg['message'] = "Registration Completed";
        $msg['Userinterests'] = Userinterest::where('owner_id', Auth::user()->id)->with('interest')->get();
        return response()->json($msg, 201);
    }

    public function validateRequest($request)
    {
       $rules = [
        'first_name' => '|required',
        'last_name' => 'string|required',
        'phone' => 'phone:NG,US,mobile|required',
        'dob' => 'date|required',
        'interests.*.interest_id' => 'required',
        ];

        $messages = [
            'required' => ':attribute is required',
            'phone' => ':attribute number is invalid'
        ];

        $this->validate($request, $rules);

    }
}
