<?php

namespace App\Http\Requests\Favorite;

use App\Http\Requests\Favorite\BaseFavoriteRequest;

class FavoriteStoreRequest extends BaseFavoriteRequest
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
            'product_id' => 'required|exists:products,id',
        ];
    }
}
