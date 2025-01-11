<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'identifier'  => (int) $this->id,
            'firstName'   => (string) $this->first_name,
            'lastName'    => (string) $this->last_name,
            'email'       => (string) $this->email,
            'phone'       => (string) $this->phone,
            'isVerified'  => (bool) $this->isVerified() == 'true',
            'isAdmin'     => (bool) $this->isAdmin() == 'true',
            'createdDate' => (string) $this->created_at,
            'lastChange'  => (string) $this->updated_at,
            'deletedDate' => isset($this->deleted_at) ? (string) $this->deleted_at : null
        ];
    }
}
