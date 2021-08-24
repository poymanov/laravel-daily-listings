<?php

declare(strict_types=1);

namespace Tests\Feature\Listing\Listing;

use App\Models\Listing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Попытка обновления гостем
     */
    public function testGuest()
    {
        $listing = $this->createListing();

        $response = $this->patch($this->makeUrl($listing->id));
        $response->assertRedirect(self::LOGIN_URL);
    }

    /**
     * Попытка обновления пользователем, который ещё не завершил регистрацию
     */
    public function testRegistrationNotCompleted()
    {
        $user    = $this->createUser([], true);
        $listing = $this->createListing(['user_id' => $user->id]);

        $this->signIn($user);
        $response = $this->patch($this->makeUrl($listing->id));
        $response->assertRedirect('/registration-step-two');
    }

    /**
     * Попытка обновления предложения другого пользователя
     */
    public function testAnotherUser()
    {
        $user    = $this->createUser();
        $listing = $this->createListing();

        /** @var Listing $listingDraft */
        $listingDraft = Listing::factory()->make();

        $this->signIn($user);
        $response = $this->patch($this->makeUrl($listing->id), $listingDraft->toArray());
        $response->assertForbidden();
    }

    /**
     * Попытка обновления с пустыми данными
     */
    public function testEmpty()
    {
        $user    = $this->createUser();
        $listing = $this->createListing(['user_id' => $user->id]);

        $this->signIn($user);
        $response = $this->patch($this->makeUrl($listing->id));
        $response->assertSessionHasErrors(['title', 'description', 'price']);
    }

    /**
     * Попытка обновления с коротким наименованием
     */
    public function testTooShortTitle()
    {
        $user    = $this->createUser();
        $listing = $this->createListing(['user_id' => $user->id]);

        $this->signIn($user);
        $response = $this->patch($this->makeUrl($listing->id), ['title' => 'te']);
        $response->assertSessionHasErrors(['title']);
    }

    /**
     * Попытка обновления с коротким описанием
     */
    public function testTooShortDescription()
    {
        $user    = $this->createUser();
        $listing = $this->createListing(['user_id' => $user->id]);

        $this->signIn($user);
        $response = $this->patch($this->makeUrl($listing->id), ['description' => 'te']);
        $response->assertSessionHasErrors(['description']);
    }

    /**
     * Попытка обновления с нулевой ценой
     */
    public function testNullablePrice()
    {
        $user    = $this->createUser();
        $listing = $this->createListing(['user_id' => $user->id]);

        $this->signIn($user);
        $response = $this->patch($this->makeUrl($listing->id), ['price' => 0]);
        $response->assertSessionHasErrors(['price']);
    }

    /**
     * Попытка обновления с отрицательной ценой
     */
    public function testNegativePrice()
    {
        $user    = $this->createUser();
        $listing = $this->createListing(['user_id' => $user->id]);

        $this->signIn($user);
        $response = $this->patch($this->makeUrl($listing->id), ['price' => -1]);
        $response->assertSessionHasErrors(['price']);
    }

    /**
     * Успешное обновление
     */
    public function testSuccess()
    {
        $user    = $this->createUser();
        $listing = $this->createListing(['user_id' => $user->id]);

        /** @var Listing $listingDraft */
        $listingDraft = Listing::factory()->make();

        $this->signIn($user);

        $response = $this->patch($this->makeUrl($listing->id), $listingDraft->toArray());
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/listings');

        $response->assertSessionHas('alert.success', 'Listing updated');

        $this->assertDatabaseHas('listings', [
            'id'          => $listing->id,
            'title'       => $listingDraft->title,
            'description' => $listingDraft->description,
            'price'       => $listingDraft->price,
        ]);
    }

    /**
     * Формирование адреса обновления
     *
     * @param int $id ID предложения, для которого необходимо сформировать адрес обновления
     *
     * @return string
     */
    public function makeUrl(int $id): string
    {
        return '/listings/' . $id;
    }
}
