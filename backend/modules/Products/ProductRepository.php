<?php

namespace Products;

use Products\Product;

/**
 * Class ProductRepository
 * @package Products
 */
class ProductRepository
{
    /**
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function index($filters = [])
    {
        return Product::query()
            ->when($filters['name'] ?? null, function ($query, $name) {
                return $query->where('name', 'like', "%$name%");
            })->when($filters['description'] ?? null, function ($query, $description) {
                return $query->where('description', 'like', "%$description%");
            })->when($filters['code'] ?? null, function ($query, $code) {
                return $query->where('code', $code);
            })->when($filters['status'] ?? null, function ($query, $status) {
                return $query->where('status', $status);
            });
    }
}
