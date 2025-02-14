<?php

namespace App\Http\Controllers\Order\OrderItems;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderItems\OrderItemsStoreRequest;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Order\OrderItem\OrderItemService;
use Exception;
use function App\Helpers\errorResponse;
use function App\Helpers\showAll;
use Illuminate\Support\Facades\Auth;

class OrderItemController extends Controller
{

    protected $orderItemService;

    public function __construct(OrderItemService $orderItemService)
    {
        $this->middleware('auth:sanctum');
        $this->orderItemService = $orderItemService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Order $order)
    {
        if ($this->authorize('view', $order)) {
            try {
                $orderItems = $this->orderItemService->index($order);

                return showAll($orderItems, 'orderItems', 200);
            } catch (Exception $e) {
                return errorResponse($e->getMessage(), 500);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderItemsStoreRequest $request, Order $order)
    {
        if ($this->authorize('create', OrderItem::class)) {
            $data = $request->validated();

            try {
                $orderItems = $this->orderItemService->create($data, $order);

                return showAll($orderItems, 'orderItems');
            } catch (Exception $e) {
                return errorResponse($e->getMessage(), 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order, OrderItem $orderItem)
    {
        if ($this->authorize('delete', $orderItem)) {
            try {
                $orderItems = $this->orderItemService->destroy($order, $orderItem);

                return showAll($orderItems, 'orderItems', 200);
            } catch (Exception $e) {
                return errorResponse($e->getMessage(), 500);
            }
        }
    }
}
