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
        // retrieve channel that commented on the video
        $channel = new ChannelResource($this->channels[0]);

        return [
            'id' => $this->id,
            'text' => $this->comment,
            'likes' => $this->likes,
            'dislikes' => $this->dislikes,
            'commented_at' => $this->created_at->diffForHumans(),
            'belongsToVideo' => $this->youtube_video_id,
            'channel' => $channel,
        ];
    }
}
