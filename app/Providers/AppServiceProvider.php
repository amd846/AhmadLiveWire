<?php

namespace App\Providers;
use App\Services\orderService;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(OrderService::class, function ($app) {
            return new OrderService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(orderService $orderService): void
    {
        $this->runOnStartup($orderService);
    }



    private function runOnStartup(orderService $orderService)
    {
        \Log::info('Application started! Running startup function.');

        
      //  $orderService->deleteThreeDays();
      //  $orderService->moreSixty();

       //orderService::deleteThreeDays();
        // Add your function logic here
    }
}
