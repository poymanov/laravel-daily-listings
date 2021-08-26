<?php

declare(strict_types=1);

namespace App\UseCase\Listing\Media\Delete;

class Command
{
    /** @var int */
    private int $id;

    /** @var int */
    private int $userId;

    /** @var int */
    private int $mediaId;

    /**
     * @param int $id
     * @param int $userId
     * @param int $mediaId
     */
    public function __construct(int $id, int $userId, int $mediaId)
    {
        $this->id      = $id;
        $this->userId  = $userId;
        $this->mediaId = $mediaId;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
    public function getMediaId(): int
    {
        return $this->mediaId;
    }
}
