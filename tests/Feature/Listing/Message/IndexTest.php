<?php

declare(strict_types=1);

namespace Tests\Feature\Listing\Message;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    private const URL = '/listings';

    /**
     * Ссылка на отправку сообщения не должна отображаться для собственных предложений
     */
    public function testSelfListing()
    {
        $user = $this->createUser();
        $this->createListing(['user_id' => $user]);

        $this->signIn($user);
        $response = $this->get(self::URL);
        $response->assertOk();

        $response->assertDontSee('Send message');
    }

    /**
     * Ссылка на отправку сообщения должна отображаться для собственных предложений
     */
    public function testSuccess()
    {
        $this->createListing();

        $this->signIn();
        $response = $this->get(self::URL);
        $response->assertOk();

        $response->assertSee('Send message');
    }
}
