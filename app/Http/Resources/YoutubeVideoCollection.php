<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class YoutubeVideoCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    //  rename the data object to a different name
    public static $wrap = 'videos';

    public function toArray($request)
    {
        return [
            'videos' => $this->collection
            
        ];
    }
}
