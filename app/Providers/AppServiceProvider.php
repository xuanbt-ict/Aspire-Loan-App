<?php

namespace App\Providers;

use App\Repositories\Eloquent\LoanRepository;
use App\Repositories\Eloquent\PaymentRepository;
use App\Repositories\LoanRepositoryInterface;
use App\Repositories\PaymentRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRepositories();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    protected function registerRepositories()
    {
        $this->app->bind(LoanRepositoryInterface::class, LoanRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
    }
}
