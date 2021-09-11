<?php

namespace App\Actions;

use Exception;
use App\Models\Item;
use App\Models\Bidding;

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
        $bidding = Bidding::create($data);

        $bidding->item()->update(['last_bid_id' => $bidding->id]);

        return $bidding;
    }
}
