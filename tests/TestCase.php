<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected const LOGIN_URL = '/login';

    /**
     * @param null $user
     */
    protected function signIn($user = null): void
    {
        $user = $user ?: User::factory()->create();
        $this->actingAs($user);
    }

    /**
     * Создание сущности User
     *
     * @param array $params                     Параметры нового пользователя
     * @param bool  $isNotCompletedRegistration Признак - пользователь не завершил регистрацию
     *
     * @return User
     */
    protected function createUser(array $params = [], bool $isNotCompletedRegistration = false): User
    {
        $factory = User::factory();

        if ($isNotCompletedRegistration) {
            $factory = $factory->withNotCompletedRegistration();
        }

        return $factory->create($params);
    }
}
