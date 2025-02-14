<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderStoreRequest;
use App\Http\Requests\Order\OrderUpdateRequest;
use App\Models\Order;
use App\Services\Order\OrderService;
use Exception;
use function App\Helpers\errorResponse;
use function App\Helpers\showAll;
use function App\Helpers\showOne;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->middleware('auth:sanctum');
        $this->orderService = $orderService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if ($this->authorize('viewAny', Order::class)) {
            try {
                $orders = $this->orderService->index();

                return showAll($orders, 'orders', 200);
            } catch (Exception $e) {
                return errorResponse($e->getMessage(), 500);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderStoreRequest $request)
    {
        if ($this->authorize('create', Order::class)) {
            $data = $request->validated();
            try {
                $order = $this->orderService->create($data);

                return showOne($order, 201);
            } catch (Exception $e) {
                return errorResponse($e->getMessage(), 500);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        if ($this->authorize('view', $order)) {
            try {
                $order = $this->orderService->show($order);

                return showOne($order, 200);
            } catch (Exception $e) {
                return errorResponse($e->getMessage(), 500);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderUpdateRequest $request, Order $order)
    {
        if ($this->authorize('update', $order)) {
            $data = $request->validated();
            try {
                $order = $this->orderService->update($data, $order);

                return showOne($order, 200);
            } catch (Exception $e) {
                return errorResponse($e->getMessage(), 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        if ($this->authorize('delete', $order)) {
            try {
                $order = $this->orderService->destroy($order);

                return showAll($order, 'order', 200);
            } catch (Exception $e) {
                return errorResponse($e->getMessage(), 500);
            }
        }
    }
}
