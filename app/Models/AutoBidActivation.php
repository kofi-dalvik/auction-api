<?php

namespace App\Models;

use App\Models\Relationships\UserTrait;
use App\Models\Relationships\ItemTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AutoBidActivation extends Model
{
    use HasFactory, UserTrait, ItemTrait;
}
