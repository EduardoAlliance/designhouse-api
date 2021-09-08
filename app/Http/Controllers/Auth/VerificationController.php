<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class VerificationController extends Controller
{
    public function __construct()
    {
        //$this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify(Request $request)
    {
        $user = User::find($request->id);

        if(!$user){
            return response()->json(['errors'=>[
                'message'=>'Email does not exists'
            ]],404);
        }

        if(! URL::hasValidSignature($request)){
            return response()->json(['errors'=>[
                'message'=>'Invalid verification link'
            ]],404);
        }
        if($user->hasVerifiedEmail()){
            return response()->json(['errors'=>[
                'message'=>'Email address already verified'
            ]],404);
        }

        $user->markEmailAsVerified();
        event(new Verified($request->user()));

        return response()->json(['message'=>'Email successfully verified'],200);

    }

    public function resend(Request $request)
    {
        $this->validate($request,[
            'email'=>'required|email'
        ]);

        $user = User::where('email',$request->email)->first();

        if(!$user){
            return response()->json(['errors'=>[
                'message'=>'Email does not exists'
            ]],404);
        }

        if($user->hasVerifiedEmail()){
            return response()->json(['errors'=>[
                'message'=>'Email address already verified'
            ]],404);
        }
        $user->sendEmailVerificationNotification();

        return response()->json(['message'=>'Email verification send']);

    }
}
