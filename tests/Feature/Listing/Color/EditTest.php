<?php

declare(strict_types=1);

namespace Tests\Feature\Listing\Color;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditTest extends TestCase
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
     * Попытка открытия страницы предложения другого пользователя
     */
    public function testAnotherUser()
    {
        $user    = $this->createUser();
        $listing = $this->createListing();

        $this->signIn($user);
        $response = $this->get($this->makeUrl($listing->id));
        $response->assertForbidden();
    }

    /**
     * Успешное открытие страницы
     */
    public function testSuccess()
    {
        $user        = $this->createUser();
        $listing     = $this->createListing(['user_id' => $user->id]);
        $colorFirst  = $this->createColor();
        $colorSecond = $this->createColor();

        $this->signIn($user);
        $response = $this->get($this->makeUrl($listing->id));
        $response->assertOk();
        $response->assertSee($colorFirst->name);
        $response->assertSee($colorSecond->name);
        $response->assertSee('Update');
    }

    /**
     * Формирование адреса просмотра списка цветов
     *
     * @param int $id ID предложения, для которого необходимо сформировать адрес страницы просмотра списка цветов
     *
     * @return string
     */
    public function makeUrl(int $id): string
    {
        return '/listings/' . $id . '/colors';
    }
}
