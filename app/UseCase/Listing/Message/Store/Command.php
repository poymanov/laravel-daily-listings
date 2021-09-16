<?php

declare(strict_types=1);

namespace App\UseCase\Listing\Message\Store;

class Command
{
    /** @var int */
    private int $userId;

    /** @var int */
    private int $listingId;

    /** @var string */
    private string $text;

    /**
     * @param int    $userId
     * @param int    $listingId
     * @param string $text
     */
    public function __construct(int $userId, int $listingId, string $text)
    {
        $this->userId    = $userId;
        $this->listingId = $listingId;
        $this->text      = $text;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getListingId(): int
    {
        return $this->listingId;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }
}
