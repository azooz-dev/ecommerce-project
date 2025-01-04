<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use Exception;
use function App\Helpers\errorResponse;
use function App\Helpers\showAll;
use function App\Helpers\uploadFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Product::with('category')
                ->with('productImages')
                ->with('productSizes')->get();

            $products = ProductResource::collection($products);
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
        $data['slug'] = Str::slug($data['name']);

        try {
            $product = Product::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description'],
                'price' => $data['price'],
                'price_discount' => isset($data['price_discount']) ? $data['price_discount'] : null,
                'quantity' => $data['quantity'],
                'category_id' => $data['category_id'],
                'status' => Product::AVAILABLE_PRODUCT,
            ]);

            if ($request->has('productImages')) {
                $this->handleProductImages($product, $data['productImages']);
            }

            if (isset($data['productSizes'])) {
                $this->handleProductSizes($product, $data['productSizes']);
            }

            return showAll(new ProductResource($product), 'product', 201);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        try {
            $product = new ProductResource($product);
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

        try {

            if ($request->has('category_id')) {
                $product->category_id = $data['category_id'];
            }

            if ($request->has('name')) {
                $product->name = $data['name'];
                $product->slug = Str::slug($data['name']);
            }

            if ($request->has('productImages')) {
                $this->deleteProductImages($product);
                $this->handleProductImages($product, $data['productImages']);
            }

            if (isset($data['productSizes'])) {
                $this->handleProductSizes($product, $data['productSizes']);
            }

            if ($request->has('status')) {
                $product->status = $data['status'];
            }

            $product->update($data);

            $product = new ProductResource($product);

            return showAll($product, 'product', 200);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $this->deleteProductImages($product);
            $product->productImages()->delete();
            $product->productSizes()->delete();

            $product->delete();
            return showAll(new ProductResource($product), 'product', 200);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    private function handleProductImages($product, $productImages)
    {
        if (is_array($productImages)) {
            foreach ($productImages as $image) {
                $imageName = uploadFile($image, 'product_images', 'public');
                $product->productImages()->create([
                    'image_name' => $imageName,
                    'alt_text' => isset($image['alt_text']) ? $image['alt_text'] : null
                ]);
            }
        } else {
            $imageName = uploadFile($productImages, 'product_images', 'public');
            $product->productImages()->create([
                'image_name' => $imageName,
                'alt_text' => isset($image['alt_text']) ? $image['alt_text'] : null
            ]);
        }
    }

    private function handleProductSizes($product, $sizes)
    {
        if (str_contains($sizes, ',')) {
            $sizes = explode(',', $sizes);
            $sizes = array_map(function ($size) {
                return ['size_product' => $size];
            }, $sizes);
            $product->productSizes()->createMany($sizes);
        } else {
            $product->productSizes()->create([
                'size_product' => $sizes,
            ]);
        }
    }

    private function deleteProductImages($product)
    {
        if (isset($product->productImages)) {
            foreach ($product->productImages as $image) {
                if (Storage::exists('product_images/' . $image->image_name)) {
                    Storage::delete('product_images/' . $image->image_name);
                }
                $image->delete();
            }
        }
    }
}
