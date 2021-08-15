<?php

declare(strict_types=1);

namespace Tests\Feature\Listing;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    private const URL = '/listing';

    /**
     * Попытка посещения гостем
     */
    public function testGuest()
    {
        $response = $this->get(self::URL);
        $response->assertRedirect(self::LOGIN_URL);
    }

    /**
     * Попытка посещения пользователем, который ещё не завершил регистрацию
     */
    public function testRegistrationNotCompleted()
    {
        $this->signIn($this->createUser([], true));
        $response = $this->get(self::URL);
        $response->assertRedirect('/registration-step-two');
    }

    /**
     * Страница успешно открывается
     */
    public function testSuccess()
    {
        $listing = $this->createListing();

        $this->signIn($this->createUser());
        $response = $this->get(self::URL);
        $response->assertOk();

        $response->assertSee($listing->title);
        $response->assertSee($listing->description);
        $response->assertSee($listing->price);
    }
}
