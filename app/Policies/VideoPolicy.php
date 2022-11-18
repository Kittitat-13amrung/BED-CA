<?php

namespace App\Policies;

use App\Models\Channel;
use App\Models\YoutubeVideo;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class VideoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(Channel $channel)
    {
        //
    }

    /**
     * Determine whether the Channel can view the model.
     *
     * @param  \App\Models\Channel  $channel
     * @param  \App\Models\YoutubeVideo  $youtubeVideo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Channel $channel, YoutubeVideo $youtubeVideo)
    {
        //
    }

    /**
     * Determine whether the Channel can create models.
     *
     * @param  \App\Models\Channel  $channel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(Channel $channel)
    {
        //
    }

    /**
     * Determine whether the Channel can update the model.
     *
     * @param  \App\Models\Channel  $channel
     * @param  \App\Models\YoutubeVideo  $youtubeVideo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Channel $channel, YoutubeVideo $youtubeVideo)
    {
        return $channel->id === $youtubeVideo->channel_id 
        ? Response::allow()
        : Response::deny();
    }

    /**
     * Determine whether the Channel can delete the model.
     *
     * @param  \App\Models\Channel  $channel
     * @param  \App\Models\YoutubeVideo  $youtubeVideo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Channel $channel, YoutubeVideo $youtubeVideo)
    {
        return $channel->id === $youtubeVideo->channel_id
        ? Response::allow()
        : Response::deny();
    }

    /**
     * Determine whether the Channel can restore the model.
     *
     * @param  \App\Models\Channel  $channel
     * @param  \App\Models\YoutubeVideo  $youtubeVideo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(Channel $channel, YoutubeVideo $youtubeVideo)
    {
        //
    }

    /**
     * Determine whether the Channel can permanently delete the model.
     *
     * @param  \App\Models\Channel  $channel
     * @param  \App\Models\YoutubeVideo  $youtubeVideo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(Channel $channel, YoutubeVideo $youtubeVideo)
    {
        //
    }
}
