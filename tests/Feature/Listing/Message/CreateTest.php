<?php

declare(strict_types=1);

namespace Tests\Feature\Listing\Message;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Попытка посещения гостем
     */
    public function testGuest()
    {
        $listing = $this->createListing();

        $response = $this->get($this->makeUrl($listing->id));
        $response->assertRedirect(self::LOGIN_URL);
    }

    /**
     * Попытка посещения пользователем, который ещё не завершил регистрацию
     */
    public function testRegistrationNotCompleted()
    {
        $user    = $this->createUser([], true);
        $listing = $this->createListing(['user_id' => $user->id]);

        $this->signIn($user);
        $response = $this->get($this->makeUrl($listing->id));
        $response->assertRedirect('/registration-step-two');
    }

    /**
     * Попытка открытия страницы отправки сообщения для собственного предложения
     */
    public function testSelfListing()
    {
        $user    = $this->createUser();
        $listing = $this->createListing(['user_id' => $user->id]);

        $this->signIn($user);
        $response = $this->get($this->makeUrl($listing->id));
        $response->assertForbidden();
    }

    /**
     * Страница успешно открывается
     */
    public function testSuccess()
    {
        $listing = $this->createListing();

        $this->signIn($this->createUser());
        $response = $this->get($this->makeUrl($listing->id));
        $response->assertOk();

        $response->assertSee('New Message');
        $response->assertSee('Text');
        $response->assertSee('Send');
    }

    /**
     * Формирование адреса отправки сообщения
     *
     * @param int $id ID предложения, для которого необходимо сформировать адрес отправки сообщения
     *
     * @return string
     */
    public function makeUrl(int $id): string
    {
        return '/listings/' . $id . '/messages/create';
    }
}
