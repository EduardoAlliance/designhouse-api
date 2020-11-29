<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\UpdateSettingsProfile ;
use App\Rules\CheckSamePassword;
use App\Rules\MatchOldPassword;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function updateProfile(UpdateSettingsProfile $request){

        try{
            $location = new Point($request->location['latitude'],$request->location['longitude']);
            $request->user()->update([
                'tagline'=>$request->tagline,
                'name'=>$request->name,
                'about'=>$request->about,
                'formatted_address'=>$request->formatted_address,
                'available_to_hire'=>$request->available_to_hire,
                'location'=>$location,
            ]);
            return new UserResource($request->user());
        }catch (\Exception $e){
            return response()->json(['error'=>'Something wrong happened'],404);
        }
    }

    public function updatePassword(Request $request){
        $request->validate([
            'current_password'=>['required',new MatchOldPassword()],
            'password'=>['required','confirmed',new CheckSamePassword()],
        ]);

       try{
           $request->user()->update([
               'password'=>bcrypt($request->password)
           ]);
           return response()->json(['message'=>'Password updated'],200);
       }catch (\Exception $e){
           return response()->json(['error'=>'Something wrong happened'],404);
       }

    }
}
