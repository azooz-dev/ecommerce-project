<?php

namespace App\Http\Requests\Order;

use App\Http\Requests\BaseRequest;
use App\Models\Order;

abstract class BaseOrderRequest extends BaseRequest
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
    abstract public function rules(): array;

    protected function ruleFor(string $field): string
    {
        return $this instanceof OrderStoreRequest ? 'required' : 'nullable';
    }

    public function commonRules(): array
    {
        return [
            'payment_method' => $this->ruleFor('payment_method') . '|string',
            'address'        => $this->ruleFor('address') . '|string',
            'user_id'        => $this->ruleFor('user_id') . '|exists:users,id',
            'order_items'    => $this->ruleFor('order_items') . '|array',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'             => 'identifier',
            'order_number'   => 'order_number',
            'total_amount'   => 'total_cost',
            'payment_method' => 'payment_type',
            'address'        => 'shipping_address',
            'status'         => 'status',
            'order_items'    => 'orderItems',
            'user_id'        => 'user_id'
        ];
    }

    public static function transformAttributes($index)
    {
        $attribute = [
            'identifier'       => 'id',
            'order_number'     => 'order_number',
            'total_cost'       => 'total_amount',
            'payment_type'     => 'payment_method',
            'shipping_address' => 'address',
            'status'           => 'status',
            'orderItems'       => 'order_items',
            'user_id'          => 'user_id'
        ];
        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
