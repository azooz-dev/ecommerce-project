<?php

namespace App\Services\Product;

use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use App\Repositories\Product\ProductRepository;
use Illuminate\Support\Str;

use function App\Helpers\errorResponse;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        $products = $this->productRepository->index();

        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        $product = $this->productRepository->show($product);

        return new ProductResource($product);
    }

    public function create(array $data)
    {
        $data['slug'] = Str::slug($data['name']);

        $product = $this->productRepository->create($data);

        return new ProductResource($product);
    }

    public function update($product, array $data)
    {
        $product = $this->productRepository->update($product, $data);

        if (!$product->isDirty()) {
            return errorResponse('يجب تغيير قيمة واحدة على الأقل', 422);
        }

        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $product = $this->productRepository->destroy($product);

        return new ProductResource($product);
    }
}
