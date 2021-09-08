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

    public function search($request){


        $query = $this->newQuery();
        $query->where('publish',true);

        //return designs with comments
        if($request->has_comments){
            $query->has('comments');
        }

        //return designs with team
        if($request->has_team ){
            $query->has('team');
        }

        //search title and description
        if($request->q){
            $query->where(function($q) use ($request){
                $q->where('title','like','%'.$request->q.'%')
                    ->orWhere('description','like','%'.$request->q.'%');
            });
        }

        // order
        if($request->orderBy == 'likes'){
            $query->withCount('likes')
                ->orderByDesc('likes_count');
        }else{
            $query->latest();
        }

        return $query->get();
    }

    public function scopeIsPublish($query){
        return $query->where('publish',1) ;
    }

}
