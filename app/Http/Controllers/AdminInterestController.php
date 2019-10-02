<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interest;
use App\Poll;
use App\User;

class AdminInterestController extends Controller
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
    
    public function index() {

       $interest = Interest::withCount('poll')
                    ->get();    
       return response()->json(['data' =>['success' => true, 'interest' => $interest]], 200); 
    }

    public function showAdmin($id)
    {   
            $poll = Poll::where('interest_id', $id)
                    ->withCount('votes')
                    ->with(['options' => function($query){
                        $query->withCount('votes');
                     }])
                    ->with('users')
                    ->get();
            if($poll) {
                return response()->json($poll, 200);
            }else {
                return response()->json('An error ocurred', 400);
            }
            
    }

    public function store(Request $request, Interest $interest) {

        $this->validate($request, [
            'title'  => 'required|unique:interests',
        ]); 

        $interest->title = $request->input('title');
        $interest->save();   
        return response()->json(['data' =>['success' => true, 'messsage' => 'New Interest Added' . $interest->title ]], 201); 
    }

    public function update(Request $request, $interest_id) {
        $this->validate($request, [
            'title'  => 'required',
        ]);

        $data = Interest::findOrfail($interest_id);

        $data->title = $request->input('title');
        $data->save();
        return response()->json(['data' =>['success' => true, 'messsage' => 'Interest Updated']], 200);
    }


    public function destroy($interest_id) {

        $data = Interest::findOrfail($interest_id);
        $data->delete();
        return response()->json(['data' =>['success' => true, 'messsage' => 'Interest Deleted']], 200);
    }


}