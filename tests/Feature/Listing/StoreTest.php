<?php

declare(strict_types=1);

namespace Tests\Feature\Listing;

use App\Models\Listing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private const URL = '/listing';

    /**
     * Попытка посещения гостем
     */
    public function testGuest()
    {
        $response = $this->post(self::URL);
        $response->assertRedirect(self::LOGIN_URL);
    }

    /**
     * Попытка посещения пользователем, который ещё не завершил регистрацию
     */
    public function testRegistrationNotCompleted()
    {
        $this->signIn($this->createUser([], true));
        $response = $this->post(self::URL);
        $response->assertRedirect('/registration-step-two');
    }

    /**
     * Попытка создания пустого предложения
     */
    public function testEmpty()
    {
        $this->signIn();

        $response = $this->post(self::URL);
        $response->assertSessionHasErrors(['title', 'description', 'price']);
    }

    /**
     * Попытка создания с коротким наименованием
     */
    public function testTooShortTitle()
    {
        $this->signIn();

        $response = $this->post(self::URL, ['title' => 'te']);
        $response->assertSessionHasErrors(['title']);
    }

    /**
     * Попытка создания с коротким описанием
     */
    public function testTooShortDescription()
    {
        $this->signIn();

        $response = $this->post(self::URL, ['description' => 'te']);
        $response->assertSessionHasErrors(['description']);
    }

    /**
     * Попытка создания с нулевой ценой
     */
    public function testNullablePrice()
    {
        $this->signIn();

        $response = $this->post(self::URL, ['price' => 0]);
        $response->assertSessionHasErrors(['price']);
    }

    /**
     * Попытка создания с отрицательной ценой
     */
    public function testNegativePrice()
    {
        $this->signIn();

        $response = $this->post(self::URL, ['price' => -1]);
        $response->assertSessionHasErrors(['price']);
    }

    /**
     * Успешное создание
     */
    public function testSuccess()
    {
        $user = $this->createUser();

        $this->signIn($user);

        /** @var Listing $listing */
        $listing = Listing::factory()->make();

        $response = $this->post(self::URL, $listing->toArray());
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/listing');

        $response->assertSessionHas('alert.success', 'Listing created');

        $this->assertDatabaseHas('listings', [
            'title'       => $listing->title,
            'description' => $listing->description,
            'price'       => $listing->price,
            'user_id'     => $user->id,
        ]);
    }

    /**
     * Успешное создание с загрузкой изображения
     */
    public function testSuccessWithImage()
    {
        Storage::fake('public');

        $user = $this->createUser();

        $this->signIn($user);

        /** @var Listing $listing */
        $listing = Listing::factory()->make();

        $response = $this->post(self::URL, $listing->toArray() + [
                'photo' => UploadedFile::fake()->image('photo.jpg'),
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/listing');

        $response->assertSessionHas('alert.success', 'Listing created');

        $this->assertDatabaseHas('listings', [
            'title'       => $listing->title,
            'description' => $listing->description,
            'price'       => $listing->price,
            'user_id'     => $user->id,
        ]);

        $this->assertDatabaseCount('media', 1);
        $this->assertCount(2, Storage::disk('public')->allFiles());
    }
}
