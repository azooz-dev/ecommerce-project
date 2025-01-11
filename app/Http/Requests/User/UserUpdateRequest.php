<?php

namespace App\Http\Requests\User;

use App\Http\Requests\User\BaseUserRequest;
use App\Models\User;

class UserUpdateRequest extends BaseUserRequest
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
            'first_name'  => 'nullable|string',
            'last_name'   => 'nullable|string',
            'email'       => 'nullable|email|unique:users',
            'phone'       => 'nullable|numeric',
            'password'    => 'nullable|min:8|confirmed',
            'role'        => 'nullable|in:' . User::ADMIN_USER . ',' . User::REGULAR_USER
        ];
    }
}
