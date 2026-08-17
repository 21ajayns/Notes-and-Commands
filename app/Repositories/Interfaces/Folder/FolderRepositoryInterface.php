<?php
declare(strict_types=1);

namespace App\Repositories\Interfaces\Folder;

use App\DataTransferObjects\Folder\FolderCreateDto;
use App\Models\Folder\Folder;
use Illuminate\Database\Eloquent\Collection;

interface FolderRepositoryInterface
{
    public function create(FolderCreateDto $createDto): Folder;

    public function all(?string $folderId = null, ?string $category = null): Collection;
}