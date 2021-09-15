<?php

declare(strict_types=1);

namespace Tests\Feature\Listing\Listing;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilterTest extends TestCase
{
    use RefreshDatabase;

    private const URL = '/listings';

    /**
     * Фильтр предложений успешно отображается
     */
    public function testSuccessWithFilter()
    {
        $category = $this->createCategory();
        $color    = $this->createColor();
        $size     = $this->createSize();
        $city     = $this->createCity();

        $this->signIn();
        $response = $this->get(self::URL);
        $response->assertOk();

        $response->assertSee($category->name);
        $response->assertSee($color->name);
        $response->assertSee($size->name);
        $response->assertSee($city->name);
        $response->assertSee('Search');
    }

    /**
     * Фильтрация предложений по названию
     */
    public function testByTitle()
    {
        $listingFirst  = $this->createListing(['title' => 'Test']);
        $listingSecond = $this->createListing();

        $this->signIn();
        $response = $this->get(self::URL . '?title=te');
        $response->assertOk();

        $response->assertSee($listingFirst->title);
        $response->assertDontSee($listingSecond->title);
    }

    /**
     * Фильтрация предложений по категории
     */
    public function testByCategory()
    {
        $category = $this->createCategory();

        $listingFirst = $this->createListing();
        $listingFirst->categories()->attach($category);

        $listingSecond = $this->createListing();

        $this->signIn();
        $response = $this->get(self::URL . '?category=' . $category->id);
        $response->assertOk();

        $response->assertSee($listingFirst->title);
        $response->assertDontSee($listingSecond->title);
    }

    /**
     * Фильтрация предложений по цвету
     */
    public function testByColor()
    {
        $color = $this->createColor();

        $listingFirst = $this->createListing();
        $listingFirst->colors()->attach($color);

        $listingSecond = $this->createListing();

        $this->signIn();
        $response = $this->get(self::URL . '?color=' . $color->id);
        $response->assertOk();

        $response->assertSee($listingFirst->title);
        $response->assertDontSee($listingSecond->title);
    }

    /**
     * Фильтрация предложений по размеру
     */
    public function testBySize()
    {
        $size = $this->createSize();

        $listingFirst = $this->createListing();
        $listingFirst->sizes()->attach($size);

        $listingSecond = $this->createListing();

        $this->signIn();
        $response = $this->get(self::URL . '?size=' . $size->id);
        $response->assertOk();

        $response->assertSee($listingFirst->title);
        $response->assertDontSee($listingSecond->title);
    }

    /**
     * Фильтрация предложений по городу автора предложения
     */
    public function testByUserCity()
    {
        $user = $this->createUser();

        $listingFirst  = $this->createListing(['user_id' => $user->id]);
        $listingSecond = $this->createListing();

        $this->signIn();
        $response = $this->get(self::URL . '?city=' . $user->city->id);
        $response->assertOk();

        $response->assertSee($listingFirst->title);
        $response->assertDontSee($listingSecond->title);
    }

    /**
     * Фильтрация предложений по сохраненным текущим пользователем
     */
    public function testBySaved()
    {
        $user = $this->createUser();

        $listingFirst  = $this->createListing();
        $listingSecond = $this->createListing();

        $user->savedListings()->attach($listingFirst);

        $this->signIn($user);

        $response = $this->get(self::URL . '?saved=on');
        $response->assertOk();

        $response->assertSee($listingFirst->title);
        $response->assertDontSee($listingSecond->title);
    }

    /**
     * Отображение нулевого количества сохраненных предложений пользователя
     */
    public function testNotSavedListings()
    {
        $this->signIn();
        $response = $this->get(self::URL);
        $response->assertOk();
        $response->assertSee('Saved (0)');
    }

    /**
     * Отображение количества сохраненных предложений пользователя
     */
    public function testSavedListings()
    {
        $user = $this->createUser();

        $listing = $this->createListing(['user_id' => $user->id]);
        $user->savedListings()->attach($listing);

        $this->signIn($user);

        $response = $this->get(self::URL);
        $response->assertOk();
        $response->assertSee('Saved (1)');
    }
}
