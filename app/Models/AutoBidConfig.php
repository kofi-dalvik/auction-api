<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Relationships\UserTrait;

class AutoBidConfig extends Model
{
    use HasFactory, UserTrait;
}
