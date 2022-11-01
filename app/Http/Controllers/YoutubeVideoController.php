<?php

namespace App\Http\Controllers;

use App\Http\Resources\YoutubeVideoResource;
use App\Http\Resources\CommentResource;
use App\Models\YoutubeVideo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;


    /**
     *
     * 
     * 
     * 
 * @OA\Get(
 *     path="/api/youtubeVideos",
 *     description="Displays all the youtube videos with its relationship.",
 *      summary="Display all videos",
 *     tags={"Youtube Videos"},
     *      @OA\Response(
        *          response=200,
        *          description="Successful operation, Returns a list of Videos in JSON format",
        *          @OA\JsonContent(ref="#/components/schemas/youtube_video"),
        *          ),
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
     *
     * @return \Illuminate\Http\Response
     */

class YoutubeVideoController extends Controller
{
    /**
     * Returns all the videos in the database.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Eager loading video data with relationships
        $videos = YoutubeVideo::with(['comments', 'channel']);

        // Return data encapsulated in a collection paginated by 10
        return YoutubeVideoResource::collection($videos->paginate(10))->response();
    }

    /**
     * Store a newly created video in the database.
     *
     * @OA\Post(
     *      path="/api/youtubeVideos",
     *      operationId="store",
     *      tags={"Youtube Videos"},
     *      summary="Create a new youtube video",
     *      description="update and store the updated data to a specific youtube video in the database",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"title", "description", "duration", "likes", "dislikes", "views"},
     *            @OA\Property(property="title", type="string", format="string", example="Sample Title"),
     *            @OA\Property(property="description", type="string", format="string", example="A long description about this great book"),
     *            @OA\Property(property="duration", type="integer", format="integer", example="60"),
     *            @OA\Property(property="likes", type="integer", format="integer", example="200"),
     *            @OA\Property(property="dislikes", type="integer", format="integer", example="300") ,
     *            @OA\Property(property="views", type="integer", format="integer", example="1") 
     *          )
     *      ),
     *     @OA\Response(
     *          response=200, description="Success",
 *              @OA\JsonContent(ref="#/components/schemas/youtube_video")
     *          )
     *     )
     * )
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // Validating the request from POST method
        $validation = $request->validate([
            'title' => 'required|max:255', 
            'description' => '',
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
        // declare a variable to store the array of data
        $uploadedVideo = new YoutubeVideoResource($video->load(['comments','channel']));

        return response()->json($uploadedVideo, Response::HTTP_CREATED); // returns the array in JSON format to the user
    }

    /**
     * Display a youtube video by its ID.
     *
     * @OA\Get(
    *     path="/api/youtubeVideos/{id}",
    *     description="Retreive a youtube video specified by the ID parameter and displays it in a JSON format.",
    *     summary="Gets a youtube video by its ID",
    *     tags={"Youtube Videos"},
    *          @OA\Parameter(
        *          name="id",
        *          description="video id",
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
     * @param  \App\Models\YoutubeVideo  $youtubeVideo
     * @return \Illuminate\Http\Response
     */
    public function show(YoutubeVideo $youtubeVideo)
    {
        // eager loads the video with selected ID and its relationships
        return new YoutubeVideoResource($youtubeVideo->load(['comments','channel']));
    }

    /**
     * Update the specified video that existed in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\YoutubeVideo  $youtubeVideo
     * @return \Illuminate\Http\Response
     * 
     * @OA\Put(
     *      path="/api/youtubeVideos/{id}",
     *      operationId="put",
     *      tags={"Youtube Videos"},
     *      summary="Update a youtube video by id",
     *      description="update and store the updated data to a specific youtube video in the database",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"title", "description", "duration", "likes", "dislikes", "views"},
     *            @OA\Property(property="title", type="string", format="string", example="Sample Title"),
     *            @OA\Property(property="description", type="string", format="string", example="A long description about this great book"),
     *            @OA\Property(property="duration", type="integer", format="integer", example="60"),
     *            @OA\Property(property="likes", type="integer", format="integer", example="200"),
     *            @OA\Property(property="dislikes", type="integer", format="integer", example="300") ,
     *            @OA\Property(property="views", type="integer", format="integer", example="1") 
     *          )
     *      ),
     *     @OA\Response(
     *          response=Response::HTTP_CREATED, description="Video updated",
     *          @OA\JsonContent(ref="#/components/schemas/youtube_video")
     *          ),
     *     @OA\Response(
     *          response=400, description="Invalid video ID",
     *          ),
        *      @OA\Response(
        *          response=401,
        *          description="Unauthenticated",
        *      ),
        *      @OA\Response(
        *          response=403,
        *          description="Forbidden"
        *      )
     *          ),
        *      @OA\Response(
        *          response=404,
        *          description="Video not found",
        *      )
     *          
     *     )
     * )
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
            'thumbnail' =>"url"

        ]);

        // update the youtube video data with the new request
        $youtubeVideo->update($validation);

        // then return the newly updated resource to the body
        return new YoutubeVideoResource($youtubeVideo->load(['comments','channel']));
    }

    /**
     * Remove the specified video from the database.
     *
     * @OA\Delete(
     *    path="/api/youtubeVideos/{id}",
     *    operationId="destroy",
     *    tags={"Youtube Videos"},
     *    summary="Delete a youtube video by its ID",
     *    description="Delete a youtube video specified by the ID parameter.",
     *    @OA\Parameter(name="id", in="path", description="Id of a Video", required=true,
     *        @OA\Schema(type="integer")
     *    ),
     *    @OA\Response(
     *         response=Response::HTTP_ACCEPTED,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/successful_delete"),
     *       ),
     *    @OA\Response(
     *         response=Response::HTTP_NOT_FOUND,
     *         description="Video not found"
     *       ),
     *      )
     *  )
     * @param  \App\Models\YoutubeVideo  $youtubeVideo
     * @return \Illuminate\Http\Response
     */
    public function destroy(YoutubeVideo $youtubeVideo)
    {
        // delete the selected video
        $youtubeVideo->delete();
        // then response back with HTTP response of code 200 to display message to indicate a succesful action
        return response()->json(["message" => "The video has been successfully delete", "status" => "202"], Response::HTTP_ACCEPTED);
    }

    /**
     * Display all the comments from a video using its ID.
     *
     * @OA\Get(
    *     path="/api/youtubeVideos/{id}/comments",
    *     description="Retreive comments from a youtube video specified by the ID parameter and displays it in a JSON format.",
    *     summary="Gets comments by its youtube video ID",
    *     tags={"Youtube Videos"},
    *          @OA\Parameter(
        *          name="id",
        *          description="Video ID",
        *          required=true,
        *          in="path",
        *          @OA\Schema(
        *              type="integer")
     *          ),
        *      @OA\Response(
        *          response=200,
        *          description="Successful operation",
        *          @OA\JsonContent(ref="#/components/schemas/Comment")
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
    public function showComments($id) {
        // retreive all the comments using the id implemented from the URL
        $comments = YoutubeVideo::findOrFail($id)->comments;

        // returns the findings as an array of objects to the user.
        return CommentResource::collection($comments);
    }
}
