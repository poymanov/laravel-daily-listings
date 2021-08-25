<?php

declare(strict_types=1);

namespace App\UseCase\Listing\Media\Store;

use Illuminate\Http\UploadedFile;

class Command
{
    /** @var int */
    private int $id;

    /** @var int */
    private int $userId;

    /** @var UploadedFile[] */
    private array $photos;

    /**
     * @param int            $id
     * @param int            $userId
     * @param UploadedFile[] $photos
     */
    public function __construct(int $id, int $userId, array $photos)
    {
        $this->id     = $id;
        $this->userId = $userId;
        $this->photos = $photos;
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
     * @return UploadedFile[]
     */
    public function getPhotos(): array
    {
        return $this->photos;
    }
}
