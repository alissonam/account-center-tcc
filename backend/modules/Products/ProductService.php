<?php

namespace Products;

use App\Http\Services\Service;
use Products\ProductRepository;

/**
 * Class ProductService
 * @package Products
 */
class ProductService extends Service
{

    /**
     * @param array $filters
     * @return array
     */
    public function index(array $filters)
    {
        $productsQuery = ProductRepository::index($filters);

        return self::buildReturn(
            $productsQuery
                ->with(\request()->with ?? [])
                ->paginate(\request()->perPage)
        );
    }

    /**
     * @param array $data
     * @return array
     */
    public function store(array $data)
    {
        $product = Product::create($data);

        return self::buildReturn($product);
    }

    /**
     * @param Product $product
     * @param array $data
     * @return array
     */
    public function update(Product $product, array $data)
    {
        $product->update($data);

        return self::buildReturn($product);
    }

    /**
     * @param Product $product
     * @return array
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return self::buildReturn();
    }
}
