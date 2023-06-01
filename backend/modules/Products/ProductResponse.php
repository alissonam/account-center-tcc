<?php

namespace Products;

use Media\MediaService;

/**
 * Trait ProductResponse
 * @package Products
 */
trait ProductResponse
{
    public function responseToIndex($data = [], int $statusCode = 200)
    {
        foreach ($data->items() as $product) {
            $product['logoUrl'] = !$product->logo ? '' :
            $product['logoUrl'] = $product->logo->filename;
        }
        return response()->json($data, $statusCode);
    }
}
