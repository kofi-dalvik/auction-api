<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;

use App\Models\Item;
use App\Models\User;
use App\Events\BidCreated;
use App\Models\AutoBidActivation;
use App\Listeners\ProcessAutoBidding;

class BiddingsTest extends TestCase
{
    protected $actor;

    protected $item;

    public function  setUp(): void
    {
        parent::setUp();

        $this->actor = User::find(1);

        $this->item = Item::factory()->create(['name'=> 'test', 'description' => 'test item']);
    }

    private function getBidPayload()
    {
        return [
            'item_id' => $this->item->id,
            'amount' => $this->item->price + 1,
        ];
    }

    private function storeBidding($payload = null)
    {
        if (!$payload) {
            $payload = $this->getBidPayload();
        }

        return $this->actingAs($this->actor)->postJson('/api/biddings', $payload);
    }

    public function testShouldMakeBidsWhenValidDataIsProvided()
    {
        $response = $this->storeBidding()
                        ->assertStatus(Response::HTTP_CREATED)
                        ->assertJson([
                            'item_id' => $this->item->id,
                            'user_id' => $this->actor->id,
                            'amount' => $this->item->price + 1
                        ]);
    }

    public function testShouldOnlyBidMoreThanCurrentBid()
    {
        $payload = $this->getBidPayload();
        $payload['amount'] = $this->item->price;

        $response = $this->storeBidding($payload)
                        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testShouldNotBidWhenUserIsHighestBidder()
    {
        //create bidding for this user
        $this->item->biddings()->create([
            'user_id' => $this->actor->id,
            'amount' => $this->item->price + 1
        ]);

        $response = $this->storeBidding()
                        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testShouldBeAbleToActivateAutoBidding()
    {
        $payload = $this->getBidPayload();
        $payload['auto_bidding'] = 1;

        $response = $this->actingAs($this->actor)
                        ->postJson('/api/biddings/auto_bid', $payload)
                        ->assertStatus(Response::HTTP_OK);

        $this->assertTrue(
            AutoBidActivation::where('user_id', $this->actor->id)
            ->where('item_id', $this->item->id)
            ->exists()
        );
    }

    public function testShouldDispatchBidCreatedEvent()
    {
        Event::fake();

        $response = $this->storeBidding()
                        ->assertStatus(Response::HTTP_CREATED);

        Event::assertDispatched(fn (BidCreated $e) => $response['id'] === $e->bidding->id);
    }

    public function testShouldProcessAutobidsBotsWhenNewBidIsCreated()
    {
        Event::fake();

        $response = $this->storeBidding()
                        ->assertStatus(Response::HTTP_CREATED);

        Event::assertListening(
            BidCreated::class,
            ProcessAutoBidding::class
        );
    }
}
