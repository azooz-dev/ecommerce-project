<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderResource;
use App\Models\User;
use Exception;

use function App\Helpers\errorResponse;
use function App\Helpers\showAll;

class UserOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        try {
            $orders = $user->orders()->with('orderItems')->get();

            return showAll(OrderResource::collection($orders), 'orders', 200);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }
}
