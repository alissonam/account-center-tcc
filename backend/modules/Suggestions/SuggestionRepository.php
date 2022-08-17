<?php

namespace Suggestions;

/**
 * Class SuggestionRepository
 * @package Suggestions
 */
class SuggestionRepository
{
    /**
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Support\HigherOrderWhenProxy|\Illuminate\Support\Traits\Conditionable|mixed
     */
    public static function index(array $filters = [])
    {
        return Suggestion::query()->when($filters['product_id'] ?? null, function ($query, $client) {
            return $query->where('product_id', $client);
        });
    }
}
