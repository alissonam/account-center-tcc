<?php

use Subscriptions\Subscription;
use Illuminate\Support\Facades\Artisan;
use Plans\Plan;
use Products\Product;
use Products\ProductService;
use Users\User;

/*
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
*/

Artisan::command('subscription:active-defaults', function () {
    $this->comment("Iniciando ativação de inscrições com planos padrões");

    $subscriptions = Subscription::query()
        ->where('subscriptions.status', Subscription::STATUS_AWAITING)
        ->join('plans', 'plans.id', 'subscriptions.plan_id')
        ->where('plans.default', true)
        ->where('subscriptions.created_at', '<', (new \DateTime())->setTime(0,0))
        ->get();

    foreach ($subscriptions as $subscription) {
        $activeSubscription = Subscription::query()
            ->where('status', Subscription::STATUS_ACTIVE)
            ->where('user_id', $subscription->user_id)
            ->where('product_id', $subscription->product_id)
            ->first();

        if(!$activeSubscription) {
            continue;
        }

        $dayOfActiveSubscription = (new \DateTime($activeSubscription->created_at))->format('d');
        $dateOfCurrentMonth = (new \DateTime())->format('y-m');
        $dateToInactive = (new \DateTime("$dateOfCurrentMonth-$dayOfActiveSubscription"))->setTime(0,0);

        if ($dateToInactive == (new \DateTime())->setTime(0,0)) {
            $activeSubscription->update(['status' => Subscription::STATUS_INACTIVE]);
            $subscription->update(['status' => Subscription::STATUS_ACTIVE]);

            $userToSubscription = User::find($subscription->user_id);
            $product = Product::find($subscription->product_id);
            $plan = Plan::find($subscription->plan_id);

            $json = [
                'action' => 'update_subscription',
                'user'   => [
                    'id'           => $userToSubscription->id,
                    'name'         => $userToSubscription->name,
                    'last_name'    => $userToSubscription->last_name,
                    'document'     => $userToSubscription->document,
                    'registration' => $userToSubscription->registration,
                    'email'        => $userToSubscription->email,
                    'phone'        => $userToSubscription->phone,
                    'zipcode'      => $userToSubscription->zipcode,
                    'state'        => $userToSubscription->state,
                    'city'         => $userToSubscription->city,
                    'neighborhood' => $userToSubscription->neighborhood,
                    'street'       => $userToSubscription->street,
                    'number'       => $userToSubscription->number,
                    'complement'   => $userToSubscription->complement
                ],
                'payload' => $plan->payload
            ];

            ProductService::sendDataToProduct($product, $json);

            $this->comment("Ativação de inscrições padrões efetuada com sucesso!");
        }
    }
})->purpose('Efetivar inscrições com planos padrões');
