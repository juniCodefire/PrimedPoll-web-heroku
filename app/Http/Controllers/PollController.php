<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\User;
use App\Poll;
use App\Option;
use Cloudder;

class PollController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function createpoll(Request $request)
    {
        $this->validate($request, [
            'pollname' => 'required|min:4',
            'interest' => 'required',
            'expirydate' => 'required',
            'options' => 'required',
        ]);

        $poll = new Poll;
        $user = Auth::user();

        // $poll->user_id = $user->id;
        $poll->user_id = 1;
        $poll->name = $request->input('pollname');
        $poll->interest_id = $request->input('interest');
        $poll->expirydate = $request->input('expirydate');
        $poll->save();


        if ($request->hasFile('options') && $request->file('options')) {
            foreach ($request->file('options') as $file) {
                $filename = $file->getClientOriginalName();
                $image_name = $file->getRealPath();
                Cloudder::upload($image_name, null);
                $cloudResult = Cloudder::getResult();
                $imagename = $cloudResult['url'];
                if ($cloudResult) {
                    $poll_id = $poll->id;
                    $optionsModel = new Option;
                    $optionsModel->name = $imagename;
                    $optionsModel->poll_id = $poll_id;
                    $optionsModel->save();
                }
            }
            return 'yes';
        }


        // if ($requzest->hasFile('options') && $request->file('image')->isValid()) {
        //     foreach ($request->file('options') as $file) {
        //         $filename = $file->getClientOriginalName();
        //         $imagename = $file->getRealPath();
        //         Cloudder::upload($imagename, null);

        //         list($width, $height) = getimagesize($imagename);
        //         $this->saveimages($request, $imageurl);
        //         $destinationPath = "uploads";
        //         $poll_id = $poll->id;
        //         $file->move($destinationPath, $filename);
        //         $optionsModel = new Option;
        //         $optionsModel->name = $filename;
        //         $optionsModel->poll_id = $poll_id;
        //         $optionsModel->save();
        //     }
        //     return 'yes';
        // }


    }
}
