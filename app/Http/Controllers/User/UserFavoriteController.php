<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Favorite\FavoriteStoreRequest;
use App\Http\Resources\Favorite\FavoriteResource;

use App\Models\User;
use Exception;
use function App\Helpers\errorResponse;
use function App\Helpers\showAll;
use function App\Helpers\showOne;

class UserFavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }


    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        try {
            if ($user->favorites->isEmpty()) {
                return errorResponse('لا يوجد منتجات مفضلة', 404);
            }

            $favorites = $user->favorites;

            return showAll(FavoriteResource::collection($favorites), 200);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FavoriteStoreRequest $request, User $user)
    {
        $data = $request->validated();
        try {
            $favorite = $user->favorites()->create($data);

            return showOne(new FavoriteResource($favorite), 201);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, String $product)
    {
        try {
            if (!$product) {
                return errorResponse('يجب إدخال رقم المنتج', 422);
            }

            $favorite = $user->favorites()->where('product_id', $product)->first();

            if (!$favorite) {
                return errorResponse('هذا المنتج غير موجود في قائمة المفضلة', 404);
            }

            $favorite->delete();
            return showOne(new FavoriteResource($favorite), 200);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }
}
