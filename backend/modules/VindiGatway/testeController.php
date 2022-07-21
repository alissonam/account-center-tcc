<?php

namespace VindiGatway;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use PaymentGatway\PaymentGatway;
use Subscriptions\Subscription;
use Users\User;

class testeController extends Controller
{

    public function __construct()
    {
    }

    public function teste(
        //  User $user
        Subscription $subscription
        )
    {
        // // dd($user);
        // $customerService = PaymentGatway::Customer($user);
        // // $customerVindi = $customerService->storecustomer();
        // $user->vindi_id = 1517640;
        // //$user->email = "william.nahirnei@gmail.com";
        // //$customer = $customerService->updateCustomer();
        // // $customer = $customerService->archiveCustomer();
        // $customer = $customerService->unarchiveCustomer();
        // dd("teste", $customer);

        $subscription->load(\request('with') ?? []);
        //dd($subscription);
        $subscriptionService = PaymentGatway::Subscription($subscription);
        //dd($subscriptionService);
        // $subscriptionVindi = $subscriptionService->storeSubscription();
        $subscription->vindi_id = 682369;
        $subscriptionVindi = $subscriptionService->cancelSubscription();
        dd("teste", $subscriptionVindi);
    }
}
