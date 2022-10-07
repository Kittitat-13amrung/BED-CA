<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ChannelCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    //  rename the data object to a different name
    public static $wrap = 'channels';

    public function toArray($request)
    {
        return [
            'channels' => $this->collection
            
        ];
    }
}
