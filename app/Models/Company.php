<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    // Company users
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // Company short URLs
    public function shortUrls(): HasMany
    {
        return $this->hasMany(ShortUrl::class);
    }

    // Company invitations
    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

}
