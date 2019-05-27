<?php

namespace App\Http\Controllers;

use App\User;
use App\Poll;
use App\Option;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        $users = User::orderBy('created_at', 'desc')->paginate(20);

		return response()->json($users, 200);
    }

    public function polls()
    {
        $polls = Poll::where('expirydate', '>=', Carbon::now())->with('options')->orderBy('created_at', 'asc')->paginate(20);

		return response()->json($polls, 200);
    }

    public function trending()
    {
        $poll = Poll::where('expirydate', '>=', Carbon::now());

        if($poll){

                $trending = DB::table('votes')
                ->select('poll_id', DB::raw('count(*) as totalVote'))
                ->groupBy('poll_id')
                ->orderBy('totalVote', 'desc')
                ->take(10)
                ->get();           
                return response()->json($trending, 200);
            
        } return response()->json('No trending Post', 202);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        return response()->json(['data' =>['success' => true, 'message' => 'User Deleted']], 200);
    }

}
