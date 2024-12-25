<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseRequest;
use App\Models\Product;

abstract class BaseProductRequest extends BaseRequest
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
        // Return 'required' for store requests, 'nullable' for update requests
        return $this instanceof ProductStoreRequest ? 'required' : 'nullable';
    }

    public function commonRules(): array
    {
        return [
            'name'           => $this->ruleFor('name') . '|string|max:255',
            'description'    => 'nullable|string',
            'status'         => 'nullable|boolean',
            'quantity'       => $this->ruleFor('quantity') . '|integer|min:1',
            'price'          => $this->ruleFor('price') . '|numeric|min:0',
            'price_discount' => 'nullable|numeric|min:0',
            'category_id'    => $this->ruleFor('category_id') . '|exists:categories,id',
            'status'      => 'nullable|in:' . Product::AVAILABLE_PRODUCT . ',' . Product::UNAVAILABLE_PRODUCT,
        ];
    }

    public function attributes(): array
    {
        return [
            'id'             => 'identifier',
            'name'           => 'title',
            'description'    => 'details',
            'quantity'       => 'stock',
            'status'         => 'situation',
            'productImages'  => 'pictures',
            'productSizes'   => 'sizes',
            'category_id'    => 'category_id',
            'price'          => 'price',
            'price_discount' => 'price_discount',
            'category_id'    => 'category_id',
            'created_at'     => 'createdDate',
            'update_at'      => 'lastChange',
            'deleted_at'     => 'deletedDate'
        ];
    }

    public static function transformAttributes($index)
    {
        $attribute = [
            'title'          => 'name',
            'details'        => 'description',
            'stock'          => 'quantity',
            'situation'      => 'status',
            'pictures'        => 'productImages',
            'sizes'          => 'productSizes',
            'category'       => 'category_id',
            'price'          => 'price',
            'price_discount' => 'price_discount',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
