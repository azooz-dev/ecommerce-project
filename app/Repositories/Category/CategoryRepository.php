<?php

namespace App\Repositories\Category;

use App\Models\Category;

use function App\Helpers\errorResponse;

class CategoryRepository
{
    public function index()
    {
        return Category::all();
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

        if (!$category->isClean()) {
            return errorResponse('يجب تغيير قيمة واحدة على الأقل', 422);
        }

        return $category;
    }

    public function destroy(Category $category)
    {
        return $category->destroy($category->id);
    }
}
