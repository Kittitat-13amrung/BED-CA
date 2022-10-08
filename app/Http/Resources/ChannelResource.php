<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\YoutubeVideoCollection;

class ChannelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    //  rename the data object to a different name
    public static $wrap = 'channel';

    public function toArray($request)
    {
        // $videoHidden = $videos->map(function ($video) {
        //     return $video->only('title', 'uuid', 'likes', 'dislikes', 'views');
        // });


        // dd($videoHidden);

        return [
            'id' => $this->id, 
            'name' => $this->name,
            'subscribers' => $this->subscribers,
            'created_at' => $this->created_at,
            'videos' => YoutubeVideoShortenResource::collection($this->videos)
        ];
    }
}
