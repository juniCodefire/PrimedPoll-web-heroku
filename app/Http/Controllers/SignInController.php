<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;

class SignInController extends Controller
{
 /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;
    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function authenticate(Request $request) {
    // Do a validation for the input 
        $this->validate($request, [

        	'email' => 'required|email',
        	'password' => 'required'
        ]);

    try {
        if (!$token = $this->jwt->attempt($request->only("email", "password"))) {
            return response()->json(['user_not_found'], 404);
        }
    } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
        return response()->json(['token_expired'], 500);
    } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
        return response()->json(['token_invalid'], 500);
    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        return response()->json(['token_absent' => $e->getMessage()], 500);
    }
   
       $user = User::where('email',$request->input("email"))->first();

           if ($user->email_verified_at != null) {
                    return response()->json(['data' =>['success' => true, 'user' => $user, 'token' => $token]], 200);
             }else{
                    return response()->json(['data' =>['error' => false, 'message' => "Not confirmed yet"]], 401); 
                }    

    }

}