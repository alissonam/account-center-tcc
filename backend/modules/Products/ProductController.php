<?php

namespace Products;

use App\Http\Controllers\Controller;

/**
 * Class ProductController
 * @package Products
 */
class ProductController extends Controller
{
    use ProductResponse;

    private ProductService $productService;

    /**
     * ProductController constructor.
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @param ProductRequest $request
     * @return mixed
     */
    public function index(ProductRequest $request)
    {
        $result = $this->productService->index($request->validated());

        return $this->response($result['response'], $result['status']);
    }

    /**
     * @param ProductRequest $request
     * @return mixed
     */
    public function store(ProductRequest $request)
    {

        $result = $this->productService->store($request->validated());

        return $this->response($result['response'], $result['status']);
    }

    /**
     * @param Product $product
     * @return mixed
     */
    public function show(Product $product)
    {
        return $this->response($product->load(\request('with') ?? [])->toArray());
    }

    /**
     * @param ProductRequest $request
     * @param Product $product
     * @return mixed
     */
    public function update(ProductRequest $request, Product $product)
    {
        $result = $this->productService->update($product, $request->validated());

        return $this->response($result['response'], $result['status']);
    }

    /**
     * @param Product $product
     * @return mixed
     */
    public function destroy(Product $product)
    {
        $result = $this->productService->destroy($product);

        return $this->response($result['response'], $result['status']);
    }
}
