<?php

declare(strict_types=1);

namespace App\UseCase\Listing\Color\Update;

class Command
{
    /**
     * @var int
     */
    private int $listingId;

    /**
     * @var array
     */
    private array $colors;

    /**
     * @param int   $listingId
     * @param array $colors
     */
    public function __construct(int $listingId, array $colors)
    {
        $this->listingId = $listingId;
        $this->colors    = $colors;
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
    public function getColors(): array
    {
        return $this->colors;
    }
}
