<?php

namespace App\Models\Relationships;

use App\Models\User;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;

trait UserTrait
{
    /**
     * This resource belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
