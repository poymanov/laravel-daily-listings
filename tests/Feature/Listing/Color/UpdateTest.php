<?php

declare(strict_types=1);

namespace Tests\Feature\Listing\Color;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Попытка изменения гостем
     */
    public function testGuest()
    {
        $listing = $this->createListing();

        $response = $this->post($this->makeUrl($listing->id));
        $response->assertRedirect(self::LOGIN_URL);
    }

    /**
     * Попытка изменения пользователем, который ещё не завершил регистрацию
     */
    public function testRegistrationNotCompleted()
    {
        $user    = $this->createUser([], true);
        $listing = $this->createListing(['user_id' => $user->id]);

        $this->signIn($user);
        $response = $this->post($this->makeUrl($listing->id));
        $response->assertRedirect('/registration-step-two');
    }

    /**
     * Попытка изменения цветов предложения другого пользователя
     */
    public function testAnotherUser()
    {
        $user    = $this->createUser();
        $listing = $this->createListing();
        $color   = $this->createColor();

        $this->signIn($user);
        $response = $this->post($this->makeUrl($listing->id), ['colors' => [$color->id]]);
        $response->assertForbidden();
    }

    /**
     * Попытка изменения цветов без указания самих цветов
     */
    public function testEmpty()
    {
        $user    = $this->createUser();
        $listing = $this->createListing(['user_id' => $user->id]);

        $this->signIn($user);
        $response = $this->post($this->makeUrl($listing->id));
        $response->assertSessionHasErrors('colors');
    }

    /**
     * Успешное изменение цветов
     */
    public function testSuccess()
    {
        $user    = $this->createUser();
        $listing = $this->createListing(['user_id' => $user->id]);

        $colorFirst  = $this->createColor();
        $colorSecond = $this->createColor();

        $this->signIn($user);
        $response = $this->post($this->makeUrl($listing->id), ['colors' => [$colorFirst->id, $colorSecond->id]]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/listings/' . $listing->id . '/edit');

        $response->assertSessionHas('alert.success', 'Colors updated');

        $this->assertDatabaseCount('color_listing', 2);

        $this->assertDatabaseHas('color_listing', [
            'color_id'   => $colorFirst->id,
            'listing_id' => $listing->id,
        ]);

        $this->assertDatabaseHas('color_listing', [
            'color_id'   => $colorSecond->id,
            'listing_id' => $listing->id,
        ]);
    }

    /**
     * Формирование адреса изменения цветов
     *
     * @param int $id ID предложения, для которого необходимо сформировать адрес изменения цветов
     *
     * @return string
     */
    public function makeUrl(int $id): string
    {
        return '/listings/' . $id . '/colors';
    }
}
