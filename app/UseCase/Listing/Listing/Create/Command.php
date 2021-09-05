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

    /** @var int[] */
    private array $categories;

    /** @var int[] */
    private array $colors;

    /** @var int[] */
    private array $sizes;

    /** @var UploadedFile|null */
    private ?UploadedFile $photo;

    /**
     * @param string            $title
     * @param string            $description
     * @param int               $price
     * @param int               $userId
     * @param int[]             $categories
     * @param int[]             $colors
     * @param int[]             $sizes
     * @param UploadedFile|null $photo
     */
    public function __construct(
        string $title,
        string $description,
        int $price,
        int $userId,
        array $categories,
        array $colors,
        array $sizes,
        ?UploadedFile $photo
    ) {
        $this->title       = $title;
        $this->description = $description;
        $this->price       = $price;
        $this->userId      = $userId;
        $this->categories  = $categories;
        $this->colors      = $colors;
        $this->sizes       = $sizes;
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
     * @return int[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @return int[]
     */
    public function getColors(): array
    {
        return $this->colors;
    }

    /**
     * @return int[]
     */
    public function getSizes(): array
    {
        return $this->sizes;
    }

    /**
     * @return UploadedFile|null
     */
    public function getPhoto(): ?UploadedFile
    {
        return $this->photo;
    }
}
