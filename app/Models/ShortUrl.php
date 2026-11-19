<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShortUrl extends Model
{
    protected $fillable = [
        'company_id',
        'user_id',
        'original_url',
        'short_code',
    ];

    // URL belongs to a company
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    // URL created by a user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
