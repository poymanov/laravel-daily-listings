<?php

declare(strict_types=1);

namespace Tests\Feature\Listing\Listing\Index\Save;

use App\Http\Livewire\Listing\SaveButton;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SaveTest extends TestCase
{
    use RefreshDatabase;

    private const URL = '/listings';

    /**
     * Кнопка сохранения не должна отображаться для собственных предложений
     */
    public function testSaveButtonSelfListing()
    {
        $user = $this->createUser();
        $this->createListing(['user_id' => $user->id]);

        $this->signIn($user);
        $response = $this->get(self::URL);
        $response->assertOk();

        $response->assertDontSee('Add to saved');
    }

    /**
     * Отображение кнопки сохранения для предложений других пользователей
     */
    public function testSuccessSaveButton()
    {
        $this->createListing();

        $this->signIn($this->createUser());
        $response = $this->get(self::URL);
        $response->assertOk();

        $response->assertSee('Add to saved');
    }

    /**
     * Попытка сохранения несуществующего предложения
     */
    public function testSaveNotExisted()
    {
        $notExistedId = 999;

        $user = $this->createUser();

        $this->signIn($user);

        Livewire::test(SaveButton::class)
            ->set('listingId', $notExistedId)
            ->call('save')
            ->assertRedirect('/listings')
            ->assertSessionHas('alert.error', 'Listing not found');

        $this->assertDatabaseMissing('listing_user', [
            'listing_id' => $notExistedId,
            'user_id'    => $user->id,
        ]);
    }

    /**
     * Попытка сохранения собственного предложения
     */
    public function testSaveSelf()
    {
        $user = $this->createUser();

        $listing = $this->createListing(['user_id' => $user->id]);

        $this->signIn($user);

        Livewire::test(SaveButton::class)
            ->set('listingId', $listing->id)
            ->call('save')
            ->assertRedirect('/listings')
            ->assertSessionHas('alert.error', 'Failed to save yourself listing');

        $this->assertDatabaseMissing('listing_user', [
            'listing_id' => $listing->id,
            'user_id'    => $user->id,
        ]);
    }

    /**
     * Попытка сохранения предложения, которое уже сохранено
     */
    public function testSaveAlreadySaved()
    {
        $user    = $this->createUser();
        $listing = $this->createListing();

        $user->savedListings()->save($listing);

        $this->signIn($user);

        Livewire::test(SaveButton::class)
            ->set('listingId', $listing->id)
            ->call('save')
            ->assertRedirect('/listings')
            ->assertSessionHas('alert.error', 'Failed to save already saved listing');
    }

    /**
     * Успешное сохранение предложения для пользователя
     */
    public function testSuccessSave()
    {
        $listing = $this->createListing();

        $user = $this->createUser();

        $this->signIn($user);

        Livewire::test(SaveButton::class)
            ->set('listingId', $listing->id)
            ->call('save');

        $this->assertDatabaseHas('listing_user', [
            'listing_id' => $listing->id,
            'user_id'    => $user->id,
        ]);
    }
}
