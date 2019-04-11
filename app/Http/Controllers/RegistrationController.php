<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use libphonenumber\PhoneNumberType;

class RegisterController extends Controller
{
    public function register(Request $request){

		$this->validateRequest($request);
								 
        $token = (str_random(60));
        
		$user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
			'email' => $request->input('email'),
            'phone' => $request->get('phone'),
            'dob' => $request->input('dob'),
            'category' => $request->input('category'),
			'password'=> Hash::make($request->get('password')),
			'api_token' => $token
        ]);
        
		$res['message'] = "{$user->first_name} Created Successfully!";
		$res['user'] = $user;
		return response()->json($res, 201);
    }

    public function validateRequest(Request $request){

		$rules = [
            'first_name' => 'unique:users',
            'last_name' => 'unique:users',
			'email' => 'required|email',
            'phone' => 'phone:NG,US,mobile',
            'dob' =>   'date',
            'category' => 'string',
            'password' => 'required|min:6|confirmed',
        ];
        
		$messages = [
			'required' => ':attribute is required',
			'email' => ':attribute not a valid format',
			'phone' => ':attribute number is invalid'
        ];
        
		$this->validate($request, $rules, $messages);
    }
}