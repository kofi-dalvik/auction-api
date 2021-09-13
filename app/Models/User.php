<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use \Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['username', 'password'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $appends = ['image_url'];

    /**
     * User has one  autobiding configurations
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function autoBidConfig(): HasOne
    {
        return $this->hasOne(AutoBidConfig::class, 'user_id');
    }

    public function getImageUrlAttribute()
    {
        if (in_array($this->id, [1, 2])) {
            $path = "images/user-{$this->id}.jpg";
        } else {
            $path = "images/user.jpg";
        }

        return asset($path);
    }
}
