<?php

namespace Plans;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Plan
 * @package Plan
 */
class Plan extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'payload',
        'description',
        'hidden',
        'preferential',
        'product_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'payload' => 'array',
    ];
}
