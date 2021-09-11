<?php

namespace App\Listeners;

use App\Events\BidCreated;
use App\Actions\MakeBidAction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Models\Item;
use App\Models\AutoBidActivation;

class ProcessAutoBidding implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BidCreated  $event
     * @return void
     */
    public function handle(BidCreated $event)
    {
        return;

        $activations = $event->bidding->item
                        ->autoBidActivations()
                        ->orderBy('created_at', 'asc')
                        ->get();

        foreach ($activations as $activation) {
            $this->autoBid(latest_bid, $activation);
        }
    }

    private function autoBid(Bidding $last_bid, AutoBidActivation $activation)
    {
        if ($last_bid->user_id == $activation->user_id) return;

        $bid_amount = $last_bid->amount + 1;

        if ($activation->user->autoBidConfig->max_bid_amount > $bid_amount) {
            return;
        }

        $data = [
            'is_auto_bid' => true,
            'item_id' => $last_bid->item_id,
            'user_id' => $activation->user_id,
            'amount' => $bid_amount
        ];

        $bidder = new  MakeBidAction();
        $bidder->execute();
        BidCreated::dispatch($bidding);
    }
}
