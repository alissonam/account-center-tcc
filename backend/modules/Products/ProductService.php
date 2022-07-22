<?php

namespace Products;

use App\Http\Services\Service;
use Plans\Plan;
use GuzzleHttp\Client;

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

    /**
     * @param Product $product
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany|Plan|null
     */
    public static function getDefaultPlan(Product $product)
    {
        return $product->plans()->where('default', true)->first();
    }

    /**
     * @param Product $product
     * @param array $data
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function sendDataToProduct(Product $product, array $data)
    {
        $client             = new Client();
        $url                = $product->action_url;
        $options['headers'] = [
            'Content-Type'   => 'application/json',
            'Accept'         => 'application/json',
            'x-access-token' => $product->api_token,
        ];
        $options['json']    = $data;

        $client->post($url, $options);
    }
}
