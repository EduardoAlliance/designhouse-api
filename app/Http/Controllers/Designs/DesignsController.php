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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  Design $design
     * @return DesignResource
     */
    public function show(Design $design)
    {
        return new DesignResource($design);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateDesign  $request
     * @param  Design $design
     * @throws
     * @return DesignResource
     */
    public function update(UpdateDesign $request,  Design $design)
    {
        $this->authorize('update',$design);
        $design->update([
            'title'=>$request->title,
            'description'=>$request->description,
            'slug'=> Str::slug($request->title),
            'publish'=> ! $design->upload_successful ? false : $request->is_live
        ]);

        $design->retag($request->tags);

        return new DesignResource($design);

    }


    public function destroy($id)
    {
        $design = Design::where('u_id',$id)->first();

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
}
