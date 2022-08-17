<?php

namespace Suggestions;

use App\Http\Requests\Request;

/**
 * Class SuggestionRequest
 * @package Suggestions
 */
class SuggestionRequest extends Request
{
    /**
     * @return string[]
     */
    public function validateToIndex()
    {
        return [
            'product_id' => '',
        ];
    }

    /**
     * @return string[]
     */
    public function validateToStore()
    {
        return [
            'description' => 'required',
            'product_id'  => 'required|exists:products,id',
        ];
    }

    /**
     * @return string[]
     */
    public function validateToUpdate()
    {
        return [
            'description' => 'required',
        ];
    }
}
