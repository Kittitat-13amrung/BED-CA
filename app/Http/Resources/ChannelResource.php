<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\YoutubeVideoCollection;
use Illuminate\Support\Carbon;

class ChannelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {
        // eager-load all the videos created by the channel
        $videos = YoutubeVideoResource::collection($this->whenLoaded('videos'));
        return [
            'id' => $this->id, 
            'name' => $this->name,
            'subscribers' => $this->subscribers,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/Y'),
            'hasVideo' => $this->videos->count(),
            'videos' => $videos
        ];
    }
}
