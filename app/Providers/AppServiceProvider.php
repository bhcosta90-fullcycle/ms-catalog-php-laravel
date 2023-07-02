<?php

namespace App\Providers;

use App\Events\VideoEventManager;
use App\Services\AMQP\AMQPInterface;
use App\Services\AMQP\PhpAmqplib;
use App\Services\Storage\FileStorage;
use App\Transactions\DatabaseTransaction;
use BRCas\CA\UseCase\DatabaseTransactionInterface;
use BRCas\CA\UseCase\FileStorageInterface;
use BRCas\MV\UseCases\Video\Interfaces\VideoEventManagerInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        $this->app->bind(DatabaseTransactionInterface::class, DatabaseTransaction::class);
        $this->app->singleton(FileStorageInterface::class, FileStorage::class);
        $this->app->singleton(VideoEventManagerInterface::class, VideoEventManager::class);

        $this->app->bind(AMQPInterface::class, PhpAmqplib::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
