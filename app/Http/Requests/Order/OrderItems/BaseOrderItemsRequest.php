<?php

namespace App\Http\Requests\Order\OrderItems;

use App\Http\Requests\BaseRequest;

abstract class BaseOrderItemsRequest extends BaseRequest
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

    public function attributes(): array
    {
        return [
            'id'             => 'identifier',
            'order_items'    => 'orderItems'
        ];
    }

    public static function transformAttributes($index)
    {
        $attribute = [
            'identifier'       => 'id',
            'orderItems'       => 'order_items'
        ];
        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
