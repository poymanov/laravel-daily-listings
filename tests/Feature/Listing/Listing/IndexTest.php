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
     * Страница успешно отображается с категориями
     */
    public function testSuccessWithCategories()
    {
        $listing = $this->createListing();

        $categoryFirst  = $this->createCategory();
        $categorySecond = $this->createCategory();

        $listing->categories()->attach([$categoryFirst->id, $categorySecond->id]);

        $this->signIn($this->createUser());
        $response = $this->get(self::URL);
        $response->assertOk();

        $response->assertSee($categoryFirst->name);
        $response->assertSee($categorySecond->name);
    }

    /**
     * Страница успешно отображается с цветами
     */
    public function testSuccessWithColors()
    {
        $listing = $this->createListing();

        $colorFirst  = $this->createColor();
        $colorSecond = $this->createColor();

        $listing->colors()->attach([$colorFirst->id, $colorSecond->id]);

        $this->signIn($this->createUser());
        $response = $this->get(self::URL);
        $response->assertOk();

        $response->assertSee($colorFirst->name);
        $response->assertSee($colorSecond->name);
    }

    /**
     * Страница успешно отображается с размерами
     */
    public function testSuccessWithSizes()
    {
        $listing = $this->createListing();

        $sizeFirst  = $this->createSize();
        $sizeSecond = $this->createSize();

        $listing->sizes()->attach([$sizeFirst->id, $sizeSecond->id]);

        $this->signIn($this->createUser());
        $response = $this->get(self::URL);
        $response->assertOk();

        $response->assertSee($sizeFirst->name);
        $response->assertSee($sizeSecond->name);
    }

    /**
     * Страница успешно отображается с городами пользователей, создавших предложения
     */
    public function testSuccessWithCities()
    {
        $userFirst = $this->createUser();
        $this->createListing(['user_id' => $userFirst->id]);

        $userSecond = $this->createUser();
        $this->createListing(['user_id' => $userSecond->id]);

        $this->signIn();
        $response = $this->get(self::URL);
        $response->assertOk();

        $response->assertSee($userFirst->city->name);
        $response->assertSee($userSecond->city->name);
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
