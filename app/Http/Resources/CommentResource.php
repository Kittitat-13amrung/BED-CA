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
        $video = new YoutubeVideoResource($this->video);
        // dd($this->video);

        return [
            'id' => $this->id,
            'text' => $this->comment,
            'likes' => $this->likes,
            'dislikes' => $this->dislikes,
            'created_at' => $this->updated_at->diffForHumans(),
            'belongs_to_video' => $video,
            'belongs_to_channel' => $channel,
        ];
    }
}
