<?php

namespace App\Providers;

use App\Services\WhatsappService;
use Illuminate\Support\ServiceProvider;

class WhatsappServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(WhatsAppService::class, function () {
            return new WhatsappService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
