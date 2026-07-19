<?php
declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Dto\FolderDto;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Support\Collection;

interface FolderRepositoryInterface
{
    public function create(FolderDto $create, User $user, ?Folder $parent = null): Folder;

    public function getAll(User $user): Collection;
}