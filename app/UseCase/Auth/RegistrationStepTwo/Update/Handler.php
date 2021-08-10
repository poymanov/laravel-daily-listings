<?php

declare(strict_types=1);

namespace App\UseCase\Auth\RegistrationStepTwo\Update;

use App\Models\User;
use Exception;

class Handler
{
    /**
     * @param Command $command
     *
     * @throws Exception
     */
    public function handle(Command $command): void
    {
        $userId = $command->getUserId();

        /** @var User|null $user */
        $user = User::find($userId);

        if (!$user) {
            throw new Exception('User not found, ID: ' . $userId);
        }

        $user->city_id = $command->getCityId();
        $user->address = $command->getAddress();
        $user->phone   = $command->getPhone();

        if (!$user->save()) {
            throw new Exception('Failed to update user data, ID: ' . $userId);
        }
    }
}
