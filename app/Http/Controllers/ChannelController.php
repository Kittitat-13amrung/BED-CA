<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChannelCollection;
use App\Http\Resources\ChannelResource;
use App\Http\Resources\YoutubeVideoResource;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ChannelController extends Controller
{
    /**
     * Display channels retrieved from the database.
     * @OA\Get(
        *     path="/api/channels",
        *     description="Displays all the youtube videos",
        *     tags={"Channels"},
        *      @OA\Response(
        *          response=200,
        *          description="Successful operation, Returns a list of Books in JSON format",
        *          @OA\JsonContent(ref="#/components/schemas/Channel")  
        *       ),
        *      @OA\Response(
        *          response=401,
        *          description="Unauthenticated",
        *      ),
        *      @OA\Response(
        *          response=403,
        *          description="Forbidden"
        *      )
 * )
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Eager loading channel data with its relationships
        $channels = Channel::with(['videos', 'comments']);

        // responds in JSON format the collection of data
        return new ChannelCollection($channels->paginate(50));
    }

    /**
     * Display all the videos made by the channel by retreiving its ID.
     *
     * @OA\Get(
    *     path="/api/channels/{id}/videos",
    *     description="Gets videos by its channel ID",
    *     tags={"Channels"},
    *          @OA\Parameter(
        *          name="id",
        *          description="Video id",
        *          required=true,
        *          in="path",
        *          @OA\Schema(
        *              type="integer")
     *          ),
        *      @OA\Response(
        *          response=200,
        *          description="Successful operation",
        *          @OA\JsonContent(ref="#/components/schemas/youtube_video")
        *       ),
        *      @OA\Response(
        *          response=401,
        *          description="Unauthenticated",
        *      ),
        *      @OA\Response(
        *          response=403,
        *          description="Forbidden"
        *      )
 * )
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function getVideos($id)
    {
        // retrieve the data of the channel specified by the ID parameter
        $videos = Channel::findOrFail($id)->videos;
        // and returns it as a collection to the user.
        return YoutubeVideoResource::collection($videos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // To be implemented in CA2
    }

    /**
     * Display the specified channel resource using its ID.
     *
        * @OA\Get(
        *     path="/api/channels/{id}",
        *     description="Gets a channel by its ID",
        *     tags={"Channels"},
        *          @OA\Parameter(
        *          name="id",
        *          description="Channel id",
        *          required=true,
        *          in="path",
        *          @OA\Schema(
        *              type="integer")
        *          ),
        *      @OA\Response(
        *          response=200,
        *          description="Successful operation",
        *          @OA\JsonContent(ref="#/components/schemas/youtube_video")
        *       ),
        *      @OA\Response(
        *          response=401,
        *          description="Unauthenticated",
        *      ),
        *      @OA\Response(
        *          response=403,
        *          description="Forbidden"
        *      )
 * )
     * @param  \App\Models\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function show(Channel $channel)
    {
        // eager loads the channel with selected ID and its relationships
        return new ChannelResource($channel->loadMissing(['videos', 'videos.comments']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Channel $channel)
    {
        // To be implemented in CA2
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Channel $channel)
    {
        // To be implemented in CA2
    }
}
