<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class YoutubeVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $method = $this->method();

        if($method === "PUT") {
            return [
                'title' => 'required|max:255', 
                'description' => 'max:2000',
                'duration' => 'integer', 
                'likes' => 'required|integer', 
                'dislikes' => 'required|integer', 
                'views' => 'integer',
                'thumbnail' =>"url",
                "channel_id" => "integer"    
            ];
        };

        if($method === "PATCH") {
            return [
                'title' => 'sometimes|required|max:255', 
                'description' => 'sometimes|max:2000',
                'duration' => 'somteimes|integer', 
                'likes' => 'sometimes|required|integer', 
                'dislikes' => 'sometimes|integer', 
                'views' => 'sometimes|integer',
                'thumbnail' =>"sometimes|url",
                "channel_id" => "sometimes|integer"    
            ];
        };

        return [
            'title' => 'required|max:255', 
            'description' => 'max:2000',
            'duration' => 'integer', 
            'likes' => 'integer', 
            'dislikes' => 'integer', 
            'views' => 'integer',
            'channel_id' => 'integer'
        ];
    }
}
