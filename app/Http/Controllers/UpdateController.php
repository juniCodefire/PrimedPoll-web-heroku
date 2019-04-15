<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use libphonenumber\PhoneNumberType;



class UpdateController extends Controller
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

    //

    public function validateRequest(Request $request)
    {
       $id = Auth::id();

       $rules = [
        'first_name' => 'users,first_name,string',
        'last_name' => 'unique:users,last_name,string',
        'email' => 'unique:users,email,'.$id.'|required|email',
        'phone' => 'users,phone,required|phone:NG,US,mobile',
        'dob' => 'date',
        'category' => 'string',
        'password' => 'nullable|min:6|different:current_password|confirmed',
        ];

        $messages = [
            'required' => ':attribute is required',
            'phone' => ':attribute number is invalid'
        ];

        $this->validate($request, $rules);

    }

    public function update(Request $request)
    {
        $user = Auth::user();
       
        $this->validateRequest($request);

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->dob = $request->input('dob');
        $user->category = $request->input('category');
        if(!empty($request->input('password')))
        {
            $user->password = Hash::make($request->input('password'));
        }
       
        $user->save();
		$res['message'] = "{$user->first_name} Updated Successfully!";        
        return response()->json($res, 200); 
    }
}
