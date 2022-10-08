<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class YoutubeVideoShortenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public static $wrap = 'video';

    public function toArray($request)
    {

        return [
            'title' => $this->title,
            'description' => $this->description,
            'duration' => $this->duration . " minutes",
            'likes' => $this->likes,
            'dislikes' => $this->dislikes,
            'views' => $this->views,
            'link' => "youtube.com/" . $this->uuid,
        ];
    }


}
