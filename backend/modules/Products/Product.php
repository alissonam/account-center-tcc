<?php

namespace Products;

use Illuminate\Database\Eloquent\Model;

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
}
