<?php

declare(strict_types=1);

namespace Tests\Feature\Listing\Media;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ShowTest extends TestCase
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
     * Успешное открытие страницы с загруженными изображениями
     */
    public function testSuccess()
    {
        Storage::fake('public');

        $user    = $this->createUser();
        $listing = $this->createListing(['user_id' => $user->id]);
        $listing->addMedia(UploadedFile::fake()->image('photo1.jpg'))->toMediaCollection('listings');
        $listing->addMedia(UploadedFile::fake()->image('photo2.jpg'))->toMediaCollection('listings');

        $this->signIn($user);
        $response = $this->get($this->makeUrl($listing->id));
        $response->assertOk();
        $response->assertSee('Media');
        $response->assertSee('Delete');

        $media = $listing->getMedia('listings');

        $response->assertSee($media[0]->getUrl('thumb'));
        $response->assertSee($media[0]->getUrl());

        $response->assertSee($media[1]->getUrl('thumb'));
        $response->assertSee($media[1]->getUrl());
    }


    /**
     * Формирование адреса просмотра списка изображения
     *
     * @param int $id ID предложения, для которого необходимо сформировать адрес страницы просмотра списка изображения
     *
     * @return string
     */
    public function makeUrl(int $id): string
    {
        return '/listings/' . $id . '/media';
    }
}
