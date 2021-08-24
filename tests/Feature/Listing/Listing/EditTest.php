<?php

declare(strict_types=1);

namespace Tests\Feature\Listing\Listing;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
     * Попытка открытия страницы редактирования предложения другого пользователя
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
     * Успешное открытие страницы редактирования
     */
    public function testSuccess()
    {
        $user    = $this->createUser();
        $listing = $this->createListing(['user_id' => $user->id]);

        $this->signIn($user);
        $response = $this->get($this->makeUrl($listing->id));
        $response->assertOk();

        $response->assertSee('Edit Listing');
        $response->assertSee($listing->title);
        $response->assertSee($listing->description);
        $response->assertSee($listing->price);
        $response->assertSee('Update');
    }

    /**
     * Формирование адреса редактирования
     *
     * @param int $id ID предложения, для которого необходимо сформировать адрес страницы редактирования
     *
     * @return string
     */
    public function makeUrl(int $id): string
    {
        return '/listings/' . $id . '/edit';
    }
}
