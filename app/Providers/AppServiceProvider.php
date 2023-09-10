<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Query\Builder;

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
        Builder::macro('whereLike', function ($column, $value) {
            return $this->where($column, 'LIKE', '%' . $value . '%');
        });

        Builder::macro('whereMin', function ($column, $value) {
            return $this->where($column, '>=', $value);
        });

        Builder::macro('whereMax', function ($column, $value) {
            return $this->where($column, '<=', $value);
        });
    }
}
