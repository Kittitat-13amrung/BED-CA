<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ChannelResource;
use App\Http\Resources\CommentResource;
use Illuminate\Support\Carbon;
use Mockery\Undefined;

class YoutubeVideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {
        // eager loading channel and comments
        // as part of the video's relationships
        $channel = new ChannelResource($this->whenLoaded('channel'));
        $comments = CommentResource::collection($this->whenLoaded('comments'));

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/Y'), //parse creation date to d/m/y format
            'duration' => $this->duration,
            'likes' => $this->likes,
            'dislikes' => $this->dislikes,
            'views' => $this->views,
            'link' => "youtube.com/" . $this->uuid,
            'thumbnail' => $this->thumbnail,
            'channel' => $channel,
            'hasComments' => $this->comments->count(),
            'comment' => $comments,
        ];
    }


}
