<?php

declare(strict_types=1);

namespace App\UseCase\Listing\Media\Delete;

use App\Models\Listing;
use App\Models\User;
use Exception;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Handler
{
    public function handle(Command $command): void
    {
        $userId    = $command->getUserId();
        $listingId = $command->getId();
        $mediaId   = $command->getMediaId();

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
            throw new Exception('User not authorized to delete media, ID: ' . $listingId . ', User ID: ' . $userId);
        }

        /** @var Media|null $media */
        $media = $listing->getMedia('listings')->where('id', $mediaId)->first();

        if (is_null($media)) {
            throw new Exception('Media not found, ID: ' . $mediaId);
        }

        if (!$media->delete()) {
            throw new Exception('Failed to delete media, ID: ' . $mediaId);
        }
    }
}
