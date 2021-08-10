<?php

declare(strict_types=1);

namespace Tests\Feature\Auth\RegistrationStepTwo;

use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditTest extends TestCase
{
    use RefreshDatabase;

    private const URL = '/registration-step-two';

    /**
     * Попытка посещения гостем
     */
    public function testGuest()
    {
        $response = $this->get(self::URL);
        $response->assertRedirect(self::LOGIN_URL);
    }

    /**
     * Попытка посещения пользователем, который уже завершил регистрацию
     */
    public function testAlreadyCompleted()
    {
        $this->signIn();
        $response = $this->get(self::URL);
        $response->assertRedirect('/dashboard');
    }

    /**
     * Форма успешно отображается
     */
    public function testSuccess()
    {
        /** @var City $cityFirst */
        /** @var City $citySecond */
        $cityFirst  = City::factory()->create();
        $citySecond = City::factory()->create();

        $this->signIn($this->createUser([], true));

        $response = $this->get(self::URL);
        $response->assertOk();
        $response->assertSee('Phone');
        $response->assertSee('Address');
        $response->assertSee('City');
        $response->assertSee($cityFirst->name);
        $response->assertSee($citySecond->name);
    }
}
