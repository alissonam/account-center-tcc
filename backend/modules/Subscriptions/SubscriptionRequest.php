<?php

namespace Subscriptions;

use App\Http\Requests\Request;

/**
 * Class SubscriptionRequest
 * @package Subscriptions
 */
class SubscriptionRequest extends Request
{
    /**
     * @return string[]
     */
    public function validateToIndex()
    {
        return [
            'name' => '',
        ];
    }

    /**
     * @return string[]
     */
    public function validateToStore()
    {
        return [
            'name'          => 'required|min:2',
            'plan_id'       => '',
            'product_id'    => '',
            'user_id'       => '',
        ];
    }

    /**
     * @return string[]
     */
    public function validateToUpdate()
    {
        return [
            'name'      => 'min:2',
            'plan_id'       => '',
            'product_id'    => '',
            'user_id'       => '',
        ];
    }
}
