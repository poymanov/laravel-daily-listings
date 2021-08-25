<?php

declare(strict_types=1);

namespace Tests\Feature\Listing\Media;

use App\Models\Listing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Попытка посещения гостем
     */
    public function testGuest()
    {
        $listing = $this->createListing();

        $response = $this->post($this->makeUrl($listing->id));
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
        $response = $this->post($this->makeUrl($listing->id));
        $response->assertRedirect('/registration-step-two');
    }

    /**
     * Попытка загрузки изображения для предложения другого пользователя
     */
    public function testAnotherUser()
    {
        $user    = $this->createUser();
        $listing = $this->createListing();

        $this->signIn($user);
        $response = $this->post($this->makeUrl($listing->id), ['photos' => [UploadedFile::fake()->image('photo1.jpg')]]);
        $response->assertForbidden();
    }

    /**
     * Попытка загрузки без файлов изображений
     */
    public function testEmpty()
    {
        $user = $this->createUser();

        $this->signIn($user);

        /** @var Listing $listing */
        $listing = Listing::factory()->create(['user_id' => $user->id]);

        $response = $this->post($this->makeUrl($listing->id));
        $response->assertSessionHasErrors('photos');
    }

    /**
     * Успешная загрузка новых изображений
     */
    public function testSuccess()
    {
        Storage::fake('public');

        $user = $this->createUser();

        $this->signIn($user);

        /** @var Listing $listing */
        $listing = Listing::factory()->create(['user_id' => $user->id]);

        $response = $this->post($this->makeUrl($listing->id), [
            'photos' => [UploadedFile::fake()->image('photo1.jpg'), UploadedFile::fake()->image('photo2.jpg')],
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/listings/' . $listing->id . '/edit');

        $response->assertSessionHas('alert.success', 'Media added');

        $this->assertDatabaseCount('media', 2);
        $this->assertCount(4, Storage::disk('public')->allFiles());
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
