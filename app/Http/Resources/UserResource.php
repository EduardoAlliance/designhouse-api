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
            'photo'=>$this->photo_url,
            $this->mergeWhen( auth()->check() && auth()->id() == $this->id,[
                'email'=>$this->email,
            ]),
            'tagline'=>$this->tagline,
            'about'=>$this->about,
            'location'=>$this->location,
            'formatted_address'=>$this->formatted_address,
            'available_to_hire'=>$this->available_to_hire,
            'designs'=> DesignResource::collection($this->whenLoaded('designs'))
        ];
    }
}
