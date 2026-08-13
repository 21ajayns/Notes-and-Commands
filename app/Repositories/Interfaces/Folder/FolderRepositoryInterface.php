<?php
declare(strict_types=1);

namespace App\Repositories\Interfaces\Folder;

use App\DataTransferObjects\Folder\FolderCreateDto;
use App\Models\Folder\Folder;

interface FolderRepositoryInterface
{
    public function create(FolderCreateDto $createDto): Folder;
}