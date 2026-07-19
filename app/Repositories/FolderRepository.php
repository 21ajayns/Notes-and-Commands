<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Dto\FolderDto;
use App\Models\Folder;
use App\Models\User;
use App\Repositories\Interfaces\FolderRepositoryInterface;
use Illuminate\Support\Collection;

class FolderRepository implements FolderRepositoryInterface
{
    public function create(FolderDto $create, User $user, ?Folder $parent = null): Folder
    {
        $folder = new Folder();

        $folder->setAttribute('name', $create->getName());
        $folder->setAttribute('parent_id', $create->getParentId());
        $folder->setAttribute('image', $create->getImage());

        $folder->user()->associate($user);

        if ($parent) {
            $folder->parent()->associate($parent);
        }

        $folder->save();

        return $folder;
    }

    public function getAll(User $user): Collection
    {
        return Folder::with(['parent', 'children', 'notes'])->where('user_id', $user->id)->get();
    }
}