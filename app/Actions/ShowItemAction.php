<?php

namespace App\Actions;

use Exception;
use App\Models\Item;
use Illuminate\Pagination\Paginator;

class ShowItemAction
{
    /**
     * Get given item details
     *
     * @param mixed $id
     * @return \App\Models\Item
     */
    public function execute(mixed $id): Item
    {
        $item = Item::with('images')->find($id);

        if (!$item) throw new Exception('Item not found');

        return $item;
    }
}