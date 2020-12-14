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
            'u_id'=>$this->u_id,
            'title'=>$this->title,
            'slug'=>$this->slug,
            'is_live'=>$this->publish,
            'images'=> $this->images,
            'description'=>$this->description,
            'likes_count'=>$this->likes->count(),
            'created_at'=>$this->created_at->diffForHumans(),
            'tag_list'=>[
                'normalized' => $this->tagArrayNormalized,
                'tags' => $this->tagArray
            ],
            'team'=> $this->team ? [
                $this->team->name,
                $this->team->slug
            ] : null,
            'comments'=> CommentResource::collection($this->whenLoaded('comments')),
            'user'=> new UserResource($this->whenLoaded('user')),
        ];
    }
}
