<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use \Illuminate\Database\Eloquent\Relations\HasMany;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;
use \Illuminate\Database\Eloquent\Builder;

class Item extends Model
{
    use HasFactory;

    /**
     * Item has many biddings
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function biddings(): HasMany
    {
        return $this->hasMany(Bidding::class, 'item_id');
    }

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
     * Scope to filter active items
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsActive(Builder $query): Builder
    {
        return $query->where('closing_date', '>', now());
    }
}
