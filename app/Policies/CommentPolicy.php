<?php

namespace App\Policies;

use App\Models\Channel;
use App\Models\Comments;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the Channel can view any models.
     *
     * @param  \App\Models\Channel  $user
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
     * @param  \App\Models\Comments  $comments
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Channel $channel, Comments $comments)
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
     * @param  \App\Models\Comments  $comments
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Channel $channel, Comments $comment)
    {
        return $channel->id === $comment->channels()->get()->first()->id
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the Channel can delete the model.
     *
     * @param  \App\Models\Channel  $channel
     * @param  \App\Models\Comments  $comments
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Channel $channel, Comments $comment)
    {
        // dd($comment->channels()->get()->first()->id);
        return $channel->id === $comment->channels()->get()->first()->id
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the Channel can restore the model.
     *
     * @param  \App\Models\Channel  $channel
     * @param  \App\Models\Comments  $comments
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(Channel $channel, Comments $comments)
    {
        //
    }

    /**
     * Determine whether the Channel can permanently delete the model.
     *
     * @param  \App\Models\Channel  $channel
     * @param  \App\Models\Comments  $comments
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(Channel $channel, Comments $comments)
    {
        //
    }
}
