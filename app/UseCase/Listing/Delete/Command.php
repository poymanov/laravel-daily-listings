<?php

declare(strict_types=1);

namespace App\UseCase\Listing\Delete;

class Command
{
    /** @var int */
    private int $id;

    /** @var int */
    private int $userId;

    /**
     * @param int $id
     * @param int $userId
     */
    public function __construct(int $id, int $userId)
    {
        $this->id     = $id;
        $this->userId = $userId;
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
}
