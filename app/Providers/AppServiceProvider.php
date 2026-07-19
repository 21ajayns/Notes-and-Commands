<?php

namespace App\Providers;

use App\Repositories\FolderRepository;
use App\Repositories\Interfaces\FolderRepositoryInterface;
use App\Repositories\Interfaces\NoteRepositoryInterface;
use App\Repositories\NoteRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FolderRepositoryInterface::class, FolderRepository::class);
        $this->app->bind(NoteRepositoryInterface::class, NoteRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}