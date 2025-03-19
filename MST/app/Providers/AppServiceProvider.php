<?php

namespace App\Providers;

use OpenApi\Annotations as OA;
use App\Repositories\UserRepository;
use App\Repositories\CourseRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\EnrollmentRepository;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\CourseRepositoryInterface;
use App\Repositories\EnrollmentRepositoryInterface;

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
        $this->app->bind(UserRepositoryInterface::class,UserRepository::class);  
        $this->app->bind(EnrollmentRepositoryInterface::class,EnrollmentRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
    }
    
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
