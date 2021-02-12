<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'=> $this->id,
            'name'=>$this->name,
            $this->mergeWhen( auth()->check() && auth()->id() == $this->id,[
                'email'=>$this->email,
            ]),
            'designs'=> DesignResource::collection($this->whenLoaded('designs'))
        ];
    }
}
