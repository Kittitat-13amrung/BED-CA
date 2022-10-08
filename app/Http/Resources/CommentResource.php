<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ChannelResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'comment' => $this->comment,
            'likes' => $this->likes,
            'dislikes' => $this->dislikes,
            'created_at' => $this->created_at,
            'channel' => ChannelResource::collection($this->channels),
        ];
    }
}
