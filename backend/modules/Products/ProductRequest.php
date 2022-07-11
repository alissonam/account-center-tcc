<?php

namespace Products;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

/**
 * Class ProductRequest
 * @package Products
 */
class ProductRequest extends Request
{

    /**
     * @return string[]
     */
    public function validateToIndex()
    {
        return [
            'name'          => '',
            'description'   => '',
            'code'          => '',
            'status'        => '',
        ];
    }

    /**
     * @return string[]
     */
    public function validateToStore()
    {
        return [
            'name'        => 'required|string|min:5|max:255',
            'status'      => ['required', 'string', Rule::in([Product::STATUS_ACTIVE, Product::STATUS_INATIVE])],
            'code'        => 'string|max:255',
            'action_url'  => 'string|max:255',
            'app_url'     => 'string|max:255',
            'api_token'   => 'string|max: 255',
            'description' => 'string',
        ];
    }

    /**
     * @return string[]
     */
    public function validateToUpdate()
    {

        return [
            'name'        => 'required|string|min:5|max:255',
            'status'      => ['required', 'string', Rule::in([Product::STATUS_ACTIVE, Product::STATUS_INATIVE])],
            'code'        => 'string|max:255',
            'action_url'  => 'string|max:255',
            'app_url'     => 'string|max:255',
            'api_token'   => 'string|max: 255',
            'description' => 'string',
        ];
    }

}
