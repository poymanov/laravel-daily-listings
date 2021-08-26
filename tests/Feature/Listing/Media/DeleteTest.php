<?php

declare(strict_types=1);

namespace Tests\Feature\Listing\Media;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Попытка посещения гостем
     */
    public function testGuest()
    {
        Storage::fake('public');

        $listing = $this->createListing();
        $listing->addMedia(UploadedFile::fake()->image('photo.jpg'))->toMediaCollection('listings');
        $media = $listing->getMedia('listings')->first();

        $response = $this->delete($this->makeUrl($listing->id, $media->id));
        $response->assertRedirect(self::LOGIN_URL);
    }

    /**
     * Попытка посещения пользователем, который ещё не завершил регистрацию
     */
    public function testRegistrationNotCompleted()
    {
        Storage::fake('public');

        $user    = $this->createUser([], true);
        $listing = $this->createListing(['user_id' => $user->id]);
        $listing->addMedia(UploadedFile::fake()->image('photo.jpg'))->toMediaCollection('listings');
        $media = $listing->getMedia('listings')->first();

        $this->signIn($user);
        $response = $this->delete($this->makeUrl($listing->id, $media->id));
        $response->assertRedirect('/registration-step-two');
    }

    /**
     * Попытка загрузки изображения для предложения другого пользователя
     */
    public function testAnotherUser()
    {
        Storage::fake('public');

        $user    = $this->createUser();
        $listing = $this->createListing();
        $listing->addMedia(UploadedFile::fake()->image('photo.jpg'))->toMediaCollection('listings');
        $media = $listing->getMedia('listings')->first();

        $this->signIn($user);
        $response = $this->delete($this->makeUrl($listing->id, $media->id));
        $response->assertForbidden();
    }

    /**
     * Успешное удаление изображения
     */
    public function testSuccess()
    {
        Storage::fake('public');

        $user    = $this->createUser();
        $listing = $this->createListing(['user_id' => $user->id]);
        $listing->addMedia(UploadedFile::fake()->image('photo.jpg'))->toMediaCollection('listings');
        $media = $listing->getMedia('listings')->first();

        $this->signIn($user);
        $response = $this->delete($this->makeUrl($listing->id, $media->id));
        $response->assertRedirect('/listings/' . $listing->id . '/edit');

        $this->assertDatabaseCount('media', 0);
        $this->assertCount(0, Storage::disk('public')->allFiles());
    }

    /**
     * Формирование адреса удаления изображения предложения
     *
     * @param int $listingId ID предложения, для которого необходимо удалить изображение
     * @param int $mediaId   ID изображения, которое необходимо удалить
     *
     * @return string
     */
    public function makeUrl(int $listingId, int $mediaId): string
    {
        return '/listings/' . $listingId . '/media/' . $mediaId;
    }
}
