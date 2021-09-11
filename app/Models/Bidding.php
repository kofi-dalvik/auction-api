<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Relationships\ItemTrait;
use App\Models\Relationships\UserTrait;

class Bidding extends Model
{
    use HasFactory, ItemTrait, UserTrait;
}
