<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Models\Product;
use App\Services\Product\ProductService;
use Exception;
use function App\Helpers\errorResponse;
use function App\Helpers\showAll;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = $this->productService->index();

            return showAll($products, 'products', 200);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        $data = $request->validated();

        if ($this->authorize('create', Product::class)) {
            try {
                $product = $this->productService->create($data);

                return showAll($product, 'product', 201);
            } catch (Exception $e) {
                return errorResponse($e->getMessage(), 500);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        try {
            $product = $this->productService->show($product);

            return showAll($product, 'product', 200);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($this->authorize('update', $product)) {
            try {
                $product = $this->productService->update($product, $data);

                return showAll($product, 'product', 200);
            } catch (Exception $e) {
                return errorResponse($e->getMessage(), 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($this->authorize('delete', $product)) {
            try {
                $product = $this->productService->destroy($product);

                return showAll($product, 'product', 200);
            } catch (Exception $e) {
                return errorResponse($e->getMessage(), 500);
            }
        }
    }
}
