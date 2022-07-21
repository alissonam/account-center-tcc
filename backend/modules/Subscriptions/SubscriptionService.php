<?php

namespace Subscriptions;

use App\Http\Services\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Plans\Plan;
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
        $filters            = UserService::injectLoggedUserFilters($filters);
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
        /** @var User $userLogged */
        $userLogged = auth()->user();

        if (
            $userLogged->role === User::USER_ROLE_MEMBER &&
            !Hash::check($data['password'], $userLogged->password ?? null)
        ) {
            throw self::exception([
                'message' => 'Senha incorreta!'
            ], 403);
        }

        if ($userLogged->role === User::USER_ROLE_MEMBER) {
            $data['user_id']    = $userLogged->id;
            $userToSubscription = $userLogged;
        } else {
            $userToSubscription = User::find($data['user_id']);
        }

        $plan               = Plan::find($data['plan_id']);
        $data['product_id'] = $plan->product_id;
        $product            = $plan->product;

        $haveActiveSubscription = SubscriptionRepository::activeSubscription($data['user_id'], $data['product_id'])->first();

        DB::beginTransaction();
        try {
            if (!$haveActiveSubscription) {
                $payload = $plan->payload;
                if ($plan->default) {
                    $data['status'] = Subscription::STATUS_ACTIVE;
                } else {
                    $defaultPlan                  = $product->plans()->where('default', true)->first();
                    $dataToDefaultPlan            = $data;
                    $dataToDefaultPlan['plan_id'] = $defaultPlan->id;
                    $dataToDefaultPlan['status']  = Subscription::STATUS_ACTIVE;
                    Subscription::create($dataToDefaultPlan);
                    $payload = $defaultPlan->payload;
                }

                try {
                    ProductService::sendDataToProduct($product, [
                        'action'  => 'subscription',
                        'user'    => [
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
                    ]);
                } catch (\Throwable) {
                    throw self::exception(['message' => 'Falha ao criar inscrição no produto']);
                }
            }

            $subscription = Subscription::create($data);

            // TODO: Criação vindi aqui passando $subscription

            DB::commit();
        } catch (\Throwable $t) {
            DB::rollBack();
            // TODO: adicionar notificação slack ou registro do erro com msg do erro
            throw self::exception(['message' => 'Falha na inscrição do produto']);
        }

        return self::buildReturn($subscription);
    }

    /**
     * @param Subscription $subscription
     * @param array $data
     * @return array
     */
    public function update(Subscription $subscription, array $data)
    {
        $subscription->update($data);

        return self::buildReturn($subscription);
    }
}
