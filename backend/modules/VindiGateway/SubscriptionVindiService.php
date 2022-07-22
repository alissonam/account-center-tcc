<?php

namespace VindiGateway;

use Plans\PlanRepository;
use Subscriptions\Subscription;

class SubscriptionVindiService extends ApiVindiService {

    private $accountCenterSubscription = null;

    public function __construct($accountCenterSubscription = null)
    {
        parent::__construct("Subscription");
        $this->accountCenterSubscription = $accountCenterSubscription;
    }

    public function getSubscriptionByPaymentGatewayId($idSubscription)
    {
        $subscription = $this->vindiService->get($idSubscription);
        return $this->subscriptionToAccountCenterSubscription($subscription);
    }

    public function storeSubscription()
    {
        $subscription = $this->accountCenterToSubscription();
        $storedVindiSubscription = $this->vindiService->create($subscription);
        $this->accountCenterSubscription->vindi_id = $storedVindiSubscription->id;
        return $this->accountCenterSubscription;
    }

    public function cancelSubscription()
    {
        $this->vindiService->delete($this->accountCenterSubscription->vindi_id);
    }

    private function subscriptionToAccountCenterSubscription($subscription)
    {
        $plan = PlanRepository::planFromVindi($subscription->plan->id, $subscription->product_items[0]->product_id)
            ->first();

        $subscription = new Subscription(
            [
            'id' => $subscription->code,
            'plan_id' => $plan->id ?? null,
            'user_id' => $subscription->customer->id,
            'product_id' => $plan->product_id ?? null,
            'vindi_id' => $subscription->id
        ]);
        return $subscription;
    }

    private function accountCenterToSubscription() {
        $plan = $this->accountCenterSubscription->plan;
        $subscription = [
            'id'                   => $this->accountCenterSubscription->vindi_id,
            'code'                 => $this->accountCenterSubscription->id,
            'customer_id'          => $this->accountCenterSubscription->user->vindi_id,
            'plan_id'              => $plan->vindi_plan_id,
            'product_items'        => ['product_id' => $plan->vindi_product_id],
            "payment_method_code"  => "credit_card", // alterar para pegar da request;
        ];
        return array_filter($subscription);
    }
}
