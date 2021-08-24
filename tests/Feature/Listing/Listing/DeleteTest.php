<?php

declare(strict_types=1);

namespace Tests\Feature\Listing\Listing;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
        $response->assertRedirect('/listings');

        $response->assertSessionHas('alert.success', 'Listing deleted');

        $this->assertDatabaseMissing('listings', [
            'id' => $listing->id,
        ]);
    }

    /**
     * Успешное удаление
     */
    public function testSuccessWithImages()
    {
        Storage::fake('public');

        $user    = $this->createUser();
        $listing = $this->createListing(['user_id' => $user->id]);
        $listing->addMedia(UploadedFile::fake()->image('photo1.jpg'))->toMediaCollection('listings');

        $this->signIn($user);
        $response = $this->delete($this->makeUrl($listing->id));
        $response->assertRedirect('/listings');

        $response->assertSessionHas('alert.success', 'Listing deleted');

        $this->assertDatabaseMissing('listings', [
            'id' => $listing->id,
        ]);

        $this->assertDatabaseCount('media', 0);
        $this->assertCount(0, Storage::disk('public')->allFiles());
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
        return '/listings/' . $id;
    }
}
