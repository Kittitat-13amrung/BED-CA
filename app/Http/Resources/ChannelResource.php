<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\YoutubeVideoResource;

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
        $videos = YoutubeVideoResource::collection($this->videos);

        return [
            'name' => $this->name,
            'subscribers' => $this->subscribers,
            'created_at' => $this->created_at,
            'videos' => $this->videos->only('title')
        ];
    }
}
