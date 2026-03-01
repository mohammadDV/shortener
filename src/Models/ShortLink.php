<?php

declare(strict_types=1);

namespace Mohammaddv\Shortener\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model for short links
 */
class ShortLink extends Model
{
    /**
     * The attributes that are fillable
     *
     * @var array<string, string>
     */
    protected $fillable = [
        'original_url',
        'code',
        'expires_at'
    ];

    /**
     * The attributes that should be cast
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean'
    ];
}