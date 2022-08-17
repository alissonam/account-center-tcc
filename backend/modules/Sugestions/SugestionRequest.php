<?php

namespace Sugestions;

use App\Http\Requests\Request;

/**
 * Class SugestionRequest
 * @package Sugestions
 */
class SugestionRequest extends Request
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
            'description'      => 'required',
            'product_id'       => 'required|exists:products,id',
        ];
    }

    /**
     * @return string[]
     */
    public function validateToUpdate()
    {
        return [
            'description'      => 'required',
        ];
    }
}
