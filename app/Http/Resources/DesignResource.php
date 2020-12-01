<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DesignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'user'=> new UserResource($this->user),
            'title'=>$this->title,
            'slug'=>$this->slug,
            'is_live'=>$this->publish,
            'images'=> $this->images,
            'description'=>$this->description,
            'created_at'=>$this->created_at->diffForHumans(),
            'tag_list'=>[
                'normalized' => $this->tagArrayNormalized,
                'tags' => $this->tagArray
            ]
        ];
    }
}
