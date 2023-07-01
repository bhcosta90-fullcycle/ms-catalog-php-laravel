<?php

namespace App\Providers;

use App\Repositories\Eloquent\{
    CastMemberRepositoryEloquent,
    CategoryRepositoryEloquent,
    GenreRepositoryEloquent,
    VideoRepositoryEloquent
};
use BRCas\MV\Domain\Repository\{
    CastMemberRepositoryInterface,
    CategoryRepositoryInterface,
    GenreRepositoryInterface,
    VideoRepositoryInterface
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
        $this->app->singleton(CastMemberRepositoryInterface::class, CastMemberRepositoryEloquent::class);
        $this->app->singleton(VideoRepositoryInterface::class, VideoRepositoryEloquent::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
