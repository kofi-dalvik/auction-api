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
    use RefreshDatabase;

    protected $actor;

    public function  setUp(): void
    {
        parent::setUp();

        $this->actor = User::factory()->create();
    }

    public function testShouldListPaginatedItems()
    {
        $response = $this->actingAs($this->actor)->getJson('/api/items')
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(['current_page' => 1, 'per_page' => 10]);

        $this->assertTrue(isset($response['data']) && is_array($response['data']));
    }

    public function testShouldSearchItemsByKeyword()
    {
        $item = Item::factory()->create([
            'name'=> 'This is a sampleitem',
            'description' => 'sample item'
        ]);

        $response = $this->actingAs($this->actor)
            ->getJson('/api/items', ['keyword' => 'sampleitem'])
            ->assertStatus(Response::HTTP_OK);

        $this->assertTrue(isset($response['data']) && is_array($response['data']) && count($response['data']));
    }

    public function testShouldShowItemById()
    {
        $item = Item::factory()->create(['name'=> 'name', 'description' => 'description']);

        $response = $this->actingAs($this->actor)
            ->getJson("/api/items/{$item->id}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(['id' => $item->id]);
    }

    public function testShouldReturn_404ResponseForUnknownItem()
    {
        $id = 'id';

        $response = $this->actingAs($this->actor)
            ->getJson("/api/items/$id")
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
