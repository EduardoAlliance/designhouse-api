<?php

namespace App\Models;

use App\Models\Traits\Likeable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Cviebrock\EloquentTaggable\Taggable;

class Design extends Model
{
    use Taggable, Likeable;

    protected $fillable = [
      'user_id','u_id','team_id',
      'image','title','description','slug','can_comment','publish','upload_successful','disk'
    ];

    public function getRouteKeyName(){
        return 'u_id';
    }

    //realtions
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comments(){
        return $this->morphMany(Comment::class,'commentable')->orderBy('created_at','asc');
    }

    public function team(){
        return $this->belongsTo(Team::class);
    }



    //end relations
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
