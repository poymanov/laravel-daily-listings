<?php

namespace Tests;

use App\Models\Category;
use App\Models\Listing;
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


    /**
     * Создание сущности {@see Listing}
     *
     * @param array $params Параметры нового объекта
     *
     * @return Listing
     */
    protected function createListing(array $params = []): Listing
    {
        return Listing::factory()->create($params);
    }

    /**
     * Создание сущности {@see Category}
     *
     * @param array $params Параметры нового объекта
     *
     * @return Category
     */
    protected function createCategory(array $params = []): Category
    {
        return Category::factory()->create($params);
    }
}
