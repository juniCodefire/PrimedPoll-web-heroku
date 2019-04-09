<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class VerifyMailController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
 
public function verify($verify_token)
		{
			if(! $verify_token){
				return response()->json("Invalid Verification Token", 401);
			}

			$user = User::where('verify_token', $verify_token)->first();

			if ( ! $user)
			{
				return response()->json("Account already verified, please Login!", 401);
			}

			$user->email_verified_at = date("Y-m-d H:i:s");
			$user->verify_token = null;
			$user->save();

            $msg['message'] = "You have successfully verified your account.";
            return response()->json($msg, 201);
        }
}