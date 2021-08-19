<?php

declare(strict_types=1);

namespace App\UseCase\Listing\Create;

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
        $userId = $command->getUserId();

        /** @var User|null $user */
        $user = User::find($userId);

        if (!$user) {
            throw new Exception('User for listing not found, ID: ' . $userId);
        }

        $listing              = new Listing();
        $listing->title       = $command->getTitle();
        $listing->description = $command->getDescription();
        $listing->price       = $command->getPrice();
        $listing->user_id     = $userId;

        if (!$listing->save()) {
            throw new Exception('Failed to create listing');
        }

        foreach ($command->getPhotos() as $photo) {
            $listing->addMedia($photo)->toMediaCollection('listings');
        }
    }
}
