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
     * @throws \Throwable
     */
    public function store(array $data)
    {
        /** @var User $loggedUser */
        $loggedUser = auth()->user();

        if (
            $loggedUser->role === User::USER_ROLE_MEMBER &&
            !Hash::check($data['password'], $loggedUser->password ?? null)
        ) {
            throw self::exception([
                'message' => 'Senha incorreta!'
            ], 403);
        }

        DB::beginTransaction();
        try {
            $plan                   = Plan::find($data['plan_id']);
            $userId                 = $loggedUser->checkRole(User::USER_ROLE_MEMBER) ? $loggedUser->id : $data['user_id'];
            $haveActiveSubscription = SubscriptionRepository::activeSubscription($userId, $plan->product_id)->first();

            self::prepareData($data, [
                'user_id'    => fn($value) => $userId,
                'product_id' => fn($value) => $plan->product_id,
                'status'     => fn($value) => !$haveActiveSubscription && $plan->default ? Subscription::STATUS_ACTIVE : Subscription::STATUS_AWAITING,
            ], true);

            $createdSubscription = Subscription::create($data);

            if (!$haveActiveSubscription) {
                $subscriptionToProductApi = $createdSubscription;

                if (!$plan->default) {
                    $defaultPlan       = ProductService::getDefaultPlan($plan->product);
                    $dataToDefaultPlan = $data;
                    self::prepareData($dataToDefaultPlan, [
                        'plan_id' => fn($value) => $defaultPlan->id,
                        'status'  => fn($value) => Subscription::STATUS_ACTIVE,
                    ], true);
                    $subscriptionToProductApi = Subscription::create($dataToDefaultPlan);
                }

                try {
                    self::sendSubscriptionToProductApi($subscriptionToProductApi, $data['password']);
                } catch (\Throwable) {
                    throw self::exception(['message' => 'Falha ao criar inscrição no produto']);
                }
            }

            if (!$plan->default) {
                try {
                    // TODO: Criação vindi aqui passando $createdSubscription
                } catch (\Throwable) {
                    throw self::exception(['message' => 'Falha ao criar inscrição no gateway']);
                }
            }

            DB::commit();
        } catch (\Throwable $t) {
            DB::rollBack();
            // TODO: adicionar notificação slack ou registro do erro com msg do erro
            throw self::exception(['message' => 'Falha na inscrição do produto']);
        }

        return self::buildReturn($createdSubscription);
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

    /**
     * @param $subscription
     */
    public static function activeSubscriptionByGateway($subscription)
    {
        DB::beginTransaction();
        try {
            SubscriptionRepository::activeSubscription($subscription->user_id, $subscription->product_id)
                ->update([
                    'status'      => Subscription::STATUS_INACTIVE,
                    'finished_in' => new \DateTime()
                ]);

            $subscription->update(['status' => Subscription::STATUS_ACTIVE]);

            self::sendSubscriptionToProductApi($subscription);

            DB::commit();
        } catch (\Throwable $t) {
            DB::rollBack();
            // TODO: adicionar notificação slack ou registro do erro com msg do erro
            throw self::exception(['message' => 'Falha na ativação da inscrição']);
        }
    }

    /**
     * @param Subscription $subscription
     * @param null $userPassword
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function sendSubscriptionToProductApi(Subscription $subscription, $userPassword = null)
    {
        $product            = $subscription->product;
        $plan               = $subscription->plan;
        $userToSubscription = $subscription->user;

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
                'password'     => $userPassword,
            ],
            'payload' => $plan->payload
        ]);
    }
}
