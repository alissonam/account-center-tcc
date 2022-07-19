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
            'user_id'       => '',
            'vindi_id'      => '',
            'password'      => 'nullable|string|min:6',
        ];
    }

    /**
     * @return string[]
     */
    public function validateToUpdate()
    {
        return [
            'plan_id'   => '',
            'vindi_id'  => '',
            'password'  => 'nullable|string|min:6',
        ];
    }
}
