<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeamsController extends Controller
{

    public function index(Request $request){

        $teams = Team::all();
        return TeamResource::collection($teams->loadMissing('owner','members','designs'));
    }

    public function store(Request $request){

        $request->validate([
           'name'=>'required|string|max:80|unique:teams,name'
        ]);

        $team = Team::create([
           'name'=>$request->name,
           'u_id'=> Str::uuid(),
           'slug'=> Str::slug($request->name),
            'owner_id'=> auth()->id()
        ]);

        return new TeamResource($team);
    }

    public function update(Request $request, Team $team){

        $this->authorize('update',$team);

        $request->validate([
            'name'=>'required|string|max:80|unique:teams,name,'.$team->id
        ]);

        $team->update([
            'name'=>$request->name,
            'slug'=> Str::slug($request->name)
        ]);
        return new  TeamResource($team);
    }

    public function destroy(Team $team){
        // check if auth is the owner of the team
       $this->authorize('destroy',$team);
        $team->delete();
        return response()->json(['message'=>'Deleted']);
    }

    public function findById(Team $team){
       return new TeamResource($team);
    }

    public function getUserTeams(){
        $teams = auth()->user()->teams;
        return TeamResource::collection($teams);
    }

    public function findBySlug($slug){
        $team = Team::where('slug',$slug)->firstOrFail();
        return new TeamResource($team);
    }

    public function removeFromTeam(Team $team,User $user){
        //the owner can remove any user

        //check if user is not the owner
        if($user->isOwnerOfTeam($team)){
            return response()->json(['message','You cannot do this']);
        }

        //check the auth is not the owner and is the person whi want to leave the team
        // user authenticated can not remove other user
        if(!auth()->user()->isOwnerOfTeam($team) && auth()->id() !== $user->id){
            return response()->json(['message','You cannot do this']);
        }

        $team->removeUserFromTeam($user->id);
        return response()->json(['message'=>'Success']);
    }

    public function getDesigns(Team $team){

        $designs = $team->designs()->isPublish()->get();
        return DesignResource::collection($designs);

    }


}
