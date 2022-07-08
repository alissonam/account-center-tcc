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
            'payload'       => '',
            'description'   => '',
            'hidden'        => '',
            'preferential'  => '',
            'product_id'    => '',
        ];
    }

    /**
     * @return string[]
     */
    public function validateToUpdate()
    {
        return [
            'name'      => 'min:2',
            'payload'       => '',
            'description'   => '',
            'hidden'        => '',
            'preferential'  => '',
            'product_id'    => '',
        ];
    }
}
