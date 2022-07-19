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
        $data['product_id'] = Product::find($plan->product_id)->id;
        $product = Product::find($data['product_id']);
        $userLogged = auth()->user();

        if ($userLogged->role === User::USER_ROLE_MEMBER && !Hash::check($data['password'], $userLogged->password ?? null)) {
            throw self::exception([
                'message' => 'Senha incorreta!'
            ], 403);
        }

        if ($userLogged->role === User::USER_ROLE_MEMBER){
            $data['user_id'] = $userLogged->id;
        }

        $json = [
            'action' => 'create_account',
            'user'   => [
                'id'           => $userLogged->id,
                'name'         => $userLogged->name,
                'last_name'    => $userLogged->last_name,
                'document'     => $userLogged->document,
                'registration' => $userLogged->registration,
                'email'        => $userLogged->email,
                'phone'        => $userLogged->phone,
                'zipcode'      => $userLogged->zipcode,
                'state'        => $userLogged->state,
                'city'         => $userLogged->city,
                'neighborhood' => $userLogged->neighborhood,
                'street'       => $userLogged->street,
                'number'       => $userLogged->number,
                'complement'   => $userLogged->complement,
                'password'     => $data['password'],
            ],
            'payload' => $plan->payload
        ];

        ProductService::sendDataToProduct($product, $json);
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

    /**
     * @param Subscription $subscription
     * @return array
     */
    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return self::buildReturn();
    }
}
