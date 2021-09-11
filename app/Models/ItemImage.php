<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Relationships\ItemTrait;

class ItemImage extends Model
{
    use HasFactory, ItemTrait;
}
