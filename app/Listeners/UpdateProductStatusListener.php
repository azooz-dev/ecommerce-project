<?php

namespace App\Listeners;

use App\Events\ProductOutOfStockEvent;
use App\Models\Product;

class UpdateProductStatusListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ProductOutOfStockEvent $event): void
    {
        if ($event->product->quantity == 0 && $event->product->isAvailable()) {
            $event->product->status = Product::UNAVAILABLE_PRODUCT;
            $event->product->save();
        }
    }
}
