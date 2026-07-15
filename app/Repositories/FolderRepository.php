<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Dto\FolderDto;
use App\Models\Folder;
use App\Repositories\Contracts\FolderRepositoryInterface;
use Illuminate\Support\Collection;

class FolderRepository implements FolderRepositoryInterface
{
    public function create(FolderDto $create): Folder
    {
        $folder = new Folder();

        $folder->setAttribute('name', $create->getName());
        $folder->setAttribute('image', $create->getImage());

        $folder->save();

        return $folder;
    }

    public function getAll(): Collection
    {
        return (new Folder())->all();
    }
}
