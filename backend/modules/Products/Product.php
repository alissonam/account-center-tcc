<?php

namespace Products;

use Illuminate\Database\Eloquent\Model;
use Media\Media;
use Plans\Plan;

/**
 * Class Product
 * @package Products
 */
class Product extends Model
{

    const STATUS_ACTIVE           = 'active';
    const STATUS_INATIVE          = 'inative';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'status',
        'code',
        'action_url',
        'app_url',
        'api_token',
        'description',
    ];

    public function logo()
    {
        return $this->morphOne(Media::class, 'subject');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function plans()
    {
        return $this->hasMany(Plan::class);
    }
}
