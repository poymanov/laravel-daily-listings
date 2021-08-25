<?php

declare(strict_types=1);

namespace App\UseCase\Listing\Media\Store;

use App\Models\Listing;
use App\Models\User;
use Exception;

class Handler
{
    public function handle(Command $command): void
    {
        $userId    = $command->getUserId();
        $listingId = $command->getId();

        /** @var User|null $user */
        $user = User::find($userId);

        if (!$user) {
            throw new Exception('User for listing not found, ID: ' . $userId);
        }

        /** @var Listing|null $listing */
        $listing = Listing::find($listingId);

        if (!$listing) {
            throw new Exception('Listing not found, ID: ' . $listingId);
        }

        if ($listing->user_id != $userId) {
            throw new Exception('User nor authorized for add media, ID: ' . $listingId . ', User IDL ' . $userId);
        }

        foreach ($command->getPhotos() as $photo) {
            $listing->addMedia($photo)->toMediaCollection('listings');
        }
    }
}
