<?php

namespace App\Http\Controllers;

use App\Http\Resources\YoutubeVideoResource;
use App\Models\YoutubeVideo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class YoutubeVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Eager loading video data with relationships
        $videos = YoutubeVideo::with(['comments', 'channel']);

        // Return data encapsulated in a collection paginated by 10
        return YoutubeVideoResource::collection($videos->paginate(10))->response();
        // return new YoutubeVideoCollection(YoutubeVideo::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // Validating the request from POST method
        $validation = $request->validate([
            'title' => 'required|max:255', 
            'description' ,
            'duration' => 'integer', 
            'likes' => 'integer', 
            'dislikes' => 'integer', 
            'views' => 'integer',

        ]);

        // create a new data with the validated data
        $video = YoutubeVideo::create($validation);
        // assigning fake uuid and thumbnail
        $video->uuid = "watch?v=".Str::random(10);
        $video->thumbnail = "https://picsum.photos/360/360";

        return response()->json($video, Response::HTTP_CREATED); // returns the created data in JSON format
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\YoutubeVideo  $youtubeVideo
     * @return \Illuminate\Http\Response
     */
    public function show(YoutubeVideo $youtubeVideo)
    {
        // eager loads the video with selected ID and its relationships
        return new YoutubeVideoResource($youtubeVideo->load(['comments','channel']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\YoutubeVideo  $youtubeVideo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, YoutubeVideo $youtubeVideo)
    {
        // Validating the request from PUT method
        $validation = $request->validate([
            'title' => 'required|max:255', 
            'description' ,
            'duration' => 'integer', 
            'likes' => 'integer', 
            'dislikes' => 'integer', 
            'views' => 'integer',

        ]);

        // update the youtube video data with the new request
        $youtubeVideo->update($validation);

        // then return the newly updated resource to the body
        return new YoutubeVideoResource($youtubeVideo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\YoutubeVideo  $youtubeVideo
     * @return \Illuminate\Http\Response
     */
    public function destroy(YoutubeVideo $youtubeVideo)
    {
        // delete the selected data
        $youtubeVideo->delete();
        // then response back with HTTP response of code 204
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
