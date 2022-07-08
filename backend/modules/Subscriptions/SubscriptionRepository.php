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
        return Subscription::query();
    }
}
