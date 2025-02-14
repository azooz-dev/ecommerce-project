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
                return errorResponse(__('auth.this_user_has_no_favorites'), 404);
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
                return errorResponse(__('auth.product_id_is_required'), 422);
            }

            $favorite = $user->favorites()->where('product_id', $product)->first();

            if (!$favorite) {
                return errorResponse(__('auth.this_product_is_not_in_favorites'), 404);
            }

            $favorite->delete();
            return showOne(new FavoriteResource($favorite), 200);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }
}
