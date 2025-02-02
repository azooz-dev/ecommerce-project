<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Coupon\CouponResource;
use App\Http\Resources\Order\OrderItems\OrderItemsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'identifier'       => (int) $this->id,
            'order_number'     => (string) $this->order_number,
            'total_cost'       => (string) $this->total_amount,
            'payment_type'     => (string) $this->payment_method,
            'shipping_address' => (string) $this->address,
            'coupon'           => isset($this->coupon) ? new CouponResource($this->coupon) : null,
            'status'           => (string) $this->status,
            'user'             => $this->user,
            'orderItems'       =>  OrderItemsResource::collection($this->orderItems),
            'createdDate'      => (string) $this->created_at,
            'lastChange'       => (string) $this->updated_at,
            'deletedDate'      => isset($this->deleted_at) ? (string) $this->deleted_at : null,
        ];
    }


    public function transformAttribute($index)
    {
        $attribute = [
            'identifier'       => 'id',
            'order_number'     => 'order_number',
            'total_cost'       => 'total_amount',
            'payment_type'     => 'payment_method',
            'shipping_address' => 'address',
            'status'           => 'status',
            'orderItems'       => 'order_items',
            'createdDate'      => 'created_at',
            'lastChange'       => 'updated_at',
            'deletedDate'      => 'deleted_at',
        ];
        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
