<?php

declare(strict_types=1);

namespace Tests\Feature\Listing\Listing;

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

    private const URL = '/listings';

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
        $response->assertSessionHasErrors(['title', 'description', 'price', 'categories', 'colors', 'sizes']);
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
        $user     = $this->createUser();
        $category = $this->createCategory();
        $color    = $this->createColor();
        $size     = $this->createSize();

        $this->signIn($user);

        /** @var Listing $listing */
        $listing = Listing::factory()->make();

        $response = $this->post(
            self::URL,
            $listing->toArray() + [
                'categories' => [$category->id],
                'colors'     => [$color->id],
                'sizes'      => [$size->id],
            ]
        );

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/listings');

        $response->assertSessionHas('alert.success', 'Listing created');

        $this->assertDatabaseHas('listings', [
            'title'       => $listing->title,
            'description' => $listing->description,
            'price'       => $listing->price,
            'user_id'     => $user->id,
        ]);

        $this->assertDatabaseCount('category_listing', 1);

        $this->assertDatabaseHas('category_listing', [
            'category_id' => $category->id,
        ]);

        $this->assertDatabaseCount('color_listing', 1);

        $this->assertDatabaseHas('color_listing', [
            'color_id' => $color->id,
        ]);

        $this->assertDatabaseCount('listing_size', 1);

        $this->assertDatabaseHas('listing_size', [
            'size_id' => $color->id,
        ]);
    }

    /**
     * Успешное создание с загрузкой изображения
     */
    public function testSuccessWithImage()
    {
        Storage::fake('public');

        $category = $this->createCategory();
        $color    = $this->createColor();
        $size     = $this->createSize();

        $this->signIn($this->createUser());

        /** @var Listing $listing */
        $listing = Listing::factory()->make();

        $response = $this->post(
            self::URL,
            $listing->toArray() + [
                'photo'      => UploadedFile::fake()->image('photo.jpg'),
                'categories' => [$category->id],
                'colors'     => [$color->id],
                'sizes'      => [$size->id],
            ]
        );

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/listings');

        $response->assertSessionHas('alert.success', 'Listing created');

        $this->assertDatabaseCount('media', 1);
        $this->assertCount(2, Storage::disk('public')->allFiles());
    }
}
