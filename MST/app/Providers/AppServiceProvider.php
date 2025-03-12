<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Taouil API",
 *     version="1.0.0"
 * )
 */

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
