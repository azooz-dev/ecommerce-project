<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Category\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'identifier'     => (int) $this->id,
            'title'          => (string) $this->name,
            'slug'           => (string) $this->slug,
            'details'        => (string) $this->description,
            'stock'          => (int) $this->quantity,
            'price'          => (float) $this->price,
            'price_discount' => (float) $this->price_discount,
            'pictures'       => $this->productImages,
            'sizes'          => $this->productSizes,
            'category'       => new CategoryResource($this->category),
            'situation'      => (string) $this->status,
            'createdDate'    => (string) $this->created_at,
            'lastChange'     => (string) $this->updated_at,
            'deletedDate'    => isset($this->deleted_at) ? (string) $this->deleted_at : null,
        ];
    }

    public static function transformAttribute($index)
    {
        $attribute = [
            'identifier'     => 'id',
            'title'          => 'name',
            'slug'           => 'slug',
            'details'        => 'description',
            'stock'          => 'quantity',
            'situation'      => 'status',
            'price'          => 'price',
            'category'       => 'category_id',
            'price discount' => 'price_discount',
            'pictures'       => 'productImages',
            'sizes'          => 'productSizes',
            'createdDate'    => 'created_at',
            'lastChange'     => 'updated_at',
            'deletedDate'    => 'deleted_at',
        ];
        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
