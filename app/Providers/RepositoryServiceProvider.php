<?php

namespace App\Providers;

use App\Repositories\Eloquent\CastMemberRepositoryEloquent;
use App\Repositories\Eloquent\CategoryRepositoryEloquent;
use App\Repositories\Eloquent\GenreRepositoryEloquent;
use App\Repositories\Eloquent\VideoRepositoryEloquent;
use BRCas\MV\Domain\Repository\CastMemberRepositoryInterface;
use BRCas\MV\Domain\Repository\CategoryRepositoryInterface;
use BRCas\MV\Domain\Repository\GenreRepositoryInterface;
use BRCas\MV\Domain\Repository\VideoRepositoryInterface;
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
