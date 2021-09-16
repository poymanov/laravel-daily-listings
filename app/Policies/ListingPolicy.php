<?php

namespace App\Policies;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ListingPolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\User    $user
     * @param \App\Models\Listing $listing
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Listing $listing)
    {
        return $user->id == $listing->user_id;
    }

    /**
     * @param \App\Models\User    $user
     * @param \App\Models\Listing $listing
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Listing $listing)
    {
        return $user->id == $listing->user_id;
    }

    /**
     * @param User    $user
     * @param Listing $listing
     *
     * @return bool
     */
    public function sendMessage(User $user, Listing $listing)
    {
        return $user->id != $listing->user_id;
    }

    /**
     * @param User    $user
     * @param Listing $listing
     *
     * @return bool
     */
    public function saveListing(User $user, Listing $listing)
    {
        return $user->id != $listing->user_id;
    }
}
