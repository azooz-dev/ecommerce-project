<?php

namespace App\Providers;

use App\Repositories\Category\CategoryRepository;
use App\Repositories\Order\OrderItem\OrderItemRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Product\ProductRepository;
use App\Services\Category\CategoryService;
use App\Services\Order\OrderItem\OrderItemService;
use App\Services\Order\OrderService;
use App\Services\Product\ProductService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ProductRepository::class,
            ProductRepository::class,
        );

        $this->app->bind(
            ProductService::class,
            ProductService::class,
        );

        $this->app->bind(
            OrderRepository::class,
            OrderRepository::class,
        );

        $this->app->bind(
            OrderService::class,
            OrderService::class,
        );

        $this->app->bind(
            OrderItemRepository::class,
            OrderItemRepository::class,
        );

        $this->app->bind(
            OrderItemService::class,
            OrderItemService::class,
        );

        $this->app->bind(
            CategoryRepository::class,
            CategoryRepository::class,
        );

        $this->app->bind(
            CategoryService::class,
            CategoryService::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
