<?php

namespace App\Providers;

use App\Repositories\Interfaces\FolderRepositoryInterface;
use App\Repositories\Interfaces\NoteRepositoryInterface;
use App\Repositories\FolderRepository;
use App\Repositories\NoteRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(NoteRepositoryInterface::class, NoteRepository::class);
        $this->app->bind(FolderRepositoryInterface::class, FolderRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
