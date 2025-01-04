<?php

namespace App\Http\Requests\Order\OrderItems;

use App\Http\Requests\Order\OrderItems\BaseOrderItemsRequest;

class OrderItemsStoreRequest extends BaseOrderItemsRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_items' => 'required|array',
            'order_items.*.product_id'  => 'required|exists:products,id',
            'order_items.*.quantity'    => 'required|integer',
        ];
    }
}
