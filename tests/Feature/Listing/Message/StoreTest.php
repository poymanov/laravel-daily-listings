<?php

declare(strict_types=1);

namespace Tests\Feature\Listing\Message;

use App\Notifications\Listing\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Попытка посещения гостем
     */
    public function testGuest()
    {
        $listing = $this->createListing();

        $response = $this->post($this->makeUrl($listing->id));
        $response->assertRedirect(self::LOGIN_URL);
    }

    /**
     * Попытка отправки пользователем, который ещё не завершил регистрацию
     */
    public function testRegistrationNotCompleted()
    {
        $user    = $this->createUser([], true);
        $listing = $this->createListing();

        $this->signIn($user);
        $response = $this->post($this->makeUrl($listing->id));
        $response->assertRedirect('/registration-step-two');
    }

    /**
     * Попытка открытия страницы отправки сообщения для собственного предложения
     */
    public function testSelfListing()
    {
        $user    = $this->createUser();
        $listing = $this->createListing(['user_id' => $user->id]);

        $this->signIn($user);
        $response = $this->post($this->makeUrl($listing->id), ['text' => 'test']);
        $response->assertForbidden();
    }

    /**
     * Попытка отправки пустого сообщения
     */
    public function testEmpty()
    {
        $listing = $this->createListing();
        $this->signIn($this->createUser());

        $response = $this->post($this->makeUrl($listing->id));
        $response->assertSessionHasErrors('text');
    }

    /**
     * Попытка отправки слишком короткого сообщения
     */
    public function testTooShort()
    {
        $listing = $this->createListing();
        $this->signIn($this->createUser());

        $response = $this->post($this->makeUrl($listing->id), ['text' => '12']);
        $response->assertSessionHasErrors('text');
    }

    /**
     * Успешная отправка сообщения
     */
    public function testSuccess()
    {
        Notification::fake();

        $user    = $this->createUser();
        $listing = $this->createListing();
        $this->signIn($user);

        $text = $this->faker->text;

        $response = $this->post($this->makeUrl($listing->id), ['text' => $text]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/listings');
        $response->assertSessionHas('alert.success', 'Message sent successfully');

        $this->assertDatabaseHas('messages', [
            'user_id'    => $user->id,
            'listing_id' => $listing->id,
            'text'       => $text,
        ]);

        Notification::hasSent($listing->user, Message::class);
    }

    /**
     * Попытка множественной отправки сообщений
     */
    public function testThrottle()
    {
        Notification::fake();

        $listing = $this->createListing();
        $this->signIn($this->createUser());

        $text = $this->faker->text;

        $this->post($this->makeUrl($listing->id), ['text' => $text]);

        $response = $this->post($this->makeUrl($listing->id), ['text' => $text]);
        $response->assertStatus(Response::HTTP_TOO_MANY_REQUESTS);

        Notification::assertTimesSent(1, Message::class);
    }

    /**
     * Формирование адреса отправки сообщения
     *
     * @param int $id ID предложения, для которого необходимо сформировать адрес отправки сообщения
     *
     * @return string
     */
    public function makeUrl(int $id): string
    {
        return '/listings/' . $id . '/messages';
    }
}
