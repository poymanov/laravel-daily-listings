<?php

declare(strict_types=1);

namespace App\UseCase\Listing\Size\Update;

class Command
{
    /**
     * @var int
     */
    private int $listingId;

    /**
     * @var array
     */
    private array $sizes;

    /**
     * @param int   $listingId
     * @param array $sizes
     */
    public function __construct(int $listingId, array $sizes)
    {
        $this->listingId = $listingId;
        $this->sizes     = $sizes;
    }

    /**
     * @return int
     */
    public function getListingId(): int
    {
        return $this->listingId;
    }

    /**
     * @return array
     */
    public function getSizes(): array
    {
        return $this->sizes;
    }
}
