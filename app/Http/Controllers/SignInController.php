<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

class SignInController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
  protected function jwt(User $user) {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued. 
            'exp' => time() + 60*60 // Expiration time
        ];
        
        // As you can see we are passing `JWT_SECRET` as the second parameter that will 
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    } 
    public function authenticate(Request $request) {
    // Do a validation for the input 
        $this->validate($request, [

        	'email' => 'required|email',
        	'password' => 'required'
        ]);

    // store the request into a variable

        $email = $request->input('email');
        $password = $request->input('password');

     //Query the database with the email giving

       $user = User::where('email', $email)->first();
    //Check if rthe user exist
        if ($user === null) {
        	return response()->json(['data' =>['error' => false, 'message' => 'Not found']], 404);
        }

     //Check if password match
        if (Hash::check($password, $user->password)) {
                
                if ($user->email_verified_at != null) {
                     return response()->json(['data' =>['success' => true, 'user' => $user, 'token' => $this->jwt($user)]], 200);
                }else{
                     return response()->json(['data' =>['error' => false, 'message' => "Not confirmed yet"]], 401); 

                }                     	
        }else{
        	return response()->json(['data' =>['error' => false, 'message' => "Invalid Credential"]], 401);
        }

    }

}
