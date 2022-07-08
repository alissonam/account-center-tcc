<?php

namespace Plans;

use App\Http\Controllers\Controller;

/**
 * Class PlanController
 * @package Plans
 */
class PlanController extends Controller
{
    use PlanResponse;

    /** @var PlanService */
    private PlanService $planService;

    /**
     * PlanController constructor.
     * @param PlanService $planService
     */
    public function __construct(PlanService $planService)
    {
        $this->planService = $planService;
    }

    /**
     * @param PlanRequest $request
     * @return mixed
     */
    public function index(PlanRequest $request)
    {
        $result = $this->planService->index($request->validated());

        return $this->response($result['response'], $result['status']);
    }

    /**
     * @param PlanRequest $request
     * @return mixed
     */
    public function store(PlanRequest $request)
    {
        $result = $this->planService->store($request->validated());

        return $this->response($result['response'], $result['status']);
    }

    /**
     * @param Plan $plan
     * @return mixed
     */
    public function show(Plan $plan)
    {
        return $this->response($plan->load(\request('with') ?? [])->toArray());
    }

    /**
     * @param PlanRequest $request
     * @param Plan $plan
     * @return mixed
     */
    public function update(PlanRequest $request, Plan $plan)
    {
        $result = $this->planService->update($plan, $request->validated());

        return $this->response($result['response'], $result['status']);
    }

    /**
     * @param Plan $plan
     * @return mixed
     */
    public function destroy(Plan $plan)
    {
        $result = $this->planService->destroy($plan);

        return $this->response($result['response'], $result['status']);
    }
}
