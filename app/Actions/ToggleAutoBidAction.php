<?php

namespace App\Actions;

use Exception;
use App\Models\AutoBidActivation;

class ToggleAutoBidAction
{
    /**
     * Toggle auto bidding
     *
     * @param array $array
     * @return void
     */
    public function execute(array $data): void
    {
        $toggler = (int) $data['auto_bidding'];

        $activation = AutoBidActivation::where('user_id', $data['user_id'])
                        ->where('item_id', $data['item_id'])
                        ->first();

        if ($toggler === 0 && $activation) {
            $activation->delete();
        } elseif ($toggler === 1 && !$activation) {
            AutoBidActivation::create([
                'user_id' => $data['user_id'],
                'item_id' => $data['item_id']
            ]);
        }
    }
}
