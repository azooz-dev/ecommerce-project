<?php

namespace App\Http\Controllers\Order\OrderItems;

use App\Events\ProductOutOfStockEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderItems\OrderItemsStoreRequest;
use App\Http\Resources\Order\OrderItems\OrderItemsResource;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Exception;
use function App\Helpers\errorResponse;
use function App\Helpers\showAll;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Order $order)
    {
        try {
            $orderItems = $order->orderItems()->get();
            $orderItems = OrderItemsResource::collection($orderItems);

            return showAll($orderItems, 'orderItems', 200);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderItemsStoreRequest $request, Order $order)
    {
        $data = $request->validated();

        try {
            if ($order->status == Order::COMPLETED_ORDER) {
                throw new HttpException(409, 'لقد تم اكتمال الطلب بالفعل مسبقاً');
            }

            DB::transaction(function () use ($data, $order) {
                $existingItems = $order->orderItems()->get();

                foreach ($data['order_items'] as $orderItem) {
                    if (isset($orderItem['product_id']) && $existingItems->contains('product_id', $orderItem['product_id'])) {
                        $item = $existingItems->where('product_id', $orderItem['product_id'])->first();
                        $item->update([
                            'quantity' => $orderItem['quantity'],
                            'price' => isset($item->product->discount_price) ? $item->product->discount_price * $orderItem['quantity'] : $item->product->price * $orderItem['quantity'],
                        ]);
                    } else {
                        $order = $this->processOrderItem($order, $orderItem);
                    }
                }
                $order->calculateTotalAmount();
                $order->save();
            });

            return showAll(OrderItemsResource::collection($order->orderItems), 'orderItems');
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order, OrderItem $orderItem)
    {
        try {
            if ($order->status == Order::COMPLETED_ORDER) {
                throw new HttpException(409, 'لا يمكن حذف الطلب بعد اكتماله');
            }

            $orderItem->product->increment('quantity', $orderItem->quantity);
            $orderItem->delete();
            $order->calculateTotalAmount();
            return showAll(OrderItemsResource::collection($order->orderItems), 'orderItems', 200);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    protected function checkStock($product, $quantity)
    {
        if ($product->quantity < $quantity) {
            throw new HttpException(409, $product->id . ' :لا يوجد كمية كافية من رقم المنتج');
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
