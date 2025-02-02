<?php

namespace App\Repositories\Order\OrderItem;

use App\Events\ProductOutOfStockEvent;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OrderItemRepository
{
    public function index(Order $order)
    {
        $orderItems = $order->orderItems()->get();

        return $orderItems;
    }

    public function create(array $data, Order $order)
    {
        if ($order->status == Order::COMPLETED_ORDER || $order->status == Order::CANCELED_ORDER) {
            throw new HttpException(409, 'لقد تم اكتمال الطلب بالفعل مسبقاً');
        }

        DB::transaction(function () use ($data, $order) {
            $existingItems = $order->orderItems()->get();

            foreach ($data['order_items'] as $orderItem) {
                if (isset($orderItem['product_id']) && $existingItems->contains('product_id', $orderItem['product_id'])) {
                    $item = $existingItems->where('product_id', $orderItem['product_id'])->first();

                    $this->checkStock($item->product, $orderItem['quantity']);
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

        return $order->orderItems;
    }

    public function destroy(Order $order, OrderItem $orderItem)
    {
        if ($order->status == Order::COMPLETED_ORDER || $order->status == Order::CANCELED_ORDER) {
            throw new HttpException(409, 'لا يمكن حذف الطلب بعد اكتماله');
        }

        $orderItem->product->increment('quantity', $orderItem->quantity);
        $orderItem->delete();
        $order->calculateTotalAmount();

        return $order->orderItems;
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
