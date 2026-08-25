<?php

namespace App\Providers;

use App\Repositories\Folder\FolderRepository;
use App\Repositories\Interfaces\Folder\FolderRepositoryInterface;
use App\Repositories\Interfaces\Note\NoteRepositoryInterface;
use App\Repositories\Interfaces\Task\TaskRepositoryInterface;
use App\Repositories\Note\NoteRepository;
use App\Repositories\Task\TaskRepository;
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
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
