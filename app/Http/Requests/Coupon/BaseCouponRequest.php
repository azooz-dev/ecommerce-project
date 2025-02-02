<?php

namespace App\Http\Requests\Coupon;

use App\Http\Requests\BaseRequest;

abstract class BaseCouponRequest extends BaseRequest
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
    abstract public function rules();

    public function attributes(): array
    {
        return [
            'id'              => 'identifier',
            'name'            => 'code',
            'status'          => 'status',
            'discount'        => 'discount',
            'coupon_validity' => 'coupon validity',
        ];
    }

    public static function transformAttributes($index)
    {
        $attribute = [
            'identifier' => 'id',
            'code'       => 'name',
            'status'     => 'status',
            'validity'   => 'coupon_validity',
            'discount'   => 'discount',
        ];
        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
