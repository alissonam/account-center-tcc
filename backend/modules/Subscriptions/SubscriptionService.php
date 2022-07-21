<?php

namespace Subscriptions;

use App\Http\Services\Service;
use Illuminate\Support\Facades\Hash;
use Plans\Plan;
use Products\Product;
use Products\ProductService;
use Users\User;
use Users\UserService;

/**
 * Class SubscriptionService
 * @package Subscriptions
 */
class SubscriptionService extends Service
{
    /**
     * @param array $filters
     * @return array
     */
    public function index(array $filters)
    {
        $filters = UserService::injectLoggedUserFilters($filters);
        $subscriptionsQuery = SubscriptionRepository::index($filters);

        return self::buildReturn(
            $subscriptionsQuery
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
        $plan = Plan::find($data['plan_id']);
        $data['product_id'] = $plan->product_id;
        $userLogged = auth()->user();

        if ($userLogged->role === User::USER_ROLE_MEMBER && !Hash::check($data['password'], $userLogged->password ?? null)) {
            throw self::exception([
                'message' => 'Senha incorreta!'
            ], 403);
        }

        if ($userLogged->role === User::USER_ROLE_MEMBER){
            $data['user_id'] = $userLogged->id;
            $userToSubscription = $userLogged;
        } else {
            $userToSubscription = User::find($data['user_id']);
        }

        $subscriptionActive = SubscriptionRepository::activeSubscription($data['user_id'], $data['product_id'])->first();
        $product = $plan->product;

        if (!$subscriptionActive){
            $payload = $plan->payload;
            if($plan->default){
                $data['status'] = Subscription::STATUS_ACTIVE;
            }else {
                $defaultPlan = $product->plans()->where('default', true)->first();
                $dataToDefaultPlan = $data;
                $dataToDefaultPlan['plan_id'] = $defaultPlan->id;
                $dataToDefaultPlan['status'] = Subscription::STATUS_ACTIVE;
                Subscription::create($dataToDefaultPlan);
                $payload = $defaultPlan->payload;
            }
            $json = [
                'action' => 'create_account',
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
                    'complement'   => $userToSubscription->complement,
                    'password'     => $data['password'],
                ],
                'payload' => $payload
            ];
            try {
                ProductService::sendDataToProduct($product, $json);
            }catch (\Throwable) {
                throw self::exception(['message' => 'Falha na inscrição do produto']);
            }
        }
        $subscription = Subscription::create($data);

        return self::buildReturn($subscription);
    }

    /**
     * @param Subscription $subscription
     * @param array $data
     * @return array
     */
    public function update(Subscription $subscription, array $data)
    {
        $userLogged = auth()->user();

        if ($userLogged->role === User::USER_ROLE_MEMBER && !Hash::check($data['password'], $userLogged->password ?? null)) {
            throw self::exception([
                'message' => 'Senha incorreta!'
            ], 403);
        }

        $subscription->update($data);

        return self::buildReturn($subscription);
    }
}
