<?php

namespace App\Actions;

use Exception;
use App\Models\User;
use App\Models\Item;
use App\Models\Bidding;
use App\Models\AutoBidConfig;
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
        $settings = User::find($data['user_id'])->autoBidConfig;

        $this->enforceConstraints($data, $settings);

        $bidding = $this->createBidding($data, $settings);

        $this->toggleAutoBidding($bidding, (int) $data['auto_bidding']);

        return $bidding;
    }

    /**
     * Enforces constraints on biddings
     *
     * @param array $data
     * @param mixed $settings
     * @return void
     */
    private function enforceConstraints(array $data, $settings): void
    {
        if ($settings && $settings->max_bid_amount < 1) {
            throw new Exception('Not enough funds for auto bidding');
        }

        $latest_bidding = Bidding::where('item_id', $data['item_id'])->latest('created_at')->first();

        if ($latest_bidding) {
            if ($latest_bidding->user_id == $data['user_id']) {
                throw new Exception('You are currently the highest bidder');
            }

            if ($latest_bidding->amount >= $data['amount']) {
                $amount = $latest_bidding->amount;
                throw new Exception('You can only bid more than the current bid amount $' . $amount);
            }
        } else {
            $item = Item::find($data['item_id']);

            if ($item->price >= $data['amount']) {
                throw new Exception('You can only bid more than the item price $' . $item->price);
            }
        }
    }

    /**
     * Creates bid
     *
     * @param array $data
     * @param mixed $settings
     * @return \App\Models\Bidding
     */
    private function createBidding(array $data, $settings): Bidding
    {
        $bidding = Bidding::create([
            'user_id' => $data['user_id'],
            'item_id' => $data['item_id'],
            'amount' => $data['amount'],
        ]);

        $bidding->item()->update(['latest_bid_id' => $bidding->id]);

        if ($settings) {
            $settings->max_bid_amount -= 1;
            $settings->save();
        }

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
