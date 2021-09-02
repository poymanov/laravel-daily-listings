<?php

declare(strict_types=1);

namespace App\UseCase\Listing\Color\Update;

use App\Models\Listing;
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
        $colors    = $command->getColors();
        $listingId = $command->getListingId();

        /** @var Listing|null $listing */
        $listing = Listing::find($listingId);

        if (!$listing) {
            throw new Exception('Listing not found, ID: ' . $listingId);
        }

        $listing->colors()->sync($colors);
    }
}
