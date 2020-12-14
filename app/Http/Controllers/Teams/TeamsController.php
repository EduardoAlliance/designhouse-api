<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use App\Models\Team;
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

    public function destroy(Request $request,Team $team){

    }

    public function findById(Team $team){
       return new TeamResource($team);
    }

    public function getUserTeams(){
        $teams = auth()->user()->team();
        return TeamResource::collection($teams);
    }

    public function findBySlug($slug){
        $team = Team::where('slug',$slug)->first();
        if($team){
            return new TeamResource($team);
        }else{
           abort(404);
        }

    }


}
