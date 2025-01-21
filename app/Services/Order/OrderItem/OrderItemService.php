<?php

namespace App\Services\Order\OrderItem;

use App\Http\Resources\Order\OrderItems\OrderItemsResource;
use App\Repositories\Order\OrderItem\OrderItemRepository;

class OrderItemService
{
    protected $orderItemRepository;

    public function __construct(OrderItemRepository $orderItemRepository)
    {
        $this->orderItemRepository = $orderItemRepository;
    }

    public function index($order)
    {
        $orderItems = $this->orderItemRepository->index($order);

        return OrderItemsResource::collection($orderItems);
    }

    public function create(array $data, $order)
    {
        $orderItems = $this->orderItemRepository->create($data, $order);

        return OrderItemsResource::collection($orderItems);
    }

    public function destroy($order, $orderItem)
    {
        $orderItems = $this->orderItemRepository->destroy($order, $orderItem);

        return OrderItemsResource::collection($orderItems);
    }
}
