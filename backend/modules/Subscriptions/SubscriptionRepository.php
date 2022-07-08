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
        return Subscription::query()->when($filters['plan_id'] ?? null, function ($query, $id) {
            return $query->where('plan_id', $id);
        })->when($filters['product_id'] ?? null, function ($query, $id) {
            return $query->where('product_id', $id);
        })->when($filters['user_id'] ?? null, function ($query, $id) {
            return $query->where('user_id', $id);
        });
    }
}
