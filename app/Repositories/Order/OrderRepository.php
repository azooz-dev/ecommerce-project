<?php

namespace App\Repositories\Order;

use App\Events\ProductOutOfStockEvent;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OrderRepository
{
    public function index()
    {
        $orders = Order::with('user')
            ->with('orderItems')
            ->get();

        return $orders;
    }

    public function show(Order $order)
    {
        return $order;
    }

    public function create(array $data)
    {
        if ($data['payment_method'] == 'cash') {
            $buyer = User::findOrFail($data['user_id']);
            $this->checkBuyerVerified($buyer);

            $order = DB::transaction(function () use ($data) {
                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'user_id' => $data['user_id'],
                    'address' => $data['address'],
                    'payment_method' => $data['payment_method'],
                    'coupon_id' => isset($data['coupon_id']) ? $data['coupon_id'] : null,
                ]);

                $order->save();
                return $order;
            });
            return $order;
        }
    }

    public function update(array $data, Order $order)
    {
        if ($order->status == Order::COMPLETED_ORDER || $order->status == Order::CANCELED_ORDER) {
            throw new HttpException(409, 'لقد تم اكتمال الطلب بالفعل مسبقاً');
        }

        $order = $order->fill($data);

        return $order;
    }

    public function destroy(Order $order)
    {
        DB::transaction(function () use ($order) {
            $order->orderItems()->delete();
            $order->delete();
        });

        return $order;
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

    protected function checkStock($product, $quantity)
    {
        if ($product->quantity < $quantity) {
            throw new HttpException(409, 'لا يوجد كمية كافية من هذا المنتج');
        }
    }
}
