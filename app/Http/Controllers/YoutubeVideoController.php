<?php

namespace App\Http\Controllers;

use App\Http\Resources\YoutubeVideoCollection;
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
        return new YoutubeVideoCollection(YoutubeVideo::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $video = YoutubeVideo::create($request->only([
            'title', 'description', 'duration', 'likes', 'dislikes', 'views',
        ]));

        $video->uuid = "watch?v=".Str::uuid();
        $video->thumbnail = "https://picsum.photos/360/360";

        return new YoutubeVideoResource($video);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\YoutubeVideo  $youtubeVideo
     * @return \Illuminate\Http\Response
     */
    public function show(YoutubeVideo $youtubeVideo)
    {
        return new YoutubeVideoResource($youtubeVideo);
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
        // update the youtube video data with the new request
        $youtubeVideo->update($request->only([
            'title', 'description', 'duration', 'likes', 'dislikes', 'views',
        ]));

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
        $youtubeVideo->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
