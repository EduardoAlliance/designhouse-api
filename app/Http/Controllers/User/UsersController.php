<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(){
        $users = User::all();
        return UserResource::collection($users->loadMissing('designs'));
    }

    public function searchDesigners(Request $request){
        $users = (new User())->search($request);
        return UserResource::collection($users);
    }

    public function getDesignsForUser(User $user){

        $designs = $user->designs()->isPublish()->get();

        return DesignResource::collection($designs);

    }


}
