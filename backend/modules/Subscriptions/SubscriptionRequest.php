<?php

namespace Subscriptions;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

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
            'plan_id'    => '',
            'user_id'    => '',
            'product_id' => '',
            'status'     => ''
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
            'vindi_id'    => '',
        ];
    }
}
