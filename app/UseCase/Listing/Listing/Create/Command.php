<?php

declare(strict_types=1);

namespace App\UseCase\Listing\Listing\Create;

use Illuminate\Http\UploadedFile;

class Command
{
    /** @var string */
    private string $title;

    /** @var string */
    private string $description;

    /** @var int */
    private int $price;

    /** @var int */
    private int $userId;

    /** @var UploadedFile|null */
    private ?UploadedFile $photo;

    /**
     * @param string            $title
     * @param string            $description
     * @param int               $price
     * @param int               $userId
     * @param UploadedFile|null $photo
     */
    public function __construct(string $title, string $description, int $price, int $userId, ?UploadedFile $photo)
    {
        $this->title       = $title;
        $this->description = $description;
        $this->price       = $price;
        $this->userId      = $userId;
        $this->photo       = $photo;
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

    /**
     * @return UploadedFile|null
     */
    public function getPhoto(): ?UploadedFile
    {
        return $this->photo;
    }
}
