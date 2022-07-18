<?php

namespace Plans;

use Illuminate\Database\Eloquent\Model;
use Products\Product;

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
        'default',
        'product_id',
        'vindi_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'payload'      => 'array',
        'hidden'       => 'boolean',
        'preferential' => 'boolean',
        'default'      => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
