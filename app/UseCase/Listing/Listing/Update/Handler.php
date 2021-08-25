<?php

declare(strict_types=1);

namespace App\UseCase\Listing\Listing\Update;

use App\Models\Listing;
use App\Models\User;
use Exception;

class Handler
{
    /**
     * @param Command $command
     *
     * @throws Exception
     */
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

        $listing->title       = $command->getTitle();
        $listing->description = $command->getDescription();
        $listing->price       = $command->getPrice();

        if (!$listing->save()) {
            throw new Exception('Failed to update listing');
        }
    }
}
