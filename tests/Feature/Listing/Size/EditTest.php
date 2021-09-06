<?php

declare(strict_types=1);

namespace Tests\Feature\Listing\Size;

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
        $user       = $this->createUser();
        $listing    = $this->createListing(['user_id' => $user->id]);
        $sizeFirst  = $this->createSize();
        $sizeSecond = $this->createSize();

        $this->signIn($user);
        $response = $this->get($this->makeUrl($listing->id));
        $response->assertOk();
        $response->assertSee($sizeFirst->name);
        $response->assertSee($sizeSecond->name);
        $response->assertSee('Update');
    }

    /**
     * Формирование адреса просмотра списка размеров
     *
     * @param int $id ID предложения, для которого необходимо сформировать адрес страницы просмотра списка размеров
     *
     * @return string
     */
    public function makeUrl(int $id): string
    {
        return '/listings/' . $id . '/sizes';
    }
}
