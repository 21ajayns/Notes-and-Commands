<?php

namespace App\Providers;

use App\Repositories\Command\CommandRepository;
use App\Repositories\CommandFolder\CommandFolderRepository;
use App\Repositories\Folder\FolderRepository;
use App\Repositories\Interfaces\Command\CommandRepositoryInterface;
use App\Repositories\Interfaces\CommandFolder\CommandFolderRepositoryInterface;
use App\Repositories\Interfaces\Folder\FolderRepositoryInterface;
use App\Repositories\Interfaces\Note\NoteRepositoryInterface;
use App\Repositories\Interfaces\Task\TaskRepositoryInterface;
use App\Repositories\Interfaces\User\UserRepositoryInterface;
use App\Repositories\Note\NoteRepository;
use App\Repositories\Task\TaskRepository;
use App\Repositories\User\UserRepository;
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
        $this->app->bind(CommandRepositoryInterface::class, CommandRepository::class);
        $this->app->bind(CommandFolderRepositoryInterface::class, CommandFolderRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
