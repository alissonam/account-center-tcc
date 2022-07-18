<?php

namespace Products;

use Illuminate\Database\Eloquent\Model;
use Media\Media;

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
        'vindi_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status'    => 'boolean',
    ];

    public function logo()
    {
        return $this->morphOne(Media::class, 'subject');
    }
}
