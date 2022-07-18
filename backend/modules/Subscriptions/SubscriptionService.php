<?php

namespace Subscriptions;

use App\Http\Services\Service;
use Illuminate\Support\Facades\Hash;
use Plans\Plan;
use Products\Product;
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
        $userLogged = auth()->user();

        if ($userLogged->role === User::USER_ROLE_MEMBER && !Hash::check($data['password'], $userLogged->password ?? null)) {
            throw self::exception([
                'message' => 'Senha incorreta!'
            ], 403);
        }

        if ($userLogged->role === User::USER_ROLE_MEMBER){
            $data['user_id'] = $userLogged->id;
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

        if ($userLogged->role === User::USER_ROLE_MEMBER && !Hash::check($data['password'], $user->password ?? null)) {
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
