<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChannelRequest extends FormRequest
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

        if ($method === "PUT") {
            return [
                "name" => "required|min:3",
                "email" => "required|email|unique:channels",
                "password" => "required|min:6",
            ];
        };

        if ($method === "PATCH") {
            return [
                "name" => "sometimes|min:3",
                "email" => "sometimes|email|unique:channels",
                "password" => "sometimes|min:6"
            ];
        };

        // return [
        //     "name" => "required|min:3",
        //     "email" => "required|email|unique:channels",
        //     "password" => "required|min:6"
        // ];
    }
}
