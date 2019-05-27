<?php

namespace App\Http\Controllers;

use App\User;
use Cloudder;
use App\Userinterest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use libphonenumber\PhoneNumberType;

class UserProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function index(User $user)
    {
        $user = Auth::user();
             
        return response()->json(['data' => [ 'success' => true, 'user' => $user ]], 200);
    }

    public function uploadImage(Request $request)
    {
        $this->validate($request, [
            'image' => 'image|max:4000|required',
            ]);
        $user = Auth::user();

        if ($request->hasFile('image') && $request->file('image')->isValid()){
            if ($user->image != "noimage.jpg") {
                $oldImage = pathinfo($user->image, PATHINFO_FILENAME);
                try {
                    $delete_old_image = Cloudder::destroyImage($oldImage);
                } catch (Exception $e) {
                    $mes['error'] = "Try Again";
                    return back()->with($mes);
                }
            }

            $user = $request->file('image');
            $filename = $request->file('image')->getClientOriginalName();
            $image = $request->file('image')->getRealPath();
            Cloudder::upload($image, null);

            list($width, $height) = getimagesize($image);
            $image = Cloudder::show(Cloudder::getPublicId(), ["width" => $width, "height"=>$height]);

            $this->saveImages($request, $image);

        $res['message'] = "Upload Successful!";  
        $res['image'] = $image;          
        return response()->json($res, 200); 

        }
    }

    public function saveImages(Request $request, $image)
    {
        $user = Auth::user();

        $user->image = $image;
        $user->save();
    }

    public function editProfile(Request $request)
    {
        $user = Auth::user();
    
        $this->validateRequest($request);

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->phone = $request->input('phone');
        $user->dob = $request->input('dob');

        $items = $request->input('interests');
        
        foreach($items as $item) {
            $userinterest = new Userinterest;
            $userinterest->owner_id = $user->id;
            $userinterest->interest_id = $item['interest_id'];
            $userinterest->save();
        }

        if(!empty($request->input('password')))
        {
            $user->password = Hash::make($request->input('password'));
        }
       
        $user->save();
		$res['message'] = "Account Updated Successfully!";        
        $res['user'] = $user;
        $res['Userinterests'] = Userinterest::where('owner_id', Auth::user()->id)->with('interest')->get();
        return response()->json($res, 200); 
    }

    public function validateRequest(Request $request)
    {
       $id = Auth::id();

       $rules = [
        'first_name' => 'required',
        'last_name' => 'string|required',
        'phone' => 'phone:NG,US,mobile|required',
        'dob' => 'date|required',
        'interests.*.interest_id' => 'required',
        'password' => 'nullable|min:6|different:current_password|confirmed',
        ];

        $messages = [
            'required' => ':attribute is required',
            'phone' => ':attribute number is invalid'
        ];
        
        $this->validate($request, $rules);

    }
}
