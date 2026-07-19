<?php
declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Dto\FolderDto;
use App\Models\Folder;
use Illuminate\Support\Collection;

interface FolderRepositoryInterface
{
    public function create(FolderDto $create): Folder;

    public function getAll(): Collection;
}
