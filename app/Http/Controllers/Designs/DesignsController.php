<?php

namespace App\Http\Controllers\Designs;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateDesign;
use App\Http\Resources\DesignResource;
use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DesignsController extends Controller
{

    public function index()
    {
        $designs = Design::all();
        return DesignResource::collection($designs->loadMissing('user','comments'));
    }

    public function show(Design $design)
    {
        return new DesignResource($design->loadMissing('user','comments'));
    }

    public function update(UpdateDesign $request,  Design $design)
    {
        $this->authorize('update',$design);
        $design->update([
            'title'=>$request->title,
            'description'=>$request->description,
            'slug'=> Str::slug($request->title),
            'publish'=> ! $design->upload_successful ? false : $request->is_live,
            'team_id'=>$request->team
        ]);

        $design->retag($request->tags);

        return new DesignResource($design);

    }

    public function destroy($id)
    {
        $design = Design::where('u_id',$id)->firstOrFail();

        $this->authorize('delete',$design);
        //remove design images
        foreach (['original','large','thumbnail'] as $size){
            if(Storage::disk($design->disk)->exists('uploads/designs/'.$size.'/'.$design->image)){
                Storage::disk($design->disk)->delete('uploads/designs/'.$size.'/'.$design->image);
            }
        }
        $design->delete();
        return response()->json(["message"=>"Design deleted"]);
    }

    public function like($design_id){

         $design = Design::where('u_id',$design_id)->firstOrFail();

        if($design->isLikeByUser(auth()->id())){
            $design->unlike();
        }else{
            $design->like();
        }

        return response()->json(['message'=>'success'],200);

    }

    public function likedByUser($design_id){
        $design = Design::where('u_id',$design_id)->firstOrFail();
        $liked = $design->isLikeByUser();

        return response()->json(['liked'=>$liked]);
    }

    public function searchDesigns(Request  $request){

        $design = new Design();
        $designs = $design->search($request);
        return DesignResource::collection($designs);

    }

    public function findBySlug($slug){

        $design = Design::where('slug',$slug)->firstOrFail();

        return new DesignResource($design);
    }

    public function userOwnsDesign($uid){
        $design = Design::where('u_id',$uid)->where('user_id',auth()->user()->id)->firstOrFail();
        return new DesignResource($design);
    }

}
