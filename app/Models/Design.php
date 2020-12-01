<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Cviebrock\EloquentTaggable\Taggable;

class Design extends Model
{
    use Taggable;

    protected $fillable = [
      'user_id','u_id',
      'image','title','description','slug','can_comment','publish','upload_successful','disk'
    ];

    public function getRouteKeyName(){
        return 'u_id';
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getImagesAttribute(){
        return [
            'original'=>$this->getImagePath('original'),
            'large'=>$this->getImagePath('large'),
            'thumbnail'=> $this->getImagePath('thumbnail')
        ];
    }

    protected function getImagePath($size){
        return Storage::disk($this->disk)->url('uploads/designs/'.$size.'/'.$this->image);
    }

}
