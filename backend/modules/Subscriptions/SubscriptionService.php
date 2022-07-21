<?php

namespace Subscriptions;

use App\Http\Services\Service;
use Carbon\Carbon;
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

    /**
     * @param Subscription $subscription
     * @return array
     */
    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return self::buildReturn();
    }

    /**
     * this function get subscription by vindi_id
     * @param int $vindi_id
     * @return Subscription
     */
    public static function getByVindiId($vindi_id)
    {
        return SubscriptionRepository::getByVindiId($vindi_id)->first();
    }

    /**
     * this function deactive all actives subscription in specific product of user
     * @param int $user_id
     * @param int $product_id
     */
    public static function deactiveAllActiveSubscriptionInProductOfUser($user_id, $product_id)
    {
        $subscriptions = SubscriptionRepository::getAllSubscriptionInProductOfUser($user_id, $product_id, Subscription::STATUS_INACTIVE);
        $subscriptions->update(
            [
                'status' => Subscription::STATUS_INACTIVE,
                'finished_in' => new Carbon()
            ]
        );
    }
}
