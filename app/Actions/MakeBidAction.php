<?php

namespace App\Actions;

use Exception;
use App\Models\Bidding;
use App\Models\AutoBidActivation;

class MakeBidAction
{
    /**
     * Make a bid
     *
     * @param array $array
     * @return \App\Models\Bidding
     */
    public function execute(array $data): Bidding
    {
        $latest_bidding = Bidding::where('item_id', $data['item_id'])
                            ->latest('created_at')
                            ->first();

        if ($latest_bidding->user_id == $data['user_id']) {
            throw new Exception('You are currently the highest bidder');
        }

        $bidding = $this->createBidding($data);

        $this->toggleAutoBidding($bidding, (int) $data['auto_bidding']);

        return $bidding;
    }

    /**
     * Creates bid
     *
     * @param array $data
     * @return \App\Models\Bidding
     */
    private function createBidding(array $data): Bidding
    {
        $bidding = Bidding::create([
            'user_id' => $data['user_id'],
            'item_id' => $data['item_id'],
            'amount' => $data['amount'],
        ]);

        $bidding->item()->update(['last_bid_id' => $bidding->id]);

        return $bidding;
    }

    /**
     * Toggle autobid activation
     *
     * @param \App\Models\Bidding $bidding
     * @param int $toggler
     * @return void
     */
    private function toggleAutoBidding(Bidding $bidding, int $toggler): void
    {
        $activation = AutoBidActivation::where('user_id', $bidding->user_id)
                        ->where('item_id', $bidding->item_id)
                        ->first();

        if ($toggler === 0 && $activation) {
            $activation->delete();
        } elseif ($toggler === 1 && !$activation) {
            AutoBidActivation::create([
                'user_id' => $bidding->user_id,
                'item_id' => $bidding->item_id
            ]);
        }
    }
}
