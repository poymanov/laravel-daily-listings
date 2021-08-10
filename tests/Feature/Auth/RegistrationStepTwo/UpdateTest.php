<?php

declare(strict_types=1);

namespace Tests\Feature\Auth\RegistrationStepTwo;

use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private const URL = '/registration-step-two';

    /**
     * Попытка посещения гостем
     */
    public function testGuest()
    {
        $response = $this->patch(self::URL);
        $response->assertRedirect(self::LOGIN_URL);
    }

    /**
     * Попытка посещения пользователем, который уже завершил регистрацию
     */
    public function testAlreadyCompleted()
    {
        $this->signIn();
        $response = $this->patch(self::URL);
        $response->assertRedirect('/dashboard');
    }

    /**
     * Попытка завершения регистрации с пустыми данными
     */
    public function testEmpty()
    {
        $this->signIn($this->createUser([], true));
        $response = $this->patch(self::URL);
        $response->assertSessionHasErrors(['phone', 'address', 'city_id']);
    }

    /**
     * Попытка завершения регистрации c коротким значением телефона
     */
    public function testTooShortPhone()
    {
        $this->signIn($this->createUser([], true));
        $response = $this->patch(self::URL, ['phone' => '12']);
        $response->assertSessionHasErrors(['phone']);
    }

    /**
     * Попытка завершения регистрации с коротким значением адреса
     */
    public function testTooShortAddress()
    {
        $this->signIn($this->createUser([], true));
        $response = $this->patch(self::URL, ['address' => 'te']);
        $response->assertSessionHasErrors(['address']);
    }

    /**
     * Попытка завершения регистрации с некорректным значением города
     */
    public function testWrongCityId()
    {
        $this->signIn($this->createUser([], true));
        $response = $this->patch(self::URL, ['city_id' => 999]);
        $response->assertSessionHasErrors(['city_id']);
    }

    /**
     * Успешное завершение регистрации
     */
    public function testSuccess()
    {
        /** @var City $city */
        $city = City::factory()->create();

        $user = $this->createUser([], true);

        $this->signIn($user);

        $address = $this->faker->address();
        $phone   = $this->faker->phoneNumber();

        $response = $this->patch(self::URL, ['city_id' => $city->id, 'address' => $address, 'phone' => $phone]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas('users', [
            'id'      => $user->id,
            'city_id' => $city->id,
            'address' => $address,
            'phone'   => $phone,
        ]);
    }
}
