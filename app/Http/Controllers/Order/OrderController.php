<?php

namespace App\Http\Controllers\Order;

use App\Events\ProductOutOfStockEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderStoreRequest;
use App\Http\Requests\Order\OrderUpdateRequest;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Exception;
use function App\Helpers\errorResponse;
use function App\Helpers\showAll;
use function App\Helpers\showOne;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $orders = Order::with('user')
                ->with('orderItems')
                ->get();

            $orders = OrderResource::collection($orders);
            return showAll($orders, 'orders', 200);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderStoreRequest $request)
    {
        $data = $request->validated();
        try {
            if ($data['payment_method'] == 'cash') {
                $buyer = User::findOrFail($data['user_id']);
                $this->checkBuyerVerified($buyer);

                $order = DB::transaction(function () use ($data) {
                    $order = Order::create([
                        'order_number' => Order::generateOrderNumber(),
                        'user_id' => $data['user_id'],
                        'address' => $data['address'],
                        'payment_method' => $data['payment_method'],
                    ]);

                    foreach ($data['order_items'] as $orderItem) {
                        $order = $this->processOrderItem($order, $orderItem);
                    }
                    $order->save();
                    return $order;
                });
                return showOne(new OrderResource($order), 201);
            }
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        try {
            return new OrderResource($order, 200);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderUpdateRequest $request, Order $order)
    {
        $data = $request->validated();
        try {
            if ($order->status == Order::COMPLETED_ORDER) {
                throw new HttpException(409, 'لقد تم اكتمال الطلب بالفعل مسبقاً');
            }

            DB::transaction(function () use ($data, $order) {
                $order = $order->fill($data);

                if ($data['order_items']) {
                    $existingItems = $order->orderItems()->get()->keyBy('id');

                    foreach ($data['order_items'] as $orderItem) {
                        if (isset($orderItem['id']) && $existingItems->has($orderItem['id'])) {
                            $item = $existingItems->get($orderItem['id']);
                            $item->update([
                                'quantity' => $orderItem['quantity'],
                                'price' => isset($orderItem->discount_price) ? $orderItem->discount_price : $orderItem->price,
                            ]);
                        } else {
                            $order = $this->processOrderItem($order, $orderItem);
                        }
                    }
                    $order->calculateTotalAmount();
                    $order->save();
                }
            });

            return showOne(new OrderResource($order), 200);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        try {
            DB::transaction(function () use ($order) {
                $order->orderItems()->delete();
                $order->delete();
            });
            return showAll(new OrderResource($order), 'order', 200);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    protected function checkStock($product, $quantity)
    {
        if ($product->quantity < $quantity) {
            throw new HttpException(409, 'لا يوجد كمية كافية من هذا المنتج');
        }
    }

    protected function checkBuyerVerified($buyer)
    {
        if (!$buyer->isVerified()) {
            throw new HttpException(409, 'يرجى تأكيد الحساب');
        }
    }

    protected function processOrderItem($order, $orderItem)
    {
        $product = Product::find($orderItem['product_id']);
        $this->checkStock($product, $orderItem['quantity']);

        $product->decrement('quantity', $orderItem['quantity']);
        $product->save();

        $order->orderItems()->create([
            'product_id' => $orderItem['product_id'],
            'quantity' => $orderItem['quantity'],
            'price' => isset($product->price_discount) ? $product->price_discount * $orderItem['quantity'] : $product->price * $orderItem['quantity'],
        ]);
        event(new ProductOutOfStockEvent($product));
        return $order;
    }
}
