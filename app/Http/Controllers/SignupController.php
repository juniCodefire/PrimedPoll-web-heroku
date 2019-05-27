<?php
namespace App\Http\Controllers;

use App\User;
use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller as BaseController;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SignupController extends Controller
{
	public function register(Request $request)
	
	{
		$this->validateRequest($request);

		$verifycode = (str_random(6));

		//start temporay transaction
		DB::beginTransaction();

		try {
			
			$user = User::create([
			'email' => $request->input('email'),
			'password' => Hash::make($request->get('password')),
				'verifycode' => $verifycode
			]);


			Mail::to($user->email)->send(new VerifyEmail($user));


			$msg['message'] = "Thanks for signing up! A Verification Mail has been Sent to $user->email";

			$msg['verified'] = false;
			

			//if operation was successful save changes to database
			DB::commit();

			return response()->json($msg, 200);

		}catch(\Exception $e) {

			//if any operation fails, Thanos snaps finger - user was not created
			DB::rollBack();

			$msg['error'] = "Account Not created, Try Again!";
			return response()->json($msg, 422);
			

		}

		
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