<?php

declare(strict_types=1);

namespace Tests\Feature\Listing\Size;

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
     * Попытка изменения размеров предложения другого пользователя
     */
    public function testAnotherUser()
    {
        $user    = $this->createUser();
        $listing = $this->createListing();
        $size    = $this->createSize();

        $this->signIn($user);
        $response = $this->post($this->makeUrl($listing->id), ['sizes' => [$size->id]]);
        $response->assertForbidden();
    }

    /**
     * Попытка изменения размеров без указания самих цветов
     */
    public function testEmpty()
    {
        $user    = $this->createUser();
        $listing = $this->createListing(['user_id' => $user->id]);

        $this->signIn($user);
        $response = $this->post($this->makeUrl($listing->id));
        $response->assertSessionHasErrors('sizes');
    }

    /**
     * Успешное изменение размеров
     */
    public function testSuccess()
    {
        $user    = $this->createUser();
        $listing = $this->createListing(['user_id' => $user->id]);

        $sizeFirst  = $this->createSize();
        $sizeSecond = $this->createSize();

        $this->signIn($user);
        $response = $this->post($this->makeUrl($listing->id), ['sizes' => [$sizeFirst->id, $sizeSecond->id]]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/listings/' . $listing->id . '/edit');

        $response->assertSessionHas('alert.success', 'Sizes updated');

        $this->assertDatabaseCount('listing_size', 2);

        $this->assertDatabaseHas('listing_size', [
            'size_id'    => $sizeFirst->id,
            'listing_id' => $listing->id,
        ]);

        $this->assertDatabaseHas('listing_size', [
            'size_id'    => $sizeSecond->id,
            'listing_id' => $listing->id,
        ]);
    }

    /**
     * Формирование адреса изменения размеров
     *
     * @param int $id ID предложения, для которого необходимо сформировать адрес изменения размеров
     *
     * @return string
     */
    public function makeUrl(int $id): string
    {
        return '/listings/' . $id . '/sizes';
    }
}
