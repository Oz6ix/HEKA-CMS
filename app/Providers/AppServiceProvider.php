<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\PatientRepositoryInterface::class,
            \App\Repositories\Eloquent\PatientRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\StaffRepositoryInterface::class,
            \App\Repositories\Eloquent\StaffRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\InventoryRepositoryInterface::class,
            \App\Repositories\Eloquent\InventoryRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\AppointmentRepositoryInterface::class,
            \App\Repositories\Eloquent\AppointmentRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\PatientDiagnosisRepositoryInterface::class,
            \App\Repositories\Eloquent\PatientDiagnosisRepository::class
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
