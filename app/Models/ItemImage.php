<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Relationships\ItemTrait;

class ItemImage extends Model
{
    use HasFactory, ItemTrait;

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return asset('images/items/' . $this->url);
    }
}
