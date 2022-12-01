<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChannelResource;
use App\Http\Resources\CommentResource;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum', ['only' => ['channel', 'logout']]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Post(
     *     path="/api/auth/register",
     *     description="Register an account to retrieve data from API",
     *     summary="Register account",
     *     tags={"Authentication"},
     *    security={{ "bearerAuth": {} }},
     *          @OA\RequestBody(
     *            description="Login data",
     *            @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  description="Enter your channel name"
     *                ),
     *                @OA\Property(
     *                  property="email",
     *                  type="email",
     *                  description="Enter your channel email"
     *                ),
     *                @OA\Property(
     *                  property="password",
     *                  type="password",
     *                  description="Enter your channel password",
     *                ),
     *              )
     *            )
     *            ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully registered an account.",
     *          @OA\JsonContent(ref="#/components/schemas/auth")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated: Email is already used by another user.",
     *      ),
     *      @OA\Response(
     *          response=406,
     *          description="Unacceptable: Missing some or all parameters."
     *      ),
     * ),
     */

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:3',
                'email' => 'required|unique:channels|email',
                'password' => 'required|min:6'
            ]);

            if ($validator->fails()) {
                //return JSON response
                return response()->json([
                    'status' => Response::HTTP_NOT_ACCEPTABLE,
                    'message' => "validation error",
                    $validator->errors()
                ], Response::HTTP_NOT_ACCEPTABLE);
            };

            $channel = Channel::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            // create a token from the created channel - access in personal_access_token table 
            $token = $channel->createToken('channel-access-token')->plainTextToken;

            return response()->json([
                'status' => Response::HTTP_CREATED,
                'message' => 'Channel has been successfully registered',
                'token' => $token
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        };
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Post(
     *     path="/api/auth/login",
     *     description="Login to your account to retrieve data from API",
     *     summary="Login to your account",
     *     tags={"Authentication"},
     *    security={{ "bearerAuth": {} }},
     *          @OA\RequestBody(
     *            description="Login data",
     *            @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                  property="email",
     *                  type="email",
     *                  description="Enter your channel email"
     *                ),
     *                @OA\Property(
     *                  property="password",
     *                  type="password",
     *                  description="Enter your channel password",
     *                )
     *              )
     *            )
     *            ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/auth")
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
     */
    public function login(Request $request)
    {
        try {
            $validateChannel = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );

            if ($validateChannel->fails()) {
                return response()->json([
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'validation error',
                    'errors' => $validateChannel->errors()
                ], Response::HTTP_UNAUTHORIZED);
            }

            // dd(Auth::attempt(['email' => $request->email, 'password' => $request->password]));

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => Response::HTTP_NOT_ACCEPTABLE,
                    'message' => 'Email or password does not match with our record',
                ], Response::HTTP_NOT_ACCEPTABLE);
            }

            $channel = Channel::where('email', $request->email)->first();

            return response()->json([
                'status' => Response::HTTP_ACCEPTED,
                'message' => 'logged in successfully',
                'token' => $channel->createToken('channel-access-token')->plainTextToken
            ], Response::HTTP_ACCEPTED);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $th->getMEssage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        };
    }
    /**
     * Get details about your channel.
     *
     * @OA\Post(
     *     path="/api/auth/channel",
     *     description="Get everything related to your account such as name, subscriber, videos, comments etc.",
     *     summary="Get details about your channel",
     *     tags={"Authentication"},
     *    security={{ "bearerAuth": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *            allOf={
     *              @OA\Schema(ref="#/components/schemas/Channel"),
     *              @OA\Schema(ref="#/components/schemas/Comment"),
     *            }
     *          ),
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
     */

    public function channel()
    {

        $channel = new ChannelResource(auth()->user()->loadMissing(['videos', 'videos.comments']));
        $comments = CommentResource::collection(auth()->user()->comments);


        return response()->json([
            'channel' => $channel,
            'comments' => $comments
        ], Response::HTTP_OK);
    }

    /**
     * Delete the token when logged out.
     *
     * @OA\Post(
     *     path="/api/auth/logout",
     *     description="Logout of your account.",
     *     summary="Logout of your account",
     *     tags={"Authentication"},
     *      
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *            ref="#/components/schemas/auth",
     *          ),
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
     */
    public function logout(Request $request)
    {

        $request->user()->tokens()->delete();
        return response()->json(['message' => "The token has been successfully deleted", "status" => Response::HTTP_ACCEPTED], Response::HTTP_ACCEPTED);
    }
}
