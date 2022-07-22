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
        })->when($filters['status'] ?? null, function ($query, $status) {
                if (is_array($status)) {
                    $query->whereIn('status', $status);
                } else {
                    $query->where('status', $status);
                }
            });
    }

    /**
     * @param $userId
     * @param $productId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function activeSubscription($userId, $productId)
    {
        return Subscription::query()->where('user_id', $userId)
            ->where('status', '=',  Subscription::STATUS_ACTIVE)
            ->where('product_id', $productId)
            ->orderBy('id', 'desc');
    }

    /**
     * this function get subscription by vindi_id
     * @param int $vindi_id
     */
    public static function getByVindiId($vindi_id)
    {
        return Subscription::where("vindi_id", $vindi_id);
    }

    /**
     * this function get subscription by vindi_id
     * @param int $user_id
     * @param int $product_id
     * @param string $status
     */
    public static function getAllSubscriptionInProductOfUser($user_id, $product_id, $status)
    {
        return Subscription::where("user_id", $user_id)
            ->where("product_id", $product_id)
            ->where("status", $status);
    }
}
