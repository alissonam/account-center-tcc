<?php

namespace Subscriptions;

use Illuminate\Database\Eloquent\Model;
use Plans\Plan;
use Products\Product;
use Users\User;

/**
 * Class Subscription
 * @package Subscription
 */
class Subscription extends Model
{
    const STATUS_ACTIVE   = 'active';
    const STATUS_AWAITING = 'awaiting';
    const STATUS_INACTIVE = 'inactive';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'plan_id',
        'product_id',
        'user_id',
        'vindi_id',
        'status',
        'finished_in'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
