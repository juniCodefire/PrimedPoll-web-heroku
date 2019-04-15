<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewPassword;

use App\User;

class ChangePasswordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function updatepassword(Request $request)
    {

    // Do a validation for the input
        $this->validate($request, [
        	'verifycode' => 'required|max:6|min:5',
        	'newpassword' => 'required',
        ]);

        $userEmail = $request->query('email');
        $verifycode = $request->query('verifycode');
        $newPassword = $request->input('newpassword');
        $verifyPassword = $request->input('verifypassword');

       $checkverifyemail = User::where('email', $userEmail)->first();

       if ($checkverifyemail == null)
       {
        return response()->json(['data' =>['success' => false, 'message' => 'Email does not exist']], 400);
       } elseif ($verifycode !== $checkverifyemail->verifycode)
       {
        return response()->json(['data' =>['success' => false, 'message' => 'Verifycode is invalid']], 400);
       } elseif ($verifyPassword !== $newPassword)
       {
        return response()->json(['data' =>['success' => false, 'message' => 'Passwords not match']], 400);
       }

        try{
            $checkverifyemail->password = Hash::make($verifyPassword);
            // Mail::to($VerifyEmail->email)->send(new NewPassword($VerifyEmail));
            $checkverifyemail->save();
            return response()->json(['data' => ['success' => true, 'message' => "Your password has been changed"]], 200);
          } catch (Exception $e) {
             return response()->json(['data' => ['success' => true, 'message' => "Error changing password...."]], 500);
          }
    }

}
