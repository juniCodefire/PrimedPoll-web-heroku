<?php

namespace App\Http\Controllers;

use App\User;
use App\Poll;
use App\Interest;
use App\Option;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
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
    
    public function users()
    {
       
		return response()->json($users, 200);
    }
}
