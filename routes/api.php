<?php

use App\Http\Controllers\YoutubeVideoController;
use App\Http\Controllers\ChannelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Created youtubeVideos URL with all the CRUD functionality
Route::apiResource('/youtubeVideos', YoutubeVideoController::class);

// Created channel URL with only index and show function attached
Route::apiResource('/channels', ChannelController::class)->only(['index', 'show']);