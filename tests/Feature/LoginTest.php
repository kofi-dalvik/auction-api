<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function testShouldLogUserInWhenValidCredentialsAreProvided()
    {
        $username = 'user1';
        $user = User::factory(['username' => $username])->create();

        $data = ['username' => $username];
        $response = $this->postJson('/api/login', $data)
            ->assertStatus(Response::HTTP_OK);

        $this->assertTrue(isset($response['user']['username']) && $response['user']['username'] === $data['username']);
        $this->assertTrue(isset($response['token']));
    }

    public function testLoginShouldFailWhenIncorrectCredentialsAreProvided()
    {
        $response = $this->postJson('/api/login', ['username' => 'someuser'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertTrue(isset($response['errors']['username']));
    }
}
