<?php

declare(strict_types=1);

namespace App\UseCase\Listing\Category\Update;

class Command
{
    /**
     * @var int
     */
    private int $listingId;

    /**
     * @var array
     */
    private array $categories;

    /**
     * @param int   $listingId
     * @param array $categories
     */
    public function __construct(int $listingId, array $categories)
    {
        $this->listingId  = $listingId;
        $this->categories = $categories;
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
    public function getCategories(): array
    {
        return $this->categories;
    }
}
