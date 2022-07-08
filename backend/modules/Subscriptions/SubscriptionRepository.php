<?php

namespace Subscriptions;

/**
 * Class SubscriptionRepository
 * @package Subscriptions
 */
class SubscriptionRepository
{
    /**
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Support\HigherOrderWhenProxy|\Illuminate\Support\Traits\Conditionable|mixed
     */
    public static function index(array $filters = [])
    {
        return Subscription::query()->when($filters['name'] ?? null, function ($query, $name) {
            return $query->where('name', 'like', "%$name%");
        });
    }
}
