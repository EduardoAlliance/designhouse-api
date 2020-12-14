<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
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
           'name'=>$this->name,
           'total_members'=>$this->members()->count(),
           'slug'=>$this->slug,
           'owner'=> new UserResource($this->whenLoaded('owner')),
           'members' => UserResource::collection($this->whenLoaded('members')),
           'designs' => DesignResource::collection($this->whenLoaded('designs'))
       ];
    }
}
