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
        'visible',
        'preferential',
        'default',
        'product_id',
        'vindi_product_id',
        'vindi_plan_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'payload'      => 'array',
        'visible'      => 'boolean',
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
