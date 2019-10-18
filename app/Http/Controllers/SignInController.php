<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Admin;
use Carbon\Carbon;
use JWTAuthException;
use App\Http\Requests;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
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
    public function userLogin(Request $request)
    {
        // Do a validation for the input
        $this->validate($request, [

            'email' => 'required|email',
            'password' => 'required'
        ]);
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = $this->jwt->attempt($credentials, ['exp' => Carbon::now()->addDay(2)->timestamp])) {
                return response()->json(['message' => 'User not found'], 404);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], 500);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], 500);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent' => $e->getMessage()], 500);
        }
//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
        $user = Auth::guard('api')->user();
        $image_link = 'https://res.cloudinary.com/getfiledata/image/upload/w_200,c_thumb,ar_4:4,g_face/';

        if ($user->email_verified_at != null) {
            return response()->json(['data' => ['success' => true, 'user' => $user, 'image_link' => $image_link, 'token' => $token]], 200);
        } else {
            return response()->json(['data' => ['error' => false, 'message' => "Not confirmed yet"]], 401);
        }
    }

    public function adminLogin(Request $request)
    {
        // Do a validation for the input
        $this->validate($request, [

            'email' => 'required|email',
            'password' => 'required'
        ]);
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = Auth::guard('admin')->attempt($credentials)) {
                return response()->json(['message' => 'Admin not found'], 404);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], 500);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], 500);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent' => $e->getMessage()], 500);
        }

        $admin = Auth::guard('admin')->user();
        $image_link = 'https://res.cloudinary.com/getfiledata/image/upload/w_200,c_thumb,ar_4:4,g_face/';
        return response()->json(['data' => ['success' => true, 'admin' => $admin, 'imag_link' => $image_link, 'token' => $token]], 200);
    }
}
