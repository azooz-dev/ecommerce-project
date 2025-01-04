<?php

namespace App\Http\Requests\Favorite;

use App\Http\Requests\BaseRequest;

abstract class BaseFavoriteRequest extends BaseRequest
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
            'id'      => 'identifier',
            'product_id' => 'product',
        ];
    }


    public static function transformAttributes($index)
    {
        $attribute = [
            'identifier'      => 'id',
            'product'         => 'product_id',
        ];
        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
