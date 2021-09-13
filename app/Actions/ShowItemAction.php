<?php

namespace App\Actions;

use Exception;
use App\Models\Item;

class ShowItemAction
{
    /**
     * Get given item details
     *
     * @param int $id
     * @return \App\Models\Item
     */
    public function execute(int $id): Item
    {
        $item = Item::with([
            'images',
            'biddings.user',
            'latestBid.user',
            'biddings' => fn ($query) => $query->orderBy('amount', 'desc'),
            'autoBidActivations' => fn ($query) => $query->where('user_id', auth()->user()->id)
        ])->find($id);

        if (!$item) throw new Exception('Item not found');

        return $item;
    }
}
