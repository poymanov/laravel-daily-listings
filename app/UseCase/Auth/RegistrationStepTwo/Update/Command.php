<?php

declare(strict_types=1);

namespace App\UseCase\Auth\RegistrationStepTwo\Update;

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

    /**
     * @param int    $userId
     * @param int    $cityId
     * @param string $address
     * @param string $phone
     */
    public function __construct(int $userId, int $cityId, string $address, string $phone)
    {
        $this->userId  = $userId;
        $this->cityId  = $cityId;
        $this->address = $address;
        $this->phone   = $phone;
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
}
