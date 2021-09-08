<?php

namespace App\Http\Controllers\Designs;

use App\Http\Controllers\Controller;
use App\Jobs\UploadImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function upload(Request $request){
        $request->validate([
           'image'=>'required|mimes:jpeg,jpg,gif,bmp,png|max:2048'
        ]);

        $image = $request->file('image');
        $image_path = $image->getPathname();
        $filename = time().'_'.preg_replace('/\s+/','_',strtolower($image->getClientOriginalName()));

        // move the image to the temporary location (tmp)
        $tmp = $image->storeAs('uploads/original',$filename,'tmp');


        // create database record for design
        $design = auth()->user()->designs()->create([
            'u_id'=> Str::uuid(),
           'image' => $filename,
           'disk'=>config('site.upload_disk')
        ]);

        //dispatch a job to handle image manipulation
        $this->dispatch(new UploadImage($design));

        return response()->json($design, 200);
    }
}
