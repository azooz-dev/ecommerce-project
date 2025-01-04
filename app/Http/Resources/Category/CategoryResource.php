<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'title'       => (string) $this->name,
            'slug'        => (string) $this->slug,
            'details'     => (string) $this->description,
            'createdDate' => (string) $this->created_at,
            'lastChange'  => (string) $this->updated_at,
            'deletedDate' => isset($this->deleted_at) ? (string) $this->deleted_at : null,
        ];
    }

    public static function transformAttribute($index)
    {
        $attribute = [
            'title'       => 'name',
            'slug'        => 'slug',
            'details'     => 'description',
            'createdDate' => 'created_at',
            'lastChange'  => 'updated_at',
            'deletedDate' => 'deleted_at',
        ];
        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
