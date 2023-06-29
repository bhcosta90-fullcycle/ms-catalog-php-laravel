<?php

namespace App\Providers;

use App\Repositories\Eloquent\{
    CategoryRepositoryEloquent,
    GenreRepositoryEloquent
};
use BRCas\MV\Domain\Repository\{
    CategoryRepositoryInterface,
    GenreRepositoryInterface
};
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(CategoryRepositoryInterface::class, CategoryRepositoryEloquent::class);
        $this->app->singleton(GenreRepositoryInterface::class, GenreRepositoryEloquent::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
