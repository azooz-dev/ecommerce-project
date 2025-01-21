<?php

namespace App\Services\Category;

use App\Http\Resources\Category\CategoryResource;
use App\Repositories\Category\CategoryRepository;
use Illuminate\Support\Str;

use function App\Helpers\errorResponse;

class CategoryService
{
    public function __construct(private CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }


    public function index()
    {
        $categories = $this->categoryRepository->index();
        return CategoryResource::collection($categories);
    }

    public function show($category)
    {
        $category = $this->categoryRepository->show($category);
        return new CategoryResource($category);
    }

    public function create($data)
    {
        $data['slug'] = Str::slug($data['name']);

        $category = $this->categoryRepository->create($data);
        return new CategoryResource($category);
    }

    public function update($category, $data)
    {
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category = $this->categoryRepository->update($category, $data);

        if (!$category->isDirty()) {
            return errorResponse('يجب تغيير قيمة واحدة على الأقل', 422);
        }

        return new CategoryResource($category);
    }

    public function destroy($category)
    {
        $category = $this->categoryRepository->destroy($category);
        return new CategoryResource($category);
    }
}
