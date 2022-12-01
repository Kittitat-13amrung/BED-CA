<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comments;
use App\Models\YoutubeVideo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;



class CommentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Eager loading video data with relationships
        $comments = Comments::paginate('20');

        // Return data encapsulated in a collection paginated by 10
        return CommentResource::collection($comments)->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *      path="/api/comments/{id}",
     *      tags={"Comments"},
     *      summary="Create a new comment in a video",
     *      description="store the data as a comment in a youtube video.",
     *    security={{ "bearerAuth": {} }},
     *          @OA\Parameter(
     *          name="id",
     *          description="video id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer")
     *          ),
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"comment"},
     *            @OA\Property(property="comment", type="string", format="string", example="Hi, this is a test"),
     *      ),
     *      ),
     *     @OA\Response(
     *          response=200, description="Success",
     *              @OA\JsonContent(ref="#/components/schemas/Comment")
     *          ),
     *     @OA\Response(
     *          response=404, description="Comment Not Found",
     *          ),
     *     @OA\Response(
     *          response=401, description="Unauthorised Access",
     *          ),
     *     )
     * )
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $channel = $request->user();
        // FIX
        // dd([$request->all(), 'channel_id' => $channel]);

        // create a new data with the validated data
        // $video = YoutubeVideo::create([$request, 'channel_id' => $channel]);
        $comment = new Comments($request->only('comment'));
        // $comment->youtube_video_id = $id;
        // $comment->save();
        $video = YoutubeVideo::find($id);
        $video->comments()->save($comment);
        $comment->channels()->save($channel);
        // dd($request->only('comment'));
        // $comment->channels()->attach($comment->id);
        // declare a variable to store the array of data
        $uploadedComment = new CommentResource($comment);

        return response()->json($uploadedComment, Response::HTTP_CREATED); // returns the array in JSON format to the user
    }

    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *      path="/api/comments/{id}",
     *      tags={"Comments"},
     *      summary="Get a comment in a youtube video",
     *      description="retrieve a comment, given it existed, from a youtube video.",
     *          @OA\Parameter(
     *          name="id",
     *          description="video id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer")
     *          ),
     *     @OA\Response(
     *          response=200, description="Success",
     *              @OA\JsonContent(ref="#/components/schemas/Comment")
     *          ),
     *     @OA\Response(
     *          response=404, description="Comment Not Found",
     *          ),
     * )
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // retreive all the comments using the id implemented from the URL
        try {
            $comments = Comments::findOrFail($id);
            // dd($comments);
        } catch (ModelNotFoundException $ex) {
            return response()->json(["message" => "The video with id " . $id . " cannot be found in our database.", "status" => "404"], Response::HTTP_NOT_FOUND);
        };

        // return response()->json(["message" => `The video with id`, "status" => "404"], Response::HTTP_NOT_FOUND);

        // returns the findings as an array of objects to the user.
        return new CommentResource($comments);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showChannelComments()
    {
        $comments = auth()->user()->comments;
        // dd($comments);
        // if (!isNull($comments)) {
        $collection = CommentResource::collection($comments);
        // } else {
        //     return response()->json(["message" => "The comments do not exist", "status" => Response::HTTP_NOT_FOUND]);
        // }

        return $collection;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comments $comment)
    {
        // dd(auth()->user()->comments->find($comment)->id);
        $response = Gate::inspect('update', $comment);
        // dd($response);
        if ($response->allowed()) {
            // update the youtube video data with the new request
            $comment->update($request->all());

            // then return the newly updated resource to the body
            return new CommentResource($comment);
        } else {
            return response()->json(["message" => "There are no comment with this ID created by your channel", "status" => Response::HTTP_NOT_FOUND]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comments $comment)
    {

        // dd(auth()->user()->comments->find($comment)
        $response = Gate::inspect('delete', $comment);
        // dd($response);

        if ($response->allowed()) {
            // delete the selected video
            $comment->delete();
        } else {
            return response()->json(["message" => "There are no comment with this ID created by your channel", "status" => Response::HTTP_NOT_FOUND]);
        }

        // then response back with HTTP response of code 200 to display message to indicate a succesful action
        return response()->json(["message" => "The comment has been successfully deleted", "status" => "202"], Response::HTTP_ACCEPTED);
    }
}
