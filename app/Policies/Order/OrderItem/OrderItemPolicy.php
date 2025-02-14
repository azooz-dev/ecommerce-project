<?php

namespace App\Policies\Order\OrderItem;

use App\Models\OrderItem;
use App\Models\User;

class OrderItemPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OrderItem $orderItem): bool
    {
        return $user->id == $orderItem->order->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, OrderItem $orderItem): bool
    {
        return $user->id == $orderItem->order->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OrderItem $orderItem): bool
    {
        return $user->id == $orderItem->order->user_id || $user->tokenCan('delete');
    }
}
