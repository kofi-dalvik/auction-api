<?php

namespace App\Actions;

use App\Models\Item;
use Illuminate\Pagination\Paginator;
use App\Querries\KeywordSearchTrait;

class ListItemAction
{
    use KeywordSearchTrait;

    protected $sort_directions = ['asc', 'desc'];

    /**
     * Get items listings
     *
     * @param array $params
     * @return \Illuminate\Pagination\Paginator
     */
    public function execute(array $params): Paginator
    {
        $sort_price  = strtolower($params['sort_price']);
        $sort_by_price = in_array($sort_price, $this->sort_directions);

        $query = Item::with('images')
                    ->when($sort_by_price, fn ($query) => $query->orderBy('price', $sort_price))
                    ->when($keyword, fn () => $this->keywordSearch($query, $keyword, ['name', 'description']))
                    ->latest('created_at')
                    ->paginate(10);
    }
}
