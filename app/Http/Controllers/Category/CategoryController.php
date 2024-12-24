<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category;
use Exception;

use function App\Helpers\errorResponse;
use function App\Helpers\showAll;
use function App\Helpers\showOne;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Category::all();
            $categories = CategoryResource::collection($categories);

            return showAll($categories, 200);
        } catch (\Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request)
    {
        try {
            $data = $request->validated();

            $category = Category::create($data);

            $category = new CategoryResource($category);

            return showOne($category, 200);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        try {
            $category = new CategoryResource($category);
            return showOne($category, 200);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        try {
            $data = $request->validated();

            $category = $category->fill($data);
            if (!$category->isDirty()) {
                return errorResponse('يجب تغيير قيمة واحدة على الأقل', 422);
            }

            $category->save();
            $category = new CategoryResource($category);

            return showOne($category);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();

            return showOne(new CategoryResource($category));
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }
}
