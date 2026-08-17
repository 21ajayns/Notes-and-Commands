<?php
declare(strict_types=1);

namespace App\Repositories\Folder;

use App\DataTransferObjects\Folder\FolderCreateDto;
use App\Models\Folder\Folder;
use App\Repositories\Interfaces\Folder\FolderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class FolderRepository implements FolderRepositoryInterface
{
    public function create(FolderCreateDto $createDto): Folder
    {
        $folder = new Folder();
        $folder->setAttribute('name', $createDto->getName());
        $folder->setAttribute('category', $createDto->getCategory()->getValue());
        $folder->setAttribute('folder_id', $createDto->getFolderId());

        $folder->save();

        return $folder;
    }

    public function all(?string $folderId = null, ?string $category = null): Collection
    {
        return Folder::query()
            ->where('folder_id', $folderId)
            ->when($category !== null, fn ($query) => $query->where('category', $category))
            ->get();
    }
}
