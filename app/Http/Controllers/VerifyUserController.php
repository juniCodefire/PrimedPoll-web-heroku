<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

use Illuminate\Support\Facades\Auth;

class VerifyUserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
 
    public function verifyUser(Request $request, User $user)
    {
        $this->validate($request, [
            'verifycode' => 'required|max:6'
        ]);

        $verifycode = $request->input('verifycode');

        $checkCode = User::where('verifycode', $verifycode)->exists();

        if ($checkCode) {

        $user = User::where('verifycode', $verifycode)->first();

            if ($user->email_verified_at == null){
                $user->email_verified_at = date("Y-m-d H:i:s");
                $user->save();
                
                //generate new token for user
                $token = Auth::guard('api')->login($user);

                $msg["message"] =  "Account is verified";

                $msg['verified'] = true;

                $msg['token'] = $token;

                return response()->json($msg, 200);
             }

             $msg["message"] =  "Account is is already verified.";
             $msg['verified'] = true;


             return response()->json($msg, 200);

        } else{

            $msg["message"] = "Account with code does not exist!";

            return response()->json($msg, 409);

        }
            
        
    }
}