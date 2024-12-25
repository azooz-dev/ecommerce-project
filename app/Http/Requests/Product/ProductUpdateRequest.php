<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\Product\BaseProductRequest;

class ProductUpdateRequest extends BaseProductRequest
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
        return array_merge($this->commonRules(), [
            'productImages' => 'nullable|array|min:1',
            'productImages.*' => 'nullable|image|mime:jpeg,png,jpg,gif,svg|max:2048',
            'productSizes' => 'nullable|array|min:1',
            'quantity' => 'sometimes|integer',
        ]);
    }
}
