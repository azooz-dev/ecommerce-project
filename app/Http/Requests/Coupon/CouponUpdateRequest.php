<?php

namespace App\Http\Requests\Coupon;

use App\Http\Requests\Coupon\BaseCouponRequest;
use App\Models\Coupon;

class CouponUpdateRequest extends BaseCouponRequest
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
            'name'            => 'nullable|string|max:255',
            'coupon_validity' => 'nullable|date|after:today',
            'discount'        => 'nullable|numeric|min:0|max:100',
            'status'          => 'nullable|in:' . Coupon::ACTIVE_COUPON . ',' . Coupon::INACTIVE_COUPON
        ];
    }
}
