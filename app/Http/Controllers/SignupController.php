<?php
namespace App\Http\Controllers;

use App\User;
use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller as BaseController;

class SignupController extends Controller
{
		public function register(Request $request)
		
		{
			$this->validateRequest($request);

			$verifycode = (str_random(6));

			$user = User::create([
				'email' => $request->input('email'),
				'password' => Hash::make($request->get('password')),
				'verifycode' => $verifycode
			]);

				Mail::to($user->email)->send(new VerifyEmail($user));

				$msg['success'] = "Thanks for signing up! A Verification Mail has been Sent to $user->email";
				return response()->json($msg, 200);
		}	
	
    public function validateRequest(Request $request){
		$rules = [
			'email' => 'required|email|unique:users',
    	'password' => 'required|min:6|confirmed',
		];
		$messages = [
			'required' => ':attribute is required',
			'email' => ':attribute not a valid format',
	];
		$this->validate($request, $rules, $messages);
		}
		
}