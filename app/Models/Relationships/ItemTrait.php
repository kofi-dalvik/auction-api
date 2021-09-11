<?php

namespace App\Models\Relationships;

use App\Models\User;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;

trait ItemTrait
{
    /**
     * This resource belongs to an item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
