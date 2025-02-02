<?php

namespace App\Http\Requests\Coupon;

use App\Models\Coupon;

class CouponStoreRequest extends BaseCouponRequest
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
            'name'            => 'required|string|max:255',
            'coupon_validity' => 'required|date|after:today',
            'discount'        => 'required|numeric|min:0|max:100',
            'status'          => 'required|in:' . Coupon::ACTIVE_COUPON . ',' . Coupon::INACTIVE_COUPON
        ];
    }
}
