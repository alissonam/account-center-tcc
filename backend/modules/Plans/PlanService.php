<?php

namespace Plans;

use App\Http\Services\Service;

/**
 * Class PlanService
 * @package Plans
 */
class PlanService extends Service
{
    /**
     * @param array $filters
     * @return array
     */
    public function index(array $filters)
    {
        $plansQuery = PlanRepository::index($filters);

        return self::buildReturn(
            $plansQuery
                ->with(\request()->with ?? [])
                ->paginate(\request()->perPage)
        );
    }

    /**
     * @param array $data
     * @return array
     */
    public function store(array $data)
    {
        $plan = Plan::create($data);

        if ($data['preferential'] === true) {
            PlanRepository::plansOfProduct($plan->product_id, $plan->id)->update(['preferential' => false]);
        }

        if ($data['default'] === true) {
            PlanRepository::plansOfProduct($plan->product_id, $plan->id)->update(['default' => false]);
        }

        return self::buildReturn($plan);
    }

    /**
     * @param Plan $plan
     * @param array $data
     * @return array
     */
    public function update(Plan $plan, array $data)
    {
        $plan->update($data);

        if ($data['preferential'] === true) {
            PlanRepository::plansOfProduct($plan->product_id, $plan->id)->update(['preferential' => false]);
        }

        if ($data['default'] === true) {
            PlanRepository::plansOfProduct($plan->product_id, $plan->id)->update(['default' => false]);
        }

        return self::buildReturn($plan);
    }

    /**
     * @param Plan $plan
     * @return array
     */
    public function destroy(Plan $plan)
    {
        $plan->delete();

        return self::buildReturn();
    }
}
