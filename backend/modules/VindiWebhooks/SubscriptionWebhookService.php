<?php

namespace VindiWebhooks;

use App\Http\Services\Service;
use Subscriptions\SubscriptionService;
use Symfony\Contracts\Service\Attribute\SubscribedService;

/**
 * Class SubscriptionWebhookService
 * @package VindiWebhooks
 */
class SubscriptionWebhookService extends Service
{
    /**
     * this method execut list of function when subscription is paid
     * @param array $billData
     */
    public static function payedSubscription ($billData) {
        $vindiCustomer = self::getCustomerOfBill($billData);
        $vindiSubscription = self::getSubscriptionOfBill($billData);

        $subscription = SubscriptionService::getByVindiId($vindiSubscription["id"]);
        SubscriptionService::deactiveAllActiveSubscriptionInProductOfUser($subscription->user_id, $subscription->product_id);
        SubscriptionService::activeSubscription($subscription);

        dd($subscription);
        dd($vindiSubscription);
    }

    /**
     * this method extract subscription data of bill
     * @param array $billData
     * @return array
     */
    private static function getSubscriptionOfBill($billData) {
        return $billData["bill"]["subscription"];
    }

    /**
     * this method extract customer of bill
     * @param array $billData
     * @return array
     */
    private static function getCustomerOfBill($billData) {
        return $billData["bill"]["customer"];
    }
}
