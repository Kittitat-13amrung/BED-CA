<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChannelRequest;
use App\Http\Resources\ChannelCollection;
use App\Http\Resources\ChannelResource;
use App\Http\Resources\YoutubeVideoResource;
use App\Models\Channel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
     *          @OA\Parameter(
     *          name="orderBy",
     *          description="orderBy allows data to be re-arrange in the order of descending order of id, name, created_at, and subscribers.",
     *          in="query",
     *          @OA\Schema(
     *              type="string")
     *          ),
     *          @OA\Parameter(
     *          name="random",
     *          description="retrieve data and pluck it in random order.",
     *          in="query",
     *          @OA\Schema(
     *              type="boolean")
     *          ),
     *          @OA\Parameter(
     *          name="subscribers",
     *          description="retrieve data by filtering the channels that has their subscribers below the specified threshold.",
     *          in="query",
     *          @OA\Schema(
     *              type="integer")
     *          ),
     *          @OA\Parameter(
     *          name="year",
     *          description="retrieve data by filtering the channels that are made in the specified year.",
     *          in="query",
     *          @OA\Schema(
     *              type="integer")
     *          ),
     *          @OA\Parameter(
     *          name="hasVideos",
     *          description="retrieve data by filtering the channels that have videos.",
     *          in="query",
     *          @OA\Schema(
     *              type="enum",
     *              enum={"yes","no"}
     *              ),
     *          ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation, Returns a list of Books in JSON format",
     *          @OA\JsonContent(ref="#/components/schemas/Channel")  
     *       ),
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

        if ($request->query()) { //if there is a query
            // switch cases if orderBy matches below
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
            }

            // get data in random order
            if ($request->get('random')) {
                $channels->inRandomOrder()->get();
            }

            // get data by filtering the amount of subscribers
            if ($request->get('subscribers')) {
                $channels->where('subscribers', '<=', $request->get('subscribers'));
            }

            // get data by fitering the creation year
            if ($request->get('year')) {
                $channels->whereYear('created_at', $request->get('year'));
            }

            // get data by filtering if the channels have videos
            if ($request->get('hasVideos') === "yes") {
                $channels->has('videos')->get();
            } else if ($request->get('hasVideos') === "no") {
                $channels->doesntHave('videos')->get();
            }
        }

        // Eager loading channel data with its relationships
        $channels->with(['videos', 'comments']);

        // responds in JSON format the collection of data
        return ChannelResource::collection($channels->paginate(10))->response();
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
    // public function store(Request $request)
    // {
    //     // Implemented in AuthController
    // }

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
    public function show($id)
    {
        // retreive all the comments using the id implemented from the URL
        try {
            $channel = Channel::findOrFail($id);
        } catch (ModelNotFoundException $ex) { //if channel doesn't exists throw an error
            return response()->json(["message" => "The channel with id " . $id . " cannot be found in our database.", "status" => "404"], Response::HTTP_NOT_FOUND);
        };

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
        // update the bearer token user info
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
    // public function destroy(Channel $channel)
    // {
    //     // Implemented in AuthController
    // }
}
