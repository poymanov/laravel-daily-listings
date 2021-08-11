<?php

declare(strict_types=1);

namespace App\UseCase\Auth\RegistrationStepTwo\Update;

use Illuminate\Http\UploadedFile;

class Command
{
    /** @var int */
    private int $userId;

    /** @var int */
    private int $cityId;

    /** @var string */
    private string $address;

    /** @var string */
    private string $phone;

    /** @var UploadedFile|null */
    private ?UploadedFile $photo;

    /**
     * @param int               $userId
     * @param int               $cityId
     * @param string            $address
     * @param string            $phone
     * @param UploadedFile|null $photo
     */
    public function __construct(int $userId, int $cityId, string $address, string $phone, ?UploadedFile $photo)
    {
        $this->userId  = $userId;
        $this->cityId  = $cityId;
        $this->address = $address;
        $this->phone   = $phone;
        $this->photo   = $photo;
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
    public function getCityId(): int
    {
        return $this->cityId;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @return UploadedFile|null
     */
    public function getPhoto(): ?UploadedFile
    {
        return $this->photo;
    }
}
