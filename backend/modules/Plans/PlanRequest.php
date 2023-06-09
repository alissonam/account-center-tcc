<?php

namespace Plans;

use App\Http\Requests\Request;

/**
 * Class PlanRequest
 * @package Plans
 */
class PlanRequest extends Request
{
    /**
     * @return string[]
     */
    public function validateToIndex()
    {
        return [
            'name'       => '',
            'product_id' => '',
        ];
    }

    /**
     * @return string[]
     */
    public function validateToStore()
    {
        return [
            'name'             => 'required|min:2',
            'product_id'       => 'required',
            'vindi_product_id' => '',
            'vindi_plan_id'    => '',
            'payload'          => '',
            'description'      => '',
            'visible'          => '',
            'preferential'     => '',
            'default'          => '',
            'order'            => ''
        ];
    }

    /**
     * @return string[]
     */
    public function validateToUpdate()
    {
        return [
            'name'             => 'min:2',
            'product_id'       => '',
            'vindi_product_id' => '',
            'vindi_plan_id'    => '',
            'payload'          => '',
            'description'      => '',
            'visible'          => '',
            'preferential'     => '',
            'default'          => '',
            'order'            => ''
        ];
    }
}
