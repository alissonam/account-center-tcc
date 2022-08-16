<?php

namespace Sugestions;

/**
 * Class SugestionRepository
 * @package Sugestions
 */
class SugestionRepository
{
    /**
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Support\HigherOrderWhenProxy|\Illuminate\Support\Traits\Conditionable|mixed
     */
    public static function index(array $filters = [])
    {
        return Sugestion::query()->when($filters['product_id'] ?? null, function ($query, $client) {
            return $query->where('product_id', $client);
        });
    }
}
