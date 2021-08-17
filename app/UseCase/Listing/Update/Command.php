<?php

declare(strict_types=1);

namespace App\UseCase\Listing\Update;

class Command
{
    /** @var int */
    private int $id;

    /** @var string */
    private string $title;

    /** @var string */
    private string $description;

    /** @var int */
    private int $price;

    /** @var int */
    private int $userId;

    /**
     * @param int    $id
     * @param string $title
     * @param string $description
     * @param int    $price
     * @param int    $userId
     */
    public function __construct(int $id, string $title, string $description, int $price, int $userId)
    {
        $this->id          = $id;
        $this->title       = $title;
        $this->description = $description;
        $this->price       = $price;
        $this->userId      = $userId;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}
