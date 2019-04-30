<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;





class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function profile(User $user)

    {
        $user = Auth::user();
             
        return response()->json(['data' => [ 'success' => true, 'user' => $user ]], 200);
    }
}
