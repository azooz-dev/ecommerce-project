<?php

namespace App\Services\Order;

use App\Http\Resources\Order\OrderResource;
use App\Repositories\Order\OrderRepository;

class OrderService
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index()
    {
        $orders = $this->orderRepository->index();

        return OrderResource::collection($orders);
    }

    public function show($order)
    {
        $order = $this->orderRepository->show($order);

        return new OrderResource($order);
    }

    public function create(array $data)
    {
        $order = $this->orderRepository->create($data);

        return new OrderResource($order);
    }

    public function update(array $data, $order)
    {
        $order = $this->orderRepository->update($data, $order);

        return new OrderResource($order);
    }

    public function destroy($order)
    {
        $order = $this->orderRepository->destroy($order);

        return $order;
    }
}
