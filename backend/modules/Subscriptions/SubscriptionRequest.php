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
        ];
    }

    /**
     * @return string[]
     */
    public function validateToStore()
    {
        return [
            'plan_id'       => 'required',
            'product_id'    => 'required',
            'user_id'       => 'required',
        ];
    }

    /**
     * @return string[]
     */
    public function validateToUpdate()
    {
        return [
            'plan_id' => '',
        ];
    }
}
