<?php

declare(strict_types=1);

namespace Tests\Feature\Listing\Listing;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    private const URL = '/listings/create';

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
        $this->signIn($this->createUser());
        $response = $this->get(self::URL);
        $response->assertOk();

        $response->assertSee('New Listing');
        $response->assertSee('Title');
        $response->assertSee('Description');
        $response->assertSee('Price');
        $response->assertSee('Create');
        $response->assertSee('Photo');
    }

    /**
     * Список категорий для предложения успешно отображается
     */
    public function testSuccessCategories()
    {
        $this->createListing();

        $categoryFirst  = $this->createCategory();
        $categorySecond = $this->createCategory();

        $this->signIn($this->createUser());
        $response = $this->get(self::URL);
        $response->assertOk();

        $response->assertSee($categoryFirst->id);
        $response->assertSee($categorySecond->id);

        $response->assertSee($categoryFirst->name);
        $response->assertSee($categorySecond->name);
    }

    /**
     * Список цветов для предложения успешно отображается
     */
    public function testSuccessColors()
    {
        $this->createListing();

        $colorFirst  = $this->createColor();
        $colorSecond = $this->createColor();

        $this->signIn($this->createUser());
        $response = $this->get(self::URL);
        $response->assertOk();

        $response->assertSee($colorFirst->id);
        $response->assertSee($colorSecond->id);

        $response->assertSee($colorFirst->name);
        $response->assertSee($colorSecond->name);
    }

    /**
     * Список размеров для предложения успешно отображается
     */
    public function testSuccessSizes()
    {
        $this->createListing();

        $sizeFirst  = $this->createSize();
        $sizeSecond = $this->createSize();

        $this->signIn($this->createUser());
        $response = $this->get(self::URL);
        $response->assertOk();

        $response->assertSee($sizeFirst->id);
        $response->assertSee($sizeSecond->id);

        $response->assertSee($sizeFirst->name);
        $response->assertSee($sizeSecond->name);
    }
}
