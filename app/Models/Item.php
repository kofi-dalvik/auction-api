<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use \Illuminate\Database\Eloquent\Relations\HasMany;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;
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
     * Item has many autobid activations
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function autoBidActivations(): HasMany
    {
        return $this->hasMany(AutoBidActivation::class, 'item_id');
    }

    /**
     * Item belongs to the lastest bidding
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function latestBid(): BelongsTo
    {
        return $this->belongsTo(Bidding::class, 'latest_bid_id');
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
