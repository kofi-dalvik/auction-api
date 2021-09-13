<?php

namespace App\Actions;

use Arr;
use App\Models\Item;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Queries\KeywordSearchTrait;

class ListItemAction
{
    use KeywordSearchTrait;

    protected $sort_directions = ['asc', 'desc'];

    /**
     * Get items listings
     *
     * @param array $params
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function execute(array $params): LengthAwarePaginator
    {
        $sort_price  = strtolower(Arr::get($params, 'sort_price', 'asc'));
        $sort_by_price = in_array($sort_price, $this->sort_directions);
        $keyword = Arr::get($params, 'keyword', null);

        $query = Item::with('images', 'latestBid.user')
                    ->isActive()
                    ->when($sort_by_price, fn ($query) => $query->orderBy('price', $sort_price))
                    ->when($keyword, fn ($query) => $this->keywordSearch($query, $keyword, ['name', 'description']))
                    ->latest('created_at');

        return $query->paginate(10);
    }
}
