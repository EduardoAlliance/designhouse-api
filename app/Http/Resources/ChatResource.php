<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
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
            'dates'=>[
                'created'=> $this->created_at->diffForHumans(),
                'created_at' => $this->created_at
            ],
            'is_unread' => $this->isUnreadForUser(auth()->id()),
            'latest_message'=> new MessageResource($this->latest_message),
            'messages'=> MessageResource::collection($this->whenLoaded('messages')),
            'participants'=> UserResource::collection($this->whenLoaded('participants'))
        ];
    }
}
