<?php

namespace App\Providers;

use App\Repositories\AttachmentRepository;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\AttachmentRepositoryInterface;
use App\Repositories\Interfaces\EloquentRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(AttachmentRepositoryInterface::class, AttachmentRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
