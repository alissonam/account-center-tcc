<?php

namespace VindiGetway;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use Subscriptions\Subscription;
use Users\User;

class testeController extends Controller
{

    public function __construct()
    {
    }

    public function teste(
        // User $user
        Subscription $subscription
        )
    {
        // // dd($subscription);
        // $customerService = new CustomerService($user);
        // $customerVindi = $customerService->storecustomer();
        // //$customerVindi->vindi_id = 1515058;
        // // $customer = $customerService->store();
        // dd("teste", $customerVindi);

        $subscription->load(\request('with') ?? []);
        //dd($subscription);
        $subscriptionService = new SubscriptionService($subscription);
        //$subscriptionVindi = $subscriptionService->storeSubscription();
        $subscription->vindi_id = 680929;
        $subscriptionVindi = $subscriptionService->cancelSubscription();
        dd("teste", $subscriptionVindi);
    }
}
