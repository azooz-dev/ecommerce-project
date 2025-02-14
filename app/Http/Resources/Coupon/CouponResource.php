<?php

namespace App\Http\Resources\Coupon;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
            'code'       => (string) $this->name,
            'validity'   => (string) $this->coupon_validity,
            'discount'   => (string) $this->discount,
            'status'     => (string) $this->isActive() ? 'Active' : 'Inactive',
        ];
    }

    public static function transformAttribute($index)
    {
        $attribute = [
            'identifier' => 'id',
            'code'       => 'name',
            'validity'   => 'coupon_validity',
            'discount'   => 'discount',
            'status'     => 'status',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
