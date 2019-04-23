<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Admin;

class AdminProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin.auth');
    }

    
    public function adminData(Request $request) {

        $admin = Auth::guard('admin')->user();
        return response()->json(['data' =>['success' => true, 'admin' => $admin]], 200); 
    }

    public function updatePass(Request $request) {
        $admin = Auth::guard('admin')->user();

        $this->validate($request, [
            'password'  => 'required|min:6',
        ]); 
        $password = Hash::make($request->input('password'));
        
        $admin->password = $password;
        $admin->save();

        return response()->json(['data' =>['success' => true, 'messsage' => 'Password Updated']], 200); 
    }

    //

}
