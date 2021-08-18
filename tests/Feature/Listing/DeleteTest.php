<?php

declare(strict_types=1);

namespace Tests\Feature\Listing;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Попытка удаления гостем
     */
    public function testGuest()
    {
        $listing = $this->createListing();

        $response = $this->delete($this->makeUrl($listing->id));
        $response->assertRedirect(self::LOGIN_URL);
    }

    /**
     * Попытка удаления пользователем, который ещё не завершил регистрацию
     */
    public function testRegistrationNotCompleted()
    {
        $user    = $this->createUser([], true);
        $listing = $this->createListing(['user_id' => $user->id]);

        $this->signIn($user);
        $response = $this->delete($this->makeUrl($listing->id));
        $response->assertRedirect('/registration-step-two');
    }

    /**
     * Попытка удаления предложения другого пользователя
     */
    public function testAnotherUser()
    {
        $user    = $this->createUser();
        $listing = $this->createListing();

        $this->signIn($user);
        $response = $this->delete($this->makeUrl($listing->id));
        $response->assertForbidden();
    }

    /**
     * Успешное удаление
     */
    public function testSuccess()
    {
        $user    = $this->createUser();
        $listing = $this->createListing(['user_id' => $user->id]);

        $this->signIn($user);
        $response = $this->delete($this->makeUrl($listing->id));
        $response->assertRedirect('/listing');

        $response->assertSessionHas('alert.success', 'Listing deleted');

        $this->assertDatabaseMissing('listings', [
            'id' => $listing->id,
        ]);
    }

    /**
     * Формирование адреса удаления
     *
     * @param int $id ID предложения, для которого необходимо сформировать адрес удаления
     *
     * @return string
     */
    public function makeUrl(int $id): string
    {
        return '/listing/' . $id;
    }
}
