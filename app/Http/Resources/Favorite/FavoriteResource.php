<?php

namespace App\Http\Resources\Favorite;

use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'identifier' => (int) $this->id,
            'product'    => new ProductResource($this->product),
            'user'       => $this->user,
        ];
    }

    public static function transformAttribute($index)
    {
        $attribute = [
            'identifier' => 'id',
            'product'    => 'product_id',
            'user'       => 'user_id',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
