<?php

declare(strict_types=1);

namespace Tests\Feature\Listing\Category;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Попытка изменения гостем
     */
    public function testGuest()
    {
        $listing = $this->createListing();

        $response = $this->post($this->makeUrl($listing->id));
        $response->assertRedirect(self::LOGIN_URL);
    }

    /**
     * Попытка изменения пользователем, который ещё не завершил регистрацию
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
     * Попытка изменения категорий предложения другого пользователя
     */
    public function testAnotherUser()
    {
        $user     = $this->createUser();
        $listing  = $this->createListing();
        $category = $this->createCategory();

        $this->signIn($user);
        $response = $this->post($this->makeUrl($listing->id), ['categories' => [$category->id]]);
        $response->assertForbidden();
    }

    /**
     * Попытка изменения категорий без указания самих категорий
     */
    public function testEmpty()
    {
        $user    = $this->createUser();
        $listing = $this->createListing(['user_id' => $user->id]);

        $this->signIn($user);
        $response = $this->post($this->makeUrl($listing->id));
        $response->assertSessionHasErrors('categories');
    }

    /**
     * Успешное изменение категорий
     */
    public function testSuccess()
    {
        $user    = $this->createUser();
        $listing = $this->createListing(['user_id' => $user->id]);

        $categoryFirst  = $this->createCategory();
        $categorySecond = $this->createCategory();

        $this->signIn($user);
        $response = $this->post($this->makeUrl($listing->id), ['categories' => [$categoryFirst->id, $categorySecond->id]]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/listings/' . $listing->id . '/edit');

        $response->assertSessionHas('alert.success', 'Categories updated');

        $this->assertDatabaseCount('category_listing', 2);

        $this->assertDatabaseHas('category_listing', [
            'category_id' => $categoryFirst->id,
            'listing_id'  => $listing->id,
        ]);

        $this->assertDatabaseHas('category_listing', [
            'category_id' => $categorySecond->id,
            'listing_id'  => $listing->id,
        ]);
    }

    /**
     * Формирование адреса изменения категорий
     *
     * @param int $id ID предложения, для которого необходимо сформировать адрес изменения категорий
     *
     * @return string
     */
    public function makeUrl(int $id): string
    {
        return '/listings/' . $id . '/categories';
    }
}
