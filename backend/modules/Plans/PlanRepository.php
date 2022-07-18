<?php

namespace Plans;

/**
 * Class PlanRepository
 * @package Plans
 */
class PlanRepository
{
    /**
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Support\HigherOrderWhenProxy|\Illuminate\Support\Traits\Conditionable|mixed
     */
    public static function index(array $filters = [])
    {
        return Plan::query()->when($filters['name'] ?? null, function ($query, $name) {
            return $query->where('name', 'like', "%$name%");
        })->when($filters['product_id'] ?? null, function ($query, $client) {
            return $query->where('product_id', $client);
        });
    }

    /**
     * @param $productId
     * @param $exceptPlanId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function plansOfProduct($productId, $exceptPlanId)
    {
        return Plan::query()->where('product_id', $productId)
            ->where('id', '!=',  $exceptPlanId);
    }

}
