<?php

namespace App\Repositories\Category;

use App\Models\Category;


class CategoryRepository
{
    public function index()
    {
        return Category::latest()->get();
    }

    public function show(Category $category)
    {
        return $category;
    }

    public function create(array $data)
    {
        return Category::create($data);
    }

    public function update(Category $category, array $data)
    {
        $category->update($data);

        return $category;
    }

    public function destroy(Category $category)
    {
        return $category->destroy($category->id);
    }
}
