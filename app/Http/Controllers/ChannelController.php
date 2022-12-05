<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChannelRequest;
use App\Http\Resources\ChannelCollection;
use App\Http\Resources\ChannelResource;
use App\Http\Resources\YoutubeVideoResource;
use App\Models\Channel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ChannelController extends Controller
{

    // middleware on only Update and Delete functions
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['only' => ['update', 'delete']]);
    }

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
    public function index(Request $request)
    {


        // Query Eloquent Building
        $channels = Channel::query();

        if ($request->query()) {

            switch ($request->get('orderBy')) {
                case 'desc':
                    $channels->orderBy('id', 'desc');
                    break;
                case 'subscribers':
                    $channels->orderBy('subscribers');
                    break;
                case 'title':
                    $channels->orderBy('name');
                    break;
                case 'created_at':
                    $channels->orderBy('created_at');
                    break;
                case 'likes':
                    $channels->orderBy('likes');
                    break;
                case 'dislikes':
                    $channels->orderBy('dislikes');
                    break;
            }

            if ($request->get('random')) {
                $channels->inRandomOrder()->get();
            }

            if ($request->get('likes')) {
                $channels->where('likes', '<=', $request->get('likes'));
            }

            if ($request->get('dislikes')) {
                $channels->where('dislikes', '<=', $request->get('likes'));
            }

            if ($request->get('created_at')) {
                $channels->where('created_at', '<=', $request->get('created_at'));
            }

            if ($request->get('updated_at')) {
                $channels->where('updated_at', '<=', $request->get('created_at'));
            }

            if ($request->get('hasVideo') === "yes") {
                $channels->has('videos')->get();
            } else {
                $channels->doesntHave('videos')->get();
            }
        }

        // Eager loading channel data with its relationships
        $channels->with(['videos', 'comments']);

        // responds in JSON format the collection of data
        return ChannelResource::collection($channels->paginate(10))->response();
        // return new ChannelCollection($channels->paginate(50));
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
        // Implemented in AuthController
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
     * @OA\Put(
     *     path="/api/auth/channel",
     *     description="Update Channel details",
     *     summary="Update all details in your channel",
     *     tags={"Authentication"},
     *    security={{ "bearerAuth": {} }},
     *          @OA\RequestBody(
     *            @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  description="Update your channel name"
     *                ),
     *                @OA\Property(
     *                  property="email",
     *                  type="email",
     *                  description="Update your channel email"
     *                ),
     *                @OA\Property(
     *                  property="password",
     *                  type="password",
     *                  description="Update your channel password",
     *                )
     *              )
     *            )
     *            ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Channel")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * ),
     *     * @OA\Patch(
     *     path="/api/auth/channel",
     *     description="Update Channel details",
     *     summary="Update a specific detail in your channel",
     *     tags={"Authentication"},
     *    security={{ "bearerAuth": {} }},
     *          @OA\RequestBody(
     *            @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  description="Update your channel name"
     *                ),
     *                @OA\Property(
     *                  property="email",
     *                  type="email",
     *                  description="Update your channel email"
     *                ),
     *                @OA\Property(
     *                  property="password",
     *                  type="password",
     *                  description="Update your channel password",
     *                )
     *              )
     *            )
     *            ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Channel")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * )
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function update(ChannelRequest $request)
    {
        // To be implemented in CA2
        $channel = Channel::findOrFail(auth()->user()->id);

        $channel->update($request->all());
        $channel->save();

        // return response()->json(["message" => "The channel details have been successfully updated", "status" => Response::HTTP_ACCEPTED], Response::HTTP_ACCEPTED);
        return new ChannelResource($channel->loadMissing(['videos', 'videos.comments']));
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
        $channel = Channel::findOrFail(auth()->user()->id);
        $channel->delete();
    }
}
