<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use \Illuminate\Database\Eloquent\Relations\HasMany;
use \Illuminate\Database\QueryBuilder;

class Item extends Model
{
    use HasFactory;

    /**
     * Item has many images
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(ItemImage::class, 'item_id');
    }

    /**
     * Scope for active items
     *
     * @param \Illuminate\Database\QueryBuilder $query
     * @return \Illuminate\Database\QueryBuilder
     */
    public function scopeIsActive(QueryBuilder $query): QueryBuilder
    {
        return $query->where('closing_date', '>', now());
    }
}
