<?php

namespace App\Http\Requests\Order;

use App\Http\Requests\Order\BaseOrderRequest;
use App\Models\Order;


class OrderStoreRequest extends BaseOrderRequest
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
            'payment_method' => 'required|string',
            'address'        => 'required|string',
            'user_id'        => 'required|exists:users,id',
        ];
    }
}
