<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comments;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'showChannelComments']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $channel = $request->user()->id;
// FIX
        // dd([$request->all(), 'channel_id' => $channel]);

        // create a new data with the validated data
        // $video = YoutubeVideo::create([$request, 'channel_id' => $channel]);
        $comment = new Comments($request->only('comment'));
        $comment->youtube_video_id = $id;
        $comment->save();
        // dd($request->only('comment'));
        $comment->channels()->attach([$channel, $comment->id]);
        // declare a variable to store the array of data
        $uploadedComment = new CommentResource($comment);

        return response()->json($uploadedComment, Response::HTTP_CREATED); // returns the array in JSON format to the user
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showChannelComments(Request $request)
    {
        $comments = auth()->user()->comments;
        // dd($comments);

        $collection = CommentResource::collection($comments);

        return $collection;
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comments $comment)
    {
        
        $response = Gate::inspect('delete', $comment);
        
        if($response->allowed()) {
            // delete the selected video
            $comment->delete();
        } else {
                return response()->json(["message" => "There are no comment with this ID created by your channel", "status" => Response::HTTP_NOT_FOUND]);
        }

        // then response back with HTTP response of code 200 to display message to indicate a succesful action
        return response()->json(["message" => "The comment has been successfully deleted", "status" => "202"], Response::HTTP_ACCEPTED);
    }
}
