<?php
declare(strict_types=1);

namespace App\Repositories\Folder;

use App\DataTransferObjects\Folder\FolderCreateDto;
use App\Models\Folder\Folder;
use App\Repositories\Interfaces\Folder\FolderRepositoryInterface;

class FolderRepository implements FolderRepositoryInterface
{
    public function create(FolderCreateDto $createDto): Folder
    {
        $folder = new Folder();
        $folder->setAttribute('name', $createDto->getName());
        $folder->setAttribute('folder_id', $createDto->getFolderId());

        $folder->save();

        return $folder;
    }
}