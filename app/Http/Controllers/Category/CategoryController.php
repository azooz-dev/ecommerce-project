<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Models\Category;
use App\Services\Category\CategoryService;
use Exception;

use function App\Helpers\errorResponse;
use function App\Helpers\showAll;
use function App\Helpers\showOne;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService)
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
        $this->categoryService = $categoryService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // if ($this->authorize('view', Category::class)) {
        try {
            $categories = $this->categoryService->index();

            return showAll($categories, 200);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
        // }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request)
    {
        if ($this->authorize('create', Category::class)) {
            try {
                $data = $request->validated();

                $category = $this->categoryService->create($data);

                return showOne($category, 201);
            } catch (Exception $e) {
                return errorResponse($e->getMessage(), 500);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        try {
            $category = $this->categoryService->show($category);
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
        if ($this->authorize('update', Category::class)) {
            try {
                $data = $request->validated();

                $category = $this->categoryService->update($category, $data);

                return showOne($category);
            } catch (Exception $e) {
                return errorResponse($e->getMessage(), 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($this->authorize('delete', Category::class)) {
            try {
                $category = $this->categoryService->destroy($category);

                return showOne($category);
            } catch (Exception $e) {
                return errorResponse($e->getMessage(), 500);
            }
        }
    }
}
