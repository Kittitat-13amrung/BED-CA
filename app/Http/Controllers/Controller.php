<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Youtube Video API",
 *      description="This is a Youtube Video API that allow users to retrieve generated data about youtube video(s)
 *      as a JSON format.",
 *      @OA\Contact(
 *          email="n00201327@iadt.ie"
 *      ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *     ),
 * 
 * )
 * @OA/Get(
     *   path="/"
     *   description="Home page",
     *   @OA\Response(
     *     response="default",
     *     description="Welcome page",
     *   ),
     *   @OA\Schema(
 *                 schema="youtube_video",
 *                 @OA\Property(
 *                     property="id",
 *                     type="integer",
 *                     example="1"
 *                 ),
 *                 @OA\Property(
 *                     property="title",
 *                     type="string",
 *                     example="Lorem ipsum dolor sit amet, consectetur adipiscing elit."
 *                 ),
 *                 @OA\Property(
 *                     property="description",
 *                     type="text",
 *                     example="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sed erat mi. Vivamus mollis orci vitae neque fringilla, non interdum urna commodo. Cras at blandit lorem, malesuada posuere felis."                    
 *                 ),
  *                 @OA\Property(
 *                     property="created_at",
 *                     type="date",
 *                     example="2022-10-29"
 *                 ),
  *                 @OA\Property(
 *                     property="duration",
 *                     type="integer",
 *                     example="30"
 *                 ),
   *                 @OA\Property(
 *                     property="likes",
 *                     type="integer",
 *                     example="30"
 *                 ),
   *                 @OA\Property(
 *                     property="dislikes",
 *                     type="integer",
 *                     example="30"
 *                 ),
   *                 @OA\Property(
 *                     property="views",
 *                     type="integer",
 *                     example="30"
 *                 ),
   *                 @OA\Property(
 *                     property="link",
 *                     type="text",
 *                     example="youtube.com\/watch?v=1fa3d3b7-d0c7-3e5e-89e7-3713d0e74f87"
 *                 ),
   *                 @OA\Property(
 *                     property="thumbnail",
 *                     type="text",
 *                     example="https:\/\/via.placeholder.com\/640x480.png\/005500?text=thumbnail+voluptatem"
 *                 ),
 *                 @OA\Property(
 *                      property="channel",
 *                      ref="#/components/schemas/Channel")
 *                  ),
 *                 @OA\Property(
 *                      property="comments",
 *                      ref="#/components/schemas/Comment")
 *                  ),
 *                  )
 *                   @OA\Schema(
 *                     schema="Channel",
 *                     type="object",
 *                     @OA\Property(
 *                       property="id",
 *                       type="integer",
 *                       example="1",
 *                       description="channel id"
 *                     ),
 *                     @OA\Property(
 *                       property="name",
 *                       type="string",
 *                       example="James",
 *                       description="name of the channel"
 *                     ),
 *                     @OA\Property(
 *                       property="subscribers",
 *                       type="integer",
 *                       example="1100",
 *                       description="number of subscribers"
 *                     ),
 *                     @OA\Property(
 *                       property="created_at",
 *                       type="date",
 *                       format="date",
 *                       example="2019-10-06",
 *                       description="the date when the channel has been created"
 *                     ),
 *                     @OA\Property(
 *                       property="hasVideo",
 *                       type="integer",
 *                       format="integer",
 *                       example="100",
 *                       description="the amount of video"
 *                     )
 *                   ),
 *                      @OA\Schema(
 *                     schema="Comment",
 *                     type="object",
 *                     @OA\Property(
 *                       property="id",
 *                       type="integer",
 *                       example="1",
 *                       description="comment id"
 *                     ),
 *                     @OA\Property(
 *                       property="text",
 *                       type="text",
 *                       example="lorem ipsum",
 *                       description="comment texts"
 *                     ),
 *                     @OA\Property(
 *                       property="likes",
 *                       type="integer",
 *                       example="1100",
 *                       description="number of likes"
 *                     ),
 *                     @OA\Property(
 *                       property="dislikes",
 *                       type="integer",
 *                       example="90",
 *                       description="number of dislikes"
 *                     ),
 *                     @OA\Property(
 *                       property="commented_at",
 *                       type="string",
 *                       format="string",
 *                       example="6 years ago",
 *                       description="The date of the comment"
 *                     ),
 *                    @OA\Property(
 *                       property="channel",
 *                       ref="#/components/schemas/Channel")
 *                     ),
 *                   )
     *     
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
