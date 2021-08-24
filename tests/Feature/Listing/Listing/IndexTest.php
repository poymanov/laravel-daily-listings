<?php

declare(strict_types=1);

namespace Tests\Feature\Listing\Listing;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    private const URL = '/listings';

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

        $response->assertSee('Create');
        $response->assertSee($listing->title);
        $response->assertSee($listing->description);
        $response->assertSee($listing->price);
    }

    /**
     * Страница успешно открывается с отображением изображений
     */
    public function testSuccessWithImage()
    {
        Storage::fake('public');

        $listing = $this->createListing();
        $listing->addMedia(UploadedFile::fake()->image('photo1.jpg'))->toMediaCollection('listings');

        $this->signIn($this->createUser());
        $response = $this->get(self::URL);
        $response->assertOk();

        $response->assertSee('Create');
        $response->assertSee($listing->getFirstMediaUrl('listings', 'thumb'));
        $response->assertSee($listing->title);
        $response->assertSee($listing->description);
        $response->assertSee($listing->price);
    }

    /**
     * Ссылка редактирования не отображается для предложений других пользователей
     */
    public function testAnotherUserEditLink()
    {
        $this->createListing();

        $this->signIn($this->createUser());
        $response = $this->get(self::URL);
        $response->assertDontSee('Edit');
    }

    /**
     * Ссылка редактирования отображается для предложений авторизованного пользователя
     */
    public function testEditLink()
    {
        $user = $this->createUser();

        $this->createListing(['user_id' => $user->id]);

        $this->signIn($user);
        $response = $this->get(self::URL);
        $response->assertSee('Edit');
    }

    /**
     * Ссылка удаления не отображается для предложений других пользователей
     */
    public function testAnotherUserDeleteLink()
    {
        $this->createListing();

        $this->signIn($this->createUser());
        $response = $this->get(self::URL);
        $response->assertDontSee('Delete');
    }

    /**
     * Ссылка удаления отображается для предложений авторизованного пользователя
     */
    public function testDeleteLink()
    {
        $user = $this->createUser();

        $this->createListing(['user_id' => $user->id]);

        $this->signIn($user);
        $response = $this->get(self::URL);
        $response->assertSee('Delete');
    }
}
