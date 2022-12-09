<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\YoutubeVideoController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\CommentController;
use App\Models\YoutubeVideo;
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

// Route::middleware('auth:sanctum')->get('/channel', function($request, request) {
//     return $request->channel();
// });

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::prefix('/channel')->group(function () {
        Route::get('/', [AuthController::class, 'channel']);
        Route::delete('/', [AuthController::class, 'deleteChannel']);
        Route::match(['put', 'patch', 'post'], '/', [ChannelController::class, 'update']);

        // Get comments made by the user logged in using bearer token
        Route::get('/comments', [CommentController::class, 'showChannelComments']);
        Route::get('/videos', [YoutubeVideoController::class, 'showChannelVideos']);
    });
    // Route::put('channel', [ChannelController::class, 'update']);
});

// Created youtubeVideos URL with all the CRUD functionality
Route::apiResource('/youtubeVideos', YoutubeVideoController::class);

// Added a method to retrieve comments belong to a channel
Route::prefix('youtubeVideos')->group(function () {
    Route::get('/channel', [AuthController::class, 'channel']);
    Route::get('/{id}/comments', [YoutubeVideoController::class, 'showComments'])->name('showComments');
    Route::post('/{id}/comments/', [CommentController::class, 'store']);
    // Route::delete('{id}', [CommentController::class, 'store']);
});

// Created comments URL with all the CRUD functionality except store
// where the store will be in a youtubeVideo URL i.e. youtubeVideo/{id}/comments
Route::apiResource('/comments', CommentController::class)->except('store');

// Created channel URL with only index and show function attached
Route::apiResource('/channels', ChannelController::class)->only(['index', 'show']);


// Added another method to retrieve videos belong to a channel
Route::get('/channels/{id}/videos', [ChannelController::class, 'getVideos'])->name('showVideos');
