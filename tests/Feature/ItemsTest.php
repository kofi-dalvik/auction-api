<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Item;
use Tests\TestCase;

class ItemsTest extends TestCase
{
    public function testShouldListPaginatedItems()
    {
        $user = User::find(1);

        $response = $this->actingAs($user)->getJson('/api/items')
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(['current_page' => 1, 'per_page' => 10]);

        $this->assertTrue(isset($response['data']) && is_array($response['data']));
    }

    public function testShouldSearchItemsByKeyword()
    {
        $user = User::find(1);

        $item = Item::factory()->create([
            'name'=> 'This is a sampleitem',
            'description' => 'sample item'
        ]);

        $response = $this->actingAs($user)
            ->getJson('/api/items', ['keyword' => 'sampleitem'])
            ->assertStatus(Response::HTTP_OK);

        $this->assertTrue(isset($response['data']) && is_array($response['data']) && count($response['data']));
    }
}
