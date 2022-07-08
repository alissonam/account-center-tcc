<?php

namespace Subscriptions;

use App\Http\Controllers\Controller;

/**
 * Class SubscriptionController
 * @package Subscriptions
 */
class SubscriptionController extends Controller
{
    use SubscriptionResponse;

    /** @var SubscriptionService */
    private SubscriptionService $subscriptionService;

    /**
     * SubscriptionController constructor.
     * @param SubscriptionService $subscriptionService
     */
    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * @param SubscriptionRequest $request
     * @return mixed
     */
    public function index(SubscriptionRequest $request)
    {
        $result = $this->subscriptionService->index($request->validated());

        return $this->response($result['response'], $result['status']);
    }

    /**
     * @param SubscriptionRequest $request
     * @return mixed
     */
    public function store(SubscriptionRequest $request)
    {
        $result = $this->subscriptionService->store($request->validated());

        return $this->response($result['response'], $result['status']);
    }

    /**
     * @param Subscription $subscription
     * @return mixed
     */
    public function show(Subscription $subscription)
    {
        return $this->response($subscription->load(\request('with') ?? [])->toArray());
    }

    /**
     * @param SubscriptionRequest $request
     * @param Subscription $subscription
     * @return mixed
     */
    public function update(SubscriptionRequest $request, Subscription $subscription)
    {
        $result = $this->subscriptionService->update($subscription, $request->validated());

        return $this->response($result['response'], $result['status']);
    }

    /**
     * @param Subscription $subscription
     * @return mixed
     */
    public function destroy(Subscription $subscription)
    {
        $result = $this->subscriptionService->destroy($subscription);

        return $this->response($result['response'], $result['status']);
    }
}
