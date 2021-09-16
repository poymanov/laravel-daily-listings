<?php

declare(strict_types=1);

namespace App\UseCase\Listing\Message\Store;

use App\Models\Listing;
use App\Models\Message;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Listing\Message as MessageNotification;
use Throwable;

class Handler
{
    /**
     * @param Command $command
     *
     * @throws Throwable
     */
    public function handle(Command $command): void
    {
        $userId    = $command->getUserId();
        $listingId = $command->getListingId();
        $text      = $command->getText();

        /** @var User|null $user */
        $user = User::find($userId);

        if (!$user) {
            throw new Exception('User for send message not found, ID: ' . $userId);
        }

        /** @var Listing|null $listing */
        $listing = Listing::find($listingId);

        if (!$listing) {
            throw new Exception('Listing not found, ID: ' . $listingId);
        }

        /** @var User|null */
        $listingAuthor = $listing->user;

        if (!$listingAuthor) {
            throw new Exception('Listing author not found, ID: ' . $userId);
        }


        DB::transaction(function () use ($user, $listing, $text, $listingAuthor) {
            $message             = new Message();
            $message->user_id    = $user->id;
            $message->listing_id = $listing->id;
            $message->text       = $text;

            if (!$message->save()) {
                throw new Exception('Failed to send message to user, ID: ' . $user->id);
            }

            Notification::send($listingAuthor, new MessageNotification($user->name, $user->email, $listing->title, $text));
        });
    }
}
