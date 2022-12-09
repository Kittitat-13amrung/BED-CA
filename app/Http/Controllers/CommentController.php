<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comments;
use App\Models\YoutubeVideo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{

    // Implement middlewares on all routes except index and show
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/api/comments",
     *     description="Displays all the comments with its relationship ie. Channel that made the comment.",
     *      summary="Display all comments",
     *     tags={"Comments"},
     *          @OA\Parameter(
     *          name="orderBy",
     *          description="orderBy allows data to be re-arrange in the order of title, created_at, views, likes, and dislikes.",
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
     *          name="likes",
     *          description="retrieve data by filtering the comments that have the amount of likes less than the specified number.",
     *          in="query",
     *          @OA\Schema(
     *              type="integer")
     *          ),
     * *          @OA\Parameter(
     *          name="dislikes",
     *          description="retrieve data by filtering the comments that have the amount of dislikes less than the specified number.",
     *          in="query",
     *          @OA\Schema(
     *              type="integer")
     *          ),
     * *          @OA\Parameter(
     *          name="year",
     *          description="retrieve data by filtering the comments that are made in the specified year.",
     *          in="query",
     *          @OA\Schema(
     *              type="integer")
     *          ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation, Returns a list of Comments in JSON format",
     *          @OA\JsonContent(ref="#/components/schemas/Comment"),
     *          ),
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
    public function index(Request $request)
    {

        // Query Eloquent
        $comments = Comments::query();
        if ($request->query()) { //if there is a query
            // switch cases if orderBy matches below
            switch ($request->get('orderBy')) {
                case 'desc':
                    $comments->orderBy('id', 'desc');
                    break;
                case 'text':
                    $comments->orderBy('comment');
                    break;
                case 'created_at':
                    $comments->orderBy('created_at');
                    break;
                case 'updated_at':
                    $comments->orderBy('updated_at');
                    break;
                case 'likes':
                    $comments->orderBy('likes');
                    break;
                case 'dislikes':
                    $comments->orderBy('dislikes');
                    break;
            }

            // get data in random order
            if ($request->get('random')) {
                $comments->inRandomOrder()->get();
            }

            // get data filtered by amount of likes
            if ($request->get('likes')) {
                $comments->where('likes', '<=', $request->get('likes'));
            }

            // get data filtered by amount of dislikes
            if ($request->get('dislikes')) {
                $comments->where('dislikes', '<=', $request->get('dislikes'));
            }

            // get data filtered by creation year
            if ($request->get('year')) {
                $comments->whereYear('created_at', $request->get('year'));
            }
        };


        // Eager loading video data with relationships
        $comments->with('video');

        // Return data encapsulated in a collection paginated by 10
        return CommentResource::collection($comments->paginate(20))->response();
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
     *          @OA\RequestBody(
     *                required=true,
     *            @OA\MediaType(
     *                mediaType="application/x-www-form-urlencoded",
     *                @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                  property="comment",
     *                  type="string",
     *                  description="Post a new comment"
     *                ),
     *              )
     *            )
     *            ),
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
    public function store(CommentRequest $request, $id)
    {
        // get the user that made the request
        $channel = $request->user();

        // create a new data with the validated data
        $comment = new Comments($request->only('comment'));
        $video = YoutubeVideo::find($id); // find the video from parameter
        $video->comments()->save($comment); // save the comment into that video
        $comment->channels()->save($channel); // then save comment to channel
        // declare a variable to store the array of data
        $uploadedComment = new CommentResource($comment->load('video'));

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
        } catch (ModelNotFoundException $ex) { //if comment doesn't exists throw an error
            return response()->json(["message" => "The video with id " . $id . " cannot be found in our database.", "status" => "404"], Response::HTTP_NOT_FOUND);
        };


        // returns the findings as an array of objects to the user.
        return new CommentResource($comments->load(['video']));
    }

    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *      path="/api/auth/channel/comments",
     *      tags={"Authentication"},
     *      summary="Get comment(s) made by your channel",
     *      description="retrieve comment(s), given it existed, from the token bearer.",
     *    security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *          response=200, description="Success",
     *              @OA\JsonContent(ref="#/components/schemas/Comment")
     *          ),
     *     @OA\Response(
     *          response=401, description="Unauthorised",
     *          ),
     *     @OA\Response(
     *          response=404, description="Comment Not Found",
     *          ),
     * )
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showChannelComments()
    {
        // Extracts data from the logged in user
        $comments = auth()->user()->comments;
        // Fetch the data into a collection
        $collection = CommentResource::collection($comments);
        // Return data as JSON format
        return $collection;
    }

    /**
     * Update the specified resource in storage.
     * 
     * @OA\Put(
     *      path="/api/comments/{id}",
     *      operationId="CommentPut",
     *      tags={"Comments"},
     *      summary="Update a comment by id",
     *      description="update and store the updated data to a specific comment in the database. The comment must be the one made by your channel. Otherwise, an error will display.",
     *    security={{ "bearerAuth": {} }},
     *          @OA\Parameter(
     *          name="id",
     *          description="ID of the comment from your channel. You could look through your comments made by your channel in the Channels tag",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer")
     *          ),
     *          @OA\RequestBody(
     *            required=true,
     *            @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                  property="comment",
     *                  type="string",
     *                  description="Update your comment body"
     *                )
     *              ),
     *            ),
     *            ),
     *     @OA\Response(
     *          response=Response::HTTP_CREATED, description="Comment updated",
     *          @OA\JsonContent(ref="#/components/schemas/Comment")
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
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Comment not found",
     *      ),
     *          
     *     )
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CommentRequest $request, Comments $comment)
    {
        // Inspecting using CommentPolicy to see
        // if the user is the one who made the comment
        $response = Gate::inspect('update', $comment);

        if ($response->allowed()) {
            // update the comment with the new request
            $comment->update($request->all());

            // then return the newly updated resource to the body
            return new CommentResource($comment->load('video'));
        } else { //if user is declined throw an error message
            return response()->json(["message" => "There are no comment with this ID created by your channel", "status" => Response::HTTP_NOT_FOUND]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *    path="/api/comments/{id}",
     *    operationId="CommentDestroy",
     *    tags={"Comments"},
     *    summary="Delete a comment by its ID",
     *    description="Delete a comment specified by the ID parameter.",
     *    security={{ "bearerAuth": {} }},
     *    @OA\Parameter(name="id", in="path", description="ID of the Comment", required=true,
     *        @OA\Schema(type="integer")
     *    ),
     *    @OA\Response(
     *         response=Response::HTTP_ACCEPTED,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/successful_delete"),
     *       ),
     *    @OA\Response(
     *         response=Response::HTTP_NOT_FOUND,
     *         description="Comment not found"
     *       ),
     *      )
     *  ) 
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comments $comment)
    {
        // Inspecting using CommentPolicy to see
        // if the user is the one who made the comment
        $response = Gate::inspect('delete', $comment);

        if ($response->allowed()) {
            // delete the selected video
            $comment->delete();
        } else { //if user is declined throw an error message
            return response()->json(["message" => "There are no comment with this ID created by your channel", "status" => Response::HTTP_NOT_FOUND]);
        }

        // then response back with HTTP response of code 200 to display message to indicate a succesful action
        return response()->json(["message" => "The comment has been successfully deleted", "status" => "202"], Response::HTTP_ACCEPTED);
    }
}
