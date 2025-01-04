<?php

namespace App\Http\Resources\Order\OrderItems;

use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemsResource extends JsonResource
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
            'product'        => new ProductResource($this->product),
            'stock'          => (int) $this->quantity,
            'price'          => (float) $this->price,
            'discount_price' => (float) $this->discount_price,
            'createdDate'    => (string) $this->created_at,
            'lastChange'     => (string) $this->updated_at,
            'deletedDate'    => isset($this->deleted_at) ? (string) $this->deleted_at : null,
        ];
    }


    public static function transformAttribute($index)
    {
        $attribute = [
            'identifier'     => 'id',
            'product'        => 'product',
            'stock'          => 'quantity',
            'price'          => 'price',
            'discount_price' => 'discount_price',
            'createdDate'    => 'created_at',
            'lastChange'     => 'updated_at',
            'deletedDate'    => 'deleted_at',
        ];
        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
