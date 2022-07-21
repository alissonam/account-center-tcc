<?php
namespace VindiGatway;

use Subscriptions\Subscription;
use Vindi\Vindi;

class SubscriptionVindiService extends ApiVindiService {

    private $accountCenterSubscription = null;

    public function __construct($accountCenterSubscription = null)
    {
        parent::__construct("Subscription");
        $this->accountCenterSubscription = $accountCenterSubscription;
    }

    public function getSubscriptionByPaymentGatwayId($idSubscription)
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
        $subscription = new Subscription(
            [
            'id' => $subscription->code,
            'plan_id' => $subscription->plan->id,
            'user_id' => $subscription->customer->id,
            'product_id' => $subscription->product_items[0]->product_id,
            'vindi_id' => $subscription->id
        ]);
        return $subscription;
    }

    private function accountCenterToSubscription() {
        $subscription = [
            'id'                   => $this->accountCenterSubscription->vindi_id ?? null,
            'customer_id'          => $this->accountCenterSubscription->user->vindi_id,
            'plan_id'              => $this->accountCenterSubscription->plan->vindi_id,
            "payment_method_code"  => "credit_card", // alterar para pegar da request;
            'product_items'        => ['product_id' => $this->accountCenterSubscription->product->vindi_id],
            'code'                 => $this->accountCenterSubscription->id,
        ];
        return array_filter($subscription);
    }
}
