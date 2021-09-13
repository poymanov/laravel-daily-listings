<?php

declare(strict_types=1);

namespace Tests\Feature\Listing\Listing\Index\Save;

use App\Http\Livewire\Listing\SaveButton;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UnSaveTest extends TestCase
{
    use RefreshDatabase;

    private const URL = '/listings';

    /**
     * Кнопка удаления из сохраненных не должна отображаться для собственных предложений
     */
    public function testUnSaveButtonSelfListing()
    {
        $user = $this->createUser();
        $this->createListing(['user_id' => $user->id]);

        $this->signIn($user);
        $response = $this->get(self::URL);
        $response->assertOk();

        $response->assertDontSee('Unsave');
    }

    /**
     * Отображение кнопки отмены сохранения для сохраненного предложения
     */
    public function testSuccessUnSaveButton()
    {
        $user    = $this->createUser();
        $listing = $this->createListing();

        $user->savedListings()->attach($listing);

        $this->signIn($user);
        $response = $this->get(self::URL);
        $response->assertOk();

        $response->assertSee('UnSave');
    }

    /**
     * Попытка удаления из сохраненных не сохраненного предложения
     */
    public function testNotSaved()
    {
        $notSavedId = 999;

        $user = $this->createUser();

        $this->signIn($user);

        Livewire::test(SaveButton::class)
            ->set('listingId', $notSavedId)
            ->call('unSave')
            ->assertRedirect('/listings')
            ->assertSessionHas('alert.error', 'Listing not saved');
    }

    /**
     * Успешное удаленние сохранненого предложения
     */
    public function testSuccessSave()
    {
        $user    = $this->createUser();
        $listing = $this->createListing();

        $user->savedListings()->attach($listing);

        $this->signIn($user);

        Livewire::test(SaveButton::class)
            ->set('listingId', $listing->id)
            ->call('unSave');

        $this->assertDatabaseMissing('listing_user', [
            'listing_id' => $listing->id,
            'user_id'    => $user->id,
        ]);
    }
}
