<?php

namespace App\Http\Requests\Category;

use App\Http\Requests\BaseRequest;


abstract class BaseCategoryRequest extends BaseRequest
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
            'id' => 'identifier',
            'name' => 'title',
            'description' => 'details',
            'created_at' => 'createdDate',
            'updated_at' => 'lastChange',
            'deleted_at' => 'deletedDate',
        ];
    }

    public static function transformAttributes($index)
    {
        $attribute = [
            'title' => 'name',
            'details' => 'description'
        ];
        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
