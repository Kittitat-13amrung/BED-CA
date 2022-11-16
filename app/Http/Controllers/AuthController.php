<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:3',
                'email' => 'required|unique:channels|email',
                'password' => 'required|min:6'
            ]);

            if($validator->fails()) {
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

        } 
        catch(\Throwable $th) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        };

    }

    public function login(Request $request) {
        try {
            $validateChannel = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateChannel->fails()) {
                return response()->json([
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'validation error',
                    'errors' => $validateChannel->errors()
                ], Response::HTTP_UNAUTHORIZED);
            }

            // dd(Auth::attempt(['email' => $request->email, 'password' => $request->password]));

            if(!Auth::attempt($request->only(['email', 'password']))) {
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
        } catch(\Throwable $th) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $th->getMEssage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        };
    }

    public function channel() {
        return response()->json([
            'channel' => [auth()->user()]
        ], Response::HTTP_OK);
    }


}
