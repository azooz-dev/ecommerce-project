<?php

namespace App\Repositories\Product;

use App\Models\Product;
use Exception;

use function App\Helpers\errorResponse;
use function App\Helpers\uploadFile;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductRepository
{
    public function index()
    {
        $products = Product::with('category')
            ->with('productImages')
            ->with('productSizes')->get();

        return $products;
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function create(array $data)
    {
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

            if (isset($data['productImages'])) {
                $this->handleProductImages($product, $data['productImages']);
            }

            if (isset($data['productSizes'])) {
                $this->handleProductSizes($product, $data['productSizes']);
            }

            return $product;
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    public function update(Product $product, array $data)
    {
        if (isset($data['category_id'])) {
            $product->category_id = $data['category_id'];
        }

        if (isset($data['name'])) {
            $product->name = $data['name'];
            $product->slug = Str::slug($data['name']);
        }

        if (isset($data['productImage'])) {
            $this->deleteProductImages($product);
            $this->handleProductImages($product, $data['productImage']);
        }

        if (isset($data['productSizes'])) {
            $this->handleProductSizes($product, $data['productSizes']);
        }

        if (isset($data['status'])) {
            $product->status = $data['status'];
        }

        $product->update($data);


        return $product;
    }

    public function destroy(Product $product)
    {
        $this->deleteProductImages($product);
        $product->productImages()->delete();
        $product->productSizes()->delete();

        $product->delete();

        return $product;
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
}
