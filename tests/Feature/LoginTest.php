<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function testShouldLogUserInWhenValidCredentialsAreProvided()
    {
        $data = ['username' => 'user1'];
        $response = $this->postJson('/api/login', $data)
            ->assertStatus(Response::HTTP_OK);

        $this->assertTrue(isset($response['user']['username']) && $response['user']['username'] === $data['username']);
        $this->assertTrue(isset($response['access_token']));
    }

    public function testLoginShouldFailWhenIncorrectCredentialsAreProvided()
    {
        $response = $this->postJson('/api/login', ['username' => 'someuser'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertTrue(isset($response['errors']['username']));
    }
}
