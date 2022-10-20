<?php

namespace App\Http\Controllers;

use App\Http\Resources\YoutubeVideoResource;
use App\Models\YoutubeVideo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;


    /**
     * Display a listing of the resource.
     *
 * @OA\Get(
 *     path="/api/youtubeVideos",
 *     description="Displays all the youtube videos with its relationship such as:
 *       the channel that created it.
 *       ",
 *     tags={"Youtube Videos"},
     *      @OA\Response(
        *          response=200,
        *          description="Successful operation, Returns a list of Books in JSON format"
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
     * @OA\Post(
     *      path="/api/youtubeVideos",
     *      operationId="store",
     *      tags={"Youtube Videos"},
     *      summary="Create a new youtube video",
     *      description="Stores the video in the DB",
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
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=""),
     *             @OA\Property(property="data",type="object")
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
     * @OA\Get(
    *     path="/api/youtubeVideos/{id}",
    *     description="Gets a video by its ID",
    *     tags={"Youtube Videos"},
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
        *          description="Successful operation"
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
     * Update the specified resource in storage.
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
     *      description="update and store the updated data to specified video in the DB",
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
     *          response=400, description="Invalid video ID",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=""),
     *             @OA\Property(property="data",type="object")
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
        *      ),
        *      @OA\Response(
        *          response=405,
        *          description="Validation exception"
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

        ]);

        // update the youtube video data with the new request
        $youtubeVideo->update($validation);

        // then return the newly updated resource to the body
        return new YoutubeVideoResource($youtubeVideo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *    path="/api/youtubeVideos/{id}",
     *    operationId="destroy",
     *    tags={"Youtube Videos"},
     *    summary="Delete a Video",
     *    description="Delete Video",
     *    @OA\Parameter(name="id", in="path", description="Id of a Video", required=true,
     *        @OA\Schema(type="integer")
     *    ),
     *    @OA\Response(
     *         response=Response::HTTP_NO_CONTENT,
     *         description="Success",
     *         @OA\JsonContent(
     *         @OA\Property(property="status_code", type="integer", example="204"),
     *         @OA\Property(property="data",type="object")
     *          ),
     *       )
     *      )
     *  )
     * @param  \App\Models\YoutubeVideo  $youtubeVideo
     * @return \Illuminate\Http\Response
     */
    public function destroy(YoutubeVideo $youtubeVideo)
    {
        // delete the selected data
        $youtubeVideo->delete();
        // then response back with HTTP response of code 204
        return response()->json(null, Response::HTTP_OK);
    }
}
